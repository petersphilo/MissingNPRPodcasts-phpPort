$(document).ready(function(){
	
	$('a').not('.btn').attr('target', '_blank');
	
	var storage = window.localStorage,
		storageKey = 'missingNprPodcastsApiKey';
	
	var subscriptionsContainer = $('.subscriptions');
	
	var apiKeyField = $('#apiKeyField'),
		apiKeyCheckButton = $('#apiKeyCheckButton'),
		apiForm = $('#apiForm');
	
	var morningEditionITunesButton = $('#morningEditionITunesButton'),
		morningEditionRssButton = $('#morningEditionRssButton');
	
	var allThingsConsideredITunesButton = $('#allThingsConsideredITunesButton'),
		allThingsConsideredRssButton = $('#allThingsConsideredRssButton');
	
	var weekendSundayITunesButton = $('#weekendSundayITunesButton'),
		weekendSundayRssButton = $('#weekendSundayRssButton');
	
	var weekendSaturdayITunesButton = $('#weekendSaturdayITunesButton'),
		weekendSaturdayRssButton = $('#weekendSaturdayRssButton');
	
	function attachGoogleAnalyticsEvents() {
		function clickHandler(button) { return function() { _gaq.push(['_trackEvent', button, 'click']); }; }
		morningEditionITunesButton.click(clickHandler('Morning Edition iTunes Button'));
		morningEditionRssButton.click(clickHandler('Morning Edition RSS Button'));
		allThingsConsideredITunesButton.click(clickHandler('All Things Considered iTunes Button'));
		allThingsConsideredRssButton.click(clickHandler('All Things Considered RSS Button'));
		weekendSundayITunesButton.click(clickHandler('WeekEnd Sunday iTunes Button'));
		weekendSundayRssButton.click(clickHandler('WeekEnd Sunday RSS Button'));
		weekendSaturdayITunesButton.click(clickHandler('WeekEnd Saturday iTunes Button'));
		weekendSaturdayRssButton.click(clickHandler('WeekEnd Saturday RSS Button'));
	
		apiKeyCheckButton.click(function() { _gaq.push(['_trackEvent', 'Validate API Key Button', 'click']); });
	}
	
	function attemptToRestoreKey() {
		if (storage && storage[storageKey]) {
			_gaq.push(['_trackEvent', 'API Key', 'Restored']);
			apiKeyField.val(storage[storageKey]);
			apiKeyCheckButton.click();
		}
	}
	
	function storeKey(key) {
		if (storage) {
			storage[storageKey] = key;
		}
	}
	
	function disableValidateButton() {
		apiKeyCheckButton.attr('disabled', true);
		apiKeyCheckButton.text('Validating...');
	}
	
	function enableValidateButton() {
		apiKeyCheckButton.removeAttr('disabled');
		apiKeyCheckButton.text('Validate API Key');
	}
	
	function validate(e) {
		
		e.preventDefault();
		
		disableValidateButton();
		
		var baseUrl = window.location.host + window.location.pathname,
			baseProtocol = window.location.protocol + '//',
			iTunesProtocol = 'itpc://',
			apiKey = apiKeyField.val();
		
		$.getJSON('p/?TestAPIKey=TestAPIKey&api_key=' + apiKey, null, function(data) {
			
			if (data.hasOwnProperty('validKey') && data.validKey === true) {
				
				_gaq.push(['_trackEvent', 'API Key', 'Validated']);
				
				var binder = function(name, iTunesButton, rssButton) {
					
					var commonUrl = baseUrl + 'p/' + '?api_key=' + apiKey + '&prog=' + name,
						iTunesUrl = iTunesProtocol + commonUrl,
						rssUrl = baseProtocol + commonUrl;
						
					// Apply the new URLs
					iTunesButton.attr('href', iTunesUrl);
					rssButton.attr('href', rssUrl);
				}
				
				binder('morningedition', morningEditionITunesButton, morningEditionRssButton);
				binder('allthingsconsidered', allThingsConsideredITunesButton, allThingsConsideredRssButton);
				binder('weekendsunday', weekendSundayITunesButton, weekendSundayRssButton);
				binder('weekendsaturday', weekendSaturdayITunesButton, weekendSaturdayRssButton);
				
				// Make the buttons visible
				subscriptionsContainer.removeClass('hidden');
				
				// Make sure we're scrolled to the bottom
				var body = $('body')[0];
				body.scrollTop = body.scrollHeight;
				
				// Revert to original button text
				enableValidateButton();
				
				// Store the key for use next time
				storeKey(apiKey);
				
			} else {
				
				_gaq.push(['_trackEvent', 'API Key', 'Failed to Validate']);
				
				window.alert('The API key you entered does not appear to be valid. Please double check it and try again.');
				enableValidateButton();
			}
		});
	}
	
	attachGoogleAnalyticsEvents();
	
	apiKeyCheckButton.click(validate);
	apiForm.submit(validate);
	
	// Restore an old key if one's available
	attemptToRestoreKey();
	
	
	}); 