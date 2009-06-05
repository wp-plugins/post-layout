<?php
/*
Plugin Name: Post Layout
Plugin URI: http://www.satollo.net/plugins/post-layout
Description: Adds HTML o javascript code before, after or in the middle of the content of pages or posts without modify the theme. For any problem or question write me: satollo@gmail.com.
Version: 2.0.7
Author: Satollo
Author URI: http://www.satollo.net
Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
*/

/*	Copyright 2008  Satollo  (email : satollo@gmail.com)

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

define('POST_LAYOUT', true);

add_filter("plugin_action_links_post-layout/plugin.php", 'pstl_plugin_action_links');
function pstl_plugin_action_links($links) 
{ 
	$settings_link = '<a href="options-general.php?page=post-layout/options.php">' . __('Settings') . '</a>'; 
	array_unshift($links, $settings_link); 
 	return $links;
}

$pstl_options = get_option('pstl');
$pstl_options['comments_count'] = 0;

$pstl_suffix = '';
if (pstl_mobile_type() != '') $pstl_suffix = '_mobile';

function pstl_request($name, $default=null)
{
    if (!isset($_REQUEST[$name])) return $default;
    if (get_magic_quotes_gpc()) return pstl_stripslashes($_REQUEST[$name]);
    else return $_REQUEST[$name];
}

function pstl_stripslashes($value)
{
    $value = is_array($value) ? array_map('pstl_stripslashes', $value) : stripslashes($value);
    return $value;
}

add_filter('comment_text', 'pstl_comment_text');
function pstl_comment_text($comment = '') 
{
    global $pstl_options;

    $pstl_options['comments_count']++;
    if ($pstl_options['comments_count'] == $pstl_options['comments_number'])
    {
		$comment .= $pstl_options['comment_last'];
    }
	if (get_comment_ID() == $_GET['cid']) return $comment . $pstl_options['comment_after'];
    
    return $comment;
}


add_action('the_content', 'pstl_the_content');
function pstl_the_content(&$content)
{
    global $pstl_options, $post;

	if (is_feed()) return $content;
    
    $pstl_disabled = get_post_meta($post->ID, 'pstl_disabled', true);
	if ($pstl_disabled) return $content;
	
    $title = get_the_title();
    $title_encoded = urlencode($title);
    $link = get_permalink();
    $link_encoded = urlencode($link);

    $mobile = pstl_mobile_type();
    $suffix = '';
    if ($mobile != '') $suffix = '_mobile';
        
    $before = '';
    $after = '';
    $more = '';

    if (is_single())
    {
        
		$pstl_options['comments_number'] = get_comments_number();
		
        $before = $pstl_options['post_before' . $suffix];
        
        //$after = get_post_meta($post->ID, 'pstl_after', true);
        //if ($after == '') $after = $pstl_options['post_after' . $suffix];
        if ($after == '') $after = $pstl_options['post_after' . $suffix];
        
        if ($pstl_options['post_more_size'] > 0) 
        {
            $length = strlen(strip_tags($content));
            if ($length >= $pstl_options['post_more_size']) $more = $pstl_options['post_more' . $suffix]; 
        }
        else 
        {
            $more = $pstl_options['post_more' . $suffix];
        }
    }
    else if (is_page())
    {
		$pstl_options['comments_number'] = get_comments_number();

        $before = $pstl_options['page_before' . $suffix];
        $after = $pstl_options['page_after' . $suffix];
    }
    else
    {
        $before = $pstl_options['home_before' . $suffix];
        $after = $pstl_options['home_after' . $suffix];
    }

    if (strpos($before, '<?') !== false) 
    {
        ob_start();
        eval('?>' . $before);
        $before = ob_get_contents();
        ob_end_clean();
    }
    
    $before = str_replace('[title]', $title, $before);
    $before = str_replace('[title_encoded]', $title_encoded, $before);
    $before = str_replace('[link]', $link, $before);
    $before = str_replace('[link_encoded]', $link_encoded, $before);
    $before = str_replace('[author_aim]', get_the_author_aim(), $before);

    if (strpos($after, '<?') !== false) 
    {
        ob_start();
        eval('?>' . $after);
        $after = ob_get_contents();
        ob_end_clean();
    }
    $after = str_replace('[title]', $title, $after);
    $after = str_replace('[title_encoded]', $title_encoded, $after);
    $after = str_replace('[link]', $link, $after);
    $after = str_replace('[link_encoded]', $link_encoded, $after);
    $after = str_replace('[related]', $related, $after);
    $after = str_replace('[author_aim]', get_the_author_aim(), $after);


    $x = strpos($content, 'id="more');
    if ($x !== false)
    {
        // span end
        $x = strpos($content, '>', $x);
        if ($x !== false)
        {
        
            if (strpos($more, '<?') !== false) 
            {
                ob_start();
                eval('?>' . $more);
                $more = ob_get_contents();
                ob_end_clean();
            }        
            $more = str_replace('[title]', $title, $more);
            $more = str_replace('[title_encoded]', $title_encoded, $more);
            $more = str_replace('[link]', $link, $more);
            $more = str_replace('[link_encoded]', $link_encoded, $more);
            $more = str_replace('[related]', $related, $more);
            $more = str_replace('[author_aim]', get_the_author_aim(), $more);

            $content = substr($content, 0, $x+1) . $more . substr($content, $x+1);
        }
    }

    return $before . $content . $after;
}

add_action('comment_form', 'pstl_comment_form', 99);
function pstl_comment_form()
{
    global $pstl_options;

	$comment = $pstl_options['comment_form'];
	echo $comment;
}

add_action('admin_menu', 'pstl_admin_menu');
function pstl_admin_menu()
{
    add_options_page('Post Layout', 'Post Layout', 'manage_options', 'post-layout/options.php');
}

add_action('edit_form_advanced', 'pstl_post_form_meta');
add_action('edit_page_form', 'pstl_post_form_meta');
function pstl_post_form_meta()
{
    global $post;
    $pstl_disabled = get_post_meta($post->ID, 'pstl_disabled', true);
    $pstl_block_1 = get_post_meta($post->ID, 'pstl_block_1', true);
    $pstl_block_1_mobile = get_post_meta($post->ID, 'pstl_block_1_mobile', true);
?>
    <div id="postpstl" class="postbox if-js-closed">
    <h3>Post Layout</h3>
    <div class="inside">
    <p class="meta-options">
        <input type="hidden" value="1" name="pstl_edit" />
    	<label for="pstl_disabled" class="selectit"> <input name="pstl_disabled" value="1" type="checkbox" <?php echo $pstl_disabled?"checked":""; ?>> Disable on this post.</label>
    </p>
    </div>
    </div>
<?php
}


add_action('edit_post', 'pstl_post_meta');
add_action('publish_post', 'pstl_post_meta');
add_action('save_post', 'pstl_post_meta');
add_action('edit_page_form', 'pstl_post_meta');	
function pstl_post_meta($post_id) 
{
    
    if (isset($_POST['pstl_edit'])) 
    {
        if (isset($_POST['pstl_disabled']))
        {
            // Se esiste gi� il true finale evita l'add
            add_post_meta($post_id, 'pstl_disabled', '1', true);
        }
	    else 
	    {
            delete_post_meta($post_id, 'pstl_disabled');
        }
        
        $tmp = trim(pstl_request('pstl_block_1'));
        if ($tmp != '')
        {
            // Se esiste gi� il true finale evita l'add
            add_post_meta($post_id, 'pstl_block_1', $tmp, true);
        }
	    else 
	    {
            delete_post_meta($post_id, 'pstl_block_1');
        }
    }
}
        
add_shortcode('localblock', 'pstl_localblock_call');
function pstl_localblock_call($attrs, $content=null)
{
    global $pstl_options, $post, $pstl_suffix;
  
    $id = $attrs['id'];
    if (!$id) $id = '1';
    
    $buffer = get_post_meta($post->ID, 'pstl_block_' . $id . $pstl_suffix, true);
    
    if (strpos($buffer, '<?') !== false) 
    {
        ob_start();
        eval('?>' . $more);
        $buffer = ob_get_contents();
        ob_end_clean();
    }   
    return $buffer;    
}

add_shortcode('globalblock', 'pstl_globalblock_call');
function pstl_globalblock_call($attrs, $content=null)
{
    global $pstl_options, $post, $pstl_suffix;
  
    $id = $attrs['id'];
    if (!$id) $id = '1';
    
    $buffer = $pstl_options['block_' . $id . $pstl_suffix];
    
    if (strpos($buffer, '<?') !== false) 
    {
        ob_start();
        eval('?>' . $more);
        $buffer = ob_get_contents();
        ob_end_clean();
    }   
    return $buffer;    
}

/* Detect if the user agent is one of a mobile device (if the options are setted to do this detection). Returns 'pda' for a mobile device and 
'iphone' for a iphone. Returns an empty string for common desktop browsers.
*/
function pstl_mobile_type()
{
    global $pstl_options;
    
    if (!$pstl_options['mobile']) return '';
    
    $hyper_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    $hyper_agents = explode(',', "elaine/3.0,iphone,ipod,palm,eudoraweb,blazer,avantgo,windows ce,cellphone,small,mmef20,danger,hiptop,proxinet,newt,palmos,netfront,sharp-tq-gx10,sonyericsson,symbianos,up.browser,up.link,ts21i-10,mot-v,portalmmm,docomo,opera mini,palm,handspring,nokia,kyocera,samsung,motorola,mot,smartphone,blackberry,wap,playstation portable,lg,mmp,opwv,symbian,epoc");
    foreach ($hyper_agents as $hyper_a)
    {
        if (strpos($hyper_agent, $hyper_a) !== false)
        {
            if (strpos($hyper_agent, 'iphone') || strpos($hyper_agent, 'ipod'))
            {
                return 'iphone';
            }
            else
            {
                return 'pda';
            }
        }
    }
    return '';
}
?>
