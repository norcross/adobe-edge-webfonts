jQuery(document).ready( function($) {

// **************************************************************
//  disable save on load and allow on change
// **************************************************************

	$('p#aef-submit input').attr('disabled', 'disabled');

	$('select.font-choices').change( function() {
		var font = $('select.font-choices option:selected').prop('value');
		
		if(font !== 'none' )
			$('p#aef-submit input').removeAttr('disabled');
	});

// **************************************************************
//  add another field
// **************************************************************

	$( 'input.font-clone' ).on('click', function() {
		$('p.font-choice:last').clone().insertBefore('input.font-clone');
	});

// **************************************************************
//  what, you're still here? it's over. go home.
// **************************************************************

});