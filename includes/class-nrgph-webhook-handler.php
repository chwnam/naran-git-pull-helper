<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'NRGPH_Webhook_Handler' ) ) :

	/**
	 * @link https://developer.github.com/webhooks/event-payloads/#push
	 * @link https://gitlab.com/help/user/project/integrations/webhooks
	 */
	class NRGPH_Webhook_Handler {
		private $object;

		private $log_enabled;

		public function __construct( $object_id ) {
			$this->object      = get_post( $object_id );
			$this->log_enabled = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) && ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG );
		}

		public function handle_request() {
			if ( $this->is_valid_object() && $this->is_valid_request() && $this->is_push_request() ) {
				$this->error_log( "SERVER: \n" . print_r( $_SERVER, 1 ) );

				$git  = escapeshellcmd( $this->get_git_cmd() );
				$path = $this->get_local_path();

				if ( $path && is_dir( $path ) && is_executable( $path ) && is_executable( $git ) ) {
					$path = escapeshellarg( $path );
					// reset hard
					$settings = nrgph_get_setting_object();
					if ( $settings->is_reset_hard() ) {
					    $command = "cd {$path} && {$git} reset --hard master";
                        $this->error_log( "Executing command: `{$command}`" );
						exec( $command, $output, $return );
                        $this->error_log( sprintf( "`git reset --hard master` executed. return_val: %s", print_r( $return, 1 ) ) );
					}

					$command = "cd {$path} && {$git} pull";
					$this->error_log( "Executing command: `{$command}`" );
					exec( $command, $output, $return );
					$this->error_log( sprintf( "`git pull` executed. return_val: %s", print_r( $return, 1 ) ) );

					if ( '0' === $return ) {
						$this->insert_success_log();
					} else {
						$this->insert_error_log();
					}
				} else {
					$this->error_log(
						sprintf(
							"Error handling request.\n- path: %s (%s, %s)\n- git: %s (%s)",
							$path,
							is_dir( $path ) ? 'is_dir: true' : 'is_dir: false',
							is_executable( $path ) ? 'is_executable: true' : 'is_executable: false',
							$git,
							is_executable( $git ) ? 'is_executable: true' : 'is_executable: false'
						)
					);
				}

				die( 200 );
			}

			// TODO: error log
			die( 403 );
		}

		private function is_valid_request() {
			$provider = $this->get_webhook_provider();
			$token    = $this->get_secret_token();

			if ( 'github' === $provider ) {
				$header  = $_SERVER['HTTP_X_HUB_SIGNATURE'] ?? '';
				$content = file_get_contents( 'php://input' );
				$pos     = strpos( $header, '=' );

				if ( false !== $pos ) {
					$algorithm  = substr( $header, 0, $pos );
					$hash_value = substr( $header, $pos + 1 );

					return hash_hmac( $algorithm, $content, $token ) === $hash_value;
				}
			} elseif ( 'gitlab' === $provider ) {
				return $token === $_SERVER['HTTP_X_GITLAB_TOKEN'] ?? '';
			}

			return false;
		}

		private function get_content() {
			if ( 'application/json' === ( $_SERVER['CONTENT_TYPE'] ?? false ) ) {
				return json_decode( file_get_contents( 'php://input' ), true );
			} else {
				return $_REQUEST;
			}
		}

		private function is_push_request() {
			$provider = $this->get_webhook_provider();

			if ( 'github' === $provider ) {
				return 'push' === ( $_SERVER['HTTP_X_GITHUB_EVENT'] ?? '' );
			} elseif ( 'gitlab' === $provider ) {
				return 'Push Hook' === ( $_SERVER['HTTP_X_GITLAB_EVENT'] ?? '' );
			}

			return false;
		}

		private function is_valid_object() {
			return $this->object &&
			       NRGPH_CPT_Repository::get_post_type() === $this->object->post_type &&
			       'publish' === $this->object->post_status;
		}

		private function is_log_enabled() {
			return $this->log_enabled;
		}

		private function insert_success_log() {
			// TODO: implement this.
			// todo: success comment.

			/**
			 *
			 * 'comment_agent'        => $provider
			 * 'comment_approved'     => 1,
			 * 'comment_content'      => '',
			 * 'comment_post_ID'      => $this->post->ID,
			 * 'comment_type'         => 'nrgph-webhook-log',
			 * 'comment_meta'         => '
			 *
			 * github -
			 *
			 * message => git pull for $post_title finished successfully .
			 * github -> nrgph_github_delivery: [HTTP_X_GITHUB_DELIVERY] => 6afc10c4-bcfc-11ea-8687-ba304c4ff712
			 *
			 * gitlab -
			 *  checkout_sha: e96f607451f0aa23d7b6868b5b0087a0ac556768
			 * gitlab -> nrgph_gitlab_checkout_sha ['checkout_sha']
			 */
			$provider = $this->get_webhook_provider();
			if ( 'github' === $provider ) {
			} elseif ( 'gitlab' === $provider ) {
			}
			// nrgph_nrgph_insert_webhook_log();
		}

		private function insert_error_log() {
			// TODO: implement this.
			// todo: error comment. $output.

			/**
			 *
			 * 'comment_agent'        => $provider
			 * 'comment_approved'     => 1,
			 * 'comment_content'      => '',
			 * 'comment_post_ID'      => $this->post->ID,
			 * 'comment_type'         => 'nrgph-webhook-log',
			 * 'comment_meta'         => '
			 *
			 * github -
			 *
			 * message => git pull for $post_title failed. Output Return code.
			 * github -> nrgph_github_delivery: [HTTP_X_GITHUB_DELIVERY] => 6afc10c4-bcfc-11ea-8687-ba304c4ff712
			 *
			 * gitlab -
			 *  checkout_sha: e96f607451f0aa23d7b6868b5b0087a0ac556768
			 * gitlab -> nrgph_gitlab_checkout_sha ['checkout_sha']
			 */
			$provider = $this->get_webhook_provider();
			if ( 'github' === $provider ) {
			} elseif ( 'gitlab' === $provider ) {
			}
			// nrgph_nrgph_insert_webhook_log();
		}

		private function get_local_path() {
			return trailingslashit( WP_CONTENT_DIR ) .  nrgph_get_cpt_repository()->get_field_local_path()->get( $this->object );
		}

		private function get_secret_token() {
			return nrgph_get_cpt_repository()->get_field_secret_token()->get( $this->object );
		}

		private function get_webhook_provider() {
			return nrgph_get_cpt_repository()->get_field_webhook_provider()->get( $this->object );
		}

		private function get_git_cmd() {
			$settings = nrgph_get_setting_object();
			$git      = $settings->get_git_path();

			if ( empty( $git ) ) {
				$git = 'git';
			}

			return $git;
		}

		private function error_log( $log ) {
			if ( $this->is_log_enabled() ) {
				error_log( $log );
			}
		}
	}

endif;
