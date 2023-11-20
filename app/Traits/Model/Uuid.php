<?php

namespace App\Traits\Model;

use Exception;
use Ramsey\Uuid\Uuid as Generator;

trait Uuid
{

    public function __construct()
    {
        parent::__construct();
        $this->incrementing = false;
        $this->keyType = 'string';
    }


    protected static function bootUuid()
    {
        static::creating(function ($model) {
            try {
                $model->setId($model);
                $model->incrementing = false;
                $model->keyType = 'string';
            } catch (Exception $e) {
                abort(500, $e->getMessage());
            }
        });
    }

    private function setId($model)
    {
        $key = $this->primaryKey ?? 'id';
        $model->$key = Generator::uuid4()->toString();
    }
}
