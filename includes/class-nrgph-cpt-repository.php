<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'NRGPH_CPT_Repository' ) ) :

	final class NRGPH_CPT_Repository {
		const META_KEY_WEBHOOK_PROVIDER = 'nrgph_repository_webhook_provider';
		const META_KEY_SECRET_TOKEN = 'nrgph_repository_secret_token';
		const META_KEY_LOCAL_PATH = 'nrgph_repository_local_path';
		const META_KEY_REMOTE_URL = 'nrgph_repository_remote_url';

		public function __construct() {
			add_action( 'init', [ $this, 'register_post_type' ], 100 );
			add_action( 'init', [ $this, 'register_meta' ], 110 );
			add_action( 'current_screen', [ $this, 'current_screen' ], 100 );
			add_action( 'edit_form_after_editor', [ $this, 'output_edit_form' ] );
			add_action( 'save_post_' . self::get_post_type(), [ $this, 'save_metadata' ], 100, 3 );
			add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ], 100 );
		}

		public static function get_post_type() {
			return 'nrgph_repository';
		}

		public function register_post_type() {
			register_post_type(
				self::get_post_type(),
				[
					'label'                => 'Repositories',
					'labels'               => [
						'name'                     => 'Repositories',
						'singular_name'            => 'Repository',
						'add_new'                  => 'Add New',
						'add_new_item'             => 'Add New Repository',
						'edit_item'                => 'Edit Repository',
						'search_items'             => 'Search Repositories',
						'not_found'                => 'No repositories found',
						'not_found_in_trash'       => 'No repository found in Trash',
						'all_items'                => 'All Repositories',
						'menu_name'                => 'Repositories',
						'filter_items_list'        => 'Filter repositories list',
						'items_list_navigation'    => 'Repolistories list navigation',
						'items_list'               => 'Repositories List',
						'item_published'           => 'Repository published',
						'item_published_privately' => 'Repository published privately',
						'item_reverted_to_draft'   => 'Repository reverted to draft',
						'item_scheduled'           => 'Repository scheduled',
						'item_updated'             => 'Repository updated',
					],
					'description'          => 'Targeted repositories for `git pull`.',
					'public'               => false,
					'hierarchical'         => false,
					'exclude_from_search'  => true,
					'publicly_queryable'   => false,
					'show_ui'              => true,
					'show_in_menu'         => true,
					'show_in_admin_bar'    => false,
					'show_in_rest'         => false,
					// TODO: capability type
					//					'capability_type'      => [ 'repository', 'repositories' ],
					'map_meta_cap'         => true,
					'supports'             => [ 'title' ],
					'register_meta_box_cb' => null,
					'taxonomies'           => [],
					'has_archive'          => false,
					'rewrite'              => [
						'slug'       => 'repo',
						'with_front' => true,
						'feeds'      => false,
						'pages'      => false,
						'ep_mask'    => EP_PERMALINK,
					],
					'query_var'            => false,
					'delete_with_user'     => false,
				]
			);
		}

		public function register_meta() {
			register_meta(
				'post',
				self::META_KEY_WEBHOOK_PROVIDER,
				[
					'object_subtype'    => self::get_post_type(),
					'type'              => 'string',
					'description'       => 'Name of webhook provider. e.g. github, gitlab.',
					'single'            => true,
					'sanitize_callback' => 'sanitize_key',
				]
			);

			register_meta(
				'post',
				self::META_KEY_SECRET_TOKEN,
				[
					'object_subtype'    => self::get_post_type(),
					'type'              => 'string',
					'description'       => 'Secret token to validate a webhook request.',
					'single'            => true,
					'sanitize_callback' => 'sanitize_text_field',
				]
			);

			register_meta(
				'post',
				self::META_KEY_LOCAL_PATH,
				[
					'object_subtype'    => self::get_post_type(),
					'type'              => 'string',
					'description'       => 'Local repository path.',
					'single'            => true,
					'sanitize_callback' => 'sanitize_text_field',
				]
			);

			register_meta(
				'post',
				self::META_KEY_REMOTE_URL,
				[
					'object_subtype'    => self::get_post_type(),
					'type'              => 'string',
					'description'       => 'Remote repository path.',
					'single'            => true,
					'sanitize_callback' => 'sanitize_text_field',
				]
			);
		}

		public function current_screen( WP_Screen $screen ) {
			if ( self::get_post_type() === $screen->post_type && 'edit' === $screen->base ) {
				add_filter(
					'manage_' . $screen->id . '_columns',
					[ $this, 'manage_columns' ]
				);

				add_action(
					'manage_' . $screen->post_type . '_posts_custom_column',
					[ $this, 'custom_column' ],
					100,
					2
				);
			}
		}

		public function manage_columns( array $columns ) {
			$pos = array_search( 'date', array_keys( $columns ) );

			if ( false !== $pos ) {
				$columns = array_merge(
					(array) array_slice( $columns, 0, $pos ),
					[
						'provider' => 'Provider',
						'webhook'  => 'Webhook',
						'path'     => 'Path',
					],
					(array) array_slice( $columns, $pos )
				);
			}

			return $columns;
		}

		public function custom_column( $column_name, $post_id ) {
			switch ( $column_name ) {
				case 'provider':
					echo esc_html(
						nrgph_get_webhook_provider_name(
							$this->get_field_webhook_provider()->get( $post_id )
						)
					);
					break;

				case 'webhook':
					echo esc_url( nrgph_get_webhook_url( $post_id ) );
					break;

				case 'path':
					echo esc_html( $this->get_field_local_path()->get( $post_id ) );
					break;
			}
		}

		public function output_edit_form( WP_Post $post ) {
			if ( self::get_post_type() === $post->post_type ) {
				nrgph_template(
					'admin/edit-cpt-repository.php',
					[
						'meta_key_webhook_provider'   => self::META_KEY_WEBHOOK_PROVIDER,
						'meta_key_local_path'         => self::META_KEY_LOCAL_PATH,
						'meta_key_secret_token'       => self::META_KEY_SECRET_TOKEN,
						'meta_key_remote_url'         => self::META_KEY_REMOTE_URL,
						'value_webhook_provider'      => $this->get_field_webhook_provider()->get( $post ),
						'value_local_path'            => $this->get_field_local_path()->get( $post ),
						'value_secret_token'          => $this->get_field_secret_token()->get( $post ),
						'value_remote_url'            => $this->get_field_remote_url()->get( $post ),
						'value_webhook_url'           => nrgph_get_webhook_url( $post->ID ),
						'available_webhook_providers' => nrgph_get_available_webhook_providers(),
					]
				);

				$screen = get_current_screen();
				if ( empty( $screen->action ) && 'post' === $screen->base ) {
					$provider = $this->get_field_webhook_provider()->get( $post );
					if ( 'github' === $provider ) {
						nrgph_template(
							'admin/instruction-github.php',
							[ 'base_url' => plugin_dir_url( NRGPH_MAIN ) . 'assets/img/admin' ]
						);
					} elseif ( 'gitlab' === $provider ) {
						nrgph_template(
							'admin/instruction-gitlab.php',
							[ 'base_url' => plugin_dir_url( NRGPH_MAIN ) . 'assets/img/admin' ]
						);
					}
				}
			}
		}

		public function save_metadata( $post_id, $post, $updated ) {
			if ( $this->is_save_context( $post, $updated ) ) {
				$error      = new WP_Error();
				$local_path = trim( wp_unslash( $_REQUEST[ self::META_KEY_LOCAL_PATH ] ?? '' ), '/\\' );
				$remote     = wp_unslash( $_REQUEST[ self::META_KEY_REMOTE_URL ] ?? '' );
				$repo_path  = WP_CONTENT_DIR . "/{$local_path}";

				// Run git clone.
				if ( $repo_path && ! file_exists( $repo_path ) && $remote ) {
					$settings = nrgph_get_setting_object();
					if ( $settings->is_clone() ) {
						$git = escapeshellcmd( $settings->get_git_path() );
						if ( empty( $git ) ) {
							$git = '/usr/bin/git';
						}
						$parent = escapeshellarg( dirname( $repo_path ) );
						$remote = escapeshellarg( $remote );
						$base   = escapeshellarg( wp_basename( $repo_path ) );
						$cmd    = "cd {$parent} && {$git} clone {$remote} {$base}";
						exec( $cmd, $output, $return );
					}
				}

				$repo_path = realpath( $repo_path );
				if ( ! $repo_path ) {
					$error->add( 'error', 'Local path must exist in this server.' );
				} elseif ( ! is_dir( $repo_path ) || ! is_executable( $repo_path ) ) {
					$error->add( 'error', 'Local path must be a directory and accessible by the web server.' );
				}

				if ( $error->has_errors() ) {
					wp_die( $error, 'Error saving repository', [ 'back_link' => true ] );
				}

				$this->get_field_webhook_provider()->update(
					$post_id,
					$_REQUEST[ self::META_KEY_WEBHOOK_PROVIDER ] ?? ''
				);
				$this->get_field_local_path()->update(
					$post_id,
					$_REQUEST[ self::META_KEY_LOCAL_PATH ] ?? ''
				);
				$this->get_field_secret_token()->update(
					$post_id,
					$_REQUEST[ self::META_KEY_SECRET_TOKEN ] ?? ''
				);
				$this->get_field_remote_url()->update(
					$post_id,
					$_REQUEST[ self::META_KEY_REMOTE_URL ] ?? ''
				);
			}
		}

		public function enqueue_scripts( $hook ) {
			global $typenow;

			if ( 'post.php' === $hook && self::get_post_type() === $typenow ) {
				wp_enqueue_style(
					'nrgph-instructions',
					plugins_url( 'assets/css/admin/instructions.css', NRGPH_MAIN ),
					[],
					NRGPH_VERSION
				);
				wp_enqueue_script(
					'nrgph-instructions',
					plugins_url( 'assets/js/admin/instructions.js', NRGPH_MAIN ),
					[ 'jquery' ],
					NRGPH_VERSION,
					true
				);
			}
		}

		public function get_field_webhook_provider() {
			return nrgph_meta_field( self::META_KEY_WEBHOOK_PROVIDER, self::get_post_type() );
		}

		public function get_field_local_path() {
			return nrgph_meta_field( self::META_KEY_LOCAL_PATH, self::get_post_type() );
		}

		public function get_field_secret_token() {
			return nrgph_meta_field( self::META_KEY_SECRET_TOKEN, self::get_post_type() );
		}

		public function get_field_remote_url() {
			return nrgph_meta_field( self::META_KEY_REMOTE_URL, self::get_post_type() );
		}

		private function is_save_context( $post, $updated ) {
			return (
				! ( defined( 'DOING_CRON' ) && DOING_CRON ) &&
				! ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) &&
				$post instanceof WP_Post &&
				self::get_post_type() === $post->post_type &&
				'trash' !== $post->post_status &&
				current_user_can( get_post_type_object( self::get_post_type() )->cap->edit_post, $post->ID ) &&
				$updated &&
				isset( $_REQUEST['nrgph_nonce'] ) &&
				wp_verify_nonce( $_REQUEST['nrgph_nonce'], 'edit-cpt-repository' )
			);
		}
	}

endif;
