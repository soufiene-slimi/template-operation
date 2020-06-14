@extends(backpack_view('blank'))

@php
$defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('template-operation::template.templates') => false,
];

// if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
$breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;

$heading = $crud->getHeading() ?? $crud->entity_name_plural;
$subheading = trans('template-operation::template.templates');
@endphp

@section('header')
<div class="container-fluid">
    <h2>
        <span class="text-capitalize">{!! $heading !!}</span>
        <small>{!! $subheading !!}.</small>

        @if ($crud->hasAccess('list'))
        <small><a href="{{ url($crud->route) }}" class="hidden-print font-sm"><i class="la la-angle-double-left"></i>
                {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
        @endif
    </h2>
</div>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <a href="{{ url($crud->route).'/template/create' }}" class="btn btn-primary" data-style="zoom-in"><span
                class="ladda-label"><i class="la la-plus"></i> {!! trans('template-operation::template.add_template')
                !!}</span></a>
    </div>
</div>
<div class="row">
    <div class="{{ $crud->get('template.cardsContentClass') ?? 'col-md-12' }}">
        <!-- Default box -->
        @if(! count($templates))
        <span class="display-4">{{ trans('template-operation::template.no_templates') }} <i
                class="las la-meh"></i></span>
        <p>{!! trans('template-operation::template.no_templates_text', ['entries' =>
            '<code>'.$crud->entity_name_plural.'</code>']) !!}</p>
        @else
        @include('template-operation::templates_cards')
        @endif
    </div>
</div>
@endsection


@section('after_styles')
<link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/crud.css') }}">
@endsection

@section('after_scripts')
<link rel="stylesheet" href="https://backstrap.net/vendors/simple-line-icons/css/simple-line-icons.css">
<script src="{{ asset('packages/backpack/crud/js/crud.js') }}"></script>
@endsection
