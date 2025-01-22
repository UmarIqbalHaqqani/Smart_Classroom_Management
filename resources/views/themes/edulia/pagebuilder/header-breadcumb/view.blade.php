<section class="bradcrumb_area" style="background-image:url('{{(pagesetting('header_bg_image') ? pagesetting('header_bg_image')[0]['thumbnail'] : '')}}')">
  <div class="container">
      <div class="row">
          <div class="col-md-12">
              <div class="bradcrumb_area_inner">
                  <h1>{{ pagesetting('left_title') }} <span><a href="{{url('/')}}">{{ pagesetting('right_home') }} </a> {{!empty(pagesetting('right_content_title')) ? '/'.pagesetting('right_content_title') : ''}}</span></h1>
              </div>
          </div>
      </div>
  </div>
</section>