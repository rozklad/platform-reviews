<li role="presentation" class="tab-account tab-account-reviews {{ (isset($active) && $active == 'reviews' ? 'active' : null) }}">
	<a href="{{ route('sanatorium.reviews.reviews.index') }}" aria-controls="reviews" role="tab">
		{{ trans('sanatorium/reviews::reviews/common.title') }}
	</a>
</li>