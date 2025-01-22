<div class="pb-collapse-wrapper">
    <form method="post" id="current-section-form">
        @csrf
        <input type="hidden" name="section_id" id="current-section-id" />
        <div id="section-settings-wrapper">
            <div class="at-empty-block-settings">
                <span>{{__('pagebuilder::pagebuilder.select_any_element')}}</span>
            </div>
    </form>
</div>