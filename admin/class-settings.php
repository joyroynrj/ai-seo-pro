<?php
/**
 * The settings page functionality.
 *
 * @package    AI_SEO_Pro
 * @subpackage AI_SEO_Pro/admin
 * @author     Joy Roy
 */
class AI_SEO_Pro_Settings
{

	/**
	 * The ID of this plugin.
	 *
	 * @var      string    $plugin_name
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @var      string    $version
	 */
	private $version;

	/**
	 * Active tab.
	 *
	 * @var      string    $active_tab
	 */
	private $active_tab;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'general';
	}

	/**
	 * Add options page to admin menu.
	 */
	public function add_plugin_admin_menu()
	{
		add_menu_page(
			__('AI SEO Pro', 'ai-seo-pro'),
			__('AI SEO Pro', 'ai-seo-pro'),
			'manage_options',
			$this->plugin_name,
			array($this, 'display_plugin_admin_page'),
			'dashicons-search',
			65
		);

		add_submenu_page(
			$this->plugin_name,
			__('Dashboard', 'ai-seo-pro'),
			__('Dashboard', 'ai-seo-pro'),
			'manage_options',
			$this->plugin_name,
			array($this, 'display_plugin_admin_page')
		);

		add_submenu_page(
			$this->plugin_name,
			__('Settings', 'ai-seo-pro'),
			__('Settings', 'ai-seo-pro'),
			'manage_options',
			$this->plugin_name . '-settings',
			array($this, 'display_plugin_settings_page')
		);

		add_submenu_page(
			$this->plugin_name,
			__('Help', 'ai-seo-pro'),
			__('Help', 'ai-seo-pro'),
			'manage_options',
			$this->plugin_name . '-help',
			array($this, 'display_help_page')
		);
	}

	/**
	 * Register plugin settings.
	 */
	public function register_settings()
	{

		// General settings
		register_setting(
			'ai_seo_pro_general',
			'ai_seo_pro_post_types',
			array(
				'type' => 'array',
				'sanitize_callback' => array($this, 'sanitize_post_types'),
			)
		);

		register_setting(
			'ai_seo_pro_general',
			'ai_seo_pro_title_separator',
			array(
				'type' => 'string',
				'default' => '-',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		register_setting('ai_seo_pro_general', 'ai_seo_pro_homepage_title');
		register_setting('ai_seo_pro_general', 'ai_seo_pro_homepage_description');
		register_setting('ai_seo_pro_general', 'ai_seo_pro_site_represents');
		register_setting('ai_seo_pro_general', 'ai_seo_pro_organization_name');
		register_setting('ai_seo_pro_general', 'ai_seo_pro_person_name');
		register_setting('ai_seo_pro_general', 'ai_seo_pro_site_logo');

		// API settings
		register_setting(
			'ai_seo_pro_api',
			'ai_seo_pro_api_provider',
			array(
				'type' => 'string',
				'default' => 'gemini',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		register_setting(
			'ai_seo_pro_api',
			'ai_seo_pro_api_key',
			array(
				'type' => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		register_setting(
			'ai_seo_pro_api',
			'ai_seo_pro_auto_generate',
			array(
				'type' => 'boolean',
				'default' => false,
			)
		);

		// Feature settings - SEPARATE GROUP
		register_setting('ai_seo_pro_features', 'ai_seo_pro_enable_content_analysis');
		register_setting('ai_seo_pro_features', 'ai_seo_pro_enable_seo_score');
		register_setting('ai_seo_pro_features', 'ai_seo_pro_enable_schema');
		register_setting('ai_seo_pro_features', 'ai_seo_pro_focus_keyword');
		register_setting('ai_seo_pro_features', 'ai_seo_pro_readability_analysis');

		// Social settings - SEPARATE GROUP (FIXED)
		register_setting('ai_seo_pro_social', 'ai_seo_pro_og_tags');
		register_setting('ai_seo_pro_social', 'ai_seo_pro_twitter_cards');
		register_setting('ai_seo_pro_social', 'ai_seo_pro_default_og_image');
		register_setting('ai_seo_pro_social', 'ai_seo_pro_twitter_username');

		// Advanced settings
		register_setting('ai_seo_pro_advanced', 'ai_seo_pro_noindex_archives');
		register_setting('ai_seo_pro_advanced', 'ai_seo_pro_remove_stopwords');
		register_setting('ai_seo_pro_advanced', 'ai_seo_pro_breadcrumbs');
		register_setting('ai_seo_pro_advanced', 'ai_seo_pro_api_timeout');
		register_setting('ai_seo_pro_advanced', 'ai_seo_pro_cache_duration');
	}

	/**
	 * Display the plugin admin page.
	 */
	public function display_plugin_admin_page()
	{
		require_once AI_SEO_PRO_PLUGIN_DIR . 'admin/views/dashboard.php';
	}

	/**
	 * Display the plugin settings page.
	 */
	public function display_plugin_settings_page()
	{
		?>
		<div class="wrap ai-seo-pro-settings">
			<h1><?php echo esc_html(get_admin_page_title()); ?></h1>

			<?php settings_errors(); ?>

			<nav class="nav-tab-wrapper">
				<a href="?page=<?php echo $this->plugin_name; ?>-settings&tab=general"
					class="nav-tab <?php echo $this->active_tab === 'general' ? 'nav-tab-active' : ''; ?>">
					<?php _e('General', 'ai-seo-pro'); ?>
				</a>
				<a href="?page=<?php echo $this->plugin_name; ?>-settings&tab=api"
					class="nav-tab <?php echo $this->active_tab === 'api' ? 'nav-tab-active' : ''; ?>">
					<?php _e('AI API', 'ai-seo-pro'); ?>
				</a>
				<a href="?page=<?php echo $this->plugin_name; ?>-settings&tab=features"
					class="nav-tab <?php echo $this->active_tab === 'features' ? 'nav-tab-active' : ''; ?>">
					<?php _e('Features', 'ai-seo-pro'); ?>
				</a>
				<a href="?page=<?php echo $this->plugin_name; ?>-settings&tab=social"
					class="nav-tab <?php echo $this->active_tab === 'social' ? 'nav-tab-active' : ''; ?>">
					<?php _e('Social Media', 'ai-seo-pro'); ?>
				</a>
				<a href="?page=<?php echo $this->plugin_name; ?>-settings&tab=advanced"
					class="nav-tab <?php echo $this->active_tab === 'advanced' ? 'nav-tab-active' : ''; ?>">
					<?php _e('Advanced', 'ai-seo-pro'); ?>
				</a>
			</nav>

			<div class="tab-content">
				<?php
				switch ($this->active_tab) {
					case 'general':
						$this->render_general_tab();
						break;
					case 'api':
						$this->render_api_tab();
						break;
					case 'features':
						$this->render_features_tab();
						break;
					case 'social':
						$this->render_social_tab();
						break;
					case 'advanced':
						$this->render_advanced_tab();
						break;
					default:
						$this->render_general_tab();
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render General settings tab.
	 */
	private function render_general_tab()
	{
		?>
		<form method="post" action="options.php">
			<?php
			settings_fields('ai_seo_pro_general');
			do_settings_sections('ai_seo_pro_general');
			?>
			<table class="form-table">
				<tr>
					<th scope="row">
						<label><?php _e('Enable for Post Types', 'ai-seo-pro'); ?></label>
					</th>
					<td>
						<?php
						$post_types = get_post_types(array('public' => true), 'objects');
						$enabled_post_types = get_option('ai_seo_pro_post_types', array('post', 'page'));

						foreach ($post_types as $post_type) {
							if ($post_type->name === 'attachment')
								continue;
							?>
							<label style="display: block; margin-bottom: 5px;">
								<input type="checkbox" name="ai_seo_pro_post_types[]"
									value="<?php echo esc_attr($post_type->name); ?>" <?php checked(in_array($post_type->name, $enabled_post_types)); ?>>
								<?php echo esc_html($post_type->label); ?>
							</label>
							<?php
						}
						?>
						<p class="description">
							<?php _e('Select which post types should have AI SEO meta boxes.', 'ai-seo-pro'); ?>
						</p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="title_separator"><?php _e('Title Separator', 'ai-seo-pro'); ?></label>
					</th>
					<td>
						<select name="ai_seo_pro_title_separator" id="title_separator">
							<?php
							$separators = array('-', '–', '—', '|', '/', '::', '<', '>');
							$current = get_option('ai_seo_pro_title_separator', '-');
							foreach ($separators as $sep) {
								printf(
									'<option value="%s" %s>%s</option>',
									esc_attr($sep),
									selected($current, $sep, false),
									esc_html($sep)
								);
							}
							?>
						</select>
						<p class="description">
							<?php _e('Choose separator for page titles.', 'ai-seo-pro'); ?>
						</p>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="homepage_title"><?php _e('Homepage Title', 'ai-seo-pro'); ?></label>
					</th>
					<td>
						<input type="text" id="homepage_title" name="ai_seo_pro_homepage_title"
							value="<?php echo esc_attr(get_option('ai_seo_pro_homepage_title')); ?>" class="regular-text">
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="homepage_description"><?php _e('Homepage Description', 'ai-seo-pro'); ?></label>
					</th>
					<td>
						<textarea id="homepage_description" name="ai_seo_pro_homepage_description" rows="3"
							class="large-text"><?php echo esc_textarea(get_option('ai_seo_pro_homepage_description')); ?></textarea>
					</td>
				</tr>
			</table>

			<?php submit_button(); ?>
		</form>
		<?php
	}

	/**
	 * Render API settings tab.
	 */
	private function render_api_tab()
	{
		require_once AI_SEO_PRO_PLUGIN_DIR . 'admin/views/settings-api.php';
	}

	/**
	 * Render Features settings tab.
	 */
	private function render_features_tab()
	{
		require_once AI_SEO_PRO_PLUGIN_DIR . 'admin/views/settings-features.php';
	}

	/**
	 * Render Social Media settings tab.
	 */
	private function render_social_tab()
	{
		require_once AI_SEO_PRO_PLUGIN_DIR . 'admin/views/settings-social.php';
	}

	/**
	 * Render Advanced settings tab.
	 */
	private function render_advanced_tab()
	{
		require_once AI_SEO_PRO_PLUGIN_DIR . 'admin/views/settings-advanced.php';
	}

	/**
	 * Display help page.
	 */
	public function display_help_page()
	{
		require_once AI_SEO_PRO_PLUGIN_DIR . 'admin/views/help.php';
	}

	/**
	 * Sanitize post types array.
	 *
	 * @param    array    $input    User input.
	 * @return   array
	 */
	public function sanitize_post_types($input)
	{
		if (!is_array($input)) {
			return array();
		}

		return array_map('sanitize_text_field', $input);
	}
}