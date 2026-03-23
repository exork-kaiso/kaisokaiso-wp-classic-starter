<?php
/**
 * テーマの functions と定義
 *
 * @package kaisokaiso-wp-classic-starter
 */

/**
 * テーマサポートの設定
 */
function kaisokaiso_wp_classic_starter_setup() {
  // アイキャッチ画像のサポート
  add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'kaisokaiso_wp_classic_starter_setup' );

/**
 * スタイルシートの読み込み
 */
function kaisokaiso_wp_classic_starter_enqueue_styles() {
  /* Google Fonts: Zen Maru Gothic（見出し・ナビ等で使用） */
  wp_enqueue_style(
    'kaisokaiso-wp-classic-starter-zen-maru-gothic',
    'https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic:wght@400;700&display=swap',
    array(),
    null
  );

  /* Google Fonts: Dancing Script（メッセージページ見出しで使用） */
  wp_enqueue_style(
    'kaisokaiso-wp-classic-starter-dancing-script',
    'https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&display=swap',
    array(),
    null
  );

  /* Google Fonts: Klee One（メッセージページ署名で使用） */
  wp_enqueue_style(
    'kaisokaiso-wp-classic-starter-klee-one',
    'https://fonts.googleapis.com/css2?family=Klee+One:wght@400;600&display=swap',
    array(),
    null
  );

  /* メインスタイルシート（Vite ビルド成果物） */
  wp_enqueue_style(
    'kaisokaiso-wp-classic-starter-main',
    get_stylesheet_directory_uri() . '/dist/assets/main.css',
    array(),
    null
  );

  /* Contact Form 7 用スタイル（CF7 有効時のみ読み込み） */
  if ( class_exists( 'WPCF7' ) ) {
    wp_enqueue_style(
      'kaisokaiso-wp-classic-starter-entry-form',
      get_stylesheet_directory_uri() . '/dist/assets/entry-form.css',
      array( 'kaisokaiso-wp-classic-starter-main' ),
      null
    );
  }

  /* フッター上部の背景装飾（動的 URL のため inline style で注入） */
  $footer_bg_url = esc_url( get_stylesheet_directory_uri() . '/images/footer_bg.webp' );
  wp_add_inline_style(
    'kaisokaiso-wp-classic-starter-main',
    ".footer-bg-decor { background-image: url(\"{$footer_bg_url}\"); }"
  );
}
add_action( 'wp_enqueue_scripts', 'kaisokaiso_wp_classic_starter_enqueue_styles' );

/**
 * スクリプトの読み込み（ES モジュール）
 */
function kaisokaiso_wp_classic_starter_enqueue_scripts() {
  $script_url = get_stylesheet_directory_uri() . '/dist/assets/script.js';

  if ( function_exists( 'wp_enqueue_script_module' ) ) {
    // WordPress 6.5+ 推奨: ネイティブの Script Modules API
    wp_enqueue_script_module(
      'kaisokaiso-wp-classic-starter-main',
      $script_url,
      array(),
      null
    );
  } else {
    // WordPress 6.4 以前: wp_enqueue_script + script_loader_tag でフォールバック
    wp_enqueue_script(
      'kaisokaiso-wp-classic-starter-main',
      $script_url,
      array(),
      null,
      true
    );
  }
}
add_action( 'wp_enqueue_scripts', 'kaisokaiso_wp_classic_starter_enqueue_scripts' );

/**
 * WordPress 6.4 以前: スクリプトを type="module" で読み込む（フォールバック）
 */
function kaisokaiso_wp_classic_starter_script_loader_tag( $tag, $handle, $src ) {
  if ( 'kaisokaiso-wp-classic-starter-main' === $handle && ! function_exists( 'wp_enqueue_script_module' ) ) {
    return str_replace( '<script ', '<script type="module" ', $tag );
  }
  return $tag;
}
add_filter( 'script_loader_tag', 'kaisokaiso_wp_classic_starter_script_loader_tag', 10, 3 );

/**
 * Contact Form 7 の CSS を読み込まない
 */
add_filter( 'wpcf7_load_css', '__return_false' );
