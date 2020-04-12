@extends('appshell::layouts.default')

@section('title')
    {{ __('Create Channel') }}
@stop

@section('content')
{!! Form::model($channel, ['route' => 'mitosis.channel.store', 'autocomplete' => 'off']) !!}

    <div class="card card-accent-success">

        <div class="card-header">
            {{ __('Channel Details') }}
        </div>

        <div class="card-block">
            @include('mitosis::channel._form')
        </div>

        <div class="card-footer">
            <button class="btn btn-success">{{ __('Create channel') }}</button>
            <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>
    </div>

{!! Form::close() !!}
@stop
