<?php namespace Sanatorium\Reviews\Repositories\Review;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class ReviewRepository implements ReviewRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Reviews\Handlers\Review\ReviewDataHandlerInterface
	 */
	protected $data;

	/**
	 * The Eloquent reviews model.
	 *
	 * @var string
	 */
	protected $model;

	/**
	 * Constructor.
	 *
	 * @param  \Illuminate\Container\Container  $app
	 * @return void
	 */
	public function __construct(Container $app)
	{
		$this->setContainer($app);

		$this->setDispatcher($app['events']);

		$this->data = $app['sanatorium.reviews.review.handler.data'];

		$this->setValidator($app['sanatorium.reviews.review.validator']);

		$this->setModel(get_class($app['Sanatorium\Reviews\Models\Review']));
	}

	/**
	 * {@inheritDoc}
	 */
	public function grid()
	{
		return $this
			->createModel();
	}

	/**
	 * {@inheritDoc}
	 */
	public function findAll()
	{
		return $this->container['cache']->rememberForever('sanatorium.reviews.review.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.reviews.review.'.$id, function() use ($id)
		{
			return $this->createModel()->find($id);
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForCreation(array $input)
	{
		return $this->validator->on('create')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForUpdate($id, array $input)
	{
		return $this->validator->on('update')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function store($id, array $input)
	{
		return ! $id ? $this->create($input) : $this->update($id, $input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(array $input)
	{
		// Create a new review
		$review = $this->createModel();

		// Fire the 'sanatorium.reviews.review.creating' event
		if ($this->fireEvent('sanatorium.reviews.review.creating', [ $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForCreation($data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Save the review
			$review->fill($data)->save();

			// Fire the 'sanatorium.reviews.review.created' event
			$this->fireEvent('sanatorium.reviews.review.created', [ $review ]);
		}

		return [ $messages, $review ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the review object
		$review = $this->find($id);

		// Fire the 'sanatorium.reviews.review.updating' event
		if ($this->fireEvent('sanatorium.reviews.review.updating', [ $review, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($review, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the review
			$review->fill($data)->save();

			// Fire the 'sanatorium.reviews.review.updated' event
			$this->fireEvent('sanatorium.reviews.review.updated', [ $review ]);
		}

		return [ $messages, $review ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the review exists
		if ($review = $this->find($id))
		{
			// Fire the 'sanatorium.reviews.review.deleted' event
			$this->fireEvent('sanatorium.reviews.review.deleted', [ $review ]);

			// Delete the review entry
			$review->delete();

			return true;
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function enable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => true ]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function disable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => false ]);
	}

}
