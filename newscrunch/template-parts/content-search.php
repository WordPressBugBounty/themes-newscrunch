<?php
/**
 * Template part for displaying search content
 *
 * @package Newscrunch
 */
?>
<article itemscope itemtype="https://schema.org/Article" id="post-<?php the_ID(); ?>" <?php post_class('spnc-grid-catpost spnc-post'); ?> >
	<div class="spnc-post-wrap">
		<div class="spnc-post-overlay"></div>
	    <?php if(has_post_thumbnail()): ?>
			<!-- Post Featured Image -->
			<figure class="spnc-post-thumbnail <?php echo esc_attr(get_theme_mod('img_animation','i_effect1'));?>">
				<a itemprop="url" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php the_post_thumbnail('full', array('class'=>'img-fluid', 'itemprop'=>'image' )); ?>
				</a>
			</figure>
		<?php endif; ?>
		
		<div class="spnc-post-content <?php if(!has_post_thumbnail()): ?>featured-img<?php endif; ?>">
			<div class="spnc-content-wrapper">
                <div class="spnc-post-wrapper">
                	<header class="spnc-entry-header">
                        <div class="spnc-entry-meta">
                            <!-- Post Author -->
				 			<?php if ( get_theme_mod('newscrunch_enable_post_author',true) == true ) :?>
								<span itemprop="author" class="spnc-author">
								<i class="fas fa-solid fa-user"></i>
					                <a <?php if (is_rtl()) { echo 'dir="rtl"';} ?> itemprop="url" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" title="<?php echo esc_attr__('Posts by','newscrunch') . ' ' . esc_attr(get_the_author()); ?>">
					                <?php echo esc_html(get_the_author()); ?></a>
					            </span>				            
							<?php endif; ?>

							<!-- Post Tag -->
							<?php
				        	if(get_theme_mod('newscrunch_enable_post_tag',true)==true):
								if(has_tag()):
									echo '<span class="tag-links"><i class="fa fa-tags"></i>';
								 	the_tags('',', ');
								 	echo'</span>';	
							 	endif;
							endif; ?> 	
							<!-- Post Comments -->
	                        <?php if(get_theme_mod('newscrunch_enable_post_comment',true)==true):?>
								<span class="comment-links"><i class="far fa-comment-alt"></i>
									<a <?php if (is_rtl()) { echo 'dir="rtl"';} ?> itemprop="url" href="<?php the_permalink(); ?>#respond" title="<?php esc_attr_e('Number of Comments','newscrunch'); ?>">
										<?php echo esc_html(get_comments_number()) . ' ' . esc_html__('Comments','newscrunch'); ?>
							     	</a>
						     	</span>
					     	<?php endif;
					     	// Read Time
							if ( class_exists('Newscrunch_Plus') ):
	                            if(get_theme_mod('reading_time_enable',false) === true): ?>
	                                <span class="spnc-read-time"><i class="fa fa-eye"></i> <?php spncp_reading_time();?></span>
	                       	<?php endif; endif; ?>
                        </div>
                        <?php if(get_theme_mod('newscrunch_enable_post_title',true)==true): ?> 
	                        <h3 itemprop="name" class="spnc-entry-title">
	                            <a class="<?php echo esc_attr(get_theme_mod('link_animate','a_effect1'));?>" itemprop="url" href="<?php the_permalink();?>" title="<?php the_title(); ?>"><?php the_title();?></a>
	                        </h3>
	                    <?php endif; 
	                    if(get_theme_mod('newscrunch_enable_post_description',true)==true):?>
		                    <p class="spnc-description">
								<?php newscrunch_excerpt(15); ?>
							</p>
					<?php endif; ?>
                    </header>
                    <div class="spnc-entry-content">
                        <div class="spnc-footer-meta">
	                    	<?php if(get_theme_mod('newscrunch_enable_post_read_more',true)==true):
		                        $newscrunch_read_more = get_theme_mod('newscrunch_blog_archive_read_btn', __('Read More','newscrunch'));?>
		                        <a itemprop="url" href="<?php echo esc_url(get_the_permalink());?>" class="spnc-more-link" title="<?php echo esc_attr($newscrunch_read_more);?>"><?php echo esc_html($newscrunch_read_more);?></a>
	                    	<?php endif;?>
                            <div class="spnc-entry-meta">
					    		<?php if ( get_theme_mod('newscrunch_enable_post_date',true) == true ) :?>
						            <span class="spnc-date">	
						            	<i class="fas fa-solid fa-clock"></i>
										<?php echo newcrunch_post_date_time(get_the_ID()); ?>
									</span>
								<?php endif; ?>               
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>	
</article>