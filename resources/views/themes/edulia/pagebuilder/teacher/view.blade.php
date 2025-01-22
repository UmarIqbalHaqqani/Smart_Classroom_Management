<section class="section_padding teacher">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section_title">
                    <span class="section_title_meta">{{ pagesetting('teacher_sub_heading') }}</span>
                    <h2>{{ pagesetting('teacher_heading') }}</h2>
                </div>
            </div>
        </div>
        <x-teacher-list :column="pagesetting('teacher_area_column')" :count="pagesetting('teacher_count')"> </x-teacher-list>
       

    </div>
</section>
