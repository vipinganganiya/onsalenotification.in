 <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Subscribe Club To User(s)</h4>
        </div>
        <div class="modal-body" id="modelHtml"> 
            @if(($users) && count($users) > 0)
            <div class="form-group">
                    <label for="users" class="col-sm-2 control-label">Select User</label>
                    <div class="col-sm-10">
                        <select multiple id="users" class="form-control">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div> 
            @endif

            @if(($clubs) && count($clubs) > 0)
                <div class="form-group">
                    <label for="clubs" class="col-sm-2 control-label">Selected Clubs</label>
                    <div class="col-sm-10">
                        <select multiple id="clubs" class="form-control">
                            @foreach($clubs as $club) {
                                <option selected value="{{ $club->id }}">{{$club->title}}</option>
                            @endforeach
                        </select>
                    </div>
                </div> 
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default assignBtn" onclick="assignClubToUser(null, 'multiple');">Subscribe Clubs</button> 
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div> 