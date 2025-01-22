<section class="section_padding faq_area">
  @if (pagesetting('faqs_heading'))
    <h2>{{ pagesetting('faqs_heading') }}</h2>
  @endif
    <div class="container mt-20">
      <div class="row">
        @if (!empty(pagesetting('faq_datas')))
          <div class="faq_area_accordion" id="accordionExample">
            @foreach (pagesetting('faq_datas') as $key => $data)
              <div class="accordion-item">
                  <h6 class="accordion-header" id="headingOne{{$key}}">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                          data-bs-target="#collapseOne{{$key}}" aria-expanded="false" aria-controls="collapseOne{{$key}}">
                          {{gv($data, 'faq_question')}}
                      </button>
                  </h6>
                  <div id="collapseOne{{$key}}" class="accordion-collapse collapse" aria-labelledby="headingOne{{$key}}"
                      data-bs-parent="#accordionExample">
                      <div class="accordion-body">
                        {!! gv($data, 'faq_answer') !!}
                      </div>
                  </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
</section>
