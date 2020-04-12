<div class="form-group">
    <div class="input-group">
        <span class="input-group-addon">
            <i class="zmdi zmdi-portable-wifi"></i>
        </span>
        {{ Form::text('name', null, [
                'class' => 'form-control form-control-lg' . ($errors->has('name') ? ' is-invalid' : ''),
                'placeholder' => __('Name of the channel')
            ])
        }}
    </div>
    @if ($errors->has('name'))
        <input hidden class="form-control is-invalid" />
        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
    @endif
</div>

<div class="form-group row">
    <label class="col-form-label col-form-label-sm col-md-2">{{ __('Slug') }}</label>
    <div class="col-md-10">
        {{ Form::text('slug', null, [
                'class' => 'form-control form-control-sm' . ($errors->has('slug') ? ' is-invalid': ''),
                'placeholder' => __('The human readable id of the channel')
            ])
        }}
        @if ($errors->has('slug'))
            <div class="invalid-feedback">{{ $errors->first('slug') }}</div>
        @endif
    </div>
</div>

<hr>

<div class="form-group row">
    <label class="col-form-label col-form-label-sm col-md-2">{{ __('Country') }}</label>
    <div class="col-md-10">
        {{ Form::select('configuration[country_id]', $countries, null, [
                'class' => 'form-control form-control-sm' . ($errors->has('configuration') ? ' is-invalid': ''),
                'placeholder' => __('--')
           ])
        }}
        @if ($errors->has('configuration'))
            <div class="invalid-feedback">{{ $errors->first('type') }}</div>
        @endif
    </div>
</div>

