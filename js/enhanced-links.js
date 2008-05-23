function EnhancedLinksPlugin() {
	this._settings = {
		expandText		: '&raquo;',
		contractText	: '&laquo;',
		leafText		: '-',
		expandImage		: '',	
		contractImage	: '',
		leafImage		: '',
		buttonColor		: '#CC0000',
		buttonWidth		: '10px',
		buttonMargin	: '0 5px 0 0',
		isButtonAfter	: false
	};
}

$.extend(EnhancedLinksPlugin.prototype, {
	setSettings: function(newSettings) {
		$.extend(this._settings, newSettings || {});
	},
	
	getSettings: function() {
		return this._settings;
	},

	getButtonText: function(text, image) {
		var output = '';
		if (image=='') {
			output += text;
		} else {
			output += '<img src="' + image + '" alt="' + text + '" title="' + text + '" />';
		}
		
		return output;
	}
});

$(document).ready(function() {
	EnhancedLinksPlugin = new EnhancedLinksPlugin();
});

$.fn.enhancedLinks = function(args) { 
	var defaults = EnhancedLinksPlugin.getSettings(); 
	$.extend(defaults, args);
	
	return $('li.linkcat', this).each(function() {	
		var hasChildren = ($(this).children('ul').length > 0);
		var button = '';
		
		// Make button text
		if (hasChildren) {
			if (defaults.contractChildren==1) {
				button += '<span class="button is_expanded" style="cursor: pointer;">';
				button += enhancedCategoriesPlugin.getButtonText(defaults.expandText, defaults.expandImage);
				button += '</span>';
			} else {
				button += '<span class="button is_contracted" style="cursor: pointer;">';
				button += enhancedCategoriesPlugin.getButtonText(defaults.contractText, defaults.contractImage);
				button += '</span>';
			}
		} else {
			button += '<span class="button" style="">';
			button += enhancedCategoriesPlugin.getButtonText(defaults.leafText, defaults.leafImage);
			button += '</span>';
		}
		
		// Add the button before or after the category
		if (defaults.isButtonAfter) {		
			if (hasChildren) {
				$(this).children('ul').before(button);
			} else {
				$(this).append(button);
			}			
		} else {
			$(this).prepend(button);
		}
		
		// Behaviour of the category
		$(this)
			.css({listStyleType: 'none'})
			.children('span.button, span.link-cat-title')
				.css({ 	width: 	defaults.buttonWidth, 
						margin: defaults.buttonMargin, 
						color: 	defaults.buttonColor 
					})
				.click(function() {
						$(this).siblings('ul').slideToggle();
							
						if ($(this).hasClass('link-cat-title'))
							var buttons = $(this).siblings('span.button');
						else 
							var buttons = $(this);
							
						buttons
							.filter('span.button')
								.each(function() {
									if ($(this).hasClass('is_expanded')) {
										$(this)
											.html(enhancedCategoriesPlugin.getButtonText(defaults.contractText, defaults.contractImage))
											.removeClass('is_expanded')
											.addClass('is_contracted');
									} else {
										$(this)
											.html(enhancedCategoriesPlugin.getButtonText(defaults.expandText, defaults.expandImage))
											.removeClass('is_contracted')
											.addClass('is_expanded');
									}
									return this;
								});
					});
					
		$(this).children('ul')
			.css({ 	paddingLeft: defaults.buttonWidth });

		// Contract child categories if asked
		if (defaults.contractChildren==1) {
			$(this).children('ul').hide();
		}

		return this;
	});
};