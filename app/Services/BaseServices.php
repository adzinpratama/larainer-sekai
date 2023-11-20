<?php

namespace App\Services;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class BaseServices
{
    protected $_db;
    protected $_perpage;
    protected $primaryKey = 'id';

    protected $imageKey;
    protected $imagePath;
    protected $imageSubPath;
    protected $imageOptions;

    protected $thumbnailKey;
    protected $thumbnailOption = [
        'width' => 200,
        'height' => 200
    ];

    protected $filterNull = true;

    protected $searchKey = 'name';
    protected $searchParamName = 'search';
    protected $withSearch = true;

    protected $relationKey = [];
    protected $transProgress;


    protected static $model = NULL;

    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->_perpage = env('DATA_PERPAGE', 20);
        if (static::$model) $this->_db = new static::$model;
    }

    /**
     * accessing class via static
     *  to access other function */
    public static function ini()
    {
        return new static();
    }

    /* Handler query in oloquent */
    public function query($request)
    {
        return $this->_db->when(
            $this->withSearch,
            fn ($q) => $this->whenSearch($q, $this->searchKey, $this->searchParamName)
        )->when(!empty($this->relationKey), fn ($q) => $q->with($this->relationKey));
    }

    /**
     * when search query
     * @param object $query,
     * @param string $key
     * @param string $param
     *  */
    public function whenSearch(
        object $query,
        string $key = 'name',
        string $param = 'search'
    ) {
        return $query->when(
            request()->has($param) && request()->$param,
            fn ($q) => $q->where($key, 'LIKE', "%" . request()->$param . "%")
        );
    }

    /* get data with paginate */
    public function getPaginate($request = null)
    {
        return $this->query($request)->orderByDesc('created_at')->paginate($this->_perpage);
    }

    /**
     * get data paginate with callable function
     * @param object $request
     * @param callable $callable
     * @return object
     *  */
    public function getPagiCall(
        object $request = null,
        callable $callable = null,
        bool $latest = true
    ) {
        return $this->query($request)
            ->when($latest, fn ($q) => $q->orderByDesc('created_at'))
            ->when($callable && is_callable($callable), $callable)
            ->paginate($this->_perpage);
    }

    /* get data result object */
    public function getData(
        $request = null,
        bool $lastUpdate = true,
        callable $callable = null,
        int|null $limit = null
    ) {
        return $this->query($request)->when($lastUpdate, function ($query) {
            return $query->latest();
        })->when($callable, $callable)
            ->when($limit, fn ($q) => $q->limit($limit))
            ->get();
    }

    /* get data result Array */
    public function getArray($request = null, $lastUpdate = true)
    {
        return $this->getData($request, $lastUpdate)->toArray();
    }

    /* get one data by id */
    public function getOneById($id)
    {
        return $this->_db->where($this->primaryKey, $id)->first();
    }

    /**
     * get one by id with custom select
     * and callable
     * @param string $id,
     * @param string|array $select
     * @param callable $callable
     * @return object|null
     *  */
    public function getById(
        string $id,
        string|array $select = '*',
        callable $callable = NULL
    ) {
        return $this->_db->where($this->primaryKey, $id)
            ->when($callable && is_callable($callable), $callable)
            ->first($select);
    }

    public function getBySlug(
        string $slug,
        string|array $select = '*',
        callable $callable = NULL
    ) {
        return $this->_db->whereSlug($slug)
            ->when($callable && is_callable($callable), $callable)
            ->first($select);
    }

    /**
     * get one data by slug
     * @param string $slug
     * @return object|null
     *  */
    public function getOneBySlug($slug)
    {
        return $this->_db->whereSlug($slug)->first();
    }



    /**
     * Method getWhereIn
     *
     * @param array $status [explicite description]
     * @param string $column [explicite description]
     * @param bool $paginate [explicite description]
     * @param string $method [explicite description]
     * @param callable $callable [explicite description]
     * @param bool $orderLatest [explicite description]
     *
     * @return object
     */
    public function getWhereIn(
        array $status,
        string $column,
        bool $paginate = false,
        string $method = 'get',
        callable $callable = null,
        bool $orderLatest = true
    ): object {
        return static::$model::whereIn($column, $status)
            ->when($callable, $callable)
            ->when($orderLatest, fn ($q) => $q->orderByDesc('created_at'))
            ->when(
                $paginate,
                fn ($q) => $q->paginate($this->_perpage),
                fn ($q) => $q->{$method}()
            );
    }

    /**
     * get count data
     * @return int
     *  */
    public function getCountData(callable $callable = null)
    {
        return $this->query(null)
            ->when($callable, $callable)
            ->pluck($this->primaryKey)->count();
    }

    /* handler save new Data */
    public function storeHandle($data)
    {
        if ($this->imageSubPath) $this->imagePath .= Str::slug($data[$this->imageSubPath]);
        if ($this->filterNull) $data = array_filter($data);
        if ($this->imageKey) $data =  $this->handlerImage($data);
        $storing = $this->_db->create($data);
        return $this->response(true, 'data creating', $storing);
    }

    /**
     * handler update data
     *
     * @param array $data [explicite description]
     * @param string $id [explicite description]
     *
     * @return object
     */
    public function updateHandle(array $data, string $id): object
    {
        if ($this->filterNull) array_filter($data);
        if ($this->imageKey) return $this->updateWithImage($data, $id);
        $updating = tap($this->getOneById($id))->update($data);
        return $this->response(true, 'data updating', $updating);
    }

    /**
     * handler removing data
     *
     * @param string $id [explicite description]
     *
     * @return object
     */
    public function removeHandle(string $id): object
    {
        if ($this->imageKey) return $this->removeWithImage($id);
        $data = $this->getOneById($id);
        $this->beforeRemove($data);
        return $this->response($data->delete(), 'data deleting');
    }

    /* handler removing image */
    public function removeImage($image)
    {
        return File::delete(public_path($image));
    }

    /**
     * Method uploadImage
     * handler image upload
     *
     * @param object|array $request [explicite description]
     * @param string $key [explicite description]
     * @param string $path [explicite description]
     *
     * @return string
     */
    public function uploadImage(
        object|array $request,
        string $key,
        string $path
    ): string {
        return $this->uploadImageHandle($request, $key, 'images/' . $path);
    }

    /**
     * Method uploadFile
     *
     * @param object|array $request [explicite description]
     * @param string $key [explicite description]
     * @param string $path [explicite description]
     *
     * @return string
     */
    public function uploadFile(
        object|array $request,
        string $key,
        string $path
    ): string {
        return $this->uploadHandler($request, $key, 'uploads/' . $path);
    }

    /**
     * Method uploadImageHandle
     *handler image upload
     * @param object|array $request [explicite description]
     * @param string $key [explicite description]
     * @param string $path [explicite description]
     *
     * @return string
     */
    public function uploadImageHandle(
        object|array $request,
        string $key,
        string $path
    ): string {
        $toUpload = Image::make($request[$key]);
        if (isset($this->imageOptions[$key])) {
            foreach ($this->imageOptions[$key] as $name => $option) {
                if ($name == 'resize') {
                    $toUpload->resize($option['width'], $option['height']);
                }
            }
        }
        $toUpload = $toUpload->encode('webp', 75);
        $file = time() . rand(100, 999) . '.webp';
        $store = Storage::disk('public')->put("$path/$file", $toUpload->__toString());
        return $store ? "$path/$file" : null;
    }

    /**
     * Method uploadHandler
     *
     * @param object|array $request [explicite description]
     * @param string $key [explicite description]
     * @param string $path [explicite description]
     *
     * @return string
     */
    public function uploadHandler(
        object|array $request,
        string $key,
        string $path
    ): string {
        // $request[$key]->move(public_path($path), $file);
        $toUpload = $request[$key];
        $file = time() . rand(100, 999) . '.' . $toUpload->extension();
        $toUpload->storeAs($path, $file);
        return "$path/$file";
    }

    public function uploadThumbnail(string $source, $key, $path)
    {
        $thumb = 'thumbnail/' . $path;
        $file = str_replace("images/", "", $source);

        return $this->imageResizeHandle(
            $source,
            $thumb . '/' . $file,
            $this->thumbnailKey[$key]['width'] ?? $this->thumbnailOption['width'],
            $this->thumbnailKey[$key]['height'] ?? $this->thumbnailOption['height']
        );
    }

    public function imageResizeHandle(
        string $source,
        string $target,
        string $width,
        string $height
    ) {
        Image::make($source)
            ->resize($width, $height)
            ->save(public_path($target));
        return $target;
    }

    /* handler upload image from api */
    public function base64ImageSave($image, $path)
    {
        // check if image is valid base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
            // take out base64 encode text without mimeType
            $image = substr($image, strpos($image, ',') + 1);

            // get file extension
            $type = strtolower($type[1]);
            // check if file is image
            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                throw new \Exception('invalid image type');
            }
            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);

            if ($image === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('dit not match data URI with image data');
        }
        $dir = 'images/' . $path . '/';
        $file = Str::random() . '.' . $type;
        $absolute_path = public_path($dir);
        $relative_path = $dir . $file;
        if (!File::exists($absolute_path)) {
            File::makeDirectory($absolute_path, 0755, true);
        }
        file_put_contents($relative_path, $image);
        return $relative_path;
    }

    /**
     * handle upload image from $imagekey
     *  */
    public function handlerImage($data)
    {
        $imageKey = array_intersect_key($data, array_flip($this->imageKey));
        foreach ($imageKey as $key => $value) {
            if ($value) {
                $data[$key] = $this->uploadImage($data, $key, $this->imagePath);
                if (isset($thumbnailKey[$key])) {
                    $thumbnail = $this->uploadThumbnail($data[$key], $key, $this->imagePath);
                    if (isset($this->thumbnailKey[$key]['save'])) $data[$this->thumbnailKey[$key]['save']] = $thumbnail;
                }
            }
        }
        return $data;
    }

    /**
     * handle update data with image
     * @param array $data
     * @param string $id
     *  */
    public function updateWithImage($data, $id)
    {
        $old = $this->getOneById($id);
        if ($imageKey = array_intersect_key($data, array_flip($this->imageKey))) {
            $data = $this->handlerImage($data);
            foreach ($imageKey as $key => $value) {
                if ($value) $this->removeImage($old->$key);
                else if (array_key_exists($key, $data)) unset($data[$key]);
            }
        }
        $res = tap($old)->update($data);
        return $this->response(true, 'data updated', ['data' => $res]);
    }

    /**
     * remove data with image
     * @param string $id
     *  */
    public function removeWithImage($id)
    {
        $data = $this->getOneById($id);
        $this->beforeRemove($data);
        foreach ($this->imageKey as $key) {
            if ($data->$key) $this->removeImage($data->$key);
        }
        return $data->delete();
    }

    public function beforeRemove(object $query)
    {
        return $query;
    }


    /**
     * Method response
     * @param bool $success
     * @param string $message
     * @param array|object|null $data
     * @param array|object|null $errors
     * @param int $code
     * @return object
     */
    public function response(
        bool $success,
        string $message = '',
        array|object $data = [],
        array|object $errors = [],
        int $code = 200
    ): object {
        return (object)[
            'success' => $success,
            'message' => $message,
            'code' => $code,
            'data' => $data,
            'errors' => $errors
        ];
    }

    /**
     * Method invalidResponse
     *
     * @param array $messages [explicite description]
     *
     * @return object
     */
    public function invalidResponse(array $messages): object
    {
        $message = collect($messages)->first();

        return $this->response(
            false,
            is_array($message) ? $message[0] : $message,
            errors: $messages,
            code: 422
        );
    }

    /**
     * Method createOrUpdate
     *
     * @param array $data [explicite description]
     *
     * @return object
     */
    public function createOrUpdate(array $data): object
    {
        if (
            isset($data['id']) && $data['id']
        ) return $this->updateHandle(
            $data,
            $data['id']
        );
        return $this->storeHandle($data);
    }

    /**
     * dbtransaction
     *
     * @param  Closure $callable
     * @param  callable $errors
     * @return object
     */
    public function dbtransaction(Closure $callable, callable $errors = null): object
    {
        $this->transProgress = collect();
        DB::beginTransaction();
        try {
            return $callable($this);
        } catch (\Throwable $th) {
            $this->transProgress['errors'] = $th;
            if (is_callable($errors)) $errors($this);
            DB::rollBack();
            return $this->response(false, $th->getMessage(), [
                'code' => $th->getCode() != 0 ? $th->getCode() :  500
            ]);
        } finally {
            DB::commit();
        }
    }

    /**
     * dbcommit
     *
     * @param  string $key
     * @param  mixed $value
     * @return mixed
     */
    public function dbcommit(string $key, mixed $value)
    {
        if (!isset($this->transProgress[$key])) $this->transProgress[$key] = collect();
        return $this->transProgress[$key]->push($value);
    }
}
