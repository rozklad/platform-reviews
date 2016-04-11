<?php namespace Sanatorium\Reviews\Repositories\Review;

interface ReviewRepositoryInterface {

	/**
	 * Returns a dataset compatible with data grid.
	 *
	 * @return \Sanatorium\Reviews\Models\Review
	 */
	public function grid();

	/**
	 * Returns all the reviews entries.
	 *
	 * @return \Sanatorium\Reviews\Models\Review
	 */
	public function findAll();

	/**
	 * Returns a reviews entry by its primary key.
	 *
	 * @param  int  $id
	 * @return \Sanatorium\Reviews\Models\Review
	 */
	public function find($id);

	/**
	 * Determines if the given reviews is valid for creation.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForCreation(array $data);

	/**
	 * Determines if the given reviews is valid for update.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForUpdate($id, array $data);

	/**
	 * Creates or updates the given reviews.
	 *
	 * @param  int  $id
	 * @param  array  $input
	 * @return bool|array
	 */
	public function store($id, array $input);

	/**
	 * Creates a reviews entry with the given data.
	 *
	 * @param  array  $data
	 * @return \Sanatorium\Reviews\Models\Review
	 */
	public function create(array $data);

	/**
	 * Updates the reviews entry with the given data.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Sanatorium\Reviews\Models\Review
	 */
	public function update($id, array $data);

	/**
	 * Deletes the reviews entry.
	 *
	 * @param  int  $id
	 * @return bool
	 */
	public function delete($id);

}
