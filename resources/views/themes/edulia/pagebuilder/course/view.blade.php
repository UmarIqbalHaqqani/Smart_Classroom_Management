<section class="section_padding course home_course">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section_title">
                    <span class="section_title_meta">{{ pagesetting('course_sub_title') }}</span>
                    <h2>{{ pagesetting('course_title') }}</h2>
                    <p>{!! pagesetting('course_description') !!}</p>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <x-course :column="pagesetting('course_area_column')" :sorting="pagesetting('course_sorting')" :count="pagesetting('course_count')"> </x-course>
        </div>
    </div>
</section>
