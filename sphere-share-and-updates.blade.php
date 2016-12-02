<div class="spahere-shareUpdates" ng-repeat="status in all_status_updates.all_status_updates" ng-if="full">
<div class="all-comp-wraper-border radius-top-wraper white-comp-bg-color status-top-content">

  <a ng-href="{{ URL::to('profile')}}">
    <img ng-src="https://s3.amazonaws.com/frontlinesl/users/<%status.user_id%>/avatars/<%status.profile_image%>" class="status-ownprofile-img float-l" ng-if="status.profile_image !=null"/>
    <img ng-src="{{URL::asset('images/no-profile-img.png')}}" class="float-l status-ownprofile-img" ng-if="status.profile_image ==null">
    <div class="home-utatus-un"><%status.first_name%><%status.last_name%></div>
  </a>

  <div class="time"><%status.created_at | amUtc | amLocal | amDateFormat:'D MMM YYYY'%> at <%status.created_at | amUtc | amLocal | amDateFormat:'HH:mm' %></div>


  <!-- posts status updates options buttons -->
  <div class="posts-options" ng-if="status.user_id == rootid">

    <!--div ls-privacy></div-->
    <div class="dropdown float-l privacy-dropdown" data-toggle="tooltip" title="Privacy">
      <div ls-privacy="status.visibility" on-change-privacy="changePrivacy(privacy)"></div>
    </div>
    <div class="float-l">
     <img ng-src="{{URL::asset('newui/img/edit-btn.png')}}" title="Edit" data-toggle="modal" data-target="#edit-s-Q-A" ng-click="editSphereStatusUpdate($index,status.status_update_id,status.description,status.status_image,status.status_file)">
    </div>
    <div class="float-l">
     <img ng-src="{{URL::asset('newui/img/delete-btn.png')}}" title="Delete" data-toggle="modal" data-target="#delete-s-Q-A" ng-click="deleteSphereStatusUpdate($index,status.status_update_id)">
    </div> 
  </div>

  <!-- <div class="posts-options">
    <div class="dropdown float-l privacy-dropdown" data-toggle="tooltip" title="" data-original-title="More...">
      <img src="{{URL::asset('newui/img/down-arrow.png')}}" class="dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
      <ul class="dropdown-menu">
        <li ng-click="hidePost(status)"><a href=""><img src="{{URL::asset('newui/img/hide.png')}}">Hide</a></li>
        <li ng-click="reportAbusePost(status)"><a href=""><img src="{{URL::asset('newui/img/report.png')}}">Report Abuse</a></li>
      </ul>
    </div>
  </div> -->
  <!-- END posts status updates options buttons -->


  <!-- text only statusupdate -->
  <div class="status-text clear-float">

    <!-- <input type="checkbox" class="read-more-state"/>
    <p class="read-more-wrap">
        <%status.description%>
    </p>
    <a target="_blank" class="post-read-more float-r">
        Read More...
    </a> -->

    <div class="comment more" show-more="status.description" limit=384></div>

    <hr ng-if="status.status_image.length+status.status_file.length != null && status.description.length>0" class="message-img-seperator">

      <div class="sphere-status-img" ng-if="status.status_image != null" ng-repeat="imagelist in status.status_image">

          <a ng-href="https://s3.amazonaws.com/frontlinesl/users/<%status.sphere_admin_id%>/spheres/<%status.sphere_id%>/images/<%imagelist.original_image%>" ><img ng-src="https://s3.amazonaws.com/frontlinesl/users/<%status.sphere_admin_id%>/spheres/<%status.sphere_id%>/images/<%imagelist.images%>"></a>

      </div>

      <div class="clear-float"></div>

      <div ng-if="status.status_file != null" ng-repeat="filelist in status.status_file">
          <a ng-href="https://s3.amazonaws.com/frontlinesl/users/<%status.sphere_admin_id%>/spheres/<%status.sphere_id%>/files/<%filelist.files%>" ><%filelist.files%></a>

      </div>

  </div>
  <!-- END text only statusupdate -->

  <!-- Rating comp 2 
  =========================================================================== -->
  <!--<div class="float-l ratings">You have rated it</div>
    <div class="rating-comp float-l">
      <button><img src="img/clear-rating.png"></button>
      <ul>
        <li><img src="img/green-star.png"></li>
        <li><img src="img/green-star.png"></li>
        <li><img src="img/green-star.png"></li>
        <li><img src="img/gray-star.png"></li>
      </ul>
    </div>
    <div class="ratings">Average: <b>4.5 / 5</b> (1321 ratings)</div>
    <div class="clear-float"></div>-->
  <!-- ===============================================================================
  END Rating comp -->

</div>

<!-- comment section -->
<div class="comment-gray-bg" style="padding-top: 10px;">

  <div class="loard-more-row">
    <a href="#" class="float-r" ng-if="status.load_comment_no ==1"  ng-click="getMoreComments(status.status_update_id,$index,status.page)" style="color: #7EC025;">View previous comments...</a>
    <div class="clear-float"></div>
  </div>

  <div class="comment-section-scroll">
  <div class="s-answer col-md-12 col-xs-12 padding-zero" ng-repeat="comm in status.comments">
    <a ng-href="{{ URL::to('profile')}}">
        <img ng-src="https://s3.amazonaws.com/frontlinesl/users/<%comm.comment_user_id%>/avatars/<%comm.profile_image%>" class="comments-pro-image-user2 col-xs-1 padding-zero" ng-if="comm.profile_image !=null"/>
        <img ng-src="{{URL::asset('images/no-profile-img.png')}}" class="float-l s-comment-proimage" ng-if="comm.profile_image ==null">
      <b><%comm.first_name%> <%comm.last_name%></b>
    </a>

    <div class="col-md-1 col-xs-1 padding-zero float-r time home-post-time"><%comm.created_at | amUtc | amLocal | amDateFormat:'D MMM YYYY'%> at <% comm.created_at | amUtc | amLocal | amDateFormat:'HH:mm' %></div>

    <!-- comments options buttons -->
    <!-- <div class="sp-comment-options">
      <img src="{{URL::asset('newui/img/hide.png')}}" data-toggle="tooltip" title="Hide" ng-click="hideComment(status,comment)">
      <img src="{{URL::asset('newui/img/report.png')}}" data-toggle="tooltip" title="Report Abuse" ng-click="reportAbuseComment(status,comment)"> 
    </div> -->

    <div class="sp-comment-options" ng-if="comm.comment_user_id == rootid">
      <img ng-src="{{URL::asset('newui/img/edit-btn.png')}}" data-toggle="modal" title="Edit" data-target="#edit-s-comment" ng-click="editComment($index,comm.id,comm.comment,$parent.$index)">
      <img ng-src="{{URL::asset('newui/img/delete-btn.png')}}" data-toggle="modal" title="Delete" data-target="#delete-s-comment" ng-click="deleteComment($index,comm.id,$parent.$index)">
    </div>
    <!-- comments options buttons -->
      <div class="col-md-10 col-xs-10 padding-zero s-comments-text" show-more="comm.comment" limit=187></div>

  </div>


  </div>
  <div class="clear-float"></div>
</div>
<!-- END comment section -->



<!-- cimment submit section -->
<div class="comment-submit col-md-12 col-xs-12">

  <img class="comments-pro-image float-l" ng-src="{{User::getUserBasic()->profile_image}}"/>
    <div class="warning-msg hidden" id="warning-message-sphere-edit"><img src="{{URL::asset('newui/img/warning.png')}}">Please fill the *mandatory information</div>

  <textarea placeholder="Start sharing your opinion..." class="text-input" id="sphere_comment_<%status.status_update_id%>" ng-model="sphere_status_comments[$index]" press-enter="addComment($index,status.status_update_id)" onblur="requiredValidator(this)" onkeypress="removeValidator(this);" auto-height="180"></textarea>
  
  <button sent btn class="green-btn comment-submit-btn" ng-click="addComment($index,status.status_update_id)">Submit</button>
  
</div>
<!-- END cimment submit section -->

<div class="clear-float"></div>

</div>
<div class="loard-more-row" ng-if="full">
    <a ng-href="#" class="float-r" ng-if="status_load ==1" ng-click="getMoreStatus(sphereid)">Load More...</a>
    <div class="clear-float"></div>
</div>

<!-- empty sphere HOME -->
<div class="sphere-empty-feield padding-zero white-comp-bg-color borderradious-allsides" ng-if="empty">
    <div class="empty-sphere-home"></div>
    <img ng-src="{{URL::asset('newui/img/info-icon.png')}}" class="float-l">
    <p>Please add content(s) now!</p>
</div>
<!-- END empty sphere HOME -->

