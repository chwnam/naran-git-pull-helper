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

		public static function checkbox_field( $args ) {
			$args['attrs']['type'] = 'checkbox';

			$attrs = self::format_attrs( $args['attrs'] ?? [] );

			printf(
				'<input %s> <label for="%s">%s</label>',
				$attrs,
				esc_attr( $args['attrs']['id'] ?? '' ),
				esc_html( $args['desc'] ?? '' )
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
						$buffer[] = $key . '=' . self::enclose( esc_url( $value ) );
						break;

					case 'class':
						$buffer[] = $key . '=' . self::enclose(
								implode( ' ', array_map( 'sanitize_html_class', preg_split( '/\s+/', $value ) ) )
							);
						break;

					case 'checked':
						if ( $key == $value || true === $value ) {
							$buffer[] = $key . '=' . self::enclose( $key );
						}
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
