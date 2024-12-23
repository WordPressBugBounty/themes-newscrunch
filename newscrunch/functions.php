<?php
/**
 * Newscrunch functions and definitions
 *
 * @package Newscrunch
 */

// Global variables define
define('NEWSCRUNCH_TEMPLATE_DIR_URI', get_template_directory_uri());
define('NEWSCRUNCH_TEMPLATE_DIR', get_template_directory());

// wp_body_open function definition
if ( ! function_exists( 'wp_body_open' ) ) {

    function wp_body_open() {
        /**
         * Triggered after the opening <body> tag.
         */
        do_action( 'wp_body_open' );
    }
}

/**
 * Load all core theme function files
*/
require NEWSCRUNCH_TEMPLATE_DIR . '/inc/scripts/script.php';
require NEWSCRUNCH_TEMPLATE_DIR . '/inc/helpers.php';
require NEWSCRUNCH_TEMPLATE_DIR . '/inc/breadcrumbs/breadcrumbs.php';
require NEWSCRUNCH_TEMPLATE_DIR . '/inc/breadcrumbs/breadcrumbs-2.php';
require NEWSCRUNCH_TEMPLATE_DIR . '/inc/menu/default_menu_walker.php';
require NEWSCRUNCH_TEMPLATE_DIR . '/inc/menu/newscrunch_nav_walker.php';
require NEWSCRUNCH_TEMPLATE_DIR . '/partials/widgets/register-sidebars.php';
require NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/customizer.php';
require NEWSCRUNCH_TEMPLATE_DIR . '/inc/theme-color/custom-color.php';
require NEWSCRUNCH_TEMPLATE_DIR . '/inc/theme-color/color-background.php';
require NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/selective-refresh.php';
require NEWSCRUNCH_TEMPLATE_DIR . '/inc/meta-boxes/newscrunch-meta-box.php'; 
require NEWSCRUNCH_TEMPLATE_DIR . '/inc/meta-boxes/newscrunch-post-format-meta-box.php';
require NEWSCRUNCH_TEMPLATE_DIR . '/inc/pagination/newscrunch-pagination.php';
if( class_exists( 'Spice_Starter_Sites' )):
require NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/upsell/class-customize.php'; 
endif;
require NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/customizer-recommended-plugin.php';

if ( ! function_exists( 'spncp_activate' ) ) {
	require NEWSCRUNCH_TEMPLATE_DIR . '/inc/font/font.php';
}

require_once ( NEWSCRUNCH_TEMPLATE_DIR . '/inc/customizer/sanitize-callback.php' );

if ( ! function_exists( 'newscrunch_setup' ) ) :
	/**
		* Sets up theme defaults and registers support for various WordPress features.
		*
		* Note that this function is hooked into the after_setup_theme hook, which
		* runs before the init hook. The init hook is too late for some features, such
		* as indicating support for post thumbnails.
	 */
	function newscrunch_setup() {
		/*
			* Make theme available for translation.
			* Translations can be filed in the /languages/ directory.
			* If you're building a theme based on News Crunch, use a find and replace
			* to change 'newscrunch' to the name of your theme in all the template files.
		*/
		load_theme_textdomain( 'newscrunch', NEWSCRUNCH_TEMPLATE_DIR . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		* Add theme supports.
		*/
		add_theme_support( 'title-tag' );
		add_theme_support( "align-wide" );
		add_editor_style();
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'register_block_style' );
		add_theme_support( 'register_block_pattern' );
		/*
		* Enable support for Post Thumbnails on posts and pages.
		*/
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Menu', 'newscrunch' ),
				'footer_menu' => esc_html__( 'Footer Menu', 'newscrunch' ),
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'newscrunch_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Post Formats
		add_theme_support( 'post-formats', array( 'video', 'gallery', 'quote', 'audio', 'link' ) );

		//Add support for core custom logo.
		add_theme_support('custom-logo',
			array(
				'height'      => 60,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
				'header-text' => array('site-title', 'site-description')
			)
		);

		// Add theme support for HTML5.
    	add_theme_support( 'html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ) );

		if( !class_exists('Newscrunch_Plus') ) {
			//About Theme	         
	        $newscrunch_theme = wp_get_theme(); // gets the current theme
	        if ('Newscrunch' == $newscrunch_theme->name || 'Newscrunch Child' == $newscrunch_theme->name || 'Newscrunch child' == $newscrunch_theme->name ) {
	            if (is_admin()) {                       
	                require NEWSCRUNCH_TEMPLATE_DIR . '/admin/admin-init.php';
	            }
	        }
		}	
	}
endif;
add_action( 'after_setup_theme', 'newscrunch_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function newscrunch_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'newscrunch_content_width', 640 );
}
add_action( 'after_setup_theme', 'newscrunch_content_width', 0 );

/*
 * Add Body Class
 */
add_filter( 'body_class', 'newscrunch_body_class' );
function newscrunch_body_class( $classes ) {
        $classes[] = 'newscrunch';
	return $classes;
}

function newscrunch_hedder_full_layout(){
    if(get_theme_mod('header_layout','2')=='full'):?>
        <style type="text/css">
           @media (min-width: 1200px){
           	.spnc-topbar{padding:0 50px;}
           	.header-sidebar .spnc-container,.header-1 .spnc-custom .spnc-navbar{max-width: 100%;}
           	.header-sidebar.header-1 .spnc-navbar .spnc-container {padding: 12px 50px;}
           }
           .stickymenu {
           	max-width: 100%;
           }
        </style>
    <?php 
    endif;
}
add_action('wp_head','newscrunch_hedder_full_layout');

$newscrunch_theme = wp_get_theme(); // gets the current theme 

// Notice to add required plugin
if(!class_exists('Newscrunch_Plus')){
	if('Newscrunch' == $newscrunch_theme->name || 'Newscrunch Child' == $newscrunch_theme->name || 'Newscrunch child' == $newscrunch_theme->name ) :
	    function newscrunch_admin_plugin_notice_warn() {
	        $theme_name=wp_get_theme();
	        if ( get_option( 'dismissed-newscrunch_comanion_plugin', false ) ) {
	           return;
	        }?>

	        <div class="updated notice is-dismissible newscrunch-theme-notice">
	        	<div class="dashboard-hero-panel">
		            <div class="hero-panel-content">
		                <div class="hero-panel-subtitle">
		                    <?php esc_html_e('Hello', 'newscrunch'); 
		                    echo ', '; 
		                    $current_user = wp_get_current_user();
		                    echo esc_html($current_user->display_name);
		                    ?>
		                </div>
		                <div class="hero-panel-title">
		                    <?php 
		                    /* translators: %s: theme name */
		                    printf(esc_html__('Welcome to', 'newscrunch') . ' %s', $theme_name ); ?>
		                </div>
		                <div class="hero-panel-description">
		                    <?php 
		                    /* translators: %s: theme name */
		                    printf('%s ' . esc_html__("is now installed and ready to use. We've provide some links to get you started.", 'newscrunch'), $theme_name ); ?>
		                </div>
		                <div class="theme-admin-button-wrap theme-admin-button-group">
		                	<a href="<?php echo esc_url(admin_url('admin.php?page=newscrunch-welcome')); ?>" class="button theme-admin-button admin-button-secondary" target="_self" title="<?php esc_attr_e('Theme Dashboard', 'newscrunch'); ?>">
			                        <span class="dashicons dashicons-dashboard"></span>
			                        <span><?php esc_html_e('Theme Dashboard', 'newscrunch'); ?></span>
			                </a>
		                    <a href="<?php echo esc_url('https://spicethemes.com/newscrunch-wordpress-theme/#newscrunch_demo_lite'); ?>" class="button theme-admin-button admin-button-secondary" target="_blank" title="<?php esc_attr_e('Live Demo', 'newscrunch'); ?>">
		                        <span class="dashicons dashicons-welcome-view-site"></span>
		                        <span><?php esc_html_e('View Live Demos', 'newscrunch'); ?></span>
		                    </a>
		                    <a href="<?php echo esc_url('https://helpdoc.spicethemes.com/category/newscrunch/'); ?>" class="button theme-admin-button admin-button-secondary" target="_blank" title="<?php esc_attr_e('Help Docs', 'newscrunch'); ?>">
		                        <span class="dashicons dashicons-media-document"></span>
		                        <span><?php esc_html_e('Theme Documentation', 'newscrunch'); ?></span>
		                    </a>
			                <?php if(!class_exists('Spice_Starter_Sites')){?>
			               		<button id="install-plugin-button" data-plugin-url="<?php echo esc_url( 'https://spicethemes.com/extensions/spice-starter-sites.zip' ); ?>">
                                    <?php echo esc_html__( 'Install Plugin', 'newscrunch' ); ?>
                                </button>
			               <?php }?>
		                </div>
		            </div>
		            <div class="hero-panel-image">
		                    <img src="<?php echo esc_url(get_theme_file_uri().'/admin/assets/img/welcome-banner.png');?>" alt="<?php esc_attr_e('Welcome Banner','newscrunch'); ?>">
		            </div>
	        	</div>
	        </div>
	        
	        <script type="text/javascript">
	            jQuery(function($) {
	            $( document ).on( 'click', '.newscrunch-theme-notice .notice-dismiss', function () {
	                var type = $( this ).closest( '.newscrunch-theme-notice' ).data( 'notice' );
	                $.ajax( ajaxurl,
	                  {
	                    type: 'POST',
	                    data: {
	                      action: 'dismissed_notice_handler',
	                      type: type,
	                    }
	                  } );
	              } );
	          });
	        </script>
	    <?php

	    }
	     global $pagenow;
	    if ( "themes.php" == $pagenow && is_admin() ) {
	    add_action( 'admin_notices', 'newscrunch_admin_plugin_notice_warn' );
	    add_action( 'wp_ajax_dismissed_notice_handler', 'newscrunch_ajax_notice_handler');
		}
	endif;
}

if ( ! function_exists( 'newcrunch_schema_attributes' ) ) :
	function newcrunch_schema_attributes() {
		$itemtype = 'WebPage'; 
		$blog_page = ( is_home() || is_archive() || is_attachment() || is_tax() || is_single() );
		$itemtype = ( $blog_page ) ? 'Blog' : $itemtype;
		$itemtype = ( is_search() ) ? 'SearchResultsPage' : $itemtype;
		$itemtype_final = apply_filters( 'newcrunch_schema_attributes_itemtype', $itemtype );
		echo apply_filters( 'newcrunch_schema_attributes', "itemtype='https://schema.org/" . esc_attr( $itemtype_final ) . "' itemscope='itemscope'" );
	}
endif;

// Freemius snippet code
if('Newscrunch' == $newscrunch_theme->name || 'Newscrunch Child' == $newscrunch_theme->name || 'Newscrunch child' == $newscrunch_theme->name ) {
	if ( ! function_exists( 'new_fs' ) ) {
		if(class_exists('Spice_Starter_Sites') && defined( 'SPICE_STARTER_SITES_PLUGIN_PATH' ) && file_exists(SPICE_STARTER_SITES_PLUGIN_PATH . '/freemius/start.php')) {
		    // Create a helper function for easy SDK access.
		    function new_fs() {
		        global $new_fs;

		        if ( ! isset( $new_fs ) ) {
		            // Include Freemius SDK.
		            require_once SPICE_STARTER_SITES_PLUGIN_PATH . '/freemius/start.php';

		            $new_fs = fs_dynamic_init( array(
		                'id'                  => 	'12701',
		                'slug'                => 	'newscrunch',
		                'type'                => 	'theme',
		                'public_key'          => 	'pk_364d8ab336ff6a7292ae9fa7719fe',
		                'is_premium'          =>	true,
		                'has_premium_version' => 	false,
		                'has_addons'          => 	true,
		                'has_paid_plans'      => 	false,
		                'menu'                => 	array(
		                    'slug'           =>		'newscrunch-welcome',
		                    'account'        =>		true,
		                    'support'        =>		true,
		                )
		            ) );
		        }

		        return $new_fs;
		    }

		    // Init Freemius.
		    new_fs();
		    // Signal that SDK was initiated.
		    do_action( 'new_fs_loaded' );
		}
	}
}

// Update release notice to the admin dashboard
if(!class_exists('Newscrunch_Plus')) {
	if('Newscrunch' == $newscrunch_theme->name || 'Newscrunch Child' == $newscrunch_theme->name || 'Newscrunch child' == $newscrunch_theme->name ) :
		function newscrunch_add_update_admin_notice() {
			$theme = wp_get_theme(); 
		  ?>
		    <div class="newscrunch-update-notice notice notice-info is-dismissible">
		        <div class="notice-content-wrap">
		            <div class="notice-content">
		            	<h2><?php printf( '%1$s ' . __('Current','newscrunch') . ' %2$s', esc_html($theme->name), '<span>Version' . ' ' . esc_html($theme->get('Version')) . '</span>'); ?></h2>
		                
		                <p class="notice-des">
		                	<?php printf( '%1$s %2$s %3$s', esc_html__("We've consistently aimed to meet our users' needs and demands. In order to address specific requirements and rectify issues from our previous version, we've rolled out version","newscrunch"), esc_html($theme->get('Version')), esc_html__('complete with exciting new features. Take a look now!','newscrunch')); ?>
		                </p>

		                <ol class="admin-notice-up-list">
		                    <li><?php echo 'Added Category description.'; ?></li>
		                    <li><?php echo 'Added blog/archive overlay layout in pro.'; ?></li>
		                </ol>

		                <div class="admin-notice-up-btn-wrap">
		                	<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button theme-admin-button admin-button-secondary" target="_blank" title="<?php esc_attr_e('Try Now', 'newscrunch'); ?>">
		                		<span class="dashicons dashicons-admin-customizer"></span>
		                		<span><?php esc_html_e('Try It Now', 'newscrunch'); ?></span>
		                	</a>

		                	<a href="<?php echo esc_url('https://spicethemes.com/newscrunch-changelog/'); ?>" class="button theme-admin-button admin-button-secondary" target="_blank" title="<?php esc_attr_e('Changelog', 'newscrunch'); ?>">
		                		<span class="dashicons dashicons-visibility"></span>
		                		<span><?php esc_html_e('See Changelog', 'newscrunch'); ?></span>
		                	</a>

			                <a href="<?php echo esc_url('https://youtube.com/playlist?list=PLTfjrb24Pq_DeJOZdKEaP3rZPbHuOCLtZ&si=rsDRjg6uD5J_LFkv'); ?>" class="button theme-admin-button admin-button-secondary" target="_blank" title="<?php esc_attr_e('Watch Videos', 'newscrunch'); ?>">
			                	<span class="dashicons dashicons-youtube"></span>
			                	<span><?php esc_html_e('Watch Videos', 'newscrunch'); ?></span>
			                </a>

			                <a href="<?php echo esc_url('https://spicethemes.com/newscrunch-plus/'); ?>" class="button theme-admin-button admin-button-secondary" target="_blank" title="<?php esc_attr_e('Upgrade To Pro', 'newscrunch'); ?>">
			                	<span class="dashicons dashicons-update"></span>
			                	<span><?php esc_html_e('Upgrade To Pro', 'newscrunch'); ?></span> 
			               	</a>			               	
		                </div>
		            </div>
		        </div>
		    </div>
		<?php
		}
		global $pagenow;
	    if("themes.php" == $pagenow && is_admin()) {
			add_action('admin_notices', 'newscrunch_add_update_admin_notice');
		}
	endif;	
}


// Get the post date
if ( ! function_exists( 'newcrunch_post_date_time' ) ) :
	function newcrunch_post_date_time( $post_id='', $tag='' ) 
	{
	    if(get_theme_mod('select_date_format','date_format_by_wp')== 'date_format_by_theme')
	    {
	    	if (is_rtl()) { $rtl = 'dir="rtl"'; } else { $rtl =''; }
	    	$display_date = (get_theme_mod('select_display_date','publish')=='publish') ? 'get_the_time' : 'get_the_modified_time';
	    	return '<span '.$rtl.' class="display-time">'.human_time_diff($display_date('U',$post_id), current_time('timestamp')) . " " . __('ago','newscrunch').'</span>';	
	    }
	    else
	    {	
	    	if($tag == 'no')
	    	{
	    		if (is_rtl()) { $rtl = 'dir="rtl"'; } else { $rtl =''; }
		    	$post_date = (get_theme_mod('select_display_date','publish')=='publish') ? get_the_date() : get_the_modified_date();
		    	return '<time '.$rtl.' itemprop="'.$post_date.'" class="entry-date">'.esc_html($post_date).'</time>';
	    	}
	    	else
	    	{
	    		if (is_rtl()) { $rtl = 'dir="rtl"'; } else { $rtl =''; }
		    	$post_date = (get_theme_mod('select_display_date','publish')=='publish') ? get_the_date() : get_the_modified_date();
		    	return '<a '.$rtl.' itemprop="url" href="'.esc_url(home_url('/')).esc_html(date('Y/m', strtotime(get_the_date()))).'" title="'.esc_attr__('date-time','newscrunch').'"><time itemprop="'.$post_date.'" class="entry-date">'.esc_html($post_date).'</time></a>';
	    	}
	    	
	    }
	}
endif;

// Hook the AJAX action for logged-in users
add_action('wp_ajax_newscrunch_check_plugin_status', 'newscrunch_check_plugin_status');

function newscrunch_check_plugin_status() {

    if (!isset($_POST['plugin_slug'])) {
        wp_send_json_error('No plugin slug provided.');
        return;
    }

    $plugin_slug = sanitize_text_field($_POST['plugin_slug']);
    $plugin_main_file = $plugin_slug . '/' . $plugin_slug . '.php'; // Adjust this based on your plugin structure

    // Check if the plugin exists
    $plugins = get_plugins();
    if (isset($plugins[$plugin_main_file])) {
        if (is_plugin_active($plugin_main_file)) {
            wp_send_json_success(array('status' => 'activated'));
        } else {
            wp_send_json_success(array('status' => 'installed'));
        }
    } else {
        wp_send_json_success(array('status' => 'not_installed'));
    }
}

// Existing AJAX installation function for installing and activating
add_action('wp_ajax_newscrunch_install_activate_plugin', 'newscrunch_install_and_activate_plugin');

function newscrunch_install_and_activate_plugin() {

    if (!isset($_POST['plugin_url'])) {
        wp_send_json_error('No plugin URL provided.');
        return;
    }

    // Include necessary WordPress files for plugin installation
    include_once(ABSPATH . 'wp-admin/includes/file.php');
    include_once(ABSPATH . 'wp-admin/includes/misc.php');
    include_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');

    $plugin_url = esc_url($_POST['plugin_url']);
    $plugin_slug = sanitize_text_field($_POST['plugin_slug']);
    $plugin_main_file = $plugin_slug . '/' . $plugin_slug . '.php'; // Ensure this matches your plugin structure

    WP_Filesystem();
    // Download the plugin file
    $temp_file = download_url($plugin_url);

    if (is_wp_error($temp_file)) {
        wp_send_json_error($temp_file->get_error_message());
        return;
    }

    // Unzip the plugin to the plugins folder
    $plugin_folder = WP_PLUGIN_DIR;
    $result = unzip_file($temp_file, $plugin_folder);

    // Clean up temporary file
    unlink($temp_file);

    if (is_wp_error($result)) {
        wp_send_json_error($result->get_error_message());
        return;
    }

    // Activate the plugin if it was installed
    $activate_result = activate_plugin($plugin_main_file);

    // Return success with redirect URL
    if ( class_exists('Newscrunch_Plus') ){
    	wp_send_json_success(array('redirect_url' => admin_url('admin.php?page=newscrunch-plus-welcome')));
    }else{
    	wp_send_json_success(array('redirect_url' => admin_url('admin.php?page=newscrunch-welcome')));
    }
}

// Enqueue JavaScript for the button functionality
add_action('admin_enqueue_scripts', 'newscrunch_enqueue_plugin_installer_script');

function newscrunch_enqueue_plugin_installer_script() {
    wp_enqueue_script('newscrunch-plugin-installer-js',  NEWSCRUNCH_TEMPLATE_DIR_URI . '/admin/assets/js/plugin-installer.js', array('jquery'), null, true);
    wp_localize_script('newscrunch-plugin-installer-js', 'pluginInstallerAjax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('plugin_installer_nonce')
    ));
}