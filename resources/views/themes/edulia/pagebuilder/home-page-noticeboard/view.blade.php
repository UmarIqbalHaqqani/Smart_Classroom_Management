<section class="section_padding_off notice-section">
  <div class="container">
      <div class="row m-0">
          <div class="col-lg-12 col-xl-10 offset-xl-1 offset-0">
              <div class="row text-center" id='noticeContent'>
                  <div class="notice-board-header d-flex justify-content-between align-items-center mb-3">
                      <h2 class="notice-board-title mb-0">{{pagesetting('noticeboard_title')}}</h2>
                      <a class="notice-view-all" href="{{url('/').pagesetting('noticeboard_show_more_link')}}">{{pagesetting('noticeboard_show_more')}}</a>
                  </div>
                  <div class="notification-container">
                    <ul>
                      <x-home-page-noticeboard
                        :sorting="pagesetting('notice_gallery_sorting')" 
                        :count="pagesetting('notice_gallery_count')"
                      ></x-home-page-noticeboard>
                    </ul>
                  </div>
              </div>
          </div>
      </div>
  </div>
</section>
@pushonce(config('pagebuilder.site_script_var'))
    <script>
            // notification area

            var isAnimating = false;

        $(function() {
            let tickerLength = $('.notification-container ul li').length;
            let tickerHeight = $('.notification-container ul li').outerHeight();
            $('.notification-container ul li:last-child').prependTo('.notification-container ul');
            $('.notification-container ul').css('marginTop', -tickerHeight);
            
            var timer;
            function moveTop() {
                if (!isAnimating) {
                    isAnimating = true;
                    $('.notification-container ul').animate({
                      top: -tickerHeight - 10
                    }, 600, function() {
                      isAnimating = false;
                      $('.notification-container ul li:first-child').appendTo('.notification-container ul');
                      $('.notification-container ul').css('top', '');
                    });
                }
            }
            
            // Check if the mouse is hovered over the notification container
            var isHovered = false;
            $('.notification-container').hover(function() {
              isHovered = true;
            }, function() {
              isHovered = false;
            });
            
            // Pause the animation when the mouse is hovered over the notification container
            timer = setInterval(moveTop, 3000);
            $('.notification-container').on('mouseenter', function() {
              clearInterval(timer);
            }).on('mouseleave', function() {
              timer = setInterval(moveTop, 3000);
            });
        });
    </script>
@endpushonce