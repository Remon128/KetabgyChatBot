<?php

file_put_contents("fb.txt",file_get_contents("php://input"));

$fb = file_get_contents("fb.txt");

$fb = json_decode($fb);

$messageId = $fb->entry[0]->id;
$messageSender = $fb->entry[0]->messaging[0]->sender->id;
$messageContent = $fb->entry[0]->messaging[0]->message->text;
$token = "EAAU3mgjZALxQBADQD49ZAouViu9TJDW8kZBsWWtclBkwBXwiBK5WmIUGxU0aYxtnZCN1cWyFJyeV7sLcIWvBiZC5XOo47iU4Xd3NEuZAPVHTCRWf2HWOpRF7uIzG5PUFp0Mki0lb7gadFac2vf2BRzPWMohZBqlx8fvNF2ug0Vo7gZDZD";
$data = array(
    'recipient'=>array('id'=>"$messageSender"),
    'message'=>array('text'=>"hi there")

);
$options = array(
    'http'=>array(
        'method' =>'POST',
        'content'=>json_encode($data),
        'header'=>"Content-Type: application/json\n"
    )

);
$context = stream_context_create($options);
file_get_contents("https://graph.facebook.com/v2.8/me/messages?access_token=$token",false,$context);
/*
if($messageContent!=null){
file_get_contents("https://graph.facebook.com/v2.8/me/messages?access_token=$token",false,$context);
}
*/
?>