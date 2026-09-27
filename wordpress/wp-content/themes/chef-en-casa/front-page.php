<?php
/**
 * Front page template.
 *
 * @package ChefEnCasa
 */

$theme_uri = get_template_directory_uri();
$featured_dishes = array();
$featured_menus  = class_exists( 'Chef_Menu_Service' ) ? array_slice( ( new Chef_Menu_Service() )->all(), 0, 3 ) : array();
$featured_slugs  = array( 'ensalada-mediterranea-quinoa', 'pollo-arroz-integral-brocoli', 'tofu-dorado-vegetales' );
$image_map = array(
	'ensalada-citrica-de-pollo' => 'ensalada-citrica-pollo.webp', 'ensalada-mediterranea-quinoa' => 'ensalada-mediterranea-quinoa.webp',
	'pollo-arroz-integral-brocoli' => 'pollo-arroz-brocoli.webp', 'bowl-ligero-de-pavo-y-quinoa' => 'bowl-pavo-quinoa.webp',
	'lentejas-verdes-con-espinaca' => 'lentejas-espinaca.webp', 'pavo-salteado-zapallo-italiano' => 'pavo-zapallo-italiano.webp',
	'ensalada-tibia-papa-brocoli' => 'ensalada-papa-brocoli.webp', 'bowl-garbanzos-lentejas' => 'bowl-garbanzos-lentejas.webp',
	'tofu-dorado-vegetales' => 'tofu-dorado-vegetales.webp',
);
if ( class_exists( 'Chef_Dish_Service' ) ) {
	$all_dishes = ( new Chef_Dish_Service() )->all();
	foreach ( $featured_slugs as $featured_slug ) {
		foreach ( $all_dishes as $dish ) {
			if ( $featured_slug === $dish['slug'] ) { $featured_dishes[] = $dish; break; }
		}
	}
}
get_header();
?>
<main id="main-content">
	<section class="hero home-banner" id="experiencia" style="--home-hero:url('<?php echo esc_url( $theme_uri . '/assets/images/hero-cocina-saludable.webp' ); ?>')">
		<div class="container home-banner-content">
			<div class="hero-copy-inner">
				<p class="eyebrow"><span></span><?php echo esc_html( get_theme_mod( 'chef_hero_eyebrow', __( 'Chef personal · en la comodidad de tu hogar', 'chef-en-casa' ) ) ); ?></p>
				<h1><?php echo esc_html( get_theme_mod( 'chef_hero_title', __( 'Tu tiempo importa.', 'chef-en-casa' ) ) ); ?><strong><?php echo esc_html( get_theme_mod( 'chef_hero_accent', __( 'Nosotros cocinamos.', 'chef-en-casa' ) ) ); ?></strong></h1>
				<p class="hero-summary"><?php echo esc_html( get_theme_mod( 'chef_hero_text', __( 'Menús preparados en tu cocina, pensados para tu forma de alimentarte y el ritmo real de tu semana.', 'chef-en-casa' ) ) ); ?></p>
				<div class="hero-actions"><a class="button" href="#como-funciona"><?php echo esc_html( get_theme_mod( 'chef_cta_label', __( 'Descubre cómo funciona', 'chef-en-casa' ) ) ); ?><span aria-hidden="true">↓</span></a><a class="text-link" href="#beneficios"><?php esc_html_e( 'Conoce la propuesta', 'chef-en-casa' ); ?><span aria-hidden="true">→</span></a></div>
				<div class="trust-row"><div><b><?php esc_html_e( 'A tu medida', 'chef-en-casa' ); ?></b><span><?php esc_html_e( 'Menús flexibles', 'chef-en-casa' ); ?></span></div><div><b><?php esc_html_e( 'En tu hogar', 'chef-en-casa' ); ?></b><span><?php esc_html_e( 'Sin desplazamientos', 'chef-en-casa' ); ?></span></div><div><b><?php esc_html_e( 'Más tiempo', 'chef-en-casa' ); ?></b><span><?php esc_html_e( 'Para lo importante', 'chef-en-casa' ); ?></span></div></div>
			</div>
		</div>
	</section>

	<section class="section process" id="como-funciona"><div class="container">
		<div class="section-heading"><div><p class="kicker"><?php esc_html_e( 'Así de simple', 'chef-en-casa' ); ?></p><h2><?php esc_html_e( 'Tu cocina, en buenas manos.', 'chef-en-casa' ); ?></h2></div><p><?php esc_html_e( 'Nos ocupamos de cada detalle para que vuelvas a disfrutar tu tiempo y tu mesa.', 'chef-en-casa' ); ?></p></div>
		<div class="steps">
			<?php
			$step_icons = array(
				'conversation' => '<svg viewBox="0 0 24 24" role="img"><path d="M7 17.5 3.5 20v-4.6A7.5 7.5 0 0 1 2 11c0-4.4 4.1-8 9.2-8s9.3 3.6 9.3 8-4.2 8-9.3 8c-1.5 0-3-.3-4.2-1.5Z"/><path d="M7.5 10.8h.1m3.5 0h.1m3.5 0h.1"/></svg>',
				'menu'         => '<svg viewBox="0 0 24 24" role="img"><path d="M6 3.5h12a2 2 0 0 1 2 2v15H4v-15a2 2 0 0 1 2-2Z"/><path d="M8 8h8M8 12h5M8 16h7"/></svg>',
				'home'         => '<svg viewBox="0 0 24 24" role="img"><path d="m3 11 9-7 9 7"/><path d="M5.5 9.5v10h13v-10M9 19.5v-6h6v6"/><path d="M17.5 5.7V3.5"/></svg>',
			);
			$steps = array(
				array( '01', 'conversation', __( 'Cuéntanos sobre ti', 'chef-en-casa' ), __( 'Conversamos sobre tus gustos, hábitos y el tipo de alimentación que buscas.', 'chef-en-casa' ) ),
				array( '02', 'menu', __( 'Diseñamos tu menú', 'chef-en-casa' ), __( 'Creamos una propuesta variada y pensada para el ritmo real de tu semana.', 'chef-en-casa' ) ),
				array( '03', 'home', __( 'Cocinamos en tu casa', 'chef-en-casa' ), __( 'Tu chef prepara, ordena y deja todo listo para que solo tengas que disfrutar.', 'chef-en-casa' ) ),
			);
			foreach ( $steps as $step ) : ?>
			<article class="step"><span class="step-number"><?php echo esc_html( $step[0] ); ?></span><span class="step-icon" aria-hidden="true"><?php echo wp_kses( $step_icons[ $step[1] ], array( 'svg' => array( 'viewbox' => true, 'role' => true ), 'path' => array( 'd' => true ) ) ); ?></span><h3><?php echo esc_html( $step[2] ); ?></h3><p><?php echo esc_html( $step[3] ); ?></p></article>
			<?php endforeach; ?>
		</div>
	</div></section>

	<?php if ( $featured_dishes ) : ?>
	<section class="section featured-dishes" id="platos"><div class="container">
		<div class="dishes-heading"><div><p class="kicker"><?php esc_html_e( 'Una muestra del catálogo', 'chef-en-casa' ); ?></p><h2><?php esc_html_e( 'Platos que hablan por sí solos.', 'chef-en-casa' ); ?></h2></div><p><?php esc_html_e( 'Preparaciones reales con información clara, calculada desde cada ingrediente y su cantidad.', 'chef-en-casa' ); ?></p></div>
		<div class="dish-grid">
		<?php
		foreach ( $featured_dishes as $dish ) : $nutrition = $dish['nutrition']['per_serving']; ?>
			<article class="dish-card">
				<div class="dish-photo"><img src="<?php echo esc_url( $theme_uri . '/assets/images/dishes/' . $image_map[ $dish['slug'] ] ); ?>" alt="<?php echo esc_attr( $dish['name'] ); ?>" loading="lazy"><span><?php echo esc_html( $dish['preparation_type'] ); ?></span></div>
				<div class="dish-content"><div class="dish-types"><?php foreach ( $dish['food_types'] as $food_type ) : ?><span><?php echo esc_html( $food_type['name'] ); ?></span><?php endforeach; ?></div><h3><?php echo esc_html( $dish['name'] ); ?></h3><p><?php echo esc_html( $dish['description'] ); ?></p>
					<div class="nutrition-row"><div><b><?php echo esc_html( number_format_i18n( $nutrition['calories'], 0 ) ); ?></b><span>kcal</span></div><div><b><?php echo esc_html( number_format_i18n( $nutrition['proteins'], 1 ) ); ?>g</b><span><?php esc_html_e( 'proteína', 'chef-en-casa' ); ?></span></div><div><b><?php echo esc_html( number_format_i18n( $nutrition['carbohydrates'], 1 ) ); ?>g</b><span><?php esc_html_e( 'carbos', 'chef-en-casa' ); ?></span></div></div><p class="nutrition-note"><?php esc_html_e( 'Valores aproximados por porción', 'chef-en-casa' ); ?></p>
				</div>
			</article>
		<?php endforeach; ?>
		</div>
	</div></section>
	<?php endif; ?>

	<?php if ( $featured_menus ) : ?>
	<section class="section menu-showcase" id="menus"><div class="container">
		<div class="dishes-heading"><div><p class="kicker"><?php esc_html_e( 'Ideas para tu semana', 'chef-en-casa' ); ?></p><h2><?php esc_html_e( 'Un menú para cada forma de disfrutar.', 'chef-en-casa' ); ?></h2></div><p><?php esc_html_e( 'Combina platos preparados en casa y organizados según el estilo de alimentación que prefieras.', 'chef-en-casa' ); ?></p></div>
		<div class="menu-grid">
		<?php foreach ( $featured_menus as $menu ) : $menu_cover = $menu['dishes'][0]['dish'] ?? array(); ?>
			<article class="menu-card menu-card-<?php echo esc_attr( $menu['food_type']['slug'] ); ?>">
				<?php if ( $menu_cover ) : ?><div class="menu-card-image"><img src="<?php echo esc_url( $theme_uri . '/assets/images/dishes/' . ( $image_map[ $menu_cover['slug'] ] ?? 'ensalada-mediterranea-quinoa.webp' ) ); ?>" alt="<?php echo esc_attr( $menu['name'] ); ?>" loading="lazy" decoding="async"></div><?php endif; ?>
				<div class="menu-card-top"><span><?php echo esc_html( $menu['food_type']['name'] ); ?></span><b><?php echo esc_html( count( $menu['dishes'] ) ); ?> <?php esc_html_e( 'platos', 'chef-en-casa' ); ?></b></div>
				<h3><?php echo esc_html( $menu['name'] ); ?></h3><p><?php echo esc_html( $menu['description'] ); ?></p>
				<ul><?php foreach ( $menu['dishes'] as $menu_dish ) : ?><li><div><small><?php echo esc_html( $menu_dish['course_type'] ); ?></small><strong><?php echo esc_html( $menu_dish['dish']['name'] ); ?></strong></div><span><?php echo esc_html( number_format_i18n( $menu_dish['dish']['nutrition']['per_serving']['calories'], 0 ) ); ?> kcal</span></li><?php endforeach; ?></ul>
			</article>
		<?php endforeach; ?>
		</div>
		<div class="section-action"><a class="button" href="<?php echo esc_url( home_url( '/menus/' ) ); ?>"><?php esc_html_e( 'Explorar todos los menús', 'chef-en-casa' ); ?> <span aria-hidden="true">→</span></a></div>
	</div></section>
	<?php endif; ?>

	<section class="section benefits benefits-photo" id="beneficios" style="--benefits-photo:url('<?php echo esc_url( $theme_uri . '/assets/images/hero-guia-alimentacion.webp' ); ?>')"><div class="container benefits-grid">
		<div class="benefits-copy"><p class="kicker kicker-light"><?php esc_html_e( 'Cocina personal, de verdad', 'chef-en-casa' ); ?></p><h2><?php esc_html_e( 'La buena cocina empieza escuchando.', 'chef-en-casa' ); ?></h2><p><?php esc_html_e( 'Cada propuesta nace de entender qué disfrutas, qué necesitas y cómo vives. Sin fórmulas rígidas ni soluciones genéricas.', 'chef-en-casa' ); ?></p><span class="signature"><?php esc_html_e( 'Hecho en casa. Pensado para ti.', 'chef-en-casa' ); ?></span></div>
		<div class="benefit-list">
			<?php foreach ( array(
				array( '✦', __( 'Ingredientes que reconoces', 'chef-en-casa' ), __( 'Productos frescos y preparaciones honestas.', 'chef-en-casa' ) ),
				array( '●', __( 'Un servicio que se adapta', 'chef-en-casa' ), __( 'Tu rutina guía cada decisión del menú.', 'chef-en-casa' ) ),
				array( '♥', __( 'Equilibrio sin rigidez', 'chef-en-casa' ), __( 'Información clara para elegir con confianza.', 'chef-en-casa' ) ),
				array( '✓', __( 'Tu cocina, impecable', 'chef-en-casa' ), __( 'Preparamos y dejamos todo en orden.', 'chef-en-casa' ) ),
			) as $benefit ) : ?>
			<div class="benefit-item"><span aria-hidden="true"><?php echo esc_html( $benefit[0] ); ?></span><div><h3><?php echo esc_html( $benefit[1] ); ?></h3><p><?php echo esc_html( $benefit[2] ); ?></p></div></div>
			<?php endforeach; ?>
		</div>
	</div></section>

	<section class="section cta" id="contacto"><div class="container"><div class="cta-panel"><div><p class="kicker"><?php esc_html_e( 'Muy pronto', 'chef-en-casa' ); ?></p><h2><?php esc_html_e( 'Tu próxima comida puede sentirse diferente.', 'chef-en-casa' ); ?></h2><p><?php esc_html_e( 'Estamos preparando los primeros menús y experiencias de Chef en Casa.', 'chef-en-casa' ); ?></p></div><a class="button button-light" href="mailto:hola@example.com"><?php esc_html_e( 'Quiero conocer más', 'chef-en-casa' ); ?><span aria-hidden="true">↗</span></a></div></div></section>
</main>
<?php get_footer(); ?>
