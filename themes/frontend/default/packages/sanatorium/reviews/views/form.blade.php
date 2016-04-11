@extends('layouts/default')

@section('styles')
@parent
<style type="text/css">
.ion-android-star,
.ion-android-star-outline {
	font-size: 21px;
}
.star-box {
	display: inline-block;
}
textarea {
	margin-bottom: 50px;
}

</style>
@stop

@section('scripts')
@parent
<script type="text/javascript">
$(function(){
	$('[name="percent"]').change(function(event){
		var $input = $(this),
			$box = $(this).parents('[data-count]'),
			count = $box.data('count');

		$('[data-count]').find('i').removeClass('ion-android-star').addClass('ion-android-star-outline');

		for ( var i = 1; i <= count; i++ ) {
			$('[data-count="'+i+'"]').find('i').addClass('ion-android-star').removeClass('ion-android-star-outline');
		}
	});
});
</script>
@stop

@section('page')

	<h1>{{ trans('sanatorium/reviews::common.write') }}</h1>

	<form action="{{ route('sanatorium.reviews.submit') }}" method="POST">
		
		<input type="hidden" name="reviewable_type" value="{{ $reviewable_type }}">
		<input type="hidden" name="reviewable_id" value="{{ $reviewable_id }}">

		<div class="row">
			<div class="col-sm-9">
				
				@if ( !$has_reviewed )
				<div class="form-group">
					{{-- User name --}}
					<label for="text" class="control-label">
						{{ $currentUser->first_name }} {{ $currentUser->last_name }}
					</label>

					{{-- Review --}}
					<textarea name="text" id="text" rows="8" class="form-control" placeholder="{{ trans('sanatorium/reviews::common.text_placeholder') }}"></textarea>
				</div>

				{{-- Rating --}}
				<div class="form-group">
					<label class="control-label">
						{{ trans('sanatorium/reviews::common.your_rating') }}
					</label>
					<br>
					<?php for ( $i = 1; $i <= 5; $i++ ) { ?>
						<div class="star-box text-center" data-count="{{ $i }}">
							<i class="ion-android-star"></i>
							<br>
							<input type="radio" name="percent" value="{{ $i * 20 }}" required {{ ($i == 5 ? 'checked' : '') }}>
						</div>
					<?php } ?>
				</div>
				
				<div class="form-group">
						<button type="submit" class="btn btn-primary">
						{{ trans('action.submit') }}
					</button>
				</div>

				@else 

					<h2>{{ trans('sanatorium/reviews::common.reviewed') }}</h2>

					<p>{{ trans('sanatorium/reviews::common.reviewed_text') }}</p>

					<br>

					<div class="well">
						
						<h3>
							{{ $review->user->first_name }}
							{{ $review->user->last_name }}
						</h3>

						<?php for ( $i = 1; $i <= ($review->percent/20); $i++ ) { ?>
							<div class="star-box text-center" data-count="{{ $i }}">
								<i class="ion-android-star"></i>
							</div>
						<?php } ?>

						<br>

						<p>{{ $review->text }}</p>

					</div>

					<a href="{{ route('user.orders') }}" class="text-bigger">
						<i class="ion-ios-arrow-thin-left"></i> {{ trans('sanatorium/shoporders::orders/common.actions.back') }}
					</a>

				@endif
			</div>
			<div class="col-sm-3">
				
				<div class="product-block text-center" itemscope itemtype="http://schema.org/Product">

					<a href="{{ $product->url }}" class="thumb-area product-image">

						<img src="{{ $product->coverThumb(200,134) }}" itemprop="image" alt="{{ $product->product_title }}">

					</a>

					<br>

					<h2 class="product-title" itemprop="name">
						<a href="{{ $product->url }}">	
							{{ $product->product_title }}
						</a>
					</h2>

					<br>

					<?php 
					/**
					 * Reviews part
					 */
					$reviews = app('sanatorium.reviews.review')->where('reviewable_id', $product->id)
									->where('reviewable_type', get_class($product))
									->get();

					$reviews_percent = app('sanatorium.reviews.review')->where('reviewable_id', $product->id)
									->where('reviewable_type', get_class($product))
									->sum('percent');

					if ( count($reviews) > 0 ) {
						$percentage = $reviews_percent / count($reviews);
					} else {
						$percentage = 100;
					}

					$stars = ($percentage/20);
					$outlined = 5-$stars;
					?>
					<?php for ( $i = 1; $i <= $stars; $i++ ) { ?>
						<i class="ion-android-star"></i>
					<?php } ?>
					<?php for ( $i = 1; $i <= $outlined; $i++ ) { ?>
						<i class="ion-android-star-outline"></i>
					<?php } ?>

					<br>

					<div class="price product-common-price product-price-default-vat" style="font-size:24px;font-weight:300">
						{{ $product->getPrice('vat', 1, 2) }}
					</div>

				</div>

			</div>
		</div>

	</form>

@stop