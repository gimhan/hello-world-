<?php

Event::listen(UpdateFriendRequestEventHandler::EVENT, 'UpdateFriendRequestEventHandler');
Event::listen(UpdateBallEventHandler::EVENT, 'UpdateBallEventHandler');
Event::listen(UpdateFriendMessageWindowRequestEventHandler::EVENT, 'UpdateFriendMessageWindowRequestEventHandler');
Event::listen(UpdateUserLeftEventHandler::EVENT, 'UpdateUserLeftEventHandler');
Event::listen(UpdatePostNotificationEventHandler::EVENT, 'UpdatePostNotificationEventHandler');
Event::listen(UpdateCommentNotificationEventHandler::EVENT, 'UpdateCommentNotificationEventHandler');
Event::listen(UpdateRatingPostNotificationEventHandler::EVENT, 'UpdateRatingPostNotificationEventHandler');
Event::listen(UpdateSphereMessageWindowRequestEventHandler::EVENT, 'UpdateSphereMessageWindowRequestEventHandler');
Event::listen(UpdateSphereMessageSendEventHandler::EVENT, 'UpdateSphereMessageSendEventHandler');
Event::listen(UpdateUserstatusEventHandler::EVENT, 'UpdateUserstatusEventHandler');
Event::listen(AcceptFriendRequestEventHandler::EVENT,'AcceptFriendRequestEventHandler');
Event::listen(CreateNewSphereEvent::EVENT,'CreateNewSphereEvent');
Event::listen(AddSphereMemberEvent::EVENT,'AddSphereMemberEvent');
Event::listen(RemoveSphereMemberEvent::EVENT,'RemoveSphereMemberEvent');
Event::listen(SphereStatusEvent::EVENT,'SphereStatusEvent');
Event::listen(PostSessionEventHandler::EVENT,'PostSessionEventHandler');
Event::listen(FriendRequestCountEventHandler::EVENT,'FriendRequestCountEventHandler');
//Event::listen(CountforNotificationsEventHandler::EVENT,'CountforNotificationsEventHandler');



