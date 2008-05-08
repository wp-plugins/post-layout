<?php
/*
Plugin Name: Post Layout
Plugin URI: http://www.satollo.com/english/wordpress/post-layout
Description: Adds HTML o javascript code before, after or in the middle of the content of pages or posts without modify the theme. For any problem or question write me: satollo@gmail.com.
Version: 1.3.1
Author: Satollo
Author URI: http://www.satollo.com
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

$pstl_options = get_option('pstl');
$pstl_options['comments_count'] = 0;

add_filter('comment_text', 'pstl_comment_text');
function pstl_comment_text($comment = '') 
{
    global $pstl_options;
    //echo $pstl_options['comments_count'] . '/' . $pstl_options['comments_number'];
    $pstl_options['comments_count']++;
    if ($pstl_options['comments_count'] == $pstl_options['comments_number'])
    {
        return $comment . $pstl_options['comment_last'];
    }
    
    return $comment;
}


add_action('the_content', 'pstl_the_content');
function pstl_the_content(&$content)
{
    global $pstl_options;

	if (is_feed()) return $content;
	
    $title = get_the_title();
    $title_encoded = urlencode($title);
    $link = get_permalink();
    $link_encoded = urlencode($link);

    $before = $pstl_options['home_before'];
    $after = $pstl_options['home_after'];
    $more = '';

    if (is_single())
    {
		$pstl_options['comments_number'] = get_comments_number();
        if ($pstl_options['post_home'])
        {
            $before = $pstl_options['home_before'];
            $after = $pstl_options['home_after'];
        }
        else
        {
            $before = $pstl_options['post_before'];
            $after = $pstl_options['post_after'];
        }
        $more = $pstl_options['post_more'];
    }
    else if (is_page())
    {
		$pstl_options['comments_number'] = get_comments_number();
        if ($pstl_options['post_home'])
        {
            $before = $pstl_options['home_before'];
            $after = $pstl_options['home_after'];
        }
        else if ($pstl_options['page_post'])
        {
            $before = $pstl_options['post_before'];
            $after = $pstl_options['post_after'];
        }
        else
        {
            $before = $pstl_options['page_before'];
            $after = $pstl_options['page_after'];
        }
    }
    else
    {
        $before = $pstl_options['home_before'];
        $after = $pstl_options['home_after'];
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
    if (defined('BOOKMARK_ME')) $before = str_replace('[bookmark_me]', bookmark_me(), $before);

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
    if (defined('BOOKMARK_ME')) $after = str_replace('[bookmark_me]', bookmark_me(), $after);


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
            if (defined('BOOKMARK_ME')) $more = str_replace('[bookmark_me]', bookmark_me(), $more);

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


add_action('admin_head', 'pstl_admin_head');
function pstl_admin_head()
{
    add_options_page('Post Layout', 'Post Layout', 'manage_options', 'post-layout/options.php');
}

?>
