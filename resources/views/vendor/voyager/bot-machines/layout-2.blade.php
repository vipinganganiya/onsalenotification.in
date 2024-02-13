

<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <div class="container-fluid" style="background: #09b109 !important;">
                <div class="pull-left">
                    <h4 class="modal-title">
                        <i class="voyager-window-list"></i> Bot Machine - Overview
                    </h4>
                </div>
            </div>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <button type="button" class="btn btn-info btn-lg align-right" id="add_machine_btn">Add Machine</button>
                <table class="table table-bordered table-hover" style="border-color: #868484;">
                    <thead> 
                        <tr>
                            <th style="border-bottom-color: #868484;color:#918f8f;">Machine Unique Identifier.</th>
                            <th style="border-bottom-color: #868484;color:#918f8f;">Machine Name</th> 
                            <th style="border-bottom-color: #868484;color:#918f8f;">Actions</th>
                        </tr>
                    </thead>
                     @forelse ($machines as $machine)
                    <thead> 
                        <tr>
                            <th style="border-bottom-color: #868484;color:#918f8f;">{{ $machine->machine_unique_field }}</th>
                            <th style="border-bottom-color: #868484;color:#918f8f;">{{ $machine->machine_name }}</th> 
                            <th style="border-bottom-color: #868484;color:#918f8f;"></th> 
                        </tr>
                    </thead>
                    @empty
                    <tbody>
                        <tr>
                            <td colspan="6" class="text-center" style="font-style: italic;">No Data Available</td>
                        </tr>
                    </tbody>
                    @endforelse
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-danger view quote_cancel_btn" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>