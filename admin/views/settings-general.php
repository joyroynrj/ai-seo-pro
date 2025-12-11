<?php
/**
 * General settings tab.
 *
 * @package    AI_SEO_Pro
 * @subpackage AI_SEO_Pro/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$enabled_post_types = get_option( 'ai_seo_pro_post_types', array( 'post', 'page' ) );
$title_separator = get_option( 'ai_seo_pro_title_separator', '-' );
$homepage_title = get_option( 'ai_seo_pro_homepage_title', get_bloginfo( 'name' ) );
$homepage_description = get_option( 'ai_seo_pro_homepage_description', get_bloginfo( 'description' ) );
?>

<form method="post" action="options.php">
	<?php settings_fields( 'ai_seo_pro_general' ); ?>
	
	<h2><?php _e( 'Post Type Settings', 'ai-seo-pro' ); ?></h2>
	
	<table class="form-table">
		<tr>
			<th scope="row">
				<label><?php _e( 'Enable for Post Types', 'ai-seo-pro' ); ?></label>
			</th>
			<td>
				<div class="post-type-list">
					<?php
					$post_types = get_post_types( array( 'public' => true ), 'objects' );
					
					foreach ( $post_types as $post_type ) {
						if ( $post_type->name === 'attachment' ) {
							continue;
						}
						?>
						<label>
							<input type="checkbox" 
								   name="ai_seo_pro_post_types[]" 
								   value="<?php echo esc_attr( $post_type->name ); ?>"
								   <?php checked( in_array( $post_type->name, $enabled_post_types ) ); ?>>
							<span><?php echo esc_html( $post_type->label ); ?></span>
						</label>
						<?php
					}
					?>
				</div>
				<p class="description">
					<?php _e( 'Select which post types should have AI SEO meta boxes', 'ai-seo-pro' ); ?>
				</p>
			</td>
		</tr>
	</table>

	<h2><?php _e( 'Title Settings', 'ai-seo-pro' ); ?></h2>
	
	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="title_separator"><?php _e( 'Title Separator', 'ai-seo-pro' ); ?></label>
			</th>
			<td>
				<select name="ai_seo_pro_title_separator" id="title_separator" class="regular-text">
					<?php
					$separators = array(
						'-'  => '- (dash)',
						'–'  => '– (ndash)',
						'—'  => '— (mdash)',
						'|'  => '| (pipe)',
						'/'  => '/ (slash)',
						'::' => ':: (double colon)',
						'<'  => '< (less than)',
						'>'  => '> (greater than)',
					);
					
					foreach ( $separators as $sep => $label ) {
						printf(
							'<option value="%s" %s>%s</option>',
							esc_attr( $sep ),
							selected( $title_separator, $sep, false ),
							esc_html( $label )
						);
					}
					?>
				</select>
				<p class="description">
					<?php _e( 'Choose the separator to use in page titles (e.g., "Post Title - Site Name")', 'ai-seo-pro' ); ?>
				</p>
				
				<div class="title-preview" style="margin-top: 15px; padding: 10px; background: #f9f9f9; border-left: 4px solid #2271b1;">
					<strong><?php _e( 'Preview:', 'ai-seo-pro' ); ?></strong><br>
					<span id="title-preview-text">
						<?php echo esc_html( 'Your Post Title ' . $title_separator . ' ' . get_bloginfo( 'name' ) ); ?>
					</span>
				</div>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="homepage_title"><?php _e( 'Homepage Title', 'ai-seo-pro' ); ?></label>
			</th>
			<td>
				<input type="text" 
					   id="homepage_title"
					   name="ai_seo_pro_homepage_title" 
					   value="<?php echo esc_attr( $homepage_title ); ?>" 
					   class="large-text"
					   placeholder="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<p class="description">
					<?php _e( 'The title that will be used for your homepage', 'ai-seo-pro' ); ?>
				</p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="homepage_description"><?php _e( 'Homepage Description', 'ai-seo-pro' ); ?></label>
			</th>
			<td>
				<textarea id="homepage_description"
						  name="ai_seo_pro_homepage_description" 
						  rows="3" 
						  class="large-text"
						  placeholder="<?php echo esc_attr( get_bloginfo( 'description' ) ); ?>"><?php echo esc_textarea( $homepage_description ); ?></textarea>
				<p class="description">
					<?php _e( 'The description that will be used for your homepage', 'ai-seo-pro' ); ?>
				</p>
			</td>
		</tr>
	</table>

	<h2><?php _e( 'Knowledge Graph', 'ai-seo-pro' ); ?></h2>
	
	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="site_represents"><?php _e( 'Site Represents', 'ai-seo-pro' ); ?></label>
			</th>
			<td>
				<select name="ai_seo_pro_site_represents" id="site_represents" class="regular-text">
					<option value="organization" <?php selected( get_option( 'ai_seo_pro_site_represents', 'organization' ), 'organization' ); ?>>
						<?php _e( 'Organization', 'ai-seo-pro' ); ?>
					</option>
					<option value="person" <?php selected( get_option( 'ai_seo_pro_site_represents' ), 'person' ); ?>>
						<?php _e( 'Person', 'ai-seo-pro' ); ?>
					</option>
				</select>
				<p class="description">
					<?php _e( 'Does this site represent an organization or a person?', 'ai-seo-pro' ); ?>
				</p>
			</td>
		</tr>

		<tr id="organization_name_row">
			<th scope="row">
				<label for="organization_name"><?php _e( 'Organization Name', 'ai-seo-pro' ); ?></label>
			</th>
			<td>
				<input type="text" 
					   id="organization_name"
					   name="ai_seo_pro_organization_name" 
					   value="<?php echo esc_attr( get_option( 'ai_seo_pro_organization_name', get_bloginfo( 'name' ) ) ); ?>" 
					   class="regular-text">
				<p class="description">
					<?php _e( 'The name of your organization', 'ai-seo-pro' ); ?>
				</p>
			</td>
		</tr>

		<tr id="person_name_row" style="display: none;">
			<th scope="row">
				<label for="person_name"><?php _e( 'Person Name', 'ai-seo-pro' ); ?></label>
			</th>
			<td>
				<input type="text" 
					   id="person_name"
					   name="ai_seo_pro_person_name" 
					   value="<?php echo esc_attr( get_option( 'ai_seo_pro_person_name' ) ); ?>" 
					   class="regular-text">
				<p class="description">
					<?php _e( 'Your name', 'ai-seo-pro' ); ?>
				</p>
			</td>
		</tr>

		<tr>
			<th scope="row">
				<label for="site_logo"><?php _e( 'Site Logo URL', 'ai-seo-pro' ); ?></label>
			</th>
			<td>
				<input type="url" 
					   id="site_logo"
					   name="ai_seo_pro_site_logo" 
					   value="<?php echo esc_url( get_option( 'ai_seo_pro_site_logo', get_site_icon_url() ) ); ?>" 
					   class="regular-text">
				<p class="description">
					<?php _e( 'URL of your site logo (recommended: 600x60px)', 'ai-seo-pro' ); ?>
				</p>
			</td>
		</tr>
	</table>

	<?php submit_button(); ?>
</form>

<script>
jQuery(document).ready(function($) {
	// Update title preview
	$('#title_separator').on('change', function() {
		var separator = $(this).val();
		var preview = 'Your Post Title ' + separator + ' <?php echo esc_js( get_bloginfo( 'name' ) ); ?>';
		$('#title-preview-text').text(preview);
	});
	
	// Toggle organization/person fields
	$('#site_represents').on('change', function() {
		if ($(this).val() === 'organization') {
			$('#organization_name_row').show();
			$('#person_name_row').hide();
		} else {
			$('#organization_name_row').hide();
			$('#person_name_row').show();
		}
	}).trigger('change');
});
</script>