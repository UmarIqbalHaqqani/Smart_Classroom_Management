<div class="col-lg-12">
    <div class="row">
        @foreach ($photoGalleries as $value)
            <div class="col-lg-3 mb-4">
                <img src="{{ asset(@$value->gallery_image) }}" width="100%"
                    height="100%">
            </div>
        @endforeach
    </div>
</div>
