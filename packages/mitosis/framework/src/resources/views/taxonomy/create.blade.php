@extends('appshell::layouts.default')

@section('title')
    {{ __('Create Category Tree') }}
@stop

@section('content')
{!! Form::open(['route' => 'mitosis.taxonomy.store', 'autocomplete' => 'off']) !!}

    <div class="card card-accent-success">

        <div class="card-header">
            {{ __('Category Tree Details') }}
        </div>

        <div class="card-block">
            @include('mitosis::taxonomy._form')
        </div>

        <div class="card-footer">
            <button class="btn btn-success">{{ __('Create category tree') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>
    </div>

{!! Form::close() !!}
@stop
