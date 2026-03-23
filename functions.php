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

/**
 * WordPressのバージョン情報を非表示にする
 */
// <meta name="generator"> タグを削除
remove_action( 'wp_head', 'wp_generator' );

// 各種フィードからバージョン情報を削除
foreach ( array( 'rss2_head', 'commentsrss2_head', 'rss_head', 'rdf_header', 'atom_head', 'comments_atom_head' ) as $action ) {
  remove_action( $action, 'the_generator' );
}

/**
 * wp_head の不要なリンクタグを削除する
 */
remove_action( 'wp_head', 'wlwmanifest_link' );    // Windows Live Writer マニフェスト
remove_action( 'wp_head', 'rsd_link' );             // Really Simple Discovery（XML-RPC関連）
remove_action( 'wp_head', 'wp_shortlink_wp_head' ); // 短縮URL

/**
 * REST API のユーザー一覧エンドポイントを無効化する
 */
function kaisokaiso_wp_classic_starter_disable_rest_users( $endpoints ) {
  if ( ! is_user_logged_in() ) {
    if ( isset( $endpoints['/wp/v2/users'] ) ) {
      unset( $endpoints['/wp/v2/users'] );
    }
    if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
      unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
    }
  }
  return $endpoints;
}
add_filter( 'rest_endpoints', 'kaisokaiso_wp_classic_starter_disable_rest_users' );

/**
 * ログインエラーメッセージを汎用化する（ユーザー名の存在を隠す）
 */
function kaisokaiso_wp_classic_starter_login_errors() {
  return 'ユーザー名またはパスワードが正しくありません。';
}
add_filter( 'login_errors', 'kaisokaiso_wp_classic_starter_login_errors' );

/**
 * XML-RPC を無効化する（ブルートフォース・DDoS攻撃の入口を塞ぐ）
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * X-Pingback ヘッダーを削除する
 */
function kaisokaiso_wp_classic_starter_remove_x_pingback( $headers ) {
  unset( $headers['X-Pingback'] );
  return $headers;
}
add_filter( 'wp_headers', 'kaisokaiso_wp_classic_starter_remove_x_pingback' );

/**
 * oEmbed 探索リンクを wp_head から削除する
 */
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'wp_oembed_add_host_js' );

/**
 * Author archive へのアクセス時にユーザー名が URL に露出しないようにする
 */
function kaisokaiso_wp_classic_starter_block_author_scan() {
  if ( ! is_admin() && isset( $_GET['author'] ) ) {
    wp_redirect( home_url( '/' ), 301 );
    exit;
  }
}
add_action( 'template_redirect', 'kaisokaiso_wp_classic_starter_block_author_scan' );
