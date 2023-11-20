<?php

namespace App\Traits\Migration;

trait UserStamp
{

    protected static function stamp($table)
    {
        $table->foreignUuid('created_by')
            ->nullable()
            ->constrained()
            ->references('id')
            ->on('users')
            ->onDelete('set null');
        $table->foreignUuid('updated_by')
            ->nullable()
            ->constrained()
            ->references('id')
            ->on('users')
            ->onDelete('set null');
    }
}
