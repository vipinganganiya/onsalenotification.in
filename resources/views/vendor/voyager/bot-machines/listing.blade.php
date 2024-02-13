<div class="modal-dialog" style="width: 90%;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <div class="container-fluid" style="background: #09b109 !important;">
                <div class="pull-left">
                    <h4 class="modal-title">
                        <i class="voyager-window-list"></i> {{ $dataType->getTranslatedAttribute('display_name_plural') }}
                    </h1>   
                </div>
            </div>
        </div> 
        <div class="page-content browse container-fluid">
            @include('voyager::alerts')
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body"> 
                            <div class="container-fluid" style="background: white !important;">
                                <div class="pull-right d-flex"> 
                                        @php
                                            $route = route('voyager.bot-machines.create');
                                        @endphp
                                        <button class="btn-add-machine" data-toggle="modal" onclick="addModel('{{$route}}');">
                                            <i class="voyager-plus"></i> {{ __('voyager::generic.add_new') }}
                                        </button> 
                                </div>
                            </div> 
                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table id="dataTable" class="table table-bordered table-hover" style="border-color: #868484;">
                                        <thead>
                                            <tr> 
                                                <th class="dt-not-orderable"> <input type="checkbox" class="select_all"></th>  
                                                <th>Machine Unique Identifier</th> 
                                                <th>Machine Name</th>
                                                <th>Actions</th> 
                                            </tr>
                                        </thead>
                                        
                                            <tbody> 
                                                @forelse ($machines as $machine)
                                                <tr role="row" class="{{ $loop->iteration % 2 == 0 ? 'even': 'odd' }}">
                                                    <td><input type="checkbox" name="row_id" id="checkbox_{{$machine->id}}" value="{{$machine->id}}"> </td>
                                                    <td>{{$machine->machine_unique_field}}</td> 
                                                    <td>{{$machine->machine_name}}</td>
                                                    <td class="right">
                                                        <a href="#" title="View" class="btn btn-sm btn-warning pull-right view" onclick="loadThread('bot-profiles/read/2','');">
                                                            <i class="voyager-eye"></i>
                                                        </a>    
                
                                                        <a href="#" title="Edit" class="btn btn-sm btn-primary pull-right edit" onclick="loadThread('http://onsalenotification.in/admin/bot-profiles/2/edit', '2')">
                                                            <i class="voyager-edit"></i>
                                                        </a>
                                                    </td>  
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
        </div>  
    </div>
</div>