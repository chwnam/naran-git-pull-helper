<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NRGPH_Settings_Object' ) ) :

	class NRGPH_Settings_Object {
		private $git_path = '';

		public function get_git_path() {
			return $this->git_path;
		}

		public function set_git_path( $git_path ) {
			$git_path = sanitize_text_field( $git_path );

			if ( $git_path && is_executable( $git_path ) ) {
				$this->git_path = $git_path;
			} else {
				$this->git_path = '';
			}

			return $this;
		}

		public static function from_array( $array ) {
			$instance = new self();

			$instance->set_git_path( $array['git_path'] ?? '' );

			return $instance;
		}

		public function to_array() {
			return [
				'git_path' => $this->get_git_path(),
			];
		}
	}

endif;
