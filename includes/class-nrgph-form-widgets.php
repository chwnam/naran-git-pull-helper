<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'NRGPH_Form_Widgets' ) ) :

	class NRGPH_Form_Widgets {
		public static function input_field( $args ) {
			printf(
				'<input %s>',
				self::format_attrs( $args['attrs'] ?? [] )
			);
		}

		private static function format_attrs( $attrs ) {
			$buffer = [];

			foreach ( $attrs as $key => $value ) {
				$key = sanitize_key( $key );
				if ( ! $key ) {
					continue;
				}

				switch ( $key ) {
					case 'href':
					case 'src':
						$buffer[] = esc_url( $value );
						break;

					case 'class':
						$buffer[] = implode( ' ', array_map( 'sanitize_html_class', preg_split( '/\s+/', $value ) ) );
						break;

					default:
						$buffer[] = $key . '=' . self::enclose( esc_attr( $value ) );
						break;
				}
			}

			return implode( ' ', $buffer );
		}

		public static function enclose( $value, $enc = '"' ) {
			return "{$enc}{$value}{$enc}";
		}
	}

endif;
