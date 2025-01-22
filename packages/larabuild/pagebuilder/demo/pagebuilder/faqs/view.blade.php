<div class="tmp-faq">
  <h2>{{ pagesetting('heading') }}</h2>
  <div class="tmp-faqsection">
    @if (!empty(pagesetting('faq_data')))
    @foreach (pagesetting('faq_data') as $faq)
    <div class="tmp-faqwrap">
      <div class="tmp-faqtitlle">
        <h6>{{ $faq['question'] }}</h6>
        <img src="{{ asset('demo/images/chevron-right.svg') }}" alt="">
      </div>
      <div class="tmp-faqcontent">
        <p>{!! $faq['answer'] !!}</p>
      </div>
    </div>
    @endforeach
    @endif
  </div>
</div>

@once
  @push('page-js')
    <script>
      const accordion = document.querySelectorAll(".tmp-faqwrap");
      for(let i=0;i<accordion.length;i++){
        accordion[i].addEventListener('click',function(){
          let isActive = this.classList.contains('active');
          for(let j=0;j<accordion.length;j++){
            accordion[j].classList.remove('active');
          }
          if(isActive){
            this.classList.remove('active');
          } else{
            this.classList.add('active');
          }
        })
      };
    </script>
  @endpush
@endonce
