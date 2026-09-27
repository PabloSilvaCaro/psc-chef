<?php
/**
 * Default template.
 *
 * @package ChefEnCasa
 */

get_header();
?>
<main id="main-content" class="section"><div class="container content-area">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<article <?php post_class(); ?>><h1><?php the_title(); ?></h1><?php the_content(); ?></article>
	<?php endwhile; else : ?>
		<p><?php esc_html_e( 'No hay contenido disponible.', 'chef-en-casa' ); ?></p>
	<?php endif; ?>
</div></main>
<?php get_footer(); ?>

