<?php

namespace Botble\MyStyle\Providers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\MetaBox;
use Botble\Base\Models\BaseModel;
use Botble\MyStyle\Facades\MyStyleHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_action(BASE_ACTION_META_BOXES, [$this, 'addMyStyleField'], 50, 2);
        add_action(BASE_ACTION_AFTER_CREATE_CONTENT, [$this, 'saveFieldsInFormScreen'], 75, 3);
        add_action(BASE_ACTION_AFTER_UPDATE_CONTENT, [$this, 'saveFieldsInFormScreen'], 75, 3);
        add_action(BASE_ACTION_AFTER_DELETE_CONTENT, [$this, 'deleteFields'], 75, 3);

        // embed your css to article
        add_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, [$this, 'embedMyStyles'], 1001, 2);
    }

    public function deleteFields(string $screen, Request $request, BaseModel $object): void
    {
        if (
            MyStyleHelper::isSupportedModel(get_class($object))
            && Auth::user()->hasPermission('my-style.root')
        ) {
            $fileName = $this->fileName($object);
            $file = $this->file($fileName);

            if (File::exists($file)) {
                File::delete($file);
            }
        }
    }

    public function addMyStyleField(string $context, BaseModel $object): void
    {
        if (
            MyStyleHelper::isSupportedModel(get_class($object))
            && Auth::user()->hasPermission('my-style.root')
        ) {
            MetaBox::addMetaBox(
                'my_style',
                __('My CSS'),
                [$this, 'renderCustomCssField'],
                get_class($object),
                'advanced',
                'low'
            );
        }
    }

    public function renderCustomCssField(BaseModel $object): string
    {
        $fileName = $this->fileName($object);
        $path = $this->file();
        $isWriteable = File::isWritable($path);
        $css = '';

        if ($isWriteable) {
            if ($fileName) {
                $file = $this->file($fileName);

                if (File::exists($file)) {
                    $css = BaseHelper::getFileData($file, false);
                }
            }
        }

        return view('plugins/my-style::css-editor', compact('css', 'isWriteable', 'path'))->render();
    }

    public function saveFieldsInFormScreen(string $type, Request $request, BaseModel $object): void
    {
        if (
            MyStyleHelper::isSupportedModel($object::class)
            && Auth::user()->hasPermission('my-style.root')
            && $request->has('has-my-style')
        ) {
            $fileName = $this->fileName($object);
            $css = strip_tags($request->input('my_custom_css', ''));
            $file = $this->file($fileName);

            if (empty($css)) {
                File::delete($file);
            } else {
                BaseHelper::saveFileData($file, $css, false);
            }
        }
    }

    public function embedMyStyles(string $screen, BaseModel $object): void
    {
        if (MyStyleHelper::isSupportedModel(get_class($object))) {
            $fileName = $this->fileName($object);
            $file = $this->file($fileName);

            if (File::exists($file)) {
                Theme::asset()
                    ->container('after_header')
                    ->usePath()
                    ->add($fileName . '-my-style', 'css/' . $fileName . '.css', [], [], (string) filectime($file));
            }
        }
    }

    protected function file(string $slug = ''): string
    {
        $path = Theme::path() . '/css';

        return ! empty($slug) ? public_path($path . '/' . $slug . '.css') : $path;
    }

    protected function fileName(BaseModel $object): string
    {
        return md5(get_class($object) . '-' . $object->id);
    }
}
