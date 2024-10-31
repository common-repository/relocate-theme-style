<?php
/*
Plugin Name: Relocate Theme Style
Plugin URI: http://signpostmarv.name/relocate-theme-style/
Description: Filters WordPress output to allow you to relocate your theme stylesheets
Version: 1.0
Author: SignpostMarv Martin
Author URI: http://signpostmarv.name/
 Copyright 2009 SignpostMarv Martin  (email : relocate-theme-style.wp@signpostmarv.name)
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
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
class relocate_theme_style
{
	const option_relocated_url = 'relocate_theme_style';
	public static function bloginfo_url($url,$show)
	{
		if($show === 'stylesheet_url' && get_option(self::option_relocated_url) !== false)
		{
			return str_replace(get_bloginfo('wpurl') . '/wp-content/themes/',get_option('relocate_theme_style'),$url);
		}
		else
		{
			return $url;
		}
	}
	public static function whitelist_options($options)
	{
		$options['general'][] = self::option_relocated_url;
		return $options;
	}
	public static function options()
	{
		$url = get_option(self::option_relocated_url);
		$url = esc_attr($url ? $url : get_bloginfo('wpurl') . '/wp-content/themes/');
		$id = self::option_relocated_url;
		echo <<<EOT
<script type="text/javascript">
if(window.location.href.indexOf('options-general') != -1){
	var desc = document.createElement('span');
	desc.className = 'description';
	desc.appendChild(document.createTextNode('Enter the address of the directory containing WordPress Themes.'));
	var input = document.createElement('input');
	input.id = '$id';
	input.name = '$id';
	input.className = 'regular-text code';
	input.value = '$url';
	var td = document.createElement('td');
	td.appendChild(input);
	td.appendChild(desc);
	var label = document.createElement('label');
	label.for = '$id';
	label.appendChild(document.createTextNode('Theme Style Root Directory (URL)'));
	var th = document.createElement('th');
	th.scope = 'row';
	th.appendChild(label);
	var tr = document.createElement('tr');
	tr.valign = 'top';
	tr.appendChild(th);
	tr.appendChild(td);
	var blog_address = document.getElementById('home').parentNode.parentNode;
	blog_address.parentNode.insertBefore(tr,blog_address.nextSibling);
}
</script>
EOT;
	}
}
add_filter('bloginfo_url','relocate_theme_style::bloginfo_url',10,2);
add_filter('whitelist_options','relocate_theme_style::whitelist_options');
add_action('admin_footer','relocate_theme_style::options');
?>