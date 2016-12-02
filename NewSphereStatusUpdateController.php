<?php
/**
 * Created by PhpStorm.
 * User: mahissara
 * Date: 6/7/16
 * Time: 10:01 AM
 */

class NewSphereStatusUpdateController extends BaseController {

    // get sphere status data
    public function getStatusData(){

        $sphere_id  = Input::get('sphereid');
        $admin_id   = Input::get('adminid');

        $all_status_updates = SphereStatusUpdate::getAllSphereStatus($sphere_id,$admin_id);

        return Response::json(array('all_status_updates'=>$all_status_updates));
    }

    // set the sphere status update
    public function SetSphereStatusUpdate() {

        $sphereid=Input::get('sphere_id');
        $status=Input::get('status');
        $user= Auth::user()->id;
        //$type=Input::get('type');
        $type=1;
        //$path=Input::get('status');

        //$added_status_updates = SphereStatusUpdate::addSphereStatusUpdate($sphereid,$status,$user,$type);

        //return Response::json(array('added_status_updates'=>$added_status_updates));

        //if($path == null){
            $added_status_updates = SphereStatusUpdate::addSphereStatusUpdate($sphereid,$status,$user,$type);

            return Response::json(array('all_status_updates'=>$added_status_updates));

       // }else{
        //}

    //return $allsphere;
    }

    // updating a sphere with a file in the post
    public function SetSphereStatusUpdateWithFiles(){

        $sphereid       = Input::get('sphere_id');
        $status         = Input::get('status');
        //$type = Input::get('type');
        $file_object    = Input::get('file_object');
        //$original_name = Input::get('original_name');
        $user           = Auth::user()->id;
        $status_type    = 4;

        $added_status_updates_files = SphereStatusUpdate::addSphereStatusUpdateFiles($sphereid,$status,$user,$status_type,$file_object);

        return Response::json(array('all_status_updates'=>$added_status_updates_files));

    }

    // updating a sphere with an image in a post
    public function SetSphereStatusUpdateWithImages(){

        $sphereid       = Input::get('sphere_id');
        $status         = Input::get('status');
        //$type = Input::get('type');
        $file_object    = Input::get('file_object');
        //$original_name = Input::get('original_name');
        $user           = Auth::user()->id;
        $status_type    = 2;

        //var_dump($added_status_updates);exit();

        $added_status_updates = SphereStatusUpdate::addSphereStatusUpdateImages($sphereid,$status,$user,$status_type,$file_object);


        return Response::json(array('all_status_updates'=>$added_status_updates));

    }

    // commenting on a post inside a sphere
    public function SetSphereStatusComment() {

        $status_id  = Input::get('status_update_id');
        $comment    = Input::get('comment');
        $sphereid   = Input::get('sphereid');
        $user       = Auth::user()->id;

        $added_comment_updates = SphereStatusUpdate::addSphereStatusComments($status_id,$comment,$user,$sphereid);

        return Response::json(array('added_status_comment_updates'=>$added_comment_updates));

        //return $allsphere;
    }

    // rating inside a sphere 
    public function SetSphereStatusRatings() {

        $status_id  = Input::get('statusid');
        $rating     = Input::get('ratings');
        $user       = Input::get('user');

        $added_comment_updates = SphereStatusUpdate::addSphereStatusRatings($status_id,$rating,$user);

        return Response::json(array('added_status_ratings_updates'=>$added_comment_updates));

        //return $allsphere;
    }

    // AJAX paginated comment loading
    public function LoadMoreStatusUpdateComments(){

        $status_id  = Input::get('statusid');
        $page       = Input::get('page');

        $load_more_comments = SphereStatusUpdate::getLoadMoreSphereStatusUpdateComments($status_id,$page);

        return Response::json(array('added_status_comment_updates'=>$load_more_comments));

        //return Response::json($load_more_comments);
    }

    // AJAX Paginated loading on sphere status update
    public function LoadMoreStatusUpdate(){

        $status_id  = Input::get('statusid');
        $page       = Input::get('page');

        $load_more_status = SphereStatusUpdate::getSphereStatusUpdateLoadMore($status_id,$page);

        //var_dump($load_more_status);exit;

        return Response::json(array('all_status_updates'=>$load_more_status));

    }

    // remove the status update
    public function RemoveImage(){

        $image_id       = Input::get('imageid');

        $remove_image   = SphereStatusUpdate::removeImage($image_id);

        return Response::json(array('remove_image'=>$remove_image));

    }

    // edit a status update
    public function EditStatusUpdate(){

        $sphereid   = Input::get('sphereid');
        $statusid   = Input::get('statusid');
        $status     = Input::get('status');
        $files      = Input::get('files');
        $images     = Input::get('images');

        $remove_image = SphereStatusUpdate::editSphereStatusUpdate($sphereid,$statusid,$status,$files,$images);

        return Response::json(array('edited_status_update'=>$remove_image));

    }

    // remove status image in the post
    public function removeStatusImage(){

        $id = Input::get('imageid');

        $remove_image_result = SphereStatusUpdate::removeImage($id);

        return Response::json(array('remove_image'=>$remove_image_result));

    }

    // remove a file in the status
    public function removeStatusFile(){

        $id = Input::get('fileid');

        $remove_file_result = SphereStatusUpdate::removeFile($id);

        return Response::json(array('remove_file'=>$remove_file_result));

    }

    // delete a status post in the sphere
    public function deleteStatusupdate(){

        $id = Input::get('statusid');

        $delete_status = SphereStatusUpdate::deleteStatus($id);

        return Response::json(array('delete_status'=>$delete_status));

    }

    // delete status comment
    public function deleteStatusComment(){

        $id = Input::get('commentid');

        $delete_comment = SphereStatusUpdate::deleteSphereStatusComment($id);

        return Response::json(array('delete_comment'=>$delete_comment));

    }

    // edit a status comment
    public function editStatusComment(){

        $id         = Input::get('commentid');
        $comment    = Input::get('comment');

        $edit_comment = SphereStatusUpdate::editSphereStatusComment($id,$comment);

        return Response::json(array('edit_comment'=>$edit_comment));

    }

    // get sphere status block
    public function getSphereStatusBlock() {
        $sphere_id=Input::get('sphereid');

        $anno = 'active';
        // View::composer('newsphere/single_sphere', function($view) {
        //$view->with('viewname', $view->$anno);
        // });
        //$view = View::make('greeting')->with('name', 'Steve');
        return View::make('newsphere/sphere_status_block');

    }

} 