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
					link += '?url='			+ this.url;
					link += '&title='		+ this.title;
				break;

				// Facebook
				case 'fb':
					link  = 'http://www.fb.com/sharer.php';
					link += '?u=' + this.url;
					link += '&t=' + this.title;
				break;

				// Twitter
				case 'tw':
					link  = 'http://twitter.com/share';
					link += '?url='			+ this.url;
					link += '&text='		+ this.title;
				break;

				// Mail
				case 'po':
					link  = 'mailto:';
					link += '?subject='			+ this.title;
					link += '&body='			+ this.url;
				break;

				default:
				break;
			};
			share.popup(link);
			return false;
		},
		popup: function(link) {
			window.open(link,'','toolbar=0,status=0,width=626,height=436');
			return false;
		}
	};

	// Кнопки поделиться
	$('.share-block a').click(function() {
		var id = $(this).attr('id');
		if (id) share.init(id);
		return false;
	});
	//Кнопка меню
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
	});
});