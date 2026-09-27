<?php
/**
 * Menu tables and demonstration records.
 *
 * @package ChefEnCasa
 */

defined( 'ABSPATH' ) || exit;

final class Chef_Menu_Installer {
	public static function install(): void {
		self::create_tables();
		self::seed_menus();
	}

	private static function create_tables(): void {
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$charset = $wpdb->get_charset_collate();
		$menus   = $wpdb->prefix . 'chef_menus';
		$links   = $wpdb->prefix . 'chef_menu_dish';
		dbDelta( "CREATE TABLE {$menus} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			name varchar(160) NOT NULL,
			slug varchar(160) NOT NULL,
			description text NOT NULL,
			food_type_id bigint(20) unsigned NOT NULL,
			active tinyint(1) NOT NULL DEFAULT 1,
			created_at datetime NOT NULL,
			updated_at datetime NOT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY slug (slug),
			KEY food_type_id (food_type_id),
			KEY active (active)
		) {$charset};" );
		dbDelta( "CREATE TABLE {$links} (
			menu_id bigint(20) unsigned NOT NULL,
			dish_id bigint(20) unsigned NOT NULL,
			course_type varchar(60) NOT NULL DEFAULT 'Plato principal',
			sort_order smallint(5) unsigned NOT NULL DEFAULT 0,
			PRIMARY KEY  (menu_id,dish_id),
			KEY dish_id (dish_id)
		) {$charset};" );
	}

	private static function seed_menus(): void {
		global $wpdb;
		$menus_table  = $wpdb->prefix . 'chef_menus';
		$links_table  = $wpdb->prefix . 'chef_menu_dish';
		$dishes_table = $wpdb->prefix . 'chef_dishes';
		$types_table  = $wpdb->prefix . 'chef_food_types';
		$dishes       = $wpdb->get_results( "SELECT slug, id FROM {$dishes_table}", OBJECT_K );
		$type_rows    = $wpdb->get_results( "SELECT slug, id FROM {$types_table}", OBJECT_K );
		$now          = current_time( 'mysql', true );

		foreach ( self::menu_seed() as $menu ) {
			$type = $type_rows[ $menu['type'] ] ?? null;
			if ( ! $type ) { continue; }
			$menu_id = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$menus_table} WHERE slug = %s", $menu['slug'] ) );
			if ( ! $menu_id ) {
				$wpdb->insert( $menus_table, array( 'name' => $menu['name'], 'slug' => $menu['slug'], 'description' => $menu['description'], 'food_type_id' => $type->id, 'active' => 1, 'created_at' => $now, 'updated_at' => $now ), array( '%s', '%s', '%s', '%d', '%d', '%s', '%s' ) );
				$menu_id = $wpdb->insert_id;
			}
			foreach ( $menu['dishes'] as $order => $dish_data ) {
				$dish = $dishes[ $dish_data[0] ] ?? null;
				if ( $dish ) {
					$wpdb->query( $wpdb->prepare( "INSERT INTO {$links_table} (menu_id,dish_id,course_type,sort_order) VALUES (%d,%d,%s,%d) ON DUPLICATE KEY UPDATE course_type=VALUES(course_type),sort_order=VALUES(sort_order)", $menu_id, $dish->id, $dish_data[1], $order + 1 ) );
				}
			}
		}
	}

	private static function menu_seed(): array {
		return array(
			array( 'name' => 'Menú Hipocalórico Día 1', 'slug' => 'menu-hipocalorico-dia-1', 'type' => 'hipocalorica', 'description' => 'Alternativa fresca y ligera para una jornada equilibrada.', 'dishes' => array( array( 'ensalada-citrica-de-pollo', 'Plato principal' ), array( 'ensalada-tibia-papa-brocoli', 'Acompañamiento' ) ) ),
			array( 'name' => 'Menú Hipocalórico Día 2', 'slug' => 'menu-hipocalorico-dia-2', 'type' => 'hipocalorica', 'description' => 'Proteína magra, granos y vegetales.', 'dishes' => array( array( 'bowl-ligero-de-pavo-y-quinoa', 'Plato principal' ), array( 'lentejas-verdes-con-espinaca', 'Entrada' ) ) ),
			array( 'name' => 'Menú Bajo en Grasa Día 1', 'slug' => 'menu-bajo-en-grasa-dia-1', 'type' => 'baja-en-grasa', 'description' => 'Cocciones simples con cortes magros.', 'dishes' => array( array( 'pollo-arroz-integral-brocoli', 'Plato principal' ), array( 'ensalada-tibia-papa-brocoli', 'Acompañamiento' ) ) ),
			array( 'name' => 'Menú Bajo en Grasa Día 2', 'slug' => 'menu-bajo-en-grasa-dia-2', 'type' => 'baja-en-grasa', 'description' => 'Sabores suaves y vegetales de temporada.', 'dishes' => array( array( 'pavo-salteado-zapallo-italiano', 'Plato principal' ), array( 'ensalada-citrica-de-pollo', 'Entrada' ) ) ),
			array( 'name' => 'Menú Vegano Día 1', 'slug' => 'menu-vegano-dia-1', 'type' => 'vegana', 'description' => 'Una selección vegetal fresca y colorida.', 'dishes' => array( array( 'ensalada-mediterranea-quinoa', 'Entrada' ), array( 'tofu-dorado-vegetales', 'Plato principal' ) ) ),
			array( 'name' => 'Menú Vegano Día 2', 'slug' => 'menu-vegano-dia-2', 'type' => 'vegana', 'description' => 'Legumbres y vegetales para una propuesta completa.', 'dishes' => array( array( 'bowl-garbanzos-lentejas', 'Plato principal' ), array( 'ensalada-tibia-papa-brocoli', 'Acompañamiento' ) ) ),
		);
	}
}
