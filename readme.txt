=== Post Layout ===
Tags: post, layout, theme, adsense, page, ads
Requires at least: 2.1
Tested up to: 2.5.1
Stable tag: 1.3
Donate link: http://www.satollo.com/english/donate
Contributors: satollo

Add HTML, JavaScript or PHP (new!) code before, after or in the middle of a post without modify the theme.

== Description ==

Add HTML, JavaScript or PHP (new!) code before, after or in the middle of a post without modify the theme.

The plugin is VERY useful to everyone need to add AdSense or other advertising code in the post
body (before, after or in the middle of them). The plugin injects the code without modify the theme
used and is "theme independent", so you can change the theme and the custom code you added won't stop to display.

The plugin can inject such code in the comment form, too, and after the last comment: those two positions
are useful for blogs with a lot of comments, so there will be ads even in the bottom of a long page.

Users of 2.5 version of WordPress: the upgrade probably broken your comments count - it's an issue of wordpress not of this plugin!. 
Use the plugin "Comments number restore" to solve that issue.

If you have plugin that need to modify the theme to display (ad the related post plugin) you can now call their
functions with Post Layout! No more modifications to two or three theme files!

Now integrates with Bookmark Me! Install both the plugins and discover how they go together on
http://www.satollo.com/english/wordpress/post-layout.

For any problem or question write me: satollo@gmail.com.

Do you need other features? Please mail them to me don't be shy!

Version 1.3: the widget has been removed! Pay attention if you are using it!

The plugin supports some "tags" that will e replaced bofore injecting the code in the post:

- [title], [title_encoded] the title and the title encoded to be used as a parameter in a link
- [link], [link_encoded] the permalink and the permalink encoded to be used as a parameter in a link
- [author_aim] the author aim field, I use it to change the adsense pud id in a revenue shared blog

== Installation ==

1. Put the plugin folder into [wordpress_dir]/wp-content/plugins/
2. Go into the WordPress admin interface and activate the plugin
3. Go to the options page and configure the plugin

== History ==

Version 1.3
- added the abilityto execute php code in the HTML snippets (the plugin autodetect the usage of php tags and try to exceute the code)
- removed the not well working widget (it will be reinserted in a later version, sorry for that)
- added a way to put code in the comment form and after the last comment

== Frequently Asked Questions ==

No questions have been asked.

== Screenshots ==

No screenshots are available.
