jQuery(document).ready( function($) {

// **************************************************************
//  disable save on load and allow on change
// **************************************************************
/*
	$('p#aef-submit input').attr('disabled', 'disabled');

	$('select.font-choices').change( function() {
		var font = $('select.font-choices option:selected').prop('value');
		
		if(font !== 'none' )
			$('p#aef-submit input').removeAttr('disabled');
	});
*/
// **************************************************************
//  add another field
// **************************************************************

	$( 'input.font-clone' ).on('click', function() {
		$('p.font-choice:last').clone().insertBefore('input.font-clone');
	});

// **************************************************************
//  call Chosen
// **************************************************************

	$('.chzn-select').chosen();

// **************************************************************
//  store options
// **************************************************************

	$('input.font-submit').click(function(event) {
	
		$('div#message').remove();

		// get the values
		font = $('p.font-choice').find('option:selected').val();

		console.log(font);

/*
		var data = {
			action: 'key_delete',
			key_kill: key_kill
		};

		jQuery.post(ajaxurl, data, function(response) {
			var obj;

			try {
				obj = jQuery.parseJSON(response);
			}
			catch(e) {
				$('input#pmm-remove .btn-clear').css('visibility', 'hidden');
				$('div#wpbody h2:first').after('<div id="message" class="error below-h2"><p>There was an error. Please try again.</p></div>');
			}

			if(obj.success === true) {
				$('input#pmm-remove .btn-clear').css('visibility', 'hidden');
				$('input.meta_key_field').val('');
				$('div#wpbody h2:first').after('<div id="message" class="updated below-h2"><p>' + obj.message + '</p></div>');
			}

			else if(obj.success === false && obj.process == 'NO_KEY_ENTERED') {
				$('input#pmm-remove .btn-clear').css('visibility', 'hidden');
				$('span#key_kill_text').after('<div id="message" class="error"><p>' + obj.message + '</p></div>');
			}

			else if(obj.success === false && obj.process == 'NO_KEYS_FOUND') {
				$('input#pmm-remove .btn-clear').css('visibility', 'hidden');
				$('div#wpbody h2:first').after('<div id="message" class="error"><p>' + obj.message + '</p></div>');
			}

			else {
				$('input#pmm-remove .btn-clear').css('visibility', 'hidden');
				$('div#wpbody h2:first').after('<div id="message" class="error below-h2"><p>There was an error. Please try again.</p></div>');
			}
		});
*/
	});

// **************************************************************
//  what, you're still here? it's over. go home.
// **************************************************************

});