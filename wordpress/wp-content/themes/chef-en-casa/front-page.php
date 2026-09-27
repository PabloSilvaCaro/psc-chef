<?php
/**
 * Front page template.
 *
 * @package ChefEnCasa
 */

get_header();

$theme_uri = get_template_directory_uri();
?>
<main id="main-content">
	<section class="hero" id="experiencia">
		<div class="hero-copy">
			<div class="hero-copy-inner">
				<p class="eyebrow"><span></span><?php echo esc_html( get_theme_mod( 'chef_hero_eyebrow', __( 'Chef personal · en la comodidad de tu hogar', 'chef-en-casa' ) ) ); ?></p>
				<h1><?php echo esc_html( get_theme_mod( 'chef_hero_title', __( 'Tu tiempo importa.', 'chef-en-casa' ) ) ); ?><strong><?php echo esc_html( get_theme_mod( 'chef_hero_accent', __( 'Nosotros cocinamos.', 'chef-en-casa' ) ) ); ?></strong></h1>
				<p class="hero-summary"><?php echo esc_html( get_theme_mod( 'chef_hero_text', __( 'Menús preparados en tu cocina, pensados para tu forma de alimentarte y el ritmo real de tu semana.', 'chef-en-casa' ) ) ); ?></p>
				<div class="hero-actions"><a class="button" href="#como-funciona"><?php echo esc_html( get_theme_mod( 'chef_cta_label', __( 'Descubre cómo funciona', 'chef-en-casa' ) ) ); ?><span aria-hidden="true">↓</span></a><a class="text-link" href="#beneficios"><?php esc_html_e( 'Conoce la propuesta', 'chef-en-casa' ); ?><span aria-hidden="true">→</span></a></div>
				<div class="trust-row"><div><b><?php esc_html_e( 'A tu medida', 'chef-en-casa' ); ?></b><span><?php esc_html_e( 'Menús flexibles', 'chef-en-casa' ); ?></span></div><div><b><?php esc_html_e( 'En tu hogar', 'chef-en-casa' ); ?></b><span><?php esc_html_e( 'Sin desplazamientos', 'chef-en-casa' ); ?></span></div><div><b><?php esc_html_e( 'Más tiempo', 'chef-en-casa' ); ?></b><span><?php esc_html_e( 'Para lo importante', 'chef-en-casa' ); ?></span></div></div>
			</div>
		</div>
		<div class="hero-image"><img src="<?php echo esc_url( $theme_uri . '/assets/images/hero-cocina-saludable.png' ); ?>" alt="<?php esc_attr_e( 'Plato saludable con quinoa y vegetales frescos', 'chef-en-casa' ); ?>"><div class="hero-badge"><span class="badge-icon">✦</span><div><b><?php esc_html_e( 'Cocinado para ti', 'chef-en-casa' ); ?></b><small><?php esc_html_e( 'Fresco · cercano · personal', 'chef-en-casa' ); ?></small></div></div></div>
	</section>

	<section class="section process" id="como-funciona"><div class="container">
		<div class="section-heading"><div><p class="kicker"><?php esc_html_e( 'Así de simple', 'chef-en-casa' ); ?></p><h2><?php esc_html_e( 'Tu cocina, en buenas manos.', 'chef-en-casa' ); ?></h2></div><p><?php esc_html_e( 'Nos ocupamos de cada detalle para que vuelvas a disfrutar tu tiempo y tu mesa.', 'chef-en-casa' ); ?></p></div>
		<div class="steps">
			<?php
			$steps = array(
				array( '01', '◌', __( 'Cuéntanos sobre ti', 'chef-en-casa' ), __( 'Conversamos sobre tus gustos, hábitos y el tipo de alimentación que buscas.', 'chef-en-casa' ) ),
				array( '02', '◇', __( 'Diseñamos tu menú', 'chef-en-casa' ), __( 'Creamos una propuesta variada y pensada para el ritmo real de tu semana.', 'chef-en-casa' ) ),
				array( '03', '⌂', __( 'Cocinamos en tu casa', 'chef-en-casa' ), __( 'Tu chef prepara, ordena y deja todo listo para que solo tengas que disfrutar.', 'chef-en-casa' ) ),
			);
			foreach ( $steps as $step ) : ?>
			<article class="step"><span class="step-number"><?php echo esc_html( $step[0] ); ?></span><span class="step-icon" aria-hidden="true"><?php echo esc_html( $step[1] ); ?></span><h3><?php echo esc_html( $step[2] ); ?></h3><p><?php echo esc_html( $step[3] ); ?></p></article>
			<?php endforeach; ?>
		</div>
	</div></section>

	<section class="section benefits" id="beneficios"><div class="container benefits-grid">
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

