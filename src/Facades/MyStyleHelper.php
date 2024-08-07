<?php

namespace Botble\MyStyle\Facades;

use Botble\MyStyle\MyStyleHelper as BaseMyStyleHelper;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Botble\MyStyle\MyStyleHelper registerModule($model)
 * @method static array supportedModels()
 * @method static bool isSupportedModel(string $model)
 * @method static \Botble\MyStyle\MyStyleHelper unregisterModule(string $model)
 * @method static \Botble\MyStyle\MyStyleHelper setConfig(array $config)
 * @method static mixed config(string|null $key = null, $default = null)
 *
 * @see \Botble\MyStyle\MyStyleHelper
 */
class MyStyleHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BaseMyStyleHelper::class;
    }
}
