<?php

//phpinfo();
  $consumer_key = '9cWkEzzE43ysLMpkwLBH';
  $okapi_base_url = 'http://opencaching.pl/okapi/services/';
  $service_change_log = 'replicate/changelog';
  $since = '';
  $log_path = './changelogs/';

  $font_size = "14px";


  if ( isset($_POST['submit']) || isset($_GET['since'])) { 
  	
    if( isset($_GET['since'])) { 
		 $since = $_GET['since'] ; }
		elseif ( isset($_POST['since'])) { 
		 $since = $_POST['since'] ; }
	    
    $okapi_call_url =  $okapi_base_url . 
                 			 $service_change_log . '?' .
                 			 'consumer_key='. $consumer_key . '&'.
                 			 'since='. $since ;
    $json = file_get_contents($okapi_call_url);
		$obj = json_decode($json);
		$revision = $obj->revision ;
		$more = $obj->more ;
		if( count($obj->changelog) > 0) {
//					var_dump($obj); 
       save_log_to_file( $revision, $json );
		}
    $since = $revision;  
    if($more == true) {
    	$redirect_url = 'Location: '.$_SERVER['SCRIPT_URI'].'?since='.$since ;
    	header($redirect_url);
    }
     
         
  }    




echo '<html>
	<head>
	<title>Opencaching.pl get change logs</title> 
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">	
	<meta name="HandheldFriendly" content="true" />
	<meta name="Viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>	
	<style type="text/css">
	 body { font-family: verdana,tahoma ; font-size: '.$font_size.'; }
	 form { font-size: '.$font_size.'; }
	 input { font-size : '.$font_size.';}
	</style>
	<body>
	 <p><b>Pobranie logu zmian z <a href="http://opencaching.pl">Opencaching.pl</a> od wersji:</b></p></br>	
	 <form name="input" action="" method="post">	
        <input type="text" name="since" value="'.$since.'" maxlength="10" size="10"/><br/><br /><br />    
	    <input type="submit" name="submit" value="Pobierz" />
	   </form>
	 </body>
	 </html>'; 


function save_log_to_file( $revision, $content ) {
	global $log_path;
	
 	$file = $log_path.'change-log-r'.$revision.'.json';
 	file_put_contents($file, $content, LOCK_EX);
 }


?>


