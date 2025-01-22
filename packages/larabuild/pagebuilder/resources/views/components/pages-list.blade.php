@if (!$pages->isEmpty())
    <div class="row">
        <div class="col-lg-12">
            <x-table>
                <div class="table-responsive">
                    <table id="table_id" class="table page-list-table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>{{ __('common.sl') }}</th>
                                <th>{{ __('pagebuilder::pagebuilder.name') }}</th>
                                <th>{{ __('pagebuilder::pagebuilder.url') }}</th>
                                <th>{{ __('pagebuilder::pagebuilder.status') }}</th>
                                <th>{{ __('pagebuilder::pagebuilder.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pages as $key => $page)
                                <tr>
                                    <td data-label=""><span>{{ $loop->iteration }}</span></td>
                                    <td data-label="{{ __('pagebuilder::pagebuilder.name') }}">
                                        <span>{!! $page->name !!}</span> @if($page->home_page) <span class="badge badge-primary">Home</span> @endif 
                                    </td>
                                    <td data-label="{{ __('pagebuilder::pagebuilder.url') }}">
                                        <span>{{ url(!empty($page->slug) ? $page->slug : '/') }}</span>
                                    </td>
                                    <td data-label="{{ __('pagebuilder::pagebuilder.status') }}">
                                        <div class="tb-switchbtn">
                                            <input type="checkbox" class="tb-checkactionhome publish_status" id="home_page{{$page->id}}" data-id="{{$page->id}}" {{ $page->status == 'published' ? 'checked' : '' }}/>
                                        </div>
                                    </td>
                                    <td data-label="{{ __('pagebuilder::pagebuilder.actions') }}">
                                        <x-drop-down>
                                            <a class="dropdown-item" id="page_edit_btn"
                                                data-page-id={{ $page->id }}>
                                                @lang('common.edit')
                                            </a>
                                            <a class="dropdown-item"
                                                href="{{ route('pagebuilder.build', ['id' => $page->id]) }}"
                                                target="_blank">
                                                @lang('common.build')
                                            </a>
                                            <a class="dropdown-item" {!! $page->status != 'published' ? 'style="pointer-events: none;"' : '' !!}
                                                href="{{ url(!empty($page->slug) ? $page->slug : '/') }}"
                                                target="_blank">
                                                @lang('common.view')
                                            </a>
                                            <a class="dropdown-item deletePage"
                                                data-page-id="{{ $page->id }}">
                                                @lang('common.delete')
                                            </a>
                                        </x-drop-down>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-table>
        </div>
    </div>
    {!! $pages->links('pagebuilder::pagination.pb-pagination') !!}
@else
    @component('pagebuilder::components.no-record')
    @endcomponent
@endif
