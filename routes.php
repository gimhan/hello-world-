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

// BEFORE AUTH STARTS HERE
Route::get('/', array('as' => '/', 'uses' => 'GuestController@showHome'));
Route::get('/login',array('as'=>'guest-landing','uses'=>'GuestController@index'));

Route::post('/register',array('as'=>'register','uses'=>'LoginController@register'));
Route::get('/register/resend-mail-global/',array('as'=>'mail-resend-global','uses'=>'LoginController@resendMailGlobalView'));
Route::post('/resend-glboal',array('as'=>'resend-glboal','uses'=>'LoginController@resendGlobal'));
Route::get('/register/mail/resend/{code}',array('as'=>'mail-resend','uses'=>'LoginController@mailResend'));
Route::get('/register-success',array('as'=>'register-success','uses'=>'LoginController@registerSuccess'));
Route::get('/activate/{code}',array('as'=>'account-activate','uses'=>'LoginController@activateAccount'));

Route::post('/check-mail',array('as'=>'check-mail','uses'=>'LoginController@checkMail'));
Route::post('/user-login',array('as'=>'user-login','uses'=>'LoginController@login'));

// recover passwords
Route::get('/recover/password',array('as'=>'recover-password','uses'=>'LoginController@recoverPasswordView'));
Route::post('/recover/password/sendmail',array('as'=>'recover-password-sendmail','uses'=>'LoginController@recoverPasswordSendMail'));
Route::get('/recover/password/code/{recover_key}',array('as'=>'recover-password-code','uses'=>'LoginController@recoverPasswordCode'));
Route::get('/reset/password',array('as'=>'reset-password','uses'=>'LoginController@resetPassword'));
Route::post('/recover/reset/password',array('as'=>'recover-reset-password','uses'=>'LoginController@recoverResetPassword'));

Route::get('/guest/terms-and-conditions',array('as'=>'guest-terms-and-conditions','uses'=>'GuestController@toc'));
Route::get('/guest/privacy-policy',array('as'=>'guest-privacy-policy','uses'=>'GuestController@privacyPolicy'));
Route::get('/guest/feedback',array('as'=>'guest-feedback','uses'=>'GuestController@feedback'));
Route::get('/guest/contact-us',array('as'=>'guest-contact-us','uses'=>'GuestController@contactUs'));

Route::get('auth/login',array('as'=>'auth-login','uses'=>'GuestController@authLogin'));
Route::get('/auth/register',array('as'=>'auth-register','uses'=>'GuestController@authRegister'));
Route::get('/secmail/{code}',array('as'=>'secmail-activate','uses'=>'LoginController@secondaryEmailActivation'));
Route::get('get-user-details/{id}',array('as'=>'get-user-details','uses'=>'HomeController@getuserdetails'));
Route::get('/hide/guide',array('as'=>'hide-guide','uses'=>'SettingsController@hideGuid'));

Route::get('/contact-us',array('as'=>'contact-us',function(){
  if (is_null(Auth::user())) {
      return Redirect::to('register');
    }
  return View::make('contactUs');
}));

Route::get('/feedback-us',array('as'=>'feedback-us',function(){
  if (is_null(Auth::user())) {
      return Redirect::to('register');
    }
  return View::make('feedback');
}));

Route::get('/terms-conditions',array('as'=>'terms-conditions',function(){
  if (is_null(Auth::user())) {
      return Redirect::to('register');
    }
  return View::make('tearmsAndConditions');
}));

Route::post('/contact-us',array('as'=>'contact-us','uses'=>'FeedbackController@contactus'));
Route::post('/feedback-us',array('as'=>'feedback-us','uses'=>'FeedbackController@feedback'));


Route::group(array('before' => 'auth'), function() {  

  Route::post('getStatusDescription', array('as' => 'getStatusDescription', 'uses' => 'UpdateController@getStatusDescription'));
    

  // end rate publifed posts
  // start Comment publifed posts
  
  // end comment publifed posts

  // end block friend
  // start full notification view
   
  // end full notification view
  // start privacy settings
    Route::post('getUpdatesVisibilityType', array('as' => 'getUpdatesVisibilityType', 'uses' => 'UpdateController@getUpdatesVisibilityType'));
    Route::post('setUpdatesVisibilityType', array('as' => 'setUpdatesVisibilityType', 'uses' => 'UpdateController@setUpdatesVisibilityType'));
  // end privacy settings
  // start post notification check logged user\
    Route::post('checkFriends', array('as' => 'checkFriends', 'uses' => 'UpdateController@checkFriends'));
  // end post notification check logged user



// end nuwan


Route::post('load-status-tab',array('as'=>'load-status-tab','uses'=>'SearchController@Status'));
Route::post('load-Post-tab',array('as'=>'load-Post-tab','uses'=>'SearchController@Post'));
Route::post('load-sphere-tab',array('as'=>'load-Sphere-tab','uses'=>'SearchController@Sphere'));
Route::post('load-publications-tab',array('as'=>'load-publications-tab','uses'=>'SearchController@Publication'));
Route::post('load-peoples-tab',array('as'=>'load-publications-tab','uses'=>'SearchController@Peoples'));
Route::post('search/simple', array('as' => 'search/simple', 'uses' => 'SearchController@showSearchSimple'));
Route::get('search/simple',function(){
  return Redirect::route('home');
});

// global search suggestions
Route::post('global-search-suggestions',array('as'=>'global-search-suggestions','uses'=>'SearchController@globalSearchSuggestions'));

Route::get('/home', array('as' => 'home', 'uses' => 'HomeController@showHome'));

Route::get('get-all-status/{page}/{stamp}', array('as' => 'get-all-status', 'uses' => 'UpdateController@getAllfeeds'));



Route::post('search',array('as'=>'search','uses'=>'SearchController@search'));
//New UI




Route::get('/login-history', array('uses' => 'HomeController@showLoginHistory'));
Route::get('user/a/{slug}', array('uses' => 'HomeController@showPublicProfile'));
Route::get('/change-password', array('uses' => 'HomeController@changePassword'));
Route::post('/update-password', array('as' => 'update-password', 'uses' => 'HomeController@postUpdatePassword'));
Route::get('/logout', array('as' => 'logout', 'uses' => 'HomeController@logout'));
Route::get('/register-complete', array('as' => 'register-complete', 'uses' => 'HomeController@showRegistrationComplete'));
Route::post('/crop-avatar', array('as' => 'crop-avatar', 'uses' => 'HomeController@cropAvatar'));
Route::post('/register/complete', array('as' => 'register/complete', 'uses' => 'HomeController@getRegisterComplete'));


// end papers





Route::post('/user-connect', array('as' => 'user-connect', 'uses' => 'HomeController@userConnect'));
Route::post('/remove-connect', array('as' => 'remove-connect', 'uses' => 'HomeController@removeConnect'));

Route::get('/get-notifications', array('as' => 'get-notifications', 'uses' => 'HomeController@getNotifications'));



Route::post('/process-connect-request', array('as' => 'process-connect-request', 'uses' => 'HomeController@processConnectRequest'));


/** File Controller **/
Route::post('file-upload',array('as'=>'file-upload','uses'=>'FilesController@upload'));
Route::post('file-delete-object',array('as'=>'file-delete','uses'=>'FilesController@deleteObject'));
Route::post('file-put-object/{param?}',array('as'=>'put-object-controller','uses'=>'FilesController@putObject'));
Route::post('file-get-object',array('as'=>'put-get-controller','uses'=>'FilesController@getObject'));
/**End  File Controller **/


// end Search Module
// start image post
Route::post('fileUploadProgress', array('uses' => 'UpdateController@fileUploadProgress'));
Route::post('fileUploadSave', array('uses' => 'UpdateController@fileUploadSave'));
Route::post('getImageLocationInPost', array('uses' => 'UpdateController@getImageLocationInPost'));
// end image post
// start URL post
Route::post('statusURLPostSave', array('uses' => 'UpdateController@statusURLPostSave'));
// end URL post
// start Comment

// end comment
// start Ratings



//#Sphere#
Route::get('sphere', array('as' => 'sphere', 'uses' => 'NewSphereController@showSphere'));




Route::get('tearms-and-conditions',array('as'=>'tearms-and-conditions',function(){
  return View::make('tearmsAndConditions');
}));

Route::get('privacy-policy',array('as'=>'privacy-policy',function(){
  return View::make('privacyPolicy');
}));


Route::get('domain-composer',array('as'=>'domain-composer','uses'=>'SearchController@checkDomains'));
// POSTS

// Geeth was here from here
Route::get('/read-post/{documentId}',array('as'=>'read-post','uses'=>'PostController@readPost'));
Route::get('load-posts',array('as'=>'view-posts','uses'=>'PostController@loadPosts'));
Route::get('load-public-posts',array('as'=>'load-public-posts','uses'=>'PostController@loadPublicPosts'));
Route::get('load-my-post',array('as'=>'load-my-post','uses'=>'PostController@loadMyPosts'));
Route::get('load-my-drafts',array('as'=>'load-my-drafts','uses'=>'PostController@loadMyDraftPosts'));
Route::get('load-my-bookmarks',array('as'=>'load-my-bookmarks','uses'=>'PostController@loadMyBookMarkedPosts'));
Route::get('load-my-editorial',array('as'=>'view-my-post','uses'=>'PostController@loadMyEditorialPosts'));
Route::get('load-my-requests',array('as'=>'load-my-requests','uses'=>'PostController@loadMyRequests'));

// get other user's post
Route::get('load-another-user-post/{userid}',array('as'=>'load-another-user-post','uses'=>'PostController@loadAnotherUserPosts'));

Route::get('/post/connections/{userName}',array('as'=>'user-connections-for-post','uses'=>'PostController@getConnections'));

Route::post('/post/create',array('as'=>'post-create','uses'=>'PostController@initiatePost'));
Route::get('/post/edit/{documentId}', array('as'=>'post-edit','uses'=>'PostController@editPost'));
Route::post('/post/edit/save', array('as'=>'post-edit-save','uses'=>'PostController@savePost'));
Route::post('/post/load/comments', array('as'=>'post-comments-load','uses'=>'PostController@commentsLoad'));
Route::post('/post/comment', array('as'=>'post-comment','uses'=>'PostController@comment'));
Route::post('post-delete-comment', array('as'=>'post-delete-comment','uses'=>'PostController@removeComment'));

Route::get('post-delete/{documentId}', array('as'=>'post-delete','uses'=>'PostController@deletePost'));
Route::get('post-leave/{documentId}', array('as'=>'post-delete','uses'=>'PostController@leavePost'));

Route::post('/post/mark/complete', array('as'=>'post-mark-complete','uses'=>'PostController@markComplete'));
Route::get('/post/mark/draft/{documentId}', array('as'=>'post-mark-draft','uses'=>'PostController@markDraft'));

Route::get('/post/bookmark/{postId}', array('as'=>'post-bookmark','uses'=>'PostController@bookmark'));
Route::get('/post/bookmark/remove/{postId}', array('as'=>'post-bookmark-remove','uses'=>'PostController@bookmarkRemove'));

Route::post('/post/edit/remove-post-session',array('as'=>'remove-post-session','uses'=>'PostController@removePostSession'));
Route::get('/post/edit/session/killed',array('as'=>'post-session-time-out','uses'=>'PostController@sessionKilledRedirect'));

Route::get('/post/accept/{postId}',array('as'=>'post-accept','uses'=>'PostController@acceptRequest'));
Route::get('/post/decline/{postId}',array('as'=>'post-decline','uses'=>'PostController@declineRequest'));

Route::post('post-remove-user',array('as'=>'post-user-remove','uses'=>'PostController@postRemoveUser'));
Route::post('post-remove-invited-user',array('as'=>'post-user-remove-invited-user','uses'=>'PostController@postRemoveInvitedUser'));
Route::post('post-manage-save',array('as'=>'post-manage-save','uses'=>'PostController@postManageSave'));


//angular redirect
Route::get('edit/load-my-post',array('as'=>'edit-load-my-post-redirect','uses'=>'PostController@editMarkCompleteRedirect'));


Route::get('help',array('as'=>'help','uses'=>'HelpController@index'));

/***
FEED STREAM
**/

Route::get('feed-stream/{page}/{stamp}',array('as'=>'feed-stream','uses'=>'FeedController@index'));
Route::get('feed-my-stream/{page}/{stamp}',array('as'=>'feed-my-stream','uses'=>'FeedController@getMyFeeds'));
Route::get('feed-user-stream/{userid}/{page}/{stamp}',array('as'=>'feed-user-stream','uses'=>'FeedController@getUserFeeds'));

// FEED POST ITEM ROUTES
Route::get('feed-stream-load-comment/{feed_id}',array('as'=>'feed-stream-load-comment','uses'=>'FeedController@loadPostComment'));
Route::get('feedstream-postloadmore-comment/{feedid}/{pageno}',array('as'=>'feedstream-postloadmore-comment','uses'=>'FeedController@getMoreComment'));
Route::get('postpub-status-delete/{feed_id}',array('as'=>'postpub-status-delete','uses'=>'FeedController@postPubitemDelete'));
Route::post('feed-stream-postitem-comment',array('as'=>'feed-stream-postitem-comment','uses'=>'FeedController@postComment'));
Route::post('feed-stream-postitem-comment-edit',array('as'=>'feed-stream-postitem-comment-edit','uses'=>'FeedController@postCommentEdit'));
Route::post('feed-stream-postitem-comment-delete',array('as'=>'feed-stream-postitem-comment-delete','uses'=>'FeedController@postCommentDelete'));
Route::post('feed-stream-postpub-rating',array('as'=>'feed-stream-postpub-rating','uses'=>'FeedController@postRating'));


// FEED CONNECTION CREATION ITEM ROUTES
Route::get('feedstream-conncreatdelete/{feedid}',array('as'=>'feedstream-conncreatdelete','uses'=>'FeedController@conncreatDelete'));

Route::post('feed-stream-conncreat-comment',array('as'=>'feed-stream-conncreat-comment','uses'=>'FeedController@conncreatComment'));
Route::get('feedstream-conncreatloadmore-comment/{feedid}/{pageno}',array('as'=>'feedstream-conncreatloadmore-comment','uses'=>'FeedController@conncreatGetMoreComment'));
Route::post('feed-stream-conncreat-comment-edit',array('as'=>'feed-stream-conncreat-comment-edit','uses'=>'FeedController@conncreatCommentEdit'));
Route::post('feed-stream-conncreat-comment-delete',array('as'=>'feed-stream-conncreat-comment-delete','uses'=>'FeedController@conncreatCommentDelete'));
Route::post('feed-stream-conncreat-rating',array('as'=>'feed-stream-conncreat-rating','uses'=>'FeedController@conncreatRating'));


// Geeth was here to here

Route::get('posts', array('as'=>'posts','uses'=>'PostController@init' ));
Route::post('load-post',array('as'=>'load-post','uses'=>'PostController@loadpost'));
Route::post('update',array('as'=>'post-update','uses'=>'PostController@update'));

Route::post('post-save-draft',array('as'=>'post-save-draft','uses'=>'PostController@saveDraft'));
Route::post('post-publish',array('as'=>'post-publish','uses'=>'PostController@publish'));
Route::get('new-post',array('as'=>'new-post',function(){
    return View::make('posts.new_posts');
}));

Route::get('view-all-post',array('as'=>'view-all-post','uses'=>'PostController@viewAllPost'));
Route::get('view-post/{slug}',array('as'=>'view-post','uses'=>'PostController@viewPost'));
Route::get('view-post/{userSlug}/{postSlug}',array('as'=>'view-post','uses'=>'PostController@viewPost'));

Route::post('view-all-post',array('as'=>'view-all-post-pagin','uses'=>'PostController@viewAllPostPagin'));
Route::post('save-post-comment',array('as'=>'save-post-comment','uses'=>'PostController@PostCommentSave'));

Route::get('get-post-details/{postid}',array('as'=>'get-post-details','uses'=>'PostController@getpostDetails'));
Route::get('get-post-comment/{postid}/{page}',array('as'=>'get-post-comment','uses'=>'PostController@getpostComment'));
Route::post('edit-post-comment',array('as'=>'edit-post-comment','uses'=>'PostController@PostCommentEdit'));
Route::post('delete-post-comment',array('as'=>'delete-post-comment','uses'=>'PostController@PostCommentDelete'));
// SEARCH
// end simple Search Module
// start advance Search Module


# MESSAGES #

Route::get('messages',array('as'=>'messages','uses'=>'MessageController@init'));
Route::get('message/load/users',array('as'=>'message-load-users','uses'=>'MessageController@loadUsers'));
Route::get('message/history/{userid}/{page}/{stamp}',array('as'=>'message-history','uses'=>'MessageController@loadMessageHistory'));
Route::get('message/select/user/{id}',array('as'=>'message-select-user','uses'=>'MessageController@userSelect'));
Route::get('message/guest/user',array('as'=>'message-guest-user','uses'=>'MessageController@loadGuestUsers'));
Route::get('message/search/firend/{keyword}',array('as'=>'message-search-firend','uses'=>'MessageController@searchUsers'));

Route::post('message/pre-rquest',array('as'=>'message-pre-request','uses'=>'MessageController@preparemessage'));
Route::get('message/sync-files/{conversation}/{page}/{stamp}',array('as'=>'message-sync-file','uses'=>'MessageController@syncFiles'));
Route::post('message/send',array('as'=>'sendMessage','uses'=>'MessageController@sendMessage'));

Route::post('message/delete/history',array('as'=>'message-delete-conversation','uses'=>'MessageController@deleteHistory'));
Route::post('message/pin',array('as'=>'message-pin','uses'=>'MessageController@pin_message'));
Route::post('message/un-pin',array('as'=>'message-un-pin','uses'=>'MessageController@un_pin'));
Route::get('messages-docs/{messageid}/{page}',array('uses'=>'MessageController@loadDocs'));
Route::get('messages-images/{messageid}/{page}',array('uses'=>'MessageController@loadImages'));
Route::get('messages/load/pin/{user}/{stamp}/{page}',array('uses'=>'MessageController@getpins'));
Route::get('messages/load/links/{converid}/{page}',array('as'=>'messages-get-links','uses'=>'MessageController@getLinks'));
Route::get('message-sync-with/{convid}/{user}',array('as'=>'message-sync-with','uses'=>'MessageController@syncMessage'));

Route::get('message/load/doc/{userid}/{docname}',array('as'=>'load-a-doc','uses'=>'MessageController@getDoc'));
Route::get('message/read/{conversationid}/{messageid}/{userid}',array('as'=>'read-status','uses'=>'MessageController@readMessage'));
# End Message #

// settings and manage profile
Route::get('settings', array('as' => 'settings', 'uses' => 'SettingsController@showSettings'));
Route::post('settings/recover/reset/password', array('as' => 'settingsPassChange', 'uses' => 'SettingsController@passwordChange'));
Route::post('settings/add/secondary-email', array('as' => 'settingsAddSecondaryEmail', 'uses' => 'SettingsController@settingsAddSecondaryEmail'));

Route::get('settings/get/discoverable', array('as' => 'getDiscoverable', 'uses' => 'SettingsController@getDiscoverable'));
Route::post('settings/set/discoverable', array('as' => 'setDiscoverable', 'uses' => 'SettingsController@setDiscoverable'));

Route::get('settings/login-history', array('as' => 'loginHistory', 'uses' => 'SettingsController@loginHistory'));
Route::post('settings/deactivate', array('as' => 'accountDeactivate', 'uses' => 'SettingsController@accountDeactivate'));

Route::get('settings/remove/secondary-email', array('as' => 'removeSecondaryEmail', 'uses' => 'SettingsController@removeSeondaryEmail'));

Route::get('settings-resend-secondary-email', array('as' => 'settings-resend-secondary-email', 'uses' => 'SettingsController@resendSecondaryEmail'));


Route::get('privacy', array('as' => 'privacy', 'uses' => 'SettingsController@showPrivacy'));

Route::post('privacy-Acedemic',array('as'=>'privacy-Acedemic','uses'=>'SettingsController@setAcademicInformations'));
Route::post('privacy-Personal',array('as'=>'privacy-Personal','uses'=>'SettingsController@setPersonalInformations'));
Route::post('privacy-Professional',array('as'=>'privacy-Professional','uses'=>'SettingsController@setProfessionalInformations'));
Route::post('privacy-Updates',array('as'=>'privacy-Updates','uses'=>'SettingsController@setUpdates'));
Route::post('privacy-Experience',array('as'=>'privacy-Experience','uses'=>'SettingsController@setExperience'));

Route::post('/edit-name', array('as' => 'edit-name', 'uses' => 'SettingsController@editName'));
Route::post('/edit-slug', array('as' => 'edit-slug', 'uses' => 'SettingsController@editSlug'));
Route::post('/edit-email-setting', array('as' => 'edit-email-setting', 'uses' => 'SettingsController@editEmail'));

Route::post('/change-password-setting', array('as' => 'change-password-setting', 'uses' => 'SettingsController@editPassword'));
Route::post('/deactivate-account', array('as' => 'deactivate-account', 'uses' => 'SettingsController@deactivateAccount'));

// status handle
Route::post('status-update', array('as' => 'post-status-message', 'uses' => 'UpdateController@updateStatus'));
Route::post('status-delete',array('uses'=>'UpdateController@statusDelete'));
Route::post('status-comment-update', array('uses' => 'UpdateController@statusCommentUpdate'));
Route::post('status-comment-delete',array('uses'=>'UpdateController@statusCommentDelete'));
Route::get('status-get-statuses/{page}/{stamp}',array('uses'=>'UpdateController@getmyStatus'));


Route::post('status-ratings', array('uses' =>'UpdateController@statusRatingsSave'));
Route::get('status-load-more-comment/{statusid}/{lastindex}',array('uses'=>'UpdateController@getMoreComment'));
Route::post('status-update-privacy',array('as'=>'change-status-privacy','uses'=>'UpdateController@changePrivacy'));
Route::post('hide-status-comment',array('uses'=>'UpdateController@hideComment'));
Route::post('hide-newsfeeds',array('uses'=>'FeedController@hidenewsfeeds'));

Route::post('news-feed-report-abuse', array('as' => 'news-feed-report-abuse', 'uses' => 'FeedController@reportAbuse'));
//status handle end






Route::post('getPotsURLS',array('uses'=>'UpdateController@getPotsURLS'));




// temp
Route::get('temp', 'HomeController@temp');

Route::get('notifications', array('as' => 'notifications', 'uses' => 'HomeController@notificationsFullView'));

Route::get('/registration-step-2', array('as' => 'registration-step-2', 'uses' => 'HomeController@showRegistrationStep2'));
Route::post('/step-2-process', array('as' => 'step-2-process', 'uses' => 'HomeController@processStep2'));

Route::get('/registration-step-3', array('as' => 'registration-step-3', 'uses' => 'HomeController@showRegistrationStep3'));
Route::post('/step-3-process', array('as' => 'step-3-process', 'uses' => 'HomeController@processStep3'));

Route::post('/edit-work', array('as' => 'edit-work', 'uses' => 'HomeController@editWork'));
Route::post('/edit-location', array('as' => 'edit-location', 'uses' => 'HomeController@editLocation'));



Route::post('profile-image-save',array('as'=>'profile-image-save','uses'=>'ProfileController@ImageSave'));

Route::post('profile-save',array('as'=>'profile-save','uses'=>'ProfileController@saveData'));

Route::post('profile-get',array('as'=>'profile-get','uses'=>'ProfileController@getData'));

Route::get('/profile', array('as' => 'profile', 'uses' => 'ProfileController@showProfile'));

Route::get('profile/{slug}', array('as' => 'profile-other', 'uses' => 'OtherProfileController@showProfile'));

Route::get('get-countries',array('as'=>'get-countries','uses'=>'HomeController@getCountires'));


//------------------------------------profile information section------------------------------------------------------------------------------


Route::post('update-gender', array('as' => 'edit_location', 'uses' => 'ProfileController@edit_gender'));

Route::post('update-location', array('as' => 'edit_location', 'uses' => 'ProfileController@edit_location'));

Route::post('update-about', array('as' => 'edit_about', 'uses' => 'ProfileController@edit_about'));

Route::post('update-birthday', array('as' => 'edit_birthday', 'uses' => 'ProfileController@edit_birthday'));

Route::post('update-contact-info',array('as'=>'update_contact_info','uses'=>'ProfileController@update_contact_info'));

Route::post('edit-acadamic', array('as' => 'edit_acadamic', 'uses' => 'ProfileController@edit_acadamic'));

Route::post('update-experience', array('as' => 'add_experience', 'uses' => 'ProfileController@add_experience'));

Route::post('update-membership', array('as' => 'add_membership', 'uses' => 'ProfileController@add_membership'));

Route::post('delete-acadamic', array('as' => 'delete_acadamic_specific', 'uses' => 'ProfileController@delete_acadamic_specific'));

Route::post('delete-experience', array('as' => 'delete_experience', 'uses' => 'ProfileController@delete_experience'));

Route::post('delete-membership', array('as' => 'delete_membership_specific', 'uses' => 'ProfileController@delete_membership_specific'));

Route::get('personal-info', array('as' => 'show_personal_info', 'uses' => 'ProfileController@show_personal_info'));

Route::get('get-contact-info', array('as' => 'show_contact_info', 'uses' => 'ProfileController@show_contact_info'));

Route::get('get-qualification', array('as' => 'show_acadamic_info', 'uses' => 'ProfileController@show_acadamic_info'));

Route::get('show-experince-info', array('as' => 'show_experince_info', 'uses' => 'ProfileController@show_experince_info'));

Route::get('show-membership-info', array('as' => 'show_membership_info', 'uses' => 'ProfileController@show_membership_info'));

Route::post('update-publication', array('as' => 'add_publication', 'uses' => 'ProfileController@add_publication'));

Route::post('delete-publication',array('as'=>'delete_publication','uses'=>'ProfileController@delete_publication'));

Route::get('get-publication-specific', array('as' => 'get_publication_specific', 'uses' => 'ProfileController@get_publication_specific'));

Route::post('update-privacy', array('as' => 'add_publications_privacy', 'uses' => 'ProfileController@update_privacy'));

Route::post('update-personal-privacy',array('as'=>'update_personal_privacy','uses'=>'ProfileController@update_personal_privacy'));

Route::get('get-privacy', array('as' => 'add_publications_privacy', 'uses' => 'ProfileController@get_privacy'));

Route::get('get-personal-privacy', array('as' => 'get_personal_privacy', 'uses' => 'ProfileController@get_personal_privacy'));

Route::post('profile-minibio-save', array('as' => 'profile-minibio-save', 'uses' => 'ProfileController@profileMinibioSave'));

Route::get('profile-minibio-load', array('as' => 'profile-minibio-load', 'uses' => 'ProfileController@profileMinibioGet'));



//--------------------- others-profile -------------------------------------

Route::get('personal-info-other/{userid}',array('as'=>'personal-info-others','uses'=>'OtherProfileController@show_personal_info'));

Route::get('get-qualification-other/{userid}',array('as'=>'get-qualification-others','uses'=>'OtherProfileController@show_academic_info'));

Route::get('get-publication-other/{userid}',array('as'=>'get-publication-others','uses'=>'OtherProfileController@show_publication_info'));

Route::get('show-experince-info-other/{userid}',array('as'=>'get-publication-others','uses'=>'OtherProfileController@show_experince_info'));

Route::get('show-membership-info-other/{userid}',array('as'=>'show-membership-info-others','uses'=>'OtherProfileController@show_membership_info'));

Route::get('get-contact-info-other/{userid}',array('as'=>'get-contact-info-others','uses'=>'OtherProfileController@show_contact_info'));

//--------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------Connections--------------------------------------------------------

Route::post('find-connections/{word}',array('as'=>'find-connections','uses'=>'ConnectionController@userSerach'));

Route::get('view-connections', array('as' => 'view-connections', 'uses' => 'ConnectionController@viewConnections'));

Route::post('accept-request', array('as' =>'accept_request', 'uses' => 'ConnectionController@acceptRequest'));

Route::post('send-request', array('as' => 'send-request', 'uses' => 'ConnectionController@sendRequest'));

Route::post('cancel_friend_request',array('uses'=>'ConnectionController@cancelRequest'));

Route::post('reject-friend-request',array('uses'=>'ConnectionController@rejectRequest'));

Route::post('block-user',array('uses'=>'ConnectionController@blockUser'));

Route::post('un-block-user',array('uses'=>'ConnectionController@unBlockUser'));

Route::get('get-friend-suggestions/{size}/{page}',array('as'=>'get-friend-suggestions','uses'=>'ConnectionController@getSuggestions'));

Route::get('get-pending-requests/{size}/{page}', array('as' => 'connect-requests', 'uses' => 'ConnectionController@getRecivedRequestList'));

Route::get('get-block-users/{size}/{page}',array('as'=>'get-block-users','uses' => 'ConnectionController@getBlocUsers'));

Route::get('get-users-friends/{user}/{page}', array('as' => 'get_friends', 'uses' => 'ConnectionController@getUsersConnction'));

Route::get('get-friends/{size}/{page}', array('as' => 'get_friends', 'uses' => 'ConnectionController@getFriendList'));

Route::get('get-sent-requests/{size}/{page}', array('as' => 'getSentRequestList', 'uses' => 'ConnectionController@getSentRequestList'));

Route::get('get-sent-requests/{size}/{page}', array('as' => 'getSentRequestList', 'uses' => 'ConnectionController@getSentRequestList'));

Route::post('update-connection-privacy', array('as' => 'connection-privacy', 'uses' => 'ConnectionController@changeConnectionPrivacy'));

//-------------------------Report Abuse----------------------------------------------------
Route::post('/add_update_comment_report_abuse', array('as' => 'add_update_comment_report_abuse', 'uses' => 'ProfileController@add_update_comment_report_abuse'));

Route::post('/add_post_comment_report_abuse', array('as' => 'add_post_comment_report_abuse', 'uses' => 'ProfileController@add_post_comment_report_abuse'));

Route::post('/add_post_report_abuse', array('as' => 'add_post_report_abuse', 'uses' => 'ProfileController@add_post_report_abuse'));

Route::get('user-connection-remove/{code}', array('as' => 'user-connection-remove', 'uses' => 'ConnectionController@removeConnection'));
//--------------------------------------------------------------------------------------------------------

});

//--------------------------------------------------------------------------------------------------------------

Route::get('/getAllSphereAnnouncement', array('as' => 'getAllSphereAnnouncement', 'uses' => 'SphereAnnouncementController@getAllSphereAnnouncement'));

Route::post('/addSphereAnnouncement', array('as' => 'addSphereAnnouncement', 'uses' => 'SphereAnnouncementController@addAnnouncemrnts'));

Route::get('/getLatestSphereAnnouncement', array('as' => 'getLatestSphereAnnouncement', 'uses' => 'SphereAnnouncementController@getLatestSpherAnnouncements'));

Route::get('/getAllSphere', array('as' => 'getAllSphere', 'uses' => 'NewSphereController@getallsphere'));

Route::get('get-otherUser-spheres/{userId}', array('as' => 'get-otherUser-spheres', 'uses' => 'NewSphereController@getOtherUserSpheres'));

Route::get('/getOneSphere', array('as' => 'getOneSphere', 'uses' => 'NewSphereController@getOneSphere'));

Route::post('/getGivenSphere', array('as' => 'getGivenSphere', 'uses' => 'NewSphereController@getGivenSphere'));

Route::get('/getAllMembers', array('as' => 'getAllMembers', 'uses' => 'NewSphereController@getAllMembers'));
//Profile Routes

Route::post('/setSphere', array('as' => 'setSphere', 'uses' => 'NewSphereController@setSphere'));

Route::get('/getStatusData', array('as' => 'getStatusData', 'uses' => 'NewSphereStatusUpdateController@getStatusData'));

Route::post('/setSphereStatus', array('as' => 'setSphereStatus', 'uses' => 'NewSphereStatusUpdateController@SetSphereStatusUpdate'));

Route::get('/getSphereFiles', array('as' => 'getSphereFiles', 'uses' => 'NewSphereController@getAllfiles'));

Route::get('/getSphereImages', array('as' => 'getSphereImages', 'uses' => 'NewSphereController@getAllImages'));

Route::post('/getUsers', array('as' => 'getUsers', 'uses' => 'NewSphereController@SetSphereStatusUpdate'));

Route::post('/setSphereStatusFiles', array('as' => 'setSphereStatusFiles', 'uses' => 'NewSphereStatusUpdateController@SetSphereStatusUpdateWithFiles'));

Route::post('/setSphereStatusImages', array('as' => 'setSphereStatusImages', 'uses' => 'NewSphereStatusUpdateController@SetSphereStatusUpdateWithImages'));

Route::post('/deleteSphere', array('as' => 'deleteSphere', 'uses' => 'NewSphereController@deleteSphere'));

Route::post('/deleteAnnouncements', array('as' => 'deleteAnnouncements', 'uses' => 'SphereAnnouncementController@deleteAnnouncements'));

Route::post('/addStatusComment', array('as' => 'addStatusComment', 'uses' => 'NewSphereStatusUpdateController@SetSphereStatusComment'));

Route::post('/inviteUsers', array('as' => 'inviteUsers', 'uses' => 'UserSearchController@SearchInviteUsers'));

Route::post('/editSphereSave', array('as' => 'editSphereSave', 'uses' => 'NewSphereController@editSphereSave'));

Route::post('/addUsersToMemberList', array('as' => 'addUsersToMemberList', 'uses' => 'NewSphereController@addInviteMemberList'));

Route::post('/removeMember', array('as' => 'removeMember', 'uses' => 'NewSphereController@removeMemberFromList'));

Route::post('/editSphereImage', array('as' => 'editSphereImage', 'uses' => 'NewSphereController@editSphereImage'));

Route::post('/hideAnnouncement', array('as' => 'hideAnnouncement', 'uses' => 'SphereAnnouncementController@hideSingleAnnouncements'));

Route::get('/getAllInvitations', array('as' => 'getAllInvitations', 'uses' => 'NewSphereController@getAllInvitations'));

Route::post('/acceptSphereInvitation', array('as' => 'acceptSphereInvitation', 'uses' => 'NewSphereController@acceptInvitations'));

Route::post('/declineSphereInvitation', array('as' => 'declineSphereInvitation', 'uses' => 'NewSphereController@declineInvitations'));

Route::post('/declineSphereInvitation', array('as' => 'declineSphereInvitation', 'uses' => 'NewSphereController@declineInvitations'));

Route::post('/editStatusUpdate', array('as' => 'editStatusUpdate', 'uses' => 'NewSphereStatusUpdateController@editStatusUpdate'));

Route::post('/removeStatusImage', array('as' => 'removeStatusImage', 'uses' => 'NewSphereStatusUpdateController@removeStatusImage'));

Route::post('/removeStatusFiles', array('as' => 'removeStatusFiles', 'uses' => 'NewSphereStatusUpdateController@removeFile'));

Route::post('/deleteStatusupdate', array('as' => 'deleteStatusupdate', 'uses' => 'NewSphereStatusUpdateController@deleteStatusupdate'));

Route::post('/deleteStatusComment', array('as' => 'deleteStatusComment', 'uses' => 'NewSphereStatusUpdateController@deleteStatusComment'));

Route::post('/editStatusComment', array('as' => 'editStatusComment', 'uses' => 'NewSphereStatusUpdateController@editStatusComment'));

Route::post('/setSpherePrivacy', array('as' => 'setSpherePrivacy', 'uses' => 'NewSphereController@setSpherePrivacy'));

Route::post('/loadMoreStatusComment', array('as' => 'loadMoreStatusComment', 'uses' => 'NewSphereStatusUpdateController@LoadMoreStatusUpdateComments'));

Route::post('/loadMoreStatus', array('as' => 'loadMoreStatus', 'uses' => 'NewSphereStatusUpdateController@LoadMoreStatusUpdate'));

Route::get('/sphereAnnouncementBlock', array('as' => 'sphereAnnouncementBlock', 'uses' => 'SphereAnnouncementController@showSphereAnnouncements'));

Route::get('/sphereMemberBlock', array('as' => 'sphereMemberBlock', 'uses' => 'NewSphereController@getMemberBlock'));

Route::get('/sphereStatusBlock', array('as' => 'sphereStatusBlock', 'uses' => 'NewSphereStatusUpdateController@getSphereStatusBlock'));

Route::post('/inviteUsersSphere', array('as' => 'inviteUsersSphere', 'uses' => 'UserSearchController@SearchInviteUsersForSphere'));


//-----------------------------------notifications------------------------------------------------------------
Route::get('/getAllNotifications', array('as' => 'getAllNotifications', 'uses' => 'NotificationController@getALlGlobalNotifications'));

Route::get('mini-notifications', array('as' => 'mini-notifications', 'uses' => 'NotificationController@getMiniNotifications'));

Route::post('/updateNotification', array('as' => 'updateNotification', 'uses' => 'NotificationController@sphereMemberAcceptNotificationChange'));
//------------------------------------------------------------------------------------------------------------




// Interest Routes
Route::post('/add-interests', array('as' => 'addInterests', 'uses' => 'InterestController@addInterests'));


Route::get('view-info',function(){
   phpinfo();
});