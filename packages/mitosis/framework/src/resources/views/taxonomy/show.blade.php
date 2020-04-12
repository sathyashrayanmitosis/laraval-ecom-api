@extends('appshell::layouts.default')

@section('title')
    {{ __('Viewing') }} {{ $taxonomy->name }}
@stop

@section('content')

    <style>
        .card-actionbar-show-on-hover {
            opacity: 0;
            transition: opacity 0.2s ease-in-out;
        }

        .card-block:hover > .card-actionbar-show-on-hover {
            opacity: 1;
        }
    </style>

    <div class="card">
        <div class="card-block">
            <div class="card">
                @include('mitosis::taxon._tree', ['taxons' => $taxonomy->rootLevelTaxons()])

                @can('create taxons')
                    <div class="card-footer">
                        <a href="{{ route('mitosis.taxon.create', $taxonomy) }}"
                           class="btn btn-outline-success btn-sm">{{ __('Add :category', ['category' => \Illuminate\Support\Str::singular($taxonomy->name)]) }}</a>
                    </div>
                @endcan
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-block">
            @can('edit taxonomies')
                <a href="{{ route('mitosis.taxonomy.edit', $taxonomy) }}" class="btn btn-outline-primary">{{ __('Edit Category Tree') }}</a>
            @endcan

            @can('delete taxonomies')
                {!! Form::open([
                        'route' => ['mitosis.taxonomy.destroy', $taxonomy],
                        'method' => 'DELETE',
                        'class' => 'float-right',
                        'data-confirmation-text' => __('Delete this categorization: ":name"?', ['name' => $taxonomy->name])
                    ])
                !!}
                <button class="btn btn-outline-danger">
                    {{ __('Delete Category Tree') }}
                </button>
                {!! Form::close() !!}
            @endcan
        </div>
    </div>

@stop
