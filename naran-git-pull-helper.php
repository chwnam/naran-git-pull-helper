<?php
/**
 * Plugin Name: Naran Git Pull Helper
 * Description: Run `git pull` automatically for you.
 * Version:     1.1.1
 * Author:      changwoo
 * Author URI:  https://blog.changwoo.pe.kr
 * Plugin URI:  https://github.com/chwnam/naran-git-pull-helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NRGPH_MAIN', __FILE__ );
define( 'NRGPH_VERSION', '1.1.1' );


final class Naran_Git_Pull_Helper {
	private static $instance = null;

	private $modules = [];

	private function __construct() {
		require_once __DIR__ . '/includes/sanitizers.php';
		require_once __DIR__ . '/includes/class-nrgph-cpt-repository.php';
		require_once __DIR__ . '/includes/class-nrgph-form-widgets.php';
		require_once __DIR__ . '/includes/class-nrgph-meta-field.php';
		require_once __DIR__ . '/includes/class-nrgph-option-field.php';
		require_once __DIR__ . '/includes/class-nrgph-rewrites.php';
		require_once __DIR__ . '/includes/class-nrgph-settings.php';
		require_once __DIR__ . '/includes/class-nrgph-settings-object.php';
		require_once __DIR__ . '/includes/class-nrgph-webhook-handler.php';
		require_once __DIR__ . '/includes/class-nrgph-webhook-log.php';

		$this->modules = [
			'cpt-repository' => new NRGPH_CPT_Repository(),
			'rewrite'        => new NRGPH_Rewrites(),
			'settings'       => new NRGPH_Settings(),
			'webhook-log'    => new NRGPH_Webhook_Log(),
		];

		register_activation_hook( NRGPH_MAIN, [ $this, 'activated' ] );
		register_deactivation_hook( NRGPH_MAIN, [ $this, 'deactivated' ] );
	}

	private function __clone() {
	}

	private function __wakeup() {
	}

	private function __sleep() {
	}

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function get_module( $module_name ) {
		return $this->modules[ $module_name ] ?? null;
	}

	public function activated() {
	}

	public function deactivated() {
	}
}

require_once __DIR__ . '/includes/functions.php';

nrgph();
