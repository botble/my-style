<?php

namespace Botble\MyStyle;

use Illuminate\Support\Arr;

class MyStyleHelper
{
    private array $options;

    public function __construct()
    {
        $this->options = config('plugins.my-style.config', []);
    }

    public function registerModule($model): self
    {
        if (! is_array($model)) {
            $model = [$model];
        }

        $supported = array_merge($this->supportedModels(), $model);

        config(['plugins.my-style.config.supported' => $supported]);

        $this->options['supported'] = $supported;

        return $this;
    }

    public function supportedModels(): array
    {
        return config('plugins.my-style.config.supported', []);
    }

    public function isSupportedModel(string $model): bool
    {
        return in_array($model, $this->supportedModels());
    }

    public function unregisterModule(string $model): self
    {
        $supported = $this->supportedModels();

        if (($key = array_search($model, $supported)) !== false) {
            unset($supported[$key]);
        }

        config(['plugins.my-style.config.supported' => $supported]);

        $this->options['supported'] = $supported;

        return $this;
    }

    public function setConfig(array $config): self
    {
        $options = array_merge($this->options, $config);

        config(['plugins.my-style.config' => $options]);

        $this->options = $options;

        return $this;
    }

    public function config(?string $key = null, $default = null)
    {
        $options = $this->options;

        if ($key) {
            $options = Arr::get($options, $key, $default);
        }

        return $options;
    }
}
