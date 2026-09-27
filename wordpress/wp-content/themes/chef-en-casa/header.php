<?php
/**
 * Site header.
 *
 * @package ChefEnCasa
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?php echo esc_url( get_template_directory_uri() . '/assets/brand/favicon.svg' ); ?>" type="image/svg+xml">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content"><?php esc_html_e( 'Saltar al contenido', 'chef-en-casa' ); ?></a>
<header class="site-header" data-site-header>
	<div class="container header-inner">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'Chef en Casa, inicio', 'chef-en-casa' ); ?>">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/brand/logo-horizontal.svg' ); ?>" alt="<?php esc_attr_e( 'Chef en Casa', 'chef-en-casa' ); ?>">
		</a>
		<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation"><span></span><span></span><span></span><span class="screen-reader-text"><?php esc_html_e( 'Abrir navegación', 'chef-en-casa' ); ?></span></button>
		<nav class="primary-nav" id="primary-navigation" aria-label="<?php esc_attr_e( 'Navegación principal', 'chef-en-casa' ); ?>">
			<?php if ( has_nav_menu( 'primary' ) ) : ?>
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'nav-list', 'fallback_cb' => false ) ); ?>
			<?php else : ?>
				<ul class="nav-list"><li><a href="#experiencia"><?php esc_html_e( 'La experiencia', 'chef-en-casa' ); ?></a></li><li><a href="#como-funciona"><?php esc_html_e( 'Cómo funciona', 'chef-en-casa' ); ?></a></li><li><a href="#platos"><?php esc_html_e( 'Platos', 'chef-en-casa' ); ?></a></li><li><a href="#beneficios"><?php esc_html_e( 'Beneficios', 'chef-en-casa' ); ?></a></li></ul>
			<?php endif; ?>
			<a class="button button-small" href="#contacto"><?php esc_html_e( 'Quiero saber más', 'chef-en-casa' ); ?></a>
		</nav>
	</div>
</header>
