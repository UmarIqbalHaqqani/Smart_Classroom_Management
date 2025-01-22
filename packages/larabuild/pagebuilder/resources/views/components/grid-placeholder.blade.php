@if($grid_id)
	@php
		setGridId( $grid_id );
		$css = getCss();
		if(!empty(getBgOverlay()))
		$css = 'position:relative;'.$css;
	@endphp
@endif

<section class="pb-themesection griddable {{ getClasses() }}" data-grid-name="{{ $grid }}" data-cols={!!json_encode($columns) !!} id="{{ $grid_id }}" {!! !empty($css)? 'style="' .$css.'"':'' !!}>
	{!! getBgOverlay() !!}
	<div {!! getContainerStyles() !!}>

		<div class="row pb-tooltip">
			@if(!empty($data))
				@foreach ($data as $column => $components)
					<div class="{{ !empty($columns[$column]) ? $columns[$column] : '' }} droppable nested-sortable">
						@if (!empty($css['background-overlay-color']))
						<div style="background-color: {{ $css['background-overlay-color'] }};
							position: absolute;
							left: 0;
							top: 0;
							width: 100%;
							height: 100%;">
						</div>
						@endif
						@foreach ($components as $component)
							@php
							setSectionId($component['id']);
							@endphp

							@if(view()->exists('themes.'.activeTheme().'.pagebuilder.' . $component['section_id'] . '.view'))	
								@component('pagebuilder::components.grid',
									[	
										'template_id' => $component['section_id'],
										'droppable'   => false,
										'id'          => $component['id']
									]
								)

									<div class="pb-section-content section-data-{{ $component['section_id'] }} w-100">
										{!! view('themes.'.activeTheme().'.pagebuilder.' . $component['section_id']. '.view')->render() !!}
									</div>
							
								@endcomponent
								
							@else
								@component('pagebuilder::components.grid', 
									[	
										'template_id' =>null,
										'droppable'   => true,
										'id'          => null,
									]
								)
									<div class="pb-addsection-info">
										<svg class="pb-svg-border">
											<rect width="100%" height="100%"></rect>
										</svg>
										<a href="javascript:;" class="iconPlus">
											<i class="icon-plus"></i>
										</a>
									</div>
								@endcomponent
								
							@endif
						@endforeach
					</div>
				@endforeach

			@else
				@foreach ($columns as $item)
				
					<div class="{{$item}} droppable nested-sortable">
						@component('pagebuilder::components.grid',['class'=>'','template_id'=>null,'id'=>'','droppable'=>true]) @endcomponent
					</div>

				@endforeach
			@endif

			@component('pagebuilder::components.grid-actions',['class'=>'','template_id'=>null,'id'=>'','droppable'=>false]) @endcomponent
		</div>
	</div>
</section>