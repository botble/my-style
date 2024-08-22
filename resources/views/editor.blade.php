@if ($isWriteable)
    <x-core::form-group label="My Custom CSS" for="custom_css">
        <x-core::form.label
            for="custom_css"
            :label="__('CSS Code')"
        />

        {{ Form::codeEditor('my_custom_css', $css, ['class' => 'form-control', 'rows' => 3, 'id' => 'custom_css']) }}
    </x-core::form-group>

    <x-core::form-group label="My Custom JS" for="custom_js">
        <x-core::form.label
            for="custom_js"
            :label="__('JS Code')"
        />

        {{ Form::codeEditor('my_custom_js', $css, ['class' => 'form-control', 'rows' => 3, 'id' => 'custom_js']) }}

        <x-core::form.helper-text>{{ __('JS code will be added into footer of the page. Please DO NOT use <script></script>.') }}</x-core::form.helper-text>
    </x-core::form-group>

    <input type="hidden" name="has-my-style" value="1"/>
    @push('header')
        <style>
            #my_style {
                background: #ffdddd;
            }

            #my_style .CodeMirror {
                background: #fff1f1;
            }
        </style>
    @endpush
@else
    <div class="alert alert-danger">
        {{ trans('packages/theme::theme.folder_is_not_writeable', ['name' => $path]) }}
    </div>
@endif
