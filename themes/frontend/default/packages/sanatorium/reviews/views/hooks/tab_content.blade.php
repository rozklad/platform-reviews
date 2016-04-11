@if ( isset($reviewables) )
<div role="tabpanel" class="tab-pane {{ (isset($active) && $active == 'reviews' ? 'active' : null) }}" id="reviews">
		
		@if ( count($reviewables) < 1 )
			<p class="alert alert-info text-center">
				{{ trans('sanatorium/reviews::reviews/common.nothing_to_review') }}
			</p>
		@else
			@if ( $active == 'reviews' )
			<h2 style="padding-top:20px">{{ trans('sanatorium/reviews::reviews/common.title') }}</h2>

			<table class="table">

			<tbody>

			@foreach( $reviewables as $reviewable_item )
				<?php 
				$product = $reviewable_item['object'];
				$has_reviewed = $reviewable_item['has_reviewed'];
				$review = $reviewable_item['review'];
				?>
				<tr class="product-row">
					<td class="text-center">
						@if ( $product->has_cover_image )
							<a href="{{ $product->url }}" target="_blank">
								<img src="{{ $product->coverThumb(60,60) }}" alt="{{ $product->product_title }}" width="60" height="60">
							</a>
						@else
							
						@endif
					</td>
					<td class="col-xs-6">
						<a href="{{ $product->url }}" target="_blank">{{ $product->product_title }}</a> 
						@if ( $product->code )
							<span class="text-muted">({{ $product->code }})</span>
						@endif
						
						@if ( $has_reviewed ) 
							<h4>{{ trans('sanatorium/reviews::common.reviewed') }}</h4>

							<p>{{ trans('sanatorium/reviews::common.reviewed_text') }}</p>

							<br>

							<div class="well">
								<strong>{{ $review->user->first_name }} {{ $review->user->last_name }}:</strong>


								<p>{{ $review->text }}</p>
							</div>
						@else
							@hook('product.bought', ['product' => $product])
						@endif
					</td>
					<td class="text-right">
						{{-- Price one --}}
						{{ $product->getPrice('vat', 1) }}
					</td>
				</tr>
			@endforeach

			</tbody>

		</table>
		@endif

	@endif

</div>
@endif