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
//file_put_contents("api.txt",$messageSender);

//$sql = "SELECT ID FROM users WHERE ID=$messageSender ";
//$result = $conn->query($sql);
    $sender_ID = (string)$messageSender;
    $insert_q ="INSERT INTO users values('$sender_ID','$messageContent','0')";
    
    if($conn->query($insert_q)){
        echo "Data inserted successfuly";
    }
    else{
        echo "Data has not been inserted successfuly";
    }

    $update_q ="UPDATE users SET category='$messageContent' WHERE ID=$sender_ID ";
    if($conn->query($update_q)){
        echo "Data updated successfuly";
    }
    else{
        echo "Data has not been updated successfuly";
    }


      
$token ="EAAGFCwhT4igBAJupRQsiZAZCTew9ZCJx198vaZAZCKbKTBZA782g8AFdExwqTOZC7b8MgCbjeNiiLFGEAt9qF7nfCHGiDOTzaAecx2JhnBZBarjWf4ybdCt8hPvUpVRUBMs0lDvPCYgqP0UZAaQd5YlTlO7BnmeuNdCZCkp2VLaYJZBVAZDZD";
 
$link = "https://www.googleapis.com/books/v1/volumes?q=$messageContent&key=AIzaSyDHwutJNmMh9Eqa4i_CkWSbr4jEWqfh7Fc&maxResults=40";
//$link = "https://www.googleapis.com/books/v1/volumes?q=$messageContent&key=AIzaSyDHwutJNmMh9Eqa4i_CkWSbr4jEWqfh7Fc";
$contentLink = file_get_contents($link);
$book = json_decode($contentLink);
 static $n=0;
  
   if(isset($post_back)){
        $n++;
        $read = file_get_contents("nextPrev.txt");
        $n = $read + $n;
         
        $saved_cat = file_get_contents("category.txt");
        $link = "https://www.googleapis.com/books/v1/volumes?q=$saved_cat&key=AIzaSyDHwutJNmMh9Eqa4i_CkWSbr4jEWqfh7Fc&maxResults=40";
        $contentLink = file_get_contents($link);
        $book = json_decode($contentLink);

        $myfile = fopen("nextPrev.txt", "w");
        fwrite($myfile,$n);
        fclose("nextPrev.txt");

   }
   else{
       $myfile2 = fopen("category.txt","w");
       $saved_cat = $messageContent;
       fwrite($myfile2,"$messageContent");
       fclose("category.txt");

        $myfile = fopen("nextPrev.txt", "w");
        fwrite($myfile,0);
        fclose("nextPrev.txt");
   }

   $file_text = fopen("test.txt","w");
        fwrite($file_text,$n);
        fwrite($file_text,$saved_cat);
        fclose("test.txt");


//$title = $book->items[$n]->volumeInfo->title;
$author = $book->items[$n]->volumeInfo->authors[0];
//$desc = $book->items[$n]->volumeInfo->description;
//$link_to_desc = $book->items[$n]->volumeInfo->infoLink;
$publish_date = $book->items[$n]->volumeInfo->publishedDate;
$img = $book->items[$n]->volumeInfo->imageLinks->smallThumbnail;
$pages = $book->items[$n]->volumeInfo->pageCount;
$Pdf_link =$book->items[$n]->accessInfo->webReaderLink;

 
  ///////

    $data_publish = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('text'=>"Publish_Date : "."$publish_date")
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
/*
     $data_link_desc = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('text'=>"Link to description and more info \n "."$link_to_desc")
    );
*/
    
     $link_desc = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('text'=>"Link to description : "."http://tameable-visit.000webhostapp.com/desc.php?bookid=$messageContent&number=$n")
    );
    
    $data_pdf_source = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('text'=>"Access pdf Book \n"."$Pdf_link")
    );


     $Book_data_array = array($data_image , $data_publish , $data_author , $link_desc , $data_pdf_source , $data_pages );

   for($i=0; $i<6; $i++){

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


    $Button = array(
        'recipient'=>array('id'=>"$messageSender"),
        'message'=>array('attachment'=>array('type'=>"template",'payload'=>array('template_type'=>"button" ,'text'=>"So wanna another Book ?",
        'buttons'=>array( 0=>array('type'=>"postback",'title'=>"Next Book!",'payload'=>"Next book") ) )))
    );
    

    $options = array(
            'http'=>array(
                'method' =>'POST',
                'content'=>json_encode($Button),
                'header'=>"Content-Type: application/json\n"
            )
    );

    $context = stream_context_create($options);
        if($saved_cat!=null){
        file_get_contents("https://graph.facebook.com/v2.8/me/messages?access_token=$token",false,$context);
        }

/// User Choice


    //"data": {"facebook": {<Hello facebook>}}
 
?>



