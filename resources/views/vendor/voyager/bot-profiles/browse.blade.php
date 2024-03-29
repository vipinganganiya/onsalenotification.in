@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' '.$dataType->getTranslatedAttribute('display_name_plural'))

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="{{ $dataType->icon }}"></i> {{ $dataType->getTranslatedAttribute('display_name_plural') }}
        </h1>
        @can('delete', app($dataType->model_name))
            @include('voyager::partials.bulk-delete')
        @endcan
        @can('edit', app($dataType->model_name))
            @if(!empty($dataType->order_column) && !empty($dataType->order_display_column))
                <a href="{{ route('voyager.'.$dataType->slug.'.order') }}" class="btn btn-primary btn-add-new">
                    <i class="voyager-list"></i> <span>{{ __('voyager::bread.order') }}</span>
                </a>
            @endif
        @endcan
        @can('delete', app($dataType->model_name))
            @if($usesSoftDeletes)
                <input type="checkbox" @if ($showSoftDeleted) checked @endif id="show_soft_deletes" data-toggle="toggle" data-on="{{ __('voyager::bread.soft_deletes_off') }}" data-off="{{ __('voyager::bread.soft_deletes_on') }}">
            @endif
        @endcan
        @foreach($actions as $action)
            @if (method_exists($action, 'massAction'))
                @include('voyager::bread.partials.actions', ['action' => $action, 'data' => null])
            @endif
        @endforeach
        @include('voyager::multilingual.language-selector')
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">  
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="overflow: visible;">
                        <div class="container-fluid" style="background: white !important;">
                            <div class="pull-right d-flex">
                                @can('add', app($dataType->model_name))
                                    <!-- {{ route('voyager.'.$dataType->slug.'.create') }} -->
                                    @php
                                        $route = route('voyager.bot-profiles.create');
                                    @endphp 
                                    <button class="btn-add-profile" data-toggle="modal" data-dismiss="modal" onclick="addModel('{{$route}}');">
                                        <i class="voyager-plus"></i> {{ __('voyager::generic.add_new') }}
                                    </button>
                                    <button class="btn-config-profile" data-toggle="modal" data-dismiss="modal" data-target="#config_modal">
                                        <i class="voyager-settings"></i> Config
                                    </button>
                                @endcan
                            </div>
                        </div>
                        <br />
                        @if ($isServerSide)
                            <form method="get" class="form-search">
                                <div id="search-input">
                                    <div class="col-2">
                                        <select id="search_key" name="key">
                                            @foreach($searchNames as $key => $name)
                                                <option value="{{ $key }}" @if($search->key == $key || (empty($search->key) && $key == $defaultSearchKey)) selected @endif>{{ $name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <select id="filter" name="filter">
                                            <option value="contains" @if($search->filter == "contains") selected @endif>{{ __('voyager::generic.contains') }}</option>
                                            <option value="equals" @if($search->filter == "equals") selected @endif>=</option>
                                        </select>
                                    </div>
                                    <div class="input-group col-md-12">
                                        <input type="text" class="form-control" placeholder="{{ __('voyager::generic.search') }}" name="s" value="{{ $search->value }}">
                                        <span class="input-group-btn">
                                            <button class="btn btn-info btn-lg" type="submit">
                                                <i class="voyager-search"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                @if (Request::has('sort_order') && Request::has('order_by'))
                                    <input type="hidden" name="sort_order" value="{{ Request::get('sort_order') }}">
                                    <input type="hidden" name="order_by" value="{{ Request::get('order_by') }}">
                                @endif
                            </form>
                        @endif
                        <div class="row">
                            <div class="col-md-12 listing-table" style="margin-bottom: 0; display: none;">
                                <div class="panel panel-default" style="position: sticky; top: 0;">
                                    <div class="header-panel-heading collapsed">
                                        <div class="panel-title">
                                            <div class="accordion-toggle flexbox">
                                                @if($showCheckboxColumn)
                                                    <div class="flexbox-item flexbox-item-assign">
                                                        <input type="checkbox" class="select_all">
                                                    </div>
                                                @endif
                                                @foreach($dataType->browseRows as $row)
                                                <div class="flexbox-item flexbox-item-{{ str_replace(' ', '-', strtolower($row->getTranslatedAttribute('display_name'))) }}">
                                                    @if ($isServerSide && in_array($row->field, $sortableColumns))
                                                        <a href="{{ $row->sortByUrl($orderBy, $sortOrder) }}">
                                                    @endif
                                                    {{ $row->getTranslatedAttribute('display_name') }}
                                                    @if ($isServerSide)
                                                        @if ($row->isCurrentSortField($orderBy))
                                                            @if ($sortOrder == 'asc')
                                                                <i class="voyager-angle-up pull-right"></i>
                                                            @else
                                                                <i class="voyager-angle-down pull-right"></i>
                                                            @endif
                                                        @endif
                                                        </a>
                                                    @endif
                                                </div>
                                                @endforeach
                                                <div class="flexbox-item flexbox-item-action">{{ __('voyager::generic.actions') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                                @forelse($dataTypeContent as $data)
                                    <div class="panel panel-default data-panel" panel_div_id="{{ $data->id }}">
                                        <div class="panel-heading collapsed" data-iteration="{{ $loop->iteration }}" href="#collapse{{ $loop->iteration }}">
                                            <div class="panel-title">
                                                <div class="accordion-toggle flexbox">
                                                    @if($showCheckboxColumn)
                                                        <div class="flexbox-item flexbox-item-assign text-center" id="map-tgces-{{ $data->id }}">
                                                            <input type="checkbox" name="row_id" id="checkbox_{{ $data->getKey() }}" value="{{ $data->getKey() }}">
                                                        </div>
                                                    @endif
                                                    @foreach($dataType->browseRows as $row)
                                                        @php
                                                        if ($data->{$row->field.'_browse'}) {
                                                            $data->{$row->field} = $data->{$row->field.'_browse'};
                                                        }
                                                        @endphp
                                                         <div class="flexbox-item flexbox-item-{{ str_replace(' ', '-', strtolower($row->getTranslatedAttribute('display_name'))) }} {{ str_replace(' ', '-', strtolower($row->getTranslatedAttribute('display_name'))) }}-{{$data->id}}" id="map-tgces-{{ $data->id }}">
                                                            @if (isset($row->details->view_browse))
                                                                @include($row->details->view_browse, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $data->{$row->field}, 'view' => 'browse', 'options' => $row->details])
                                                            @elseif (isset($row->details->view))
                                                                @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $data->{$row->field}, 'action' => 'browse', 'view' => 'browse', 'options' => $row->details])
                                                            @elseif($row->type == 'image')
                                                                <img src="@if( !filter_var($data->{$row->field}, FILTER_VALIDATE_URL)){{ Voyager::image( $data->{$row->field} ) }}@else{{ $data->{$row->field} }}@endif" style="width:100px">
                                                            @elseif($row->type == 'relationship')
                                                                @include('voyager::formfields.relationship', ['view' => 'read','options' => $row->details])
                                                            @elseif($row->type == 'select_multiple')
                                                                @if(property_exists($row->details, 'relationship'))

                                                                    @foreach($data->{$row->field} as $item)
                                                                        {{ $item->{$row->field} }}
                                                                    @endforeach

                                                                @elseif(property_exists($row->details, 'options'))
                                                                    @if (!empty(json_decode($data->{$row->field})))
                                                                        @foreach(json_decode($data->{$row->field}) as $item)
                                                                            @if (@$row->details->options->{$item})
                                                                                {{ $row->details->options->{$item} . (!$loop->last ? ', ' : '') }}
                                                                            @endif
                                                                        @endforeach
                                                                    @else
                                                                        {{ __('voyager::generic.none') }}
                                                                    @endif
                                                                @endif

                                                                @elseif($row->type == 'multiple_checkbox' && property_exists($row->details, 'options'))
                                                                    @if (@count(json_decode($data->{$row->field}, true)) > 0)
                                                                        @foreach(json_decode($data->{$row->field}) as $item)
                                                                            @if (@$row->details->options->{$item})
                                                                                {{ $row->details->options->{$item} . (!$loop->last ? ', ' : '') }}
                                                                            @endif
                                                                        @endforeach
                                                                    @else
                                                                        {{ __('voyager::generic.none') }}
                                                                    @endif

                                                            @elseif(($row->type == 'select_dropdown' || $row->type == 'radio_btn') && property_exists($row->details, 'options'))

                                                                {!! $row->details->options->{$data->{$row->field}} ?? '' !!}

                                                            @elseif($row->type == 'date' || $row->type == 'timestamp')
                                                                @if ( property_exists($row->details, 'format') && !is_null($data->{$row->field}) )
                                                                    {{ \Carbon\Carbon::parse($data->{$row->field})->formatLocalized($row->details->format) }}
                                                                @else
                                                                    {{ $data->{$row->field} }}
                                                                @endif
                                                            @elseif($row->type == 'checkbox')
                                                                @if(property_exists($row->details, 'on') && property_exists($row->details, 'off'))
                                                                    @if($data->{$row->field})
                                                                        <span class="label label-info">{{ $row->details->on }}</span>
                                                                    @else
                                                                        <span class="label label-primary">{{ $row->details->off }}</span>
                                                                    @endif
                                                                @else
                                                                {{ $data->{$row->field} }}
                                                                @endif
                                                            @elseif($row->type == 'color')
                                                                <span class="badge badge-lg" style="background-color: {{ $data->{$row->field} }}">{{ $data->{$row->field} }}</span>
                                                            @elseif($row->type == 'text')
                                                                @include('voyager::multilingual.input-hidden-bread-browse')
                                                               {{ mb_strlen( $data->{$row->field} ) > 200 ? mb_substr($data->{$row->field}, 0, 200) . ' ...' : $data->{$row->field} }}
                                                            @elseif($row->type == 'text_area')
                                                                @include('voyager::multilingual.input-hidden-bread-browse')
                                                                <div>{{ mb_strlen( $data->{$row->field} ) > 200 ? mb_substr($data->{$row->field}, 0, 200) . ' ...' : $data->{$row->field} }}</div>
                                                            @elseif($row->type == 'file' && !empty($data->{$row->field}) )
                                                                @include('voyager::multilingual.input-hidden-bread-browse')
                                                                @if(json_decode($data->{$row->field}) !== null)
                                                                    @foreach(json_decode($data->{$row->field}) as $file)
                                                                        <a href="{{ Storage::disk(config('voyager.storage.disk'))->url($file->download_link) ?: '' }}" target="_blank">
                                                                            {{ $file->original_name ?: '' }}
                                                                        </a>
                                                                        <br/>
                                                                    @endforeach
                                                                @else
                                                                    <a href="{{ Storage::disk(config('voyager.storage.disk'))->url($data->{$row->field}) }}" target="_blank">
                                                                        {{ __('voyager::generic.download') }}
                                                                    </a>
                                                                @endif
                                                            @elseif($row->type == 'rich_text_box')
                                                                @include('voyager::multilingual.input-hidden-bread-browse')
                                                                <div>{{ mb_strlen( strip_tags($data->{$row->field}, '<b><i><u>') ) > 200 ? mb_substr(strip_tags($data->{$row->field}, '<b><i><u>'), 0, 200) . ' ...' : strip_tags($data->{$row->field}, '<b><i><u>') }}</div>
                                                            @elseif($row->type == 'coordinates')
                                                                @include('voyager::partials.coordinates-static-image')
                                                            @elseif($row->type == 'multiple_images')
                                                                @php $images = json_decode($data->{$row->field}); @endphp
                                                                @if($images)
                                                                    @php $images = array_slice($images, 0, 3); @endphp
                                                                    @foreach($images as $image)
                                                                        <img src="@if( !filter_var($image, FILTER_VALIDATE_URL)){{ Voyager::image( $image ) }}@else{{ $image }}@endif" style="width:50px">
                                                                    @endforeach
                                                                @endif
                                                            @elseif($row->type == 'media_picker')
                                                                @php
                                                                    if (is_array($data->{$row->field})) {
                                                                        $files = $data->{$row->field};
                                                                    } else {
                                                                        $files = json_decode($data->{$row->field});
                                                                    }
                                                                @endphp
                                                                @if ($files)
                                                                    @if (property_exists($row->details, 'show_as_images') && $row->details->show_as_images)
                                                                        @foreach (array_slice($files, 0, 3) as $file)
                                                                        <img src="@if( !filter_var($file, FILTER_VALIDATE_URL)){{ Voyager::image( $file ) }}@else{{ $file }}@endif" style="width:50px">
                                                                        @endforeach
                                                                    @else
                                                                        <ul>
                                                                        @foreach (array_slice($files, 0, 3) as $file)
                                                                            <li>{{ $file }}</li>
                                                                        @endforeach
                                                                        </ul>
                                                                    @endif
                                                                    @if (count($files) > 3)
                                                                        {{ __('voyager::media.files_more', ['count' => (count($files) - 3)]) }}
                                                                    @endif
                                                                @elseif (is_array($files) && count($files) == 0)
                                                                    {{ trans_choice('voyager::media.files', 0) }}
                                                                @elseif ($data->{$row->field} != '')
                                                                    @if (property_exists($row->details, 'show_as_images') && $row->details->show_as_images)
                                                                        <img src="@if( !filter_var($data->{$row->field}, FILTER_VALIDATE_URL)){{ Voyager::image( $data->{$row->field} ) }}@else{{ $data->{$row->field} }}@endif" style="width:50px">
                                                                    @else
                                                                        {{ $data->{$row->field} }}
                                                                    @endif
                                                                @else
                                                                    {{ trans_choice('voyager::media.files', 0) }}
                                                                @endif
                                                            @else
                                                                @include('voyager::multilingual.input-hidden-bread-browse')
                                                                <span>{{ $data->{$row->field} }}</span>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                    <div class="flexbox-item flexbox-item-thread-input" id="map-tgces-{{ $data->id }}" style="text-align: center;">
                                                        <input type="text" name="thread_input" id="thread_input_{{$data->id}}" style="color: #000; line-height: normal !important; width: 25px;" />
                                                    </div>
                                                    <div class="flexbox-item flexbox-item-action arrow-1" id="map-tgces-{{ $data->id }}">
                                                        @foreach($actions as $action)
                                                            @if (!method_exists($action, 'massAction'))
                                                                @include('voyager::bot-profiles.partials.actions', ['action' => $action])
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="panel panel-default panel-empty">
                                        <div class="panel-heading">
                                            <div class="panel-title">
                                                No Data Available
                                            </div>
                                        </div>
                                    </div>
                                @endforelse 
                            </div>
                        </div>
                        @if ($isServerSide)
                            <div class="pull-left">
                                <div role="status" class="show-res" aria-live="polite">{{ trans_choice(
                                    'voyager::generic.showing_entries', $dataTypeContent->total(), [
                                        'from' => $dataTypeContent->firstItem(),
                                        'to' => $dataTypeContent->lastItem(),
                                        'all' => $dataTypeContent->total()
                                    ]) }}</div>
                            </div>
                            <div class="pull-right">
                                {{ $dataTypeContent->appends([
                                    's' => $search->value,
                                    'filter' => $search->filter,
                                    'key' => $search->key,
                                    'order_by' => $orderBy,
                                    'sort_order' => $sortOrder,
                                    'showSoftDeleted' => $showSoftDeleted,
                                ])->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Config Model --}}
    <div class="modal fade modal-info in" id="config_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title"><i class="voyager-rocket"></i> {{ $dataType->getTranslatedAttribute('display_name_plural') }} - Configuration 
                        </h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                       <div class="card card-primary">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-2 col-add">
                                        <span class="master-table machine"> 
                                            <a onclick="showOthers(' {{route("voyager.bot-machines.index") }}')" data-dismiss="modal" aria-hidden="true">
                                                <img width="50" height="50" src="https://img.icons8.com/ios/50/gears--v1.png" alt="Machine" title="Machine" />
                                            </a>
                                            <br />
                                            <b>Machine</b> 
                                        </span>
                                    </div>

                                    <div class="col-sm-2 col-add">
                                        <span class="master-table proxy">
                                            <a onclick="showOthers(' {{route("voyager.bot-proxies.index") }}')" data-dismiss="modal" aria-hidden="true">
                                                <img width="50" height="50" src="https://img.icons8.com/external-solidglyph-m-oki-orlando/64/external-proxy-information-technology-solid-solidglyph-m-oki-orlando.png" alt="Proxy" title="Proxy" />
                                            </a>
                                            <br />
                                            <b>Proxy</b>
                                        </span>
                                    </div>

                                    <div class="col-sm-2 col-add">
                                        <span class="master-table login">
                                            <a onclick="showOthers(' {{route("voyager.bot-logins.index") }}')" data-dismiss="modal" aria-hidden="true">
                                                <img width="50" height="50" src="https://img.icons8.com/ios/50/enter-2.png" alt="Login" title="Login" />
                                            </a>
                                            <br />
                                            <b>Login</b>
                                        </span>
                                    </div>

                                    <div class="col-sm-2 col-add">
                                        <span class="master-table club">
                                            <a onclick="showOthers(' {{route("voyager.bot-clubs.index") }}')" data-dismiss="modal" aria-hidden="true">
                                                <img width="48" height="48" src="https://img.icons8.com/external-those-icons-lineal-those-icons/48/external-Card-casino-and-leisure-those-icons-lineal-those-icons-2.png" alt="Club" title="Club" />
                                            </a>
                                            <br />
                                            <a>Club</a>
                                        </span>   
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger view quote_cancel_btn" data-dismiss="modal">Close</button>
                </div> -->
            </div>
        </div>
    </div>

    {{-- Config Model --}}
    <div class="modal fade modal-info event_detail_modal in" id="loadData" style="padding-right: 17px;" role="bot-mgt">

    </div>

    {{-- Single delete modal --}}
    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('voyager::generic.delete_question') }} {{ strtolower($dataType->getTranslatedAttribute('display_name_singular')) }}?</h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_form" method="POST">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="{{ __('voyager::generic.delete_confirm') }}">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop

@section('css')
@if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
    <link rel="stylesheet" href="{{ voyager_asset('lib/css/responsive.dataTables.min.css') }}">
@endif
<link href="{{ asset('css/multiselect.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="{{ asset('css/scraper.css') }}">
@stop

@section('javascript')
    <!-- DataTables -->
    @if(!$dataType->server_side && config('dashboard.data_tables.responsive'))
        <script src="{{ voyager_asset('lib/js/dataTables.responsive.min.js') }}"></script>
    @endif
    <script src="{{ asset('js/moment.js') }}"></script>
    <script>
        $(document).ready(function () {

            $('.listing-table').slideDown();

            $('#config_btn').on('click', function (e) {
                $('#configInfo').modal('show');
            });

            @if (!$dataType->server_side)
                var table = $('#dataTable').DataTable({!! json_encode(
                    array_merge([
                        "order" => $orderColumn,
                        "language" => __('voyager::datatable'),
                        "columnDefs" => [
                            ['targets' => 'dt-not-orderable', 'searchable' =>  false, 'orderable' => false],
                        ],
                    ],
                    config('voyager.dashboard.data_tables', []))
                , true) !!});
            @else
                $('#search-input select').select2({
                    minimumResultsForSearch: Infinity
                });
            @endif

            @if ($isModelTranslatable)
                $('.side-body').multilingual();
                //Reinitialise the multilingual features when they change tab
                $('#dataTable').on('draw.dt', function(){
                    $('.side-body').data('multilingual').init();
                })
            @endif
            $('.select_all').on('click', function(e) {
                $('input[name="row_id"]').prop('checked', $(this).prop('checked')).trigger('change');
            });
        });


        var deleteFormAction;
        $('td').on('click', '.delete', function (e) {
            $('#delete_form')[0].action = '{{ route('voyager.'.$dataType->slug.'.destroy', '__id') }}'.replace('__id', $(this).data('id'));
            $('#delete_modal').modal('show');
        });

        @if($usesSoftDeletes)
            @php
                $params = [
                    's' => $search->value,
                    'filter' => $search->filter,
                    'key' => $search->key,
                    'order_by' => $orderBy,
                    'sort_order' => $sortOrder,
                ];
            @endphp
            $(function() {
                $('#show_soft_deletes').change(function() {
                    if ($(this).prop('checked')) {
                        $('#dataTable').before('<a id="redir" href="{{ (route('voyager.'.$dataType->slug.'.index', array_merge($params, ['showSoftDeleted' => 1]), true)) }}"></a>');
                    }else{
                        $('#dataTable').before('<a id="redir" href="{{ (route('voyager.'.$dataType->slug.'.index', array_merge($params, ['showSoftDeleted' => 0]), true)) }}"></a>');
                    }

                    $('#redir')[0].click();
                })
            })
        @endif
        $('input[name="row_id"]').on('change', function () {
            var ids = [];
            $('input[name="row_id"]').each(function() {
                if ($(this).is(':checked')) {
                    ids.push($(this).val());
                }
            });
            $('.selected_ids').val(ids);
        });

        function addModel(slug) {   
            $.ajax({
                url:slug,
                type: "get",
                success: function(res) {
                   $("#loadData").html(res);
                   $('#loadData').modal('show');
                },
                error: function() {
                    toastr.error('Something went wrong', 'Error');
                },
                complete: function() {
                    
                }
            });
        }
        function editProfile(url, id) { 
            var thread_input = $("#thread_input_"+id).val();
            console.log(thread_input);
            $.ajax({
                url: url,
                type: "get",
                data: {'thread_input':thread_input},
                success: function(res) {

                    if (res.id) {
                        toastr.success(res.msg, 'Success');

                        $("#thread_input_"+res.id).val('');
                        if(res.type=='start') { 
                            console.log("#running-threads-"+res.id);
                            $(".running-threads-"+res.id).text(res.thread_input);
                        }

                    } else { 
                       $("#loadData").html(res);
                       $('#loadData').modal('show'); 
                    }
                  
                },
                error: function() {
                    toastr.error('Something went wrong', 'Error');
                },
                complete: function() {
                    
                }
            });
        }
        function showOthers(url) { 
            console.log(url);  
            $.ajax({
                url: url,
                type: "get", 
                success: function(res) {
                   $("#loadData").html(res);
                   $('#config_modal').modal('hide');  
                   $('#loadData').modal('show');  
                  
                },
                error: function() {
                    toastr.error('Something went wrong', 'Error');
                },
                complete: function() {
                    
                }
            });
        }
        @if(!empty(Session::get('popup')) && Session::get('popup')  == "bot-machines-listing")
            showOthers(@json(route('voyager.bot-machines.index') ));
        @endif

        @if(!empty(Session::get('popup')) && Session::get('popup')  == "bot-clubs-listing")
            showOthers(@json(route('voyager.bot-clubs.index') ));
            //toastr.success('Bot Machine is added successfully', 'Success');
        @endif

        @if(!empty(Session::get('popup')) && Session::get('popup')  == "bot-logins-listing")
            showOthers(@json(route('voyager.bot-logins.index') )); 
        @endif
        // @if (count($errors) > 0)
        //     showOthers(@json(route('clubs-bot.create') )); 
            
        // @endif
    </script>
@stop