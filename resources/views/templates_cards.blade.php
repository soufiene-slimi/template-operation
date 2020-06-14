@php($chunk = (int) (12 / $crud->get('template.cardsPerRow') ?? 4))

<div class="row">
    @foreach($templates as $template)
    <div class="col-sm-6 col-lg-{{ $chunk }}">
        <div class="card-box {{ $crud->get('template.cardsClass') ?? 'bg-purple' }}">
            <div class="inner">
                <div class="btn-group float-right">
                    <button onclick="deleteTemplate(this)" data-template-id="{{ $template->id }}"
                        class="btn btn-transparent p-0 float-right" type="button">
                        <i class="las la-trash-alt"></i>
                    </button>
                </div>
                <h3> {{ $template->name }} </h3>
                <p>{{ Carbon\Carbon::parse($template->created_at)->isoFormat(config('backpack.base.default_date_format')) }}
                </p>
            </div>
            <div class="icon">
                {!! $crud->get('template.cardIcon') ?? '<i class="lar la-clipboard"></i>' !!}
            </div>
            <form method="POST" action="{{ url($crud->route).'/create' }}">
                @csrf
                <input type="hidden" name="template_id" value="{{ $template->id }}">
                {{-- <input class="card-box-footer" type="submit" value="submit"> --}}
                <a href="javascript:void(0)" class="card-box-footer" onclick="$(this).parent().submit()">
                    {{ trans('template-operation::template.use_this') }} <i class="las la-arrow-circle-right"></i>
                </a>
            </form>
        </div>
    </div>
    <!-- /.col-->
    @endforeach
</div>

@section('after_scripts')
<script type="text/javascript">
    $.ajaxPrefilter(function(options, originalOptions, xhr) {
        var token = $('meta[name="csrf_token"]').attr('content');
        if (token) {
              return xhr.setRequestHeader('X-XSRF-TOKEN', token);
        }
    });
    function deleteTemplate(e) {
        swal({
		  title: "{!! trans('backpack::base.warning') !!}",
		  text: "{!! trans('template-operation::template.delete_confirm') !!}",
		  icon: "warning",
		  buttons: {
		  	cancel: {
			  text: "{!! trans('backpack::crud.cancel') !!}",
			  value: null,
			  visible: true,
			  className: "bg-secondary",
			  closeModal: true,
			},
		  	delete: {
			  text: "{!! trans('backpack::crud.delete') !!}",
			  value: true,
			  visible: true,
			  className: "bg-danger",
			}
		  },
		}).then((value) => {
			if (value) {
				var templateId = $(e).data('template-id');
                var box = $(e).parent().parent().parent().parent();

                $.ajax("{{ url($crud->route).'/template/delete' }}", {
                    method: 'DELETE',
                    data: {
                        template_id: templateId
                    },
                    success: () => {
                        box.remove();
                        new Noty({
                            type: "success",
                            text: "<strong>{{ trans('template-operation::template.template_deleted_success') }}</strong>"
                        }).show();
                    }, error: () => {
                        new Noty({
                            type: "error",
                            text: "<strong>{{ trans('template-operation::template.template_deleted_error') }}</strong>"
                        }).show();
                    }
                });
			}
		});
    }
</script>
@endsection

@section('after_styles')
{{-- styles for template cards --}}
<style>
    .card-box {
        min-height: 120px;
        position: relative;
        color: #fff;
        padding: 20px 10px 40px;
        margin: 20px 0px;
        border-radius: 5px;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .card-box>.inner>div {
        z-index: 1;
    }

    .card-box>.inner>div i {
        font-size: x-large;
    }

    .card-box:hover {
        text-decoration: none;
        color: #f1f1f1;
    }

    .card-box:hover .icon i {
        font-size: 100px;
        transition: 1s;
        -webkit-transition: 1s;
    }

    .card-box .inner {
        padding: 5px 10px 0 10px;
    }

    .card-box h3 {
        font-size: 27px;
        font-weight: bold;
        margin: 0 0 8px 0;
        white-space: nowrap;
        padding: 0;
        text-align: left;
    }

    .card-box p {
        font-size: 15px;
    }

    .card-box .icon {
        position: absolute;
        top: auto;
        bottom: 5px;
        right: 5px;
        z-index: 0;
        font-size: 72px;
        color: rgba(0, 0, 0, 0.15);
    }

    .card-box .card-box-footer {
        position: absolute;
        left: 0px;
        bottom: 0px;
        text-align: center;
        padding: 3px 0;
        color: rgba(255, 255, 255, 0.8);
        background: rgba(0, 0, 0, 0.1);
        width: 100%;
        text-decoration: none;
    }

    .card-box:hover .card-box-footer {
        background: rgba(0, 0, 0, 0.3);
    }

    .bg-blue {
        background-color: #00c0ef !important;
    }

    .bg-green {
        background-color: #00a65a !important;
    }

    .bg-purple {
        background-color: #7c69ef !important;
    }

    .bg-orange {
        background-color: #f39c12 !important;
    }

    .bg-red {
        background-color: #d9534f !important;
    }
</style>
@endsection
