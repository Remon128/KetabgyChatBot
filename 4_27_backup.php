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

$conn = mysqli_connect("localhost","id1066811_users123" , "12345","id1066811_userinfo" );
if (!$conn) {
    echo "Connected Failed";
}
else{
echo "Connected successfully";
}
 
$messageId = $fb->id;
$messageSender = $fb->originalRequest->data->sender->id;
$messageContent = $fb->result->parameters->Book_category;
$user_Input = $fb->originalRequest->data->message->text;
$post_back = $fb->originalRequest->data->postback->payload;
$token ="EAAGFCwhT4igBAJupRQsiZAZCTew9ZCJx198vaZAZCKbKTBZA782g8AFdExwqTOZC7b8MgCbjeNiiLFGEAt9qF7nfCHGiDOTzaAecx2JhnBZBarjWf4ybdCt8hPvUpVRUBMs0lDvPCYgqP0UZAaQd5YlTlO7BnmeuNdCZCkp2VLaYJZBVAZDZD";


    if(strcmp($post_back,"FACEBOOK_WELCOME")==0){
           
           $saved_cat = NULL;
    }

    $sql = "SELECT ID FROM users WHERE ID=$messageSender ";
    $result = $conn->query($sql);
    
    $sender_ID = (string)$messageSender;
    $insert_q ="INSERT INTO users values('$sender_ID',' ','0')";

    
    if($conn->query($insert_q)){
        echo "Data inserted successfuly";
    }
    else{
        echo "Data has not been inserted successfuly";
    }   


$link = "https://www.googleapis.com/books/v1/volumes?q=$messageContent&key=AIzaSyDHwutJNmMh9Eqa4i_CkWSbr4jEWqfh7Fc&maxResults=40";
//$link = "https://www.googleapis.com/books/v1/volumes?q=$messageContent&key=AIzaSyDHwutJNmMh9Eqa4i_CkWSbr4jEWqfh7Fc";
$contentLink = file_get_contents($link);
$book = json_decode($contentLink);
$n=0;
  
   if(strcmp($post_back,"Next book")==0){
        $select_q_next = " SELECT next FROM users WHERE ID=$sender_ID ";
        $result1 = $conn->query($select_q_next);
        $saved_next = $result1->fetch_row();
        $n = $n + ($saved_next[0]);
        $n++;
         if($n>40){
            $n = $n-1;
            $message= "Sorry don't know more books to suggest!";
            $flag0=0;
        }
        $update_q_next ="UPDATE users SET next='$n' WHERE ID=$sender_ID ";
        $conn->query($update_q_next);

        $select_q = " SELECT category FROM users WHERE ID=$sender_ID ";
        $result2 = $conn->query($select_q);
        $saved_cat = $result2->fetch_row();
        $saved_cat = $saved_cat[0];
        $link = "https://www.googleapis.com/books/v1/volumes?q=$saved_cat&key=AIzaSyDHwutJNmMh9Eqa4i_CkWSbr4jEWqfh7Fc&maxResults=40";
        $contentLink = file_get_contents($link);
        $book = json_decode($contentLink);

   }
   else if(strcmp($post_back,"Skip book")==0){

        $select_q_next = " SELECT next FROM users WHERE ID=$sender_ID ";
        $result1 = $conn->query($select_q_next);
        $saved_next = $result1->fetch_row();
        $n = $n + ($saved_next[0]);
        $n = $n +5 ;
        if($n>40){
            $n = $n-5;
            $message= "Sorry don't know more books to suggest!";
            $flag1=0;
        }
        $update_q_next ="UPDATE users SET next='$n' WHERE ID=$sender_ID ";
        $conn->query($update_q_next);

        $select_q = " SELECT category FROM users WHERE ID=$sender_ID ";
        $result2 = $conn->query($select_q);
        $saved_cat = $result2->fetch_row();
        $saved_cat = $saved_cat[0];
        $link = "https://www.googleapis.com/books/v1/volumes?q=$saved_cat&key=AIzaSyDHwutJNmMh9Eqa4i_CkWSbr4jEWqfh7Fc&maxResults=40";
        $contentLink = file_get_contents($link);
        $book = json_decode($contentLink);
       
   }
   else{
       $update_q_next_out ="UPDATE users SET next='0' WHERE ID=$sender_ID ";
       $conn->query($update_q_next_out);
       $update_q ="UPDATE users SET category='$messageContent' WHERE ID=$sender_ID ";
       $conn->query($update_q);
       $saved_cat = $messageContent;
   }


 //if($flag0!=0 && $flag1!=0){

$title = $book->items[$n]->volumeInfo->title;
$author = $book->items[$n]->volumeInfo->authors[0];
//$desc = $book->items[$n]->volumeInfo->description;
//$link_to_desc = $book->items[$n]->volumeInfo->infoLink;
$publish_date = $book->items[$n]->volumeInfo->publishedDate;
$img = $book->items[$n]->volumeInfo->imageLinks->smallThumbnail;
$pages = $book->items[$n]->volumeInfo->pageCount;
$Pdf_link =$book->items[$n]->accessInfo->webReaderLink;

 
  /////// data Part:

    $data_publish = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('text'=>"Publish Date : "."$publish_date")
    );

    if($author==null){
        $author = "Unknown";
    }
    if($pages==null){
        $pages = "Unknown";
    }

    $data_title = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('text'=>"Title is : "."$title")
    );
    
    $data_author = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('text'=>"Author is : "."$author")
    );

    $data_image = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('attachment'=>array('type'=>"image",'payload'=>array('url'=>"$img" ,'is_reusable'=>true )))
    );

    $data_pages = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('text'=>"$pages"." Pages")
    );

    $link_desc_Button = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('attachment'=>array('type'=>"template",'payload'=>array('template_type'=>"button" ,'text'=>"Read description!",
        'buttons'=>array( 0=>array('type'=>"web_url","url"=>"http://tameable-visit.000webhostapp.com/desc.php?bookid=$saved_cat&number=$n" ,
        'title'=>"Description!",'webview_height_ratio'=>"compact") ) )))
    );
  
    $pdf_book_button = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('attachment'=>array('type'=>"template",'payload'=>array('template_type'=>"button" ,'text'=>"Access pdf book!",
        'buttons'=>array( 0=>array('type'=>"web_url","url"=>"$Pdf_link" ,
        'title'=>"Pdf Book!",'webview_height_ratio'=>"compact") ) )))
    );


     $Book_data_array = array($data_image , $data_title , $data_publish , $data_author ,$data_pages , $link_desc_Button , $pdf_book_button);

   for($i=0; $i<7; $i++){

       $options = array(
            'http'=>array(
                'method' =>'POST',
                'content'=>json_encode($Book_data_array[$i]),
                'header'=>"Content-Type: application/json\n"
            )
    );
    
    $context = stream_context_create($options);
        if($saved_cat!=null){
        file_get_contents("https://graph.facebook.com/v2.8/me/messages?access_token=$token",false,$context);
        }

   }

///////////////////////////////// Buttons Part:
    $next_Button = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('attachment'=>array('type'=>"template",'payload'=>array('template_type'=>"button" ,'text'=>"So wanna another Book ?",
        'buttons'=>array( 0=>array('type'=>"postback",'title'=>"Next Book!",'payload'=>"Next book") ) )))
    );
    
    $skip_Button = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('attachment'=>array('type'=>"template",'payload'=>array('template_type'=>"button" ,'text'=>"You can skip books!",
        'buttons'=>array( 0=>array('type'=>"postback",'title'=>"Skip (5) books!",'payload'=>"Skip book") ) )))
    );

    $Buttons = array($next_Button , $skip_Button );

    for($j=0; $j<2; $j++){

         $options = array(
            'http'=>array(
                'method' =>'POST',
                'content'=>json_encode($Buttons[$j]),
                'header'=>"Content-Type: application/json\n"
            )
    );

    $context = stream_context_create($options);
        if($saved_cat!=null){
        file_get_contents("https://graph.facebook.com/v2.8/me/messages?access_token=$token",false,$context);
        }

    }

    $List_of_Buttons = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array(
        'attachment'=>array(
        'type'=>"template",
        'payload'=>array('template_type'=>"generic" ,
        'elements'=>array( 
         0=>array(
        'title'=>"Welcome Test" , 
        'image_url'=>"http://i.ebayimg.com/images/g/l6IAAOxyUrZS64di/s-l400.jpg" , 
        'subtitle'=>"subtitle test" , 
        'buttons'=>array( 
            0=>array('type'=>"web_url",'url'=>"https://tameable-visit.000webhostapp.com/desc.php?bookid=$saved_cat&number=$n",'title'=>"TEST"),
            1=>array('type'=>"postback",'title'=>"Start Chatting",'payload'=>"DEVELOPER_DEFINED_PAYLOAD")
             ) 
        )
        
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
    file_put_contents("test.txt",json_encode($List_of_Buttons));

    $context = stream_context_create($options);
        
        file_get_contents("https://graph.facebook.com/v2.6/me/messages?access_token=$token",false,$context);


 //}

 /*
 else{
       $messageException =array( 
           'recipient'=>array('id'=>"$messageSender"), 'message'=>array('text'=>$message) )

           $options = array(
            'http'=>array(
                'method' =>'POST',
                'content'=>json_encode($messageException),
                'header'=>"Content-Type: application/json\n"
            )

      $context = stream_context_create($options);
        if($saved_cat!=null){
        file_get_contents("https://graph.facebook.com/v2.8/me/messages?access_token=$token",false,$context);
        }

 }



/// User Choice


    //"data": {"facebook": {<Hello facebook>}}
 
?>