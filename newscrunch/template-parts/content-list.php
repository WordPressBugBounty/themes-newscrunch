<?php
/**
 * Template part for displaying content
 *
 * @package NewsCrunch Theme
 */
?>
<article itemscope itemtype="https://schema.org/Article" id="post-<?php the_ID(); ?>" <?php post_class('spnc-post '); ?> >
    <div class="spnc-post-overlay">
        <?php if(has_post_thumbnail()): ?>
            <!-- Post Featured Image -->
            <figure class="spnc-post-thumbnail <?php echo esc_attr(get_theme_mod('img_animation','i_effect1'));?>"> 
                <?php the_post_thumbnail('full', array('class'=>'img-fluid sp-thumb-img', 'loading' => false, 'itemprop'=>'image' )); ?>
            </figure>
        <?php endif; ?> 
        <!-- Post Date -->
        <?php if ( get_theme_mod('newscrunch_enable_post_date',true) == true ) : ?>
            <span class="spnc-date">    
                <?php echo newcrunch_post_date_time(get_the_ID()); ?>
            </span>
        <?php endif; ?>
    </div>
    <div class="spnc-post-content">
        <?php if(get_theme_mod('newscrunch_enable_post_category',true)==true):
            if ( has_category() ) :
                echo '<span itemprop="about" class="spnc-cat-links">';
                    newscrunch_get_categories(get_the_ID());
                echo '</span>';
            endif; 
        endif; 
        if(get_theme_mod('newscrunch_enable_post_title',true)==true): ?>
            <header class="entry-header">
                <h4 class="spnc-entry-title">
                    <a class="<?php echo esc_attr(get_theme_mod('link_animate','a_effect1'));?>" itemprop="url" href="<?php the_permalink();?>" title="<?php the_title(); ?>"><?php the_title();?></a>
                </h4>
            </header>
        <?php endif; ?>
        <div class="spnc-entry-content">
            <?php if(get_theme_mod('newscrunch_enable_post_description',true)==true): ?>
                <p class="spnc-description">
                    <?php newscrunch_excerpt(15); ?>
                </p>
            <?php endif; ?>
            <div class="spnc-footer-meta">
                <?php 
                $newscrunch_author = get_theme_mod('newscrunch_enable_post_author', true);
                $newscrunch_comment = get_theme_mod('newscrunch_enable_post_comment', true);
                if( ( $newscrunch_author == true) || ( $newscrunch_comment == true) || (get_theme_mod('reading_time_enable',false) === true) ) : ?>
                    <div class=" spnc-entry-meta">
                        <?php if ( $newscrunch_author == true ) :?>
                        <span class="spnc-author">
                            <figure>
                                <img src="<?php echo get_avatar_url( get_the_author_meta( 'ID' ), array( 'size' => 96 ) ); ?>" class="img-fluid sp-thumb-img" alt="<?php esc_attr_e('author-image','newscrunch'); ?>">
                            </figure>
                            <a <?php if (is_rtl()) { echo 'dir="rtl"';} ?> itemprop="url" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" title="<?php echo esc_attr__('Posts by','newscrunch') . ' ' . esc_attr(get_the_author()); ?>"><?php echo esc_html(get_the_author()); ?></a>
                        </span>
                        <?php endif; ?>
                        <!-- Post Comments -->
                        <?php if( $newscrunch_comment == true ):?>
                            <span class="spnc-comment-links">
                                <i class="fas fa-comment-alt"></i>
                                <a <?php if (is_rtl()) { echo 'dir="rtl"';} ?> itemprop="url" href="<?php the_permalink(); ?>#respond" title="<?php esc_attr_e('Number of Comments','newscrunch'); ?>"><?php echo esc_html(get_comments_number()) . ' ' . esc_html(get_theme_mod('newscrunch_blog_archive_comments',__('Comments','newscrunch'))); ?></a>
                            </span>
                        <?php endif; 
                        /* Read Time */
                        if ( class_exists('Newscrunch_Plus') ):
                            if(get_theme_mod('reading_time_enable',false) === true): ?>
                                <span class="spnc-read-time"><i class="fa fa-eye"></i> <?php spncp_reading_time();?></span>
                        <?php endif; endif;
                        ?>
                    </div>
                <?php
                endif; 
                    $newscrunch_read_more = get_theme_mod('newscrunch_blog_archive_read_btn', __('Read More','newscrunch'));
                    if( get_theme_mod('newscrunch_enable_post_read_more', true ) == true ): ?>
                        <a itemprop="url" href="<?php echo esc_url(get_the_permalink());?>" class="spnc-more-link" title="<?php echo esc_attr($newscrunch_read_more);?>"><?php echo esc_html($newscrunch_read_more);?></a>
                    <?php endif;
                ?>
            </div>
        </div>
    </div>
</article>