<?php

$YourPageURL='xxxxxx'; // example: my.domain.tld/my/dir/  **don't forget the trailing slash!** -- missingnpr.zconsulting.net/
$YourProtocol='xxxxxx'; // https or http -- https
$YourUA='UA-xxxxxxxx-x'; // Google Analytics ID -- UA-24869005-2


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
	$FullAppURL=$protocol.'://'.$YourPageURL.'p/'; 
	}

$FullPageURL=$protocol.'://'.$YourPageURL; 

?>
