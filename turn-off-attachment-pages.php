<?php
/**
 * Plugin Name: Turn off attachment pages
 * Plugin URI: https://github.com/jstask82/wp-turn-off-attachment-pages/
 * Description: Disables the attachments page in media library/insertion. 301 redirects to the file itself or the page it is atteched to. Hides the description input.
 * Version: 1.0
 * Author: Aaron Kessler
 * Author URI: https://www.aaronkessler.de/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: _akdev_turn-off-attachment-pages
 **/

/***
 * 301 redirect to file or page
 */
function _akdev_redirectAttachment()
{

    global $post;

    if (is_attachment()) {
        $url = $post->post_parent ? get_permalink($post->post_parent) : wp_get_attachment_url($post->ID);
        wp_redirect($url, 301);
        exit();
    }
}
add_action('template_redirect', '_akdev_redirectAttachment', 10, 0);

/*
 * Replace the attachment page url
 */
function _akdev_replaceAttachmentLink($link, $postID)
{

    $post = get_post($postID);
    if ($post && ($post->post_type == 'attachment')) {
        $link = $post->post_parent ? get_permalink($post->post_parent) : wp_get_attachment_url($post->ID);
    }
    return $link;
}
add_filter('attachment_link', '_akdev_replaceAttachmentLink', 10, 2);

/**
 * Remove the attachment page link
 */
function _akdev_removeAttachmentPageLink($link)
{
    return;
}
add_filter('attachment_link', '_akdev_removeAttachmentPageLink');

/***
 * Hide the admin area description input
 */
function _akdev_hideDescriptionSetting()
{
    ?>
<style type="text/css">
.attachment-details [data-setting="description"] {
  display: none
}
</style>
<?php
}
add_action('admin_head', '_akdev_hideDescriptionSetting', 10, 0);
