<div class="tmp-services">
  <div class="tmp-sectiontitle-two">
    <h2>{{ pagesetting('heading') }}</h2>
    <p>{!! pagesetting('paragraph') !!}</p>
  </div>
  <ul class="tmp-services-list">
    @if (!empty(pagesetting('service_details')))
      @foreach (pagesetting('service_details') as $service)
        <li>
          <div class="tmp-services-items">
            @if (!empty($service['image']))
              <img src="{{ $service['image'][0]['thumbnail'] }}" alt="{{ __('Image') }}" />
            @else
              <img src="{{ asset('demo/images/default-img.jpg') }}" alt="{{ __('Image') }}" />
            @endif


            <div class="tmp-services-content">
              <h2>{{ $service['heading'] }}</h2>
              <p>{!! $service['paragraph'] !!}</p>
            </div>
            <a class="tmp-btn" href="{{ $service['cta-url'] }}">{{ $service['cta-text'] }}</a>
          </div>
        </li>
      @endforeach
    @endif
  </ul>
</div>