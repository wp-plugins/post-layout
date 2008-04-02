<?php
/*
Plugin Name: Post Layout Widget
Plugin URI: http://www.satollo.com/english/wordpress/post-layout
Description: A text widget that works like the text of the post layout plugin
Author: Satollo
Version: 1.0
Author URI: http://www.satollo.com

	My Widget is released under the GNU General Public License (GPL)
	http://www.gnu.org/licenses/gpl.txt

	This is a WordPress plugin (http://wordpress.org) and widget
	(http://automattic.com/code/widgets/).
*/

// We're putting the plugin's functions in one big function we then
// call at 'plugins_loaded' (add_action() at bottom) to ensure the
// required Sidebar Widget functions are available.
function pstl_widget_init() {

	// Check to see required Widget API functions are defined...
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return; // ...and if not, exit gracefully from the script.

	// This function prints the sidebar widget--the cool stuff!
	function pstl_widget($args) {

		// $args is an array of strings which help your widget
		// conform to the active theme: before_widget, before_title,
		// after_widget, and after_title are the array keys.
		extract($args);

		// Collect our widget's options, or define their defaults.
		$options = get_option('pstl_widget');
		$title = empty($options['title']) ? 'My Widget' : $options['title'];
		$text = empty($options['text']) ? 'Hello World!' : $options['text'];
        $text = str_replace('[author_aim]', get_the_author_aim(), $text);

 		// It's important to use the $before_widget, $before_title,
 		// $after_title and $after_widget variables in your output.
		echo $before_widget;
		echo $before_title . $title . $after_title;
		echo $text;
		echo $after_widget;
	}

	// This is the function that outputs the form to let users edit
	// the widget's title and so on. It's an optional feature, but
	// we'll use it because we can!
	function pstl_widget_control() {

		// Collect our widget's options.
		$options = get_option('pstl_widget');

		// This is for handing the control form submission.
		if ( $_POST['pstl_widget_submit'] ) {
			// Clean up control form submission options
			$newoptions['title'] = stripslashes($_POST['pstl_widget_title']);
			$newoptions['text'] = stripslashes($_POST['pstl_widget_text']);
		}

		// If original widget options do not match control form
		// submission options, update them.
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('pstl_widget', $options);
		}

		// Format options as valid HTML. Hey, why not.
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$text = htmlspecialchars($options['text'], ENT_QUOTES);

// The HTML below is the control form for editing options.
?>
		<div>
		<input type="text" id="pstl_widget_title" name="pstl_widget_title" style="width: 100%" value="<?php echo $title; ?>" />
		<textarea id="pstl_widget_text" name="pstl_widget_text" style="width: 100%; height: 150px;"><?php echo $text; ?></textarea>
		<input type="hidden" name="pstl_widget_submit" id="pstl_widget_submit" value="1" />
		</div>
	<?php
	// end of widget_mywidget_control()
	}

	// This registers the widget. About time.
	register_sidebar_widget('Post Layout Widget', 'pstl_widget');

	// This registers the (optional!) widget control form.
	register_widget_control('Post Layout Widget', 'pstl_widget_control');
}

// Delays plugin execution until Dynamic Sidebar has loaded first.
add_action('plugins_loaded', 'pstl_widget_init');
?>