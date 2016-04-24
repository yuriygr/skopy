$(document).ready(function() {
	/*
		Share
		=========================================================
	*/
	$(document).on('click', '.share a', function(){
		if (this.id)
			$.share(this.id, { title: document.title, url: location.href });
		return false;
	});
	/*
		Toggle mobile menu
		=========================================================
	*/
	$(document).on('click', '.header-bars', function(){
		if ($(this).attr('id') == 'close') {
			$(this).attr('id', 'open');
			$('.header-menu').addClass('active');
			return false;
		}
		if ($(this).attr('id') == 'open') {
			$(this).attr('id', 'close');
			$('.header-menu').removeClass('active');
			return false;
		}
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
		Alert
		=========================================================
	*/
	$(document).on('click', '.alert .close', function(){
		$(this).parent().fadeOut();
		return false;
	});
	$('.alert').delay(5000).fadeOut();
	/*
		Dropdown
		=========================================================
	*/
	$(document).on('click', '.dropdown .dropdown_toggler', function(){
		var e = $(this).closest('.dropdown');
		return e.toggleClass('open'),
		$(document).one("click",function(){
			e.removeClass("open")
		}),!1
	});
	/*
		Ajax forms
		=========================================================
	*/
	$('.form[data-ajax=true]').submit(function( event ){
		event.preventDefault();

		var action = $(this).attr('action'),
			data   = $(this).serialize();

		$.post(action, data, function (data, status) {
			if (status == "success") {
				if (data.success) {
					$.ambiance({ message: data.success, title: 'Успех', type: 'success' });
				}
				if (data.error) {
					$.ambiance({ message: data.error, title: 'Ошибка', type: 'error' });
				}
				if (data.redirect) {
					$(location).attr('href', data.redirect);
				}
			} else {
				$.ambiance({ message: 'Непредвиденная ошабка', title: 'Ошибка', type: 'error' });
			}
		});
	});

});
