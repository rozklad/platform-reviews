<?php namespace Sanatorium\Reviews\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Reviews\Repositories\Review\ReviewRepositoryInterface;

class ReviewsController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Reviews repository.
	 *
	 * @var \Sanatorium\Reviews\Repositories\Review\ReviewRepositoryInterface
	 */
	protected $reviews;

	/**
	 * Holds all the mass actions we can execute.
	 *
	 * @var array
	 */
	protected $actions = [
		'delete',
		'enable',
		'disable',
	];

	/**
	 * Constructor.
	 *
	 * @param  \Sanatorium\Reviews\Repositories\Review\ReviewRepositoryInterface  $reviews
	 * @return void
	 */
	public function __construct(ReviewRepositoryInterface $reviews)
	{
		parent::__construct();

		$this->reviews = $reviews;
	}

	/**
	 * Display a listing of review.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/reviews::reviews.index');
	}

	/**
	 * Datasource for the review Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->reviews->grid();

		$columns = [
			'id',
			'user_id',
			'reviewable_type',
			'reviewable_id',
			'percent',
			'text',
			'created_at',
		];

		$settings = [
			'sort'      => 'created_at',
			'direction' => 'desc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.reviews.reviews.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new review.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new review.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating review.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating review.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified review.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->reviews->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/reviews::reviews/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.reviews.reviews.all');
	}

	/**
	 * Executes the mass action.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function executeAction()
	{
		$action = request()->input('action');

		if (in_array($action, $this->actions))
		{
			foreach (request()->input('rows', []) as $row)
			{
				$this->reviews->{$action}($row);
			}

			return response('Success');
		}

		return response('Failed', 500);
	}

	/**
	 * Shows the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return mixed
	 */
	protected function showForm($mode, $id = null)
	{
		// Do we have a review identifier?
		if (isset($id))
		{
			if ( ! $review = $this->reviews->find($id))
			{
				$this->alerts->error(trans('sanatorium/reviews::reviews/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.reviews.reviews.all');
			}
		}
		else
		{
			$review = $this->reviews->createModel();
		}

		// Show the page
		return view('sanatorium/reviews::reviews.form', compact('mode', 'review'));
	}

	/**
	 * Processes the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function processForm($mode, $id = null)
	{
		// Store the review
		list($messages) = $this->reviews->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/reviews::reviews/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.reviews.reviews.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}
