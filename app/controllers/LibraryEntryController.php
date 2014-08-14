<?php

class LibraryEntryController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$ids = Input::get('ids');
		$userId = Input::get('userId');

		if ($userId) {
			$libraryEntries = LibraryEntry::with('anime')->where('user_id', '=', $userId)->get();
		} else if ($ids) {
			$libraryEntries = LibraryEntry::with('anime')->whereIn('id', $ids)->get();
		} else {
			$libraryEntries = LibraryEntry::with('anime')->get();
		}

		return Response::json(array(
			'libraryEntries' => $libraryEntries->toArray()),
			200
		);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$inputs = Input::get('libraryEntry');
		$libraryEntry = new LibraryEntry;

		$libraryEntry->status = $inputs['status'];
		$libraryEntry->anime_id = $inputs['anime_id'];
		$libraryEntry->user_id = $inputs['user_id'];
		$libraryEntry->episodes_seen = $inputs['episodes_seen'];

		// Defaults
		$libraryEntry->rewatched_count = 0;
		$libraryEntry->rewatching = 0; // false

		// TODO
		// Determine episodes seen
		// if status is completed and anime is on-going
		

		$libraryEntry->save();
		$id = $libraryEntry->id;

		return $this->show($id);

	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//$libraryEntries = LibraryEntry::find($id);
		$libraryEntries = LibraryEntry::with('anime')->where('id', '=', $id)->take(1)->get();

		return Response::json(array(
			'libraryEntry' => $libraryEntries[0]
			),
			200
		);

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$inputs = Input::get('libraryEntry');
		$libraryEntry = LibraryEntry::find($id);

		$libraryEntry->status = $inputs['status'];

		$libraryEntry->save();

		return $this->show($id);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
