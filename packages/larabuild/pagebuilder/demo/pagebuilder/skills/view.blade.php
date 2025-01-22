<div class="tmp-sectionskills">
  <div class="tmp-themecontainer">
    <div class="tmp-skillssect">
      <figure>
        @if (!empty(pagesetting('image')))
          <img src="{{ pagesetting('image')[0]['thumbnail'] }}" alt="{{ __('Image') }}" />
        @else
          <img src="{{ asset('demo/images/default-img.jpg') }}" alt="{{ __('Image') }}" />
        @endif
      </figure>
      <div class="tmp-skillwrap">
        <div class="tmp-sectiontitle">
          <ul class="tmp-colorlist">
            <li>
              <span></span>
            </li>
            <li>
              <span class="tmp-bgpink"></span>
            </li>
            <li>
              <span class="tmp-bgorange"></span>
            </li>
          </ul>
          @if (!empty(pagesetting('small-heading')))
            <h6>{{ pagesetting('small-heading') }}</h6>
          @endif
          @if (!empty(pagesetting('heading')))
            <h2>{{ pagesetting('heading') }}</h2>
          @endif
          <p>
            {!! pagesetting('paragraph') !!}
          </p>
        </div>
        <div class="tmp-countes">
          <h3>{{ pagesetting('counter') }}</h3>
          <span>{{ pagesetting('counter-text') }} </span>
          <a href="" class="tmp-btn">{{ pagesetting('button-cta') }} <img src="{{ asset('demo/images/iconcart.svg') }}"
              alt=""> </a>
        </div>
      </div>
    </div>
  </div>
</div>