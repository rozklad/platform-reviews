<?php namespace Sanatorium\Reviews\Controllers\Frontend;

use Cart;
use Platform\Foundation\Controllers\Controller;
use Sentinel;
use View;
use Sanatorium\Pricing\Models\Currency;
use Product;

class ReviewsController extends Controller {

	/**
	 * Return the main view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index($active = 'reviews')
	{
		// @todo - move all the unecessary things to custom location
		$this->addresses = app('Sanatorium\Addresses\Repositories\Address\AddressRepositoryInterface');

        $this->orders = app('Sanatorium\Shoporders\Repositories\Order\OrderRepositoryInterface');

        $currency = Currency::getPrimaryCurrency();

        $user = Sentinel::getUser();

        $addresses = $this->addresses->where('user_id', $user->id)->get();

        $orders = $this->orders->where('user_id', $user->id)->orderBy('id', 'DESC')->get();

        $slips = $this->orders->where('user_id', $user->id)->orderBy('id', 'DESC')->get();

        $primaryAddresses = [];

        foreach( $addresses as $address ) {

            $primaryAddresses[$address->type] = $address;

        }

        if ( !isset($primaryAddresses['fakturacni']) ) {

            $primaryAddresses['fakturacni'] = new \Sanatorium\Addresses\Models\Address;

        }

        if ( !isset($primaryAddresses['dodaci']) ) {

            $primaryAddresses['dodaci'] = new \Sanatorium\Addresses\Models\Address;
            
        }

        View::share([ 'active' => $active ]);

        // Code of the reviews tab
        $reviews = app('sanatorium.reviews.review');

        $reviewables = [];

		foreach( $orders as $order ) {

			if ( $order->status->id != config('sanatorium-reviews.finished_status') )
				continue;

			// @todo - replace with something more efficient
			$cart = unserialize($order->cart);
			$item_collections = array_values($cart['items']);

			if ( !isset($item_collections[0]) )
				continue;

			// Check each item if it was reviewed
			foreach( $item_collections as $item ) {
				if ( !isset( $reviewables[$item->id] ) ) {
					if ( $product = Product::find($item->id) ) {

						$reviewable_id = $product->id;
						$reviewable_type = get_class($product);

						// Check if reviewed already
						$review = $reviews->where('reviewable_id', $reviewable_id)
							->where('reviewable_type', $reviewable_type)
							->where('user_id', $user->id)
							->first();

						$has_reviewed = !is_null($review);

						$reviewables[$product->id] = [
							'object' => $product,
							'has_reviewed' => $has_reviewed,
							'review' => $review
						];
					}
				}
			}

		}
		
		View::share([ 'reviewables' => $reviewables ]);
		
		return view('sanatorium/profile::auth.profile', compact(
			'addresses', 
            'primaryAddresses',
            'orders',
            'currency',
            'user',
            'active',
            'slips',
            'reviewables'
			));
	}

	// @todo - make entity agnostic
	public function product($id = '')
	{
		$products = app('sanatorium.shop.product');

		$product = $products->find($id);

		$user = Sentinel::getUser();

		if ( !$product || !$user )
			return redirect()->back();

		$reviewable_id = $product->id;

		$reviewable_type = get_class($product);

		$reviews = app('sanatorium.reviews.review');

		// Check if reviewed already
		$review = $reviews->where('reviewable_id', $reviewable_id)
			->where('reviewable_type', $reviewable_type)
			->where('user_id', $user->id)
			->first();

		$has_reviewed = !is_null($review);

		return view('sanatorium/reviews::form', compact('product', 'reviewable_id', 'reviewable_type', 'product', 'has_reviewed', 'review'));
	}

	public function submit()
	{
		$reviews = app('sanatorium.reviews.review');

		$user = Sentinel::getUser();

		if ( !$user )
			return redirect()->back();

		$review = $reviews->create([
			'user_id' => $user->id,
			'reviewable_type' => request()->get('reviewable_type'),
			'reviewable_id' => request()->get('reviewable_id'),
			'percent' => (int)request()->get('percent'),
			'text' => request()->get('text')
			]);

		return redirect()->back();
	}
}
