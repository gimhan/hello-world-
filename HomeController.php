<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		return View::make('actors');
	}

	public function showLast()
	{
		return View::make('last');
	}

	 public function listAllArtists(){

     	return View::make('list');

     }

     public function getAllArtists(){

     	$allArtists = Artist::getAllArtists();
	
     	return Response::json(array('allartists' => $allArtists));


     }

}
