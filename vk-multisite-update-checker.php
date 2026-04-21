<?php
/**
 * Plugin Name: VK Multisite Update Checker
 * Description: マルチサイトのネットワーク管理画面で VK 製品（テーマ・プラグイン）の更新通知を受け取れるようにする軽量プラグイン。サイトネットワークで有効化して使用してください。
 * Version: 1.0.0
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
			WP_CONTENT_DIR . '/themes/katawara/vendor/autoload.php',
			WP_PLUGIN_DIR . '/lightning-g3-pro-unit/vendor/autoload.php',
			WP_PLUGIN_DIR . '/vk-blocks-pro/vendor/autoload.php',
			WP_PLUGIN_DIR . '/lightning-g3-evergreen/vendor/autoload.php',
			WP_PLUGIN_DIR . '/lightning-g3-vekuan/vendor/autoload.php',
			WP_PLUGIN_DIR . '/lightning-skin-charm/vendor/autoload.php',
			WP_PLUGIN_DIR . '/lightning-skin-fort/vendor/autoload.php',
			WP_PLUGIN_DIR . '/lightning-skin-pale/vendor/autoload.php',
			WP_PLUGIN_DIR . '/lightning-skin-variety/vendor/autoload.php',
			WP_PLUGIN_DIR . '/lightning-skin-jpnstyle/vendor/autoload.php',
			WP_PLUGIN_DIR . '/vk-ab-testing/vendor/autoload.php',
			WP_PLUGIN_DIR . '/vk-add-fonts-for-block-editor/vendor/autoload.php',
			WP_PLUGIN_DIR . '/vk-filter-search-pro/vendor/autoload.php',
			WP_CONTENT_DIR . '/themes/smaveksive/vendor/autoload.php',
			WP_PLUGIN_DIR . '/vk-fullsite-installer/vendor/autoload.php',
			WP_PLUGIN_DIR . '/vk-video-block-pro/vendor/autoload.php',
			WP_PLUGIN_DIR . '/lightning-video-unit/vendor/autoload.php',
			WP_PLUGIN_DIR . '/vk-ai-editmate/vendor/autoload.php',
		);
		$loaded = false;
		foreach ( $autoload_candidates as $autoload ) {
			if ( ! file_exists( $autoload ) ) {
				continue;
			}
			require_once $autoload;
			if ( class_exists( 'YahnisElsts\PluginUpdateChecker\v5\PucFactory' ) ) {
				$loaded = true;
				break;
			}
		}
		if ( ! $loaded ) {
			return;
		}
	}

	// このプラグイン自身の更新チェック（setBranch を呼ばないことで GitHub Releases を参照）.
	YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
		'https://github.com/vektor-inc/vk-multisite-update-checker',
		__FILE__,
		'vk-multisite-update-checker'
	);

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
		// Katawara テーマ.
		array(
			'type'        => 'theme',
			'slug'        => 'katawara',
			'check_file'  => WP_CONTENT_DIR . '/themes/katawara/style.css',
			'main_file'   => WP_CONTENT_DIR . '/themes/katawara/functions.php',
			'metadata_url' => 'https://license.vektor-inc.co.jp/check/?action=get_metadata&slug=katawara',
			'license_key' => '',
			'license_from' => '',
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
		// Lightning G3 Evergreen プラグイン.
		array(
			'type'        => 'plugin',
			'slug'        => 'lightning-g3-evergreen',
			'check_file'  => WP_PLUGIN_DIR . '/lightning-g3-evergreen/lightning-g3-evergreen.php',
			'main_file'   => WP_PLUGIN_DIR . '/lightning-g3-evergreen/lightning-g3-evergreen.php',
			'metadata_url' => 'https://vws.vektor-inc.co.jp/updates/?action=get_metadata&slug=lightning-g3-evergreen',
			'license_key' => '',
			'license_from' => '',
		),
		// Lightning G3 Vekuan プラグイン.
		array(
			'type'        => 'plugin',
			'slug'        => 'lightning-g3-vekuan',
			'check_file'  => WP_PLUGIN_DIR . '/lightning-g3-vekuan/lightning-g3-vekuan.php',
			'main_file'   => WP_PLUGIN_DIR . '/lightning-g3-vekuan/lightning-g3-vekuan.php',
			'metadata_url' => 'https://license.vektor-inc.co.jp/check/?action=get_metadata&slug=lightning-g3-vekuan',
			'license_key' => '',
			'license_from' => '',
		),
		// Lightning Skin Charm プラグイン.
		array(
			'type'        => 'plugin',
			'slug'        => 'lightning-skin-charm',
			'check_file'  => WP_PLUGIN_DIR . '/lightning-skin-charm/lightning_skin_charm.php',
			'main_file'   => WP_PLUGIN_DIR . '/lightning-skin-charm/lightning_skin_charm.php',
			'metadata_url' => 'https://license.vektor-inc.co.jp/check/?action=get_metadata&slug=lightning-skin-charm',
			'license_key' => '',
			'license_from' => '',
		),
		// Lightning Skin Fort プラグイン.
		array(
			'type'        => 'plugin',
			'slug'        => 'lightning-skin-fort',
			'check_file'  => WP_PLUGIN_DIR . '/lightning-skin-fort/lightning-skin-fort.php',
			'main_file'   => WP_PLUGIN_DIR . '/lightning-skin-fort/lightning-skin-fort.php',
			'metadata_url' => 'https://license.vektor-inc.co.jp/check/?action=get_metadata&slug=lightning-skin-fort',
			'license_key' => '',
			'license_from' => '',
		),
		// Lightning Skin Pale プラグイン.
		array(
			'type'        => 'plugin',
			'slug'        => 'lightning-skin-pale',
			'check_file'  => WP_PLUGIN_DIR . '/lightning-skin-pale/lightning-skin-pale.php',
			'main_file'   => WP_PLUGIN_DIR . '/lightning-skin-pale/lightning-skin-pale.php',
			'metadata_url' => 'https://license.vektor-inc.co.jp/check/?action=get_metadata&slug=lightning-skin-pale',
			'license_key' => '',
			'license_from' => '',
		),
		// Lightning Skin Variety プラグイン.
		array(
			'type'        => 'plugin',
			'slug'        => 'lightning-skin-variety',
			'check_file'  => WP_PLUGIN_DIR . '/lightning-skin-variety/lightning_skin_variety.php',
			'main_file'   => WP_PLUGIN_DIR . '/lightning-skin-variety/lightning_skin_variety.php',
			'metadata_url' => 'https://license.vektor-inc.co.jp/check/?action=get_metadata&slug=lightning-skin-variety',
			'license_key' => '',
			'license_from' => '',
		),
		// Lightning Skin JPN Style プラグイン.
		array(
			'type'        => 'plugin',
			'slug'        => 'lightning-skin-jpnstyle',
			'check_file'  => WP_PLUGIN_DIR . '/lightning-skin-jpnstyle/lightning_skin_jpnstyle.php',
			'main_file'   => WP_PLUGIN_DIR . '/lightning-skin-jpnstyle/lightning_skin_jpnstyle.php',
			'metadata_url' => 'https://license.vektor-inc.co.jp/check/?action=get_metadata&slug=lightning-skin-jpnstyle',
			'license_key' => '',
			'license_from' => '',
		),
		// VK AB Testing プラグイン.
		array(
			'type'        => 'plugin',
			'slug'        => 'vk-ab-testing',
			'check_file'  => WP_PLUGIN_DIR . '/vk-ab-testing/vk-ab-testing.php',
			'main_file'   => WP_PLUGIN_DIR . '/vk-ab-testing/vk-ab-testing.php',
			'metadata_url' => 'https://license.vektor-inc.co.jp/check/?action=get_metadata&slug=vk-ab-testing',
			'license_key' => 'vk_ab_testing_license_key',
			'license_from' => 'option',
		),
		// VK Add Fonts for Block Editor プラグイン.
		array(
			'type'        => 'plugin',
			'slug'        => 'vk-add-fonts-for-block-editor',
			'check_file'  => WP_PLUGIN_DIR . '/vk-add-fonts-for-block-editor/vk-add-fonts-for-block-editor.php',
			'main_file'   => WP_PLUGIN_DIR . '/vk-add-fonts-for-block-editor/vk-add-fonts-for-block-editor.php',
			'metadata_url' => 'https://github.com/vektor-inc/vk-add-fonts-for-block-editor',
			'license_key' => '',
			'license_from' => '',
			'branch'      => 'main',
		),
		// VK Filter Search Pro プラグイン.
		array(
			'type'        => 'plugin',
			'slug'        => 'vk-filter-search-pro',
			'check_file'  => WP_PLUGIN_DIR . '/vk-filter-search-pro/vk-filter-search-pro.php',
			'main_file'   => WP_PLUGIN_DIR . '/vk-filter-search-pro/vk-filter-search-pro.php',
			'metadata_url' => 'https://license.vektor-inc.co.jp/check/?action=get_metadata&slug=vk-filter-search-pro',
			'license_key' => '',
			'license_from' => '',
		),
		// Smaveksive テーマ.
		array(
			'type'        => 'theme',
			'slug'        => 'smaveksive',
			'check_file'  => WP_CONTENT_DIR . '/themes/smaveksive/style.css',
			'main_file'   => WP_CONTENT_DIR . '/themes/smaveksive/functions.php',
			'metadata_url' => 'https://license.vektor-inc.co.jp/check/?action=get_metadata&slug=smaveksive',
			'license_key' => 'smaveksive-license-key',
			'license_from' => 'option',
		),
		// VK FullSite Installer プラグイン.
		array(
			'type'        => 'plugin',
			'slug'        => 'vk-fullsite-installer',
			'check_file'  => WP_PLUGIN_DIR . '/vk-fullsite-installer/vk-fullsite-installer.php',
			'main_file'   => WP_PLUGIN_DIR . '/vk-fullsite-installer/vk-fullsite-installer.php',
			'metadata_url' => 'https://license.vektor-inc.co.jp/check/?action=get_metadata&slug=vk-fullsite-installer',
			'license_key' => '',
			'license_from' => '',
		),
		// VK Video Block Pro プラグイン.
		array(
			'type'        => 'plugin',
			'slug'        => 'vk-video-block-pro',
			'check_file'  => WP_PLUGIN_DIR . '/vk-video-block-pro/vk-video-block-pro.php',
			'main_file'   => WP_PLUGIN_DIR . '/vk-video-block-pro/vk-video-block-pro.php',
			'metadata_url' => 'https://license.vektor-inc.co.jp/check/?action=get_metadata&slug=vk-video-block-pro',
			'license_key' => '',
			'license_from' => '',
		),
		// Lightning Video Unit プラグイン.
		array(
			'type'        => 'plugin',
			'slug'        => 'lightning-video-unit',
			'check_file'  => WP_PLUGIN_DIR . '/lightning-video-unit/lightning-video-unit.php',
			'main_file'   => WP_PLUGIN_DIR . '/lightning-video-unit/lightning-video-unit.php',
			'metadata_url' => 'https://vws.vektor-inc.co.jp/updates/?action=get_metadata&slug=lightning-video-unit',
			'license_key' => '',
			'license_from' => '',
		),
		// VK AI EditMate プラグイン.
		array(
			'type'        => 'plugin',
			'slug'        => 'vk-ai-editmate',
			'check_file'  => WP_PLUGIN_DIR . '/vk-ai-editmate/vk-ai-editmate.php',
			'main_file'   => WP_PLUGIN_DIR . '/vk-ai-editmate/vk-ai-editmate.php',
			'metadata_url' => 'https://license.vektor-inc.co.jp/check/?action=get_metadata&slug=vk-ai-editmate',
			'license_key' => '',
			'license_from' => '',
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
