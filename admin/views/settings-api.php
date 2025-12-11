<?php
/**
 * API settings tab.
 *
 * @package    AI_SEO_Pro
 * @subpackage AI_SEO_Pro/admin/views
 */

if (!defined('ABSPATH')) {
	exit;
}

$current_provider = get_option('ai_seo_pro_api_provider', 'gemini');
$api_key = get_option('ai_seo_pro_api_key', '');
$auto_generate = get_option('ai_seo_pro_auto_generate', false);
?>

<form method="post" action="options.php">
	<?php settings_fields('ai_seo_pro_api'); ?>

	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="api_provider"><?php _e('AI Provider', 'ai-seo-pro'); ?></label>
			</th>
			<td>
				<select name="ai_seo_pro_api_provider" id="api_provider" class="regular-text">
					<option value="anthropic" <?php selected($current_provider, 'anthropic'); ?>>
						Anthropic (Claude)
					</option>
					<option value="gemini" <?php selected($current_provider, 'gemini'); ?>>
						Google (Gemini)
					</option>
				</select>
				<p class="description">
					<?php _e('Choose which AI provider to use for generating meta tags', 'ai-seo-pro'); ?>
				</p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="api_key"><?php _e('API Key', 'ai-seo-pro'); ?></label>
			</th>
			<td>
				<input type="password" id="api_key" name="ai_seo_pro_api_key" value="<?php echo esc_attr($api_key); ?>"
					class="regular-text">
				<p class="description">
					<?php _e('Enter your API key from your chosen provider', 'ai-seo-pro'); ?>
				</p>

				<div class="api-key-instructions" style="margin-top: 15px;">
					<p><strong><?php _e('How to get your API key:', 'ai-seo-pro'); ?></strong></p>
					<ul class="provider-instructions">
						<li data-provider="anthropic">
							<strong>Anthropic:</strong>
							<a href="https://console.anthropic.com/" target="_blank">
								https://console.anthropic.com/
							</a>
						</li>
						<li data-provider="gemini">
							<strong>Google Gemini:</strong>
							<a href="https://makersuite.google.com/app/apikey" target="_blank">
								https://makersuite.google.com/app/apikey
							</a>
						</li>
					</ul>
				</div>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<?php _e('Auto-generate', 'ai-seo-pro'); ?>
			</th>
			<td>
				<label>
					<input type="checkbox" name="ai_seo_pro_auto_generate" value="1" <?php checked($auto_generate, true); ?>>
					<?php _e('Automatically generate meta tags for new posts', 'ai-seo-pro'); ?>
				</label>
				<p class="description">
					<?php _e('When enabled, meta tags will be generated automatically when you publish a new post', 'ai-seo-pro'); ?>
				</p>
			</td>
		</tr>
	</table>

	<?php submit_button(); ?>
</form>