
var express =   require('express'),
    http =      require('http'),
    server =    http.createServer(app);
 
var app = express();
var cl = {}; 
const redis =   require('redis');
const io =      require('socket.io');
const client =  redis.createClient();
 
server.listen(process.env.PORT||3000, process.env.IP);


io.listen(server).on('connection', function(client) {



    client.on('client-ready',function(data){
        console.log('start');
       data.id=client.id
       cl=addUser(cl,data);

 
    })
    const redisClient = redis.createClient()
    //client.emit("logsing",{user:cl});
    // add all the subscriptions here
    redisClient.subscribe('friend.request');
    redisClient.subscribe('ball.update');
    redisClient.subscribe('message.windowupdate');
    redisClient.subscribe('user.leave');
    redisClient.subscribe('friend.status');
    redisClient.subscribe('message.acceptFriendRequest');
    redisClient.subscribe('sphere.createSphere');
   // redisClient.subscribe('message.userJoin');
    // start post notification
    redisClient.subscribe('post.notificationUpdate');
    // end post notification
    // start post comment notification
    redisClient.subscribe('post.notificationComment');
    // end post comment notification
    // start post rating notification
    redisClient.subscribe('post.notificationRatingUpdate');
    // end post rating notification
    // start spere message
    redisClient.subscribe('sphere.countSphere');
    // end spere message
    redisClient.subscribe('notification.add');
    //end notification count
    //start message count
    redisClient.subscribe('message.count');
    //end message count
    //start message count
    redisClient.subscribe('member.addMember');
    //end message count
    //start message count
    redisClient.subscribe('sphere.removeMember');
    //end message count
    //start message count
    redisClient.subscribe('sphere.statusupdate');
    //end message count
    //start friend request count
    redisClient.subscribe('friendrequest.count');
    //end friend request count
    // start Home notification
    redisClient.subscribe('post.currentsession');
    
   

    redisClient.on("message", function(channel, message) {
       // console.log(channel);


        //console.log(cl);
         var msg=JSON.parse(message);

        if(channel=='sphere.statusupdate'){
           //console.log(channel);
            //console.log(msg.reciver[cl[client.id]]);
            console.log(msg);

//            if(cl[client.id]==msg.reciver){
//
//                client.emit(channel, msg.message);
//            }

            //console.log(cl[client.id]);
            //if(cl[client.id]==msg.reciver[cl[client.id]]){
            for(var i=0; i<msg.reciver.length;i++){

                if(cl[client.id]==msg.reciver[i]){

                    var count = msg.list[cl[client.id]];
                    console.log(count);
                    client.emit(channel, count);
                }
            }


            //}
           // var status = [] ;
        }



        if(channel=='notification.add'){
            var id=cl[client.id];

          
            var users = JSON.parse(message);
            console.log(users);
            var value = users[id];
           
            client.emit(channel, value);
        }

        if(channel=='message.count'){
            if(cl[client.id]==msg.reciver){
              
                client.emit(channel, msg.message);
            }
        }


        if(channel=='sphere.countSphere'){

            console.log(msg);
           //console.log(msg.list[cl[client.id]]);
//            if(cl[client.id]==msg.reciver){
//
//                client.emit(channel, msg.message);
//            }
            for(var i=0; i<msg.reciver.length;i++){
                var count = msg.list[cl[client.id]];
                client.emit(channel, count);
            }

            //console.log(cl[client.id]);
//            if(cl[client.id]==msg.reciver[i]){
//                var count = msg.list[cl[client.id]];
//                client.emit(channel, count);
//            }

        }

        if(channel=='post.currentsession'){

              client.emit(channel, msg.message);
            for(var i = 0; i < msg.reciver.length; i++) {
                if(cl[client.id]==msg.reciver[i]){
                    console.log(cl[client.id])
                    
                }
            }

        }

        if(channel=="message.windowupdate"){
           
        
           for(var i=0; i<msg.reciver.length;i++){
             
             if(cl[client.id]==msg.reciver[i]){
                  
                
                client.emit(channel, msg.message);
             }
           }

         
        }

        if(channel=="friend.request"){
            for(var i=0; i<msg.reciver.length;i++){
             
             if(cl[client.id]==msg.reciver[i]){
                  
                
                client.emit(channel, msg.message);
             }
           }
        }

        if(channel=='message.acceptFriendRequest'){

            console.log(msg);

            if(cl[client.id]==msg.reciver){

                var count = msg.list[cl[client.id]];
                console.log(count);
                client.emit(channel, count);
            }

//            console.log(msg.list);
//            console.log(msg.list[cl[client.id]]);
//
//
//            //console.log(cl[client.id]);
//            if(cl[client.id]==msg.reciver[i]){
//                var count = msg.list[cl[client.id]];
//                client.emit(channel, count);
//            }

        }

        if(channel=='member.addMember'){

        
            var mem = [];

            for(var i = 0; i < msg.reciver.length; i++) {
                if(cl[client.id]==msg.reciver[i]){
                 
                    mem.push(msg.message[0].list[msg.reciver[i]]);
                    mem.push(msg.message[0].description);
                   
                    console.log(mem);
                    client.emit(channel, mem);
                }
            }

        }

        if(channel=='sphere.removeMember'){

        
            var remove = [] ;

            for(var i = 0; i < msg.reciver.length; i++) {
                if(cl[client.id]==msg.reciver[i]){
                   
                    remove['count'] = msg.message[0].list[msg.reciver[i]];
                    remove['des'] = msg.message[0].description;
                    client.emit(channel, remove);
                }
            }

        }

        if(channel=='friendrequest.count'){

            console.log(channel);
            console.log(msg);

            if(cl[client.id]==msg.reciver){

                //var count = msg.list[cl[client.id]];
                var count = msg.list;
                console.log(count);
                client.emit(channel, count);
            }

        }

        if(channel=='post.notificationUpdate'){

            console.log(msg.list);
            console.log(msg.list[cl[client.id]]);


            for(var i=0; i<msg.reciver.length;i++){

                if(cl[client.id]==msg.reciver[i]){

                    var count = msg.list[cl[client.id]];
                    console.log(count);
                    client.emit(channel, count);
                }
            }

            //console.log(cl[client.id]);
            //if(cl[client.id]==msg.reciver[i]){
                //var count = msg.list[cl[client.id]];
                //client.emit(channel, count);
            //}

        }

        if(channel=='post.notificationComment'){


            console.log(msg.list);
            console.log(msg.list[cl[client.id]]);


            //console.log(cl[client.id]);
           // if(cl[client.id]==msg.reciver[i]){
                var count = msg.list[cl[client.id]];
                client.emit(channel, count);
           // }

        }


    });


    client.on('disconnect', function() {
       
        cl=remUser(cl,client.id);

        redisClient.quit();
    });
    
    
    
});

var addUser=function(arra,data){  
    arra[data.id]=data.Auth;
    return arra;
}

var remUser=function(arra,clientid){
 
       delete arra[clientid];
  
    return arra;
}

