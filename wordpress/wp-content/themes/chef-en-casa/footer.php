<?php
/**
 * Site footer.
 *
 * @package ChefEnCasa
 */
?>
<footer class="site-footer">
	<div class="container footer-grid">
		<div><img class="footer-logo" src="<?php echo esc_url( get_template_directory_uri() . '/assets/brand/logo-horizontal-reversed.svg' ); ?>" alt="<?php esc_attr_e( 'Chef en Casa', 'chef-en-casa' ); ?>"><p><?php esc_html_e( 'Cocina personal, preparada en tu hogar.', 'chef-en-casa' ); ?></p></div>
		<div class="footer-meta"><p><?php echo esc_html( gmdate( 'Y' ) ); ?> · <?php esc_html_e( 'Chef en Casa', 'chef-en-casa' ); ?></p><p><?php esc_html_e( 'Demo MVP · Información nutricional referencial', 'chef-en-casa' ); ?></p></div>
	</div>
</footer>
<?php wp_footer(); ?>
</body></html>

