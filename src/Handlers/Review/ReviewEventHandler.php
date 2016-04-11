<?php namespace Sanatorium\Reviews\Handlers\Review;

use Illuminate\Events\Dispatcher;
use Sanatorium\Reviews\Models\Review;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class ReviewEventHandler extends BaseEventHandler implements ReviewEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.reviews.review.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.reviews.review.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.reviews.review.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.reviews.review.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.reviews.review.deleted', __CLASS__.'@deleted');
	}

	/**
	 * {@inheritDoc}
	 */
	public function creating(array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function created(Review $review)
	{
		$this->flushCache($review);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Review $review, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Review $review)
	{
		$this->flushCache($review);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Review $review)
	{
		$this->flushCache($review);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Reviews\Models\Review  $review
	 * @return void
	 */
	protected function flushCache(Review $review)
	{
		$this->app['cache']->forget('sanatorium.reviews.review.all');

		$this->app['cache']->forget('sanatorium.reviews.review.'.$review->id);
	}

}
