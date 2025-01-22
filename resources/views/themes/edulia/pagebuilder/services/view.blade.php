<section class="section_padding">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1 col-md-12">
                <div class="tmp-services">
                    <div class="tmp-sectiontitle-two">
                        <h2>{{ pagesetting('heading') }}</h2>
                        <p>{!! pagesetting('paragraph') !!}</p>
                    </div>
                    <ul class="tmp-services-list">
                        @if (!empty(pagesetting('service_details')))
                            @foreach (pagesetting('service_details') as $service)
                                <li>
                                    <div class="tmp-services-items" style="margin-top:60px ">
                                        <div class="tmp-services-content">
                                            <h2>{{ $service['heading'] }}</h2>
                                            <p>{!! $service['paragraph'] !!}</p>
                                        </div>
                                        <a class="tmp-btn"
                                            href="{{ $service['cta-url'] }}">{{ $service['cta-text'] }}</a>
                                    </div>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
