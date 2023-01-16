<?php

namespace App\Models;

abstract class AbstractGreenHorseModel extends \Illuminate\Database\Eloquent\Model
{
    public static function getConf(string $key) {
        return config('greenhorse.keys.' . $key, $key);
    }

    public function getModelStorageKey() :string {
        $storageKey = static::getConf(static::class);
        return str_ireplace('{id}', $this->getKey(), $storageKey);
    }

    public static function getStorageKeyById($id):string
    {
        $storageKey = static::getConf(static::class);
        return str_ireplace('{id}', $id, $storageKey);
    }
}
