@extends('appshell::layouts.default')

@section('title')
    {{ __('Create new product') }}
@stop

@section('content')

    {!! Form::open(['route' => 'mitosis.product.store', 'autocomplete' => 'off', 'enctype'=>'multipart/form-data', 'class' => 'row']) !!}

        <div class="col-12 col-lg-8 col-xl-9">
            <div class="card card-accent-success">
                <div class="card-header">
                    {{ __('Product Details') }}
                </div>
                <div class="card-block">
                    @include('mitosis::product._form')
                </div>
                <div class="card-footer">
                    <button class="btn btn-success">{{ __('Create product') }}</button>
                    <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 col-xl-3">
            @include('mitosis::product._create_images')
        </div>

    {!! Form::close() !!}

@stop
