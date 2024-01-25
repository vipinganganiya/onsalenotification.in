

<div class="modal-dialog" style="width: 90%;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <div class="container-fluid" style="background: #09b109 !important;">
                <div class="pull-left">
                    <h4 class="modal-title">
                        <i class="{{ $dataType->icon }}"></i> 
                        {{ __('voyager::generic.viewing') }} 
                        {{ ucfirst($dataType->getTranslatedAttribute('display_name_singular')) }} &nbsp; for: {{ $data->profile_name }}
                    </h4>
                    @can('browse', $dataTypeContent)
                        <!-- <a href="{{ route('voyager.'.$dataType->slug.'.index') }}" class="btn btn-warning">
                            <i class="glyphicon glyphicon-list"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.return_to_list') }}</span>
                        </a> -->
                    @endcan  
                </div>
            </div>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" style="border-color: #868484;">
                    <thead> 
                        <tr>
                            <th style="border-bottom-color: #868484;color:#918f8f;">Thread No.</th>
                            <th style="border-bottom-color: #868484;color:#918f8f;">Event Name</th>
                            <th style="border-bottom-color: #868484;border-right-color: #868484;color:#918f8f;">Status</th>
                            <th style="border-bottom-color: #868484;color:#918f8f;">Block</th>
                            <th style="border-bottom-color: #868484;color:#918f8f;">Row</th>
                            <th style="border-bottom-color: #868484;color:#918f8f;">Booked Seat</th>
                            <th style="border-bottom-color: #868484;color:#918f8f;">No. Of Seat</th>
                        </tr>
                    </thead>
                    @forelse ($data->thread as $list)
                    <thead> 
                        <tr>
                            <th style="border-bottom-color: #868484;color:#918f8f;">{{ $list->thread_no }}</th>
                            <th style="border-bottom-color: #868484;color:#918f8f;">{{ $list->event_name }}</th>
                            <th style="border-bottom-color: #868484;border-right-color: #868484;color:#918f8f;">{{ $list->status }}</th>
                            <th style="border-bottom-color: #868484;color:#918f8f;">{{ $list->block }}</th>
                            <th style="border-bottom-color: #868484;color:#918f8f;">{{ $list->seat_row }}</th>
                            <th style="border-bottom-color: #868484;color:#918f8f;">{{ $list->booked_seat }}</th>
                            <th style="border-bottom-color: #868484;color:#918f8f;">{{ $list->no_of_seats }}</th>
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