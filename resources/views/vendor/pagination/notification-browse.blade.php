 

<div class="col-sm-6">
    <div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">
        Showing {{($paginator->currentpage()-1)*$paginator->perpage()+1}} to {{$paginator->currentpage()*$paginator->perpage()}} of {{$paginator->total()}} entries
    </div>
</div>
@if ($paginator->hasPages())
<div class="col-sm-6">
    <div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">
        <ul class="pagination">
             {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true"><span>@lang('pagination.previous')</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a></li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">@lang('pagination.next')</a></li>
            @else
                <li class="disabled" aria-disabled="true"><span>@lang('pagination.next')</span></li>
            @endif
        </ul>
    </div>
</div>
@endif