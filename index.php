<!DOCTYPE html>
<html>
<head>
<?php
include_once('./_MainVars.php'); 
?>
	<title>The Missing NPR Podcasts</title>
<!-- General metadata -->
	<meta charset="utf-8">
	<meta content="Provides a podcast feed for the Morning Edition and All Things Considered NPR radio programs. Not affiliated with NPR in any way, for presonal use only." name="description">
	<link href="<?php echo $FullPageURL; ?>" rel="canonical">
	<link href="/_favicon/favicon-16x16.png" rel="icon" sizes="16x16" type="image/png">
	<link href="/_favicon/favicon-32x32.png" rel="icon" sizes="32x32" type="image/png">
	<link href="/_favicon/favicon-96x96.png" rel="icon" sizes="96x96" type="image/png">
	<link href="/_favicon/favicon-120x120.png" rel="icon" sizes="120x120" type="image/png">
	<link href="/_favicon/favicon.png" rel="icon" sizes="512x512" type="image/png">
<!-- Stuff for Facebook -->
	<meta content="en_us" property="og:locale">
	<meta content="The Missing NPR Podcasts" property="og:title">
	<meta content="Provides a podcast feed for the Morning Edition and All Things Considered NPR radio programs. Not affiliated with NPR in any way, for presonal use only." property="og:description">
	<meta content="The Missing NPR Podcasts" property="og:site_name">
	<meta content="<?php echo $FullPageURL; ?>" property="og:url">
	<meta content="<?php echo $FullPageURL; ?>_favicon/favicon.png" property="og:image">
<!-- CSS -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i&amp;subset=latin-ext" rel="stylesheet">
	<link href="https://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/css/bootstrap.no-responsive.no-icons.min.css" rel="stylesheet" type="text/css">
	<!-- <link href="js/jquery-ui-1.12.1.custom/jquery-ui.css" rel="stylesheet"> -->
	<link rel="stylesheet" href="__MainCSS.css">
<!-- js -->
	<!-- <script src="jquery-1.12.4.js"></script> -->
	<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
	<script src="__MyJS.js"></script>
<!-- Google Analytics gets injected here -->
	<script type="text/javascript" async="" src="https://www.google-analytics.com/ga.js">
	</script>
	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', "<?php echo $YourUA; ?>"]);
		
		_gaq.push(['_setDomainName', "<?php echo rtrim($YourPageURL,'/'); ?>"]);
		
		
		_gaq.push(['_trackPageview']);
		_gaq.push(['_trackPageLoadTime']);
		
		(function() {
		  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
</head>
<body>
<!-- GitHub ribbon -->
<a class="ribbon" href="https://github.com/petersphilo/MissingNPRPodcasts-phpPort" target="_blank"> <img alt="Fork me on GitHub" src="https://s3.amazonaws.com/github/ribbons/forkme_right_red_aa0000.png"> </a> 
<div class="container">
<!-- "Hero Unit" (big grey box) -->
	<div class="hero-unit">
		<h1 class="centered">
			The Missing NPR Podcasts <br>
			zConsulting.net
		</h1>
		<p>
			<br>
		</p>
		<p>
			<strong>Please note that this site is a <a href='https://github.com/petersphilo/MissingNPRPodcasts-phpPort'>php fork</a> of the original <a href="https://github.com/jetheis/MissingNPRPodcasts" target="_blank">MissingNPRPodcasts</a>.<br>
			<br>
			Please use the 'real' one instead of this one</strong>
		</p>
		<p>
		</p>
	</div>
<!-- Text below the hero unit -->
	<p>
	</p>
	<p>
		Because this website essentially circumvents NPR's business decision to encourage listeners to tune in to member stations instead of simply downloading program content, if you use this site as a complete replacememt for your member station, you are essentially hurting your local member station's business. <strong>Please don't do that</strong>. This site is entirely meant to provide a supplemental way to catch up on programs you missed when you aren't near a computer. No matter how you use this site, please remember to give to <a href="http://www.npr.org/stations/" target="_blank">your local member station</a> to make it possible for these programs to stay on the air.
	</p>
	<p>
	</p>
	<p>
	</p>
	<p>
		Access to data from the NPR API requires an API key. Instead of just using mine and pelting NPR with requests for the same key, I've designed the site to use a key for each memeber. This means you'll need to <a href="http://www.npr.org/templates/reg/" target="_blank">get an API key</a> from NPR (they're free), and enter it in the field below to generate your subscription links. Don't worry; the site won't store your key or use it for any purpose other than making a request for program content, rearranging that content (which it also won't store), and giving it to you. If you'd like to see how this all works, check out <a href="https://github.com/jetheis/MissingNPRPodcasts" target="_blank">the source code</a>.
	</p>
	<p>
	</p>
	<p>
	</p>
	<p>
		Once you've got your API key, enter it below to generate your podcast links. Remember to read the <a href="http://www.npr.org/api/apiterms.php" target="_blank">API terms of use</a> and make sure you're using the output of the NPR API as it was intended (personal, noncommercial, etc.).
	</p>
	<p>
	</p>
	<form class="form-horizontal" id="apiForm">
<!-- API key field -->
		<div class="control-group">
			<label class="control-label">API Key:</label> 
			<div class="controls">
				<input id="apiKeyField" spellcheck="false" type="text">
				<button class="btn btn-primary" id="apiKeyCheckButton" type="button">Validate API Key</button> 
			</div>
		</div>
<!-- Hidden until after validation -->
		<div class="subscriptions hidden">
<!-- Morning Edition links -->
			<div class="control-group">
				<label class="control-label">Morning Edition:</label> 
				<div class="controls">
					<a class="btn btn-success" id="morningEditionITunesButton" >Subscribe in iTunes</a> <a class="btn btn-warning" id="morningEditionRssButton" >Raw RSS</a> 
				</div>
			</div>
<!-- All Things Considered links -->
			<div class="control-group">
				<label class="control-label">All Things Considered:</label> 
				<div class="controls">
					<a class="btn btn-success" id="allThingsConsideredITunesButton" >Subscribe in iTunes</a> <a class="btn btn-warning" id="allThingsConsideredRssButton" >Raw RSS</a> 
				</div>
			</div>
<!-- All Things Considered links -->
			<div class="control-group">
				<label class="control-label">Weekend Edition Saturday:</label> 
				<div class="controls">
					<a class="btn btn-success" id="weekendSaturdayITunesButton" >Subscribe in iTunes</a> <a class="btn btn-warning" id="weekendSaturdayRssButton" >Raw RSS</a> 
				</div>
			</div>
<!-- All Things Considered links -->
			<div class="control-group">
				<label class="control-label">Weekend Edition Sunday:</label> 
				<div class="controls">
					<a class="btn btn-success" id="weekendSundayITunesButton" >Subscribe in iTunes</a> <a class="btn btn-warning" id="weekendSundayRssButton" >Raw RSS</a> 
				</div>
			</div>
		</div>
	</form>
</div>
<!-- JavaScript -->

</body>
</html>
