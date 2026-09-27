<?php
/**
 * Database installation and demo data.
 *
 * @package ChefEnCasa
 */

defined( 'ABSPATH' ) || exit;

final class Chef_Activator {
	public static function activate(): void {
		self::create_tables();
		self::seed_data();
		Chef_Dish_Installer::install();
		update_option( 'chef_en_casa_db_version', CHEF_EN_CASA_DB_VERSION, false );
	}

	private static function create_tables(): void {
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$charset = $wpdb->get_charset_collate();
		$types   = $wpdb->prefix . 'chef_food_types';
		$items   = $wpdb->prefix . 'chef_ingredients';
		$links   = $wpdb->prefix . 'chef_ingredient_food_type';

		dbDelta( "CREATE TABLE {$types} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			name varchar(120) NOT NULL,
			slug varchar(120) NOT NULL,
			description text NOT NULL,
			active tinyint(1) NOT NULL DEFAULT 1,
			created_at datetime NOT NULL,
			updated_at datetime NOT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY slug (slug),
			KEY active (active)
		) {$charset};" );

		dbDelta( "CREATE TABLE {$items} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			name varchar(140) NOT NULL,
			slug varchar(140) NOT NULL,
			description text NOT NULL,
			unit_measure varchar(30) NOT NULL DEFAULT 'g',
			calories_100g decimal(8,2) DEFAULT NULL,
			proteins_100g decimal(8,2) DEFAULT NULL,
			carbohydrates_100g decimal(8,2) DEFAULT NULL,
			fats_100g decimal(8,2) DEFAULT NULL,
			fiber_100g decimal(8,2) DEFAULT NULL,
			active tinyint(1) NOT NULL DEFAULT 1,
			created_at datetime NOT NULL,
			updated_at datetime NOT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY slug (slug),
			KEY active (active)
		) {$charset};" );

		dbDelta( "CREATE TABLE {$links} (
			ingredient_id bigint(20) unsigned NOT NULL,
			food_type_id bigint(20) unsigned NOT NULL,
			PRIMARY KEY  (ingredient_id,food_type_id),
			KEY food_type_id (food_type_id)
		) {$charset};" );
	}

	private static function seed_data(): void {
		global $wpdb;
		$types_table = $wpdb->prefix . 'chef_food_types';
		$items_table = $wpdb->prefix . 'chef_ingredients';
		$links_table = $wpdb->prefix . 'chef_ingredient_food_type';
		$now         = current_time( 'mysql', true );

		$types = array(
			'hipocalorica'  => array( 'Hipocalórica', 'Preparaciones de densidad calórica moderada, con énfasis en verduras, proteínas y porciones planificadas.' ),
			'baja-en-grasa' => array( 'Baja en grasa', 'Alternativas con selección de cortes magros y técnicas de cocción que moderan el uso de grasas.' ),
			'vegana'        => array( 'Vegana', 'Preparaciones basadas exclusivamente en ingredientes de origen vegetal.' ),
		);

		$type_ids = array();
		foreach ( $types as $slug => $type ) {
			$id = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$types_table} WHERE slug = %s", $slug ) );
			if ( ! $id ) {
				$wpdb->insert( $types_table, array( 'name' => $type[0], 'slug' => $slug, 'description' => $type[1], 'active' => 1, 'created_at' => $now, 'updated_at' => $now ), array( '%s', '%s', '%s', '%d', '%s', '%s' ) );
				$id = $wpdb->insert_id;
			}
			$type_ids[ $slug ] = (int) $id;
		}

		$ingredients = self::ingredient_seed();
		foreach ( $ingredients as $ingredient ) {
			$id = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$items_table} WHERE slug = %s", $ingredient['slug'] ) );
			if ( ! $id ) {
				$wpdb->insert(
					$items_table,
					array(
						'name' => $ingredient['name'], 'slug' => $ingredient['slug'], 'description' => $ingredient['description'], 'unit_measure' => 'g',
						'calories_100g' => $ingredient['nutrition'][0], 'proteins_100g' => $ingredient['nutrition'][1], 'carbohydrates_100g' => $ingredient['nutrition'][2], 'fats_100g' => $ingredient['nutrition'][3], 'fiber_100g' => $ingredient['nutrition'][4],
						'active' => 1, 'created_at' => $now, 'updated_at' => $now,
					),
					array( '%s', '%s', '%s', '%s', '%f', '%f', '%f', '%f', '%f', '%d', '%s', '%s' )
				);
				$id = $wpdb->insert_id;
			}

			foreach ( $ingredient['types'] as $type_slug ) {
				if ( isset( $type_ids[ $type_slug ] ) ) {
					$wpdb->query( $wpdb->prepare( "INSERT IGNORE INTO {$links_table} (ingredient_id, food_type_id) VALUES (%d, %d)", $id, $type_ids[ $type_slug ] ) );
				}
			}
		}
	}

	private static function ingredient_seed(): array {
		$all   = array( 'hipocalorica', 'baja-en-grasa', 'vegana' );
		$plant = array( 'hipocalorica', 'baja-en-grasa', 'vegana' );
		return array(
			array( 'name' => 'Tomate', 'slug' => 'tomate', 'description' => 'Tomate fresco de uso versátil.', 'nutrition' => array( 18, 0.9, 3.9, 0.2, 1.2 ), 'types' => $plant ),
			array( 'name' => 'Lechuga', 'slug' => 'lechuga', 'description' => 'Hoja fresca para ensaladas y acompañamientos.', 'nutrition' => array( 15, 1.4, 2.9, 0.2, 1.3 ), 'types' => $all ),
			array( 'name' => 'Espinaca', 'slug' => 'espinaca', 'description' => 'Hoja verde fresca o cocida.', 'nutrition' => array( 23, 2.9, 3.6, 0.4, 2.2 ), 'types' => $all ),
			array( 'name' => 'Pechuga de pollo', 'slug' => 'pechuga-de-pollo', 'description' => 'Corte magro de pollo sin piel.', 'nutrition' => array( 165, 31, 0, 3.6, 0 ), 'types' => array( 'hipocalorica', 'baja-en-grasa' ) ),
			array( 'name' => 'Pavo', 'slug' => 'pavo', 'description' => 'Carne magra de pavo.', 'nutrition' => array( 135, 29, 0, 1.8, 0 ), 'types' => array( 'hipocalorica', 'baja-en-grasa' ) ),
			array( 'name' => 'Arroz integral', 'slug' => 'arroz-integral', 'description' => 'Cereal integral para bases y acompañamientos.', 'nutrition' => array( 123, 2.7, 25.6, 1, 1.6 ), 'types' => $all ),
			array( 'name' => 'Quinoa', 'slug' => 'quinoa', 'description' => 'Pseudocereal de sabor suave.', 'nutrition' => array( 120, 4.4, 21.3, 1.9, 2.8 ), 'types' => $all ),
			array( 'name' => 'Papa', 'slug' => 'papa', 'description' => 'Tubérculo para preparaciones horneadas o cocidas.', 'nutrition' => array( 77, 2, 17.5, 0.1, 2.2 ), 'types' => array( 'baja-en-grasa', 'vegana' ) ),
			array( 'name' => 'Zapallo italiano', 'slug' => 'zapallo-italiano', 'description' => 'Verdura ligera de textura suave.', 'nutrition' => array( 17, 1.2, 3.1, 0.3, 1 ), 'types' => $all ),
			array( 'name' => 'Lentejas', 'slug' => 'lentejas', 'description' => 'Legumbre cocida rica en fibra.', 'nutrition' => array( 116, 9, 20.1, 0.4, 7.9 ), 'types' => array( 'hipocalorica', 'vegana' ) ),
			array( 'name' => 'Garbanzos', 'slug' => 'garbanzos', 'description' => 'Legumbre cocida para platos y cremas.', 'nutrition' => array( 164, 8.9, 27.4, 2.6, 7.6 ), 'types' => array( 'vegana' ) ),
			array( 'name' => 'Tofu', 'slug' => 'tofu', 'description' => 'Alimento vegetal a base de soya.', 'nutrition' => array( 76, 8.1, 1.9, 4.8, 0.3 ), 'types' => array( 'hipocalorica', 'vegana' ) ),
			array( 'name' => 'Aceite de oliva', 'slug' => 'aceite-de-oliva', 'description' => 'Grasa vegetal para aliños y cocción moderada.', 'nutrition' => array( 884, 0, 0, 100, 0 ), 'types' => array( 'vegana' ) ),
			array( 'name' => 'Palta', 'slug' => 'palta', 'description' => 'Fruto cremoso utilizado en ensaladas y acompañamientos.', 'nutrition' => array( 160, 2, 8.5, 14.7, 6.7 ), 'types' => array( 'vegana' ) ),
			array( 'name' => 'Brócoli', 'slug' => 'brocoli', 'description' => 'Vegetal crucífero para preparaciones al vapor o salteadas.', 'nutrition' => array( 34, 2.8, 6.6, 0.4, 2.6 ), 'types' => $all ),
		);
	}
}
