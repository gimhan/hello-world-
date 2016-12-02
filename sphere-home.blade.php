@extends('layouts.main')

@section('body')


<div class="col-md-7 col-xs-7 padding-zero"> 
<div class="panel panel-default">
<div class="panel-heading" style="padding-bottom: 2px;">
    <img src="{{URL::asset('newui/img/sphere-page.png')}}" style="margin-right: 6px;"><label style="font-size:18px;">Sphere</label>
    <button class="green-btn float-r" data-toggle="modal" data-target="#new-sphere">+ New Spheres</button>
    <div class="clear-float"></div>
</div>

<div class="panel panel-body" style="background:#fff;" >
<!-- Sphere middle col -->
<div class="sphere-mid-col sphere-list-row float-l" ng-controller="allsphere" ng-cloak>

<ul class="nav nav-tabs">
    <li class="active"><a id="myPosts" ng-click="showTab(0)" href="#my-sphere" data-toggle="tab" >YOUR SPHERES </a>
    </li>
    <li class="nav-seperator"></li>
    <li>
        <a id="myDrafts" ng-click="showTab(1)" href="#sp-inv" data-toggle="tab">INVITATIONS
            <span class="sp-inv-count" ng-if="notifications.sphere_invite>0" ng-cloak><%notifications.sphere_invite%></span>
        </a>
    </li>
</ul>

<div class="tab-content">
<div id="my-sphere" class="tab-pane fade in active">
<!-- Sphere row -->
<div class="sphere-row all-comp-wraper-border padding-zero white-comp-bg-color borderradious-allsides" ng-repeat="sphere in allsphere.allsphere" ng-if="full" ng-cloak>
    <div class="sphere-pro-link-img float-l">
        <a ng-href="{{ URL::to('getOneSphere?id=<%sphere.sphereid%>')}}"><img ng-src="https://s3.amazonaws.com/frontlinesl/users/<%sphere.userid%>/spheres/<%sphere.sphereid%>/images/<%sphere.image%>" class="sphere-pro-img" ng-if="sphere.image != 'default'">
            <img ng-src="{{URL::asset('images/no-image.png')}}" class="sphere-pro-img" ng-if="sphere.image == 'default'">
        </a>
    </div>

    <!-- <div class="float-l post-image-wrapper profiel-bg-img"></div> -->
    
    <div class="sphere-info float-r">
        <!-- Sphere options buttons -->
        <div class="sphere-option float-r" ng-if="sphere.userid == rootid">
            <div class="dropdown float-l privacy-dropdown" data-toggle="tooltip" title="More...">
                <img ng-src="{{URL::asset('newui/img/down-arrow.png')}}" class="dropdown-toggle" type="button" data-toggle="dropdown">
                <ul class="dropdown-menu">
                    <li><a ng-href="#" ng-click="deleteSphere(sphere.sphereid,$index)">Delete Sphere</a></li>
                </ul>
            </div>
        </div>
        <!-- END Sphere options buttons -->
        <a ng-href="{{ URL::to('getOneSphere?id=<%sphere.sphereid%>')}}" ><h5><%sphere.name.substr(0,80)%></h5>
            </a>
        <p><%sphere.description.substr(0,186)%></p>
        <div class="level-2-info">
            <div class="float-l"><%sphere.created_date | amUtc | amLocal | amDateFormat:'D MMM YYYY' %> at <% sphere.created_date | amUtc | amLocal | amDateFormat:'HH:mm' %></div>
            <div class="float-l"><%sphere.connections%> Members</div>
            <div class="float-r">
                <label><%sphere.announcements%></label>
                <img ng-src="{{URL::asset('newui/img/sphere-announc.png')}}">
            </div>
        </div>
    </div>
    <div class="clear-float"></div>

    <div class="sphere-last-update" ng-repeat="last in sphere.lastmessage">
        <label>Latest Update:</label>
        <div class="user-info">
            <img ng-src="https://s3.amazonaws.com/frontlinesl/users/<%last.user_id%>/avatars/<%last.avatar%>" class="float-l" ng-if="last.avatar !=null">
            <img ng-src="{{URL::asset('images/no-profile-img.png')}}" class="float-l" ng-if="last.avatar ==null">
            <a href=""><div class="float-l"><%last.first_name%> <%last.last_name%></div></a>
            <div><%last.created_at | amUtc | amLocal | amDateFormat:'D MMM YYYY' %> at <% last.created_at | amUtc | amLocal | amDateFormat:'HH:mm' %></div>
        </div>
        <div class="clear-float"></div>
        <div class="update-info">
            <p><%last.description%></p>
        </div>
    </div>
</div>
<!-- END Sphere row -->

<!-- All-sphere-empty -->
<div class="sphere-empty-feield padding-zero white-comp-bg-color borderradious-allsides" ng-if="empty">
    <div class="empty-sphere-list-img"></div>
    <img ng-src="{{URL::asset('newui/img/info-icon.png')}}" class="float-l">
    <p>You are not connected in any sphere click on <b>+ New Sphere</b> button to create a sphere</p>
</div>
<!-- END All-sphere-empty -->
</div>


<div id="sp-inv" class="tab-pane fade" ng-controller="invitations" ng-cloak>

<div class="sphere-row all-comp-wraper-border padding-zero white-comp-bg-color borderradious-allsides" ng-repeat="invite in invitations.allinvitations" >
    
    <div class="sp-inv-mssage">
        <img ng-src="<%invite.profile_image%>">
        <p><b><%invite.first_name%></b> has invited you to join the sphere <b><%invite.name%></b></p>
        <p class="float-l">How would you like to respond to this Sphere Invitation?</p>
        <button class="green-btn" ng-click="acceptSphereInvitation(invite.member_index,invite.sphere_id,$index)">Accept</button>
        <button class="red-btn" ng-click="declineSphereInvitation(invite.member_index,invite.sphere_id,$index)">Decline</button>
    </div>

    <div class="sphere-pro-link-img float-l">
        <a ng-href="#">
            <img ng-src="<%invite.sphere_image_link%>" class="sphere-pro-img">
        </a>
    </div>

    <div class="sphere-info float-r">
        <a ng-href="#"><h5><%invite.name%></h5></a>
        <div  class="comment more" show-more="invite.description" limit="182"></div>
        <div class="level-2-info">
            <div class="float-l"><%invite.created_at%></div>
            <div class="float-l"><%invite.member_count%></div>
        </div>
    </div>
    <div class="clear-float"></div>
</div>
<div class="clear-float"></div>
<!-- END Sphere row -->
</div>
</div>

</div>
<!-- END Sphere middle col -->

</div>

</div>

</div>
@include('home.rightSidebar')

@include('newsphere.sphere-popups')

{{HTML::script('newui/js/main.js')}}
@stop