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
$user_Input = $fb->originalRequest->data->message->text;
//file_put_contents("api.txt",$messageSender);
      
$token ="EAAGFCwhT4igBAJupRQsiZAZCTew9ZCJx198vaZAZCKbKTBZA782g8AFdExwqTOZC7b8MgCbjeNiiLFGEAt9qF7nfCHGiDOTzaAecx2JhnBZBarjWf4ybdCt8hPvUpVRUBMs0lDvPCYgqP0UZAaQd5YlTlO7BnmeuNdCZCkp2VLaYJZBVAZDZD";
 
$link = "https://www.googleapis.com/books/v1/volumes?q=$messageContent&key=AIzaSyDHwutJNmMh9Eqa4i_CkWSbr4jEWqfh7Fc&maxResults=40";
//$link = "https://www.googleapis.com/books/v1/volumes?q=$messageContent&key=AIzaSyDHwutJNmMh9Eqa4i_CkWSbr4jEWqfh7Fc";
$contentLink = file_get_contents($link);
$book = json_decode($contentLink);
 static $n=0;
  
   if(strpos($user_Input,'*')!==false){
       $n++;
   }


$title = $book->items[$n]->volumeInfo->title;
$author = $book->items[$n]->volumeInfo->authors[0];
$desc = $book->items[$n]->volumeInfo->description;
$img = $book->items[$n]->volumeInfo->imageLinks->smallThumbnail;
$pages = $book->items[$n]->volumeInfo->pageCount;
$book_id = $book->items[$n]->id;

file_put_contents("recv_desc.txt",$desc);

$desc_rem = strlen($desc)%620;

$count_desc=0;

for($i=0; $i<strlen($desc); $i=$i+620){

    $sub_desc=substr($desc,$i,620);
    $Desc_array_Data[$count_desc] = $sub_desc;
    $count_desc++;
}
    $rem_string = substr($desc,$count_desc*620,$desc_rem);
    $Desc_array_Data[$count_desc++] = $rem_string;
 
  ///////

    $data_title = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('text'=>"Title : "."$title")
    );

    $data_author = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('text'=>"Author is : "."$author")
    );


     $Book_data_array1 = array($data_title , $data_author );

    for($i=0; $i<2; $i++){

            $options = array(
            'http'=>array(
                'method' =>'POST',
                'content'=>json_encode($Book_data_array1[$i]),
                'header'=>"Content-Type: application/json\n"
            )
        
        );
            $context = stream_context_create($options);
            if($messageContent!=null){
            file_get_contents("https://graph.facebook.com/v2.8/me/messages?access_token=$token",false,$context);
        }

    }


 /////

 for($s=0; $s<$count_desc; $s++){
     
    if($s == 0){
    $data_desc = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('text'=>"Description : "."$Desc_array_Data[$s]")
    );

        $options = array(
        'http'=>array(
            'method' =>'POST',
            'content'=>json_encode($data_desc),
            'header'=>"Content-Type: application/json\n"
        )
    
    );

       $context = stream_context_create($options);
       if($messageContent!=null){
       file_get_contents("https://graph.facebook.com/v2.8/me/messages?access_token=$token",false,$context);
      }

    }
    else{

        $data_desc = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('text'=>"-"."$Desc_array_Data[$s]")
    );

        $options = array(
        'http'=>array(
            'method' =>'POST',
            'content'=>json_encode($data_desc),
            'header'=>"Content-Type: application/json\n"
        )
    
    );

       $context = stream_context_create($options);
       if($messageContent!=null){
       file_get_contents("https://graph.facebook.com/v2.8/me/messages?access_token=$token",false,$context);
      }

    }

 }


    $data_image = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('attachment'=>array('type'=>"image",'payload'=>array('url'=>"$img" ,'is_reusable'=>true )))
    );

    $data_pages = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('text'=>"$pages"." Pages")
    );


    $Book_data_array2 = array($data_image , $data_pages);

    for($i=0; $i<2; $i++){
        $options = array(
            'http'=>array(
                'method' =>'POST',
                'content'=>json_encode($Book_data_array2[$i]),
                'header'=>"Content-Type: application/json\n"
            )
        
        );

        $context = stream_context_create($options);
        if($messageContent!=null){
        file_get_contents("https://graph.facebook.com/v2.8/me/messages?access_token=$token",false,$context);
        }

    }

/// User Choice
    $data_user = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('text'=>"Please type * after selected category if you want next Book")
    );

    $options = array(
            'http'=>array(
                'method' =>'POST',
                'content'=>json_encode($data_user),
                'header'=>"Content-Type: application/json\n"
            )
    );

    $context = stream_context_create($options);
        if($messageContent!=null){
        file_get_contents("https://graph.facebook.com/v2.8/me/messages?access_token=$token",false,$context);
        }


    //"data": {"facebook": {<Hello facebook>}}
 
?>