<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Wisten
 * @since Wisten 1.0
 */
$is_ajax = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')? true : false;
if($is_ajax == true){ 
	echo '<body>';
	// sleep(1);
} else {
	get_header();
} 
$value = get_post_meta( $post->ID, '_fastwp_meta', true );
$tags 	= wp_get_object_terms($post->ID, 'portfolio-category');
$Tags = array();
foreach($tags as $tag){
	$Tags[] = $tag->name;
}
global $fastwp_social_networks, $fastwp_share_networks;
$social_template = '<a href="#" onClick="share_on.%s()"><i class="fa fa-%s"></i></a>';						
			$social = '';
			
			foreach($fastwp_share_networks as $name=>$icon){
				$social .= sprintf($social_template, $name, $icon);
			}
?>

	<div id="primary" class="site-content">
		<div id="content" role="main">
			
			<?php while ( have_posts() ) : the_post();  ?>
			<?php
				if($is_ajax == false){
			?>
			<div class="project-title-nav bg parallax " style="<?php echo (isset($value['top_parallax']) && !empty($value['top_parallax']))?'background-image:url('.$value['top_parallax'].')':'';?>">
				<div class="inner clearfix">
					<div class="title">
						<h2><?php the_title(); ?></h2>
						<h3>CONTRARY TO POPULAR BELIEF</h3>
					</div>
					<div class="post-nav">
						
				<nav class="nav-single">
				
					<span class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav"></span>' ); ?></span>
					<span class="grid-separator"></span>
					<span class="nav-next"><?php next_post_link( '%link', '<span class="meta-nav"></span>' ); ?></span>
				</nav><!-- .nav-single -->
			
					</div>
				</div>
			</div>
				<?php } else { } ?>
			<div class="inner project-item clearfix <?php echo (isset($value['layout_side']) && $value['layout_side'] == 1)?' side-by-side ':''?>">
				<?php 
				if(isset($value['project_type'])){
				switch($value['project_type']){
					case 'image':
						?>
						<div class="fastwp-image <?php echo (isset($value['layout_side']) && $value['layout_side'] == 1)?' fastwp-pair ':''?>" data-pairwith=".inside-content">
						<img src="<?php echo esc_attr( @$value['image'] ); ?>" alt="" class="img-responsive" id="single-project-image" onLoad="queue_container_equalization()">
						</div>
						<?php
					break;
					case 'video':
						if(!empty($value['video'])){ 
							$video = $value['video'];
							if(substr_count($video, 'vimeo.com') == 1){
								$videoURL = str_replace('http://vimeo.com/','http://player.vimeo.com/video/', $video);

							}else if(substr_count($video, 'youtu.be') == 1 ||substr_count($video, 'youtube.com') == 1){
								$videoURL = str_replace('/watch?v=','/embed/', $video);
							}
							?>
							<div class="fastwp-video <?php echo (isset($value['layout_side']) && $value['layout_side'] == 1)?' fastwp-pair ':''?>" data-pairwith=".inside-content">
						
							<div class="post-video video-wrapper <?php echo (isset($value['video_ar']) && $value['video_ar'] == '1')?'wide':''; ?>">
								<div>
								<iframe style="width:100%; height:100%; border:none; overflow:hidden;" src="<?php echo $videoURL; ?>?wmode=opaque" allowfullscreen></iframe>
								</div>
							</div>
							</div>
							<?php 
						}
					break;
					case 'audio':
						if(!empty($value['audio'])){
						?>
						<div class="fastwp-audio post-audio">
							<iframe style="width:100%; height:166px; border:none; overflow:hidden;" src="//w.soundcloud.com/player/?url=<?php echo $value['audio']; ?>&amp;color=ff6600&amp;auto_play=false&amp;show_artwork=false"></iframe>
						</div>
						<?php 
						}
					break;
					default:
						if(isset($value['gallery']) && is_array($value['gallery'])){ ?>
						<div class="gallery post-slide">
							<ul class="post-slides clearfix">
							<?php
							foreach($value['gallery'] as $img){
								echo '<li class="item"><img src="'.$img.'" class="img-responsive" alt=""></li>';
							}
							?>
							</ul>
						</div>
						<?php 
						}
					break;
				}
				} ?>
				<div class="inside-content">
					<div class="content bsbb">
						<h3 class="titles">Project description</h3>
						<?php the_content(); ?>
					</div>
					<div class="description <?php echo (!isset($value['layout_side']) || $value['layout_side'] == 0)?' fastwp-pair ':''?>" data-pairwith=".inside-content .content">
						<h3 class="titles">Project Details</h3>
						<ul>
						<?php if(isset($value['client']) && !empty($value['client'])):?>
							<li><strong>Client :</strong> <?php echo @$value['client']; ?></li>
						<?php endif; ?>
							<li><strong>Date :</strong> <?php the_date(); ?></li>
						<?php if(is_array($Tags)):?>
							<li><strong>Tags :</strong> <?php echo implode(', ',$Tags);?></li>
						<?php 
							endif; 
							if(isset($social) && !empty($social)):
						?>
							<li class="share"><strong>Share :</strong> <?php echo $social; ?></li>
						<?php endif; ?>
						</ul>
					</div>
				</div>
				
			</div>
			<?php
			if(isset($value['featured']) && $value['featured'] == '1' && is_array($value['project_featured']) && count($value['project_featured']) > 0 && $is_ajax != true){
				$header = sprintf('<div class="header">%s</div>', @$value['featured_title']);
				$header .= sprintf('<div class="page-desc">%s</div>', @$value['featured_descr']);

				$projects = get_posts('post_type=fwp_portfolio&numberposts=-1&posts_per_page=-1&include='.implode(',', $value['project_featured']));
				
				$portfolio_item	= '
					<div class="featured-work work col-xs-12 %s">
						<div class="work-inner">
							<div class="work-img">
								<img src="%s" alt=""/>
								<div class="mask">
									<a class="button zoom" href="%s" data-rel="zoom-image"><i class="fa fa-search"></i></a>
									<a class="button detail" href="%s" data-rel="%s"><i class="fa fa-film"></i></a>
								</div>
							</div>
							<div class="work-desc">
								<h4>%s</h4>
								<p>%s</p>
							</div>
						</div>
					</div>';
				$featured_items_wrap = '<div class="featured-projects fastwp-parallax-bg" style="%s">%s<div class="some-slider clearfix inner slide-boxes">%s</div></div>';
				$html_output = array();
				$style = '';
				if(!empty( $value['featured_parallax'])){ 
					$style = 'background-image:url('.$value['featured_parallax'].');';
				}
				foreach($projects as $item){
					$filters = '';
					$thumb 		= wp_get_attachment_image_src( get_post_thumbnail_id($item->ID), 'portfolio-thumb' );
					$thumb 		= $thumb['0'];
					$zoom_href 	= wp_get_attachment_image_src( get_post_thumbnail_id($item->ID), 'full');
					$zoom_href 	= $zoom_href['0'];
					$url 		= get_permalink($item->ID);
					$name 		= (isset($item->post_title))? apply_filters('the_title', $item->post_title):'';
					$url_rel 	= '';
					$description= '';
					$html_output[] = sprintf($portfolio_item, $filters, $thumb, $zoom_href, $url, $url_rel, $name, $description);
				}
				echo sprintf($featured_items_wrap, $style, $header, implode('', $html_output));
			}
			endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->
<?php if($is_ajax == true){ 
	wp_footer();
	echo '</body>';
} else {
	get_sidebar();
	get_footer();
}