/*!
 * Share - Share Plugin for jQuery
 * Version 1.0.0
 * @requires jQuery v2.1.4
 *
 * Copyright (c) 2016 Yuriy Grinev
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 */
(function($) {
	$.fn.share = function(social, options) {
		
		
		var defaults = {
			title: null,
			url: null
		};

		var options = $.extend(defaults, options);

		this.popup = function(link) {
			window.open(link,'','toolbar=0,status=0,width=626,height=436');
			return false;
		}

		// InContact
		if (social == 'vk') {
			link  = 'http://vk.com/share.php';
			link += '?url='		+ options['url'];
			link += '&title='	+ options['title'];
			this.popup(link);
		}

		// Telegram
		if (social == 'tg') {
			link  = 'https://telegram.me/share/url';
			link += '?url='		+ options['url'];
			link += '&text='	+ options['title'];
			this.popup(link);
		}
		
		// Facebook
		if (social == 'fb') {
			link  = 'http://www.fb.com/sharer.php';
			link += '?u=' 		+ options['url'];
			link += '&t=' 		+ options['title'];
			this.popup(link);
		}

		// Twitter
		if (social == 'tw') {
			link  = 'http://twitter.com/share';
			link += '?url='		+ options['url'];
			link += '&text='	+ options['title'];
			this.popup(link);
		}

		// Tumblr
		if (social == 'tm') {
			link  = 'http://tumblr.com/share/link';
			link += '?url='		+ options['url'];
			link += '&name='	+ options['title'];
			this.popup(link);
		}

		// Mail
		if (social == 'po') {
			link  = 'mailto:';
			link += '?subject='	+ options['title'];
			link += '&body='	+ options['url'];
			this.popup(link);
		}

		return this;
	};
	$.share = $.fn.share;
})(jQuery);