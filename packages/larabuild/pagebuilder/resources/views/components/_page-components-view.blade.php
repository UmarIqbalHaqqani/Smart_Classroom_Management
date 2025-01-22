@if(!empty($grid['data']))
    @foreach ($grid['data'] as $column => $components)
        <div class="{{ $columns[$column] }}">
            @foreach ($components as $component)
                @php setSectionId($component['id']);@endphp
                @if(view()->exists('themes.'.activeTheme().'.pagebuilder.' . $component['section_id'] . '.view'))
                    {!! view('themes.'.activeTheme().'.pagebuilder.'. $component['section_id']. '.view')->render() !!}
                @else
                    {{-- {{ __('pagebuilder::pagebuilder.no_view',['block'=>$component['section_id']??'']) }} --}}
                @endif
            @endforeach
        </div>
    @endforeach
@endif