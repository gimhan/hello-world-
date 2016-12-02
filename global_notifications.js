(function(window){

    var msg=angular.module('learersocial');

    msg.controller('allglobalnotifications',
        ['$element','$scope','$rootScope','$http','loadtemplates','requestService',
            function($element,$scope,$rootScope,$http,loadtemplates,requestService){


                loadtemplates.responses({
                    method: 'GET',
                    url: 'getAllNotifications'

                }).then(function (response) {

                        $scope.allnotifications = response.data;

                        console.log(response.data);

                }, function (response) {
                    console.log(response.data.error);
                });

                $scope.loadMiniNotifications = function () {

                    $scope.miniNotsArray = [];

                    loadtemplates.responses({
                        url:'mini-notifications',
                        method:'GET'
                    }).then(
                        function(resp){
                            console.log(resp.data);
                            $scope.miniNotsArray = resp.data.miniNotifications[0].mininotifications;

                        },
                        function (error){

                            console.log(error);
                        }
                    );
                }// end of create post function


                $scope.acceptSphereInvitation= function(member_index,sphere_id,index,notificationid){

                    //console.log(member_index);
                    //console.log(sphere_id);
                    //console.log(index);
                    //console.log(notificationid);

                    console.log($scope.allnotifications.notifications[index].sphere_invite_active);

                    $scope.allnotifications.notifications[index].sphere_invite_active = 0;

                    loadtemplates.responses({
                        method: 'POST',
                        url: 'acceptSphereInvitation',
                        data:{'sphereid':sphere_id,'member_index':member_index}
                    }).then(function (response) {

                            console.log(response);
                        //$rootScope.$broadcast('added_announce',response);
                        //$scope.allmembers = response.data;
                        //console.log(response.data);

                    }, function (response) {
                        console.log(response.data.error);
                    });

//                    loadtemplates.responses({
//                        method: 'POST',
//                        url: 'updateNotification',
//                        data:{'notificationid':notificationid}
//                    }).then(function (response) {
//
//
//                        //$rootScope.$broadcast('added_announce',response);
//                        //$scope.allmembers = response.data;
//                        //console.log(response.data);
//
//                    }, function (response) {
//                        console.log(response.data.error);
//                    });

                }
                
                $scope.declineSphereInvitation= function(member_index,sphere_id,index,notificationid){
                    
                    console.log(member_index);
                    console.log(sphere_id);
                    console.log(index);
                    console.log(notificationid);

                    $scope.allnotifications.notifications[index].sphere_invite_active = 0;



                    loadtemplates.responses({
                        method: 'POST',
                        url: 'declineSphereInvitation',
                        data:{'sphereid':sphere_id,'member_index':member_index}
                    }).then(function (response) {


                        //$rootScope.$broadcast('added_announce',response);
                        //$scope.allmembers = response.data;
                        //console.log(response.data);

                    }, function (response) {
                        console.log(response.data.error);
                    });
                    
//                    loadtemplates.responses({
//                        method: 'POST',
//                        url: 'updateNotification',
//                        data:{'notificationid':notificationid}
//                    }).then(function (response) {
//
//
//                        //$rootScope.$broadcast('added_announce',response);
//                        //$scope.allmembers = response.data;
//                        //console.log(response.data);
//
//                    }, function (response) {
//                        console.log(response.data.error);
//                    });

                }

                 $scope.acceptFriendRequest= function(profile_id,index,notificationid){

                    console.log(profile_id);
                    console.log(index);
                    console.log(notificationid);

                     $scope.allnotifications.notifications[index].connection_invite_active = 0;

                    // requestService.acceptRequest(profile_id,success,error);

                    loadtemplates.responses({
                        method: 'POST',
                        url: 'accept-request',
                        data:{'user':profile_id}
                    }).then(function (response) {

                            console.log(response);
                        //$rootScope.$broadcast('added_announce',response);
                        //$scope.allmembers = response.data;
                        //console.log(response.data);

                    }, function (response) {
                        console.log(response.data.error);
                    });



                }
                
                $scope.declineFriendRequest= function(profile_id,index,notificationid){

                    console.log(profile_id);
                    console.log(index);
                    console.log(notificationid);

                    $scope.allnotifications.notifications[index].connection_invite_active = 0;



                    loadtemplates.responses({
                        method: 'POST',
                        url: 'reject-friend-request',
                        data:{'request':profile_id}
                    }).then(function (response) {

                            console.log(response);
                        //$rootScope.$broadcast('added_announce',response);
                        //$scope.allmembers = response.data;
                        //console.log(response.data);

                    }, function (response) {
                        console.log(response.data.error);
                    });



                }
                
                $scope.acceptPostInvitation = function(postId,index){

                    console.log(postId);
                    console.log(index);

                    var self = this;
                    $scope.allnotifications.notifications[index].post_invite_active = 0;

                    loadtemplates.responses({
                        url:'post/accept/' + postId,
                        method:'GET',
                    }).then(
                        function(resp){

                            //console.log(resp.data);
                            var myEl = self.$parent.post=false;

                            var acceptDiv  = angular.element( document.querySelector( '#requestDecision-' + postId  +'' ) );
                            acceptDiv.addClass('hidden');

                            var acceptMessageDiv = angular.element( document.querySelector( '#acceptMessageDiv-' + postId  +'' ) );
                            acceptMessageDiv.removeClass('hidden');

                            var thisPostDiv = angular.element( document.querySelector( '#post-' + postId  +'' ) );
                            thisPostDiv.addClass('declined');

                            setTimeout(function() {
                                var thisPostDiv = angular.element( document.querySelector( '#post-' + postId  +'' ) );
                                thisPostDiv.addClass('hidden');
                            }, 1500);

                        },
                        function (error){

                            console.log(error);
                        }
                    );

                    
                }
                
                $scope.declinePostInvitation = function(postId,index){

                    console.log(postId);
                    console.log(index);

                    $scope.allnotifications.notifications[index].post_invite_active = 0;

                    //var self = this;

                    loadtemplates.responses({
                        url:'post/decline/' + postId,
                        method:'GET',
                    }).then(
                        function(resp){
                            //console.log(resp.data);

//                            var declineDiv = angular.element( document.querySelector( '#requestDecision-' + postId  +''  ) );
//                            declineDiv.addClass('hidden');
//
//                            var declineMessageDiv = angular.element( document.querySelector( '#declineMessageDiv-' + postId  +'' ) );
//                            declineMessageDiv.removeClass('hidden');
//
//                            var thisPostDiv = angular.element( document.querySelector( '#post-' + postId  +'' ) );
//                            thisPostDiv.addClass('declined');
//
//                            setTimeout(function() {
//                                var thisPostDiv = angular.element( document.querySelector( '#post-' + postId  +'' ) );
//                                thisPostDiv.addClass('hidden');
//                            }, 1500);

                        },
                        function (error){

                            console.log(error);
                        }
                    );
                    
                }

                
            }
        ]
    );

  
})(window);
