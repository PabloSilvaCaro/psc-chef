<?php
/**
 * Theme bootstrap.
 *
 * @package ChefEnCasa
 */

defined( 'ABSPATH' ) || exit;

define( 'CHEF_THEME_VERSION', '0.4.0' );

add_action(
	'after_setup_theme',
	static function (): void {
		load_theme_textdomain( 'chef-en-casa', get_template_directory() . '/languages' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'custom-logo', array( 'height' => 128, 'width' => 510, 'flex-height' => true, 'flex-width' => true ) );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
		register_nav_menus( array( 'primary' => __( 'Navegación principal', 'chef-en-casa' ) ) );
	}
);

add_action(
	'wp_enqueue_scripts',
	static function (): void {
		$theme_path = get_template_directory();
		wp_enqueue_style( 'chef-fonts', 'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap', array(), null );
		wp_enqueue_style( 'chef-tokens', get_template_directory_uri() . '/assets/css/design-tokens.css', array(), (string) filemtime( $theme_path . '/assets/css/design-tokens.css' ) );
		wp_enqueue_style( 'chef-theme', get_stylesheet_uri(), array( 'chef-fonts', 'chef-tokens' ), (string) filemtime( get_stylesheet_directory() . '/style.css' ) );
		wp_enqueue_script( 'chef-theme', get_template_directory_uri() . '/assets/js/main.js', array(), (string) filemtime( $theme_path . '/assets/js/main.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
		if ( is_page( array( 'menus', 'platos' ) ) ) {
			wp_enqueue_script( 'chef-catalog', get_template_directory_uri() . '/assets/js/catalog.js', array(), (string) filemtime( $theme_path . '/assets/js/catalog.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
		}
	}
);

add_filter(
	'wp_resource_hints',
	static function ( array $urls, string $relation_type ): array {
		if ( 'preconnect' === $relation_type ) {
			$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' => 'anonymous' );
			$urls[] = 'https://fonts.googleapis.com';
		}
		return $urls;
	},
	10,
	2
);

require get_template_directory() . '/inc/customizer.php';
