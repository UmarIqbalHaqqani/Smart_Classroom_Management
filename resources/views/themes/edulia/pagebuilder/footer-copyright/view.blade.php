<div class="footer_copyright">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="footer_copyright_inner">
                    {{ pagesetting('footer-copy-right-text') }}
                </div>
            </div>
            <div class="col-md-4 text-end">
                <nav>
                    <ul class='footer_copyright_social'>
                        @if (!empty(pagesetting('footer-social-icons')))
                            @foreach (pagesetting('footer-social-icons') as $socialIcon)
                                <li class='footer_copyright_social_list'>
                                    <a href="{{ gv($socialIcon, 'footer-social-icon-url') }}" target='_blank' class='footer_copyright_social_list_link'>
                                        <i class="{{ gv($socialIcon, 'footer-social-icon-class') }}"></i>{{ gv($socialIcon, 'footer-social-label') }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>