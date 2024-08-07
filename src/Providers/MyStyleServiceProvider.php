<?php

namespace Botble\MyStyle\Providers;

use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\MyStyle\Facades\MyStyleHelper;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class MyStyleServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        AliasLoader::getInstance()->alias('MyStyleHelper', MyStyleHelper::class);
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/my-style')
            ->loadAndPublishConfigurations(['permissions', 'config'])
            ->loadAndPublishViews();

        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);
        });
    }
}
