@if ($crud->hasAccess('template'))
	<a href="{{ url($crud->route.'/template') }}" class="btn btn-primary" data-style="zoom-in"><span class="ladda-label"><i class="las la-clipboard"></i> {{ trans('template-operation::template.templates') }}</span></a>
@endif
