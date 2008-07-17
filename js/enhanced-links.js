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

jQuery.extend(EnhancedLinksPlugin.prototype, {
	setSettings: function(newSettings) {
		jQuery.extend(this._settings, newSettings || {});
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

jQuery(document).ready(function() {
	EnhancedLinksPlugin = new EnhancedLinksPlugin();
});

jQuery.fn.enhancedLinks = function(args) { 
	var defaults = EnhancedLinksPlugin.getSettings(); 
	jQuery.extend(defaults, args);
	
	return jQuery('li.linkcat', this).each(function() {	
		var hasChildren = (jQuery(this).children('ul').length > 0);
		var button = '';
		
		// Make button text
		if (hasChildren) {
			if (defaults.contractChildren==1) {
				button += '<span class="button is_expanded" style="cursor: pointer;">';
				button += EnhancedLinksPlugin.getButtonText(defaults.expandText, defaults.expandImage);
				button += '</span>';
			} else {
				button += '<span class="button is_contracted" style="cursor: pointer;">';
				button += EnhancedLinksPlugin.getButtonText(defaults.contractText, defaults.contractImage);
				button += '</span>';
			}
		} else {
			button += '<span class="button" style="">';
			button += EnhancedLinksPlugin.getButtonText(defaults.leafText, defaults.leafImage);
			button += '</span>';
		}
		
		// Add the button before or after the category
		if (defaults.isButtonAfter) {		
			if (hasChildren) {
				jQuery(this).children('ul').before(button);
			} else {
				jQuery(this).append(button);
			}			
		} else {
			jQuery(this).prepend(button);
		}
		
		// Behaviour of the category
		jQuery(this)
			.css({listStyleType: 'none'})
			.children('span.button, span.link-cat-title')
				.css({ 	width: 	defaults.buttonWidth, 
						margin: defaults.buttonMargin, 
						color: 	defaults.buttonColor 
					})
				.click(function() {
						jQuery(this).siblings('ul').slideToggle();
							
						if (jQuery(this).hasClass('link-cat-title'))
							var buttons = jQuery(this).siblings('span.button');
						else 
							var buttons = jQuery(this);
							
						buttons
							.filter('span.button')
								.each(function() {
									if (jQuery(this).hasClass('is_expanded')) {
										jQuery(this)
											.html(EnhancedLinksPlugin.getButtonText(defaults.contractText, defaults.contractImage))
											.removeClass('is_expanded')
											.addClass('is_contracted');
									} else {
										jQuery(this)
											.html(EnhancedLinksPlugin.getButtonText(defaults.expandText, defaults.expandImage))
											.removeClass('is_contracted')
											.addClass('is_expanded');
									}
									return this;
								});
					});
					
		jQuery(this).children('ul')
			.css({ 	paddingLeft: defaults.buttonWidth });

		// Contract child categories if asked
		if (defaults.contractChildren==1) {
			jQuery(this).children('ul').hide();
		}

		return this;
	});
};