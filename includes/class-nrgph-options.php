<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NRGPH_Options' ) ) :

	class NRGPH_Options {
		public function __construct() {
			if ( is_admin() ) {
				add_action( 'admin_menu', [ $this, 'add_option_page' ] );
			}
		}

		public function add_option_page() {
			add_submenu_page(
				'edit.php?post_type=' . NRGPH_CPT_Repository::get_post_type(),
				'Options',
				'Options',
				'manage_options',
				'nrgph-options',
				[ $this, 'output_setting_page' ]
			);
		}

		public function output_setting_page() {
			echo '';
		}
	}

endif;