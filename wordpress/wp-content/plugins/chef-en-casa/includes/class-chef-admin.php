<?php
/**
 * Basic protected administration interface.
 *
 * @package ChefEnCasa
 */

defined( 'ABSPATH' ) || exit;

final class Chef_Admin {
	public function register(): void {
		add_action( 'admin_menu', array( $this, 'menu' ) );
		add_action( 'admin_post_chef_save_entity', array( $this, 'save' ) );
	}

	public function menu(): void {
		add_menu_page( __( 'Chef en Casa', 'chef-en-casa' ), __( 'Chef en Casa', 'chef-en-casa' ), 'manage_options', 'chef-en-casa', array( $this, 'render' ), 'dashicons-food', 30 );
	}

	public function save(): void {
		if ( ! current_user_can( 'manage_options' ) ) { wp_die( esc_html__( 'No tienes permisos para realizar esta acción.', 'chef-en-casa' ) ); }
		check_admin_referer( 'chef_save_entity' );
		$entity = sanitize_key( wp_unslash( $_POST['entity'] ?? '' ) );
		$id     = absint( $_POST['id'] ?? 0 );
		$tabs   = array( 'food_type' => 'types', 'ingredient' => 'ingredients', 'dish' => 'dishes', 'menu' => 'menus' );
		if ( ! isset( $tabs[ $entity ] ) ) { wp_die( esc_html__( 'Entidad no válida.', 'chef-en-casa' ) ); }
		$method = 'save_' . $entity;
		$this->{$method}( $id );
		wp_safe_redirect( add_query_arg( array( 'page' => 'chef-en-casa', 'tab' => $tabs[ $entity ], 'saved' => 1 ), admin_url( 'admin.php' ) ) );
		exit;
	}

	private function save_food_type( int $id ): void {
		global $wpdb;
		$table = $wpdb->prefix . 'chef_food_types'; $now = current_time( 'mysql', true );
		$data = array( 'name' => sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) ), 'slug' => sanitize_title( wp_unslash( $_POST['slug'] ?? $_POST['name'] ?? '' ) ), 'description' => sanitize_textarea_field( wp_unslash( $_POST['description'] ?? '' ) ), 'active' => isset( $_POST['active'] ) ? 1 : 0, 'updated_at' => $now );
		$id ? $wpdb->update( $table, $data, array( 'id' => $id ) ) : $wpdb->insert( $table, $data + array( 'created_at' => $now ) );
	}

	private function save_ingredient( int $id ): void {
		global $wpdb;
		$table = $wpdb->prefix . 'chef_ingredients'; $links = $wpdb->prefix . 'chef_ingredient_food_type'; $now = current_time( 'mysql', true );
		$data = array( 'name' => sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) ), 'slug' => sanitize_title( wp_unslash( $_POST['slug'] ?? $_POST['name'] ?? '' ) ), 'description' => sanitize_textarea_field( wp_unslash( $_POST['description'] ?? '' ) ), 'unit_measure' => sanitize_text_field( wp_unslash( $_POST['unit_measure'] ?? 'g' ) ), 'calories_100g' => (float) ( $_POST['calories_100g'] ?? 0 ), 'proteins_100g' => (float) ( $_POST['proteins_100g'] ?? 0 ), 'carbohydrates_100g' => (float) ( $_POST['carbohydrates_100g'] ?? 0 ), 'fats_100g' => (float) ( $_POST['fats_100g'] ?? 0 ), 'fiber_100g' => (float) ( $_POST['fiber_100g'] ?? 0 ), 'active' => isset( $_POST['active'] ) ? 1 : 0, 'updated_at' => $now );
		if ( $id ) { $wpdb->update( $table, $data, array( 'id' => $id ) ); } else { $wpdb->insert( $table, $data + array( 'created_at' => $now ) ); $id = (int) $wpdb->insert_id; }
		$wpdb->delete( $links, array( 'ingredient_id' => $id ) );
		foreach ( array_map( 'absint', (array) ( $_POST['food_types'] ?? array() ) ) as $type_id ) { $wpdb->insert( $links, array( 'ingredient_id' => $id, 'food_type_id' => $type_id ) ); }
	}

	private function save_dish( int $id ): void {
		global $wpdb;
		$table = $wpdb->prefix . 'chef_dishes'; $components = $wpdb->prefix . 'chef_dish_ingredient'; $types = $wpdb->prefix . 'chef_dish_food_type'; $now = current_time( 'mysql', true );
		$data = array( 'name' => sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) ), 'slug' => sanitize_title( wp_unslash( $_POST['slug'] ?? $_POST['name'] ?? '' ) ), 'description' => sanitize_textarea_field( wp_unslash( $_POST['description'] ?? '' ) ), 'image' => esc_url_raw( wp_unslash( $_POST['image'] ?? '' ) ), 'preparation_type' => sanitize_text_field( wp_unslash( $_POST['preparation_type'] ?? '' ) ), 'servings' => max( 1, absint( $_POST['servings'] ?? 1 ) ), 'instructions' => sanitize_textarea_field( wp_unslash( $_POST['instructions'] ?? '' ) ), 'active' => isset( $_POST['active'] ) ? 1 : 0, 'updated_at' => $now );
		if ( $id ) { $wpdb->update( $table, $data, array( 'id' => $id ) ); } else { $wpdb->insert( $table, $data + array( 'created_at' => $now ) ); $id = (int) $wpdb->insert_id; }
		$wpdb->delete( $components, array( 'dish_id' => $id ) ); $wpdb->delete( $types, array( 'dish_id' => $id ) );
		foreach ( (array) ( $_POST['ingredient_quantities'] ?? array() ) as $ingredient_id => $quantity ) { $quantity = (float) $quantity; if ( $quantity > 0 ) { $wpdb->insert( $components, array( 'dish_id' => $id, 'ingredient_id' => absint( $ingredient_id ), 'quantity' => $quantity, 'unit_measure' => 'g' ) ); } }
		foreach ( array_map( 'absint', (array) ( $_POST['food_types'] ?? array() ) ) as $type_id ) { $wpdb->insert( $types, array( 'dish_id' => $id, 'food_type_id' => $type_id ) ); }
	}

	private function save_menu( int $id ): void {
		global $wpdb;
		$table = $wpdb->prefix . 'chef_menus'; $links = $wpdb->prefix . 'chef_menu_dish'; $now = current_time( 'mysql', true );
		$data = array( 'name' => sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) ), 'slug' => sanitize_title( wp_unslash( $_POST['slug'] ?? $_POST['name'] ?? '' ) ), 'description' => sanitize_textarea_field( wp_unslash( $_POST['description'] ?? '' ) ), 'food_type_id' => absint( $_POST['food_type_id'] ?? 0 ), 'active' => isset( $_POST['active'] ) ? 1 : 0, 'updated_at' => $now );
		if ( $id ) { $wpdb->update( $table, $data, array( 'id' => $id ) ); } else { $wpdb->insert( $table, $data + array( 'created_at' => $now ) ); $id = (int) $wpdb->insert_id; }
		$wpdb->delete( $links, array( 'menu_id' => $id ) ); $order = 1;
		foreach ( array_map( 'absint', (array) ( $_POST['dishes'] ?? array() ) ) as $dish_id ) { $course = sanitize_text_field( wp_unslash( $_POST['dish_courses'][ $dish_id ] ?? 'Plato principal' ) ); $wpdb->insert( $links, array( 'menu_id' => $id, 'dish_id' => $dish_id, 'course_type' => $course, 'sort_order' => $order++ ) ); }
	}

	public function render(): void {
		if ( ! current_user_can( 'manage_options' ) ) { return; }
		$tab = sanitize_key( wp_unslash( $_GET['tab'] ?? 'dashboard' ) ); $allowed = array( 'dashboard', 'types', 'ingredients', 'dishes', 'menus' ); if ( ! in_array( $tab, $allowed, true ) ) { $tab = 'dashboard'; }
		?>
		<div class="wrap chef-admin"><h1><?php esc_html_e( 'Chef en Casa', 'chef-en-casa' ); ?></h1>
		<?php if ( isset( $_GET['saved'] ) ) : ?><div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Cambios guardados correctamente.', 'chef-en-casa' ); ?></p></div><?php endif; ?>
		<nav class="nav-tab-wrapper"><?php foreach ( array( 'dashboard' => 'Resumen', 'types' => 'Tipos', 'ingredients' => 'Ingredientes', 'dishes' => 'Platos', 'menus' => 'Menús' ) as $key => $label ) : ?><a class="nav-tab <?php echo $tab === $key ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( array( 'page' => 'chef-en-casa', 'tab' => $key ), admin_url( 'admin.php' ) ) ); ?>"><?php echo esc_html( $label ); ?></a><?php endforeach; ?></nav>
		<style>.chef-admin .chef-grid{display:grid;grid-template-columns:minmax(320px,1fr) 2fr;gap:24px;margin-top:24px}.chef-admin .chef-card{background:#fff;border:1px solid #dcdcde;padding:20px}.chef-admin input[type=text],.chef-admin input[type=number],.chef-admin textarea,.chef-admin select{width:100%}.chef-admin label{display:block;font-weight:600;margin:12px 0 5px}.chef-admin .checks label{font-weight:400;margin:5px 0}.chef-admin table{margin-top:0}@media(max-width:900px){.chef-admin .chef-grid{grid-template-columns:1fr}}</style>
		<?php $method = 'render_' . $tab; $this->{$method}(); ?></div><?php
	}

	private function form_open( string $entity, int $id ): void { ?><form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"><input type="hidden" name="action" value="chef_save_entity"><input type="hidden" name="entity" value="<?php echo esc_attr( $entity ); ?>"><input type="hidden" name="id" value="<?php echo esc_attr( (string) $id ); ?>"><?php wp_nonce_field( 'chef_save_entity' ); ?><?php }
	private function form_close(): void { submit_button( __( 'Guardar', 'chef-en-casa' ) ); ?></form><?php }
	private function field( string $name, string $label, mixed $value = '', string $type = 'text' ): void { ?><label for="chef-<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $label ); ?></label><?php if ( 'textarea' === $type ) : ?><textarea id="chef-<?php echo esc_attr( $name ); ?>" name="<?php echo esc_attr( $name ); ?>" rows="3"><?php echo esc_textarea( (string) $value ); ?></textarea><?php else : ?><input id="chef-<?php echo esc_attr( $name ); ?>" type="<?php echo esc_attr( $type ); ?>" step="any" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( (string) $value ); ?>"><?php endif; ?><?php }
	private function active( mixed $value = 1 ): void { ?><label><input type="checkbox" name="active" value="1" <?php checked( (int) $value, 1 ); ?>> <?php esc_html_e( 'Activo', 'chef-en-casa' ); ?></label><?php }

	private function render_dashboard(): void { global $wpdb; $tables = array( 'Tipos' => 'chef_food_types', 'Ingredientes' => 'chef_ingredients', 'Platos' => 'chef_dishes', 'Menús' => 'chef_menus' ); ?><div class="chef-grid" style="grid-template-columns:repeat(4,1fr)"><?php foreach ( $tables as $label => $suffix ) : $count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}{$suffix}" ); ?><div class="chef-card"><h2><?php echo esc_html( (string) $count ); ?></h2><p><?php echo esc_html( $label ); ?></p></div><?php endforeach; ?></div><?php }
	private function render_types(): void { $this->render_simple_entity( 'food_type' ); }
	private function render_ingredients(): void { $this->render_simple_entity( 'ingredient' ); }
	private function render_dishes(): void { $this->render_simple_entity( 'dish' ); }
	private function render_menus(): void { $this->render_simple_entity( 'menu' ); }

	private function render_simple_entity( string $entity ): void {
		global $wpdb; $config = array( 'food_type' => array( 'chef_food_types', 'types', 'Tipo de alimentación' ), 'ingredient' => array( 'chef_ingredients', 'ingredients', 'Ingrediente' ), 'dish' => array( 'chef_dishes', 'dishes', 'Plato' ), 'menu' => array( 'chef_menus', 'menus', 'Menú' ) )[ $entity ];
		$table = $wpdb->prefix . $config[0]; $edit_id = absint( $_GET['edit'] ?? 0 ); $item = $edit_id ? $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE id=%d", $edit_id ), ARRAY_A ) : array(); $rows = $wpdb->get_results( "SELECT * FROM {$table} ORDER BY name", ARRAY_A );
		?><div class="chef-grid"><div class="chef-card"><h2><?php echo esc_html( $edit_id ? 'Editar ' . $config[2] : 'Nuevo ' . $config[2] ); ?></h2><?php $this->form_open( $entity, $edit_id ); $this->entity_fields( $entity, $item ); $this->form_close(); ?></div><div><table class="widefat striped"><thead><tr><th><?php esc_html_e( 'Nombre', 'chef-en-casa' ); ?></th><th><?php esc_html_e( 'Estado', 'chef-en-casa' ); ?></th><th></th></tr></thead><tbody><?php foreach ( $rows as $row ) : ?><tr><td><?php echo esc_html( $row['name'] ); ?></td><td><?php echo (int) $row['active'] ? 'Activo' : 'Inactivo'; ?></td><td><a href="<?php echo esc_url( add_query_arg( array( 'page' => 'chef-en-casa', 'tab' => $config[1], 'edit' => $row['id'] ), admin_url( 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Editar', 'chef-en-casa' ); ?></a></td></tr><?php endforeach; ?></tbody></table></div></div><?php
	}

	private function entity_fields( string $entity, array $item ): void {
		global $wpdb; $this->field( 'name', 'Nombre', $item['name'] ?? '' ); $this->field( 'slug', 'Slug', $item['slug'] ?? '' ); $this->field( 'description', 'Descripción', $item['description'] ?? '', 'textarea' );
		if ( 'ingredient' === $entity ) { $this->field( 'unit_measure', 'Unidad', $item['unit_measure'] ?? 'g' ); foreach ( array( 'calories_100g' => 'Calorías / 100 g', 'proteins_100g' => 'Proteínas / 100 g', 'carbohydrates_100g' => 'Carbohidratos / 100 g', 'fats_100g' => 'Grasas / 100 g', 'fiber_100g' => 'Fibra / 100 g' ) as $key => $label ) { $this->field( $key, $label, $item[ $key ] ?? 0, 'number' ); } $this->food_type_checks( 'ingredient', (int) ( $item['id'] ?? 0 ) ); }
		if ( 'dish' === $entity ) { $this->field( 'image', 'URL de imagen', $item['image'] ?? '' ); $this->field( 'preparation_type', 'Tipo de preparación', $item['preparation_type'] ?? '' ); $this->field( 'servings', 'Porciones', $item['servings'] ?? 1, 'number' ); $this->field( 'instructions', 'Instrucciones', $item['instructions'] ?? '', 'textarea' ); $this->food_type_checks( 'dish', (int) ( $item['id'] ?? 0 ) ); $this->ingredient_quantities( (int) ( $item['id'] ?? 0 ) ); }
		if ( 'menu' === $entity ) { $types = $wpdb->get_results( "SELECT id,name FROM {$wpdb->prefix}chef_food_types WHERE active=1 ORDER BY name", ARRAY_A ); ?><label>Tipo de alimentación</label><select name="food_type_id"><?php foreach ( $types as $type ) : ?><option value="<?php echo esc_attr( $type['id'] ); ?>" <?php selected( (int) ( $item['food_type_id'] ?? 0 ), (int) $type['id'] ); ?>><?php echo esc_html( $type['name'] ); ?></option><?php endforeach; ?></select><?php $this->menu_dishes( (int) ( $item['id'] ?? 0 ) ); }
		$this->active( $item['active'] ?? 1 );
	}

	private function food_type_checks( string $entity, int $id ): void { global $wpdb; $types = $wpdb->get_results( "SELECT id,name FROM {$wpdb->prefix}chef_food_types WHERE active=1 ORDER BY name", ARRAY_A ); $table = $wpdb->prefix . ( 'dish' === $entity ? 'chef_dish_food_type' : 'chef_ingredient_food_type' ); $column = 'dish' === $entity ? 'dish_id' : 'ingredient_id'; $selected = $id ? array_map( 'intval', $wpdb->get_col( $wpdb->prepare( "SELECT food_type_id FROM {$table} WHERE {$column}=%d", $id ) ) ) : array(); ?><label>Tipos de alimentación</label><div class="checks"><?php foreach ( $types as $type ) : ?><label><input type="checkbox" name="food_types[]" value="<?php echo esc_attr( $type['id'] ); ?>" <?php checked( in_array( (int) $type['id'], $selected, true ) ); ?>> <?php echo esc_html( $type['name'] ); ?></label><?php endforeach; ?></div><?php }
	private function ingredient_quantities( int $dish_id ): void { global $wpdb; $items = $wpdb->get_results( "SELECT id,name FROM {$wpdb->prefix}chef_ingredients WHERE active=1 ORDER BY name", ARRAY_A ); $quantities = $dish_id ? array_column( $wpdb->get_results( $wpdb->prepare( "SELECT ingredient_id,quantity FROM {$wpdb->prefix}chef_dish_ingredient WHERE dish_id=%d", $dish_id ), ARRAY_A ), 'quantity', 'ingredient_id' ) : array(); ?><label>Ingredientes (gramos)</label><?php foreach ( $items as $ingredient ) : ?><div style="display:grid;grid-template-columns:1fr 90px;gap:8px;margin:5px 0"><span><?php echo esc_html( $ingredient['name'] ); ?></span><input type="number" min="0" step="0.1" name="ingredient_quantities[<?php echo esc_attr( $ingredient['id'] ); ?>]" value="<?php echo esc_attr( $quantities[ $ingredient['id'] ] ?? '' ); ?>"></div><?php endforeach; ?><?php }
	private function menu_dishes( int $menu_id ): void { global $wpdb; $dishes = $wpdb->get_results( "SELECT id,name FROM {$wpdb->prefix}chef_dishes WHERE active=1 ORDER BY name", ARRAY_A ); $selected = $menu_id ? array_column( $wpdb->get_results( $wpdb->prepare( "SELECT dish_id,course_type FROM {$wpdb->prefix}chef_menu_dish WHERE menu_id=%d", $menu_id ), ARRAY_A ), 'course_type', 'dish_id' ) : array(); ?><label>Platos y curso</label><?php foreach ( $dishes as $dish ) : ?><div style="display:grid;grid-template-columns:24px 1fr 150px;gap:6px;margin:6px 0"><input type="checkbox" name="dishes[]" value="<?php echo esc_attr( $dish['id'] ); ?>" <?php checked( isset( $selected[ $dish['id'] ] ) ); ?>><span><?php echo esc_html( $dish['name'] ); ?></span><select name="dish_courses[<?php echo esc_attr( $dish['id'] ); ?>]"><?php foreach ( array( 'Entrada', 'Plato principal', 'Acompañamiento', 'Postre' ) as $course ) : ?><option <?php selected( $selected[ $dish['id'] ] ?? '', $course ); ?>><?php echo esc_html( $course ); ?></option><?php endforeach; ?></select></div><?php endforeach; ?><?php }
}

