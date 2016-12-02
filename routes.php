<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	//return View::make('hello');
	return View::make('actors');
});

Route::get('/listone', function()
{
	//return View::make('hello');
	return View::make('list');
});


Route::get('getArtist',array('as'=>'getArtist','uses'=>'HomeController@getAllArtists'));
//Route::get('getArtist','HomeController@getAllArtists');
Route::get('/list','ArtistController@listAllArtists');
Route::get('/dan', 'HomeController@showLast');
Route::get('/homelist', 'HomeController@listAllArtists');