<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Wisten
 * @since Wisten 1.0
 */

get_header(); ?>

	<div id="primary" class="site-content contain">
		<div id="content" class="inner" role="main">

			<?php 
			while ( have_posts() ) : 
				the_post(); 
				get_template_part( 'content', 'page' ); 
				wp_link_pages( );
				comments_template( '', true ); 
			endwhile; // end of the loop. 
			?>
		</div><!-- #content -->
<?php
$post_meta = get_post_meta( $post->ID, '_attach_section', true );
if(	isset($post_meta['fastwp_attach_separator']) && 
	!empty($post_meta['fastwp_attach_separator']) && 
	strlen($post_meta['fastwp_section_ct']) > 1
){
	echo '<div class="bottom-content">'.apply_filters('the_content', $post_meta['fastwp_section_ct']).'</div>';
}
?>
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>