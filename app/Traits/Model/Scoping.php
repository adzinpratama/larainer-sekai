<?php

namespace App\Traits\Model;

use Illuminate\Support\Facades\File;

trait Scoping
{

    public static function bootScoping()
    {
        static::deleted(function ($model) {
            $deleteOnRemove = $model->deleteOnRemove ?? false;
            $fileNameKey =  $model->fileNameKey ?? ['file'];
            if (gettype($fileNameKey) == 'string') $fileNameKey = [$fileNameKey];

            if ($deleteOnRemove) collect($fileNameKey)
                ->each(fn ($item) => $model->removeFile($model->$item));
        });
    }

    /* handler removing image */
    public function scopeRemoveFile($query, string $filepath)
    {
        File::delete(public_path($filepath));
        return $this;
    }

    /* handler searching by default column search is name */
    public function scopeSearchField(
        $query,
        string $name = null,
        string $column = 'name'
    ) {
        $search = $name ?? request()->search;
        return $query->where($column, 'ILIKE', '%' . $search . '%');
    }
}
