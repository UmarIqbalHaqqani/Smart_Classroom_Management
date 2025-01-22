@if ($paginator->hasPages())
<nav class="pb-pagination_nav">
    <input type="hidden" name="current_page" id="current_page" value="{{$paginator->currentPage()}}" />
    <ul class="pb-pagination">
        @if ($paginator->onFirstPage())
        <li class="pb-d-none">
            <span class="icon-chevron-left"></span>
        </li>
        @else
        <li class="pb-prevpage">
            <a href="javascript:;" data-page="{{$paginator->currentPage() - 1}}" class="goto-page">
                <i class="icon-chevron-left"></i>
            </a>
        </li>
        @endif

        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
            <li class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
                @if ($paginator->currentPage() == $i)
                <span class="goto-page" data-page="{{$i}}">{{ $i }}</span>
                @else
                <a href="javascript:;" class="goto-page" data-page="{{$i}}">{{ $i
                    }}</a>
                @endif
            </li>
            @endfor

            @if ($paginator->hasMorePages())
            <li class="pb-nextpage">
                <a href="javascript:;" data-page="{{$paginator->currentPage() + 1}}" class="goto-page">
                    <i class="icon-chevron-right"></i>
                </a>
            </li>
            @else
            <li class="pb-d-none">
                <span class="icon-chevron-right"></span>
            </li>
            @endif
    </ul>
</nav>
@endif