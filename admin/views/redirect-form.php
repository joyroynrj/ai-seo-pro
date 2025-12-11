<?php
/**
 * Redirect Form View
 *
 * @package    AI_SEO_Pro
 * @subpackage AI_SEO_Pro/admin/views
 * @var object $redirect Redirect object
 */

if (!defined('ABSPATH')) {
    exit;
}

$is_edit = isset($redirect->id);
$page_title = $is_edit ? __('Edit Redirect', 'ai-seo-pro') : __('Add New Redirect', 'ai-seo-pro');
?>

<div class="redirect-form-container" style="max-width: 800px;">
    <h2><?php echo esc_html($page_title); ?></h2>

    <form method="post" action="" class="redirect-form">
        <?php wp_nonce_field('ai_seo_redirect_save', 'ai_seo_redirect_nonce'); ?>
        <input type="hidden" name="action" value="save_redirect">
        <?php if ($is_edit): ?>
            <input type="hidden" name="redirect_id" value="<?php echo $redirect->id; ?>">
        <?php endif; ?>

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="source_url">
                        <?php _e('Source URL', 'ai-seo-pro'); ?>
                        <span class="required">*</span>
                    </label>
                </th>
                <td>
                    <input type="text" id="source_url" name="source_url"
                        value="<?php echo esc_attr($redirect->source_url); ?>" class="large-text" required
                        placeholder="/old-page">
                    <p class="description">
                        <?php _e('The URL to redirect FROM. Use relative URLs starting with / (e.g., /old-page)', 'ai-seo-pro'); ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="target_url">
                        <?php _e('Target URL', 'ai-seo-pro'); ?>
                        <span class="required">*</span>
                    </label>
                </th>
                <td>
                    <input type="text" id="target_url" name="target_url"
                        value="<?php echo esc_attr($redirect->target_url); ?>" class="large-text" required
                        placeholder="/new-page">
                    <p class="description">
                        <?php _e('The URL to redirect TO. Can be relative (/new-page) or absolute (https://example.com)', 'ai-seo-pro'); ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="redirect_type"><?php _e('Redirect Type', 'ai-seo-pro'); ?></label>
                </th>
                <td>
                    <select id="redirect_type" name="redirect_type" class="regular-text">
                        <option value="301" <?php selected($redirect->redirect_type, '301'); ?>>
                            301 - <?php _e('Permanent', 'ai-seo-pro'); ?>
                        </option>
                        <option value="302" <?php selected($redirect->redirect_type, '302'); ?>>
                            302 - <?php _e('Temporary', 'ai-seo-pro'); ?>
                        </option>
                        <option value="307" <?php selected($redirect->redirect_type, '307'); ?>>
                            307 - <?php _e('Temporary (Preserve Method)', 'ai-seo-pro'); ?>
                        </option>
                        <option value="410" <?php selected($redirect->redirect_type, '410'); ?>>
                            410 - <?php _e('Gone', 'ai-seo-pro'); ?>
                        </option>
                        <option value="451" <?php selected($redirect->redirect_type, '451'); ?>>
                            451 - <?php _e('Unavailable For Legal Reasons', 'ai-seo-pro'); ?>
                        </option>
                    </select>
                    <p class="description">
                        <?php _e('301 is recommended for SEO. Use 302/307 for temporary redirects.', 'ai-seo-pro'); ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php _e('Options', 'ai-seo-pro'); ?></th>
                <td>
                    <fieldset>
                        <label>
                            <input type="checkbox" name="is_regex" value="1" <?php checked($redirect->is_regex, 1); ?>>
                            <?php _e('Enable Regular Expression (Regex)', 'ai-seo-pro'); ?>
                        </label>
                        <p class="description">
                            <?php _e('Use regex patterns for advanced URL matching. Example: /category/(.*) → /new-category/$1', 'ai-seo-pro'); ?>
                        </p>
                    </fieldset>

                    <fieldset style="margin-top: 10px;">
                        <label>
                            <input type="checkbox" name="is_active" value="1" <?php checked($redirect->is_active, 1); ?>>
                            <?php _e('Active', 'ai-seo-pro'); ?>
                        </label>
                        <p class="description">
                            <?php _e('Uncheck to temporarily disable this redirect without deleting it.', 'ai-seo-pro'); ?>
                        </p>
                    </fieldset>
                </td>
            </tr>
        </table>

        <div class="redirect-examples"
            style="background: #f9f9f9; border-left: 4px solid #2271b1; padding: 15px; margin: 20px 0;">
            <h4 style="margin-top: 0;"><?php _e('Examples:', 'ai-seo-pro'); ?></h4>
            <ul style="margin: 0;">
                <li><strong><?php _e('Simple:', 'ai-seo-pro'); ?></strong> /old-page → /new-page</li>
                <li><strong><?php _e('To Homepage:', 'ai-seo-pro'); ?></strong> /removed-page → /</li>
                <li><strong><?php _e('External:', 'ai-seo-pro'); ?></strong> /blog → https://blog.example.com</li>
                <li><strong><?php _e('Regex:', 'ai-seo-pro'); ?></strong> /category/(.*) → /new-category/$1</li>
            </ul>
        </div>

        <p class="submit">
            <button type="submit" class="button button-primary button-large">
                <?php echo $is_edit ? __('Update Redirect', 'ai-seo-pro') : __('Add Redirect', 'ai-seo-pro'); ?>
            </button>
            <a href="?page=<?php echo $this->plugin_name; ?>-redirects" class="button button-large">
                <?php _e('Cancel', 'ai-seo-pro'); ?>
            </a>
        </p>
    </form>
</div>

<style>
    .redirect-form-container {
        background: #fff;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-top: 20px;
    }

    .required {
        color: #dc3232;
    }
</style>

<script>
    jQuery(document).ready(function ($) {
        // Show/hide target URL based on redirect type
        $('#redirect_type').on('change', function () {
            var type = $(this).val();
            if (type === '410' || type === '451') {
                $('#target_url').closest('tr').hide();
            } else {
                $('#target_url').closest('tr').show();
            }
        }).trigger('change');

        // Validate form
        $('.redirect-form').on('submit', function (e) {
            var sourceUrl = $('#source_url').val();
            var targetUrl = $('#target_url').val();
            var redirectType = $('#redirect_type').val();

            // Validate source URL
            if (!sourceUrl || sourceUrl.trim() === '') {
                alert('<?php _e('Please enter a source URL.', 'ai-seo-pro'); ?>');
                e.preventDefault();
                return false;
            }

            // Validate target URL (except for 410/451)
            if (redirectType !== '410' && redirectType !== '451') {
                if (!targetUrl || targetUrl.trim() === '') {
                    alert('<?php _e('Please enter a target URL.', 'ai-seo-pro'); ?>');
                    e.preventDefault();
                    return false;
                }
            }
        });
    });
</script>