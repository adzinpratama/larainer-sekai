<?php

namespace App\Traits\Model;

use App\Models\User;

trait UserStamp
{

    public static function bootUserStamp()
    {
        // updating created_by and updated_by when model is created
        static::creating(function ($model) {
            $user_id = auth()->user()->id;
            if (!$model->isDirty('created_by')) {
                $model->created_by =  $user_id;
            }
            if (!$model->isDirty('updated_by')) {
                $model->updated_by =  $user_id;
            }
        });

        // updating updated_by when model is updated
        static::updating(function ($model) {
            $user_id = auth()->user()->id;

            if (!$model->isDirty('updated_by')) {
                $model->updated_by =  $user_id;
            }
        });

        static::retrieved(function ($model) {
            $model->fillable = array_merge(
                $model->fillable,
                array_diff(['created_by', 'updated_by'], $model->fillable)
            );
        });
    }

    /* Defining Created_by Attributes */
    public function createdBy()
    {
        return static::belongsTo(User::class, 'created_by', 'id');
    }

    /* Defining Updated_by Attributes */
    public function updatedBy()
    {
        return static::belongsTo(User::class, 'updated_by', 'id');
    }

    /* Defining approved_by Attributes */
    public function approvedBy()
    {
        return static::belongsTo(User::class, 'approved_by', 'id');
    }

    /* Getting active Attributes */
    public function getActiveAttribute($value)
    {
        return $value ? 'Active' : 'Non Active';
    }

    /* Setting active Attributes */
    public function setActiveAttribute($value)
    {
        return $value == 'active' ? '1' : '0';
    }
}
