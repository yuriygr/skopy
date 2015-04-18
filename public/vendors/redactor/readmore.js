if (!RedactorPlugins) var RedactorPlugins = {};
 
RedactorPlugins.readmore = function()
{
	return {
		init: function()
		{
			var  button = this.button.add('readmore', 'Читать далее');
			this.button.setAwesome('readmore', 'fa-minus');
			this.button.addCallback(button, this.readmore.insert);
		},
		insert: function(buttonName)
		{
			var data = $('.redactor-editor').html();
			if (!data.match(/<hr id="readmore"/gi)) {
				this.insert.html('<hr id="readmore">', false);			
			}
		}
	};
};