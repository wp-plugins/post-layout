<?php

function pstl_field_text($name, $label='', $tips='', $attrs='')
{
  global $options;
  if (strpos($attrs, 'size') === false) $attrs .= 'size="30"';
  echo '<tr valign="top"><th scope="row">';
  echo '<label for="options[' . $name . ']">' . $label . '</label></th>';
  echo '<td><input type="text" ' . $attrs . ' name="options[' . $name . ']" value="' .
    htmlspecialchars($options[$name]) . '"/>';
  echo ' ' . $tips;
  echo '</td></tr>';
}

function pstl_field_checkbox($name, $label='', $tips='', $attrs='')
{
  global $options;
  echo '<tr valign="top"><th scope="row">';
  echo '<label for="options[' . $name . ']">' . $label . '</label></th>';
  echo '<td><input type="checkbox" ' . $attrs . ' name="options[' . $name . ']" value="1" ' .
    ($options[$name]!= null?'checked':'') . '/>';
  echo ' ' . $tips;
  echo '</td></tr>';
}

function pstl_field_textarea($name, $label='', $tips='', $attrs='')
{
  global $options;

  if (strpos($attrs, 'cols') === false) $attrs .= 'cols="70"';
  if (strpos($attrs, 'rows') === false) $attrs .= 'rows="5"';

  echo '<tr valign="top"><th scope="row">';
  echo '<label for="options[' . $name . ']">' . $label . '</label></th>';
  echo '<td><textarea wrap="off" ' . $attrs . ' name="options[' . $name . ']">' .
    htmlspecialchars($options[$name]) . '</textarea>';
  echo '<br /> ' . $tips;
  echo '</td></tr>';
}

function pstl_field_textarea2($name)
{
    global $options;

    echo '<textarea wrap="off" cols="45" rows="5" name="options[' . $name . ']">' . htmlspecialchars($options[$name]) . '</textarea>';
}

if (isset($_POST['save']))
{
  $options = pstl_request('options');
  if ($options['post_more_size'] == '') $options['post_more_size'] = 0;
  update_option('pstl', $options);
}
else
{
    $options = get_option('pstl');
}
?>
<style type="text/css">
.wrap textarea, .wrap input {
    font-family: monospace;
    font-size: 9pt;
}
</style>
<div class="wrap">
<form method="post">

<h2>Post Layout</h2>
<p>To have more information about this plugin or some tips on it's usage, please go to 
the <a href="http://www.satollo.com/english/wordpress/post-layout">Post Layout official page</a>.</p>

<table class="form-table">
<tr valign="top">
    <th scope="row">General options</th>
    <td>

        <input type="checkbox" name="options[mobile]" value="1" <?php echo ($options[$name]!=null?'checked':''); ?> />
        <label for="options[mobile]">Enable the mobile user agent detection</label>
        <br />
        
    </td>
</tr>
</table>

<h3>Single post</h3>
<p>The codes below will be used when a single post is showed to the users and added before, after or in the middle of the post (if a "more break" is there).</p>

<table class="form-table">
<tr valign="top">
    <th scope="row">Before the content</th>
    <td>For desktop browser<br /><?php pstl_field_textarea2('post_before'); ?></td>
    <td>For mobile devices<br /><?php pstl_field_textarea2('post_before_mobile'); ?></td>
</tr>

<tr valign="top">
    <th scope="row">In the middle of the content</th>
    <td valign="top">For desktop browser<br /><?php pstl_field_textarea2('post_more'); ?>
    Suspend this injection if post is less than <input type="text" size="6" name="options[post_more_size]" value="<?php echo htmlspecialchars($options['post_more_size']); ?>"/> characters
    </td>
    <td valign="top">For mobile devices<br /><?php pstl_field_textarea2('post_more_mobile'); ?></td>
</tr>

<tr valign="top">
    <th scope="row"><label>After the content</label></th>
    <td>For desktop browser<br /><?php pstl_field_textarea2('post_after'); ?></td>
    <td>For mobile devices<br /><?php pstl_field_textarea2('post_after_mobile'); ?></td>
</tr>

</table>

<h3>Page</h3>
<table class="form-table">
<tr valign="top">
    <th scope="row"><label>Code before the page</label></th>
    <td>For desktop browser<br /><?php pstl_field_textarea2('page_before'); ?></td>
    <td>For mobile devices<br /><?php pstl_field_textarea2('page_before_mobile'); ?></td>
</tr>
<tr valign="top">
    <th scope="row"><label>Code after the page</label></th>
    <td>For desktop browser<br /><?php pstl_field_textarea2('page_after'); ?></td>
    <td>For mobile devices<br /><?php pstl_field_textarea2('page_after_mobile'); ?></td>
</tr>
</table>

<h3>Home and tags and categories pages</h3>
<table class="form-table">
<tr valign="top">
    <th scope="row"><label>To add before the post content</label></th>
    <td>For desktop browser<br /><?php pstl_field_textarea2('home_before'); ?></td>
    <td>For mobile devices<br /><?php pstl_field_textarea2('home_before_mobile'); ?></td>
</tr>
<tr valign="top">
    <th scope="row"><label>To add before the post content</label></th>
    <td>For desktop browser<br /><?php pstl_field_textarea2('home_after'); ?></td>
    <td>For mobile devices<br /><?php pstl_field_textarea2('home_after_mobile'); ?></td>
</tr>
</table>

<!--
<h3>Blocks</h3>
<table class="form-table">
<tr valign="top">
    <th scope="row"><label>Block 1</label></th>
    <td>For desktop browser<br /><?php pstl_field_textarea2('block_1'); ?></td>
    <td>For mobile devices<br /><?php pstl_field_textarea2('block_1_mobile'); ?></td>
</tr>
<tr valign="top">
    <th scope="row"><label>Block 2</label></th>
    <td>For desktop browser<br /><?php pstl_field_textarea2('block_2'); ?></td>
    <td>For mobile devices<br /><?php pstl_field_textarea2('block_2_mobile'); ?></td>
</tr>
</table>
<p>Use the short tag [glbalblock id="x"] to insert the code anywhere in posts and pages. The x value for id has t be replaced with the block number.</p>
-->

<h3>Comments</h3>
<table class="form-table">
<?php pstl_field_textarea('comment_form', 'Code after comment form'); ?>
<?php pstl_field_textarea('comment_last', 'Code after the last comment'); ?>
<!--<?php pstl_field_textarea('comment_after', 'Code after the current comment'); ?>-->
</table>

<p>In the code text you can use:
<ul>
<li>[title] - will be replaced by the post title</li>
<li>[title_encoded] - will be replaced by the post title encoded to be used as an URL parameter</li>
<li>[link] - will be replaced by the post permalink</li>
<li>[link_encoded] - will be replaced by the post permalink encoded to be used as an URL parameter</li>
<li>[author_aim] - will be replaced by the author aim</li>
</ul>

<p class="submit"><input type="submit" name="save" value="Save"/></p>
</form>
</div>
