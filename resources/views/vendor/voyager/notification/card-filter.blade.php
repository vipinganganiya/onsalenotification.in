@if(($listings) && count($listings) > 0)  
    @foreach ($listings as $list)
    <div class="card mb-3 card-body">
        <div class="row">
            <div class="col-sm-2">   
                @if(!empty($list->club->image))
                    <img src="{{Voyager::image($list->getThumbnail($list->club->image, 'cropped'))}}" alt="{{$list->club->title}}" title="{{$list->club->title}}" class="width-90 rounded-3" />
                @else 
                  <img src="https://www.bootdey.com/image/100x80/FFB6C1/000000" alt="Contemporary Club" />
                @endif  
            </div>
            <div class="col-sm-6">
                <div class="overflow-hidden flex-nowrap">
                    <h6 class="mb-1">{{ $list->title}}</h6> 
                    <p><i class="voyager-location"></i> {{$list->club->title}}</p>
                    <span class="text-muted d-block mb-2 small">
                        <i class="glyphicon glyphicon-calendar"></i>  {{ \Carbon\Carbon::parse($list->date)->format('d M Y') }}   
                        &nbsp; <i class="glyphicon glyphicon-time"></i> &nbsp;{{ $list->time }}
                    </span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2 text-text">
                <span class="text-criteria"> <i class="glyphicon glyphicon-asterisk yellow"></i> {{ $list->criteria}} </span>
            </div>
            <div class="col-sm-3">
                <h5 class="pt-3 text-onsale text-600 text-primary-d1 letter-spacing"> 
                    {{ \Carbon\Carbon::parse($list->onsale_date)->format('d M Y') }} {{ $list->onsale_time }}
                </h5> 
            </div> 
            <div class=" col-sm-3"> 
                <h4 class="pt-3 text-countdown text-600 text-primary-d1"> 
                    <span data-countdown="{{ \Carbon\Carbon::parse($list->onsale_date)->format('Y/m/d')}} {{$list->onsale_time}}"> </span>
                </h4> 
            </div>
            <div class="col-sm-4">
                @if(!empty($list->URL))
                    <a href="{{$list->URL}}" class="f-n-hover btn btn-info btn-raised px-4 py-25 w-75 text-600" target="_blank">Go to Web</a>
                 @endif
            </div>
        </div>
    </div>  
    @endforeach
    <div class="col-md-12">
        <div class="text-center">
            <button id="load_more_button" onclick="filter(event, '{{$filterValue}}', '{{$next}}')" class="btn btn-primary">Load More</button>
        </div>
    </div>
@else 
    <div class="card mb-3 card-body">
        <div class="timetable-item norecord-found">
            <span>No Record Found</span>
        </div>
    </div>
@endif