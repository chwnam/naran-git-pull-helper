<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'NRGPH_Webhook_Log' ) ) :

	class NRGPH_Webhook_Log {
		public function insert_log( $comment_data ) {
			$comment_data = wp_parse_args(
				$comment_data,
				[
					'comment_agent'        => '',
					'comment_approved'     => 1,
					'comment_author'       => '',
					'comment_author_email' => '',
					'comment_author_IP'    => '',
					'comment_author_url'   => '',
					'comment_content'      => '',
					'comment_karma'        => 0,
					'comment_parent'       => 0,
					'comment_post_ID'      => 0,
					'comment_type'         => 'nrgph-webhook-log',
					'comment_meta'         => [],
					'user_id'              => '',
				]
			);

			$comment_data['comment_type'] = 'nrgph-webhook-log';

			// return wp_insert_comment( $comment_data );
		}
	}

endif;
