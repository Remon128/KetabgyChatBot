<?php
  // Send a raw HTTP header 
  header ('Content-Type: text/html; charset=UTF-8'); 

  // Declare encoding META tag, it causes browser to load the UTF-8 charset 
  // before displaying the page. 
  echo '<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />'; 

  // Right to Left issue 
  echo '<body dir="rtl">';

file_put_contents("fb.txt",file_get_contents("php://input"));

$fb = file_get_contents("fb.txt");

$fb = json_decode($fb);

$messageId = $fb->entry[0]->id;
$messageSender = $fb->entry[0]->messaging[0]->sender->id;
$messageContent = $fb->entry[0]->messaging[0]->message->text;
$token = "EAAZAriZChusqMBAAGZA1VP0uWaNyZCvwtSHkX8BSczvXODhQcwEUROtPEH7miZCjvCduZAQbbNVFtYaSUo9v2Vdt0SYf06cjIZBGiljXjpfz4febs9Llh3GLtx08mgEye6ERYsxAOpdpBSLJkHAK7yotCReAQeNs5FFYo8LimUG9AZDZD";

$link = "https://www.googleapis.com/books/v1/volumes?q=$messageContent&key=AIzaSyDHwutJNmMh9Eqa4i_CkWSbr4jEWqfh7Fc";
$contentLink = file_get_contents($link);
$book = json_decode($contentLink);
$title = $book->items[0]->volumeInfo->title ;
$author = $book->items[0]->volumeInfo->authors[0];
//$img = $book->items[0]->
file_put_contents("api.txt",$contentLink);


if(strpos($messageContent,"are" )!== false)
{
    $meg = "fine and you ?";
}
else if(strpos($messageContent,"hello")!==false)
{
    $meg = "hi friend :D";
}
else if(strpos($messageContent,"bomb")!==false)
{
   $meg = "fuck u :P";
}

$meg = $author ;

$data = array(
    'recipient'=>array('id'=>"$messageSender"),
    'message'=>array('text'=>"$meg")
);

$options = array(
    'http'=>array(
        'method' =>'POST',
        'content'=>json_encode($data),
        'header'=>"Content-Type: application/json\n"
    )

);
$context = stream_context_create($options);
if($messageContent!=null){
file_get_contents("https://graph.facebook.com/v2.8/me/messages?access_token=$token",false,$context);
}

?>