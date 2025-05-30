<?php
/**
 * @author spicethemes
 */

if (!class_exists('Newscrunch_About_Page')) {
	class Newscrunch_About_Page {

		public static $instance;
		public $options;
		public $version = '1.0.0';
		public $theme;
		public $demo_link;
		public $docs_link;
		public $rate_link;
		public $theme_page;
		public $pro_link;
		public $tabs;
		public $action_count;
		public $recommended_actions;

		public static function get_instance() {

			if (is_null(self::$instance)) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function __construct() {
			$this->theme               = wp_get_theme();
			$actions                   = $this->get_recommended_actions();
			$this->action_count        = $actions['count'];
			$this->recommended_actions = $actions['actions'];

			add_action('admin_menu', array($this, 'add_theme_info_menu'));
			add_action('wp_ajax_newscrunch_update_rec_acts', array($this, 'update_recommended_actions_watch'));
			add_action('load-themes.php', array($this, 'activation_admin_notice'));
			/* enqueue script and style for welcome screen */
			add_action( 'admin_enqueue_scripts', array( $this, 'style_and_scripts' ) );
			
			/* load welcome screen */
			add_action( 'newscrunch_info_screen', array( $this, 'getting_started' ), 10 );
			add_action( 'newscrunch_info_screen', array( $this, 'recommended_actions' ), 20 );
			add_action( 'newscrunch_info_screen', array( $this, 'video_tutorials' ), 30 );
		}

		/**
	 	* Load welcome screen css and javascript
	 	* @sfunctionse  1.8.2.4
	 	*/
		public function style_and_scripts( $hook_suffix ) {

			if ( 'toplevel_page_newscrunch-welcome' == $hook_suffix || 'admin_page_spice-settings-importer' == $hook_suffix ) {
			
				wp_enqueue_style( 'newscrunch-info-screen-css', NEWSCRUNCH_TEMPLATE_DIR_URI . '/admin/assets/css/welcome.css' );
				wp_enqueue_style('newscrunch-theme-info-style', NEWSCRUNCH_TEMPLATE_DIR_URI . '/admin/assets/css/welcome-page-styles.css');
				wp_enqueue_style('newscrunch-welcome_customizer', NEWSCRUNCH_TEMPLATE_DIR_URI . '/admin/assets/css/welcome_customizer.css');
				wp_enqueue_script('plugin-install');
				wp_enqueue_script('updates');
				
			}
			wp_enqueue_script('newscrunch-blocks-install', NEWSCRUNCH_TEMPLATE_DIR_URI . '/admin/assets/js/plugin-install.js', array('jquery'));
			wp_enqueue_script('newscrunch-ajax', NEWSCRUNCH_TEMPLATE_DIR_URI . '/admin/assets/js/ajax.js', array('jquery'));
			wp_localize_script('newscrunch-blocks-install', 'newscrunch_companion_install',
			array(
				'installing' => esc_html__('Installing', 'newscrunch' ),
				'activating' => esc_html__('Activating', 'newscrunch' ),
				'error'      => esc_html__('Error', 'newscrunch' ),
				'ajax_url'   => esc_url(admin_url('admin-ajax.php')),
			));
		}

		/**
		 * Load scripts for customizer page
		 * @sfunctionse  1.8.2.4
		 */
		public function scripts_for_customizer() {

			wp_enqueue_style( 'newscrunch-info-screen-customizer-css', NEWSCRUNCH_TEMPLATE_DIR_URI . '/admin/assets/css/welcome_customizer.css' );
			wp_enqueue_script( 'newscrunch-info-screen-customizer-js', NEWSCRUNCH_TEMPLATE_DIR_URI . '/admin/assets/js/welcome_customizer.js', array('jquery'), '20120206', true );
			global $newscrunch_required_actions;
			$nr_actions_required = 0;

			/* get number of required actions */
			if( get_option('newscrunch_show_required_actions') ):
				$newscrunch_show_required_actions = get_option('newscrunch_show_required_actions');
			else:
				$newscrunch_show_required_actions = array();
			endif;

			if( !empty($newscrunch_required_actions) ):
				foreach( $newscrunch_required_actions as $newscrunch_required_action_value ):
					if(( !isset( $newscrunch_required_action_value['check'] ) || ( isset( $newscrunch_required_action_value['check'] ) && ( $newscrunch_required_action_value['check'] == false ) ) ) && ((isset($newscrunch_show_required_actions[$newscrunch_required_action_value['id']]) && ($newscrunch_show_required_actions[$newscrunch_required_action_value['id']] == true)) || !isset($newscrunch_show_required_actions[$newscrunch_required_action_value['id']]) )) :
						$nr_actions_required++;
					endif;
				endforeach;
			endif;

			wp_localize_script( 'newscrunch-info-screen-customizer-js', 'newscrunchLiteWelcomeScreenCustomizerObject', array(
				'nr_actions_required' => $nr_actions_required,
				'aboutpage' => esc_url( admin_url( 'themes.php?page=newscrunch-info' ) ),
				'customizerpage' => esc_url( admin_url( 'customize.php' ) ),
				'themeinfo' => esc_html__('View Theme Info','newscrunch' ),
			) );
		}
		
		public function add_theme_info_menu() {
			$theme = $this->theme;
			$count = $this->action_count;
			$count = ($count > 0) ? '<span class="awaiting-mod action-count"><span>' . $count . '</span></span>' : '';
			/* translators: %1$s: theme name and %2$s: count of recommended plugins */
			$title = sprintf(esc_html__('About','newscrunch') . ' %1$s %2$s ', esc_html($theme->get('Name')), $count);
			/* translators: %1$s: theme name and %2$s: version of the theme*/
			add_menu_page(sprintf(esc_html__('Welcome to', 'newscrunch' ) . ' %1$s %2$s ', esc_html($theme->get('Name')), esc_html($theme->get('Version'))), $title, 'edit_theme_options', 'newscrunch-welcome', array($this, 'print_welcome_page'));	
		}

		public function activation_admin_notice() {
			global $pagenow;
			if (is_admin() && ('themes.php' == $pagenow) && isset($_GET['activated'])) {
				add_action('admin_notices', array($this, 'welcome_admin_notice'), 99);
			}
		}

		public function welcome_admin_notice() {
			$theme = $this->theme;
			$theme_name = $theme->get('Name');
			?>
			<div class="updated notice is-dismissible">
				<p><?php 
				/* translators: %1$s: theme name and %2$ %3$s: url of the options page */
				printf( '%1$s ' . esc_html__('theme is installed. To take full advantage of the features this theme has to offer visit our','newscrunch') . ' %2$s ' . esc_html__('welcome page','newscrunch') . '%3$s', esc_html($theme_name), '<a href="' . esc_url( admin_url( 'admin.php?page=newscrunch-welcome' ) ) . '" class="newscrunch-wl-page-link">', '</a>' ); ?></p>
				<p><a href="<?php echo esc_url(admin_url('admin.php?page=newscrunch-welcome')); ?>" class="button newscrunch-get-start-btn" style="text-decoration: none;"><?php 
				/* translators: %s: theme name */
				printf( esc_html__( 'Get started with', 'newscrunch') . ' %s ' . esc_html__('theme', 'newscrunch'), esc_html($theme_name)); ?></a></p>
			</div>
			<?php
		}

		public function print_welcome_page() {
			$theme = $this->theme;
			?>
	  		<div class="spice-container-fulid">
				<div class="spice-row">
					<div class="spice-col-1">
						<div class="wrap theme-info-wrap newscrunch-wrap">
							<div style="clear: both;"></div>
							<div class="theme-welcome-container" style="min-height:300px;">
								<div class="theme-welcome-inner">
									<?php
										$tabs = $this->get_tabs_array();
										$tabs_head     = '';
										$tab_file_path = '';
										$active_tab    = 'getting_started';

										if (isset($_GET['tab']) && $_GET['tab']) {
											$active_tab = sanitize_text_field($_GET['tab']);
										}

										foreach ($tabs as $key => $tab) {
											$active_class = '';
											$count        = '';
											if ($active_tab == $tab['link']) {
												
												$tab_file_path = $tab['file_path'];
											}

											if ($tab['link'] == 'recommended_actions') {
												$count = $this->action_count;
												$count = ($count > 0) ? '<span class="badge-action-count">' . $count . '</span>' : '';
											}
			                             
											$tabs_head .= sprintf('<li role="presentation"><a href="%s" class="nav-tab %s" role="tab" data-toggle="tab">%s</a></li>', esc_url(('#' . $tab['link'])), $active_class, $tab['name'] . $count);
											                    
										}
										
									?>
									<ul class="newscrunch-nav-tabs" role="tablist">
										<?php echo wp_kses_post($tabs_head); ?>
									</ul>
									 
									<div class="newscrunch-tab-content">
										<?php do_action( 'newscrunch_info_screen' ); ?>
									</div>
									
									<div class="tab-content <?php echo esc_attr($active_tab); ?>">
									 	<?php
											if (!empty($tab_file_path) && file_exists($tab_file_path)) {
												require_once $tab_file_path;
											}
										?>
									 	<div style="clear: both;"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
		
		/**
		 * Getting Started
		 * 
		 */
		public function getting_started() {
			get_template_part( 'admin/tab-pages/getting-started' );
		}

		/**
		 * Recommended Action
		 * 
		 */
		public function recommended_actions() {
			$newscrunch_actions = $this->recommended_actions;
			$newscrunch_actions_todo = get_option( 'recommended_actions', false );
			// Pass $newscrunch_actions to the template part
		    set_query_var( 'newscrunch_actions', $newscrunch_actions );
		    set_query_var( 'newscrunch_actions_todo', $newscrunch_actions_todo );
			get_template_part( 'admin/tab-pages/recommended_actions' );
		}


		/**
		 * Video Tutorials
		 * 
		 */
		public function video_tutorials() {
			get_template_part( 'admin/tab-pages/video-tutorials' );
		}
	
	
		public function get_tabs_array() {
			$tabs_array = array();
			
			$tabs_array[]	= array(
				'link'      => 'getting_started',
				'name'      => 'Getting Started',
				'file_path' => NEWSCRUNCH_TEMPLATE_DIR . '/admin/tab-pages/getting-started.php',
			);
				
			$tabs_array[]	= 	array(
				'link'      => 'recommended_actions',
				'name'      => 'Recommended Actions',
				'file_path' => NEWSCRUNCH_TEMPLATE_DIR . '/admin/tab-pages/recommended_actions.php',
			);

			$tabs_array[]	= 	array(
				'link'      => 'video_tutorials',
				'name'      => 'Video Tutorials',
				'file_path' => NEWSCRUNCH_TEMPLATE_DIR . '/admin/tab-pages/video-tutorials.php',
			);
						
			return $tabs_array;
		}

		public function get_recommended_plugins() {
			
			$plugins = apply_filters('newscrunch_recommended_plugins', array());
			return $plugins;
		}

		public function get_useful_plugins() {
			$plugins = apply_filters('newscrunch_useful_plugins', array());
			return $plugins;
		}

		public function get_recommended_actions() {

			$act_count = 0;
			$actions_todo = get_option( 'recommended_actions', array());
			
			$plugins = $this->get_recommended_plugins();

			if ($plugins) {
				foreach ($plugins as $key => $plugin) {
					$action = array();
					if (!isset($plugin['slug'])) {
						continue;
					}

					$action['id']   = 'install_' . $plugin['slug'];
					$action['desc'] = '';
					if (isset($plugin['desc'])) {
						$action['desc'] = $plugin['desc'];
					}

					$action['name'] = '';
					if (isset($plugin['name'])) {
						$action['title'] = $plugin['name'];
					}

					$link_and_is_done  = $this->get_plugin_buttion($plugin['slug'], $plugin['name']);
					$action['link']    = $link_and_is_done['button'];
					$action['is_done'] = $link_and_is_done['done'];
					if (!$action['is_done'] && (!isset($actions_todo[$action['id']]) || !$actions_todo[$action['id']])) {
						$act_count++;
					}
					$recommended_actions[] = $action;
					$actions_todo[]        = array('id' => $action['id'], 'watch' => true);
				}
				return array('count' => $act_count, 'actions' => $recommended_actions);
			}

		}

		public function get_plugin_buttion($slug, $name) {
			$is_done      = false;
			$button_html  = '';
			$is_installed = $this->is_plugin_installed($slug);
			$plugin_path  = $this->get_plugin_basename_from_slug($slug);
			$is_activeted = $this->chk_plg($name);
			if (!$is_installed) {
				$plugin_install_url = add_query_arg(
					array(
						'action' => 'install-plugin',
						'plugin' => $slug,
					),
					self_admin_url('update.php')
				);
				$plugin_install_url = wp_nonce_url($plugin_install_url, 'install-plugin_' . $slug);

				$button_html        = sprintf('<a class="spicethemes-plugin-install install-now button-secondary button" data-slug="%1$s" href="%2$s" aria-label="%3$s" data-name="%4$s">%5$s</a>',
					esc_attr($slug),
					esc_url($plugin_install_url),
					/* translators: %s: theme name */
					sprintf(esc_html__('Install','newscrunch') . ' %s ' . esc_html__('now','newscrunch'), esc_html($name)),
					esc_html($name),
					esc_html__('Install and activate', 'newscrunch' )
				);
			} 
			elseif ($is_installed && !$is_activeted) {

				$plugin_activate_link = add_query_arg(
					array(
						'action'        => 'activate',
						'plugin'        => rawurlencode($plugin_path),
						'plugin_status' => 'all',
						'paged'         => '1',
						'_wpnonce'      => wp_create_nonce('activate-plugin_' . $plugin_path),
					), self_admin_url('plugins.php')
				);

				global $pagenow;
    			if ( "themes.php" != $pagenow && is_admin() )
    			{
    				$activate_string=esc_html__('Activate', 'newscrunch' );
    			}
    			else
    			{
    				$activate_string=esc_html__('Activate Import Plugin', 'newscrunch' );
    			}

				$button_html = sprintf('<a class="spicethemes-plugin-activate activate-now button-primary button" data-slug="%1$s" href="%2$s" aria-label="%3$s" data-name="%4$s">%5$s</a>',
					esc_attr($slug),
					esc_url($plugin_activate_link),
					/* translators: %s: theme name */
					sprintf(esc_html__('Activate','newscrunch') . ' %s ' . esc_html__('now','newscrunch'), esc_html($name)),
					esc_html($name),
					$activate_string
				);
			} 
			elseif ($is_activeted) {
				global $pagenow;
    			if ( "themes.php" != $pagenow && is_admin() )
    			{
				$button_html = sprintf('<div class="action-link button disabled"><span class="dashicons dashicons-yes"></span> %s</div>', esc_html__('Active', 'newscrunch' ));
				$is_done     = true;
				}
			}

			return array('done' => $is_done, 'button' => $button_html);
		}

		public function chk_plg($name)
		{
			if( class_exists('WPCF7') && ($name=='Contact Form 7'))
			{
				return true;
			}
			if( class_exists('Unique_Headers_Instantiate') && ($name=='Unique Headers'))
			{
				return true;
			}
			if( function_exists('wpseo_init') && ($name=='Yoast SEO'))
			{
				return true;
			}
			if( class_exists('Spice_Post_Slider') && ($name=='Spice Post Slider'))
			{
				return true;
			}
			if( class_exists('Spice_Social_Share') && ($name=='Spice Social Share'))
			{
				return true;
			}
			if( function_exists('sobw_fs') && ($name=='Seo Optimized Images'))
			{
				return true;
			}
			if( class_exists('WooCommerce') && ($name=='WooCommerce'))
			{
				return true;
			}

		}

		public function get_plugin_basename_from_slug($slug) {
			$keys = array_keys($this->get_installed_plugins());
			foreach ($keys as $key) {
				if (preg_match('|^' . $slug . '/|', $key)) {
					return $key;
				}
			}
			return $slug;
		}

		public function is_plugin_installed($slug) {
			$installed_plugins = $this->get_installed_plugins(); // Retrieve a list of all installed plugins (WP cached).
			$file_path         = $this->get_plugin_basename_from_slug($slug);
			return (!empty($installed_plugins[$file_path]));
		}

		public function get_installed_plugins() {

			if (!function_exists('get_plugins')) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			return get_plugins();
		}

		public function update_recommended_actions_watch() {
			if (isset($_POST['action_id'])) {
				$action_id    = sanitize_text_field($_POST['action_id']);
				$actions_todo = get_option('recommended_actions', array());

				if ((!isset($actions_todo[$action_id]) || !$actions_todo[$action_id])) {
					$actions_todo[$action_id] = true;
				} else {
					$actions_todo[$action_id] = false;
				}
				update_option('recommended_actions', $actions_todo);
			}
			echo json_encode(get_option('recommended_actions'));
			wp_die();
		}


		public function get_plugin_info_api( $slug ) {
			require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
			$call_api = get_transient( 'st_about_plugin_info_' . $slug );

			if ( false === $call_api ) {
				$call_api = plugins_api(
					'plugin_information', array(
						'slug'   => $slug,
						'fields' => array(
							'downloaded'        => false,
							'rating'            => false,
							'description'       => false,
							'short_description' => true,
							'donate_link'       => false,
							'tags'              => false,
							'sections'          => true,
							'homepage'          => true,
							'added'             => false,
							'last_updated'      => false,
							'compatibility'     => false,
							'tested'            => false,
							'requires'          => false,
							'downloadlink'      => false,
							'icons'             => true,
						),
					)
				);
				set_transient( 'st_about_plugin_info_' . $slug, $call_api, 30 * MINUTE_IN_SECONDS );
			}

			return $call_api;
		}

		public function get_plugin_icon( $icons ) {

			if ( ! empty( $icons['svg'] ) ) {
				$plugin_icon_url = $icons['svg'];
			} elseif ( ! empty( $icons['2x'] ) ) {
				$plugin_icon_url = $icons['2x'];
			} elseif ( ! empty( $icons['1x'] ) ) {
				$plugin_icon_url = $icons['1x'];
			} else {
				$plugin_icon_url = NEWSCRUNCH_TEMPLATE_DIR . '/admin/assets/images/placeholder_plugin.png';
			}
			return $plugin_icon_url;
		}
	}
}

function newscrunch_useful_plugins_array($plugins){
	$plugins[] = array(
		'slug'     => 'elementor',
	);
	return $plugins;
}
add_filter('newscrunch_useful_plugins', 'newscrunch_useful_plugins_array');

function newscrunch_recommended_plugins_array($plugins){
		
		$plugins[] = array(
			'name'     	=> 'Contact Form 7',
			'slug'     	=> 'contact-form-7',
			'desc'     	=> esc_html__('To display the contact form, install & activate this plugin.', 'newscrunch' ),
		);
	 	$plugins[] = array(
			'name'     => 'Yoast SEO',
			'slug'     => 'wordpress-seo',
			'desc'     => esc_html__('To enable the display of breadcrumbs, please install and activate this plugin.', 'newscrunch'),
	    );
		$plugins[] = array(
	        'name' 		=> 'Unique Headers',
	        'slug' 		=> 'unique-headers',
	        'desc' 		=> esc_html__('To add a different banner image for a different page or post, install & activate this plugin.', 'newscrunch'),
        );
	    $plugins[] = array(
			'name'     => 'Spice Post Slider',
			'slug'     => 'spice-post-slider',
			'desc'     => esc_html__('To display the posts in a beautiful slider with multiple options, install & activate this plugin.', 'newscrunch'),
	    );
	    $plugins[] = array(
			'name'     => 'Spice Social Share',
			'slug'     => 'spice-social-share',
			'desc'     => esc_html__('To add the social share buttons to your posts, install & activate this plugin.', 'newscrunch'),
	    );
	    $plugins[] = array(
			'name'     => 'Seo Optimized Images',
            'slug'     => 'seo-optimized-images',
            'desc'     => esc_html__('We highly recommend installing and activating the Seo Optimized Images plugin, which will dynamically insert SEO-friendly alt attributes and title attributes to enhance your image optimization.', 'newscrunch'),
		);
		$plugins[] = array(
			'name'     => 'WooCommerce',
			'slug'     => 'woocommerce',
			'desc'     => esc_html__('We highly recommend installing and activating the WooCommerce plugin, which allows you to create a shop and enhance your business.', 'newscrunch'),
	    );

	return $plugins;
}
add_filter('newscrunch_recommended_plugins', 'newscrunch_recommended_plugins_array');

function newscrunch_about_page() {
	return Newscrunch_About_Page::get_instance();
}
global $newscrunch_about_page;
$newscrunch_about_page = newscrunch_about_page();