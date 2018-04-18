<?php

 $Recived_data = file_get_contents("php://input");

 $Recived_data = json_decode($Recived_data);
 
 $sender_ID = $Recived_data->entry[0]->messaging[0]->sender->id;
 $sender_Message = $Recived_data->entry[0]->messaging[0]->message->text;

  $token ="EAAZAPYFo4g88BAHo17TmUdromzyzMkqkOxD615Cr3dbG8BYEQIdAZAiiczLRQWc2tZCFwlZBABToRV3tDIi7t".
  "Ywrd9YXjjMtIiFbeZCkXHDK1u06YmlZBppOcnwyLzMEskLkgXSOev6SCavnZAWnHHI23wkM0yuotBLFoJe5kxgqQZDZD";

  $send_data = array('recipient'=>array('id'=>$sender_ID),'message'=>array('text'=>" Hello user "));

  $options = array('http'=>array('method'=>'POST','content'=>json_encode($send_data),'header'=>"Content-Type: application/json\n"));

  $context = stream_context_create($options);

  if($sender_Message!=null){
      file_get_contents("https://graph.facebook.com/v2.8/me/messages?access_token=$token", false , $context);
  }

?>


<?php namespace AI;
class APIAIProvider implements AIContract
{
  protected $api_key;
  protected $serviceUrl;
  protected $version = "20150910";
  protected $language = "en"
  public function query($q)
  {
    $response = $client->post($this->getServiceUrl('query'), array(
          'headers' => array(
              'Authorization' => "Bearer {$this->api_key}",
              'Content-Type' => 'application/json; charset=utf-8'
          ),
          'json' => array(
              "query" => urlencode($q),
              "lang" => $this->language,
              "v" => $this->version
          )
      ));
      $result = $response->json();
      return json_decode($result, true);
  }
  public function __construct(String $key, String $serviceUrl)
  {
    $this->key = $key;
    $this->serviceUrl = $serviceUrl;
  }
  // Get Service Url
  public function getServiceUrl($endpoint = "")
  {
    if (! empty($endpoint)) {
      return $this->serviceUrl;
    }
    return $this->serviceUrl;
  }
  
}



?>



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
$token = "EAAZAriZChusqMBAAGZA1VP0uWaNyZCvwtSHkX8BSczvXODhQcwEUROtPEH7miZCjvCduZAQbbNVFtYaSUo9v2Vdt0SYf06cjIZBGiljXjpfz4febs9Llh3GLtx08mgEye6ERYsxAOpdpBSLJkHAK7yotCReAQeNs5FFYo8LimUG9AZDZD";
 
$link = "https://w...content-available-to-author-only...s.com/books/v1/volumes?q=$messageContent&key=AIzaSyDHwutJNmMh9Eqa4i_CkWSbr4jEWqfh7Fc";
$contentLink = file_get_contents($link);
$book = json_decode($contentLink);
$title = $book->items[0]->volumeInfo->title ;
$author = $book->items[0]->volumeInfo->authors[0];
$desc = $book->items[0]->volumeInfo->description ; 
$img = $book->items[0]->volumeInfo->imageLinks->smallThumbnail
$pages = $book->items[0]->volumeInfo->pageCount ;

 
$meg = $title ;
 
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
file_get_contents("https://g...content-available-to-author-only...k.com/v2.8/me/messages?access_token=$token",false,$context);
}
 
?>