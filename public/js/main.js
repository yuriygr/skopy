$(document).ready(function() {
	/* Ctrl
	========================================================= */
	var ctrl = false;
	$(window).keydown(function(e) {
		if (e.keyCode == 17) ctrl = true;
	})
	.keyup(function(e) {
		if (e.keyCode == 17) ctrl = false;
	})
	.blur(function() {
		ctrl = false;
	});
	/* Запрет флуда
	========================================================= */
	var pressed = false;
	$('[data-stop-repit]').keydown(function(e) {
	    if (pressed)
	        e.preventDefault();
	    pressed = true;
	});
	$('[data-stop-repit]').keyup(function(e) {
	    pressed = false;
	});
	/*
		Scroll To Top
		=========================================================
	*/
	$(document).on('click', '#rise', function(){
		$('html, body').animate({'scrollTop': 0});
		return false;
	});
	/*
		Ajax forms
		=========================================================
	*/
	$('.form[data-ajax=true]').submit(function( event ) {
		event.preventDefault();

		var action = $(this).attr('action'),
			data   = $(this).serialize();

		$.post(action, data)
		.then(function(data, status) {
			if (status == "success") {
				$.ambiance({ message: data.message, type: data.status });

				if (data.redirect) {
					$(location).attr('href', data.redirect);
				}
			} else {
				$.ambiance({ message: 'Some error', type: 'error' });
			}
			console.log(data);
		})
		.fail(function(error) {
			$.ambiance({ message: error, type: 'error' });
		})
		.always(function() {
			console.log( "finished" );
		});
	});
});
