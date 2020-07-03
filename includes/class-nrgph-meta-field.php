<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NRGPH_Meta_Field' ) ) :

	class NRGPH_Meta_Field {
		private static $instances = [];

		private $meta_key;

		private $meta;

		/**
		 * @param $meta_key
		 * @param $subtype
		 *
		 * @return self
		 */
		public static function get_field( $meta_key, $subtype ) {
			if ( ! isset( self::$instances[ $meta_key ] ) ) {
				self::$instances[ $meta_key ] = new self( $meta_key, $subtype );
			}

			return self::$instances[ $meta_key ];
		}

		private function __construct( $meta_key, $subtype ) {
			$meta_keys = get_registered_meta_keys( 'post', $subtype );
			if ( isset( $meta_keys[ $meta_key ] ) ) {
				$this->meta = &$meta_keys[ $meta_key ];
			}

			$this->meta_key = $meta_key;
		}

		private function filter_id( $object_id ) {
			if ( is_array( $object_id ) && isset( $object_id['ID'] ) ) {
				return $object_id['ID'];
			} elseif ( is_object( $object_id ) && isset( $object_id->ID ) ) {
				return $object_id->ID;
			} elseif ( is_string( $object_id ) || is_int( $object_id ) ) {
				return intval( $object_id );
			} else {
				return false;
			}
		}

		public function get( $object_id ) {
			return get_metadata(
				'post',
				$this->filter_id( $object_id ),
				$this->meta_key,
				$this->meta['single'] ?? false
			);
		}

		public function update( $object_id, $value, $prev_value = '' ) {
			return update_metadata( 'post', $this->filter_id( $object_id ), $this->meta_key, $value, $prev_value );
		}

		public function delete( $object_id, $value = '' ) {
			return delete_metadata( 'post', $this->filter_id( $object_id ), $this->meta_key, $value );
		}
	}

endif;
