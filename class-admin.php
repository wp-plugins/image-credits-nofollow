<?php

class BetterImageCreditsAdmin {

	function __construct($plugin) {
		$this->plugin = $plugin;
		$this->add_settings();

		add_filter('manage_media_columns', array(&$this, 'manage_media_columns'));
		add_action('manage_media_custom_column', array(&$this, 'manage_media_custom_column'), 10, 2);
	}

	function add_settings() {
		add_filter('plugin_action_links_image-credits-nofollow/image-credits-nofollow.php', array(&$this, 'add_settings_link'));
		add_submenu_page('options-general.php', __('Image Credits Options', 'image-credits-nofollow'), __('Image Credits', 'image-credits-nofollow'), 'manage_options', 'image-credits', array(&$this, 'options_page'));
		add_settings_section('default', '', '', 'image-credits');
		$this->add_settings_field('image-credits-nofollow_display', __('Display Credits', 'image-credits-nofollow'), 'add_settings_field_display');
		$this->add_settings_field('image-credits-nofollow_sep', __('Separator', 'image-credits-nofollow'), 'add_settings_field_sep');
		$this->add_settings_field('image-credits-nofollow_before', __('Before', 'image-credits-nofollow'), 'add_settings_field_before');
		$this->add_settings_field('image-credits-nofollow_after', __('After', 'image-credits-nofollow'), 'add_settings_field_after');
	}

	function add_settings_field($id, $title, $callback) {
		register_setting('image-credits', $id);
		add_settings_field($id, $title, array(&$this, $callback), 'image-credits');
	}

	function add_settings_link($links) {
		$url = site_url('/wp-admin/options-general.php?page=image-credits');
		$links[] = '<a href="' . $url . '">' . __('Settings') . '</a>';
		return $links;
	}

	function add_settings_field_display() { ?>
		<p><label><input type="checkbox" name="image-credits-nofollow_display[]" value="<?php echo IMAGE_CREDIT_BEFORE_CONTENT;?>"
			<?php checked($this->plugin->display_option(IMAGE_CREDIT_BEFORE_CONTENT)); ?>><?php
			_e('Before the content', 'image-credits-nofollow'); ?></label></p>
		<p><label><input type="checkbox" name="image-credits-nofollow_display[]" value="<?php echo IMAGE_CREDIT_AFTER_CONTENT;?>"
			<?php checked($this->plugin->display_option(IMAGE_CREDIT_AFTER_CONTENT)); ?>><?php
			_e('After the content', 'image-credits-nofollow'); ?></label></p>
		<p><em><?php _e('Choose how you want to display the image credits', 'image-credits-nofollow'); ?></em></p>
	<?php }

	function add_settings_field_sep() { ?>
		<p><input type="text" name="image-credits-nofollow_sep" id="image-credits-nofollow_sep" class="large-text code"
			value="<?php echo htmlspecialchars(IMAGE_CREDITS_SEP); ?>" /></p>
		<p><em><?php _e('HTML to separate the credits (enter leading and trailing spaces using HTML entities).', 'image-credits-nofollow'); ?></em></p><?php
	}

	function add_settings_field_before() { ?>
		<p><input type="text" name="image-credits-nofollow_before" id="image-credits-nofollow_before" class="large-text code"
			value="<?php echo htmlspecialchars(IMAGE_CREDITS_BEFORE); ?>" /></p>
		<p><em><?php _e('HTML to output before the credits (enter leading and trailing spaces using HTML entities).', 'image-credits-nofollow'); ?></em></p><?php
	}

	function add_settings_field_after() { ?>
		<p><input type="text" name="image-credits-nofollow_after" id="image-credits-nofollow_after" class="large-text code"
			value="<?php echo htmlspecialchars(IMAGE_CREDITS_AFTER); ?>" /></p>
		<p><em><?php _e('HTML to output after the credits (enter leading and trailing spaces using HTML entities).', 'image-credits-nofollow'); ?></em></p><?php
	}

	function options_page() { ?>
<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php _e('Image Credits Options', 'image-credits-nofollow'); ?></h2>
	<div id="main-container" class="postbox-container metabox-holder" style="width:75%;"><div style="margin:0 8px;">
		<div class="postbox">
			<h3 style="cursor:default;"><span><?php _e('Options', 'image-credits-nofollow'); ?></span></h3>
			<div class="inside">
				<form method="POST" action="options.php"><?php
				settings_fields('image-credits');
				do_settings_sections('image-credits');
				submit_button();
				?></form>
			</div> <!-- .inside -->
		</div> <!-- .postbox -->
	</div></div> <!-- #main-container -->

	<div id="side-container" class="postbox-container metabox-holder" style="width:24%;"><div style="margin:0 8px;">
		<div class="postbox">
			<h3 style="cursor:default;"><span><?php _e('Do you like this Plugin?', 'image-credits-nofollow'); ?></span></h3>
			<div class="inside">
				<p><?php _e('We also need volunteers to translate that plugin into more languages.', 'image-credits-nofollow'); ?></p>
                <p><?php _e('If you wish to help then contact <a href="http://apasionados.es/contacto/index.php?desde=wordpress-org-imagecreditnofollow-administracionplugin" target="_blank">contact form</a> or contact us on Twitter: <a href="https://twitter.com/apasionados" target="_blank">@Apasionados</a>.', 'image-credits-nofollow'); ?></p>
			</div> <!-- .inside -->
		</div> <!-- .postbox -->
	</div></div> <!-- #side-container -->

</div><?php
	}

	function manage_media_columns($defaults) {
		$defaults['credits'] = __('Credits', 'image-credits-nofollow');
		return $defaults;
	}

	function manage_media_custom_column($column, $post_id) {
		if ($column == 'credits') {
			$credit_source = esc_attr(get_post_meta($post_id, '_wp_attachment_source_name', true));
			$credit_link = esc_url(get_post_meta($post_id, '_wp_attachment_source_url', true));
			$source_dofollow = esc_attr( get_post_meta( $id, '_wp_attachment_source_dofollow', true ) );

			if (!empty($credit_source)) {
				if (empty($credit_link)) {
					echo $credit_source;
				} else {
					if ( ( $source_dofollow == true ) || ( $source_dofollow == 1 ) ) {
						$credits[] = '<a href="' . $credit_link . '" target="_blank">' . $credit_source . '</a>';					
					} else {
						$credits[] = '<a href="' . $credit_link . '" rel="nofollow" target="_blank">' . $credit_source . '</a>';
					}					
				}
			}
		}
	}

}