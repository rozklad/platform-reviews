<?php namespace Sanatorium\Reviews\Handlers\Review;

use Sanatorium\Reviews\Models\Review;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface ReviewEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a review is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a review is created.
	 *
	 * @param  \Sanatorium\Reviews\Models\Review  $review
	 * @return mixed
	 */
	public function created(Review $review);

	/**
	 * When a review is being updated.
	 *
	 * @param  \Sanatorium\Reviews\Models\Review  $review
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Review $review, array $data);

	/**
	 * When a review is updated.
	 *
	 * @param  \Sanatorium\Reviews\Models\Review  $review
	 * @return mixed
	 */
	public function updated(Review $review);

	/**
	 * When a review is deleted.
	 *
	 * @param  \Sanatorium\Reviews\Models\Review  $review
	 * @return mixed
	 */
	public function deleted(Review $review);

}
