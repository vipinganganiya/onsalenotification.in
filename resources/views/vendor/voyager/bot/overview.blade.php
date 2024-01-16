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
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            text-align: center;
        }
    </style>
@stop

@section('page_title','Subscribe Clubs')

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-window-list"></i> Bot Management - Overview
        </h1> 
        <button type="button" class="btn btn-info btn-lg" id="config_btn">Config</button>
        <button type="button" class="btn btn-info btn-lg" id="add_profile_btn">Add Profile</button>
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
                                        <th>Machine Name</th>
                                        <th>Club Name</th>
                                        <th>Profile</th>  
                                        <th>Actions</th> 
                                    </tr>
                                </thead>
                                
                                    <tbody> 
                                        @forelse ($overview as $listing)
                                        <tr role="row" class="{{ $loop->iteration % 2 == 0 ? 'even': 'odd' }}">
                                            <td><input type="checkbox" name="row_id" id="checkbox_{{$listing->id}}" value="{{$listing->id}}"> </td>
                                            <td>{{$listing->machine->machine_name}}</td> 
                                            <td>{{$listing->club->club_name}}</td> 
                                            <td>Profile</td> 
                                            <td class="right"></td>  
                                        </tr> 
                                        @empty 
                                        <tr class="odd">
                                            <td valign="top"  colspan="5" class="dataTables_empty">No data available in table</td>
                                        </tr>      
                                        @endforelse
                                    </tbody>
                            </table>
                        </div> 
                         
                    </div>
                </div>
            </div>
        </div> 
    </div> 

    {{-- Single delete modal --}}
    <div class="modal fade" id="modelSubscribeClubs" role="dialog" data-keyboard="false" data-backdrop="static">
        
    </div>
@stop

@section('javascript')
    <script type="text/javascript">
        
    </script> 
@stop