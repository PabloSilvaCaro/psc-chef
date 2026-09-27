<?php
/**
 * Dish tables and demonstration records.
 *
 * @package ChefEnCasa
 */

defined( 'ABSPATH' ) || exit;

final class Chef_Dish_Installer {
	public static function install(): void {
		self::create_tables();
		self::seed_dishes();
	}

	private static function create_tables(): void {
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$charset    = $wpdb->get_charset_collate();
		$dishes     = $wpdb->prefix . 'chef_dishes';
		$components = $wpdb->prefix . 'chef_dish_ingredient';
		$types      = $wpdb->prefix . 'chef_dish_food_type';

		dbDelta( "CREATE TABLE {$dishes} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			name varchar(160) NOT NULL,
			slug varchar(160) NOT NULL,
			description text NOT NULL,
			image varchar(255) NOT NULL DEFAULT '',
			preparation_type varchar(80) NOT NULL,
			servings smallint(5) unsigned NOT NULL DEFAULT 1,
			instructions longtext NOT NULL,
			active tinyint(1) NOT NULL DEFAULT 1,
			created_at datetime NOT NULL,
			updated_at datetime NOT NULL,
			PRIMARY KEY  (id),
			UNIQUE KEY slug (slug),
			KEY active (active)
		) {$charset};" );

		dbDelta( "CREATE TABLE {$components} (
			dish_id bigint(20) unsigned NOT NULL,
			ingredient_id bigint(20) unsigned NOT NULL,
			quantity decimal(10,2) NOT NULL,
			unit_measure varchar(30) NOT NULL DEFAULT 'g',
			PRIMARY KEY  (dish_id,ingredient_id),
			KEY ingredient_id (ingredient_id)
		) {$charset};" );

		dbDelta( "CREATE TABLE {$types} (
			dish_id bigint(20) unsigned NOT NULL,
			food_type_id bigint(20) unsigned NOT NULL,
			PRIMARY KEY  (dish_id,food_type_id),
			KEY food_type_id (food_type_id)
		) {$charset};" );
	}

	private static function seed_dishes(): void {
		global $wpdb;
		$dishes_table     = $wpdb->prefix . 'chef_dishes';
		$ingredients_table = $wpdb->prefix . 'chef_ingredients';
		$food_types_table  = $wpdb->prefix . 'chef_food_types';
		$components_table  = $wpdb->prefix . 'chef_dish_ingredient';
		$dish_types_table  = $wpdb->prefix . 'chef_dish_food_type';
		$now               = current_time( 'mysql', true );

		$ingredient_ids = $wpdb->get_results( "SELECT slug, id FROM {$ingredients_table}", OBJECT_K );
		$type_rows      = $wpdb->get_results( "SELECT id, slug FROM {$food_types_table}", ARRAY_A );
		$type_ids       = array_column( $type_rows, 'id', 'slug' );

		foreach ( self::dish_seed() as $dish ) {
			$dish_id = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$dishes_table} WHERE slug = %s", $dish['slug'] ) );
			if ( ! $dish_id ) {
				$wpdb->insert(
					$dishes_table,
					array( 'name' => $dish['name'], 'slug' => $dish['slug'], 'description' => $dish['description'], 'image' => '', 'preparation_type' => $dish['preparation_type'], 'servings' => $dish['servings'], 'instructions' => $dish['instructions'], 'active' => 1, 'created_at' => $now, 'updated_at' => $now ),
					array( '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%d', '%s', '%s' )
				);
				$dish_id = $wpdb->insert_id;
			}

			foreach ( $dish['ingredients'] as $ingredient_slug => $quantity ) {
				$ingredient = $ingredient_ids[ $ingredient_slug ] ?? null;
				if ( $ingredient ) {
					$wpdb->query( $wpdb->prepare( "INSERT INTO {$components_table} (dish_id, ingredient_id, quantity, unit_measure) VALUES (%d, %d, %f, 'g') ON DUPLICATE KEY UPDATE quantity = VALUES(quantity), unit_measure = VALUES(unit_measure)", $dish_id, $ingredient->id, $quantity ) );
				}
			}

			foreach ( $dish['types'] as $type_slug ) {
				if ( isset( $type_ids[ $type_slug ] ) ) {
					$wpdb->query( $wpdb->prepare( "INSERT IGNORE INTO {$dish_types_table} (dish_id, food_type_id) VALUES (%d, %d)", $dish_id, $type_ids[ $type_slug ] ) );
				}
			}
		}
	}

	private static function dish_seed(): array {
		return array(
			self::dish( 'Ensalada cítrica de pollo', 'ensalada-citrica-de-pollo', 'Ensalada fresca con pollo magro, hojas y tomate.', 'Ensalada', 2, 'Cocinar el pollo, cortar los vegetales y mezclar antes de servir.', array( 'pechuga-de-pollo' => 240, 'lechuga' => 120, 'tomate' => 160, 'palta' => 50 ), array( 'hipocalorica', 'baja-en-grasa' ) ),
			self::dish( 'Bowl ligero de pavo y quinoa', 'bowl-ligero-de-pavo-y-quinoa', 'Pavo, quinoa y brócoli en un bowl equilibrado.', 'Bowl', 2, 'Cocinar la quinoa y el pavo; servir con brócoli al vapor.', array( 'pavo' => 240, 'quinoa' => 180, 'brocoli' => 180 ), array( 'hipocalorica', 'baja-en-grasa' ) ),
			self::dish( 'Lentejas verdes con espinaca', 'lentejas-verdes-con-espinaca', 'Guiso ligero de lentejas, espinaca y tomate.', 'Guiso', 3, 'Calentar las lentejas con tomate e incorporar la espinaca al final.', array( 'lentejas' => 360, 'espinaca' => 120, 'tomate' => 180 ), array( 'hipocalorica', 'vegana' ) ),
			self::dish( 'Pollo con arroz integral y brócoli', 'pollo-arroz-integral-brocoli', 'Pechuga dorada con arroz integral y vegetales.', 'Plato principal', 2, 'Cocinar los componentes por separado y servir calientes.', array( 'pechuga-de-pollo' => 280, 'arroz-integral' => 220, 'brocoli' => 200 ), array( 'baja-en-grasa', 'hipocalorica' ) ),
			self::dish( 'Pavo salteado con zapallo italiano', 'pavo-salteado-zapallo-italiano', 'Salteado rápido de pavo y zapallo italiano.', 'Salteado', 2, 'Saltear el pavo y agregar el zapallo hasta que esté tierno.', array( 'pavo' => 280, 'zapallo-italiano' => 260, 'tomate' => 120 ), array( 'baja-en-grasa', 'hipocalorica' ) ),
			self::dish( 'Ensalada tibia de papa y brócoli', 'ensalada-tibia-papa-brocoli', 'Papa cocida, brócoli y hojas verdes.', 'Acompañamiento', 3, 'Cocer papa y brócoli; mezclar aún tibios con las hojas.', array( 'papa' => 360, 'brocoli' => 220, 'espinaca' => 80 ), array( 'baja-en-grasa', 'vegana' ) ),
			self::dish( 'Ensalada mediterránea de quinoa', 'ensalada-mediterranea-quinoa', 'Quinoa, tomate, espinaca y palta.', 'Ensalada', 2, 'Mezclar la quinoa fría con los vegetales y servir.', array( 'quinoa' => 260, 'tomate' => 160, 'espinaca' => 100, 'palta' => 80 ), array( 'vegana' ) ),
			self::dish( 'Tofu dorado con vegetales', 'tofu-dorado-vegetales', 'Tofu dorado con zapallo italiano y brócoli.', 'Salteado', 2, 'Dorar el tofu y añadir los vegetales hasta lograr una textura tierna.', array( 'tofu' => 300, 'zapallo-italiano' => 240, 'brocoli' => 180, 'aceite-de-oliva' => 12 ), array( 'vegana', 'hipocalorica' ) ),
			self::dish( 'Bowl de garbanzos y lentejas', 'bowl-garbanzos-lentejas', 'Legumbres, tomate y hojas verdes en un bowl completo.', 'Bowl', 3, 'Combinar las legumbres cocidas con tomate y espinaca fresca.', array( 'garbanzos' => 260, 'lentejas' => 220, 'tomate' => 160, 'espinaca' => 90 ), array( 'vegana' ) ),
		);
	}

	private static function dish( string $name, string $slug, string $description, string $type, int $servings, string $instructions, array $ingredients, array $food_types ): array {
		return array( 'name' => $name, 'slug' => $slug, 'description' => $description, 'preparation_type' => $type, 'servings' => $servings, 'instructions' => $instructions, 'ingredients' => $ingredients, 'types' => $food_types );
	}
}
