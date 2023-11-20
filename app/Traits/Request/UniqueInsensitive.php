<?php

namespace App\Traits\Request;

use Illuminate\Support\Str;

trait UniqueInsensitive
{

    public function inRules()
    {
        $column =  $this->inColumn ?? 'slug';
        return [
            'slug' => [
                $this->id
                    ? 'unique:' . $this->table . ',' . $column . ',' . $this->id . ',id'
                    : 'unique:' . $this->table . ',' . $column
            ]
        ];
    }

    public function messages()
    {
        $message = $this->inMessage && count($this->inMessage) > 0 ? $this->inMessage : [
            'slug.unique' => 'Data sudah ada'
        ];
        return [...$message];
    }

    public function inMerge()
    {
        $target = $this->inColumnTarget ? $this->{$this->inColumnTarget} : $this->name;
        $this->merge([
            'slug' => Str::slug($target)
        ]);
    }

    public function prepareForValidation(): void
    {
        $this->inMerge();
    }
}
