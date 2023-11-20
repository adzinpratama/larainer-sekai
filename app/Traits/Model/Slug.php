<?php

namespace App\Traits\Model;

use illuminate\Support\Str;

trait Slug
{

    /**
     * booting trait slug
     *  */
    public static function bootSlug()
    {
        static::creating(function ($model) {
            $model->setSlug($model);
        });

        static::updating(function ($model) {
            $model->setSlug($model);
        });
    }

    /**
     * set slug from model
     * @param $model
     *  */
    private function setSlug($model)
    {
        $name = $this->slugTarget ?? 'name';
        $model->slug = $model->createSlug($model->$name);
    }

    /**
     * generating slug from model
     * @param string $name
     *  */
    private function createSlug($name)
    {
        $column = $this->slugTarget ?? 'name';
        if (static::whereSlug($slug = Str::slug($name))->exists()) {
            if (isset($this->slug)) return $slug;
            $max = static::where($column, $name)->latest('id')->skip(1)->value('slug');

            if (isset($max[-1]) && is_numeric($max[-1])) {

                return preg_replace_callback('/(\d+)$/', function ($mathces) {

                    return $mathces[1] + 1;
                }, $max);
            }
            return "{$slug}-2";
        }
        return $slug;
    }
}
