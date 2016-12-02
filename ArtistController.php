<?php

class ArtistController extends BaseController {

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

     //public function getAllArtists(){

     	//$allArtists = Artist::getAllArtists();
	
     	//return Response::json(array('allartists' => $allArtists));


    // }

     public function listAllArtists(){

     	return View::make('list');

     }

     public function setArtists(){

     }

     public function deleteArtist(){

     	$id = Input::get();
     	$deleteArtist = Artist::deleteActor($id)

     	return $deleteArtist;

        

     }

}
