<?php

$YourPageURL='xxxxxx'; // example: my.domain.tld/my/dir/  don't forget the trailing slash!
$YourProtocol='xxxxxx'; // https or http
$YourUA='UA-xxxxxxxx-x'; // Google Analytics ID


if(isset($_SERVER['HTTPS'])){$protocol='https://'; }else{$protocol='http://'; }
if($YourProtocol!='xxxxxx'){$protocol=$YourProtocol; }

if($YourPageURL=='xxxxxx'){
	$fullURLpop=explode('/',$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']); 
	$TheLastBit=array_pop($fullURLpop); 
	if (preg_match('/\.php/',$TheLastBit)){
		foreach($fullURLpop as $URLChunk){
			$MainURL.=$URLChunk.'/'; 
			}
		} 
	$YourPageURL=$MainURL; 
	$FullAppURL=$protocol.'://'.$MainURL; 
	}
else {
	//$PageURL=$YourPageURL; 
	$FullAppURL=$protocol.'://'.$YourPageURL.'p/'; 
	}

$FullPageURL=$protocol.'://'.$YourPageURL; 
//$FullAppURL=$protocol.$AppURL; 
	
//echo '<br>The App domain: '.rtrim($PageURL,'/').'<br>'; 
//echo '<br>The App URL: '.$TheAppURL.'<br>'; 	
//echo '<br>The Full URL: '.$TheAppURL.$TheLastBit.'<br>'; 


?>
