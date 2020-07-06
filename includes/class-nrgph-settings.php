<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NRGPH_Settings' ) ) :

	class NRGPH_Settings {
		const OPTION_NAME_SETTINGS = 'nrgph_settings';

		public function __construct() {
			add_action( 'init', [ $this, 'register_settings' ], 100 );

			if ( is_admin() ) {
				add_action( 'admin_menu', [ $this, 'add_option_page' ], 100 );
			}
		}

		public function register_settings() {
			register_setting(
				'nrgph',
				self::OPTION_NAME_SETTINGS,
				[
					'type'              => 'object',
					'group'             => 'nrgph',
					'description'       => 'settings value',
					'sanitize_callback' => [ $this, 'sanitize_nrgph_settings' ],
					'show_in_rest'      => false,
					'default'           => NRGPH_Settings_Object::from_array( [] ),
				]
			);

			add_filter( 'option_' . self::OPTION_NAME_SETTINGS, [ $this, 'convert_to_object' ], 100 );
			add_action( 'update_option_' . self::OPTION_NAME_SETTINGS, [ $this, 'autoload_false' ], 100 );
		}

		public function add_option_page() {
			add_submenu_page(
				'edit.php?post_type=' . NRGPH_CPT_Repository::get_post_type(),
				'Naran Git Pull Helper Settings',
				'Settings',
				'manage_options',
				'nrgph-settings',
				[ $this, 'output_setting_page' ]
			);
		}

		public function output_setting_page() {
			$value = nrgph_get_setting_object();

			add_settings_section(
				'nrgph',
				'Git Pull Helper Settings',
				'__return_empty_string',
				'nrgph-options'
			);

			add_settings_field(
				'git-path-field',
				'Path to git',
				[ 'NRGPH_Form_Widgets', 'input_field' ],
				'nrgph-options',
				'nrgph',
				[
					'label_for' => 'git_path',
					'attrs'     => [
						'id'    => 'git_path',
						'name'  => self::OPTION_NAME_SETTINGS . '[git_path]',
						'class' => 'text',
						'type'  => 'text',
						'value' => $value->get_git_path(),
					],
				]
			);

			nrgph_template(
				'admin/options.php',
				[
					'option_group' => 'nrgph',
					'page'         => 'nrgph-options',
				]
			);
		}

		public function sanitize_nrgph_settings( $value ) {
			$git_path = $value['git_path'] ?? '';
			if ( ! is_executable( $git_path ) ) {
				add_settings_error(
					'nrgph_settings',
					'error',
					sprintf( 'Git path \'%s\' is not executable.', $git_path )
				);
			}

			return NRGPH_Settings_Object::from_array( $value )->to_array();
		}

		public function convert_to_object( $value ) {
			return NRGPH_Settings_Object::from_array( $value );
		}

		public function autoload_false() {
			global $wpdb;

			$wpdb->update(
				$wpdb->option,
				[ 'autoload' => 'no' ],
				[ 'option_name' => self::OPTION_NAME_SETTINGS ]
			);
		}

		public function get_settings() {
			return nrgph_option_field( self::OPTION_NAME_SETTINGS );
		}
	}

endif;