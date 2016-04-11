<?php namespace Sanatorium\Reviews\Widgets;

class Hooks {

	public function order($order = null)
	{
		if ( !is_object($order) )
			return null;

		if ( !is_object($order->status) )
			return null;

		if ( $order->status->id != config('sanatorium-reviews.finished_status') )
			return null;

		return $order->status->id;
	}

	public function product($args = [])
	{
		extract($args);

		return '<p>' . trans('sanatorium/reviews::actions.rate', ['url' => route('sanatorium.reviews.product', ['id' => $product->id])]) . '</p>';
	}

	public function tab_nav()
	{
		return view('sanatorium/reviews::hooks/tab_nav');
	}

	public function tab_content()
	{
		return view('sanatorium/reviews::hooks/tab_content');
	}

}
