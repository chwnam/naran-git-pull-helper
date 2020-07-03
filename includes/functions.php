<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


function nrgph() {
	return Naran_Git_Pull_Helper::get_instance();
}


function nrgph_get_module( $module_name ) {
	return nrgph()->get_module( $module_name );
}


/**
 * @return NRGPH_CPT_Repository
 */
function nrgph_get_cpt_repository() {
	return nrgph_get_module( 'cpt-repository' );
}


/**
 * @return NRGPH_Webhook_Log
 */
function nrgph_webhook_log() {
	return nrgph_get_module( 'webhook-log' );
}


function nrgph_meta_field( $meta_key, $subtype ) {
	return NRGPH_Meta_Field::get_field( $meta_key, $subtype );
}


/**
 * 템플릿 함수.
 *
 * 1. 차일드 테마의 /nrgph/{템플릿 이름} 경로.
 * 2. 부모 테마의 /nrgph/{템플릿 이름} 경로.
 * 3. 본 플러그인의 /templates/{템플릿 이름} 경로.
 *
 * @param string $template 템플릿 경로.
 * @param array $context 문맥 변수.
 * @param bool $echo 출력 여부.
 *
 * @return false|string
 */
function nrgph_template( $template, $context = [], $echo = true ) {
	$template = trim( $template, '/' );

	$paths = [
		STYLESHEETPATH . '/nrgph/' . $template,
		TEMPLATEPATH . '/nrgph/' . $template,
		dirname( NRGPH_MAIN ) . '/templates/' . $template,
	];

	$found = false;

	foreach ( $paths as $path ) {
		if ( file_exists( $path ) && is_readable( $path ) ) {
			$found = $path;
			break;
		}
	}

	if ( ! $echo ) {
		ob_start();
	}

	if ( is_array( $context ) && ! empty( $context ) ) {
		extract( $context, EXTR_SKIP );
	}

	if ( $found ) {
		/** @noinspection PhpIncludeInspection */
		include $found;
	}

	if ( ! $echo ) {
		return ob_get_clean();
	}

	return false;
}


function nrgph_get_available_webhook_providers() {
	return [
		'github' => 'Github',
		'gitlab' => 'Gitlab',
	];
}


/**
 * @param $object_id
 */
function nrgph_handle_webhook( $object_id ) {
	$webhook = new NRGPH_Webhook_Handler( $object_id );
	$webhook->handle_request();
}


/**
 * @param array $log_data
 *
 * @return int|false
 *
 * @see NRGPH_Webhook_Log::insert_log()
 */
function nrgph_nrgph_insert_webhook_log( $log_data ) {
	return nrgph_webhook_log()->insert_log( $log_data );
}
