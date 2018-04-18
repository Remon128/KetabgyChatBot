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

 
$messageId = $fb->id;
$messageSender = $fb->originalRequest->data->sender->id;
$messageContent = $fb->result->parameters->Book_category;
//file_put_contents("api.txt",$messageSender);
      
$token ="EAAGFCwhT4igBAJupRQsiZAZCTew9ZCJx198vaZAZCKbKTBZA782g8AFdExwqTOZC7b8MgCbjeNiiLFGEAt9qF7nfCHGiDOTzaAecx2JhnBZBarjWf4ybdCt8hPvUpVRUBMs0lDvPCYgqP0UZAaQd5YlTlO7BnmeuNdCZCkp2VLaYJZBVAZDZD";
 
$link = "https://www.googleapis.com/books/v1/volumes?q=$messageContent&key=AIzaSyDHwutJNmMh9Eqa4i_CkWSbr4jEWqfh7Fc&maxResults=40";
//$link = "https://www.googleapis.com/books/v1/volumes?q=$messageContent&key=AIzaSyDHwutJNmMh9Eqa4i_CkWSbr4jEWqfh7Fc";
$contentLink = file_get_contents($link);
$book = json_decode($contentLink);
$title = $book->items[0]->volumeInfo->title;
$author = $book->items[0]->volumeInfo->authors[0];
$desc = $book->items[0]->volumeInfo->description;
$img = $book->items[0]->volumeInfo->imageLinks->smallThumbnail;
$pages = $book->items[0]->volumeInfo->pageCount;

 
    $data_title = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('text'=>"Title : "."$title")
    );

    $data_author = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('text'=>"Author is :"."$author")
    );

    $data_desc = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('text'=>"Description :"."$desc")
    );

    $data_image = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('attachment'=>array('type'=>"image",'payload'=>array('url'=>"$img" ,'is_reusable'=>true )))
    );

    $data_pages = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('text'=>"$pages"." Pages")
    );

    $Book_data_array = array($data_title , $data_author , $data_desc , $data_image , $data_pages);


  for($i=0; $i<5; $i++){

    $options = array(
        'http'=>array(
            'method' =>'POST',
            'content'=>json_encode($Book_data_array[$i]),
            'header'=>"Content-Type: application/json\n"
        )
    
    );

    $context = stream_context_create($options);
    if($messageContent!=null){
    file_get_contents("https://graph.facebook.com/v2.8/me/messages?access_token=$token",false,$context);
    }

  }


    //"data": {"facebook": {<Hello facebook>}}
 
?>