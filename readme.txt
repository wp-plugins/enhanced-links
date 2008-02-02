=== Enhanced Links ===
Contributors: vprat
Donate link: http://enhanced-links.vincentprat.info
Tags: links, sidebar, navigation
Requires at least: 1.5.0
Tested up to: 2.3
Stable tag: 3.0.4

A plugin for wordpress which allows you to list your links in a sexier way. Very useful when you have a great number of links and categories.

== Description ==

A plugin for wordpress which allows you to list your links in a sexier way. Very useful when you have a great number of links and categories. A sample use can be found on my [links page](http://links.vincentprat.info).

[plugin home page](http://enhanced-links.vincentprat.info).

I need translators for the plugin, this is not a hard task, just a file to translate. Please contact me if you want to translate. Currently available in English and French.

This plugin is available under the GPL license, which means that it's free. If you use it for a commercial web site, if you appreciate my efforts or if you want to encourage me to develop and maintain it, please consider making a donation using Paypal, a secured payment solution. You just need to click the button on the [plugin home page](http://enhanced-links.vincentprat.info) and follow the instructions.

== Installation ==

Just install the plugin in the wordpress "wp-content/plugins/enhanced-links/" directory and activate it.

Then you need to insert some code into the side bar; To show all the link categories, you can use the following:
&lt;li&gt;&lt;h2&gt;Links&lt;/h2&gt;
&lt;?php enh_links_insert_categories(); ?&gt;
&lt;/li&gt;

To show only some link categories (in this example, only the categories with id 2 and 3), you can use:
&lt;li&gt;&lt;h2&gt;Links&lt;/h2&gt;
&lt;?php enh_links_insert_category(2); ?&gt;
&lt;?php enh_links_insert_category(3); ?&gt;
&lt;/li&gt;

Note that you cannot list the same category twice on the same page!

If you are using Widgets, you can insert the above code inside a PHP widget. Please refer to [ExecPHP Widgets](http://ottodestruct.com/blog/2006/04/09/fun-with-widgets/) documentation.

If you want to list your links in a static page, you should use the [RunPHP plugin](http://www.nosq.com/blog/2006/01/runphp-plugin-for-wordpress/) and insert the above code in a static page.

== Options ==

Check-out the options page of the plugin to set some options.

If you want to enable the scriptaculous or jQuery effects, you need to make sure that the corresponding javascript files are imported in your page. You can use the [wp-scriptaculous plugin](http://www.silpstream.com/blog/wp-scriptaculous/) in order to include scriptaculous automatically for you or do it manually in your theme (did not find any wp-jquery plugin).

== Change log ==

**v3.0.3**

[*] Bug correction: javascript problem when only inserting the links of one category.

**v3.0.1**

[*] Bug correction: on some installs, users got the "headers already sent..." message.

**v3.0**

[+] Added support for internationalization with POT files.
[+] Configuration page.
[+] Support for Scriptaculous and jQuery.

== Frequently Asked Questions ==

= Where to find the translations for the plugin? =

Visit the [repository for the locale files](http://enhanced-links-locales.vincentprat.info).

= I have a list of categories but they do not expand when I click on them =

You probably have activated jQuery or scriptaculous effects but did not import the corresponding files in the page.