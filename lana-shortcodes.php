<?php
/**
 * Plugin Name: Lana Shortcodes
 * Plugin URI: https://lana.codes/product/lana-shortcodes/
 * Description: Shortcodes with Bootstrap framework.
 * Version: 1.2.0
 * Author: Lana Codes
 * Author URI: https://lana.codes/
 * Text Domain: lana-shortcodes
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) or die();
define( 'LANA_SHORTCODES_VERSION', '1.2.0' );
define( 'LANA_SHORTCODES_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'LANA_SHORTCODES_DIR_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Lana Shortcodes
 * Modifiable constants
 */
add_action( 'init', function () {
	if ( ! defined( 'LANA_SHORTCODES_DEFAULT_BOOTSTRAP_VERSION' ) ) {
		define( 'LANA_SHORTCODES_DEFAULT_BOOTSTRAP_VERSION', 3 );
	}
} );

/**
 * Language
 * load
 */
load_plugin_textdomain( 'lana-shortcodes', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

/**
 * Lana Shortcodes
 * get bootstrap version
 * @return bool|int
 */
function lana_shortcodes_get_bootstrap_version() {

	if ( wp_style_is( 'bootstrap', 'registered' ) ) {
		$wp_styles = wp_styles();

		list( $version ) = explode( '.', $wp_styles->registered['bootstrap']->ver );

		if ( version_compare( $version, '4', '=' ) ) {
			return 4;
		}

		if ( version_compare( $version, '3', '=' ) ) {
			return 3;
		}
	}

	$bootstrap_version_option = get_option( 'lana_shortcodes_bootstrap_version', '' );

	if ( in_array( intval( $bootstrap_version_option ), array( 3, 4 ) ) ) {
		return $bootstrap_version_option;
	}

	if ( defined( 'LANA_SHORTCODES_DEFAULT_BOOTSTRAP_VERSION' ) ) {
		return LANA_SHORTCODES_DEFAULT_BOOTSTRAP_VERSION;
	}

	return false;
}

/**
 * Add plugin action links
 *
 * @param $links
 *
 * @return mixed
 */
function lana_shortcodes_add_plugin_action_links( $links ) {

	$settings_url = esc_url( admin_url( 'options-general.php?page=lana-shortcodes-settings.php' ) );

	/** add settings link */
	$settings_link = sprintf( '<a href="%s">%s</a>', $settings_url, __( 'Settings', 'lana-shortcodes' ) );
	array_unshift( $links, $settings_link );

	return $links;
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'lana_shortcodes_add_plugin_action_links' );

/**
 * Styles
 * load in plugin
 */
function lana_shortcodes_admin_styles() {

	wp_register_style( 'lana-shortcodes-admin', LANA_SHORTCODES_DIR_URL . '/assets/css/lana-shortcodes-admin.min.css', array(), LANA_SHORTCODES_VERSION );
	wp_enqueue_style( 'lana-shortcodes-admin' );
}

add_action( 'admin_enqueue_scripts', 'lana_shortcodes_admin_styles' );

/**
 * Styles
 * load in plugin
 */
function lana_shortcodes_styles() {

	if ( ! wp_style_is( 'bootstrap' ) && get_option( 'lana_shortcodes_bootstrap_load', '' ) == 'normal' ) {

		$bootstrap_version = lana_shortcodes_get_bootstrap_version();

		/** bootstrap 3 */
		if ( 3 == $bootstrap_version ) {
			wp_register_style( 'bootstrap', LANA_SHORTCODES_DIR_URL . '/assets/libs/bootstrap/v3/css/bootstrap.min.css', array(), '3.4.1' );
			wp_enqueue_style( 'bootstrap' );
		}

		/** bootstrap 4 */
		if ( 4 == $bootstrap_version ) {
			wp_register_style( 'bootstrap', LANA_SHORTCODES_DIR_URL . '/assets/libs/bootstrap/v4/css/bootstrap.min.css', array(), '4.5.0' );
			wp_enqueue_style( 'bootstrap' );
		}
	}
}

add_action( 'wp_enqueue_scripts', 'lana_shortcodes_styles', 1000 );

/**
 * JavaScript
 * load in plugin
 */
function lana_shortcodes_scripts() {

	if ( ! wp_script_is( 'bootstrap' ) && get_option( 'lana_shortcodes_bootstrap_load', '' ) == 'normal' ) {

		$bootstrap_version = lana_shortcodes_get_bootstrap_version();

		/** bootstrap 3 */
		if ( 3 == $bootstrap_version ) {
			/** bootstrap js */
			wp_register_script( 'bootstrap', LANA_SHORTCODES_DIR_URL . '/assets/libs/bootstrap/v3/js/bootstrap.min.js', array( 'jquery' ), '3.4.1' );
			wp_enqueue_script( 'bootstrap' );
		}

		/** bootstrap 4 */
		if ( 4 == $bootstrap_version ) {
			/** popper js */
			wp_register_script( 'popper', LANA_SHORTCODES_DIR_URL . '/assets/libs/popper/popper.min.js', array( 'jquery' ), '1.16.1' );
			wp_enqueue_script( 'popper' );

			/** bootstrap js */
			wp_register_script( 'bootstrap', LANA_SHORTCODES_DIR_URL . '/assets/libs/bootstrap/v4/js/bootstrap.min.js', array(
				'jquery',
				'popper',
			), '4.5.0' );
			wp_enqueue_script( 'bootstrap' );
		}
	}
}

add_action( 'wp_enqueue_scripts', 'lana_shortcodes_scripts', 1000 );

/**
 * Lana Shortcodes
 * add admin page
 */
function lana_shortcodes_admin_menu() {
	add_options_page( __( 'Lana Shortcodes Settings', 'lana-shortcodes' ), __( 'Lana Shortcodes', 'lana-shortcodes' ), 'manage_options', 'lana-shortcodes-settings.php', 'lana_shortcodes_settings_page' );

	/** call register settings function */
	add_action( 'admin_init', 'lana_shortcodes_register_settings' );
}

add_action( 'admin_menu', 'lana_shortcodes_admin_menu' );

/**
 * Register settings
 */
function lana_shortcodes_register_settings() {
	register_setting( 'lana-shortcodes-settings-group', 'lana_shortcodes_bootstrap_load' );
	register_setting( 'lana-shortcodes-settings-group', 'lana_shortcodes_bootstrap_version' );
}

/**
 * Lana Shortcodes Settings page
 */
function lana_shortcodes_settings_page() {
	?>
    <div class="wrap">
        <h2><?php _e( 'Lana Shortcodes Settings', 'lana-shortcodes' ); ?></h2>

        <hr/>
        <a href="<?php echo esc_url( 'https://lana.codes/' ); ?>" target="_blank">
            <img src="<?php echo esc_url( LANA_SHORTCODES_DIR_URL . '/assets/img/plugin-header.png' ); ?>"
                 alt="<?php esc_attr_e( 'Lana Codes', 'lana-shortcodes' ); ?>"/>
        </a>
        <hr/>

        <form method="post" action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>">
			<?php settings_fields( 'lana-shortcodes-settings-group' ); ?>

            <h2 class="title"><?php _e( 'Frontend Settings', 'lana-shortcodes' ); ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="lana-shortcodes-bootstrap-load">
							<?php _e( 'Bootstrap Load', 'lana-shortcodes' ); ?>
                        </label>
                    </th>
                    <td>
                        <select name="lana_shortcodes_bootstrap_load" id="lana-shortcodes-bootstrap-load">
                            <option value=""
								<?php selected( get_option( 'lana_shortcodes_bootstrap_load', '' ), '' ); ?>>
								<?php _e( 'None', 'lana-shortcodes' ); ?>
                            </option>
                            <option value="normal"
								<?php selected( get_option( 'lana_shortcodes_bootstrap_load', '' ), 'normal' ); ?>>
								<?php _e( 'Normal Bootstrap', 'lana-shortcodes' ); ?>
                            </option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="lana-shortcodes-bootstrap-version">
							<?php _e( 'Bootstrap Version', 'lana-shortcodes' ); ?>
                        </label>
                    </th>
                    <td>
                        <select name="lana_shortcodes_bootstrap_version" id="lana-shortcodes-bootstrap-version">
                            <option value=""
								<?php selected( get_option( 'lana_shortcodes_bootstrap_version', '' ), '' ); ?>>
								<?php _e( 'Default', 'lana-shortcodes' ); ?>
                            </option>
                            <option value="3"
								<?php selected( get_option( 'lana_shortcodes_bootstrap_version', '' ), '3' ); ?>>
								<?php _e( 'Bootstrap 3', 'lana-shortcodes' ); ?>
                            </option>
                            <option value="4"
								<?php selected( get_option( 'lana_shortcodes_bootstrap_version', '' ), '4' ); ?>>
								<?php _e( 'Bootstrap 4', 'lana-shortcodes' ); ?>
                            </option>
                        </select>
                    </td>
                </tr>
            </table>

            <p class="submit">
                <input type="submit" class="button-primary"
                       value="<?php esc_attr_e( 'Save Changes', 'lana-shortcodes' ); ?>"/>
            </p>

        </form>
    </div>
	<?php
}

/**
 * Lana Button Shortcode
 * with Bootstrap
 *
 * @param $atts
 * @param null $content
 *
 * @return string
 */
function lana_button_shortcode( $atts, $content = null ) {

	$bootstrap_version = lana_shortcodes_get_bootstrap_version();

	$output = '';

	/** bootstrap 3 */
	if ( 3 == $bootstrap_version ) {

		$a = shortcode_atts( array(
			'class' => '',
			'size'  => 'md',
			'type'  => 'default',
			'href'  => '#',
		), $atts );

		$btn_classes = array( 'btn', 'btn-' . $a['size'], 'btn-' . $a['type'] );

		if ( $a['class'] ) {
			if ( ! is_array( $a['class'] ) ) {
				$a['class'] = preg_split( '#\s+#', $a['class'] );
			}
		} else {
			$a['class'] = array();
		}

		$btn_classes = array_merge( $btn_classes, $a['class'] );

		/**
		 * Output
		 */
		$output = sprintf( '<a class="%s" href="%s">', esc_attr( implode( ' ', $btn_classes ) ), esc_url( $a['href'] ) );
		$output .= $content;
		$output .= '</a>';
	}

	/** bootstrap 4 */
	if ( 4 == $bootstrap_version ) {

		$a = shortcode_atts( array(
			'class'   => '',
			'size'    => '',
			'type'    => 'primary',
			'outline' => '',
			'href'    => '#',
		), $atts );

		if ( ! empty( $a['size'] ) ) {
			$a['size'] = 'btn-' . $a['size'];
		}

		if ( ! empty( $a['outline'] ) ) {
			$a['type'] = 'outline-' . $a['type'];
		}

		$btn_classes = array( 'btn', $a['size'], 'btn-' . $a['type'] );

		if ( $a['class'] ) {
			if ( ! is_array( $a['class'] ) ) {
				$a['class'] = preg_split( '#\s+#', $a['class'] );
			}
		} else {
			$a['class'] = array();
		}

		$btn_classes = array_merge( $btn_classes, $a['class'] );

		/**
		 * Output
		 */
		$output = sprintf( '<a class="%s" href="%s">', esc_attr( implode( ' ', $btn_classes ) ), esc_url( $a['href'] ) );
		$output .= $content;
		$output .= '</a>';
	}

	return $output;
}

add_shortcode( 'lana_button', 'lana_button_shortcode' );

/**
 * Lana Icon Shortcode
 * with Bootstrap
 *
 * @param $atts
 *
 * @return string
 */
function lana_icon_shortcode( $atts ) {

	$a = shortcode_atts( array(
		'name' => '',
	), $atts );

	/** validate icon */
	if ( empty( $a['name'] ) ) {
		return '';
	}

	$icon_classes = array( 'glyphicon', 'glyphicon-' . $a['name'] );

	/**
	 * Output
	 */
	$output = sprintf( '<span class="%s">', esc_attr( implode( ' ', $icon_classes ) ) );
	$output .= '</span>';

	return $output;
}

add_shortcode( 'lana_icon', 'lana_icon_shortcode' );

/**
 * Lana Label Shortcode
 * with Bootstrap
 *
 * @param $atts
 * @param null $content
 *
 * @return string
 */
function lana_label_shortcode( $atts, $content = null ) {

	$bootstrap_version = lana_shortcodes_get_bootstrap_version();

	$output = '';

	/** bootstrap 3 */
	if ( 3 == $bootstrap_version ) {

		$a = shortcode_atts( array(
			'type' => 'default',
		), $atts );

		$label_classes = array( 'label', 'label-' . $a['type'] );

		/**
		 * Output
		 */
		$output = sprintf( '<span class="%s">', esc_attr( implode( ' ', $label_classes ) ) );
		$output .= $content;
		$output .= '</span>';
	}

	/** bootstrap 4 */
	if ( 4 == $bootstrap_version ) {

		$a = shortcode_atts( array(
			'type' => 'default',
		), $atts );

		$label_classes = array( 'badge', 'badge-' . $a['type'] );

		/**
		 * Output
		 */
		$output = sprintf( '<span class="%s">', esc_attr( implode( ' ', $label_classes ) ) );
		$output .= $content;
		$output .= '</span>';
	}

	return $output;
}

add_shortcode( 'lana_label', 'lana_label_shortcode' );

/**
 * Lana Badges Shortcode
 * with Bootstrap
 *
 * @param $atts
 * @param null $content
 *
 * @return string
 */
function lana_badges_shortcode( $atts, $content = null ) {

	$bootstrap_version = lana_shortcodes_get_bootstrap_version();

	$output = '';

	/** bootstrap 3 */
	if ( 3 == $bootstrap_version ) {

		/**
		 * Output
		 */
		$output = '<span class="badge">';
		$output .= $content;
		$output .= '</span>';
	}

	/** bootstrap 4 */
	if ( 4 == $bootstrap_version ) {

		$a = shortcode_atts( array(
			'type' => 'secondary',
			'pill' => '',
		), $atts );

		if ( ! empty( $a['pill'] ) ) {
			$a['pill'] = 'badge-pill';
		}

		$label_classes = array( 'badge', 'badge-' . $a['type'], $a['pill'] );

		/**
		 * Output
		 */
		$output = sprintf( '<span class="%s">', esc_attr( implode( ' ', $label_classes ) ) );
		$output .= $content;
		$output .= '</span>';
	}

	return $output;
}

add_shortcode( 'lana_badges', 'lana_badges_shortcode' );

/**
 * Lana Progress Bar Shortcode
 * with Bootstrap
 *
 * @param $atts
 *
 * @return string
 */
function lana_progress_bar_shortcode( $atts ) {

	$a = shortcode_atts( array(
		'value'   => '0',
		'type'    => '',
		'label'   => '',
		'striped' => '',
		'active'  => '',
	), $atts );

	$a['value'] = intval( $a['value'] );

	/**
	 * Progress bar
	 */
	if ( ! empty( $a['type'] ) ) {
		$a['type'] = 'progress-bar-' . $a['type'];
	}

	if ( ! empty( $a['striped'] ) ) {
		$a['striped'] = 'progress-bar-striped';
	}

	if ( ! empty( $a['active'] ) ) {
		$a['active'] = 'active';
	}

	$progress_bar_classes = array( 'progress-bar', $a['type'], $a['striped'], $a['active'] );

	/**
	 * Label
	 */
	if ( 'hidden' == $a['label'] ) {
		$a['label'] = 'sr-only';
	} else {
		$a['label'] = '';
	}

	$label_classes = array( $a['label'] );

	/**
	 * Output
	 */
	$output = '<div class="progress">';
	$output .= sprintf( '<div class="%s" role="progressbar" aria-valuenow="%d" aria-valuemin="0" aria-valuemax="100" style="width: %s;">', esc_attr( implode( ' ', $progress_bar_classes ) ), esc_attr( $a['value'] ), esc_attr( $a['value'] . '%' ) );
	$output .= sprintf( '<span class="%s">', esc_attr( implode( ' ', $label_classes ) ) );
	$output .= esc_html( $a['value'] ) . '%';
	$output .= '</span>';
	$output .= '</div>';
	$output .= '</div>';

	return $output;
}

add_shortcode( 'lana_progress_bar', 'lana_progress_bar_shortcode' );

/**
 * TinyMCE
 * Register Plugins
 *
 * @param $plugins
 *
 * @return mixed
 */
function lana_shortcodes_add_mce_plugin( $plugins ) {

	$bootstrap_version = lana_shortcodes_get_bootstrap_version();

	if ( 3 == $bootstrap_version ) {
		$plugins['lana_button']       = LANA_SHORTCODES_DIR_URL . '/assets/js/v3/lana-button.js';
		$plugins['lana_icon']         = LANA_SHORTCODES_DIR_URL . '/assets/js/v3/lana-icon.js';
		$plugins['lana_label']        = LANA_SHORTCODES_DIR_URL . '/assets/js/v3/lana-label.js';
		$plugins['lana_badges']       = LANA_SHORTCODES_DIR_URL . '/assets/js/v3/lana-badges.js';
		$plugins['lana_progress_bar'] = LANA_SHORTCODES_DIR_URL . '/assets/js/v3/lana-progress-bar.js';
	}

	if ( 4 == $bootstrap_version ) {
		$plugins['lana_button']       = LANA_SHORTCODES_DIR_URL . '/assets/js/v4/lana-button.js';
		$plugins['lana_badges']       = LANA_SHORTCODES_DIR_URL . '/assets/js/v4/lana-badges.js';
		$plugins['lana_progress_bar'] = LANA_SHORTCODES_DIR_URL . '/assets/js/v4/lana-progress-bar.js';
	}

	return $plugins;
}

/**
 * TinyMCE
 * Register Buttons
 *
 * @param $buttons
 *
 * @return mixed
 */
function lana_shortcodes_add_mce_button( $buttons ) {

	$bootstrap_version = lana_shortcodes_get_bootstrap_version();

	if ( 3 == $bootstrap_version ) {
		array_push( $buttons, 'lana_button' );
		array_push( $buttons, 'lana_icon' );
		array_push( $buttons, 'lana_label' );
		array_push( $buttons, 'lana_badges' );
		array_push( $buttons, 'lana_progress_bar' );
	}

	if ( 4 == $bootstrap_version ) {
		array_push( $buttons, 'lana_button' );
		array_push( $buttons, 'lana_badges' );
		array_push( $buttons, 'lana_progress_bar' );
	}

	return $buttons;
}

/**
 * TinyMCE
 * Add Custom Buttons
 */
function lana_shortcodes_add_mce_shortcodes_buttons() {
	add_filter( 'mce_external_plugins', 'lana_shortcodes_add_mce_plugin' );
	add_filter( 'mce_buttons_3', 'lana_shortcodes_add_mce_button' );
}

add_action( 'admin_init', 'lana_shortcodes_add_mce_shortcodes_buttons' );
