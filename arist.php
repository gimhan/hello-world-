<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Artist extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'arist';

	public static function getAllArtists(){
		$all_artists=[];

		$getArist = DB::table('arist')
		          ->select('id','artist_name','artist_description')
		          ->get();
		          //var_dump($getArist);exit();

		          foreach($value as $getArist){
                     
                     array_push($all_artists,array(
                                      
                                   'id' => $value->id,
                                   'artist_name' => $value->artist_name,
                                   'artist_description' => $value->artist_description
                     	)
                     );

		          }
		          
		          
		          

		     return $all_artists;     

	}

	public static function setActors($artist_name,$artist_description){
           
           $add_actor                      = new Artist;
           $add_actor->artist_name         = $artist_name;
           $add_actor->artist_description  = $artist_description;
           $add_actor->save();

           return $add_actor;

	}

	public static deleteActor($id){

       $deleteActor = DB::table('arist'),
                    ->where('id',=,$id),
                    ->delete();

                    return deleteActor;


	}
}