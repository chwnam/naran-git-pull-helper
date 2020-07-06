<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NRGPH_Rewrites' ) ) :

	class NRGPH_Rewrites {
		public function __construct() {
			add_action( 'init', [ $this, 'add_rewrite_rules' ], 100 );
			add_action( 'template_redirect', [ $this, 'maybe_handle_webhook' ], 100 );
			add_filter( 'query_vars', [ $this, 'add_query_vars' ], 100 );
		}

		public function add_rewrite_rules() {
			add_rewrite_rule(
				'^nrgph/webhook/(\d+)/?$',
				'index.php?nrgph=webhook&nrgph_object_id=$matches[1]',
				'top'
			);
		}

		public function add_query_vars( $query_vars ) {
			$query_vars[] = 'nrgph';
			$query_vars[] = 'nrgph_object_id';

			return $query_vars;
		}

		public function maybe_handle_webhook() {
			global $wp_the_query;

			$nrgph     = $wp_the_query->get( 'nrgph', '' );
			$object_id = $wp_the_query->get( 'nrgph_object_id', '' );

			if ( 'webhook' === $nrgph && $object_id ) {
				nrgph_handle_webhook( $object_id );
				exit;
			}
		}

		public static function get_webhook_url( $repository_id ) {
			return home_url( '/nrgph/webhook/' . intval( $repository_id ) . '/' );
		}
	}

endif;