<?php
/**
 * Lightning G3 index.php common template-file
 *
 * @package vektor-inc/lightning
 */

use VektorInc\VK_Breadcrumb\VkBreadcrumb;

?><?php lightning_get_template_part( 'header' ); ?>

<?php
do_action( 'lightning_site_header_before', 'lightning_site_header_before' );
if ( apply_filters( 'lightning_is_site_header', true, 'site_header' ) ) {
	lightning_get_template_part( 'template-parts/site-header' );
}
do_action( 'lightning_site_header_after', 'lightning_site_header_after' );
?>

<?php do_action( 'lightning_site_body_before', 'lightning_site_body_before' ); ?>

<div class="<?php lightning_the_class_name( 'site-body' ); ?>">
	<?php do_action( 'lightning_site_body_prepend', 'lightning_site_body_prepend' ); ?>
	<div class="<?php lightning_the_class_name( 'site-body-container' ); ?> container">

		<div class="<?php lightning_the_class_name( 'main-section' ); ?>" id="main" role="main">
			<?php do_action( 'lightning_main_section_prepend', 'lightning_main_section_prepend' ); ?>

			<?php
			if ( apply_filters( 'lightning_is_main_section_template', true, 'main_section_template' ) ) {
				if ( lightning_is_woo_page() ) {
					lightning_get_template_part( 'template-parts/main-woocommerce' );
				} else {
					if ( apply_filters( 'lightning_is_singular', is_singular() ) ) {
						lightning_get_template_part( 'template-parts/main-singular' );
					} else {
						if ( is_404() ) {
							lightning_get_template_part( 'template-parts/main-404' );
						} else {
							lightning_get_template_part( 'template-parts/main-archive' );
						}
					}
				}
			}
			?>

			<?php do_action( 'lightning_main_section_append', 'lightning_main_section_append' ); ?>
		</div><!-- [ /.main-section ] -->



	</div><!-- [ /.site-body-container ] -->


</div><!-- [ /.site-body ] -->

<?php
do_action( 'lightning_site_footer_before', 'lightning_site_footer_before' );
if ( apply_filters( 'lightning_is_site_footer', true, 'site_footer' ) ) {
	lightning_get_template_part( 'template-parts/site-footer' );
}
do_action( 'lightning_site_footer_after', 'lightning_site_footer_after' );
?>

<?php lightning_get_template_part( 'footer' ); ?>
