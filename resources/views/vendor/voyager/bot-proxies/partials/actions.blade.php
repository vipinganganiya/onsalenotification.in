@if($data)
    @php
        // need to recreate object because policy might depend on record data
        $class = get_class($action);
        $action = new $class($dataType, $data);
    @endphp 
    @can ($action->getPolicy(), $data)
        @if ($action->shouldActionDisplayOnRow($data))
            @if($action->getTitle() == "View")
                <a href="#" title="{{ $action->getTitle() }}" {!! $action->convertAttributesToHtml() !!} onclick="editProfile('proxy/read/{{ $data->id }}');">
                    <i class="{{ $action->getIcon() }}"></i> 
                    </span>
                </a>
                    <!-- <span class="hidden-xs hidden-sm">{{ $action->getTitle() }} -->
                <!-- <span class="icon-stats">
                    <span title="{{ $action->getTitle() }}"  onclick="loadThread('bot-profiles/read/{{ $data->id }}');">
                        <i class="{{ $action->getIcon() }}"></i> 
                    </span> 
                </span> -->
            @else
                <a href="#" title="{{ $action->getTitle() }}" {!! $action->convertAttributesToHtml() !!} onclick="editProfile('{{ $action->getRoute($dataType->name) }}')">
                    <i class="{{ $action->getIcon() }}"></i> 
                    </span>
                </a>&nbsp;&nbsp;&nbsp;&nbsp;
                <!-- <span class="icon-stats">
                    <span  title="{{ $action->getTitle() }}" onclick="loadThread('{{ $action->getRoute($dataType->name) }}')">
                        <i class="{{ $action->getIcon() }}"></i> 
                    </span>  
                </span> --> 
            @endif  
            </span>
        @endif
    @endcan 
@elseif (method_exists($action, 'massAction'))
    <form method="post" action="{{ route('voyager.'.$dataType->slug.'.action') }}" style="display:inline">
        {{ csrf_field() }}
        <button type="submit" {!! $action->convertAttributesToHtml() !!}><i class="{{ $action->getIcon() }}"></i>  {{ $action->getTitle() }}</button>
        <input type="hidden" name="action" value="{{ get_class($action) }}">
        <input type="hidden" name="ids" value="" class="selected_ids">
    </form>
@endif