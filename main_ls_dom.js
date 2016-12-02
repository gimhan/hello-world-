
(function(window){

  var msg=angular.module('learersocial',
  ['ngAnimate','ngSanitize','angularMoment','Lscalander','ngFileUpload']); //,'ngMaterial'

  msg.config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');


  });


//######### FACTORIES #########//
msg.factory('$exceptionHandler', function($log) {
 return function(exception, cause) {
  exception.message += ' (caused by "' + cause + '")';
  $log.info(exception);
};
});

msg.service('abstract',function(){

  this.beforeLoad=function(){}

  this.finishLoad=function(){}

  this.logOut=function(){}

  this.serverError=function(){}

});


msg.directive('autoHeight',function(){
  return{
    restrict:'A',
     require: 'ngModel',

    link:function($scope,element,attr,ngModel){
          var originlaHeight=element.css('height');

            //console.log(ngModel.$modelValue)
           
        $scope.$watch(function(){
            return ngModel.$viewValue
        },function(newVal,oldVal){

      
            if(ngModel.$viewValue==null ||ngModel.$viewValue==undefined || ngModel.$viewValue.trim().length<1){
               element.removeAttr('style')
            }
        })
           element.css('overflow','hidden')
          element.bind('keyup keydown blur change focus',function(){
            
             var offset = element[0].offsetHeight - element[0].clientHeight;      
             if(element.attr('auto-height')!=''){
              var max_height = parseInt(element.attr('auto-height'));
                 
                if(Math.min(max_height, element[0].scrollHeight + offset)>=max_height){
                  
                  element.css('overflow','auto')
                }else{
                  element.css('overflow','hidden')
                }
                
                element.css('height','auto')
                .css('height', Math.min(max_height, element[0].scrollHeight + offset)+'px');
             }else{
                element.css('overflow','hidden')
                element.css('height','auto')
                .css('height',(element[0].scrollHeight + offset)+'px');
             }
          })
    },

    controller:function($scope,$element){

    }


  }
})




msg.service('mainPath',function($rootScope){

  var imgpath='http://learnersocial.io/images/';
$rootScope.doctypes={
 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':imgpath+'word.png',
 'image/jpeg':imgpath+'no-image.png',
 'image/gif':imgpath+'no-image.png',
 'image/tiff':imgpath+'no-image.png',
 'image/png':imgpath+'no-image.png',
 'application/vnd.ms-word':imgpath+'word.png',
 'application/msword':imgpath+'word.png',
 'application/pdf':imgpath+'pdf.png',
 'application/vnd.ms-excel':imgpath+'xl.png',
 'application/vnd.openxmlformats-officedocument.spreadsheetml':imgpath+'xl.png',
 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':imgpath+'xl.png',
 'application/vnd.ms-powerpoint':imgpath+'ppt.png',
 'application/vnd.openxmlformats-officedocument.presentationml.presentation':imgpath+'ppt.png',
 'application/vnd.openxmlformats-officedocument.presentationml':imgpath+'ppt.png',
 'text/plain':imgpath+'txt.png',
 'application/x-7z-compressed':imgpath+'zip.png',
 'inode/x-empty':imgpath+'unkownfile.png',
 'application/x-tar':imgpath+'zip.png',
 'application/x-rar-compressed':imgpath+'zip.png'
}
//this.path= window.location.protocol+'//'+window.location.hostname+'/';
this.path= window.location.protocol+'//'+window.location.hostname+'/learnersocialv1/public/';
 //this.spath=window.location.protocol+"//"+window.location.hostname+":8080";
 this.spath=window.location.protocol+"//"+window.location.hostname+":3000";
})

msg.service('sphereRequestService',['loadtemplates',function(loadtemplates){
  this.Accept=function(sphereid,success,error){
    loadtemplates.responses({
      url:'sphere-invitaion-accept',
      method:'POST',
      data:{'sphere':sphereid}
    }).then(
    function(resp){
      success(resp.data)
    },
    function(resp){
      error(resp.data)
    }
    );
  }

  this.Reject=function(sphereid,success,error){
   loadtemplates.responses({
    url:'sphere-invitaion-reject',
    method:'POST',
    data:{'sphere':sphereid}
  }).then(
  function(resp){
    success(resp.data)
  },
  function(resp){
    error(resp.data)
  }
  );
}
}])



msg.service('awsService',['loadtemplates',function(loadtemplates){
    

    this.getType=function(contentType){
     
     try{
      var header={
         'application/vnd.openxmlformats-officedocument.wordprocessingml.document':'word.png',
         'image/jpeg':'no-image.png',
         'image/gif':'no-image.png',
         'image/tiff':'no-image.png',
         'image/png':'no-image.png',
         'application/vnd.ms-word':'word.png',
         'application/pdf':'pdf.png',
         'application/vnd.ms-excel':'xl.png',
         'application/vnd.openxmlformats-officedocument.spreadsheetml':'xl.png',
         'application/vnd.ms-powerpoint':'ppt.png',
         'application/vnd.openxmlformats-officedocument.presentationml':'ppt.png',
         'text/plain':'txt.png',
         'application/x-7z-compressed':'zip.png',
         'inode/x-empty':'unkownfile.png',
         'application/x-tar':'zip.png',
         'application/x-rar-compressed':'zip.png'
         };

      return header[contentType];
     }catch(ex){
       return 'unkownfile.png';
     }

    }

   this.getObject=function(key,success,error){
      loadtemplates.responses({
        url:'file-get-object',
        method:'post',
        data:{key:key}
      }).then(
        function(resp){
           if(resp.status){
          
             success(resp);
           }else{
             error(resp.error);
           }
        },
        function(resp){
          error(resp.error);
        }
      )
   }
   
   this.putObject=function(buketkey,file,success,error,param){
       
       var reqlink='file-put-object';
       if(buketkey==null || buketkey== undefined){
        return;
       }
       if(file==null || file==undefined){
         return;
       }
      
        if(param!=undefined){
          reqlink='file-put-object/'+param;
        }
          loadtemplates.responses({
           method : 'post',
           url    : reqlink,
           data   : {key:buketkey,file:file}
          }).then(
            function(response){
              success(response,file);
           },function(response){
              error(response,file);
           });

  

      
      
   }

   this.removeObject=function(key,files){

   }

}]);

msg.service('messageService',['loadtemplates','awsService',function(loadtemplates,awsService){
  
  var i=0;
  var msgObj={

    text:null,
    to:null,
    files:[]
  };
 this.onsSend=false;
 this.send_message=function(_to,_message,_files,success,error){


  self.onsSend=true;
  var fileCount=0

   if(_files!==undefined && _files!==null){fileCount=_files.length;}else{_files=[]}
  

        msgObj.text         =_message;
        msgObj.to           =_to;
        msgObj.files        =_files;
 
       flush(msgObj,success,error);
     
 

  
 }



  var flush=function(message,success,error){
    var self=this;
    loadtemplates.responses({
      url : 'message/send',
      method :'POST',
      data :{
        message : message
      }
    }).then(
      function(response){
      
        success(response.data);
        
      },
      function(response){
     
        error(response);

      }
    );
  }

}]);

msg.service('requestService',['loadtemplates',function(loadtemplates){

 this.acceptRequest=function(data,success,error){    

   loadtemplates.responses({
    method:'POST',
    url:'accept-request',
    data:{'user':data}
  }).then(
  function(response){
   success(response.data);
 },
 function(response){
  error(response.data)
}
);
} 


this.rejectRequest=function(data,success,error){

 loadtemplates.responses({
  method:'POST',
  url:'reject-friend-request',
  data:{'request':data}
}).then(
function(response){
 success(response.data);
},
function(response){
 error(response.data)
}
);

}

this.sendRequest=function(data,success,error){

 loadtemplates.responses({
  method:'POST',
  url:'send-request',
  data:{'user':data}
}).then(
function(response){
 
 success(response.data);

},
function(response){
 error(response.data);
}
);

}

this.cancelRequest=function(data,success,cancel){

  loadtemplates.responses({
    method:'POST',
    url:'cancel_friend_request',
    data:{'user':data}
  }).then(
  function(resp){
   success(resp.data)
 },

 function(resp){
  error(resp.data)
}
);
}

this.blockUser=function(data,success,cancel){
  loadtemplates.responses({
    method:'POST',
    url:'block-user',
    data:{'user':data}
  }).then(
  function(resp){
   success(resp.data)
 },

 function(resp){
  error(resp.data)
}
);
}

this.unblockUser=function(data,success,cancel){
  loadtemplates.responses({
    method:'POST',
    url:'un-block-user',
    data:{'user':data}
  }).then(
  function(resp){
    success(resp.data);
  },
  function(resp){
    error(resp.data);
  }
  );
}

this.disconnect=function(data,success,cancel){
  loadtemplates.responses({
    method : 'GET',
    url:'user-connection-remove/' +data, 
  })
  .then(
    function(response){
      success(response)
     
    },
    
    function (resp){
      cancel(resp.data);
    }
  )  
}

}]);



msg.factory('loadtemplates',['$http','mainPath',function($http,mainPath){

  var defa={
    method:'GET',
    url:null,
    data:null
  },
  getobj;


  return {

   responses:function(obj){
     getobj=angular.extend({},defa, obj);
     
     try{
       if(getobj.method.toUpperCase()==="GET"){
        return $http({
          method: getobj.method,
          url: mainPath.path+getobj.url,
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept':'*/*',
            'Content-type':'application/x-www-form-urlencoded;charset=utf-8'
          },
          params:getobj.data,
          timeout:4000
        }); 
      }else if(getobj.method.toUpperCase()==="POST"){
       return $http({
        method: getobj.method,
        url: mainPath.path+getobj.url,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept':'*/*',
          'Content-type':'application/x-www-form-urlencoded;charset=utf-8'
        },
        data:$.param(getobj.data),
        timeout:4000

      }); 
     }
   }catch(exception){
        //console.log("Error")
      }
      
    }

  };
}]);

msg.service('popupService',function(){

  this.popupAlign=function(elem){

    if(elem.css('display')!=='none'){
      return;
    }

    var winheight=$(document).height();
    elem.height(winheight)
    elem.css({
      'position':'absolute',
      'top':'0',
      'left':'0'
    });
    elem.show();
    var popuph=elem.children('.popup-main');
    popuph.offset({top:($(document).scrollTop()+(window.innerHeight-popuph.height())/2)});
    $(document).scroll(function(){
      elem.height($(document).height());
     popuph.offset({top:($(document).scrollTop()+(window.innerHeight-popuph.height())/2)});
   });
    elem.find('.close-btn').click(function(){
      elem.hide();
    })
    }
});

msg.factory('scoket',['$rootScope','loadtemplates','mainPath',function($rootScope,loadtemplates,mainPath){
  var socket=null;
  var client={id:null,Auth:$('#auth').val()};

     console.log()
  
 try{


   socket = io.connect(mainPath.spath);

   socket.on('connect',function(data){

      client.id=socket.id;
      socket.emit('client-ready',client);

   });

       

   socket.on('message.windowupdate',function(data){


    $rootScope.$broadcast('messageWindowUpadte',data);
  });

   socket.on('friend.request',function(data){
     
    
    $rootScope.$broadcast('friendRequest',data);

  });

   socket.on('ball.update',function(data){

    $rootScope.$broadcast('ballUpdate',data);

  });

   socket.on('user.leave',function(data){

     $rootScope.$broadcast('userLeave',data);

   });

   socket.on('friend.status',function(data){

     $rootScope.$broadcast('friendStatus',data);

   });

   socket.on('sphere.countSphere',function(data){

     $rootScope.$broadcast('createSphere',data);

   });

   socket.on('sphere.messageSend',function(data){

     $rootScope.$broadcast('SphereMessage',data);

   });

   socket.on('message.acceptFriendRequest',function(data){

    $rootScope.$broadcast('acceptFriendRequest',data);

  });

  socket.on('post.currentsession',function(data){
 
   $rootScope.$broadcast('postcurrentsession',data);
      
 });

  socket.on('friendrequest.count',function(data){

       $rootScope.$broadcast('friendCount',data);

   });


//  socket.on('sphere.statusupdate',function(event,data){
//      //$rootScope.notifications.sphere+=1;
//      var sphere_count = data;
//      $rootScope.notifications.sphere = data;


  socket.on('sphere.statusupdate',function(data){

      $rootScope.$broadcast('sphereStatusupdate',data);

  });

     socket.on('post.notificationUpdate',function(data){
         
         $rootScope.$broadcast('postNotifyUpdate',data);

     });

     socket.on('post.notificationComment',function(data){
         console.log(data);
         $rootScope.$broadcast('wallNotifyUpdate',data);

     });

 }catch (err){
  console.log(err);
}

return socket;
}]);


msg.directive('pressEnter',function () {
  return {
    require: "ngModel",
    link:function (scope, element, attrs, ngModel) {
    element.bind("keydown keypress", function (event) {
      
      if(event.shiftKey && event.which === 13){

      }else if(event.which === 13 && !event.shiftKey) {
        
        scope.$apply(function (){
          scope.$eval(attrs.pressEnter);
        });

        event.preventDefault();
      }
    });
  }
}
});

msg.directive('messagePopup',function(loadtemplates,messageService){
  return{
    scope:{
      userLord:'=messagePopup'
    },

    templateUrl:'messagePopup.html',

    transclude: true,
    controller:function($scope,$element){
      $scope.sendTo=null;
      $scope.$watch('userLord',function(newVal, oldVal){
          $scope.sendTo=newVal;
      },true);

      $scope.model=$element.find('.modal');
      
      $element.click(function(event){
        
        var css=$scope.model.css('display');
       
        if(css==='none'){

          $scope.$apply(function(){
           $scope.popupMessage="";
           $scope.recepiantkey="";
            if($scope.userLord==null){
              $scope.sendTo=null;
            }
      
            $scope.showRecepiant=false;
            $scope.files=[];
            $scope.model.modal('show');
          })
        }
          
      })

      

      $scope.popupMessage="";
      $scope.files=[];
      $scope.popuppending=false;
      $scope.errorMessage="";



      $scope.closeModel=function(){
        var css=$scope.model.css('display');

          if(css==='block'){

            $scope.popupMessage="";
            $scope.recepiantkey="";
            $scope.files=[];
  
            $scope.recepiants=[];
            $scope.model.modal('hide')
          }
       }

      $scope.sendMessagepopup=function(){

          $scope.errorMessage="";
        if($scope.sendTo==null){
           $scope.errorMessage="Recipiant is mandatory! "
          return;
        }

        if($scope.popupMessage=="" && $scope.files.length<1){
          $scope.errorMessage="You are sending an empty message.";
          return;
        }

       messageService.send_message(
         $scope.sendTo.id,
         $scope.popupMessage,
         $scope.files,
         function(response){

           $scope.sendTo=$scope.userLord;
           $scope.popupMessage="";
           $scope.files=[];
           $scope.errorMessage="";
           $scope.model.modal('hide')
         },
         function(error){
            $scope.errorMessage=" Something went wrong !";
         }
        );
      }



      $scope.loadRecepiant=function(){
          
        //if(event.keyCode==13  ||event.keyCode==37 || event.keyCode==38 || event.keyCode==39 || event.keyCode==40){
         // return;
        //}

  

        if($scope.recepiantkey==""||$scope.recepiantkey==undefined ||$scope.recepiantkey==null){
          $scope.recepiants=[];
        }

        if($scope.recepiantkey.length<1){  
          return;
         }

         superviceindex=0;  

        loadtemplates.responses({
          url:'message/search/firend/'+$scope.recepiantkey.trim().split(' ').join('+')
        })
        .then(
          function(response){
            
            $scope.recepiants=response.data.users;
            if($scope.recepiants.length>0){
              $scope.hoverUser=$scope.recepiants[0]
              $scope.showRecepiant=true;
            }
          },
          function(response){}
          )
      }

      var fileLength=0;
      $scope.popupUpload={
         key:'messages',
         befor:function(files){
           $scope.popuppending=true;
           fileLength=files.length;
       
         },
         success:function(resp,status){

            fileLength-=1;
           if(resp.success){

             if(resp.obj.type=="image"){

               $scope.files.push(resp.obj);
               
            
             }else if(resp.obj.type=="doc"){
               $scope.files.push(resp.obj);
               
             }
           }

           if(fileLength <=0){
            fileLength=0;
            $scope.popuppending=false;
          }
        },
        error:function(resp,status){
          fileLength-=1;
          if(fileLength <=0){
            fileLength=0;
            $scope.popuppending=false;
          }
        }
      };

      $scope.remove=function(inde){
        var self=this;
        var obj=null;
        if(self.image!==undefined){
         obj=self.image
        }

        if(self.doc!==undefined){
          obj=self.doc
        }
        
        loadtemplates.responses({
            url:'file-delete-object',
            method:'POST',
            data:{file:obj,'key':'messages'}
        })
        .then(
           function(response){
            if(response.status){      
              $scope.files.splice(inde,1); 
            }
           },
           function(response){}
        )
      }

      $scope.hoverUser=null;
      var superviceindex=0;

      $scope.textmessagefocus=false;

      $scope.listnav=function(event){

          
          if($scope.showRecepiant){
            if(event.keyCode==38 || event.keyCode==40 || event.keyCode==13){
              event.preventDefault(); 
            }
              
              switch (event.keyCode){
                case 38:
                  superviceindex+=1;
                   
                   if(superviceindex>=$scope.recepiants.length){
                     superviceindex=0;
                   }
                   if(superviceindex<0){
                     superviceindex=$scope.recepiants.length-1;
                   }
                  $scope.hoverUser=$scope.recepiants[superviceindex];
                  break;
                case 40:
                   superviceindex-=1;
                     
                   if(superviceindex<0){
                     superviceindex=$scope.recepiants.length-1;
                   }
                  
                   if(superviceindex>=$scope.recepiants.length){
                     superviceindex=0;
                   }
                  $scope.hoverUser=$scope.recepiants[superviceindex];
                  break;
                case 13:
                 if($scope.hoverUser!=null){
                   if(!$scope.textmessagefocus){

                     $scope.showRecepiant = false;
                     $scope.sendTo        = $scope.hoverUser.user;
                     superviceindex==0;
                     $scope.textmessagefocus=true;
                  
                   }
                  
                 }
                 break;
              }
          }

       
      }


      $scope.userhover=function(ind){
        $scope.hoverUser=$scope.recepiants[ind];
      }

      $scope.selectRecipent=function(user){
       superviceindex==0
       $scope.showRecepiant=false;
       $scope.sendTo=user.user;
       $scope.textmessagefocus=true;
      }
      
      $scope.removeUser=function(){
        $scope.sendTo=null;
        $scope.recepiantkey="";
        $scope.textmessagefocus=false;
      }
    },

 
  }
});

msg.directive('focusMe',function($parse){
  return{


    link:function(scope,element,attr){
        
        scope.innt=false;
      var model = $parse(attr.focusMe);

      scope.$watch(model, function (value) {
         scope.innt=value
        if(scope.innt){
            element.focus();
          }else{
            element.blur();
          }
      })
        
    },


  }
})

msg.directive('backImage',function($compile){
  return {

   link:function(scope, element, attr){

    attr.$observe('backImage',function(value){

      element.css('background-image','url('+value+')');

    });
  },
  controller:function($scope, $element){

  }

}
});

msg.directive('viewFile',
  function($rootScope,$http,mainPath,$compile){
   return{
    scope:{
      files:"=viewFile"
    },
    compile: function compile(tElement, tAttrs, transclude) {
     return {
       pre: function preLink(scope, iElement, iAttrs, controller) { 
                   //console.log(iAttrs)
                 },
                 post: function postLink(scope, iElement, iAttrs, controller) {
                   //console.log(iElement)
                 }
               }
      // or
      // return function postLink( ... ) { ... }
    },
    controller:function($scope,$element){
     $element.click(function(){
      $scope.$apply(function(){

        var url=$element.css('background-image').match(/url\(([^)]+)\)/i)[1];
        $rootScope.$broadcast('openProjector',$scope.files);

      });
    })
   }
 }
});




msg.controller('projectorController',
  ['$rootScope','$http','mainPath','$compile','$scope','$element',
  function($rootScope,$http,mainPath,$compile,$scope,$element){

   $scope.close=function(){
     $element.hide();
   }



   $scope.totla=[];
   $scope.files=null;
   $scope.currentFile=null;
   $scope.totalFiles=0;

   $scope.selectImage=function(){

     $scope.currentFile=this.fi;
   }

   $scope.$on('openProjector',function(event,data){
    $scope.totla=[]
    $scope.files=null;
    $scope.currentFile=null;
    $scope.totalFiles=null;



    for(var i=0; i<data[0].length; i++){
      $scope.totla.push(i)
    }

    $scope.files=data[0];
    $scope.currentFile=data[2];
    $scope.totalFiles=data[1];

    $('#myCarousel').carousel();

  });


 }]);

msg.directive('sponserAdd',function($rootScope,$http,mainPath,$compile){
  return{
    restrict:'A',
    templateUrl:'sponsers.html',

    controller:function($scope,$element){

      $(document).scroll(function(){
       var elem=$('#requestPanel3534frst')
       var totl=elem.offset().top+$(document).scrollTop();
       if($(document).scrollTop()>elem.height()){
         $element.css({
           'position':'absolute',
           'top':$(document).scrollTop()+'px'
         });
       }else{
        $element.removeAttr('style')
      }


    });
      

    }
  }
});

msg.directive('showMore',function($rootScope,$http,mainPath,$compile){
  return{
    transclude: true,
    scope: {
      showMore:'=',
      limit:'=limit'
    },

    link:function(scope, elem, attr, ctrl, transclude){
      scope.swotxt="Read more...";
      scope.isexpand=true;
      scope.less="";
      scope.more="";
      scope.$watch('showMore',function(newval,oldval){

 
          
        elem.empty();

        if(scope.limit==undefined || isNaN(scope.limit)){
          scope.limit=170;

        }
        if(scope.showMore==undefined){
          return;
        }
        
       scope.less=scope.showMore.replace(/\n/g, '<br>');
        var content='<span ng-bind-html="less"></span>';
       
       if(scope.showMore.length>scope.limit){

         if(scope.showMore[scope.limit]=='\\' && scope.showMore[scope.limit+1]==="\\n"){
           scope.limit+=2;
         }

         scope.isexpand=false;
         scope.less=scope.showMore.toString().substring(0,scope.limit);
         
         scope.less=scope.less.replace(/\n/g, '<br>');

         scope.more=scope.showMore.toString().substring(scope.limit+1,scope.showMore.length);
         scope.more=scope.more.replace(/\n/g, '<br>');
         
         content+='<span ng-show="!isexpand">... </span>';
         content+='<span class="showmoreClass" ng-bind-html="more" style="padding-left:1px;" ng-show="isexpand"></span>';
         content+='<a href="" class="read-more-less"ng-click="expand()"><% swotxt %></a>'
       }else if(scope.showMore.split(/\r\n|\r|\n/).length>2){

          scope.isexpand=false;
               
          var totline=scope.showMore.split(/\r\n|\r|\n/);
          scope.less=totline[0]+'<br/>'+totline[1];
          
          
          for(var i=2;i<totline.length;i++){
            scope.more+=('<br/>'+totline[i]);
          }
          

          content+='<span ng-show="!isexpand">... </span>';
         content+='<span class="showmoreClass" ng-bind-html="more" style="padding-left:1px;" ng-show="isexpand"></span>';
         content+='<br/><a href="" class="read-more-less"ng-click="expand()"><% swotxt %></a>'
       }

       elem.append($compile(content)(scope));

      
    });

    scope.expand=function(){
        
      if(!scope.isexpand){
        scope.swotxt=" Read less...";            
        scope.isexpand=true;
      }else{
        scope.swotxt="Read more...";
        scope.isexpand=false;
      }
    }

      
    },
  }
});

msg.directive('lsRating',function($compile){
   var re='<a><img src="http://learnersocial.io/newui/img/clear-rating.png" ng-click="onRate(0)"></a>';
          re+='<span ng-mouseleave="lowRate()" class="rateStar">';
          re+='<a ng-repeat="star in starBucket[tempval] track by $index">';
          re+='<img ng-src="http://learnersocial.io/newui/img/<%star%>" ng-mouseover="incRate($index)"  ng-click="onRate($index+1)"></a>';
          re+='</span><div class="rateit" data-rateit-backingfld="#backing2c" data-rateit-min="0"></div>';
  return{
    scope:{
      rate:'=lsRating',
      onrate:'=onrate',
    },
    
    compile:function(elem,attr){
      return{
        pre:function(scope,elements,attrs){},
        post:function(scope,elements,attrs){}
      }
    },

    template:re,

    controller:function($scope,$element,$attrs){
        $scope.starBucket={
          0:['gray-star.png','gray-star.png','gray-star.png','gray-star.png','gray-star.png'],
          1:['green-star.png','gray-star.png','gray-star.png','gray-star.png','gray-star.png'],
          2:['green-star.png','green-star.png','gray-star.png','gray-star.png','gray-star.png'],
          3:['green-star.png','green-star.png','green-star.png','gray-star.png','gray-star.png'],
          4:['green-star.png','green-star.png','green-star.png','green-star.png','gray-star.png'],
          5:['green-star.png','green-star.png','green-star.png','green-star.png','green-star.png']
        }
        $scope.tempval=0;
        $scope.$watch('rate',function(newval,oldval){
       
          if($scope.rate!=$scope.tempval){
           
            $scope.tempval=$scope.rate
            
          }
            
        });
        
       

        $scope.incRate=function(ind){
          
          $scope.tempval=ind+1
        }
        $scope.onRate=function(ind){
          
          $scope.onrate.fire(ind);  
          $scope.tempval=ind       
        
        }
        $scope.lowRate=function(){
          $scope.tempval=$scope.rate;
          
        }
    }
  }
});



msg.directive('htmlLinks',function(loadtemplates,$compile){
  return {
    scope:{
      contains:'=htmlLinks'
    },
    compile:function(elem,attr){

      return{
        pre:function(scope,elements,attrs){
     
        },
        post:function(scope,elements,attrs){

        }
      }

    },
    controller:function($scope,$element){

      var pattern = /(http:\/\/|ftp:\/\/|https:\/\/|(www\.))([\da-z-A-Z]{2,})+([\.][a-zA-Z]{2,})*(\/|\?)?([^\s]*)/g;
       if($scope.contains==undefined||$scope.contains==null||$scope.contains.trim()=="")return;
           $element.append($scope.contains);
        if(!pattern.test($scope.contains))return;
           
           var results=$scope.contains.match(pattern)
      
            for(var i in results){
             loadtemplates.responses({
               url:'domain-composer',
               data:{param:results[i]}
              })
              .then(
                function(response){
          
                 var x='<a href="'+response.data.url+'">';
                     x+='<img src="'+response.data.icon+'" class="mCS_img_loaded"/>' ;
                    x+= response.data.host;
                     x+='</a>';
     
                 $element[0].innerHTML=$element[0].innerHTML.replace(response.data.origin,x)
                 
                 
 
                },
                function(response){}
               )
            }



    }
  }
})
msg.directive('lsPrivacy',function($rootScope,$http,$compile){
  return{
    replace:true,
    scope: {
      lsPrivacy:'=',
      onChangePrivacy:'&'
    },

    link:function(scope, elem,attr){



     scope.pri=[
     {icon:'only-me.png',value:1},
     {icon:'connection_1.png',value:2},
     {icon:'public.png',value:3}
     ];

     scope.current=scope.pri[scope.lsPrivacy-1];

     scope.changePrivacy=function(obj){

      scope.current=scope.pri[obj-1];
      scope.lsPrivacy=obj;
      scope.onChangePrivacy({privacy:obj});
    }




  },

  controller:function($scope,$element){


  },

  templateUrl:function(element, attrs){
    return 'privacy.html';
  }


}
});

msg.directive('exapndarea',function(){
  return{
    restrict: "A",
     scope:{
       maxheigth:'=maxheigth',
       minHeigth:'=minheigth'
     },
     require: "ngModel",
    link:function(scope, element, attrs, ngModel){
         
         element.css({'height':element.outerHeight()});
        if(scope.maxheigth!=undefined){

        }

         var lines=element.val().split(/\n|\r/).length;
         var fontsize=parseInt(element.css('font-size'));
         var lineheight=parseInt(element.css('line-height'));
         var elemh=element.outerHeight(true);

        
        

        element.bind("blur keyup change", function(event) {
             
         var lines=element.val().split(/\n|\r/).length;
         var fontsize=parseInt(element.css('font-size'));
         var lineheight=parseInt(element.css('line-height'));
          var elemh2=element.outerHeight(true);
  
         

        });
    }
  }
})

msg.controller('newMessageController',[
  '$scope',
  'loadtemplates',
  '$rootScope',
  '$element',
  'messageService',
  'fileUpload',
  function($scope,loadtemplates,$rootScope,$element,messageService,fileUpload){


   $scope.list=false;
   $scope.input=true;
   $scope.cont=false;
   $scope.search="";
   $scope.message="";
   $scope.usrSelect=null;
   $scope.files=[];

   $scope.sendNewMsg=function(){
    if($scope.message!="" && $scope.usrSelect!=null){

      messageService.send_message($scope.usrSelect.id,$scope.message,$scope.files,
        function(respons){
         $scope.files=[];
         $scope.message="";
         $rootScope.$broadcast('sendNewmesage',respons);
         $element.hide();
       },
       function(response){
          console.log(response.data.error);
       }
       )



    }

  };

  $scope.loadUsers=function(){

    if($scope.search.length>2){
     loadtemplates.responses({
       url:'message-search-firend',
       data:{keyword:$scope.search}
     }).then(function(response){
      if(response.data.users.length>0){
        $('#message_box_dropdown').dropdown('toggle')
        $scope.list=true;
        $scope.users=response.data.users;
      }


    },function(response){
      console.log(response.data.error)
    })
   }else{
    $scope.list=false;

  }
}

$scope.uploadfile=function(){
    //console.log($element);
    //$('#message-file-upload').click();
    fileUpload.upLoad({
      beforSend:function(obj){

      },
      success:function(data){

        obj={
          filePath:data.path,
          fileName:data.file
        };

        $scope.imgs.push(obj);
        if($scope.imgs.length>0){
        }

        if(!$scope.$$phase) {
          $scope.$digest();          
        }
      },
      errors:function(data){}
    })
  }

  $scope.select=function(obj){


    $scope.usrSelect=obj;

    $scope.list=false;
    $scope.input=false;
    $scope.cont=true;
  }

  $scope.close=function(){

    $scope.usrSelect=null
    $scope.search="";
    $scope.input=true;
    $scope.cont=false;
  }

}]);




})(window);
