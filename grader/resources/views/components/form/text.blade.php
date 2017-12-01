<div class="form-group">
    {{ Form::label($name, null, ['class' => 'control-label']) }}
    {{
        Form::text(kebab_case($name), $value, array_merge([
            'class' => 'form-control',
            'required' => true,
        ], $attributes))
    }}
</div>
