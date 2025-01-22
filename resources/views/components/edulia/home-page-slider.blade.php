<style>
    
    .hero_area_container_slide_item {
        padding-left: 12px!important;
        padding-right: 12px!important;
    }
</style>
<section class="hero_area_slider  owl-carousel">
    @if ($homeSliders->isEmpty())
        <div class="hero_area" id='slider-1'>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 p-0">
                        <div class="hero_area_inner">
                            <img src="public\theme\edulia\img\hero-bg-1.jpg" alt="hero slider">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        @foreach ($homeSliders as $index => $homeSlider)
            <div class="hero_area" id="slider-{{ $index + 1 }}">
                <div class="container-fluid hero_area_container_slide_item">
                    <div class="row">
                        <div class="col-md-12 p-0">
                            <div class="hero_area_inner">
                                <a href="{{$homeSlider->link ?? '#'}}" target="__blank">
                                    <img src="{{ asset($homeSlider->image) }}" alt="hero slider">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</section>
