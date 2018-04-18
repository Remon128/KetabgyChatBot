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
$post_back = $fb->originalRequest->data->postback->payload;
$token ="EAAGFCwhT4igBAOVcFkeJqdHa2H3amONZBxBPOdV8MIztigEZB0O9ZAZCUFiPcSzcIcySAkedj7uVT9mf4NEIUsCRjyXxBmAacDKFDQndF1r5faIQoEqBTbcqeCBnx77s1ZAlPOeNEKcnICOawHZAVtHfZBNTvbAW4qlquskupCx8AZDZD";
    

       $n = 0;
       $saved_cat = $messageContent;
       $n=0;
       $link = "https://www.googleapis.com/books/v1/volumes?q=$saved_cat&key=AIzaSyDHwutJNmMh9Eqa4i_CkWSbr4jEWqfh7Fc&maxResults=40";
       $contentLink = file_get_contents($link);
       $book = json_decode($contentLink);
    
       
       $desc1 = $book->items[$n]->volumeInfo->description;
    $desc2 = $book->items[$n+1]->volumeInfo->description;
    $desc3 = $book->items[$n+2]->volumeInfo->description;
    $desc4 = $book->items[$n+3]->volumeInfo->description;
    $desc5 = $book->items[$n+4]->volumeInfo->description;

    $title1 = $book->items[$n]->volumeInfo->title;
    $title2 = $book->items[$n+1]->volumeInfo->title;
    $title3 = $book->items[$n+2]->volumeInfo->title;
    $title4 = $book->items[$n+3]->volumeInfo->title;
    $title5 = $book->items[$n+4]->volumeInfo->title;

    /*
    $List_of_Buttons = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array(
        'attachment'=>array(
        'type'=>"template",
        'payload'=>array('template_type'=>"generic" ,
        'elements'=>array( 
         0=>array(
        'title'=>$title1 , 
        'image_url'=>$img = $book->items[$n]->volumeInfo->imageLinks->smallThumbnail , 
        'subtitle'=>"Author: ".$author1.
        "\n"."Publish Date: ".$book->items[$n]->volumeInfo->publishedDate."\n"."Pages: ".$book->items[$n]->volumeInfo->pageCount, 
        'buttons'=>array( 
            0=>array('type'=>"web_url",'url'=>"https://tameable-visit.000webhostapp.com/desc.php?bookid=$title1&number=$desc1",'title'=>"Description"),
            1=>array('type'=>"web_url",'url'=>$book->items[$n]->accessInfo->webReaderLink,'title'=>"PDF Book"),
          //  1=>array('type'=>"postback",'title'=>"Start Chatting",'payload'=>"DEVELOPER_DEFINED_PAYLOAD")
             ) 
        ),
        1=>array(
        'title'=>$title2 , 
        'image_url'=>$img = $book->items[$n+1]->volumeInfo->imageLinks->smallThumbnail , 
        'subtitle'=>"Author: ".$author2.
        "\n"."Publish Date: ".$book->items[$n+1]->volumeInfo->publishedDate."\n"."Pages: ".$book->items[$n+1]->volumeInfo->pageCount, 
        'buttons'=>array( 
            0=>array('type'=>"web_url",'url'=>"https://tameable-visit.000webhostapp.com/desc.php?bookid=$title2&number=$desc2",'title'=>"Description"),
            1=>array('type'=>"web_url",'url'=>$book->items[$n+1]->accessInfo->webReaderLink,'title'=>"PDF Book"),
          //  1=>array('type'=>"postback",'title'=>"Start Chatting",'payload'=>"DEVELOPER_DEFINED_PAYLOAD")
             ) 
        ),
        2=>array(
        'title'=>$title3 , 
        'image_url'=>$img = $book->items[$n+2]->volumeInfo->imageLinks->smallThumbnail , 
        'subtitle'=>"Author: ".$author3.
        "\n"."Publish Date: ".$book->items[$n+2]->volumeInfo->publishedDate."\n"."Pages: ".$book->items[$n+2]->volumeInfo->pageCount, 
        'buttons'=>array( 
            0=>array('type'=>"web_url",'url'=>"https://tameable-visit.000webhostapp.com/desc.php?bookid=$title3&number=$desc3",'title'=>"Description"),
            1=>array('type'=>"web_url",'url'=>$book->items[$n+2]->accessInfo->webReaderLink,'title'=>"PDF Book"),
          //  1=>array('type'=>"postback",'title'=>"Start Chatting",'payload'=>"DEVELOPER_DEFINED_PAYLOAD")
             ) 
        ),
        3=>array(
        'title'=>$title4 , 
        'image_url'=>$img = $book->items[$n+3]->volumeInfo->imageLinks->smallThumbnail , 
        'subtitle'=>"Author: ".$author4.
        "\n"."Publish Date: ".$book->items[$n+3]->volumeInfo->publishedDate."\n"."Pages: ".$book->items[$n+3]->volumeInfo->pageCount, 
        'buttons'=>array( 
            0=>array('type'=>"web_url",'url'=>"https://tameable-visit.000webhostapp.com/desc.php?bookid=$title4&number=$desc4",'title'=>"Description"),
            1=>array('type'=>"web_url",'url'=>$book->items[$n+3]->accessInfo->webReaderLink,'title'=>"PDF Book"),
          //  1=>array('type'=>"postback",'title'=>"Start Chatting",'payload'=>"DEVELOPER_DEFINED_PAYLOAD")
             ) 
        ),
         4=>array(
        'title'=>$title5 , 
        'image_url'=>$img = $book->items[$n+4]->volumeInfo->imageLinks->smallThumbnail , 
        'subtitle'=>"Author: ".$author5.
        "\n"."Publish Date: ".$book->items[$n+4]->volumeInfo->publishedDate."\n"."Pages: ".$book->items[$n+4]->volumeInfo->pageCount, 
        'buttons'=>array( 
            0=>array('type'=>"web_url",'url'=>"https://tameable-visit.000webhostapp.com/desc.php?bookid=$title5&number=$desc5",'title'=>"Description"),
            1=>array('type'=>"web_url",'url'=>$book->items[$n+4]->accessInfo->webReaderLink,'title'=>"PDF Book"),
            2=>array('type'=>"postback",'title'=>"More!",'payload'=>"Next Book")
             ) 
        ),
        
        ) 
        ) 
        ) 
        )
    );
    */

    $TEST = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('text'=>" TEST ".$messageContent)
    );

    $options = array(
            'http'=>array(
                'method' =>'POST',
                'content'=>json_encode($TEST),
                'header'=>"Content-Type: application/json\n"
            )
    );
 //   file_put_contents("test.txt",json_encode($List_of_Buttons));

    $context = stream_context_create($options);  
       
    file_get_contents("https://graph.facebook.com/v2.6/me/messages?access_token=$token",false,$context);

?>