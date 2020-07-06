<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'NRGPH_Option_Field' ) ) :

	class NRGPH_Option_Field {
		private static $instances = [];

		private $option_name;

		private $setting;

		public static function get_field( $option_name ) {
			if ( ! isset( self::$instances[ $option_name ] ) ) {
				self::$instances[ $option_name ] = new self( $option_name );
			}

			return self::$instances[ $option_name ];
		}

		private function __construct( $option_name ) {
			$settings = get_registered_settings();

			if ( isset( $settings[ $option_name ] ) ) {
				$this->setting = &$settings[ $option_name ];
			}

			$this->option_name = $option_name;
		}

		public function get() {
			return get_option( $this->option_name, $this->setting['default'] ?? false );
		}

		public function update( $value ) {
			return update_option( $this->option_name, $value, $this->setting['autoload'] ?? null );
		}

		public function delete() {
			return delete_option( $this->option_name );
		}

		public function convert_to_object( $value ) {
			if ( is_callable( [ $this->setting['object_class'], 'from_array' ] ) ) {
				$value = call_user_func( [ $this->setting['object_class'], 'from_array' ], $value );
			}

			return $value;
		}
	}

endif;
