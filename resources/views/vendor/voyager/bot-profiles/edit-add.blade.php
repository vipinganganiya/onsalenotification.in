@php
    $edit = !is_null($dataTypeContent->getKey());
    $add  = is_null($dataTypeContent->getKey());
@endphp


<div class="modal-dialog" style="width: 90%;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <div class="container-fluid" style="background: #09b109 !important;">
                <div class="pull-left">
                <h1 class="page-title">
                    <i class="{{ $dataType->icon }}"></i>
                    {{ __('voyager::generic.'.($edit ? 'edit' : 'add')).' '.$dataType->getTranslatedAttribute('display_name_singular') }}
                </h1>
                </div>
            </div>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <div class="page-content edit-add container-fluid">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="panel panel-bordered">
                                <!-- form start -->
                                <form role="form"
                                        class="form-edit-add"
                                        action="{{ $edit ? route('voyager.'.$dataType->slug.'.update', $dataTypeContent->getKey()) : route('voyager.'.$dataType->slug.'.store') }}"
                                        method="POST" enctype="multipart/form-data">
                                    <!-- PUT Method if we are editing -->
                                    @if($edit)
                                        {{ method_field("PUT") }}
                                    @endif

                                    <!-- CSRF TOKEN -->
                                    {{ csrf_field() }}

                                    <div class="panel-body">

                                        @if (count($errors) > 0)
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <!-- Adding / Editing -->
                                        @php
                                            $dataTypeRows = $dataType->{($edit ? 'editRows' : 'addRows' )};
                                        @endphp

                                        @foreach($dataTypeRows as $row)
                                            <!-- GET THE DISPLAY OPTIONS -->
                                            @php
                                                $display_options = $row->details->display ?? NULL;
                                                if ($dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')}) {
                                                    $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_'.($edit ? 'edit' : 'add')};
                                                }
                                            @endphp
                                            @if (isset($row->details->legend) && isset($row->details->legend->text))
                                                <legend class="text-{{ $row->details->legend->align ?? 'center' }}" style="background-color: {{ $row->details->legend->bgcolor ?? '#f0f0f0' }};padding: 5px;">{{ $row->details->legend->text }}</legend>
                                            @endif

                                            <div class="form-group @if($row->type == 'hidden') hidden @endif col-md-{{ $display_options->width ?? 12 }} {{ $errors->has($row->field) ? 'has-error' : '' }}" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                                                {{ $row->slugify }}
                                                <label class="control-label" for="name">{{ $row->getTranslatedAttribute('display_name') }}</label>
                                                @include('voyager::multilingual.input-hidden-bread-edit-add')
                                                @if ($add && isset($row->details->view_add))
                                                    @include($row->details->view_add, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'view' => 'add', 'options' => $row->details])
                                                @elseif ($edit && isset($row->details->view_edit))
                                                    @include($row->details->view_edit, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'view' => 'edit', 'options' => $row->details])
                                                @elseif (isset($row->details->view))
                                                    @include($row->details->view, ['row' => $row, 'dataType' => $dataType, 'dataTypeContent' => $dataTypeContent, 'content' => $dataTypeContent->{$row->field}, 'action' => ($edit ? 'edit' : 'add'), 'view' => ($edit ? 'edit' : 'add'), 'options' => $row->details])
                                                @elseif ($row->type == 'relationship')
                                                    @include('voyager::formfields.relationship', ['options' => $row->details])
                                                @else
                                                    {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                                                @endif

                                                @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                                    {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                                @endforeach
                                                @if ($errors->has($row->field))
                                                    @foreach ($errors->get($row->field) as $error)
                                                        <span class="help-block">{{ $error }}</span>
                                                    @endforeach
                                                @endif
                                            </div>
                                        @endforeach

                                    </div><!-- panel-body -->

                                    <div class="panel-footer">
                                        @section('submit-buttons')
                                            <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                                        @stop
                                        @yield('submit-buttons')
                                    </div>
                                </form>

                                <div style="display:none">
                                    <input type="hidden" id="upload_url" value="{{ route('voyager.upload') }}">
                                    <input type="hidden" id="upload_type_slug" value="{{ $dataType->slug }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-danger view quote_cancel_btn" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
<script type="text/javascript" src="http://onsalenotification.in/admin/voyager-assets?path=js%2Fapp.js"></script>