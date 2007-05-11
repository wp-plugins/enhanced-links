<?php
/*
Plugin Name: enhanced links
Version: 2.0.3
Plugin URI: http://www.vincentprat.info/wordpress/2006/04/13/wordpress-plugin-enhanced-links/
Description: Allows to get better control over the links listing : edit the links.template.inc file if you're not satisfied with the formatting. Please donate if you are satisfied.
Author: Vincent Prat
Author URI: http://www.vincentprat.info
*/

/*  Copyright 2006 Vincent Prat  (email : vpratfr@yahoo.fr)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/********** YOU CAN EDIT THE FOLLOWING VALUES *************/

// The symbol to display when the category can be shown, should be a string
define('SHOW_SYMBOL', '&raquo;'); 

// The symbol to display when the category can be hidden, should be a string
define('HIDE_SYMBOL', '&laquo;'); 

// Set this to true if you want to show the symbol before the category name. Else set to false.
define('IS_SYMBOL_BEFORE', true); 

// Set this to true if you want to show the link description.
define('SHOW_LINK_DESCRIPTION', false); 

// Set this to true if you want to use scriptaculous effects. You need to install the wp-scriptaculous
// plugin. See there: http://www.silpstream.com/blog/
define('USE_SCRIPTACULOUS_EFFECTS', true); 




/********** YOU SHOULD NOT NEED TO EDIT WHAT IS BELOW THIS LINE *************/
/********** IF YOU DO, BE CAREFUL ;) *************/

/**
 * show the links
 */
function enh_links_show_enhanced_links() {	
	// Get the category
	$categories = enh_links_get_categories();
	
	// Insert the javascript in the page
	enh_links_insert_javascript($categories);
	
	// Insert the categories
	enh_links_insert_html_categories($categories);
}

/**
 * Get the list of categories 
 */ 
function enh_links_get_categories() {
	global $wpdb;
	
	if (isset($wpdb->linkcategories)) {
		// For wordpress 1.5.x and 2.0.x
		return $wpdb->get_results(
			"SELECT cat_id, cat_name 
			 FROM $wpdb->linkcategories");
	} else {
		// For wordpress v2.1+
		return $wpdb->get_results(
			"SELECT cat_id, cat_name 
			 FROM $wpdb->categories WHERE link_count>0 ORDER BY cat_name");
	}
}

/**
 * Function to insert the html code for the categories and links
 */
function enh_links_insert_html_categories($categories) {
?>		
<?php
	foreach ($categories as $cat) {	
?>
		<h2><?php 
			if (IS_SYMBOL_BEFORE) {
				echo '<a id="categoryButton'. $cat->cat_id . '">' . SHOW_SYMBOL . '</a> &nbsp; ';
			}
		?><a href='javascript:expandCategory(<?php echo $cat->cat_id; ?>);' title='<?php echo __("Show/Hide links"); ?>'><?php echo $cat->cat_name ?></a><?php 
			if (!IS_SYMBOL_BEFORE) {
				echo '&nbsp;<a id="categoryButton'. $cat->cat_id . '">' . SHOW_SYMBOL . '</a>';
			}
		?>
		</h2>
		<div id="categoryContent<?php echo $cat->cat_id; ?>" style="DISPLAY:none">
			<ul>
				<?php get_links( 
					$cat->cat_id, 
					'<li>',       
					'</li>',      
					'<br/>',  
					false, 'name', SHOW_LINK_DESCRIPTION, false, -1, 1, true ); ?>
			</ul>
		</div>  	
<?php
	} 		
?>		
<?php
}

/**
 * Function to insert the javascript 
 */
function enh_links_insert_javascript($categories) {
?>
<script type="text/javascript">
	var js_categories = new Array(<?php echo count($categories); ?>);
<?php
	// Declare the JS variables
	$i = 0;
	foreach ($categories as $cat) {	
		$clean_cat_name = str_replace("'","\'",$cat->cat_name);
		echo "\tjs_categories[$i] = new Array($cat->cat_id, '$clean_cat_name', true);\n";
		$i++;
	}
?>

	function expandCategory(categoryId) {
		symbolBeforeCatName = <?php if (IS_SYMBOL_BEFORE) echo "true"; else echo "false"; ?>;
		showSymbol = <?php echo "'".SHOW_SYMBOL."'"; ?>;
		hideSymbol = <?php echo "'".HIDE_SYMBOL."'"; ?>;
		useScriptaculousEffects = <?php if (USE_SCRIPTACULOUS_EFFECTS) echo "true"; else echo "false"; ?>;
		
		for (var i=0; i<js_categories.length; i++) {
			currentId = js_categories[i][0];
			currentName = js_categories[i][1];
			currentlyHidden = js_categories[i][2];
			
			currentButton = document.getElementById('categoryButton' + currentId);
			currentContent = document.getElementById('categoryContent' + currentId);
			
			if (currentId==categoryId) {
				// Expand this category	or contract it if it was expanded before
				if (currentlyHidden) {
					if (useScriptaculousEffects) {
						new Effect.BlindDown(currentContent);
					} else {
						currentContent.style.display = 'block';
					}				
					currentButton.innerHTML = hideSymbol;
					js_categories[i][2] = false;
				} else {
					if (useScriptaculousEffects) {
						new Effect.BlindUp(currentContent);
					} else {
						currentContent.style.display = 'none';
					}			
					currentButton.innerHTML = showSymbol;
					js_categories[i][2] = true;
				} 
			} else {
				// Contract this category								
				if (currentButton.innerHTML != showSymbol) {
					if (useScriptaculousEffects) {
						new Effect.BlindUp(currentContent);
					} else {
						currentContent.style.display = 'none';
					}
					currentButton.innerHTML = showSymbol;
					js_categories[i][2] = true;
				} 
			}
		}
	}
</script>
<?php
}
?>