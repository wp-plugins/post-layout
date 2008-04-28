<?php
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

if (isset($_POST['save']))
{
  $options = pstl_request('options');
  update_option('pstl', $options);
}
else
{
    $options = get_option('pstl');
}
?>
<div class="wrap">
<form method="post">

<h2>Post Layout</h2>

<? if (defined('BOOKMARK_ME')) { ?>
<p>You have bookmark me installed. Use the tag [bookmark_me] to print the bookmark buttons!</p>
<? } ?>

<p>Read to the end of the page for tags to be used inside the texts.</p>

<h3>Home and categories</h3>
<table class="form-table">
<? pstl_field_textarea('home_before', 'Code before the post'); ?>
<? pstl_field_textarea('home_after', 'Code after the post'); ?>
</table>

<h3>Single post</h3>
<table class="form-table">
<? pstl_field_checkbox('post_home', 'Use the home code'); ?>
<? pstl_field_textarea('post_before', 'Code before the post'); ?>
<? pstl_field_textarea('post_more', 'Code after the more break'); ?>
<? pstl_field_textarea('post_after', 'Code after the post'); ?>
</table>

<h3>Page</h3>
<table class="form-table">
<? pstl_field_checkbox('page_post', 'Use the post codes'); ?>
<? pstl_field_textarea('page_before', 'Code before the page'); ?>
<? pstl_field_textarea('page_after', 'Code after the page'); ?>
</table>

<h3>Comments</h3>
<table class="form-table">
<? pstl_field_textarea('comment_form', 'Code after comment form'); ?>
<? pstl_field_textarea('comment_last', 'Code after the last comment'); ?>
</table>

<p>In the code text you can use:
<ul>
<li>[title] - will be replaced by the post title</li>
<li>[title_encoded] - will be replaced by the post title encoded to be used as an URL parameter</li>
<li>[link] - will be replaced by the post permalink</li>
<li>[link_encoded] - will be replaced by the post permalink encoded to be used as an URL parameter</li>
</ul>

<p class="submit"><input type="submit" name="save" value="Save"></p>
</form>
</div>
