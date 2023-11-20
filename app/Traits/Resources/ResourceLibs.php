<?php

namespace App\Traits\Resources;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

trait ResourceLibs
{
    /**
     *  Handle to hidden some key in resource
     * `@param array $data
     *  @return array
     *  */
    public function toDiff(array $data): array
    {
        if (empty($data)) return $data;
        return array_diff_key($data, array_flip(static::$hidden ?? $this->hidden ?? array()));
    }

    public function arrayOnly(array $array, array $toShow)
    {
        return array_intersect_key($array, array_flip($toShow));
    }

    public function showOnly(array $toShow): array
    {
        return array_intersect_key(static::toArray($this->request), array_flip($toShow));
    }

    public function toHidden(array|string $hiddens)
    {
        if (is_array($hiddens)) static::$hidden = [...static::$hidden, ...$hiddens];
        if (!is_array($hiddens)) $this->$hiddens = false;
        else foreach ($hiddens as $key => $hidden) {
            if (is_numeric($key)) $this->$hidden = false;
            else $this->$key = !$hidden;
        }
        return static::toArray($this->request);
    }

    public function toShow(array $toShow)
    {
        self::showing($toShow);
        return $this->toArray($this->request ?? null);
    }

    public static function collectWithHidden(array|object $resource, ?array $hidden)
    {
        static::$hidden = [...static::$hidden, ...$hidden];
        return static::collection($resource);
    }

    public static function collectWithShow(array|object $resource, ?array $toShow)
    {
        static::showing($toShow);
        return static::collection($resource);
    }

    public static function showFromHide(array $toShow)
    {
        // array_filter(static::$hidden, function ($e) use ($toShow) {
        //     return !in_array($e, $toShow);
        // });
        foreach ($toShow as $key => $value) {
            $field = array_search($value, static::$hidden);
            array_splice(static::$hidden, $field, 1);
        }
        return new self(static::$hidden);
    }

    public static function collectHideShow(
        $collection,
        array $toShow = [],
        array $toHide = []
    ) {
        // static::$hidden = [...static::$hidden, ...$toHide];
        self::hidden($toHide);
        self::showing($toShow);
        return static::collection($collection);
    }

    public static function hidden(array $toHide)
    {
        return static::$hidden = [...static::$hidden, ...$toHide];
    }

    public static function showing(array $toShow)
    {
        foreach ($toShow as $key => $value) {
            $field = array_search($value, static::$hidden);
            array_splice(static::$hidden, $field, 1);
        }
        return static::$hidden;
    }

    public function isHidden($name)
    {
        return in_array($name, static::$hidden);
    }

    public function urlTo($url)
    {
        return $this->when($url, URL::to($url), null);
    }
}
