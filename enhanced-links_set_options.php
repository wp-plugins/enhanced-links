<?php
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
?>

<?php 
	if (!isset($_POST['show_symbol']) 
		|| !isset($_POST['hide_symbol']) 
		|| !isset($_POST['effect'])) {
?>
<div class="wrap">
	<div id="message" class="error">
		<p><strong><?php _e('Missing option values!', ENHANCED_LINKS_I18N_DOMAIN) ?></strong></p>
	</div>
</div>
<?php
	} else {
		enh_links_set_show_symbol($_POST['show_symbol']);
		enh_links_set_hide_symbol($_POST['hide_symbol']);
		enh_links_set_is_symbol_before(isset($_POST['is_symbol_before']));
		enh_links_set_show_link_description(isset($_POST['show_link_description']));
		enh_links_set_effect($_POST['effect']);
		
		echo '<meta content="0; URL=options-general.php?page=enhanced-links/enhanced-links_options_page.php&msg=set" http-equiv="Refresh" />';
		exit;
	}
?>