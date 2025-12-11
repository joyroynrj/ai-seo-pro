<?php
/**
 * Redirects List View
 *
 * @package    AI_SEO_Pro
 * @subpackage AI_SEO_Pro/admin/views
 * @var array $results Redirects results
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="redirects-actions" style="margin: 20px 0;">
    <a href="?page=<?php echo $this->plugin_name; ?>-redirects&action=import" class="button">
        <?php _e('Import CSV', 'ai-seo-pro'); ?>
    </a>
    <a href="<?php echo wp_nonce_url('?page=' . $this->plugin_name . '-redirects&action=export', 'export_redirects'); ?>"
        class="button">
        <?php _e('Export CSV', 'ai-seo-pro'); ?>
    </a>
</div>

<!-- Search Form -->
<form method="get" style="margin: 20px 0;">
    <input type="hidden" name="page" value="<?php echo $this->plugin_name; ?>-redirects">
    <p class="search-box">
        <input type="search" name="s" value="<?php echo esc_attr($search); ?>"
            placeholder="<?php _e('Search redirects...', 'ai-seo-pro'); ?>">
        <button type="submit" class="button"><?php _e('Search', 'ai-seo-pro'); ?></button>
    </p>
</form>

<?php if (empty($results['redirects'])): ?>
    <div class="notice notice-info">
        <p><?php _e('No redirects found. Click "Add New" to create your first redirect.', 'ai-seo-pro'); ?></p>
    </div>
<?php else: ?>
    <form method="post" id="bulk-action-form">
        <?php wp_nonce_field('bulk_delete_redirects', 'ai_seo_redirect_nonce'); ?>
        <input type="hidden" name="action" value="bulk_delete">

        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <button type="submit" class="button action"
                    onclick="return confirm('<?php _e('Are you sure you want to delete selected redirects?', 'ai-seo-pro'); ?>');">
                    <?php _e('Delete Selected', 'ai-seo-pro'); ?>
                </button>
            </div>
        </div>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <td class="check-column">
                        <input type="checkbox" id="select-all">
                    </td>
                    <th><?php _e('Source URL', 'ai-seo-pro'); ?></th>
                    <th><?php _e('Target URL', 'ai-seo-pro'); ?></th>
                    <th><?php _e('Type', 'ai-seo-pro'); ?></th>
                    <th><?php _e('Hits', 'ai-seo-pro'); ?></th>
                    <th><?php _e('Status', 'ai-seo-pro'); ?></th>
                    <th><?php _e('Actions', 'ai-seo-pro'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results['redirects'] as $redirect): ?>
                    <tr>
                        <th class="check-column">
                            <input type="checkbox" name="redirect_ids[]" value="<?php echo $redirect->id; ?>">
                        </th>
                        <td>
                            <strong><?php echo esc_html($redirect->source_url); ?></strong>
                            <?php if ($redirect->is_regex): ?>
                                <span class="badge"
                                    style="background: #007cba; color: #fff; padding: 2px 6px; border-radius: 3px; font-size: 11px; margin-left: 5px;">REGEX</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo esc_url(home_url($redirect->target_url)); ?>" target="_blank">
                                <?php echo esc_html($redirect->target_url); ?>
                            </a>
                        </td>
                        <td>
                            <span class="redirect-type redirect-type-<?php echo $redirect->redirect_type; ?>">
                                <?php echo $redirect->redirect_type; ?>
                            </span>
                        </td>
                        <td><?php echo number_format($redirect->hits); ?></td>
                        <td>
                            <?php if ($redirect->is_active): ?>
                                <span class="status-active" style="color: #46b450;">● <?php _e('Active', 'ai-seo-pro'); ?></span>
                            <?php else: ?>
                                <span class="status-inactive" style="color: #999;">● <?php _e('Inactive', 'ai-seo-pro'); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="?page=<?php echo $this->plugin_name; ?>-redirects&action=edit&id=<?php echo $redirect->id; ?>"
                                class="button button-small">
                                <?php _e('Edit', 'ai-seo-pro'); ?>
                            </a>
                            <a href="<?php echo wp_nonce_url('?page=' . $this->plugin_name . '-redirects&action=delete&id=' . $redirect->id, 'delete_redirect_' . $redirect->id); ?>"
                                class="button button-small"
                                onclick="return confirm('<?php _e('Are you sure you want to delete this redirect?', 'ai-seo-pro'); ?>');">
                                <?php _e('Delete', 'ai-seo-pro'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if ($results['pages'] > 1): ?>
            <div class="tablenav bottom">
                <div class="tablenav-pages">
                    <?php
                    $pagination = paginate_links(array(
                        'base' => add_query_arg('paged', '%#%'),
                        'format' => '',
                        'prev_text' => '&laquo;',
                        'next_text' => '&raquo;',
                        'total' => $results['pages'],
                        'current' => $page,
                    ));

                    if ($pagination) {
                        echo $pagination;
                    }
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </form>
<?php endif; ?>

<style>
    .redirect-type {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 3px;
        font-size: 12px;
        font-weight: 600;
    }

    .redirect-type-301 {
        background: #d7f2d8;
        color: #1e4620;
    }

    .redirect-type-302,
    .redirect-type-307 {
        background: #fff4e5;
        color: #8a6d3b;
    }

    .redirect-type-410,
    .redirect-type-451 {
        background: #ffe5e5;
        color: #a94442;
    }
</style>

<script>
    jQuery(document).ready(function ($) {
        // Select all checkbox
        $('#select-all').on('change', function () {
            $('input[name="redirect_ids[]"]').prop('checked', this.checked);
        });
    });
</script>