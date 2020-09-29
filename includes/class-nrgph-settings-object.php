<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NRGPH_Settings_Object' ) ) :

	class NRGPH_Settings_Object {
		private $git_path = '';

		private $clone;

		private $reset_hard;

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

		public function is_clone() {
			return $this->clone;
		}

		public function set_clone( $clone ) {
			$this->clone = filter_var( $clone, FILTER_VALIDATE_BOOLEAN );

			return $this;
		}

		public function is_reset_hard() {
			return $this->reset_hard;
		}

		public function set_reset_hard( $reset_hard ) {
			$this->reset_hard = filter_var( $reset_hard, FILTER_VALIDATE_BOOLEAN );

			return $this;
		}

		public static function from_array( $array ) {
			$instance = new self();

			$instance
				->set_git_path( $array['git_path'] ?? '' )
				->set_clone( $array['clone'] ?? false )
				->set_reset_hard( $array['reset_hard'] ?? false );

			return $instance;
		}

		public function to_array() {
			return [
				'git_path'   => $this->get_git_path(),
				'clone'      => $this->is_clone(),
				'reset_hard' => $this->is_reset_hard(),
			];
		}
	}

endif;
