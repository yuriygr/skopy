$(document).ready(function() {
	var share = {
		// Задаём переменные
		title: $("title").text(),
		url: $(location).attr('href'),

		init: function(id) {		
			switch(id) {
				// InContact
				case 'vk':
					link  = 'http://vk.com/share.php';
					link += '?url='		+ this.url;
					link += '&title='	+ this.title;
				break;

				// Facebook
				case 'fb':
					link  = 'http://www.fb.com/sharer.php';
					link += '?u=' 		+ this.url;
					link += '&t=' 		+ this.title;
				break;

				// Twitter
				case 'tw':
					link  = 'http://twitter.com/share';
					link += '?url='		+ this.url;
					link += '&text='	+ this.title;
				break;

				// Tumblr
				case 'tm':
					link  = 'http://tumblr.com/share/link';
					link += '?url='		+ this.url;
					link += '&name='	+ this.title;
				break;

				// Mail
				case 'po':
					link  = 'mailto:';
					link += '?subject='	+ this.title;
					link += '&body='	+ this.url;
				break;

				default:
				break;
			};
			share.popup(link);
			console.log(link);
			return false;
		},
		popup: function(link) {
			window.open(link,'','toolbar=0,status=0,width=626,height=436');
			return false;
		}
	};
	/*
		Share
		=========================================================
	*/
	$('.share-block a').click(function() {
		var id = $(this).attr('id');
		if (id) share.init(id);
		return false;
	});
	/*
		Toggle mobile menu
		=========================================================
	*/
	$('a#bars').click(function() {
		var icon = $(this).find('i');
		if ($(icon).attr('class') == 'fa fa-bars') {
			$(icon).attr('class', 'fa fa-times');
			$('.header-menu').addClass('active');
			return false;
		}
		if ($(icon).attr('class') == 'fa fa-times') {
			$(icon).attr('class', 'fa fa-bars');
			$('.header-menu').removeClass('active');
			return false;
		}
		console.log(icon.attr('class'));
	});
	/*
		Scroll To Top
		=========================================================
	*/
	$('a#rise').click(function() {
		$('html, body').animate({'scrollTop': 0});
		return false;
	});
	/*
		Alert
		=========================================================
	*/
	$('.alert .close').click(function() {
		$(this).parent().fadeOut();
		return false;
	});
	$('.alert').delay(5000).fadeOut();
	/*
		Active page
		=========================================================
	*/
	$(function(){
		var url  = $(location).attr('pathname');
		var page = url.split("/")[1];
		$('ul li a[href="/'+page+'"]').parent().addClass('active');
		console.log(page);
	});
});
