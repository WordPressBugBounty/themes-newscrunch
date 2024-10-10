<?php
/**
 * Template part for displaying posts
 *
 * @package Newscrunch Theme
 */
?>
<div data-wow-delay=".8s" class="wow-callback zoomIn spnc-first-catpost">
	<article  itemscope itemtype="https://schema.org/Article" id="post-<?php the_ID(); ?>" <?php post_class('spnc-post '); ?> >
	    <div class="spnc-post-overlay"></div>
	    <div class="spnc-post-img <?php echo esc_attr(get_theme_mod('img_animation','i_effect1'));?>" style="background-image:url(<?php the_post_thumbnail_url(); ?>);" width="1920" height="865">
	    	<div class="alt-text"><?php the_title();?></div>
	        <div class="spnc-post-content">
	            <div class="spnc-content-wrapper">
	                <div class="spnc-post-wrapper">
	                    <header class="spnc-entry-header">
	                    	<?php if(get_theme_mod('newscrunch_enable_post_category',true)==true): ?>
		                        <div class="spnc-entry-meta">
		                            <?php if ( has_category() ) :
									echo '<span itemprop="about" class="spnc-cat-links">';
										newscrunch_get_categories(get_the_ID());
									echo '</span>';
									endif; ?>
							 	</div>
							<?php endif; 
							if(get_theme_mod('newscrunch_enable_post_title',true)==true): ?> 
		                        <h3 itemprop="name" class="spnc-entry-title">
			                        <a class="<?php echo esc_attr(get_theme_mod('link_animate','a_effect1'));?>" itemprop="url" href="<?php the_permalink();?>" title="<?php the_title(); ?>"><?php the_title();?></a>
			                    </h3>
			                <?php endif; ?>
	                    </header>
	                    <div class="spnc-entry-content">
	                        <div class="spnc-footer-meta">
	                            <div class="spnc-entry-meta">
		                            <?php if ( get_theme_mod('newscrunch_enable_post_author',true) == true ) :?>
									<span itemprop="author" class="spnc-author">
										<i class="fas fa-solid fa-user"></i>
											<a <?php if (is_rtl()) { echo 'dir="rtl"';} ?> itemprop="url" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" title="<?php echo esc_attr__('Posts by','newscrunch') . ' ' . esc_attr(get_the_author()); ?>">
								                <?php echo esc_html(get_the_author()); ?></a>
						            </span>				            
									<?php endif; ?>

		                            <?php
									if ( get_theme_mod('newscrunch_enable_post_date',true) == true ) :?>
						            <span class="spnc-date">	
						            	<i class="fas fa-solid fa-clock"></i>
											<?php echo newcrunch_post_date_time(get_the_ID()); ?>
									</span>
									<?php endif; ?> 
										    
		                            <!-- Post Tag -->
									<?php
						        	if(get_theme_mod('newscrunch_enable_post_tag',true)==true):
										if(has_tag()):
											echo '<span class="spnc-tag-links"><i class="fa fa-tags"></i>';
										 	the_tags('',', ');
											 	echo'</span>';	
									 	endif;
									endif; 

									// Read Time
									if ( class_exists('Newscrunch_Plus') ):
                                        if(get_theme_mod('reading_time_enable',false) === true): ?>
                                            <span class="spnc-read-time"><i class="fa fa-eye"></i> <?php spncp_reading_time();?></span>
                                   	<?php endif; endif; ?> 
	                            </div>
	                            <?php if(get_theme_mod('newscrunch_enable_post_description',true)==true): ?>
	                            	<p itemprop="description" class="spnc-description <?php if(get_theme_mod('newscrunch_enable_post_read_more',true)== false):?>no-read<?php endif;?>"><?php newscrunch_excerpt(15); ?></p>
	                            <?php
	                            endif; 
	                            if(get_theme_mod('newscrunch_enable_post_read_more',true)==true):
	                            $newscrunch_read_more = get_theme_mod('newscrunch_blog_archive_read_btn', __('Read More','newscrunch'));?>
	                            <a itemprop="url" href="<?php echo esc_url(get_the_permalink());?>" class="spnc-more-link" title="<?php echo esc_attr($newscrunch_read_more);?>"><?php echo esc_html($newscrunch_read_more);?></a>
	                        	<?php endif;?>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</article>   
</div>