function EnhancedLinksPlugin() {
	this._settings = {
		expandText: '&raquo;',
		contractText: '&laquo;',
		leafText: '-',
		buttonColor: '#CC0000',
		buttonWidth: '10px',
		buttonMargin: '0 5px 0 0'
	};
}

jQuery.extend(EnhancedLinksPlugin.prototype, {
	setSettings: function(newSettings) {
		jQuery.extend(this._settings, newSettings || {});
	},
	
	getSettings: function() {
		return this._settings;
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
			button += '<span class="button" style="cursor: pointer;">';
			button += defaults.expandText;
			button += '</span>';
		} else {
			button += '<span class="button" style="">';
			button += defaults.leafText;
			button += '</span>';
		}
		
		// Add the button in front of category
		jQuery(this).prepend(button);
		
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