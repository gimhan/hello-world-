(function(window){

  var msg=angular.module('learersocial');

    msg.service('sphereService',
        ['$rootScope','loadtemplates',
            function($rootScope,loadtemplates,$window){

                var mid=$(window).outerHeight()-$('#h_header_main').outerHeight()-60;
                $("#h_content_main").height(mid);
                $('#messages_box').height(mid-174);
                $('.tab-content').css('max-height',mid-200);
                $('#left_messages_panel_body').height(mid-48);

                this.spherePage=1,
                    this.messagePage=1;

                this.isLoadHistory=false;
                this.isLoadSpheres=false;

                this.stamp=$('#stamp').val();
                this.currentSphere=null;



                this.sendMessage=function(param,success,error){
                    loadtemplates.responses({
                        url: 'sphere-send-message',
                        method:'POST',
                        data:param
                    }).then(
                        function(resp){
                            success(resp.data);
                        },

                        function(resp){
                            error(resp.data);
                        }
                    );
                }

                this.SearchMembers=function(param,callBack){
                    loadtemplates.responses({
                        url: 'search-sphere-members',
                        method:'POST',
                        data:param
                    }).then(
                        function(resp){
                            //console.log(resp);
                            callBack(resp.data.members);
                        },
                        function(resp){

                        }
                    );
                }

                this.loadSpheres=function(callBack){


                    if(this.isLoadSpheres){
                        return;
                    }
                    var self=this;
                    self.isLoadSpheres=true;
                    loadtemplates.responses({
                        url: 'load-sphere',
                        data:{'page':self.spherePage,'stamp':self.stamp}
                    }).then(
                        function(resp){
                            self.isLoadSpheres=false;
                            if(resp.data.status){
                                if(resp.data.sphers.length>0){
                                    self.spherePage+=1;
                                }
                                callBack(resp.data.sphers);


                            }

                        },
                        function(resp){
                            self.isLoadSpheres=false;
                        }
                    );

                }

                this.loadMessages=function(callBack){

                    var self=this;

                    if(this.currentSphere==null){
                        return;
                    }

                    if(this.isLoadHistory){
                        return;
                    }

                    self.isLoadHistory=true;

                    loadtemplates.responses({
                        url:'load-sphere-history',
                        data:{'data':self.messagePage,'sphere':self.currentSphere.sphereid,'stamp':self.stamp}
                    }).then(
                        function(resp){
                            self.isLoadHistory=false;
                            if(resp.data.status){

                                callBack(resp.data.history);

                                if(resp.data.history.length>0){
                                    self.messagePage+=1;
                                }

                            }

                        },
                        function(resp){
                            self.isLoadHistory=false;
                        }
                    );
                }

                this.exitSphere=function(sphereid,callback){
                    var self=this;
                    loadtemplates.responses({
                        method:'POST',
                        url:'sphere-exit',
                        data:{'index':self.currentSphere.sphereid}
                    })
                        .then(
                        function(resp){
                            $rootScope.$broadcast('sphereExit',resp.data);
                            callback(resp.data);
                        },
                        function(resp){

                        }
                    )
                }
            }]);

    msg.controller('allsphere',
        ['$element','$scope','$rootScope','$http','loadtemplates','scoket',
            function($element,$scope,$rootScope,$http,loadtemplates,scoket){

                $scope.rootid = $rootScope.ah.id;

                //console.log('hello');
                loadtemplates.responses({
                    method: 'GET',
                    url: 'getAllSphere'

                }).then(function (response) {

                    //console.log(response);

                    if((response.data.allsphere == 'null') || (response.data.allsphere == '')){
                        $scope.empty = true;
                        $scope.full = false;
                    }else{
                        $scope.empty = false;
                        $scope.full = true;
                        $scope.allsphere = response.data;


                    }

                }, function (response) {
                    console.log(response.data.error);
                });

                $scope.getOneSphere= function(sphereid){

                    $scope.sphereid=sphereid;
                    $rootScope.$broadcast('singlesphere',$scope);

                loadtemplates.responses({
                    method: 'GET',
                    url: 'getOneSphere',
                    data:{'sphereid':sphereid}
                }).then(function (response) {

                }, function (response) {
                    //console.log(response.data.error);
                });
                }

                     
           $scope.$on('saved_sphere',function(event,data){

               if($scope.empty == true){
                   $scope.empty = false;
                   $scope.full = true;
                   $scope.allsphere = data.data;
                   //console.log($scope.allsphere);

               }else{

                   $scope.allsphere.allsphere.unshift({

                       sphereid:data.data.allsphere[0].sphereid,
                       name:data.data.allsphere[0].name,
                       userid:data.data.allsphere[0].userid,
                       description:data.data.allsphere[0].description,
                       created_date:data.data.allsphere[0].created_date,
                       image:data.data.allsphere[0].image,
                       connections:data.data.allsphere[0].connections,
                       announcements:0

                   });

               }
        });

                $scope.deleteSphere =function(sphereid,index){

                    $scope.allsphere.allsphere.splice(index, 1);

                    //console.log(sphereid,index);

                    loadtemplates.responses({
                        method: 'POST',
                        url: 'deleteSphere',
                        data:{'sphereid':sphereid}
                    }).then(function (response) {

                  $rootScope.$broadcast('delete_sphere',response);

                    }, function (response) {
                        //console.log(response.data.error);
                    });

                }

            }]);

    msg.controller('onesphere',
        ['$element','$scope','$rootScope','$http','loadtemplates','$location',
            function($element,$scope,$rootScope,$http,loadtemplates,$location){

                $scope.spehereid = $scope.id;
                $scope.rootid = $rootScope.ah.id;

                    loadtemplates.responses({
                        method: 'POST',
                        url: 'getGivenSphere',
                        data:{'sphereid':$('#sphereid').val()}
                    }).then(function (response) {
                        //console.log(response.data);
                        $scope.sphere_id = response.data.givensphere.sphere_data[0].id;
                        $scope.name = response.data.givensphere.sphere_data[0].name;
                        $scope.userid = response.data.givensphere.sphere_data[0].user_id;
                        $scope.description = response.data.givensphere.sphere_data[0].description;
                        $scope.created_at = response.data.givensphere.sphere_data[0].created_at;
                        $scope.image = response.data.givensphere.sphere_data[0].image;
                        $scope.status = response.data.givensphere.sphere_data[0].status;
                        $scope.sphere_connections = response.data.givensphere.connections;
                        $scope.image_link = response.data.givensphere.image_link;

                        $rootScope.$broadcast('sphere_admin_of_given',$scope.userid);

                        //console.log($scope.name);

                    }, function (response) {
                        //console.log(response.data.error);
                    });

                $scope.editSphere = function(name,description){


                    $scope.edit_data = [];

                    $scope.edit_data['name'] = name;
                    $scope.edit_data['description'] = description;

                    //console.log($scope.edit_data);

                    $rootScope.$broadcast('sphereed',$scope);

                }

                $scope.editSphereImage = function(sphere_id,image,userid){


                    $scope.edit_Image = [];

                    $scope.edit_Image['sphere_id'] = sphere_id;
                    $scope.edit_Image['image'] = image;
                    $scope.edit_Image['userid'] = userid;

                    //console.log($scope.edit_Image);

                    $rootScope.$broadcast('image_edit',$scope.edit_Image);

                }

                $scope.spherePrivacy = function(privacy){

                    $scope.status = privacy;

                    loadtemplates.responses({
                        method: 'POST',
                        url: 'setSpherePrivacy',
                        data:{'sphereid':$('#sphereid').val(),status: $scope.status}
                    }).then(function (response) {
                        //console.log(response.data);


                    }, function (response) {
                        //console.log(response.data.error);
                    });

                }

                $scope.$on('edited_sphere',function(event,data){

                    $scope.name = data.data.edited_sphere_data[0].name;
                    $scope.description = data.data.edited_sphere_data[0].description;
                    //console.log($scope.name);

                });

                $scope.$on('edited_image',function(event,data){

                    //console.log(data);

                    $scope.image = data;

                });

            }]);

   
    msg.controller('spheretoedit',
        ['$element','$scope','$rootScope','$http','loadtemplates','$location',
            function($element,$scope,$rootScope,$http,loadtemplates,$location){
                //console.log('test');
               // $scope.sphere_edit_name = 'dan';
               // $scope.sphere_edit_description = 'carter';
                $scope.$on('sphereed',function(event,data){

                    $scope.sphere_edit_name = data.edit_data['name'];
                    $scope.sphere_edit_description = data.edit_data['description'];

                    //console.log(data);

                });



                $scope.save_edit=function(){

                    // jQUERY INJECTED VALIDATION BEGINS HERE
                    // for the following validation the "null-error" CSS class
                    // is added to show the red highlighted textbox

                    // jQUERY automatic Validation for text fields
                    $('#sphere_edit_name').trigger('blur');
                    $('#sphere_edit_description').trigger('blur');

                    if ($('#sphere_edit_name').val() == '' || $('#sphere_edit_description').val() == '') {
                        $('#warning-message-sphere-edit').removeClass('hidden');
                        return
                    } else {
                        // hide the error messgae and hide
                        $('#warning-message-sphere-edit').addClass('hidden');

                        // close the modal
                        //$('#new-sphere').modal('hide');
                        $('#mng-sphere').modal('hide');
                    }


                    loadtemplates.responses({
                        method: 'POST',
                        url: 'editSphereSave',
                        data:{'sphereid':$('#sphereid').val(),'sphere_name':$scope.sphere_edit_name,'description':$scope.sphere_edit_description}
                    }).then(function (response) {

                        $rootScope.$broadcast('edited_sphere',response);
                        //$scope.allmembers = response.data;
                        //console.log(response.data);

                    }, function (response) {
                        //console.log(response.data.error);
                    });
                }

            }]);


    msg.controller('commenttoedit',
        ['$element','$scope','$rootScope','$http','loadtemplates','$location',
            function($element,$scope,$rootScope,$http,loadtemplates,$location){
                //console.log('test');
                // $scope.sphere_edit_name = 'dan';
                // $scope.sphere_edit_description = 'carter';
                //console.log('hi');
                $scope.$on('comment_edit',function(event,data){

                    //console.log(data);


                    $scope.comment_edit_id = data.commentid;
                    $scope.comment_edit_index = data.index;
                    $scope.comment_edit_status_index = data.statusid;

                    $scope.sphere_edit_name_comment = data.comment;

                });

                $scope.editCommentSave= function(){

                    // jQUERY INJECTED VALIDATION BEGINS HERE
                    // for the following validation the "null-error" CSS class
                    // is added to show the red highlighted textbox

                    // jQUERY automatic Validation for text fields
                    $('#sphere_edit_comment').trigger('blur');


                    if ($('#sphere_edit_comment').val() == '') {
                        $('#warning-message-sphere-comm').removeClass('hidden');
                        return
                    } else {
                        // hide the error messgae and hide
                        $('#warning-message-sphere-comm').addClass('hidden');

                        // close the modal
                        $('#edit-s-comment').modal('hide');
                    }

                    //console.log(comment);

                    loadtemplates.responses({
                        method: 'POST',
                        url: 'editStatusComment',
                        data:{'commentid':$scope.comment_edit_id,'comment': $scope.sphere_edit_name_comment}
                    }).then(function (response) {

                        //console.log(response);
                        //$scope.sphere_status = "";
                        $scope.comment_edit_result = [];
                        $scope.comment_edit_result['index'] = $scope.comment_edit_index
                        $scope.comment_edit_result['status_index'] =  $scope.comment_edit_status_index;
                        $scope.comment_edit_result['edit_comment'] = response.data.edit_comment;

                        $rootScope.$broadcast('edit_comment_result',$scope.comment_edit_result);

                    }, function (response) {
                        //console.log(response.data.error);
                    });

                }

            }]);

    msg.controller('editStatus',
        ['$element','$scope','$rootScope','$http','loadtemplates',
            function($element,$scope,$rootScope,$http,loadtemplates){

                // $scope.sphere_edit_name = 'dan';
                // $scope.sphere_edit_description = 'carter';
                $scope.$on('status_edit',function(event,data){

                   //console.log(data);

                    $scope.sphere_edit_name = data.status;
                    $scope.status_id = data.statusid;
                    $scope.spherefiles_edit = data.status_file;
                    $scope.sphereimages_edit = data.status_image;
                    $scope.status_index = data.index;


                    //console.log($scope.sphere_edit_description);

                });

                $scope.sphereimages_new = [];
                $scope.spherefiles_new =  [];

                $scope.upload_edit={

                    success:function(xResponse,status){
                        //console.log(xResponse);
                        $scope.file=xResponse['obj'].filename;
                        $scope.filetype=xResponse['obj'].type;
                        $scope.originalname=xResponse['obj'].original_name;
                        $scope.response = xResponse['obj'];
                        //$scope.display = xResponse['obj'].link;

                        if(xResponse.success){

                            if(xResponse.obj.type=="image"){

                                $scope.sphereimages_edit.push(xResponse.obj);
                                $scope.sphereimages_new.push(xResponse.obj);

                            }else if(xResponse.obj.type=="doc"){
                                $scope.spherefiles_edit.push(xResponse.obj);
                                $scope.spherefiles_new.push(xResponse.obj);
                            }
                        }

                       // console.log($scope.spherefiles);

                    },
                    error:function(xResponse,status){}
                };


                $scope.editStatusUpdate= function(){

                    // jQUERY INJECTED VALIDATION BEGINS HERE
                    // for the following validation the "null-error" CSS class
                    // is added to show the red highlighted textbox

                    // jQUERY automatic Validation for text fields
                    $('#sphere_edit_status').trigger('blur');
                    //$('#sphere_edit_description').trigger('blur');

                    if ($('#sphere_edit_status').val() == '') {
                        $('#warning-message-spherestatus-edit').removeClass('hidden');
                        return
                    } else {
                        // hide the error messgae and hide
                        $('#warning-message-spherestatus-edit').addClass('hidden');

                        // close the modal
                        $('#edit-s-Q-A').modal('hide');

                    }

                        loadtemplates.responses({
                            method: 'POST',
                            url: 'editStatusUpdate',
                            data:{'sphereid':$('#sphereid').val(),'statusid':$scope.status_id,'status':$scope.sphere_edit_name,'files':$scope.spherefiles_new,'images':$scope.sphereimages_new}
                        }).then(function (response) {

                           // console.log(response);
                            //$scope.sphere_status = "";
                            $scope.status_edit_result = [];
                            $scope.status_edit_result['index'] = $scope.status_index;
                            $scope.status_edit_result['edit_data'] = response;

                            $rootScope.$broadcast('edit_result',$scope.status_edit_result);



                        }, function (response) {
                            //console.log(response.data.error);
                        });

                }


                $scope.remove_image=function(index,id){

                    $scope.sphereimages_edit.splice(index,1);
                    //console.log($scope.sphere_edit_name);
                    //console.log($scope.sphere_edit_description);

                    loadtemplates.responses({
                        method: 'POST',
                        url: 'removeStatusImage',
                        data:{'imageid':id}
                    }).then(function (response) {

                        $rootScope.$broadcast('edited_sphere',response);
                        //$scope.allmembers = response.data;
                        //console.log(response.data);

                    }, function (response) {
                        //console.log(response.data.error);
                    });
                }

                $scope.remove_file=function(index,id){

                    $scope.sphereimages_edit.splice(index,1);
                    //console.log($scope.sphere_edit_name);
                    //console.log($scope.sphere_edit_description);

                    loadtemplates.responses({
                        method: 'POST',
                        url: 'removeStatusFiles',
                        data:{'fileid':id}
                    }).then(function (response) {

                        $rootScope.$broadcast('edited_sphere',response);
                        //$scope.allmembers = response.data;
                        //console.log(response.data);

                    }, function (response) {
                        //console.log(response.data.error);
                    });
                }

            }]);

    msg.controller('deleteStatus',
        ['$element','$scope','$rootScope','$http','loadtemplates',
            function($element,$scope,$rootScope,$http,loadtemplates){

                $scope.$on('status_delete',function(event,data){

                    //console.log(data);
                    $scope.status_id = data.statusid;
                    $scope.status_index = data.index;



                    //console.log($scope.sphere_edit_description);

                });

                $scope.delete_status=function(){

                    //console.log($scope.status_index);

                    ///$scope.all_status_updates.all_status_updates.splice($scope.status_index,1);
                    //console.log($scope.sphere_edit_name);
                    //console.log($scope.sphere_edit_description);

                    loadtemplates.responses({
                        method: 'POST',
                        url: 'deleteStatusupdate',
                        data:{'statusid':$scope.status_id}
                    }).then(function (response) {

                        $rootScope.$broadcast('delete_status_success',$scope.status_index);
                        //$scope.allmembers = response.data;
                        //console.log(response.data);

                    }, function (response) {
                        //console.log(response.data.error);
                    });
                }

            }]);


    msg.controller('deleteComment',
        ['$element','$scope','$rootScope','$http','loadtemplates',
            function($element,$scope,$rootScope,$http,loadtemplates){

                $scope.$on('comment_delete',function(event,data){

                    //console.log(data);
                    $scope.status_id = data.statusid;
                    $scope.commentid = data.commentid;
                    $scope.status_index = data.index;



                    //console.log($scope.sphere_edit_description);

                });

                $scope.delete_comment=function(){


                    //console.log($scope.sphere_edit_name);
                    //console.log($scope.sphere_edit_description);

                    loadtemplates.responses({
                        method: 'POST',
                        url: 'deleteStatusComment',
                        data:{'commentid':$scope.commentid}
                    }).then(function (response) {
                        $scope.delete_comment_sussess = [];
                        $scope.delete_comment_sussess['index'] =$scope.status_index;
                        $scope.delete_comment_sussess['statusid'] =$scope.status_id;


                        $rootScope.$broadcast('delete_comment_success',$scope.delete_comment_sussess);
                        //$scope.allmembers = response.data;
                        //console.log(response.data);

                    }, function (response) {
                        //console.log(response.data.error);
                    });
                }

            }]);


    msg.controller('latestannouncements',
        ['$element','$scope','$rootScope','$http','loadtemplates',
            function($element,$scope,$rootScope,$http,loadtemplates){

                loadtemplates.responses({
                    method: 'GET',
                    url: 'getLatestSphereAnnouncement',
                    data:{'sphereid':$('#sphereid').val()}
                }).then(function (response) {
                    //console.log(response.data);
                    if((response.data.announcemnts == 'null') || (response.data.announcemnts == '')){
                        $scope.empty = true;
                        $scope.full = false;
                    }else{
                        $scope.empty = false;
                        $scope.full = true;
                        $scope.latest = response.data;
                    }

                }, function (response) {
                    console.log(response.data.error);
                });

                $scope.$on('added_announce',function(event,data){

                    //console.log(data);
                    //popupService.popupAlign($element);

                    if($scope.empty == true){
                        $scope.empty = false;
                        $scope.full = true;
                        $scope.latest = data.data;
                        //console.log($scope.all);

                    }else{

                        $scope.latest.allannouncemnts.unshift({

                            announcement_header:data.data.allannouncemnts[0].announcement_header,
                            announcement_description:data.data.allannouncemnts[0].announcement_description,
                            created_at:data.data.allannouncemnts[0].created_at,
                            first_name:data.data.allannouncemnts[0].first_name,
                            last_name:data.data.allannouncemnts[0].last_name,
                            user_id:data.data.allannouncemnts[0].user_id,
                            profile_image:data.data.allannouncemnts[0].profile_image

                        });

                        $scope.latest.allannouncemnts.splice(2,1);

                    }
                    $scope.latest.allannouncemnts.splice(3,1);

                });

                $scope.$on('delete_annou',function(event,data){

                    $scope.latest.allannouncemnts.splice(data, 1);

                });

                $scope.deleteAnnouncement =function(annoid,index){

                    $scope.latest.allannouncemnts.splice(index, 1);

                    //console.log(sphereid,index);
                    $rootScope.$broadcast('delete_annou_late',index);

                    loadtemplates.responses({
                        method: 'POST',
                        url: 'deleteAnnouncements',
                        data:{'annoid':annoid}
                    }).then(function (response) {

                        //$rootScope.$broadcast('delete_sphere',response);

                    }, function (response) {
                        //console.log(response.data.error);
                    });

                }

                $scope.addAnnouncements= function(sphereid) {

                    loadtemplates.responses({
                        method: 'POST',
                        url: 'addSphereAnnouncement',
                        data:{'sphereid':sphereid}
                    }).then(function (response) {

                    }, function (response) {
                        //console.log(response.data.error);
                    });
                }

                $scope.viewmore = function(){

                    $scope.active = 'active';


                }


                $scope.reset = function(){

                    $scope.active = '';


                }

                $scope.hideAll= function(){

                    $scope.allhide = true;
                    $scope.full = false;
                    $scope.empty = false;

                }

                $scope.removeHide= function(){

                    $scope.full = true;
                    $scope.allhide = false;
                    $scope.empty = false;

//                    loadtemplates.responses({
//                        method: 'POST',
//                        url: 'hideAnnouncement',
//                        data:{'sphereid':$('#sphereid').val()}
//                    }).then(function (response) {
//
//
//
//                    }, function (response) {
//                        console.log(response.data.error);
//                    });

                }

                $scope.hideOne= function(annoid,index){

                    //console.log(annoid);
                    //console.log(index);

                    $scope.latest.allannouncemnts.splice(index, 1);

                    loadtemplates.responses({
                        method: 'POST',
                        url: 'hideAnnouncement',
                        data:{'sphereid':$('#sphereid').val(),'anno_id':annoid}
                    }).then(function (response) {



                    }, function (response) {
                        //console.log(response.data.error);
                    });

                }

            }]);


    msg.controller('allannouncements',
        ['$element','$scope','$rootScope','$http','loadtemplates',
            function($element,$scope,$rootScope,$http,loadtemplates){

                $scope.spehereid = $scope.id;
                $scope.rootid = $rootScope.ah.id;

                loadtemplates.responses({
                    method: 'POST',
                    url: 'getGivenSphere',
                    data:{'sphereid':$('#sphereid').val()}
                }).then(function (response) {
                    //console.log(response.data);
                    $scope.sphere_id = response.data.givensphere.sphere_data[0].id;
                    $scope.name = response.data.givensphere.sphere_data[0].name;
                    $scope.userid = response.data.givensphere.sphere_data[0].user_id;
                    $scope.description = response.data.givensphere.sphere_data[0].description;
                    $scope.created_at = response.data.givensphere.sphere_data[0].created_at;
                    $scope.image = response.data.givensphere.sphere_data[0].image;
                    $scope.status = response.data.givensphere.sphere_data[0].status;
                    $scope.sphere_connections = response.data.givensphere.connections;
                    $scope.image_link = response.data.givensphere.image_link;

                    $rootScope.$broadcast('sphere_admin_of_given',$scope.userid);

                    //console.log($scope.name);

                }, function (response) {
                    //console.log(response.data.error);
                });

                loadtemplates.responses({
                    method: 'GET',
                    url: 'getAllSphereAnnouncement',
                    data:{'sphereid':$('#sphereid').val()}
                }).then(function (response) {
                        console.log(response.data)
                    if((response.data.allannouncemnts == 'null') || (response.data.allannouncemnts == '')){
                        $scope.empty = true;
                        $scope.full = false;
                    }else{
                        $scope.empty = false;
                        $scope.full = true;
                        $scope.all = response.data;
                    }


                    //console.log(response.data);

                }, function (response) {
                    //console.log(response.data.error);
                });

                $scope.hideOne= function(annoid,index){

                    //console.log(annoid);
                    //console.log(index);

                    $scope.all.allannouncemnts.splice(index, 1);

                    loadtemplates.responses({
                        method: 'POST',
                        url: 'hideAnnouncement',
                        data:{'sphereid':$('#sphereid').val(),'anno_id':annoid}
                    }).then(function (response) {



                    }, function (response) {
                        //console.log(response.data.error);
                    });

                }

                $scope.$on('added_announce',function(event,data){

                    //console.log(data.data.heading);
                    //popupService.popupAlign($element);

                    if($scope.empty == true){
                        $scope.empty = false;
                        $scope.full = true;
                        $scope.all = data.data;
                        //console.log($scope.all);

                    }else{
                        $scope.all.allannouncemnts.unshift({


                            announcement_header:data.data.allannouncemnts[0].announcement_header,
                            announcement_description:data.data.allannouncemnts[0].announcement_description,
                            created_at:data.data.allannouncemnts[0].created_at,
                            first_name:data.data.allannouncemnts[0].first_name,
                            last_name:data.data.allannouncemnts[0].last_name,
                            user_id:data.data.allannouncemnts[0].user_id,
                            profile_image:data.data.allannouncemnts[0].profile_image

                        });

                    }

                });

                $scope.deleteAnnouncement =function(annoid,index){

                    $scope.all.allannouncemnts.splice(index, 1);

                    //console.log(sphereid,index);
                    $rootScope.$broadcast('delete_annou',index);

                    loadtemplates.responses({
                        method: 'POST',
                        url: 'deleteAnnouncements',
                        data:{'annoid':annoid}
                    }).then(function (response) {

                        //$rootScope.$broadcast('delete_sphere',response);

                    }, function (response) {
                        //console.log(response.data.error);
                    });

                }

                $scope.$on('delete_annou_late',function(event,data){

                    $scope.all.allannouncemnts.splice(data, 1);

                });




            }]);


    msg.controller('allmembers',
        ['$element','$scope','$rootScope','$http','loadtemplates',
            function($element,$scope,$rootScope,$http,loadtemplates){


                //$scope.userList=[];
                $scope.users =[];

                loadtemplates.responses({

                    method: 'GET',
                    url: 'getAllMembers',
                    data:{'sphereid':$('#sphereid').val()}
                }).then(function (response) {

                    $scope.allmembers = response.data;
                    //console.log(response.data);

                }, function (response) {
                    //console.log(response.data.error);
                });

                $scope.searchUser= function(usersName){



                    $('#post_usersearch_suggestions').removeClass('hidden');
                    if (usersName != null){
                    loadtemplates.responses({
                        method: 'POST',
                        url: 'inviteUsersSphere',
                        data:{'search_name':usersName,sphereid:$('#sphereid').val()}
                        //data:{'search_name':$scope.search,sphereid:$('#sphereid').val()}

                    }).then(function (response) {

                        console.log(response);
                        //$scope.search_names = response.data;
                        //$rootScope.$broadcast('saved_sphere',response);
                        //console.log(response.data);
                        $scope.users = [];
                        // fetch the response from the backend
                        var data = response.data;
                        // add each item in the response to the declared array
                        for(var i=0; i<data.search_names.length; i++){
                            $scope.users.push(response.data.search_names[i]);
                        }
                        console.log($scope.users);


                    }, function (response) {
                        //console.log(response.data.error);
                    });
                    }

                }

                $scope.rootid = $rootScope.ah.id;

                var superviceindex = 0;
                $scope.PosthoverUser = 1;

                $scope.keyboardControl = function(event) {

                    if($scope.users.length>0) {

                        if(event.keyCode==38 || event.keyCode==40 || event.keyCode==13) {
                            event.preventDefault();
                        }

                        switch (event.keyCode) {
                            case 38:
                                superviceindex-=1;

                                if(superviceindex==0){
                                    superviceindex=$scope.users.length;
                                }

                                $scope.PosthoverUser=$scope.users[superviceindex-1];
                                console.log($scope.PosthoverUser)
                                break;
                            case 40:
                                superviceindex+=1;

                                if(superviceindex>$scope.users.length){
                                    superviceindex=0;
                                    superviceindex+=1;
                                }

                                $scope.PosthoverUser=$scope.users[superviceindex-1];
                                console.log($scope.PosthoverUser)

                                break;
                            case 13:
                                console.log("enter broh");
                                break;
                        }
                    }

                }

                // globally declare this array to stop the array being recreated at
                // the  selectUser function initialization
                $scope.selectedUserList = [];

                // selecting a user
                $scope.selectUser = function() {

                    // after a user is selected, clear the text in the textbox
                    $('#post_create_addUser').val('');


                    // limit the authors to 59
                    if($scope.selectedUserList >= 50){
                        // reset the suggestion array and break the function
                        $scope.users = [];
                        return;
                    }

                    // Each time a user is added, check if the user exists in the array
                    // if the user exists, then avoid adding the user, else add the user
                    for (i = 0; i < $scope.selectedUserList.length; i++) {
                        if (angular.equals($scope.selectedUserList[i], this.user)) {
                            // reset the suggestion array and break the function
                            $scope.users = [];
                            return;
                        }
                    }

                    // adding the user to the array
                    $scope.selectedUserList.push(this.user);
                    // reset the users array so it can refil when the user types a name to search agian
                    $scope.users = [];

                } // end of search User function

                // remove a user from the selected list
                $scope.removeList = function(){

                    $scope.selectedUserList.splice(this.$index,1);

                } // end of selected list remove function


//                $scope.selectUser=function(){
//
//                    $scope.search='';
//                    $scope.search_names=[];
//
//                    if($scope.userList.length > 0){
//                        //console.log($scope.userList.length );
//                        for(var i = 0; i < $scope.userList.length; i++) {
//                            if($scope.userList[i].id == this.user.id){
//                                //console.log($scope.userList[i].id);
//                                //console.log(this.user.id);
//                                $scope.userList.splice(i,1);
//
//                            }
//                        }
//
//                    }
//                    $scope.userList.push(this.user);
//
//
//                    $scope.users=[];
//
//                    //console.log($scope.userList);
//
//                }
//
//                $scope.removeList=function(){
//
//                    $scope.userList.splice(this.$index,1);
//                }

                $scope.addUserToMemberList = function(){

                    //console.log($scope.userList);

                    loadtemplates.responses({
                        method: 'POST',
                        url: 'addUsersToMemberList',
                        data:{'sphereid':$('#sphereid').val(),'users':$scope.selectedUserList}
                    }).then(function (response) {

                        $scope.selectedUserList='';

//                        for(var i = 0; i < $scope.userList.length; i++) {
//
//                            $scope.allmembers.allmembers.push({
//
//                                first_name:$scope.userList[0].first_name,
//                                last_name:$scope.userList[0].last_name,
//                                profile_image:$scope.userList[0].profile_image,
//                                created_at:$scope.userList[0].created_at
//
//                            });
//
//                            $scope.userList='';
//
//                        }

                        //$scope.search_names = response.data;
                        //$rootScope.$broadcast('new_members',$scope.userList);
                        //console.log(response.data);

                    }, function (response) {
                        //console.log(response.data.error);
                    });

                }

                $scope.removeMember = function($index,member){

                    $scope.allmembers.allmembers.splice(this.$index,1);

                    loadtemplates.responses({
                        method: 'POST',
                        url: 'removeMember',
                        data:{'sphereid':$('#sphereid').val(),'member':member}
                    }).then(function (response) {


                        //$scope.search_names = response.data;
                        //$rootScope.$broadcast('new_members',$scope.userList);
                        //console.log(response.data);

                    }, function (response) {
                        //console.log(response.data.error);
                    });
                }

                $scope.$on('sphere_admin_of_given',function(event,data){
                    //console.log(data);
                    $scope.sphere_admin_id = data;
                    //console.log($scope.sphere_admin_id);
                });

            }]);

    msg.controller('specific_sphere',['$element','$scope','$rootScope','$http','loadtemplates',function($element,$scope,$rootScope,$http,loadtemplates){
       // return{

            //templateUrl:'single_sphere.blade.php',
           //controller:function($scope,$element){

                $scope.spehereid = $scope.id;
                $scope.rootid = $rootScope.ah.id;

            loadtemplates.responses({
                method: 'POST',
                url: 'getGivenSphere',
                data:{'sphereid':$('#sphereid').val()}
            }).then(function (response) {
                //console.log(response.data);
                $scope.sphere_id = response.data.givensphere.sphere_data[0].id;
                $scope.name = response.data.givensphere.sphere_data[0].name;
                $scope.userid = response.data.givensphere.sphere_data[0].user_id;
                $scope.description = response.data.givensphere.sphere_data[0].description;
                $scope.created_at = response.data.givensphere.sphere_data[0].created_at;
                $scope.image = response.data.givensphere.sphere_data[0].image;
                $scope.status = response.data.givensphere.sphere_data[0].status;
                $scope.sphere_connections = response.data.givensphere.connections;
                $scope.image_link = response.data.givensphere.image_link;

                $rootScope.$broadcast('sphere_admin_of_given',$scope.userid);

                //console.log($scope.name);

            }, function (response) {
                //console.log(response.data.error);
            });
            //}



         //}
        }]);



    msg.controller('addannouncement',
        ['$element','$scope','$rootScope','$http','loadtemplates',
            function($element,$scope,$rootScope,$http,loadtemplates){

                $scope.addAnnouncements= function(){

                // jQUERY INJECTED VALIDATION BEGINS HERE
                // for the following validation the "null-error" CSS class
                // is added to show the red highlighted textbox

                // jQUERY automatic Validation for text fields
                $('#ann_title').trigger('blur');
                $('#ann_description').trigger('blur');

                if ($('#ann_title').val() == '' || $('#ann_description').val() == '') {
                    $('#warning-message-sphere-ann').removeClass('hidden');
                    return
                } else {
                    // hide the error messgae and hide
                    $('#warning-message-sphere-edit').addClass('hidden');

                    // close the modal
                    $('#new-sphere').modal('hide');
                }

                loadtemplates.responses({
                    method: 'POST',
                    url: 'addSphereAnnouncement',
                    data:{'sphereid':$('#sphereid').val(),'announcemnt_header':$scope.announcement_header,'description':$scope.description}
                }).then(function (response) {

                    $rootScope.$broadcast('added_announce',response);
                    //$scope.allmembers = response.data;
                    $scope.announcement_header = '';
                    $scope.description = '';
                    $('#new-annou').modal('hide');
                    //console.log(response.data);

                }, function (response) {
                    //console.log(response.data.error);
                });

                }

            }]);


    msg.controller('invitations',
        ['$element','$scope','$rootScope','$http','loadtemplates',
            function($element,$scope,$rootScope,$http,loadtemplates){

                loadtemplates.responses({
                    method: 'GET',
                    url: 'getAllInvitations'
                    //data:{'sphereid':$('#sphereid').val()}
                }).then(function (response) {

                    $scope.invitations = response.data;
                    //console.log(response.data);

                }, function (response) {
                    //console.log(response.data.error);
                });

                $scope.acceptSphereInvitation= function(member_index,sphere_id,index){

                    //console.log(sphere_id);
                    //console.log(member_index);

                    $scope.invitations.allinvitations.splice(index,1);
                    $rootScope.notifications.sphere_invite-=1;

                    loadtemplates.responses({
                        method: 'POST',
                        url: 'acceptSphereInvitation',
                        data:{'sphereid':sphere_id,'member_index':member_index}
                    }).then(function (response) {
                        $rootScope.$broadcast('saved_sphere',response);
                        //$scope.allmembers = response.data;
                        //console.log(response);

                    }, function (response) {
                        //console.log(response.data.error);
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

                $scope.declineSphereInvitation= function(member_index,sphere_id,index){

                    $scope.invitations.allinvitations.splice(index,1);
                    $rootScope.notifications.sphere_invite-=1;

                    loadtemplates.responses({
                        method: 'POST',
                        url: 'declineSphereInvitation',
                        data:{'sphereid':sphere_id,'member_index':member_index}
                    }).then(function (response) {


                        //$rootScope.$broadcast('added_announce',response);
                        //$scope.allmembers = response.data;
                        //console.log(response.data);

                    }, function (response) {
                        //console.log(response.data.error);
                    });

                }

            }]);


    msg.controller('sphereadd',
        ['$element','$scope','$rootScope','$http','loadtemplates','fileUpload','sphereService',
            function($element,$scope,$rootScope,$http,loadtemplates,fileUpload,sphereService){

                $scope.privacy_applyed = 1;
                $scope.display = null;
                //$scope.show_default == true;
               // $scope.show_upload == false;

                //$scope.userList=[];

                $scope.users = [];

                $scope.upload={
                    success:function(xResponse,status){
                        console.log(xResponse);

                        $scope.image=xResponse['obj'].filename;
                        $scope.image_object = xResponse['obj'];
                        $scope.image=xResponse['obj'];
                        $scope.display = xResponse['obj'].link;
                       // $scope.show_default == false;
                        //$scope.show_upload == true;


                    },
                    error:function(xResponse,status){}
                };

                $scope.spherePrivacy= function (privacy){

                    $scope.privacy_applyed = privacy;

                }

                $scope.addSphere= function(){

                    // jQUERY INJECTED VALIDATION BEGINS HERE
                    // for the following validation the "null-error" CSS class
                    // is added to show the red highlighted textbox

                    // jQUERY automatic Validation for text fields
                    $('#sphere_name').trigger('blur');
                    $('#sphere_description').trigger('blur');

                    if ($('#sphere_name').val() == '' || $('#sphere_description').val() == '') {
                        $('#warning-message-sphere').removeClass('hidden');
                        return
                    } else {
                        // hide the error messgae and hide
                        $('#warning-message-sphere').addClass('hidden');

                        // close the modal
                        $('#new-sphere').modal('hide');
                    }


                    loadtemplates.responses({
                        method: 'POST',
                        url: 'setSphere',
                        data:{'sphere_name':$scope.sphere_name,'sphere_description':$scope.sphere_description,'image_object':$scope.image_object,'users':$scope.selectedUserList,'status':$scope.privacy_applyed}
                    }).then(function (response) {

                        $scope.sphere_name = "";
                        $scope.sphere_description="";
                        $scope.image="";

                        //$scope.saved_sphere = response.data;
                        $rootScope.$broadcast('saved_sphere',response);
                       //console.log(response.data);

                    }, function (response) {
                        //console.log(response.data.error);
                    });

                }


                $scope.searchUser= function(usersName){

                    $('#post_usersearch_suggestions').removeClass('hidden');

                    loadtemplates.responses({
                        method: 'POST',
                        url: 'inviteUsers',
                        data:{'search_name':usersName}

                    }).then(function (response) {

                        console.log(response);
                        //$scope.search_names = response.data;
                        //$rootScope.$broadcast('saved_sphere',response);
                        //console.log(response.data);

                        $scope.users = [];
                        // fetch the response from the backend
                        var data = response.data;
                        // add each item in the response to the declared array
                        for(var i=0; i<data.search_names.length; i++){
                            $scope.users.push(response.data.search_names[i]);
                        }
                        console.log($scope.users);

                    }, function (response) {
                        //console.log(response.data.error);
                    });

                }

                var superviceindex = 0;
                $scope.PosthoverUser = 1;

                $scope.keyboardControl = function(event) {

                    if($scope.users.length>0) {

                        if(event.keyCode==38 || event.keyCode==40 || event.keyCode==13) {
                            event.preventDefault();
                        }

                        switch (event.keyCode) {
                            case 38:
                                superviceindex-=1;

                                if(superviceindex==0){
                                    superviceindex=$scope.users.length;
                                }

                                $scope.PosthoverUser=$scope.users[superviceindex-1];
                                console.log($scope.PosthoverUser)
                                break;
                            case 40:
                                superviceindex+=1;

                                if(superviceindex>$scope.users.length){
                                    superviceindex=0;
                                    superviceindex+=1;
                                }

                                $scope.PosthoverUser=$scope.users[superviceindex-1];
                                console.log($scope.PosthoverUser)

                                break;
                            case 13:
                                console.log("enter broh");
                                break;
                        }
                    }

                }

                // globally declare this array to stop the array being recreated at
                // the  selectUser function initialization
                $scope.selectedUserList = [];

                // selecting a user
                $scope.selectUser = function() {

                    // after a user is selected, clear the text in the textbox
                    $('#post_create_addUser').val('');


                    // limit the authors to 59
                    if($scope.selectedUserList >= 50){
                        // reset the suggestion array and break the function
                        $scope.users = [];
                        return;
                    }

                    // Each time a user is added, check if the user exists in the array
                    // if the user exists, then avoid adding the user, else add the user
                    for (i = 0; i < $scope.selectedUserList.length; i++) {
                        if (angular.equals($scope.selectedUserList[i], this.user)) {
                            // reset the suggestion array and break the function
                            $scope.users = [];
                            return;
                        }
                    }

                    // adding the user to the array
                    $scope.selectedUserList.push(this.user);
                    // reset the users array so it can refil when the user types a name to search agian
                    $scope.users = [];

                } // end of search User function

                // remove a user from the selected list
                $scope.removeList = function(){

                    $scope.selectedUserList.splice(this.$index,1);

                } // end of selected list remove function

//                $scope.selectUser=function(){
//
//                    $scope.search='';
//                    $scope.search_names=null;
//                    if($scope.userList.length > 0){
//                       // console.log($scope.userList.length );
//                        for(var i = 0; i < $scope.userList.length; i++) {
//                            if($scope.userList[i].id == this.user.id){
//                                //console.log($scope.userList[i].id);
//                                //console.log(this.user.id);
//                                $scope.userList.splice(i,1);
//
//                            }
//                        }
//
//                    }
//                    $scope.userList.push(this.user);
//                    $scope.users=[];
//
//                }
//
//                $scope.removeList=function(){
//
//                    $scope.userList.splice(this.$index,1);
//                }
//
//                $scope.selectUser=function(){
//
//                    if($scope.userList>=50){
//                        return;
//                    }
//
//                    if($scope.userList.indexOf(this.user)>-1){
//                        return;
//                    }
//                    $scope.sphere.search=null;
//                    $scope.userList.push(this.user);
//                    $scope.users=[];
//                }
//
//                $scope.searchUser=function(){
//                    if($scope.sphere.search.length>3){
//                        sphereService.SearchMembers(
//                            {keyword:$scope.sphere.search,users:$scope.userList},
//                            function(data){
//                                console.log(data);
//                                $scope.users=data
//                            }
//                        );
//
//                    }else{
//                        if($scope.sphere.search.length==0){
//                            $scope.users=[];
//                        }
//
//                    }
//                }

                $scope.$on('newSphere',function(event,data){
                    //$scope.sphere.search=null;
                    $scope.users=[];
                    $scope.userList=[];
                    popupService.popupAlign($element);
                });

//                $scope.searchUser=function(){
//
//                    loadtemplates.responses({
//                        method: 'POST',
//                        url: 'getUsers',
//                        data:{'searchname':$scope.searchname}
//                    }).then(function (response) {
//
//                       $scope.search_user_result = response.data;
//                        //$rootScope.$broadcast('saved_sphere',response);
//                        console.log(response.data);
//
//                    }, function (response) {
//                        console.log(response.data.error);
//                    });
//
//                }

            }]);

    msg.controller('status_update',
        ['$element','$scope','$rootScope','$http','loadtemplates','fileUpload',
            function($element,$scope,$rootScope,$http,loadtemplates,fileUpload){

                $scope.page_status = 1;
                $scope.rootid = $rootScope.ah.id;
                $scope.sphere_status_comments=[];
                $scope.status_load = 0;

                //console.log($scope.rootid);
                //console.log($rootScope);

                    loadtemplates.responses({
                        method: 'GET',
                        url: 'getStatusData',
                        data:{'sphereid':$('#sphereid').val(),'adminid':$('#adminid').val()}
                    }).then(function (response) {

                        console.log(response);

                        if((response.data.all_status_updates == 'null') || (response.data.all_status_updates == '')){
                            $scope.empty = true;
                            $scope.full = false;
                        }else{
                            $scope.empty = false;
                            $scope.full = true;
                            $scope.status_load = response.data.all_status_updates[0].status_load;
                            $scope.sphereid = response.data.all_status_updates[0].sphere_id;
                            $scope.all_status_updates = response.data;

                        }


                        //console.log(response.data);
                        //popupService.popupAlign($element);



                    }, function (response) {
                        //console.log(response.data.error);
                    });

                $scope.$on('status',function(event,data){

                    //popupService.popupAlign($element);
                      //console.log(data.all_status_updates);

                    console.log(data);
                    //console.log(data.all_status_updates[0].first_name);

                    //console.log(data.data.added_status_updates[0].first_name);
                    if($scope.empty == true){
                        $scope.empty = false;
                        $scope.full = true;
                        //$scope.all_status_updates = data.data;
                        $scope.all_status_updates = data;



                        //console.log($scope.all_status_updates);

                    }else{
                        $scope.all_status_updates.all_status_updates.unshift({

                            id:data.all_status_updates[0].id,
                            status_update_id:data.all_status_updates[0].id,
                            first_name:data.all_status_updates[0].first_name,
                            last_name:data.all_status_updates[0].last_name,
                            description:data.all_status_updates[0].description,
                            user_id:data.all_status_updates[0].user_id,
                            profile_image:data.all_status_updates[0].profile_image,
                            status_image:data.all_status_updates[0].status_image,
                            status_file:data.all_status_updates[0].status_file,
                            sphere_id:data.all_status_updates[0].sphere_id,
                            comments:data.all_status_updates[0].comments,
                            sphere_admin_id:data.all_status_updates[0].sphere_admin_id,
                            created_at:data.all_status_updates[0].created_at
                        });


                    }

                    //console.log(data.data.membership);

                });

                $scope.$on('edit_result',function(event,data){

                        //console.log(data);

                    $scope.status_index = data.index;
                    $scope.satusid = data.edit_data.data.edited_status_update[0].id;

                    //console.log($scope.all_status_updates.all_status_updates);

                    //console.log($scope.all_status_updates.all_status_updates[$scope.status_index].description);

                    $scope.all_status_updates.all_status_updates[$scope.status_index].description = data.edit_data.data.edited_status_update[0].description;

                    if(data.edit_data.data.edited_status_update[0].status_image !=null){

                    for(var i = 0; i < data.edit_data.data.edited_status_update[0].status_image.length; i++){
                    $scope.all_status_updates.all_status_updates[$scope.status_index].status_image.push({

                        sphere_admin_id:data.edit_data.data.edited_status_update[0].status_image[i].sphere_admin_id,
                        sphere_id:data.edit_data.data.edited_status_update[0].status_image[i].sphere_id,
                        images:data.edit_data.data.edited_status_update[0].status_image[i].image

                    });
                    }

                    }

                    if(data.edit_data.data.edited_status_update[0].status_file !=null){

                        for(var i = 0; i < data.edit_data.data.edited_status_update[0].status_file.length; i++){
                            $scope.all_status_updates.all_status_updates[$scope.status_index].status_file.push({

                                sphere_admin_id:data.edit_data.data.edited_status_update[0].status_file[i].sphere_admin_id,
                                sphere_id:data.edit_data.data.edited_status_update[0].status_file[i].sphere_id,
                                files:data.edit_data.data.edited_status_update[0].status_file[i].file

                            });
                        }

                    }





                });


                $scope.$on('comment',function(event,data){

                    console.log(data.comment_data);

                    $scope.status_update_index = data.comment_data.index;

                    //console.log($scope.all_status_updates.all_status_updates[$scope.status_update_index].comments);

                    if($scope.all_status_updates.all_status_updates[$scope.status_update_index].comments == 0){
                        //$scope.empty = false;
                        //$scope.full = true;
                        $scope.all_status_updates.all_status_updates[$scope.status_update_index].comments = data.comment_data.data.data.added_status_comment_updates;
                        //console.log(data.comment_data.data.data.added_status_comment_updates);

                    }else{

                    //popupService.popupAlign($element);

                    //console.log(data);

                    //console.log( $scope.status_update_index);
                    //console.log(data.comment_data.data.data.added_status_comment_updates[0].first_name);
                    $scope.all_status_updates.all_status_updates[$scope.status_update_index].comments.push({

                        id:data.comment_data.data.data.added_status_comment_updates[0].id,
                        first_name:data.comment_data.data.data.added_status_comment_updates[0].first_name,
                        comment:data.comment_data.data.data.added_status_comment_updates[0].comment,
                        last_name:data.comment_data.data.data.added_status_comment_updates[0].last_name,
                        //description:data.data.added_status_updates[0].comment,
                        profile_image:data.comment_data.data.data.added_status_comment_updates[0].profile_image,
                        created_at:data.comment_data.data.data.added_status_comment_updates[0].created_at

                    });

                    }

                    //console.log(data.data.membership);

                });

                $scope.getMoreComments = function(statusid,index,page){

                   // $scope.page =  $scope.page + 1;
                    //console.log(index);
                    //console.log(page);

                    loadtemplates.responses({

                        method: 'POST',
                        url: 'loadMoreStatusComment',
                        data:{'statusid':statusid,'page':page}

                    }).then(function (response) {

                      //console.log(response);
                        //console.log(response.data.added_status_comment_updates[0].id);
                        //load_comment_no

                        for(var i = 0; i < response.data.added_status_comment_updates.load_more_comments.length; i++) {

                            $scope.all_status_updates.all_status_updates[index].comments.unshift({

                                    id:response.data.added_status_comment_updates.load_more_comments[i].id,
                                    first_name:response.data.added_status_comment_updates.load_more_comments[i].first_name,
                                    comment:response.data.added_status_comment_updates.load_more_comments[i].comment,
                                    last_name:response.data.added_status_comment_updates.load_more_comments[i].last_name,
                                    //description:data.data.added_status_updates[0].comment,
                                    profile_image:response.data.added_status_comment_updates.load_more_comments[i].profile_image,
                                    created_at:response.data.added_status_comment_updates.load_more_comments[i].created_at

                                }

                            );

                        }

                        $scope.all_status_updates.all_status_updates[index].page = response.data.added_status_comment_updates.page;

                        $scope.all_status_updates.all_status_updates[index].load_comment_no = response.data.added_status_comment_updates.load_comment_no;

                        console.log(response.data.added_status_comment_updates.load_comment_no);
                        console.log($scope.all_status_updates.all_status_updates[index].load_comment_no);

                    }, function (response) {
                        console.log(response.data.error);
                    });

                }


                $scope.getMoreStatus = function(sphereid){

                    $scope.page_status =  $scope.page_status + 1;
                    console.log(sphereid);
                    console.log($scope.page_status);

                    loadtemplates.responses({

                        method: 'POST',
                        url: 'loadMoreStatus',
                        data:{'statusid':$('#sphereid').val(),'page':$scope.page_status}

                    }).then(function (response) {

                        console.log(response);
                        //console.log(response.data.added_status_comment_updates[0].id);
                        $scope.status_load = response.data.all_status_updates[0].status_load;
                        console.log($scope.status_load);


                        for(var i = 0; i < response.data.all_status_updates.length; i++) {

                            $scope.all_status_updates.all_status_updates.unshift({

                                id:response.data.all_status_updates[i].status_update_id,
                                first_name:response.data.all_status_updates[i].first_name,
                                last_name:response.data.all_status_updates[i].last_name,
                                description:response.data.all_status_updates[i].description,
                                user_id:response.data.all_status_updates[i].user_id,
                                profile_image:response.data.all_status_updates[i].profile_image,
                                status_image:response.data.all_status_updates[i].status_image,
                                status_file:response.data.all_status_updates[i].status_file,
                                sphere_id:response.data.all_status_updates[i].sphere_id,
                                sphere_admin_id:response.data.all_status_updates[i].sphere_admin_id,
                                comments:response.data.all_status_updates[i].comments,
                                created_at:response.data.all_status_updates[i].created_at


                            });

                        }

                        if(response.data.all_status_updates.length < 3){
                            $scope.status_load = 1;
                        }

                    }, function (response) {
                        //console.log(response.data.error);
                    });

                }

                $scope.addComment=function(index,status){

                    // jQUERY INJECTED VALIDATION BEGINS HERE
                    // for the following validation the "null-error" CSS class
                    // is added to show the red highlighted textbox

                    // jQUERY automatic Validation for text fields
                    $('#sphere_comment_'+status).trigger('blur');
                    //$('#sphere_edit_description').trigger('blur');

                    if ($('#sphere_comment_'+status).val() == '') {
                        $('#warning-message-sphere-edit').removeClass('hidden');
                        return
                    } else {
                        // hide the error messgae and hide
                        $('#warning-message-sphere-edit').addClass('hidden');

                        // close the modal
                        $('#new-sphere').modal('hide');
                    }


                    console.log(status);
                    //$scope.sta = status;
                    //$scope.sta = '#sphere_comment_'
                   //$scope.com = $scope.sphere_status_comments[index];
                    //console.log($scope.sta);
                       console.log($scope.com);
                    //console.log($scope.sphere_comment);
                    $scope.status_id = status;
                    loadtemplates.responses({

                        method: 'POST',
                        url: 'addStatusComment',
                        data:{'status_update_id':status,'comment':$('#sphere_comment_'+status).val(),'sphereid':$('#sphereid').val()}

                    }).then(function (response) {

                        console.log(response.data);
                        //$scope.status_index = index;
                        //$scope.comment = response;
                        $scope.sphere_status_comments[index] = "";
                        //$scope.sphere_status_comments="";
                        //$('#sphere_comment_'+status) = null;
                        //$scope.sphere_status_comments_+status == "";


                        $scope.comment_data = [];

                        $scope.comment_data['data'] = response;
                        $scope.comment_data['index'] = index;

                        $rootScope.$broadcast('comment',$scope);
                        //$rootScope.$broadcast('commentindex',index);

                        //console.log(response.data);

                    }, function (response) {
                        //console.log(response.data.error);
                    });


                }

                $scope.editSphereStatusUpdate= function(index,statusid,status,status_image,status_file){

                    $scope.edit_status_data = [];

                    $scope.edit_status_data['index'] = index;
                    $scope.edit_status_data['statusid'] = statusid;
                    $scope.edit_status_data['status'] = status;
                    $scope.edit_status_data['status_image'] = status_image;
                    $scope.edit_status_data['status_file'] = status_file;

                    //console.log($scope);

                    $rootScope.$broadcast('status_edit',$scope.edit_status_data);

                }

                $scope.deleteSphereStatusUpdate= function(index,statusid){

                    $scope.edit_status_data = [];

                    $scope.edit_status_data['index'] = index;
                    $scope.edit_status_data['statusid'] = statusid;

                    //console.log($scope);

                    $rootScope.$broadcast('status_delete',$scope.edit_status_data);

                }

                $scope.$on('delete_status_success',function(event,data){

                    //console.log(data);

                    $scope.all_status_updates.all_status_updates.splice(data,1);

                });

                $scope.editComment = function(index,comment_id,comment,status_index){


                    $scope.edit_status_comment = [];

                    $scope.edit_status_comment['index'] = index;
                    $scope.edit_status_comment['commentid'] = comment_id;
                    $scope.edit_status_comment['comment'] = comment;
                    $scope.edit_status_comment['statusid'] = status_index;
                    $rootScope.$broadcast('comment_edit',$scope.edit_status_comment);

                }

                $scope.deleteComment = function(index,commentid,status_index){

                    $scope.delete_status_comment = [];

                    $scope.delete_status_comment['index'] = index;
                    $scope.delete_status_comment['commentid'] = commentid;
                    $scope.delete_status_comment['statusid'] = status_index;

                    //console.log($scope.delete_status_comment);
                    //console.log($scope.all_status_updates.all_status_updates[statusid].comments[0].comment);

                    //$scope.all_status_updates.all_status_updates[0].comments.splice(index,1);
                    $rootScope.$broadcast('comment_delete',$scope.delete_status_comment);

                }

                $scope.$on('delete_comment_success',function(event,data){

                    //console.log(data);
                    $scope.comment_status_id = data.statusid;
                    $scope.comment_index = data.index;

                    //console.log($scope.comment_status_id);
                    //console.log($scope.comment_index);


                    $scope.all_status_updates.all_status_updates[$scope.comment_status_id].comments.splice($scope.comment_index,1);




                });

                $scope.$on('edit_comment_result',function(event,data){

                    //console.log(data);
                    $scope.comment_status_index = data.status_index;
                    $scope.comment_index = data.index;
                    $scope.comment = data.edit_comment;

                    //console.log($scope.comment_status_id);
                    //console.log($scope.comment_index);


                    $scope.all_status_updates.all_status_updates[$scope.comment_status_index].comments[$scope.comment_index].comment = $scope.comment;




                });

            }]);

    msg.controller('status_update_add',
        ['$element','$scope','$rootScope','$http','loadtemplates','fileUpload',
            function($element,$scope,$rootScope,$http,loadtemplates,fileUpload){

                $scope.sphereimages = [];
                $scope.spherefiles =  [];

                $scope.upload={

                    success:function(xResponse,status){
                        //console.log(xResponse);
                        $scope.file=xResponse['obj'].filename;
                        $scope.filetype=xResponse['obj'].type;
                        $scope.originalname=xResponse['obj'].original_name;
                        $scope.response = xResponse['obj'];
                        //$scope.display = xResponse['obj'].link;

                        if(xResponse.success){

                            if(xResponse.obj.type=="image"){

                                $scope.sphereimages.push(xResponse.obj);

                            }else if(xResponse.obj.type=="doc"){
                                $scope.spherefiles.push(xResponse.obj);
                            }
                        }

                        //console.log($scope.spherefiles);

                    },
                    error:function(xResponse,status){}

                };

                $scope.removeImage =function(index){

                    $scope.sphereimages.splice(index,1);

                }
                $scope.removeFile =function(index){

                    $scope.spherefiles.splice(index,1);

                }

//                $scope.popupUpload={
//                    success:function(resp,status){
//                        if(resp.success){
//
//                            if(resp.obj.type=="image"){
//
//                                $scope.filespopup.images.push(resp.obj);
//
//                            }else if(resp.obj.type=="doc"){
//                                $scope.filespopup.docs.push(resp.obj);
//                            }
//                        }
//                    },
//                    error:function(resp,status){}
//                };

                $scope.addStatusUpdate= function(){

                    // jQUERY INJECTED VALIDATION BEGINS HERE
                    // for the following validation the "null-error" CSS class
                    // is added to show the red highlighted textbox

                    // jQUERY automatic Validation for text fields
                    $('#comments').trigger('blur');
                    //$('#sphere_edit_description').trigger('blur');

                    if ($('#comments').val() == '') {
                        $('#warning-message-sphere-status').removeClass('hidden');
                        return
                    } else {
                        // hide the error messgae and hide
                        $('#warning-message-sphere-status').addClass('hidden');

                        // close the modal
                        $('#new-sphere').modal('hide');
                    }

                    if($scope.file == null){

                        loadtemplates.responses({
                        method: 'POST',
                        url: 'setSphereStatus',
                        data:{'sphere_id':$('#sphereid').val(),'status':$scope.sphere_status}
                    }).then(function (response) {


                        $scope.sphere_status = "";


                        $rootScope.$broadcast('status',response.data.all_status_updates);
                        //console.log(response.data);

                    }, function (response) {
                        //console.log(response.data.error);
                    });

                }else if($scope.filetype == 'image'){

                        console.log($scope.sphereimages);

                        loadtemplates.responses({

                            method: 'POST',
                            url: 'setSphereStatusImages',
                            data:{'sphere_id':$('#sphereid').val(),'status':$scope.sphere_status,'file_object':$scope.sphereimages}
                        }).then(function (response) {
                            console.log(response);
                            //$scope.saved_sphere = response.data;
                            $scope.sphere_status = "";
                            //$scope.file = null;
                            $scope.sphereimages = [];
                            //console.log($scope.sphereimages);

                            $rootScope.$broadcast('status',response.data.all_status_updates);
                            //$scope.all_images=[];

                            //$scope.all_images[0]= response.data.all_status_updates[0].status_image;

                            //$scope.status_image = $scope.all_images;

                            //console.log(response.data.all_status_updates.status_images);
                           $rootScope.$broadcast('status_image',response.data.all_status_updates.status_images);


                        }, function (response) {
                            //console.log(response.data.error);
                        });
                    }else{

                        loadtemplates.responses({

                            method: 'POST',
                            url: 'setSphereStatusFiles',
                            data:{'sphere_id':$('#sphereid').val(),'status':$scope.sphere_status,'file_object':$scope.spherefiles}
                        }).then(function (response) {

                            //$scope.saved_sphere = response.data;
                            $scope.sphere_status = "";

                            $scope.spherefiles = [];
                            //console.log($scope.spherefiles);
                            $rootScope.$broadcast('status',response.data.all_status_updates);
                            $rootScope.$broadcast('status_files',response.data.all_status_updates.status_files);
                            //console.log(response.data);
                        }, function (response) {
                            //console.log(response.data.error);
                        });


                    }

                }



            }]);
        
         msg.controller('allfiles',
        ['$element','$scope','$rootScope','$http','loadtemplates',
            function($element,$scope,$rootScope,$http,loadtemplates){
                
                loadtemplates.responses({
                        method: 'GET',
                        url: 'getSphereFiles',
                        data:{'sphere_id':$('#sphereid').val(),'status':$scope.sphere_status}
                    }).then(function (response) {

                        if((response.data.all_sphere_files == 'null') || (response.data.all_sphere_files == '')){
                            $scope.empty = true;
                            $scope.full = false;
                        }else{
                            $scope.empty = false;
                            $scope.full = true;
                            $scope.all_files = response.data;
                        }

                    $scope.$on('status_files',function(event,data){

                        //$scope.all_sphere_files = [];
                        //$scope.fist_file = [];

                        //popupService.popupAlign($element);
                        //console.log(data);
                        //console.log(data.data.added_status_updates[0].first_name);
                        if($scope.empty == true){
                            $scope.empty = false;
                            $scope.full = true;


                           // $scope.fist_file = $scope.all_sphere_files;
                            //all_sphere_files = $scope.fist_file
                            //$scope
                            $scope.all_files = data;


                            //console.log($scope.all_files);

                        }else{
                            for(var i = 0; i < $scope.all_files.all_sphere_files.length; i++){
                            $scope.all_files.all_sphere_files.unshift({


                                sphere_admin_id:data.all_sphere_files[i].sphere_admin_id,
                                file:data.all_sphere_files[i].file,
                                sphere_id:data.all_sphere_files[i].sphere_id

//                        last_name:data.data.added_status_updates[0].last_name,
//                        description:data.data.added_status_updates[0].description,
//                        profile_image:data.data.added_status_updates[0].created_at,
//                        created_at:data.data.added_status_updates[0].created_at
                            });
                            }
                        }


                        //console.log(data.data.membership);

                    });

                        //$rootScope.$broadcast('saved_sphere',response);
                        //console.log(response.data);

                    }, function (response) {
                        //console.log(response.data.error);
                    });


  
         }]);
     
          msg.controller('allimages',
        ['$element','$scope','$rootScope','$http','loadtemplates',
            function($element,$scope,$rootScope,$http,loadtemplates){

                $scope.openImageGallery=function(gallery,current){
                    //console.log('test');
                    $scope.tempgall=gallery;
                    $scope.currObj=current;
                    $scope.isOpenGallery=true;
                    $rootScope.$broadcast('gallerydata',$scope);

                }
                
                loadtemplates.responses({
                        method: 'GET',
                        url: 'getSphereImages',
                        data:{'sphere_id':$('#sphereid').val(),'status':$scope.sphere_status}
                    }).then(function (response) {

                    if((response.data.all_sphere_images == 'null') || (response.data.all_sphere_images == '')){
                        $scope.empty = true;
                        $scope.full = false;
                    }else{
                        $scope.empty = false;
                        $scope.full = true;
                        $scope.all_images = response.data;
                        //$rootScope.$broadcast('gallerydata',$scope.all_images);
                        //console.log($scope.all_images);
                    }

                        //$rootScope.$broadcast('saved_sphere',response);
                        //console.log(response.data);

                    }, function (response) {
                        //console.log(response.data.error);
                    });



                $scope.$on('status_image',function(event,data){
                    //popupService.popupAlign($element);
                    //console.log(data);
                    //console.log(data.all_sphere_images[].sphere_admin_id);
                    if($scope.empty == true){
                        $scope.empty = false;
                        $scope.full = true;
                        $scope.all_images = data;
                        //console.log(data);

                    }else{
                        for(var i = 0; i < $scope.all_images.all_sphere_images.length; i++){

                            $scope.all_images.all_sphere_images.unshift({



                                image:data.all_sphere_images[i].image,
                                original_image:data.all_sphere_images[i].original_image,
                                sphere_admin_id:data.all_sphere_images[i].sphere_admin_id,
                                sphere_id:data.all_sphere_images[i].sphere_id

//                        last_name:data.data.added_status_updates[0].last_name,
//                        description:data.data.added_status_updates[0].description,
//                        profile_image:data.data.added_status_updates[0].created_at,
//                        created_at:data.data.added_status_updates[0].created_at
                            });
                        }

                    }

                    //console.log(data.data.membership);

                });

                $scope.loadImageMore = function(){

                    loadtemplates.responses({
                        method: 'GET',
                        url: 'getSphereImages',
                        data:{'sphere_id':$('#sphereid').val(),'status':$scope.sphere_status}
                    }).then(function (response) {

                        for(var i = 0; i < $scope.all_images.all_sphere_images.length; i++){

                            $scope.all_images.all_sphere_images.push({


                                sphere_admin_id:data.all_sphere_images[i].sphere_admin_id,
                                image:data.all_sphere_images[i].image,
                                original_image:data.all_sphere_images[i].original_image,
                                sphere_id:data.all_sphere_images[i].sphere_id

//                        last_name:data.data.added_status_updates[0].last_name,
//                        description:data.data.added_status_updates[0].description,
//                        profile_image:data.data.added_status_updates[0].created_at,
//                        created_at:data.data.added_status_updates[0].created_at
                            });
                        }
                        //$rootScope.$broadcast('saved_sphere',response);
                        //console.log(response.data);

                    }, function (response) {
                        //console.log(response.data.error);
                    });


                }



  
         }]);

    msg.controller('Imagegallery',
        ['$element','$scope','$rootScope','$http','loadtemplates',
            function($element,$scope,$rootScope,$http,loadtemplates){

                //console.log('image_gallery_test');

                $scope.$on('gallerydata',function(event,data){

                    //var IMAGE_WIDTH = 868;

                    //console.log(data);
                    $scope.rest_images = [];

                    $scope.galleryDataImges = data.all_images.all_sphere_images;
                    //console.log($scope.galleryDataImges);
                    $scope.tempgall=data.tempgall;
                    //console.log($scope.tempgall);
                    $scope.currObj=data.currObj;
                    //console.log($scope.currObj);
                   // $scope.active_image = data.all_sphere_images[0];





                });



//                console.log('test');
//
//                loadtemplates.responses({
//                    method: 'GET',
//                    url: 'getSphereImages',
//                    data:{'sphere_id':$('#sphereid').val(),'status':$scope.sphere_status}
//                }).then(function (response) {
//
//                    var IMAGE_WIDTH = 405;
//                    //$scope.IMAGE_LOCATION = "http://rabidgadfly.com/assets/angular/gallery1/";
//
//                    // Retrieve and set data
//                    //DataSource.get("images.json",function(data) {
//                        $scope.galleryData = response.data;
//                        $scope.selected = response.data[0];
//
//                    console.log($scope.galleryData);
//                    //});
//
//                    // Scroll to appropriate position based on image index and width
//                    $scope.scrollTo = function(image,ind) {
//                        $scope.listposition = {left:(IMAGE_WIDTH * ind * -1) + "px"};
//                        $scope.selected = image;
//                    };
//
//                    //$rootScope.$broadcast('saved_sphere',response);
//                    //console.log(response.data);
//
//                }, function (response) {
//                    console.log(response.data.error);
//                });

            }]);
    msg.controller('imageedit',
        ['$element','$scope','$rootScope','$http','fileUpload','loadtemplates',
            function($element,$scope,$rootScope,$http,fileUpload,loadtemplates){


                $scope.$on('image_edit',function(event,data){

                    //console.log(data);

                    $scope.image = data.image;
                    $scope.sphere_id = data.sphere_id;
                    $scope.userid = data.userid;
                    $scope.link = 'https://s3.amazonaws.com/frontlinesl/users/'+$scope.userid+'/spheres/'+$scope.sphere_id+'/images/'+$scope.image

                });


                $scope.upload={
                    success:function(xResponse,status){
                        //console.log(xResponse);

                        $scope.image=xResponse['obj'].filename;
                        $scope.image_object = xResponse['obj'];
                        $scope.image=xResponse['obj'];
                        //$scope.display = xResponse['obj'].link;
                        $scope.link = xResponse['obj'].link;

                    },
                    error:function(xResponse,status){}
                };

                $scope.saveImage= function(){

                    //console.log($scope.image);

                    loadtemplates.responses({
                        method: 'POST',
                        url: 'editSphereImage',
                        data:{'sphere_id':$scope.sphere_id,'image_object':$scope.image_object}
                    }).then(function (response) {

                        $scope.edited_image = response.data.edited_sphere_image[0].image;
                        $rootScope.$broadcast('edited_image',$scope.edited_image);
                        //console.log(response.data);
                        //console.log($scope.edited_image);

                    }, function (response) {
                        //console.log(response.data.error);
                    });

                }

            }]);

 msg.directive('image-loader',function(){
    return{
        link:function(scope,element,attr){
            
        }
    }
 })

})(window);


