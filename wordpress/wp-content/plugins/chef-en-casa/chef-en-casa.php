<?php
/**
 * Plugin Name: Chef en Casa Core
 * Description: Lógica de dominio, persistencia y API para Chef en Casa.
 * Version: 0.4.0
 * Requires at least: 6.5
 * Requires PHP: 8.2
 * Text Domain: chef-en-casa
 */

defined( 'ABSPATH' ) || exit;

define( 'CHEF_EN_CASA_VERSION', '0.4.0' );
define( 'CHEF_EN_CASA_DB_VERSION', '0.4.0' );
define( 'CHEF_EN_CASA_FILE', __FILE__ );
define( 'CHEF_EN_CASA_PATH', plugin_dir_path( __FILE__ ) );

require_once CHEF_EN_CASA_PATH . 'includes/class-chef-dish-service.php';
require_once CHEF_EN_CASA_PATH . 'includes/class-chef-dish-installer.php';
require_once CHEF_EN_CASA_PATH . 'includes/class-chef-menu-service.php';
require_once CHEF_EN_CASA_PATH . 'includes/class-chef-menu-installer.php';
require_once CHEF_EN_CASA_PATH . 'includes/class-chef-page-installer.php';
require_once CHEF_EN_CASA_PATH . 'includes/class-chef-admin.php';
require_once CHEF_EN_CASA_PATH . 'includes/class-chef-activator.php';
require_once CHEF_EN_CASA_PATH . 'includes/class-chef-rest-controller.php';

register_activation_hook( __FILE__, array( 'Chef_Activator', 'activate' ) );

add_action(
	'plugins_loaded',
	static function (): void {
		if ( CHEF_EN_CASA_DB_VERSION !== get_option( 'chef_en_casa_db_version' ) ) {
			Chef_Activator::activate();
		}
	}
);

if ( is_admin() ) {
	( new Chef_Admin() )->register();
}

add_action(
	'rest_api_init',
	static function (): void {
		( new Chef_REST_Controller() )->register_routes();
	}
);
