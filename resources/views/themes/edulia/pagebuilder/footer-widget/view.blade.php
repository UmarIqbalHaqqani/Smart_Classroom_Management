<div class="footer-item">
    <h5 style="color: {{pagesetting('footer-widget-bg-color')}}">{{pagesetting('footer-widget-heading')}}</h5>
    <ul class="footer-item-links">
        @if (!empty(pagesetting('footer-widget-links')))
            @foreach (pagesetting('footer-widget-links') as $link)
                <li>
                    <a href="{{ gv($link, 'footer-widget-url') }}" {{ gv($link, 'footer-widget-open-url') == 'new_tab' ? 'target="_blank"' : '' }} style="color: {{pagesetting('footer-widget-bg-color')}}">{{ gv($link, 'footer-widget-label') }}</a>
                </li>
            @endforeach
        @endif
    </ul>
</div>