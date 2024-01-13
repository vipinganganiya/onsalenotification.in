@extends('voyager::master')

@section('css')      
    <style type="text/css">
        .form-group {
            display: flex;
        }
        .wait-model-btn {
            position: relative;
            z-index: 99999;  
        }
        .wait-model-btn img{
            width:15px;
        }

         .wait-model {
            position: relative;
            z-index: 99999;  
        }
        .wait-model img{
            width:75px;
        }
    </style>
@stop

@section('page_title','Subscribe Clubs')

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-window-list"></i> Subscribe Club
        </h1> 
        <button type="button" class="btn btn-info btn-lg" id="subscribe_btn">Subscribe</button>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body"> 
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover">
                                <thead>
                                    <tr> 
                                        <th class="dt-not-orderable"> <input type="checkbox" class="select_all">  </th>  
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th>Created By</th>  
                                        <th>Created Date</th> 
                                    </tr>
                                </thead>
                                @if($clubs->total() > 0)
                                 	<tbody> 
                                 		@foreach($clubs as $club)
										<tr role="row" class="{{ $loop->iteration % 2 == 0 ? 'even': 'odd' }}">
											<td><input type="checkbox" name="row_id" id="checkbox_{{$club->id}}" value="{{$club->id}}"> </td>
											<td>{{$club->title}}</td> 
                                            <td>{{$club->slug}}</td> 
											<td>{{$club->createdBy->name}}</td> 
											<td class="right">{{$club->created_at}}</td>  
										</tr>
										@endforeach
									</tbody>  
								@else 
									<tbody>
										<tr class="odd">
											<td valign="top" colspan="4" class="dataTables_empty">No data available in table</td>
										</tr>
									</tbody>
								@endif
                            </table>
                        </div> 
                        {!! $clubs->render('vendor.pagination.notification-browse') !!}
                    </div>
                </div>
            </div>
        </div>

        @if(!$isAuth)
        <h1 class="page-title">
            <i class="voyager-window-list"></i> Subscribe Other User's Club
        </h1> 
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body"> 
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover">
                                <thead>
                                    <tr>  
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th>Created By</th>  
                                        <th>Created Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                @if($otherUsersClub->total() > 0)
                                    <tbody> 
                                        @foreach($otherUsersClub as $club)
                                        <tr role="row" class="{{  $loop->iteration % 2 == 0 ? 'even' : 'odd' }}"> 
                                            <td>{{$club->title}}</td> 
                                            <td>{{$club->slug}}</td> 
                                            <td>{{$club->createdBy->name}}</td> 
                                            <td class="right">{{$club->created_at}}</td> 
                                            <td>

                                                @php 
                                                    $assigned_ids = $club->users->pluck('id')->toArray();

                                                    if(in_array(\Auth()->user()->id, $assigned_ids))  {
                                                @endphp
                                                    <button type="button" class="btn btn-primary" onclick="assignClubToUser('{{$club->id}}', 'un-assign');">Un Subscribe Club</button> 
                                                @php
                                                    } else {
                                                @endphp
                                                    <button type="button" class="btn btn-primary" onclick="assignClubToUser('{{$club->id}}', 'single');">Subscribe Club</button> 
                                                @php
                                                    }
                                                @endphp
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>  
                                @else 
                                    <tbody>
                                        <tr class="odd">
                                            <td valign="top" colspan="4" class="dataTables_empty">No data available in table</td>
                                        </tr>
                                    </tbody>
                                @endif
                            </table>
                        </div> 
                        {!! $otherUsersClub->render('vendor.pagination.notification-browse') !!}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div> 

    {{-- Single delete modal --}}
    <div class="modal fade" id="modelSubscribeClubs" role="dialog" data-keyboard="false" data-backdrop="static">
        
    </div>
@stop

@section('javascript')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });   

        $('.select_all').on('click', function(e) {
            $('input[name="row_id"]').prop('checked', $(this).prop('checked')).trigger('change');
        });

        $('input[name="row_id"]').on('change', function () {
            var ids = [];
            $('input[name="row_id"]').each(function() {
                if ($(this).is(':checked')) {
                    ids.push($(this).val());
                }
            });
            $('.selected_ids').val(ids);
        });

        /**
         * event: onClick on subscribe_btn
         * below onclick event check 
         * if user has seleted the checkbox
         * if yes it will show model
         * else genrate the toast warning
         * this onclick event also pass the
         * selected checkbox to showSubscribeModel
         * model
         */
        window.onload = function () {
            var $subscribeBtn = $('#subscribe_btn');
            var $modelSubscribeClubs = $('#modelSubscribeClubs');
            $subscribeBtn.click(function () {
                var ids = [];
                var $checkedBoxes = $('#dataTable input[type=checkbox]:checked').not('.select_all');
                var count = $checkedBoxes.length; 
                if (count) {
                    // Gather IDs
                    $.each($checkedBoxes, function () {
                        var value = $(this).val();
                        ids.push(value);
                    }) 

                    showSubscribeModel(ids);
                    // Show modal 

                    $modelSubscribeClubs.modal('show');
                } else {
                    // No row selected
                    toastr.warning('You haven&#039;t selected anything to subscribe');
                }
            });
        }

        /**
         * function: showSubscribeModel
         * this function uses the ajax
         * to collect the user and clubs
         * dropdown basis on the user selected
         * clubs entries;
         */
        var showSubscribeModel = function(ids) { 
            var type = "GET";
            var ajaxurl = "{{ route('subscribe/modelListings'); }}"; 
            var loding_url = "{{ asset('/css/voyager-assets.png') }}";

            $.ajax({
                type: type,
                url: ajaxurl,
                data: {ids: ids, _token: '{{csrf_token()}}' },
                dataType: 'json',
                beforeSend: function() {
                    $('.assignBtn').attr('disabled', true);
                    $('#modelSubscribeClubs').before('<span class="wait-model">&nbsp;<img src="'+loding_url+'" alt="loading" /></span>');
                },
                complete: function() {
                   $('.assignBtn').attr('disabled', false);
                   $('.wait-model').remove();
                },
                success: function (data) {  
                    $('#modelSubscribeClubs').html(data.html);
                },
                error: function (data) {
                   alert("There is error in subscribe model function: "+data);
                }
            }); 
        }


        /**
         * function: assignClubsToUser
         * this function subscribe the user 
         * for multiple or specific clubs
         */
        var assignClubToUser  = function(clubID, ajaxType) {
            var type = "POST";
            var ajaxurl = "{{ route('subscribe/saveAssignData'); }}"; 
            var loding_url = "{{ asset('/css/voyager-assets.png') }}"; 
            var ajaxData = {};

            /**
             *  Option for Multiple and Single  Subscribe with Unsubscribe
             *  below if statement for multiple option
             */
            if(ajaxType == 'multiple' && clubID === null) {

                elUsers = document.getElementById('users');
                elClub = document.getElementById('clubs');

                var idUsers = getSelectValues(elUsers);
                var idClubs = getSelectValues(elClub); 

                if(idUsers === undefined || idUsers.length == 0) {
                    toastr.error('Please Select User(s).');
                    return false;
                } 

                if(idClubs === undefined || idClubs.length == 0) {
                    toastr.error('Please Select User(s).');
                    return false;
                } 

                ajaxData = {source: ajaxType, clubIds: idClubs, userIds: idUsers, _token: '{{csrf_token()}}' };

            } else if( ajaxData == 'single') {
                /**
                 *  Option for Single Subscribe
                 */
                ajaxData = {source: ajaxType, clubId: clubID,  _token: '{{csrf_token()}}' };

            } else {
                /**
                 *  Option for UnSubscribe
                 */
                ajaxData = {source: ajaxType, clubId: clubID,  _token: '{{csrf_token()}}' };
            } 

            $.ajax({
                type: type,
                url: ajaxurl,
                data: ajaxData,
                dataType: 'json',
                beforeSend: function() {
                    $('.assignBtn').attr('disabled', true);
                    $('.assignBtn').before('<span class="wait-model-btn">&nbsp;<img src="'+loding_url+'" alt="loading" /></span>');
                },
                complete: function() {
                    $('.assignBtn').attr('disabled', false);
                    $('.wait-model-btn').remove();
                },
                success: function (data) {    
                    toastr.success(data['success']);

                    if(data['ajaxData'] == 'multiple') {
                        $('#modelSubscribeClubs').modal('hide');
                    } else { 
                        window.location.reload();
                    }  
                },
                error: function (data) {
                   alert("There is error in subscribe ajax function: "+data);
                }
            }); 
        }

        function getSelectValues(select) {
          var result = [];
          var options = select && select.options;
          var opt;

          for (var i=0, iLen=options.length; i<iLen; i++) {
            opt = options[i];

            if (opt.selected) {
              result.push(opt.value || opt.text);
            }
          }
          return result;
        }
    </script> 
@stop