<?php
  // Send a raw HTTP header 
  header ('Content-Type: text/html; charset=UTF-8'); 
 
  // Declare encoding META tag, it causes browser to load the UTF-8 charset 
  // before displaying the page. 
  echo '<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />';
 
  // Right to Left issue 
  echo '<body dir="rtl">';

  $token ="EAAGFCwhT4igBAJupRQsiZAZCTew9ZCJx198vaZAZCKbKTBZA782g8AFdExwqTOZC7b8MgCbjeNiiLFGEAt9qF7nfCHGiDOTzaAecx2JhnBZBarjWf4ybdCt8hPvUpVRUBMs0lDvPCYgqP0UZAaQd5YlTlO7BnmeuNdCZCkp2VLaYJZBVAZDZD";

  //$challenge = $_REQUEST['hub_challenge'];

  //if ($verify_token ===$token) {
  //echo $challenge;
  //}

file_put_contents("fb.txt",file_get_contents("php://input"));
 
$fb = file_get_contents("fb.txt");
 
$fb = json_decode($fb);

$conn = mysqli_connect("localhost","id1066811_users123" , "12345","id1066811_userinfo" );
if (!$conn) {
    echo "Connected Failed";
}
else{
echo "Connected successfully";
}
 
$messageId = $fb->entry[0]->id;
$messageSender = $fb->originalRequest->data->sender->id;
$messageContent = $fb->result->parameters->Book_category;
$user_Input = $fb->originalRequest->data->message->text;
//$post_back = $fb->originalRequest->data->postback->payload;
$post_back = $fb->entry[0]->messaging[0]->postback->payload;
$sender_ID = $fb->entry[0]->messaging[0]->sender->id;
$sender_Message = $fb->entry[0]->messaging[0]->message->text;
$water_mark  = $fb->entry[0]->messaging[0]->message[0]->delivery->watermark;
$sender_ID = (string)$sender_ID;



 if($sender_ID!="427092634308233"){
/*
 $Welcome = array(
        'recipient'=>array('id'=>"1299517510083355"),
        'message'=>array('text'=>"Hello")
    );

    $options = array(
            'http'=>array(
                'method' =>'POST',
                'content'=>json_encode($Welcome),
                'header'=>"Content-Type: application/json\n"
            )
    );

    $context = stream_context_create($options);
       
    file_get_contents("https://graph.facebook.com/v2.6/me/messages?access_token=$token",false,$context);

/*
    if($post_back=="FACEBOOK_WELCOME"){
            $saved_cat = NULL;

          $insert_q ="INSERT INTO users values('$sender_ID',' ',' ')";
          $conn->query($insert_q);
    }else{
         $insert_q ="INSERT INTO users values('$sender_ID',' ',' ')";
          $conn->query($insert_q);
    }

    */

      $insert_q ="INSERT INTO users values('$sender_ID',' ',' ')";
          $conn->query($insert_q);
    
  
    
/*
    if($conn->query($insert_q)){
               echo "Data inserted successfuly";
               }
           else{
               echo "Data has not been inserted successfuly";
           } 
  
*/

      if($sender_Message=="hi" || $sender_Message=="hello" || $sender_Message=="what's up" ){
    $Welcome = array(
        'recipient'=>array('id'=>"$sender_ID"),
        'message'=>array('text'=>" Hi there , wanna read ? then tell me your favourite category !")
    );

    $options = array(
            'http'=>array(
                'method' =>'POST',
                'content'=>json_encode($Welcome),
                'header'=>"Content-Type: application/json\n"
            )
    );

    $context = stream_context_create($options);
       
    file_get_contents("https://graph.facebook.com/v2.6/me/messages?access_token=$token",false,$context);

    }
    else{

     $n = 0;
   if($post_back=="Next Book"){
        $select_q_next = " SELECT next FROM users WHERE ID='$sender_ID' ";
        $result1 = $conn->query($select_q_next);
        $saved_next = $result1->fetch_row();
        $n = $n + ($saved_next[0]);
        $n = $n + 1;
         if($n>36){
            $n = $n-1;
            $message= "Sorry don't know more books to suggest!";

                $TEST = array(
                'recipient'=>array('id'=>"$sender_ID"),
                'message'=>array('text'=>" $message")
                );

        $options = array(
                'http'=>array(
                    'method' =>'POST',
                    'content'=>json_encode($TEST),
                    'header'=>"Content-Type: application/json\n"
                )
        );

        $context = stream_context_create($options);

        file_get_contents("https://graph.facebook.com/v2.6/me/messages?access_token=$token",false,$context);
         }

         $update_q_next ="UPDATE users SET next='$n' WHERE ID=$sender_ID ";
         $conn->query($update_q_next);

        $select_q = " SELECT category FROM users WHERE ID='$sender_ID' ";
        $result2 = $conn->query($select_q);
        $saved_cat = $result2->fetch_row();
        $saved_cat = $saved_cat[0];
        $link = "https://www.googleapis.com/books/v1/volumes?q=$saved_cat&key=AIzaSyDHwutJNmMh9Eqa4i_CkWSbr4jEWqfh7Fc&maxResults=40";
        $contentLink = file_get_contents($link);
        $book = json_decode($contentLink);
        


   }
   else {

       $update_q_next_out1 ="UPDATE users SET next='0' WHERE ID='$sender_ID' ";
       $conn->query($update_q_next_out1);
       $update_q1 ="UPDATE users SET category='$sender_Message' WHERE ID='$sender_ID' ";
       $conn->query($update_q1);
       $saved_cat = $sender_Message;
       $n=0;
       $link = "https://www.googleapis.com/books/v1/volumes?q=$saved_cat&key=AIzaSyDHwutJNmMh9Eqa4i_CkWSbr4jEWqfh7Fc&maxResults=40";
       $contentLink = file_get_contents($link);
       $book = json_decode($contentLink);

   }/*
   else{
        $update_q_next_out2 ="UPDATE users SET next='0' WHERE ID='$sender_ID' ";
        $conn->query($update_q_next_out2);
       $update_q2 ="UPDATE users SET category='$messageContent' WHERE ID=$sender_ID ";
       $conn->query($update_q2);

   }
   */
   
    $author1 = $book->items[$n]->volumeInfo->authors[0];
    $author2 = $book->items[$n+1]->volumeInfo->authors[0];
    $author3 = $book->items[$n+2]->volumeInfo->authors[0];
    $author4 = $book->items[$n+3]->volumeInfo->authors[0];
    $author5 = $book->items[$n+4]->volumeInfo->authors[0];
   if($author1==null){
        $author1 = "Unknown";
    }
    if($author2==null){
        $author2 = "Unknown";
    }
    if($author3==null){
        $author3 = "Unknown";
    }
    if($author4==null){
        $author4 = "Unknown";
    }
    if($author5==null){
        $author5 = "Unknown";
    }
   
    if($book->items[$n]->volumeInfo->pageCount==null){
        $pages = "Unknown";
    }

    $bookN1 = $n;
    $bookN2 = $n+1;
    $bookN3 = $n+2;
    $bookN4 = $n+3;
    $bookN5 = $n+4;

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

    
    $List_of_Buttons = array(
        'recipient'=>array('id'=>"$sender_ID"),
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

    $options = array(
            'http'=>array(
                'method' =>'POST',
                'content'=>json_encode($List_of_Buttons),
                'header'=>"Content-Type: application/json\n"
            )
    );
    //file_put_contents("test.txt",json_encode($List_of_Buttons));
    $context = stream_context_create($options);
     
        $n = $n + 4;
        $update_q_next ="UPDATE users SET next='$n' WHERE ID='$sender_ID' ";
        $conn->query($update_q_next);
        file_get_contents("https://graph.facebook.com/v2.6/me/messages?access_token=$token",false,$context);
        
    

  mysqli_close($conn);

 }
 
 }

//////////////////////////////////////////////////////////////////

?>