@php
    $variable = substr($videoGallery->video_link, 32, 11);
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <iframe width="710" height="320"
                src="https://www.youtube.com/embed/{{ $variable }}"
                frameborder="0"
                allowfullscreen>
            </iframe>
        </div>
    </div>
</div>
