<?php
/**
 * Created by PhpStorm.
 * User: mahissara
 * Date: 6/7/16
 * Time: 10:52 AM
 */

class SphereStatusUpdate extends Eloquent {

    protected $table = 'sphere_status_updates';

    public static function getAllSphereStatus($sphereid,$admin_id){

        $blocks = newsphere::getBlockedUsersInMemberList($sphereid,Auth::user()->id);


     	if($blocks == 0) {

			$allSphere=DB::table('sphere_status_updates')
				->join('users','sphere_status_updates.user_id','=','users.id')
				->join('user_details','users.id','=','user_details.user_id')
				->where('sphere_status_updates.sphere_id','=',$sphereid)
				->orderBy('sphere_status_updates.id', 'desc')
				->select(
					'sphere_status_updates.id',
					'sphere_status_updates.description',
					'sphere_status_updates.type',
					'sphere_status_updates.user_id',
					'sphere_status_updates.created_at',
					'users.id as userid',
					'users.first_name',
					'users.last_name',
					'user_details.avatar as profile_image'
				)
				->take(3)
				->get();

	        $sphere_status = [];

	        foreach($allSphere as $status) {

				$status_id = $status->id;

				if ($status->profile_image == '') {
					$profile_image = null;
				} else {
					$profile_image = $status->profile_image;
				}

	            $sphere_admin = DB::table('sphere')
				                 ->where('id','=',$sphereid)
				                 ->get();

				foreach($sphere_admin as $adminid){

					$sphere_admin_id = $adminid->user_id;
					//$sphere_name = $adminid->name;
				}

				$comments = DB::table('sphere_status_update_comments')
							->join('users','user_id','=','users.id')
							->join('user_details','users.id','=','user_details.user_id')
							->where('sphere_status_update_comments.sphere_status_update_id','=',$status_id)
							->orderBy('sphere_status_update_comments.created_at', 'desc')
							->select(
								'sphere_status_update_comments.id',
								'sphere_status_update_comments.sphere_status_update_id',
								'sphere_status_update_comments.comment',
								'sphere_status_update_comments.created_at',
								'users.id as comment_user_id',
								'users.first_name',
								'users.last_name',
								'user_details.avatar as profile_image'
							)
							->take(3)
							->get();

                $commentsCount = DB::table('sphere_status_update_comments')
                    ->where('sphere_status_update_comments.sphere_status_update_id','=',$status_id)
                    ->orderBy('sphere_status_update_comments.created_at', 'desc')
                    ->select(
                        'sphere_status_update_comments.id'
                    )
                    ->count();

				if($comments == null){
					$comments_for_status = 0;
				} else {
					$comments_for_status_update = [];

					foreach($comments as $value) {

						if ($value->profile_image == '') {
							$profile_image_comment = null;
						} else {
							$profile_image_comment = $value->profile_image;
						}

						array_push($comments_for_status_update,array(
								'id' 						=> $value->id,
								'sphere_status_update_id' 	=> $value->sphere_status_update_id,
								'comment' 					=> $value->comment,
								'created_at' 				=> $value->created_at,
								'comment_user_id' 			=> $value->comment_user_id,
								'first_name' 				=> $value->first_name,
								'last_name' 				=> $value->last_name,
								'profile_image' 			=> $profile_image_comment,
							)

						);
					}

					$comments_for_status = $comments_for_status_update;
				}

				$status_type = $status->type;

				if($status_type == 2){

					$image_name = [];

					$image = DB::table('sphere_status_images')
							->where('sphere_status_images.sphere_status_update_id','=',$status_id)
							->select(
								'sphere_status_images.image',
								'sphere_status_images.original_image',
								'sphere_status_images.id'
							)
							->get();

					foreach($image as $value){

						array_push($image_name,array(
							'id' 				=> $value->id,
							'images' 			=> $value->image,
							'original_image' 	=> $value->original_image,
							'link' 				=> 'https://s3.amazonaws.com/frontlinesl/users/'. $sphere_admin_id .'/spheres/'. $sphereid .'/images/'.$value->image,
							'original_link' 	=> 'https://s3.amazonaws.com/frontlinesl/users/'. $sphere_admin_id .'/spheres/'. $sphereid .'/images/'.$$value->original_image
						));

					}

				} else {

					$image_name=null;

				}


				if($status_type == 4){

					$file_name = [];

					$file= DB::table('sphere_status_files')
							->where('sphere_status_files.sphere_status_update_id','=',$status_id)
							->select(
							'sphere_status_files.file'
							)
							->get();

					foreach($file as $value) {

						array_push($file_name,array(
							'files'=>$value->file
						));
					}

				} else {
					$file_name = null;
				}

                $allSphereCount=DB::table('sphere_status_updates')
                    ->where('sphere_status_updates.sphere_id','=',$sphereid)
                    ->select(
                        'sphere_status_updates.id'
                    )
                    ->count();



               $status_count = $allSphereCount;
                $comment_count = $commentsCount;

                if($status_count > 3){
                    $load_status_no = 1;
                }else{
                    $load_status_no = 0;
                }

                if($comment_count > 3){
                    $load_comment_no = 1;
                }else{
                    $load_comment_no = 0;
                }

				array_push($sphere_status,array(
					'status_update_id' 	=> $status->id,
					'description' 		=> $status->description,
					'created_at' 		=> $status->created_at,
					'user_id' 			=> $status->userid,
					'first_name' 		=> $status->first_name,
					'last_name' 		=> $status->last_name,
					'profile_image' 	=> $profile_image,
					'status_file' 		=> $file_name,
					'status_image' 		=> $image_name,
					'sphere_id' 		=> $sphereid,
					'sphere_admin_id' 	=> $sphere_admin_id,
					'comments' 			=> $comments_for_status,
					'page' 				=> 2,
                    'load_comment_no'   => $load_comment_no,
                    'status_load'       => $load_status_no
				));
	        }

    } else {

		$allSphere = DB::table('sphere_status_updates')
					 ->join('users','sphere_status_updates.user_id','=','users.id')
					 ->join('user_details','users.id','=','user_details.user_id')
					 ->where('sphere_status_updates.sphere_id','=',$sphereid)
					 ->whereNotIn('sphere_status_updates.user_id', function($query){
						 $query->select(DB::raw('connect_profile_id'))
						       ->from('profile_blocks')
						       ->where('profile_blocks.profile_id', '=', Auth::user()->id);

					})
					->orderBy('sphere_status_updates.id', 'desc')
					->select(
						'sphere_status_updates.id',
						'sphere_status_updates.description',
						'sphere_status_updates.type',
						'sphere_status_updates.user_id',
						'sphere_status_updates.created_at',
						'users.id as userid',
						'users.first_name',
						'users.last_name',
						'user_details.avatar as profile_image'
					)
					->take(3)
					->get();

        $sphere_status = [];

        foreach($allSphere as $status) {

            $status_id = $status->id;

//                 foreach($image as $value){
//                     //$image_name = $value->image;
//                      array_push($image_name,array(
//                            'id'              => $value->id,
//                            'images'          => $value->image,
//                            'original_image'  => $value->original_image,
//                            'link'            => 'https://s3.amazonaws.com/frontlinesl/users/'. $sphere_admin_id .'/spheres/'. $sphereid .'/images/'.$value->image,
//                            'original_link'   => 'https://s3.amazonaws.com/frontlinesl/users/'. $sphere_admin_id .'/spheres/'. $sphereid .'/images/'.$value->original_image
//                     ));
//
//
//                 }
//             }else{
//
//                 $image_name=null;
//
//             }
//
//
//             if($status_type == 4){
//                 $file_name = [];
//
//                 $file= DB::table('sphere_status_files')
//                        ->where('sphere_status_files.sphere_status_update_id','=',$status_id)
//                        ->select(
//                         'sphere_status_files.file'
//                          )
//                        ->get();
//
//                 foreach($file as $value){
//                     //$file_name = $value->file;
//
//                           array_push($file_name,array(
//                                  'files'=>$value->file
//                                   ));
//
//                     //$destination_file = 'users/' .$admin_id.'/spheres/'. $sphereid .'/files/';
//                     //$file_return = FileFormats::getFile($file_name,$destination_file);
//
//
//                   }
//                 }else{
//                     $file_name = null;
//                 }


            if ($status->profile_image == '') {
                $profile_image = null;
            } else {
                $profile_image = $status->profile_image;
            }

			$sphere_admin = DB::table('sphere')
							 ->where('id','=',$sphereid)
							 ->get();

			foreach($sphere_admin as $adminid){
				$sphere_admin_id = $adminid->user_id;
			}

			$comments= DB::table('sphere_status_update_comments')
			               ->join('users','user_id','=','users.id')
			               ->join('user_details','users.id','=','user_details.user_id')
			               ->where('sphere_status_update_comments.sphere_status_update_id','=',$status_id)
			               ->whereNotIn('sphere_status_update_comments.user_id', function($query){
			               $query->select(DB::raw('connect_profile_id'))
			                                  ->from('profile_blocks')
			                                  ->where('profile_blocks.profile_id', '=', Auth::user()->id);

			                        })
			                ->orderBy('sphere_status_update_comments.created_at', 'desc')
			                ->select(
			                         'sphere_status_update_comments.id',
			                         'sphere_status_update_comments.sphere_status_update_id',
			                         'sphere_status_update_comments.comment',
			                         'sphere_status_update_comments.created_at',
			                         'users.id as comment_user_id',
			                         'users.first_name',
			                         'users.last_name',
			                         'user_details.avatar as profile_image'
			                         )
			                ->take(3)
			                ->get();

            $commentsCount = DB::table('sphere_status_update_comments')
                ->where('sphere_status_update_comments.sphere_status_update_id','=',$status_id)
                ->orderBy('sphere_status_update_comments.created_at', 'desc')
                ->select(
                    'sphere_status_update_comments.id'
                )
                ->count();

			if($comments == null){
				$comments_for_status = 0;
			} else {

				$comments_for_status_update = [];
				foreach($comments as $value){

					if ($value->profile_image == '') {
						$profile_image_comment = null;
					} else {
						$profile_image_comment = $value->profile_image;
					}

					array_push($comments_for_status_update,array(

						'id' 						=> $value->id,
						'sphere_status_update_id' 	=> $value->sphere_status_update_id,
						'comment' 					=> $value->comment,
						'created_at' 				=> $value->created_at,
						'comment_user_id' 			=> $value->comment_user_id,
						'first_name' 				=> $value->first_name,
						'last_name' 				=> $value->last_name,
						'profile_image' 			=> $profile_image_comment,
					)

					);
				}

				$comments_for_status = $comments_for_status_update;
			}

            $status_type = $status->type;

            if($status_type == 2) {

				$image_name = [];

				$image = DB::table('sphere_status_images')
						->where('sphere_status_images.sphere_status_update_id','=',$status_id)
						->select(
							'sphere_status_images.image',
							'sphere_status_images.original_image',
							'sphere_status_images.id'
						        )
						->get();

				foreach($image as $value){

					array_push($image_name,array(
					 	'id' 				=> $value->id,
					 	'images' 			=> $value->image,
					 	'original_image' 	=> $value->original_image,
					 	'link' 				=> 'https://s3.amazonaws.com/frontlinesl/users/'. $sphere_admin_id .'/spheres/'. $sphereid .'/images/'.$value->image,
					 	'original_link' 	=> 'https://s3.amazonaws.com/frontlinesl/users/'. $sphere_admin_id .'/spheres/'. $sphereid .'/images/'.$value->original_image
					));
				}

            } else {
                $image_name = null;
            }

			if($status_type == 4) {

				$file_name = [];

				$file = DB::table('sphere_status_files')
				->where('sphere_status_files.sphere_status_update_id','=',$status_id)
				->select(
					'sphere_status_files.file'
				)
				->get();

				foreach($file as $value) {
					array_push($file_name,array(
						'files' => $value->file
					));
				}

			}else{
				$file_name = null;
			}

            $allSphereCount=DB::table('sphere_status_updates')
                ->where('sphere_status_updates.sphere_id','=',$sphereid)
                ->select(
                    'sphere_status_updates.id'
                )
                ->count();



            $status_count = $allSphereCount;
            $comment_count = $commentsCount;

            if($status_count > 3){
                $load_status_no = 1;
            }else{
                $load_status_no = 0;
            }

            if($comment_count > 3){
                $load_comment_no = 1;
            }else{
                $load_comment_no = 0;
            }

            //if($status_count)

            //var_dump($status_count);
            //var_dump($comment_count);exit;

			array_push($sphere_status,array(
				'status_update_id'	=> $status->id,
				'description'		=> $status->description,
				'created_at'		=> $status->created_at,
				'user_id'			=> $status->userid,
				'first_name'		=> $status->first_name,
				'last_name'			=> $status->last_name,
				'profile_image'		=> $profile_image,
				'status_file'		=> $file_name,
				'status_image'		=> $image_name,
				'sphere_id'			=> $sphereid,
				'sphere_admin_id'	=> $sphere_admin_id,
				'comments'			=> $comments_for_status,
				'page'				=> 2,
				'load_comment_no'   => $load_comment_no,
                'status_load'       => $load_status_no
			));

        }
         //var_dump($allSphere);exit();

     }

         return $sphere_status;
     }

    public static function getSphereStatusUpdateLoadMore($sphereid,$page) {

        $blocks = newsphere::getBlockedUsersInMemberList($sphereid,Auth::user()->id);

        if($blocks == 0) {
        $allSphere = DB::table('sphere_status_updates')
		            ->join('users','sphere_status_updates.user_id','=','users.id')
		            ->join('user_details','users.id','=','user_details.user_id')
		            ->where('sphere_status_updates.sphere_id','=',$sphereid)
		            ->orderBy('sphere_status_updates.id', 'desc')
		            ->select(
		                'sphere_status_updates.id',
		                'sphere_status_updates.description',
		                'sphere_status_updates.type',
		                'sphere_status_updates.created_at',
		                'users.id as userid',
		                'users.first_name',
		                'users.last_name',
		                'user_details.avatar as profile_image'
		            )
		            ->skip((3*$page)-3)
		            ->take(3)
		            ->get();
        }else{

            $allSphere=DB::table('sphere_status_updates')
                ->join('users','sphere_status_updates.user_id','=','users.id')
                ->join('user_details','users.id','=','user_details.user_id')
                ->where('sphere_status_updates.sphere_id','=',$sphereid)
                ->orderBy('sphere_status_updates.id', 'desc')
                ->select(
                    'sphere_status_updates.id',
                    'sphere_status_updates.description',
                    'sphere_status_updates.type',
                    'sphere_status_updates.user_id',
                    'sphere_status_updates.created_at',
                    'users.id as userid',
                    'users.first_name',
                    'users.last_name',
                    'user_details.avatar as profile_image'
                )
                ->take(3)
                ->skip((3*$page)-3)
                ->get();
        }

        //var_dump($allSphere);exit();

        $sphere_status = [];

        foreach($allSphere as $status) {

            $status_id = $status->id;

           // if($blocks == 0){

                if ($status->profile_image == '') {
                    $profile_image = null;
                } else {
                    $profile_image = $status->profile_image;
                }

                $sphere_admin = DB::table('sphere')
			                    ->where('id','=',$sphereid)
			                    ->get();

                foreach($sphere_admin as $adminid){
                    $sphere_admin_id = $adminid->user_id;
                }

                if($blocks == 0) {
                    $comments = DB::table('sphere_status_update_comments')
                        ->join('users','user_id','=','users.id')
                        ->join('user_details','users.id','=','user_details.user_id')
                        ->where('sphere_status_update_comments.sphere_status_update_id','=',$status_id)
                        ->orderBy('sphere_status_update_comments.created_at', 'desc')
                        ->select(
                            'sphere_status_update_comments.id',
                            'sphere_status_update_comments.sphere_status_update_id',
                            'sphere_status_update_comments.comment',
                            'sphere_status_update_comments.created_at',
                            'users.id as comment_user_id',
                            'users.first_name',
                            'users.last_name',
                            'user_details.avatar as profile_image'
                        )
                        ->take(3)
                        ->get();
                }else{

                    $comments= DB::table('sphere_status_update_comments')
                        ->join('users','user_id','=','users.id')
                        ->join('user_details','users.id','=','user_details.user_id')
                        ->where('sphere_status_update_comments.sphere_status_update_id','=',$status_id)
                        ->whereNotIn('sphere_status_update_comments.user_id', function($query){
                            $query->select(DB::raw('connect_profile_id'))
                                ->from('profile_blocks')
                                ->where('profile_blocks.profile_id', '=', Auth::user()->id);

                        })
                        ->orderBy('sphere_status_update_comments.created_at', 'desc')
                        ->select(
                            'sphere_status_update_comments.id',
                            'sphere_status_update_comments.sphere_status_update_id',
                            'sphere_status_update_comments.comment',
                            'sphere_status_update_comments.created_at',
                            'users.id as comment_user_id',
                            'users.first_name',
                            'users.last_name',
                            'user_details.avatar as profile_image'
                        )
                        ->take(3)
                        ->get();

                }

                $commentsCount = DB::table('sphere_status_update_comments')
                    ->where('sphere_status_update_comments.sphere_status_update_id','=',$status_id)
                    ->orderBy('sphere_status_update_comments.created_at', 'desc')
                    ->select(
                        'sphere_status_update_comments.id'
                    )
                    ->count();

                if($comments == null){
                    $comments_for_status = 0;
                } else {

                    $comments_for_status_update = [];
                    foreach($comments as $value){

                        if ($value->profile_image == '') {
                            $profile_image_comment = null;
                        } else {
                            $profile_image_comment = $value->profile_image;
                        }

                        array_push($comments_for_status_update,array(

                                'id'						=> $value->id,
                                'sphere_status_update_id'	=> $value->sphere_status_update_id,
                                'comment'					=> $value->comment,
                                'created_at'				=> $value->created_at,
                                'comment_user_id'			=> $value->comment_user_id,
                                'first_name'				=> $value->first_name,
                                'last_name'					=> $value->last_name,
                                'profile_image'				=> $profile_image_comment,


                            )

                        );

                    }
                    $comments_for_status = $comments_for_status_update;
                }

                $status_type = $status->type;

                if($status_type == 2){

                    $image_name = [];

                    $image= DB::table('sphere_status_images')
                        ->where('sphere_status_images.sphere_status_update_id','=',$status_id)
                        ->select(
                            'sphere_status_images.image',
                            'sphere_status_images.original_image',
                            'sphere_status_images.id'
                        )
                        ->get();

                    foreach($image as $value){
                        //$image_name = $value->image;
                        array_push($image_name,array(
                            'id'				=> $value->id,
                            'images'			=> $value->image,
                            'original_image'	=> $value->original_image,
                            'link'				=> 'https://s3.amazonaws.com/frontlinesl/users/'. $sphere_admin_id .'/spheres/'. $sphereid .'/images/'.$value->image,
                            'original_link'		=> 'https://s3.amazonaws.com/frontlinesl/users/'. $sphere_admin_id .'/spheres/'. $sphereid .'/images/'.$$value->original_image
                        ));
                    }

                } else {
                    $image_name=null;
                }


                if($status_type == 4) {
                    $file_name = [];

                    $file = DB::table('sphere_status_files')
                        ->where('sphere_status_files.sphere_status_update_id','=',$status_id)
                        ->select(
                            'sphere_status_files.file'
                        )
                        ->get();

                    foreach($file as $value){
                        array_push($file_name,array(
                            'files'=>$value->file
                        ));
                    }

                } else {
                    $file_name = null;
                }

                $allSphereCount=DB::table('sphere_status_updates')
                    ->where('sphere_status_updates.sphere_id','=',$sphereid)
                    ->select(
                        'sphere_status_updates.id'
                    )
                    ->count();



                $status_count = $allSphereCount;
                $comment_count = $commentsCount;

                $staus_skip_count = $page *3;

                            if($status_count > $staus_skip_count){
                                $load_status_no = 1;
                            }else{
                                $load_status_no = 0;
                            }

                                if($comment_count > 3){
                                    $load_comment_no = 1;
                                }else{
                                    $load_comment_no = 0;
                                }



                array_push($sphere_status,array(
                    'status_update_id' 	=> $status->id,
                    'description' 		=> $status->description,
                    'created_at' 		=> $status->created_at,
                    'user_id' 			=> $status->userid,
                    'first_name' 		=> $status->first_name,
                    'last_name' 		=> $status->last_name,
                    'profile_image' 	=> $profile_image,
                    'status_file' 		=> $file_name,
                    'status_image' 		=> $image_name,
                    'sphere_id' 		=> $sphereid,
                    'sphere_admin_id' 	=> $sphere_admin_id,
                    'comments' 			=> $comments_for_status,
                    'page' 				=> 2,
                    'load_comment_no'   => $load_comment_no,
                    'status_load'       => $load_status_no
                ));
            //}

//            } else {
//
//                foreach ($blocks as $block_users) {
//
//                    $block_id = $block_users->member_id;
//
//                    if($status->userid != $block_id) {
//
//                        if ($status->profile_image == '') {
//                            $profile_image = null;
//                        } else {
//                            $profile_image = $status->profile_image;
//                        }
//
//                        $sphere_admin=DB::table('sphere')
//                            ->where('id','=',$sphereid)
//                            ->get();
//
//                        foreach($sphere_admin as $adminid){
//                            $sphere_admin_id = $adminid->user_id;
//                        }
//
//                        $comments= DB::table('sphere_status_update_comments')
//		                            ->join('users','user_id','=','users.id')
//		                            ->join('user_details','users.id','=','user_details.user_id')
//		                            ->where('sphere_status_update_comments.sphere_status_update_id','=',$status_id)
//		                            ->orderBy('sphere_status_update_comments.created_at', 'desc')
//		                            ->select(
//		                                'sphere_status_update_comments.id',
//		                                'sphere_status_update_comments.sphere_status_update_id',
//		                                'sphere_status_update_comments.comment',
//		                                'sphere_status_update_comments.user_id',
//		                                'sphere_status_update_comments.created_at',
//		                                'users.id as comment_user_id',
//		                                'users.first_name',
//		                                'users.last_name',
//		                                'user_details.avatar as profile_image'
//		                            )
//		                            ->take(3)
//		                            ->get();
//
//                        $commentsCount = DB::table('sphere_status_update_comments')
//                            ->where('sphere_status_update_comments.sphere_status_update_id','=',$status_id)
//                            ->orderBy('sphere_status_update_comments.created_at', 'desc')
//                            ->select(
//                                'sphere_status_update_comments.id'
//                            )
//                            ->count();
//
//                        if($comments == null){
//                            $comments_for_status = 0;
//                        } else {
//                            $comments_for_status_update = [];
//
//                            foreach($comments as $value){
//                                $comment_user = $value->user_id;
//
//                                if($status->userid != $comment_user){
//                                    if ($value->profile_image == '') {
//                                        $profile_image_comment = null;
//                                    } else {
//                                        $profile_image_comment = $value->profile_image;
//                                    }
//
//                                    array_push($comments_for_status_update,array(
//	                                        'id'						=> $value->id,
//	                                        'sphere_status_update_id'	=> $value->sphere_status_update_id,
//	                                        'comment'					=> $value->comment,
//	                                        'created_at'				=> $value->created_at,
//	                                        'comment_user_id'			=> $value->comment_user_id,
//	                                        'first_name'				=> $value->first_name,
//	                                        'last_name'					=> $value->last_name,
//	                                        'profile_image'				=> $profile_image_comment,
//                                        )
//
//                                    );
//                                }
//                            }
//
//                            $comments_for_status = $comments_for_status_update;
//                        }
//
//                        $status_type = $status->type;
//
//                        if($status_type == 2){
//
//                            $image_name = [];
//
//                            $image= DB::table('sphere_status_images')
//                                ->where('sphere_status_images.sphere_status_update_id','=',$status_id)
//                                ->select(
//                                    'sphere_status_images.image',
//                                    'sphere_status_images.original_image',
//                                    'sphere_status_images.id'
//                                )
//                                ->get();
//
//                            foreach($image as $value){
//                                //$image_name = $value->image;
//                                array_push($image_name,array(
//                                      'id'             => $value->id,
//                                      'images'         => $value->image,
//                                      'original_image' => $value->original_image,
//                                      'link'           => 'https://s3.amazonaws.com/frontlinesl/users/'. $sphere_admin_id .'/spheres/'. $sphereid .'/images/'.$value->image,
//                                      'original_link'  => 'https://s3.amazonaws.com/frontlinesl/users/'. $sphere_admin_id .'/spheres/'. $sphereid .'/images/'.$$value->original_image
//                                ));
//
//                                //$destination_images = 'users/' .$admin_id.'/spheres/'. $sphereid .'/images/';
//                                //$image_return = FileFormats::getFile($image_name,$destination_images);
//
//                            }
//
//                        } else {
//                            $image_name=null;
//
//                        }
//
//
//                        if($status_type == 4) {
//
//                            $file_name = [];
//
//                            $file= DB::table('sphere_status_files')
//                                       ->where('sphere_status_files.sphere_status_update_id','=',$status_id)
//                                       ->select(
//                                          'sphere_status_files.file'
//                                               )
//                                       ->get();
//
//                            foreach($file as $value){
//                                //$file_name = $value->file;
//
//                                array_push($file_name,array(
//                                    'files'=>$value->file
//                                ));
//
//                                //$destination_file = 'users/' .$admin_id.'/spheres/'. $sphereid .'/files/';
//                                //$file_return = FileFormats::getFile($file_name,$destination_file);
//
//
//                            }
//                        } else {
//                            $file_name = null;
//                        }
//
//                        $status_count = $allSphereCount;
//                        $comment_count = $commentsCount;
//
//                        if($status_count > 3){
//                            $load_status_no = 1;
//                        }else{
//                            $load_status_no = 0;
//                        }
//
//                        if($comment_count > 3){
//                            $load_comment_no = 1;
//                        }else{
//                            $load_comment_no = 0;
//                        }
//
//
//                        array_push($sphere_status,array(
//
//                            'status_update_id'	=> $status->id,
//                            'description'		=> $status->description,
//                            'created_at'		=> $status->created_at,
//                            'user_id'			=> $status->userid,
//                            'first_name'		=> $status->first_name,
//                            'last_name'			=> $status->last_name,
//                            'profile_image'		=> $profile_image,
//                            'status_file'		=> $file_name,
//                            'status_image'		=> $image_name,
//                            'sphere_id'			=> $sphereid,
//                            'sphere_admin_id'	=> $sphere_admin_id,
//                            'comments'			=> $comments_for_status,
//                            'page'				=> 2,
//                            'load_comment_no'   => $load_comment_no,
//                            'status_load'       => $load_status_no
//                        ));
//                    }
                //}
            //}
        }

        return $sphere_status;

    }

    public static function addSphereStatusUpdate($sphereid,$status,$user,$type){

        $add 				= new SphereStatusUpdate;
        $add->sphere_id 	= $sphereid;
        $add->description 	= $status;
        $add->type 			= $type;
        $add->user_id 		= $user;
        $add->save();

        $status_update_result = DB::table('sphere_status_updates')
					            ->join('users','sphere_status_updates.user_id','=','users.id')
					            ->join('user_details','users.id','=','user_details.user_id')
					            ->where('sphere_status_updates.sphere_id','=',$sphereid)
					            ->orderBy('sphere_status_updates.id', 'desc')
					            ->select(
					                'sphere_status_updates.id',
					                'sphere_status_updates.description',
					                'sphere_status_updates.created_at',
					                'users.id as user_id',
					                'users.first_name',
					                'users.last_name',
					                'user_details.avatar as profile_image'
					                   )
					            ->orderBy('id', 'desc')
					            ->take(1)->get();

        $added_status_update = [];

        foreach($status_update_result as $status){

            $status_user_first_name = $status->first_name;
            $status_user_last_name 	= $status->last_name;
            $status_user_user_id 	= $status->user_id;
            $status_id 				= $status->id;
            //$status_user_profile_image = $status->profile_image;

            if ($status->profile_image == '') {
                $status_user_profile_image = null;
            } else {
                $status_user_profile_image = $status->profile_image;
            }

            array_push($added_status_update,array(
                'id'				=> $status->id,
                'description'		=> $status->description,
                'created_at'		=> $status->created_at,
                'user_id'			=> $status_user_user_id,
                'first_name'		=> $status_user_first_name,
                'last_name'			=> $status_user_last_name,
                'profile_image'		=> $status_user_profile_image,
                'status_file'		=> null,
                'status_image'		=> null,
                'sphere_admin_id'	=> null,
                'sphere_id'			=> null,
                'comments'			=> 0
            ));

        }

        $return_added_status = array(
            'all_status_updates' => $added_status_update,
        );

        $recivers 		= array();
        $connections 	= DB::table('sphere_members')
				            ->where('sphere_id','=',$sphereid)
				            ->where('status','=',1)
				            ->select(
				                'member_id'
				               )
				            ->get();

        foreach($connections as $mem){
            array_push($recivers,$mem->member_id);
        }

        $redis 				= Redis::connection();
        $logged_user 		= Auth::user()->id;
        $other_receivers 	= [];

        $notified_user_list = array();

        for($i=0;$i<count($recivers);$i++){

            if($recivers[$i] != Auth::user()->id){
                $id = $recivers[$i];

                $list_item				 	= $redis->incr('sphere'. $id .'_count');
                $notified_user_list[$id] 	= $list_item;
                $other_receivers 			= $id;

            }
        }

        $list 				= $notified_user_list;
        $description 		= 'has shared knowledge on Sphere ';

        $add_notifications						= new Notification;
        $add_notifications->item 				= $description;

        $add_notifications->inform_profile_id 	= Auth::user()->id;
        $add_notifications->notify_type 		= 1;
        $add_notifications->main_status_id 		= $status_id;
        $add_notifications->notify_type_id 		= $sphereid;
        $add_notifications->save();

        $notify_result = DB::table('notifications')->orderBy('id', 'desc')	->take(1)->get();

        foreach ($notify_result as $value){
            $notification_id  = $value->id;
        }

        for($i=0;$i<count($recivers);$i++){

            if($recivers[$i] != Auth::user()->id){

                $informas_add 					= new NotificationInformers;
                $informas_add->notification_id 	= $notification_id;
                $informas_add->user_id 			= $recivers[$i];
                $informas_add->save();
            }

        }

        Event::fire(SphereStatusEvent::EVENT,array(
            json_encode(
                array(
                    'sender'	=> Auth::user()->id,
                    'reciver'	=> $other_receivers,
                    'list'		=> $list
                )
            )
        ));

        return $return_added_status;

    }

    public static function addSphereStatusRatings($status_id,$rating,$user) {

        $add 							= new sphere_status_ratings;
        $add->sphere_status_update_id 	= $status_id;
        $add->rating 					= $rating;
        $add->user_id 					= $user;
        $add->save();

        $status_rating_result = DB::table('sphere_status_ratings')
					            ->orderBy('id', 'desc')
					            ->take(1)->get();

        return $status_rating_result;
    }

    public static function addSphereStatusComments($status_id,$comment,$user,$sphereid){

        $add 							= new SphereStatusUpdateComments;
        $add->sphere_status_update_id 	= $status_id;
        $add->comment 					= $comment;
        $add->user_id 					= $user;
        $add->save();

        $status_comment_result = DB::table('sphere_status_update_comments')
					            ->join('users','sphere_status_update_comments.user_id','=','users.id')
					            ->join('user_details','users.id','=','user_details.user_id')
					            ->where('sphere_status_update_comments.sphere_status_update_id','=',$status_id)
					            ->select(
					                'sphere_status_update_comments.id',
					                'sphere_status_update_comments.comment',
					                'sphere_status_update_comments.created_at',
					                'users.id as user_id',
					                'users.first_name',
					                'users.last_name',
					                'user_details.avatar as profile_image'
					            )
					            ->orderBy('sphere_status_update_comments.id', 'desc')
					            ->take(1)
					            ->get();

        $added_status_comment = [];

        foreach($status_comment_result as $value){

            $userid = $value->user_id;

            array_push($added_status_comment,array(
                'id'			=> $value->id,
                'comment'		=> $value->comment,
                'created_at'	=> $value->created_at,
                'user_id'		=> $userid,
                'first_name'	=> $value->first_name,
                'last_name'		=> $value->last_name,
                'profile_image'	=> $value->profile_image
                //'profile_image_link'=>$profile_image_result['link']
            ));
        }

        //var_dump($added_status_comment);exit();

        $recivers = array();

        $connections = DB::table('sphere_members')

            ->where('sphere_id','=',$sphereid)
            ->where('status','=',1)
            ->select(
                'member_id'
            )
            ->get();

        foreach($connections as $mem){
            array_push($recivers,$mem->member_id);
        }

        $redis 			= Redis::connection();
        $logged_user 	= Auth::user()->id;
        //$list_item = $redis->incr('sphere'. $logged_user .'_count');

        $notified_user_list = array();

        for($i=0;$i<count($recivers);$i++){

            if($recivers[$i] != $logged_user){
                $id 						= $recivers[$i];
                $list_item 					= $redis->incr('sphere'. $id .'_count');
                $notified_user_list[$id] 	= $list_item;
            }
        }

        $list 			= $notified_user_list;
        $description 	= 'has commented under your knowlege share on Sphere  ';

        $add_notifications 						= new Notification;
        $add_notifications->item 				= $description;

        $add_notifications->inform_profile_id 	= Auth::user()->id;
        $add_notifications->notify_type 		= 13;
        $add_notifications->main_status_id 		= $status_id;
        $add_notifications->notify_type_id 		= $sphereid;
        $add_notifications->save();

        $notify_result = DB::table('notifications')
			            ->orderBy('id', 'desc')
			            ->take(1)->get();

        foreach ($notify_result as $value){
            $notification_id  = $value->id;

        }

        for($i=0;$i<count($recivers);$i++){

            if($recivers[$i] != Auth::user()->id){

                $informas_add 					= new NotificationInformers;
                $informas_add->notification_id 	= $notification_id;
                $informas_add->user_id 			= $recivers[$i];
                $informas_add->save();
            }

        }

        Event::fire(SphereStatusEvent::EVENT,array(
            json_encode(
                array(
                    'sender' 	=> Auth::user()->id,
                    'reciver' 	=> $user,
                    'list' 		=> $list
                )
            )

        ));


        return $status_comment_result;
    }

    public static function getLoadMoreSphereStatusUpdateComments($status_id,$page){

		if($page == 2) {
			$number = 3;
		} else {
			$number = 20;
		}

        $status_comment_result=DB::table('sphere_status_update_comments')
            ->join('users','sphere_status_update_comments.user_id','=','users.id')
            ->join('user_details','users.id','=','user_details.user_id')
            ->where('sphere_status_update_comments.sphere_status_update_id','=',$status_id)
            ->orderBy('sphere_status_update_comments.id', 'desc')
            ->select(
                'sphere_status_update_comments.id',
                'sphere_status_update_comments.comment',
                'sphere_status_update_comments.created_at',
                'users.id as user_id',
                'users.first_name',
                'users.last_name',
                'user_details.avatar as profile_image'
            )
            ->skip(($number*$page)-$number)
            ->take(20)
            ->get();

        $added_status_comment = [];

        foreach($status_comment_result as $value) {

            $userid = $value->user_id;

            array_push($added_status_comment,array(
                'id' 			=> $value->id,
                'comment' 		=> $value->comment,
                'created_at' 	=> $value->created_at,
                'user_id' 		=>  $userid,
                'first_name' 	=> $value->first_name,
                'last_name' 	=> $value->last_name,
                'profile_image' => $value->profile_image
            ));
        }

        $page_no = $page + 1;

        if(count($status_comment_result) < 20) {
            $load = 0;
        } else {
            $load = 1;
        }

        $load_more_result_with_page = array(
            'load_more_comments' 	=> $added_status_comment,
            'page' 					=> $page_no,
            'load_comment_no' 		=> $load

        );

        return $load_more_result_with_page;

    }

    public static function addSphereStatusUpdateFiles($sphereid,$status,$user,$status_type,$file_object){

        $add 				= new SphereStatusUpdate;
        $add->sphere_id 	= $sphereid;
        $add->description 	= $status;
        $add->type 			= $status_type;
        $add->user_id 		= $user;
        $add->save();

        $status_update_result_now = DB::table('sphere_status_updates')
						            ->orderBy('id', 'desc')
						            ->take(1)->get();

        foreach($status_update_result_now as $status) {
            $status_id = $status->id;
        }

        $sphere_admin = DB::table('sphere')
			            ->where('id','=',$sphereid)
			            ->get();

        foreach($sphere_admin as $adminid){
            $sphere_admin_id 	= $adminid->user_id;
            $sphere_name 		= $adminid->name;
        }

        $added_file_names = [];

        for($i=0;$i<count($file_object);$i++){

            $add 							= new SphereStatusUpdateFiles;
            $add->sphere_status_update_id 	= $status_id;
            $add->file 						= $file_object[$i]['filename'];
            $add->original_name 			= $file_object[$i]['filename'];
            $add->type 						= $status_type;
            $add->user_id 					= $user;
            $add->save();

            $file_destination 	= User::getPaths($sphere_admin_id)->server_Path->spheres. $sphereid . '/files/';
            $file_image_save	=  FileFormats::putFile($file_object[$i],$file_destination);

            array_push($added_file_names,array(
                'files' => $file_object[$i]['filename']
            ));

        }


        $status_update_result=DB::table('sphere_status_updates')
            ->join('users','sphere_status_updates.user_id','=','users.id')
            ->join('user_details','users.id','=','user_details.user_id')
            ->where('sphere_status_updates.sphere_id','=',$sphereid)
            ->orderBy('sphere_status_updates.id', 'desc')
            ->select(
                'sphere_status_updates.id',
                'sphere_status_updates.description',
                'sphere_status_updates.created_at',
                'users.id as user_id',
                'users.first_name',
                'users.last_name',
                'user_details.avatar as profile_image'
            )
            ->orderBy('id', 'desc')
            ->take(1)->get();

        $get_saved_status_with_file = [];

        foreach($status_update_result as $value) {

            $status_id = $value->id;

            if ($value->profile_image == '') {
                $user_profile_image = null;
            } else {
                $user_profile_image = $value->profile_image;
            }

            array_push($get_saved_status_with_file,array(
                 'id'				=> $value->id,
                'description'		=> $value->description,
                'created_at'		=> $value->created_at,
                'user_id'			=> $value->user_id,
                'first_name'		=> $value->first_name,
                'last_name'			=> $value->last_name,
                'profile_image'		=> $user_profile_image,
                'sphere_id'			=> $sphereid,
                'sphere_admin_id'	=> $sphere_admin_id,
                'status_file'		=> $added_file_names,
                'status_image'		=> null

            ));

        }

        $status_files = [];

        for($i=0;$i<count($added_file_names);$i++){
	        $status_files[$i]['file'] 				= $file_object[$i]['filename'];
	        $status_files[$i]['sphere_id'] 			= $sphereid;
	        $status_files[$i]['sphere_admin_id'] 	= $sphere_admin_id;
        }

        $status_files_to_sent = array(
            'all_sphere_files' => $status_files
        );

        $return_added_status_with_docs = array(
            'all_status_updates' 	=> $get_saved_status_with_file,
            'status_files' 			=> $status_files_to_sent

        );

        $recivers 		= array();
        $connections 	= DB::table('sphere_members')
							->where('sphere_id','=',$sphereid)
							->where('status','=',1)
							->select(
								'member_id'
							)
							->get();

        foreach($connections as $mem){
            array_push($recivers,$mem->member_id);
        }

        $redis 			= Redis::connection();
        $logged_user 	= Auth::user()->id;


        $notified_user_list = array();

        for($i=0;$i<count($recivers);$i++){

            if($recivers[$i] != Auth::user()->id){

                $id 						= $recivers[$i];
                $list_item 					= $redis->incr('sphere'. $id .'_count');
                $notified_user_list[$id] 	= $list_item;
            }

        }

        $list 			= $notified_user_list;
        $description 	= 'has shared knowledge on Sphere ';


        $add_notifications 						= new Notification;
        $add_notifications->item 				= $description;
        $add_notifications->notify_type 		= 2;
        $add_notifications->notify_type_id 		= $sphereid;
        $add_notifications->main_status_id 		= $status_id;
        $add_notifications->inform_profile_id 	= Auth::user()->id;
        $add_notifications->save();

        $notify_result = DB::table('notifications')
			            ->orderBy('id', 'desc')
			            ->take(1)->get();

        foreach ($notify_result as $value){
            $notification_id  = $value->id;
        }

        for($i=0;$i<count($recivers);$i++){

            if($recivers[$i] != Auth::user()->id){

                $informas_add 					= new NotificationInformers;
                $informas_add->notification_id 	= $notification_id;
                $informas_add->user_id 			= $recivers[$i];
                $informas_add->save();
            }

        }

        Event::fire(SphereStatusEvent::EVENT,array(
            json_encode(
                array(
                    'sender' 	=> Auth::user()->id,
                    'reciver' 	=> $user,
                    'list' 		=> $list
                )
            )

        ));

        return $return_added_status_with_docs;

    }

    public static function addSphereStatusUpdateImages($sphereid,$status,$user,$status_type,$file_object){

        $add 				= new SphereStatusUpdate;
        $add->sphere_id 	= $sphereid;
        $add->description 	= $status;
        $add->type 			= $status_type;
        $add->user_id 		= $user;
        $add->save();

        $status_update_result_now = DB::table('sphere_status_updates')
						            ->orderBy('id', 'desc')
						            ->take(1)->get();

        foreach($status_update_result_now as $status) {
            $status_id = $status->id;
        }

        $sphere_admin = DB::table('sphere')
			            ->where('id','=',$sphereid)
			            ->get();

        foreach($sphere_admin as $adminid){
            $sphere_admin_id = $adminid->user_id;
            $sphere_name     = $adminid->name;
        }

        $added_images = [];

        for($i=0;$i<count($file_object);$i++){

			$add 							= new SphereStatusUpdateImages;
			$add->sphere_status_update_id 	= $status_id;
			$add->image 					= $file_object[$i]['filename'];
			$add->original_image 			= $file_object[$i]['origin'];
			$add->type 						= $status_type;
			$add->user_id 					= $user;
			$add->save();

			$image_destination = User::getPaths($sphere_admin_id)->server_Path->spheres. $sphereid . '/images/';
			$sphere_image_save =  FileFormats::putImage($file_object[$i],$image_destination);

			//$added_images = $file_object[$i]['filename'];
			array_push($added_images,array(
				'images' => $file_object[$i]['filename']
			));

        }

        $status_update_result=DB::table('sphere_status_updates')
            ->join('users','sphere_status_updates.user_id','=','users.id')
            ->join('user_details','users.id','=','user_details.user_id')
            ->where('sphere_status_updates.sphere_id','=',$sphereid)
            ->orderBy('sphere_status_updates.id', 'desc')
            ->select(
                'sphere_status_updates.id',
                'sphere_status_updates.description',
                'sphere_status_updates.created_at',
                'users.id as user_id',
                'users.first_name',
                'users.last_name',
                'user_details.avatar as profile_image'
                )
            ->orderBy('id', 'desc')
            ->take(1)->get();

        $get_saved_status_with_image = [];

        foreach ($status_update_result as $value) {
            
            $status_id = $value->id;

            if ($value->profile_image == '') {
                $user_profile_image = null;
            } else {
                $user_profile_image = $value->profile_image;
            }

            array_push($get_saved_status_with_image,array(
                'id'				=> $value->id,
                'description'		=> $value->description,
                'created_at'		=> $value->created_at,
                'user_id'			=> $value->user_id,
                'first_name'		=> $value->first_name,
                'last_name'			=> $value->last_name,
                'profile_image'		=> $user_profile_image,
                'sphere_id'			=> $sphereid,
                'sphere_admin_id'	=> $sphere_admin_id,
                'status_file'		=> null,
                'status_image'		=> $added_images

            ));




        }

        $status_image=[];

        for($i=0;$i<count($added_images);$i++){

            $status_image[$i]['image'] 				= $file_object[$i]['filename'];
            $status_image[$i]['original_image'] 	= $file_object[$i]['origin'];
            $status_image[$i]['sphere_admin_id'] 	= $sphere_admin_id;
            $status_image[$i]['sphere_id'] 			= $sphereid;

        }
        
        $status_image_to_send = array(
									'all_sphere_images' => $status_image
								);

        $return_added_status_with_files = array(

                                                 'all_status_updates'=>$get_saved_status_with_image,
                                                  'status_images'=>$status_image_to_send

                                               );


        $recivers 		= array();
        $connections 	= DB::table('sphere_members')
							->where('sphere_id','=',$sphereid)
							->where('status','=',1)
							->select(
								'member_id'
							)
							->get();


        foreach($connections as $mem){
            array_push($recivers,$mem->member_id);
        }

        $redis 			= Redis::connection();
        $logged_user 	= Auth::user()->id;

        $notified_user_list = array();

        for($i=0;$i<count($recivers);$i++) {

            if($recivers[$i] != Auth::user()->id) {

                $id 						= $recivers[$i];
                $list_item 					= $redis->incr('sphere'. $id .'_count');
                $notified_user_list[$id] 	= $list_item;

            }
        }

        $list 			= $notified_user_list;
        $description 	= 'has shared knowledge on Sphere  ';


        $add_notifications =					 new Notification;
        $add_notifications->item 				= $description;
        $add_notifications->notify_type 		= 2;
        $add_notifications->notify_type_id 		= $sphereid;
        $add_notifications->main_status_id 		= $status_id;
        $add_notifications->inform_profile_id 	=  Auth::user()->id;
        $add_notifications->save();

        $notify_result = DB::table('notifications')
			            ->orderBy('id', 'desc')
			            ->take(1)->get();

        foreach ($notify_result as $value){
            $notification_id  = $value->id;
        }

        for($i=0;$i<count($recivers);$i++){
            
            if($recivers[$i] != Auth::user()->id){

                $informas_add 					= new NotificationInformers;
                $informas_add->notification_id 	= $notification_id;
                $informas_add->user_id 			= $recivers[$i];
                $informas_add->save();
            }

        }

        Event::fire(SphereStatusEvent::EVENT,array(
            json_encode(
                array(
                    'sender'	=> Auth::user()->id,
                    'reciver' 	=> $user,
                    'list' 		=> $list
                )
            )

        ));

        return $return_added_status_with_files;

    }

    public static function removeImage($id){

        $sphere_delete_result = DB::table('sphere_status_images')
					            ->where('id','=',$id)
					            ->delete();

        return $sphere_delete_result;

    }

    public static function removeFile($id){

        $sphere_delete_result = DB::table('sphere_status_files')
					            ->where('id','=',$id)
					            ->delete();

        return $sphere_delete_result;

    }

    public static function deleteStatus($id){

        $sphere_status_delete = DB::table('sphere_status_updates')
					            ->where('id','=',$id)
					            ->delete();

        return $sphere_status_delete;

    }

    public static function deleteSphereStatusComment($id){

        $sphere_status_comment_delete = DB::table('sphere_status_update_comments')
							            ->where('id','=',$id)
							            ->delete();

        return $sphere_status_comment_delete;

    }

    public static function editSphereStatusUpdate($sphereid,$id,$status,$file,$images){

        $add 				= SphereStatusUpdate::find($id);
        $add->description 	= $status;
        $add->save();

        $status_update_result_now = DB::table('sphere_status_updates')
						            ->where('id','=',$id)
						            ->take(1)->get();

        foreach($status_update_result_now as $status){

            $status_id 		= $status->id;
            $description 	= $status->description;

        }

        $sphere_admin = DB::table('sphere')
			            ->where('id','=',$sphereid)
			            ->get();

        foreach($sphere_admin as $adminid){
            $sphere_admin_id 	= $adminid->user_id;
            $sphere_name 		= $adminid->name;
        }

        $added_file_names 	= [];
        $status_files 		= [];

        if(!is_null($file)){

            for($i=0;$i<count($file);$i++){

                $add 							= new SphereStatusUpdateFiles;
                $add->sphere_status_update_id 	= $status_id;
                $add->file 						= $file[$i]['filename'];
                $add->original_name 			= $file[$i]['filename'];
                $add->type 						= 4;
                $add->user_id 					= Auth::user()->id;
                $add->save();

                $file_destination = User::getPaths($sphere_admin_id)->server_Path->spheres. $sphereid . '/files/';
                $file_image_save 	=  FileFormats::putFile($file[$i],$file_destination);


                array_push($added_file_names,array(
                    'files'=>$file[$i]['filename']
                ));

            }


            for($i=0;$i<count($added_file_names);$i++){
                $status_files[$i]['file'] 				= $file[$i]['filename'];
                $status_files[$i]['sphere_id'] 			= $sphereid;
                $status_files[$i]['sphere_admin_id'] 	= $sphere_admin_id;
            }

            $status_files_to_sent = array(
                'all_sphere_files' => $status_files
            );


        } else {
            $status_files = null;
        }

        $added_images = [];
        $status_image = [];

        if(!is_null($images)) {

            for($i=0;$i<count($images);$i++){

                $add 							= new SphereStatusUpdateImages;
                $add->sphere_status_update_id  	= $status_id;
                $add->image 					= $images[$i]['filename'];
                $add->original_image 			= $images[$i]['origin'];
                $add->type 						= 2;
                $add->user_id 					= Auth::user()->id;
                $add->save();

                $image_destination = User::getPaths($sphere_admin_id)->server_Path->spheres. $sphereid . '/images/';
                $sphere_image_save =  FileFormats::putImage($images[$i],$image_destination);

                array_push($added_images,array(
                    'images'=> $images[$i]['filename']
                ));

            }

            for($i=0;$i<count($added_images);$i++){
                $status_image[$i]['image']				= $images[$i]['filename'];
                $status_image[$i]['original_image']		= $images[$i]['origin'];
                $status_image[$i]['sphere_admin_id']	= $sphere_admin_id;
                $status_image[$i]['sphere_id']			= $sphereid;

            }

            $status_image_to_send = array(
                'all_sphere_images'=>$status_image
            );

        } else {
            $status_image = null;
        }



        $get_saved_status_with_file = [];


        array_push($get_saved_status_with_file,array(
                   'id' 				=> $id,
                   'description' 		=> $description,
                   'sphere_id' 			=> $sphereid,
                   'sphere_admin_id' 	=> $sphere_admin_id,
                   'status_file' 		=> $added_file_names,
                   'status_image' 		=> $status_image

        ));

        return $get_saved_status_with_file;

    }

    public static function deleteSphereStatusUpdates(){
    }

    public static function editSphereStatusComment($id,$comment){

        $add 			= SphereStatusUpdateComments::find($id);
        $add->comment 	= $comment;
        $add->save();

        $status_update_result_now = DB::table('sphere_status_update_comments')
						            ->where('id','=',$id)
						            ->take(1)->get();

        foreach($status_update_result_now as $value){
            $comment = $value->comment;
        }

        return $comment;

    }




} 