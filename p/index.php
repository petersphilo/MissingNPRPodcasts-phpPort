<?php 
error_reporting(E_ALL); // watch out for this!!
ini_set('display_errors',0); // watch out for this!!
include_once('../_MainVars.php'); 

$nprProgramRefs['weekendsaturday']=7; $nprProgramRefs['weekendsunday']=10; $nprProgramRefs['morningedition']=3; $nprProgramRefs['allthingsconsidered']=2; $nprProgramRefs['freshair']=13; 
$nprProgramRefs['wesat']=7; $nprProgramRefs['wesun']=10; $nprProgramRefs['me']=3; $nprProgramRefs['atc']=2; $nprProgramRefs['sat']=7; $nprProgramRefs['sun']=10; // alternate options, not really used...
$nprProgramRefs['7']=7; $nprProgramRefs['10']=10; $nprProgramRefs['3']=3; $nprProgramRefs['2']=2; // alternate options, not really used...
$nprProgTitle['7']='NPR Programs: Weekend Edition Saturday : NPR'; 
	$nprProgTitle['10']='NPR Programs: Weekend Edition Sunday : NPR'; 
	$nprProgTitle['3']='NPR Programs: Morning Edition : NPR'; 
	$nprProgTitle['2']='NPR Programs: All Things Considered : NPR'; // Should not be needed!!!

$MainVar['program_id']=2; 
$MainVar['MissingNPRRootURL']=$FullAppURL; 
$MainVar['NPRQueryURL'] = 'https://api.npr.org/query?'; // On Mac, must use http because of OpenSSL+curl problem!!

if(isset($_GET['TestAPIKey'])){
	if(($_GET['TestAPIKey']=='TestAPIKey')&&($_GET['api_key']!='')){$MainVar['api_key']=$_GET['api_key']; test_api_key($MainVar); }
	}
if(isset($_GET['api_key'])){
	if(isset($_GET['api_key'])&&isset($_GET['prog'])){$MainVar['api_key']=$_GET['api_key']; 
		$MainVar['program_id']=$nprProgramRefs[$_GET['prog']]; 
		$MainVar['programTitle']=$nprProgTitle[$MainVar['program_id']]; //see below...
		build_rss($MainVar); 
		}
	}

//class Podcast {
	function GetAsXML($MainVar){
		$request_url=$MainVar['NPRQueryURL'].$MainVar['urlArgs']; 
		$TheConn=curl_init();
		curl_setopt($TheConn, CURLOPT_URL, $request_url);
		curl_setopt($TheConn, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($TheConn, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($TheConn);
		curl_close($TheConn);
		$respPre=str_replace('nprml:','nprml_',$response); //deal with colons
		return simplexml_load_string($respPre); 
		//return simplexml_load_string($respPre, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS); 
		}
	
	function test_api_key($MainVar){
		$MainVar['urlArgs']='parent=2&fields=all&dataType=story&output=RSS&numResults=1&apiKey='.$MainVar['api_key']; 
		$GetAsXMLResp=GetAsXML($MainVar); 
		$tmpResponse=$GetAsXMLResp->list->title;
		if($tmpResponse=='NPR: Stories from NPR : NPR'){
			$jsonResponse['validKey']=true; 
			echo json_encode($jsonResponse); 
			}
		else {echo PHP_EOL.'invalid key'.PHP_EOL; }
		die();
		}
	
	function build_rss($MainVar){
		//$MainVar['urlArgs']='id='.$MainVar['program_id'].'&fields=title,link,language,show,copyright,description,teaser,audio,product,summary,titles,teasers,dates,byline&dataType=story&output=RSS&numResults=30&apiKey='.$MainVar['api_key']; 
		//$MainVar['urlArgs']='id='.$MainVar['program_id'].'&fields=title,link,language,show,copyright,description,teaser,audio,product,summary,titles,teasers,dates,byline&dataType=story&output=RSS&numResults=36&apiKey='.$MainVar['api_key']; 
		//$MainVar['urlArgs']='id='.$MainVar['program_id'].'&fields=show,audio,titles,teasers,dates,byline&dataType=story&output=XML&numResults=36&apiKey='.$MainVar['api_key']; 
		$MainVar['urlArgs']='parent='.$MainVar['program_id'].'&fields=show,audio,titles,teasers,dates,byline&dataType=story&output=XML&numResults=50&apiKey='.$MainVar['api_key']; 
		//echo 'got here'; 
		$GetAsXMLResp=GetAsXML($MainVar); 
		//echo "got here".$response;
		$xml=new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?><rss version="2.0" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"></rss>');
		$channelMain=$xml->addChild('channel');
			$channelMain->addChild('title'); $channelMain->title=$MainVar['programTitle']; 
			$channelMain->addChild('link'); $channelMain->link=$GetAsXMLResp->list->link[0]; 
			$channelMain->addChild('description'); $channelMain->description=$GetAsXMLResp->list->miniTeaser; 
			$channelMain->addChild('language'); $channelMain->language=$GetAsXMLResp->list->language; 
			$channelMain->addChild('copyright'); $channelMain->copyright=$GetAsXMLResp->list->copyright; 
			$channelMain->addChild('itunesZXZXauthor',''); 
			$channelMain->addChild('itunesZXZXsubtitle',''); 
			$channelMain->addChild('itunesZXZXsummary'); $channelMain->itunesZXZXsummary=$GetAsXMLResp->list->miniTeaser; 
			$iTunesImage=$channelMain->addChild('itunesZXZXimage'); 
			$iTunesImageAtr=$MainVar['MissingNPRRootURL'].$MainVar['program_id'].'-newest.jpg'; 
			$iTunesImage->addAttribute('href',$iTunesImageAtr); 
			$iTunesOwner=$channelMain->addChild('itunesZXZXowner'); 
			$iTunesOwner->addChild('itunesZXZXname','NPR: National Public Radio'); 
			//populate_rss_story(); // was its own function...
				foreach ($GetAsXMLResp->list->story as $item){
					if($item->show->program['id']==$MainVar['program_id']){
						if($MainVar['program_id']==3){$item_AudioURL_Pre=preg_replace('/(.*)\?.*/','$1',$item->audio->format->mp3); }else{$item_AudioURL_Pre=$item->audio->format->mp3; }
						$item_AudioURL=$item_AudioURL_Pre; 
						//$item_AudioURL=$item->audio->format->mp3; 
						if ($item_AudioURL!=''){
							$pubDatePre=new DateTime($item->show->showDate); 
							//if($MainVar['program_id']==3 && $item->storyDate!=''){$pubDatePre=new DateTime($item->storyDate); }
							$segNum=$item->show->segNum; /* $pubDatePre=new DateTime($item->show->showDate); */ $pubDatePre->modify("+{$segNum} minutes"); $pubDate=$pubDatePre->format('r'); 
							$Channelitem=$channelMain->addChild('item'); 
							$Channelitem->addChild('title'); $Channelitem->title=$item->title; 
							$Channelitem->addChild('description'); $Channelitem->description=$item->teaser; 
							$Channelitem->addChild('link'); $Channelitem->link=$item->link[0]; 
							$Channelitem->addChild('guid'); //$Channelitem->guid=$item->attributes()->id; 
								if($MainVar['program_id']==3){$itemGUID=$item->attributes()->id.'-ME'; }else{$itemGUID=$item->attributes()->id; }
								$Channelitem->guid=$itemGUID; 
							$Channelitem->addChild('itunesZXZXsubtitle'); $Channelitem->itunesZXZXsubtitle=''; 
							$Channelitem->addChild('itunesZXZXsummary'); $Channelitem->itunesZXZXsummary=$item->teaser; 
							$Channelitem->addChild('itunesZXZXexplicit'); $Channelitem->itunesZXZXexplicit='no';
							$ChannelitemEnclos=$Channelitem->addChild('enclosure'); 
							$ChannelitemEnclos->addAttribute('url',$item_AudioURL); 
							$ChannelitemEnclos->addAttribute('type','audio/mpeg'); 
							$Channelitem->addChild('itunesZXZXduration'); $Channelitem->itunesZXZXduration=$item->audio->duration;  
							$Channelitem->addChild('pubDate'); $Channelitem->pubDate=$pubDate; 
							$Channelitem->addChild('lastModifiedDate'); $Channelitem->lastModifiedDate=$pubDate; 
							$Channelitem->addChild('itunesZXZXauthor'); $Channelitem->itunesZXZXauthor=$item->byline->name; 
							}
						}
					}
		$xmlClean = new DOMDocument(); // seems like a huge memory cost (4184 bytes), just to make it 'pretty'; used DOMDoc: http://php.net/manual/en/class.domdocument.php
		$xmlClean->loadXML($xml->asXML()); 
		$xmlClean->formatOutput = true; 
		echo str_replace('itunesZXZX','itunes:',$xmlClean->saveXML())/**//* .'Mem: '.PHP_EOL.memory_get_usage().PHP_EOL *//* .PHP_EOL.$MainVar['programTitle'].PHP_EOL */;
		/*  end DOMDoc; it's really useless, just vanity formatting... to disable, comment 4 lines above, and un-comment 1 line below:  */
		//echo str_replace('itunesZXZX','itunes:',$xml->asXML())/* .PHP_EOL.memory_get_usage().PHP_EOL */;
		//sleep(1); $exec_time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]; echo PHP_EOL."Process Time: $exec_time".PHP_EOL;
		}
//	}


?>
