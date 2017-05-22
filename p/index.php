<?php 
include_once('../_MainVars.php'); 

$nprProgramRefs['weekendsaturday']=7; $nprProgramRefs['weekendsunday']=10; $nprProgramRefs['morningedition']=3; $nprProgramRefs['allthingsconsidered']=2; 
$nprProgramRefs['wesat']=7; $nprProgramRefs['wesun']=10; $nprProgramRefs['me']=3; $nprProgramRefs['atc']=2; $nprProgramRefs['sat']=7; $nprProgramRefs['sun']=10; // alternate options, not really used...
$nprProgramRefs['7']=7; $nprProgramRefs['10']=10; $nprProgramRefs['3']=3; $nprProgramRefs['2']=2; // alternate options, not really used...

$MainVar['program_id']=2; 
$MainVar['MissingNPRRootURL']=$FullAppURL; 
$MainVar['NPRQueryURL'] = 'https://api.npr.org/query?'; // On Mac, must use http because of OpenSSL+curl problem!!

if (($_GET['TestAPIKey']=='TestAPIKey')&&($_GET['api_key']!='')){$MainVar['api_key']=$_GET['api_key']; test_api_key($MainVar); }
if (($_GET['api_key']!='')&&($_GET['prog']!='')){$MainVar['api_key']=$_GET['api_key']; $MainVar['program_id']=$nprProgramRefs[$_GET['prog']]; build_rss($MainVar); }
 
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
		}
	
	function test_api_key($MainVar){
		$MainVar['urlArgs']='id=1&fields=all&dataType=story&output=RSS&numResults=1&apiKey='.$MainVar['api_key']; 
		$GetAsXMLResp=GetAsXML($MainVar); 
		$tmpResponse=$GetAsXMLResp->channel->title;
		if($tmpResponse=='NPR: Stories from NPR : NPR'){
			$jsonResponse['validKey']=true; 
			echo json_encode($jsonResponse); 
			}
		else {echo PHP_EOL.'invalid key'.PHP_EOL; }
		die();
		}
	
	function build_rss($MainVar){
		$MainVar['urlArgs']='id='.$MainVar['program_id'].'&fields=title,link,language,show,copyright,description,teaser,audio,product,summary,titles,teasers,dates,byline&dataType=story&output=RSS&numResults=30&apiKey='.$MainVar['api_key']; 
		$GetAsXMLResp=GetAsXML($MainVar); 
		$xml=new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?><rss version="2.0" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"></rss>');
		$channelMain=$xml->addChild('channel');
		//populate_rss_channel(); // was its own function...
			$channelVars['title']=$GetAsXMLResp->channel->title; 
			$channelVars['link']=$GetAsXMLResp->channel->link; 
			$channelVars['description']=$GetAsXMLResp->channel->description; 
			$channelVars['language']=$GetAsXMLResp->channel->language; 
			$channelVars['copyright']=$GetAsXMLResp->channel->copyright; 
			$channelMain->addChild('title',$channelVars['title']); 
			$channelMain->addChild('link',$channelVars['link']); 
			$channelMain->addChild('description',$channelVars['description']); 
			$channelMain->addChild('language',$channelVars['language']); 
			$channelMain->addChild('copyright',$channelVars['copyright']); 
			$channelMain->addChild('itunesZXZXauthor',''); 
			$channelMain->addChild('itunesZXZXsubtitle',''); 
			$channelMain->addChild('itunesZXZXsummary',$channelVars['description']); 
			$iTunesImage=$channelMain->addChild('itunesZXZXimage'); 
			$iTunesImageAtr=$MainVar['MissingNPRRootURL'].$MainVar['program_id'].'-new.png'; 
			$iTunesImage->addAttribute('href',$iTunesImageAtr); 
			$iTunesOwner=$channelMain->addChild('itunesZXZXowner'); 
			$iTunesOwner->addChild('itunesZXZXname','NPR: National Public Radio'); 
			//populate_rss_story(); // was its own function...
				foreach ($GetAsXMLResp->channel->item as $item){
					$item_AudioURL=$item->nprml_audio->nprml_format->nprml_mp3[0]; 
					if ($item_AudioURL!=''){
						$itemNode['title']=$item->title; 
						$itemNode['description']=$item->description; 
						$itemNode['link']=$item->link; 
						$itemNode['guid']=$item->guid; 
						$itemNode['itunesZXZXsubtitle']=''; 
						$itemNode['itunesZXZXsummary']=$item->description; 
						$itemNode['itunesZXZXexplicit']='no'; 
						$itemNode['itunesZXZXduration']=$item->nprml_audio->nprml_duration; 
						$itemNode['pubDate']=$item->pubDate; 
						$itemNode['lastModifiedDate']=$item->nprml_lastModifiedDate; 
						$itemNode['itunesZXZXauthor']=$item->nprml_byline->nprml_name; 
						//$itemNode['yyyyyy']=$item->nprml_yyyyyy; 
						$Channelitem=$channelMain->addChild('item'); 
						$Channelitem->addChild('title',$itemNode['title']); 
						$Channelitem->addChild('description',$itemNode['description']); 
						$Channelitem->addChild('link',htmlspecialchars($itemNode['link'])); 
						$Channelitem->addChild('guid',htmlspecialchars($itemNode['guid'])); 
						$Channelitem->addChild('itunesZXZXsubtitle',$itemNode['itunesZXZXsubtitle']); 
						$Channelitem->addChild('itunesZXZXsummary',$itemNode['itunesZXZXsummary']); 
						$Channelitem->addChild('itunesZXZXexplicit',$itemNode['itunesZXZXexplicit']); 
						$ChannelitemEnclos=$Channelitem->addChild('enclosure'); 
						$ChannelitemEnclos->addAttribute('url',$item_AudioURL); 
						$ChannelitemEnclos->addAttribute('type','audio/mpeg'); 
						$Channelitem->addChild('itunesZXZXduration',$itemNode['itunesZXZXduration']); 
						$Channelitem->addChild('pubDate',$itemNode['pubDate']); 
						$Channelitem->addChild('lastModifiedDate',$itemNode['lastModifiedDate']); 
						$Channelitem->addChild('itunesZXZXauthor',$itemNode['itunesZXZXauthor']); 
						//$Channelitem->addChild('xxxxxx',$itemNode['xxxxxx']); 
						}
			
					}
		$xmlClean = new DOMDocument(); // seems like a huge memory cost (4184 bytes), just to make it 'pretty'; used DOMDoc: http://php.net/manual/en/class.domdocument.php
		$xmlClean->loadXML($xml->asXML()); 
		$xmlClean->formatOutput = true; 
		echo str_replace('itunesZXZX','itunes:',$xmlClean->saveXML())/* .PHP_EOL.memory_get_usage().PHP_EOL */;
		/*  end DOMDoc; it's really useless, just vanity formatting... to disable, comment 4 lines above, and un-comment 1 line below:  */
		//echo str_replace('itunesZXZX','itunes:',$xml->asXML())/* .PHP_EOL.memory_get_usage().PHP_EOL */;
		}
//	}


?>