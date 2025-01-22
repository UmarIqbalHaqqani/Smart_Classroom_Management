@if ($paginator->hasPages())
    <div class="notification_pagination_container notification_list">
        <ul class="d-flex justify-content-center">
            @if ($paginator->onFirstPage())
                <li class="pagination_item disabled" aria-disabled="true">
                    <a class="" aria-hidden="true">
                        <i class="ti-arrow-left"></i>
                    </a>
                </li>
            @else
                <li class="pagination_item">
                    <a class="" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <i class="ti-arrow-left"></i>
                    </a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="pagination_item disabled" aria-disabled="true"><a href=""
                            class="">{{ $element }}</a></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="pagination_item" aria-current="page"><a href=""
                                    class="current ">{{ $page }}</a></li>
                        @else
                            <li class="pagination_item"><a href="{{ $url }}"
                                    class="">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="pagination_item">
                    <a class="" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        <i class="ti-arrow-right"></i>
                    </a>
                </li>
            @else
                <li class="pagination_item disabled" aria-disabled="true">
                    <a class="" aria-hidden="true">
                        <i class="ti-arrow-right"></i>
                    </a>
                </li>
            @endif
        </ul>
    </div>
@endif
