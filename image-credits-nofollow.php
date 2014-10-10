<?php
/*
Plugin Name: Image Credits Nofollow
Plugin URI: http://apasionados.es
Description: Adds credits to the media uploads: Source and source URL. URLs are nofollow by default, but you have the option to follow them. With a shortcode and various options to display image credits in the posts.
Version: 1.1
Author: Apasionados.es
Author URI: http://apasionados.es
License: GPLv3
Text Domain: image-credits-nofollow

# The code in this plugin is free software; you can redistribute the code aspects of
# the plugin and/or modify the code under the terms of the GNU Lesser General
# Public License as published by the Free Software Foundation; either
# version 3 of the License, or (at your option) any later version.

# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
# EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
# MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
# NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
# LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
# OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
# WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
#
# See the GNU lesser General Public License for more details.
*/

$plugin_header_translate = array( __('Image Credits Nofollow', 'ap_sitelinks_search_box'), __('Adds credits to the media uploads: Source and source URL. URLs are nofollow by default, but you have the option to follow them. With a shortcode and various options to display image credits in the posts.', 'ap_sitelinks_search_box') );

define('IMAGE_CREDITS_SEP', get_option('image-credits-nofollow_sep', ',&#32;'));
define('IMAGE_CREDITS_BEFORE', get_option('image-credits-nofollow_before', '<p class="image-credits">' . __('Image Credits', 'image-credits-nofollow') . ':&#32;'));
define('IMAGE_CREDITS_AFTER', get_option('image-credits-nofollow_after', '</p>'));

define('IMAGE_CREDIT_BEFORE_CONTENT', 'before');
define('IMAGE_CREDIT_AFTER_CONTENT', 'after');


class ImageCreditsNofollowPlugin {

	function __construct() {
		add_action('init', array($this, 'init'));
		add_action('admin_menu', array(&$this, 'admin_init'));
	}

	function init() {
		// Make plugin available for translation
		// Translations can be filed in the /languages/ directory
		add_filter('load_textdomain_mofile', array(&$this, 'smarter_load_textdomain'), 10, 2);
		load_plugin_textdomain('image-credits-nofollow', false, dirname(plugin_basename(__FILE__)) . '/languages/' );

		// Manage additional media fields
		add_filter('attachment_fields_to_edit', array($this, 'add_fields' ), 10, 2);
		add_filter('attachment_fields_to_save', array($this, 'save_fields' ), 10 , 2);

		if (!is_admin()) {
			// Shortcode
			add_shortcode('image-credits', array($this, 'credits_shortcode'));

			if ($this->display_option(IMAGE_CREDIT_BEFORE_CONTENT) ||
					$this->display_option(IMAGE_CREDIT_AFTER_CONTENT)) {
				add_filter('the_content', array($this, 'filter_content'), 0);
			}
		}
	}

	function display_option($option) {
		$options = get_option('image-credits-nofollow_display', array());
		if (!is_array($options)) $options = array($options);
		return in_array($option, $options);
	}

	function enqueue_scripts() {
		wp_enqueue_style('image-credits-nofollow');
		wp_enqueue_script('image-credits-nofollow');
	}

	function filter_attachment_image_attributes($attr, $attachment) {
		$attr['class'] = $attr['class'] . ' wp-image-' . $attachment->ID;
		return $attr;
	}

	function admin_init() {
		require_once 'class-admin.php';
		$this->admin = new BetterImageCreditsAdmin($this);
	}

	function smarter_load_textdomain($mofile, $domain) {
		if ($domain == 'image-credits-nofollow' && !is_readable($mofile)) {
			extract(pathinfo($mofile));
			$pos = strrpos($filename, '_');

			if ($pos !== false) {
				# cut off the locale part, leaving the language part only
				$filename = substr($filename, 0, $pos);
				$mofile = $dirname . '/' . $filename . '.' . $extension;
			}
		}

		return $mofile;
	}

	function add_fields($form_fields, $post) {
		$form_fields['credits_source'] = array(
				'label' => __( 'Credits', 'image-credits-nofollow' ),
				'input' => 'text',
				'value' => get_post_meta($post->ID, '_wp_attachment_source_name', true) /*,
				'helps' => __( 'Source name of the image.', 'image-credits-nofollow' )*/
		);

		$form_fields['credits_link'] = array(
				'label' => __( 'Link', 'image-credits-nofollow' ),
				'input' => 'text',
				'value' => get_post_meta($post->ID, '_wp_attachment_source_url', true) /*,
				'helps' => __( 'URL where the original image was found.', 'image-credits-nofollow' )*/
		);

		$source_dofollow = (bool) get_post_meta($post->ID, '_wp_attachment_source_dofollow', true);
		$form_fields['source_dofollow'] = array(
			'label' => __( 'Follow source URL?', 'image-credits-nofollow' ),
			'input' => 'html',
			'html' => '<input alt="' . $source_dofollow . '" type="checkbox" id="attachments-' . $post->ID . '-source_dofollow" name="attachments[' . $post->ID . '][source_dofollow]" value="1"' . ( $source_dofollow ? ' checked="checked"' : '' ) . ' />',
			'value' => $source_dofollow,
			'helps' => __( 'Select to make source link dofollow. By default it\'s nofollow. ', 'image-credits-nofollow' )
		);

		return $form_fields;
	}

	function save_fields($post, $attachment) {
		if (isset($attachment['credits_source'])) {
			$credits_source = get_post_meta($post['ID'], '_wp_attachment_source_name', true);

			if ($credits_source != esc_attr($attachment['credits_source'])) {
				if (empty($attachment['credits_source'])) {
					delete_post_meta($post['ID'], '_wp_attachment_source_name');
				} else {
					update_post_meta($post['ID'], '_wp_attachment_source_name', esc_attr($attachment['credits_source']));
				}
			}
		}

		if (isset($attachment['credits_link'])) {
			$credits_link = get_post_meta($post['ID'], '_wp_attachment_source_url', true);

			if ($credits_link != esc_url( $attachment['credits_link'])) {
				if (empty($attachment['credits_link'])) {
					delete_post_meta($post['ID'], '_wp_attachment_source_url');
				} else {
					update_post_meta($post['ID'], '_wp_attachment_source_url', esc_url( $attachment['credits_link']));
				}
			}
		}

		if( isset($attachment['source_dofollow']) ){
			update_post_meta($post['ID'], '_wp_attachment_source_dofollow', $attachment['source_dofollow']);
		} else {
			update_post_meta($post['ID'], '_wp_attachment_source_dofollow', 0);
		}

		return $post;

	}

	function get_image_credits() {
		global $post;
		$attachment_ids = array();
		$credits = array();

		// First check for post thumbnail and save its ID in an array
		if (function_exists('has_post_thumbnail') && has_post_thumbnail($post->ID)) {
			$attachment_ids[] = get_post_thumbnail_id($post->ID);
		}

		// Next look in post content and check for instances of wp-image-[digits]
		if (preg_match_all('/wp-image-(\d+)/i', $post->post_content, $matches)) {
			foreach ($matches[1] as $id) {
				if (!in_array($id, $attachment_ids)) {
					$attachment_ids[] = $id;
				}
			}
		}

		// Go through all our attachments IDs and generate credits
		foreach ($attachment_ids as $id) {
			$credit_source = esc_attr(get_post_meta($id, '_wp_attachment_source_name', true));
			$credit_link = esc_url(get_post_meta($id, '_wp_attachment_source_url', true));
			$source_dofollow = esc_attr( get_post_meta( $id, '_wp_attachment_source_dofollow', true ) );

			if (!empty($credit_source)) {
				if (empty($credit_link)) {
					$credits[] = $credit_source;
				} else {
					if ( ( $source_dofollow == true ) || ( $source_dofollow == 1 ) ) {
						$credits[] = '<a href="' . $credit_link . '" target="_blank">' . $credit_source . '</a>';					
					} else {
						$credits[] = '<a href="' . $credit_link . '" rel="nofollow" target="_blank">' . $credit_source . '</a>';
					}
				}
			}
		}

		return array_unique($credits);
	}

	function credits_shortcode($atts) {
		extract(shortcode_atts(array(
				'sep' => IMAGE_CREDITS_SEP,
				'before' => IMAGE_CREDITS_BEFORE,
				'after'  => IMAGE_CREDITS_AFTER,
		), $atts, 'image-credits'));

		return $this->the_image_credits($sep, $before, $after);
	}

	function the_image_credits($sep=IMAGE_CREDITS_SEP, $before=IMAGE_CREDITS_BEFORE, $after=IMAGE_CREDITS_AFTER) {
		$credits = $this->get_image_credits();

		if (!empty($credits)) {
			$credits = implode($sep, $credits);
			return $before . $credits. $after;;
		}

		return '';
	}

	function filter_content($content) {
		if ( is_single() ) {
			$credits = $this->the_image_credits();
	
			if ($this->display_option(IMAGE_CREDIT_BEFORE_CONTENT)) {
				$content = $credits . $content;
			}
			if ($this->display_option(IMAGE_CREDIT_AFTER_CONTENT)) {
				$content = $content . $credits;
			}
		}	
			return $content;
	}
}

global $the_image_credits_nofollow_plugin;
$the_image_credits_nofollow_plugin = new ImageCreditsNofollowPlugin();

/**
 * Legacy template tag for compatibility with the image-credits plugin
 */
function get_image_credits($sep=IMAGE_CREDITS_SEP, $before=IMAGE_CREDITS_BEFORE, $after=IMAGE_CREDITS_AFTER) {
	the_image_credits($sep, $before, $after);
}

function the_image_credits($sep=IMAGE_CREDITS_SEP, $before=IMAGE_CREDITS_BEFORE, $after=IMAGE_CREDITS_AFTER) {
	echo get_the_image_credits($sep, $before, $after);
}

function get_the_image_credits($sep=IMAGE_CREDITS_SEP, $before=IMAGE_CREDITS_BEFORE, $after=IMAGE_CREDITS_AFTER) {
	global $the_image_credits_nofollow_plugin;
	return $the_image_credits_nofollow_plugin->the_image_credits($sep, $before, $after);
}