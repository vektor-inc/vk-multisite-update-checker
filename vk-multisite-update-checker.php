<?php
/**
 * Plugin Name: VK Multisite Update Checker
 * Description: マルチサイトのネットワーク管理画面で VK 製品（テーマ・プラグイン）の更新通知を受け取れるようにする軽量プラグイン。サイトネットワークで有効化して使用してください。
 * Version: 0.1.0
 * Author: Vektor,Inc.
 * Author URI: https://vektor-inc.co.jp
 * Network: true
 * License: GPL-2.0-or-later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_multisite() ) {
	return;
}

add_action( 'after_setup_theme', 'vk_multisite_update_checker_init', 99 );

function vk_multisite_update_checker_init() {

	// PUC ライブラリを読み込む（まだ読み込まれていない場合）.
	if ( ! class_exists( 'YahnisElsts\PluginUpdateChecker\v5\PucFactory' ) ) {
		$autoload_candidates = array(
			WP_CONTENT_DIR . '/themes/lightning-pro/vendor/autoload.php',
			WP_PLUGIN_DIR . '/lightning-g3-pro-unit/vendor/autoload.php',
			WP_PLUGIN_DIR . '/vk-blocks-pro/vendor/autoload.php',
		);
		$loaded = false;
		foreach ( $autoload_candidates as $autoload ) {
			if ( file_exists( $autoload ) ) {
				require_once $autoload;
				$loaded = true;
				break;
			}
		}
		if ( ! $loaded ) {
			return;
		}
	}

	$products = array(
		// Lightning Pro テーマ.
		array(
			'type'        => 'theme',
			'slug'        => 'lightning-pro',
			'check_file'  => WP_CONTENT_DIR . '/themes/lightning-pro/style.css',
			'main_file'   => WP_CONTENT_DIR . '/themes/lightning-pro/functions.php',
			'metadata_url' => 'https://license.vektor-inc.co.jp/check/?action=get_metadata&slug=lightning-pro',
			'license_key' => 'lightning-pro-license-key',
			'license_from' => 'option',
		),
		// Lightning G3 Pro Unit プラグイン.
		array(
			'type'        => 'plugin',
			'slug'        => 'lightning-g3-pro-unit',
			'check_file'  => WP_PLUGIN_DIR . '/lightning-g3-pro-unit/lightning-g3-pro-unit.php',
			'main_file'   => WP_PLUGIN_DIR . '/lightning-g3-pro-unit/lightning-g3-pro-unit.php',
			'metadata_url' => 'https://license.vektor-inc.co.jp/check/?action=get_metadata&slug=lightning-g3-pro-unit',
			'license_key' => 'lightning-g3-pro-unit-license-key',
			'license_from' => 'option',
		),
		// VK Blocks Pro プラグイン.
		array(
			'type'        => 'plugin',
			'slug'        => 'vk-blocks-pro',
			'check_file'  => WP_PLUGIN_DIR . '/vk-blocks-pro/vk-blocks.php',
			'main_file'   => WP_PLUGIN_DIR . '/vk-blocks-pro/vk-blocks.php',
			'metadata_url' => 'https://license.vektor-inc.co.jp/check/?action=get_metadata&slug=vk-blocks-pro',
			'license_key' => 'vk_blocks_pro_license_key',
			'license_from' => 'vk_blocks_options',
		),
		// Lightning G3 Expand Widget Areas プラグイン.
		array(
			'type'        => 'plugin',
			'slug'        => 'lightning-g3-expand-widget-areas',
			'check_file'  => WP_PLUGIN_DIR . '/lightning-g3-expand-widget/lightning-g3-expand-widget-areas.php',
			'main_file'   => WP_PLUGIN_DIR . '/lightning-g3-expand-widget/lightning-g3-expand-widget-areas.php',
			'metadata_url' => 'https://github.com/vektor-inc/lightning-g3-expand-widget-areas',
			'license_key' => '',
			'license_from' => '',
			'branch'      => 'master',
		),
	);

	foreach ( $products as $product ) {
		// 製品がインストールされていなければスキップ.
		if ( ! file_exists( $product['check_file'] ) ) {
			continue;
		}

		// 製品が既に有効（コードが読み込み済み）ならスキップ.
		if ( vk_multisite_is_product_active( $product ) ) {
			continue;
		}

		// PUC を初期化.
		$checker = YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
			$product['metadata_url'],
			$product['main_file'],
			$product['slug']
		);

		// GitHub の場合はブランチを設定.
		if ( ! empty( $product['branch'] ) && method_exists( $checker, 'setBranch' ) ) {
			$checker->setBranch( $product['branch'] );
		}

		// ライセンスキーをクエリに付与.
		if ( ! empty( $product['license_key'] ) ) {
			$license_key_name = $product['license_key'];
			$license_from     = $product['license_from'];
			$slug             = $product['slug'];

			$checker->addQueryArgFilter(
				function ( $query_args ) use ( $license_key_name, $license_from, $slug ) {
					$license = vk_multisite_get_license_key( $license_key_name, $license_from );
					if ( ! empty( $license ) ) {
						$query_args[ $slug . '-license-key' ] = $license;
					}
					return $query_args;
				}
			);
		}
	}
}

/**
 * 製品が既に有効かどうかを判定する。
 */
function vk_multisite_is_product_active( $product ) {
	if ( $product['type'] === 'theme' ) {
		return ( get_stylesheet() === $product['slug'] || get_template() === $product['slug'] );
	}

	// プラグインの場合：有効なら既にコードが読み込まれている.
	$plugin_file = plugin_basename( $product['main_file'] );

	// サイトネットワークで有効化されている場合.
	if ( is_plugin_active_for_network( $plugin_file ) ) {
		return true;
	}

	// メインサイトで有効化されている場合.
	$active_plugins = get_option( 'active_plugins', array() );
	return in_array( $plugin_file, $active_plugins, true );
}

/**
 * ライセンスキーを取得する。
 * 現在のサイト（ネットワーク管理画面ならメインサイト）にキーがなければ、
 * 製品が有効なサイトから探す。
 */
function vk_multisite_get_license_key( $key_name, $from ) {
	$license = vk_multisite_read_license( $key_name, $from );
	if ( ! empty( $license ) ) {
		return $license;
	}

	// 他のサイトからライセンスキーを探す.
	$sites = get_sites( array(
		'number' => 100,
		'fields' => 'ids',
	) );

	foreach ( $sites as $blog_id ) {
		if ( (int) $blog_id === get_current_blog_id() ) {
			continue;
		}
		switch_to_blog( $blog_id );
		$license = vk_multisite_read_license( $key_name, $from );
		restore_current_blog();
		if ( ! empty( $license ) ) {
			return $license;
		}
	}

	return '';
}

/**
 * 指定された方法でライセンスキーを読み込む.
 */
function vk_multisite_read_license( $key_name, $from ) {
	if ( $from === 'option' ) {
		return get_option( $key_name, '' );
	}

	// vk_blocks_options のようにオプション配列内にキーがある場合.
	$options = get_option( $from, array() );
	if ( is_array( $options ) && ! empty( $options[ $key_name ] ) ) {
		return $options[ $key_name ];
	}

	return '';
}
