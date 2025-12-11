<?php
/**
 * 404 Monitor Dashboard View
 *
 * @package    AI_SEO_Pro
 * @subpackage AI_SEO_Pro/admin/views
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get filter parameters
$days = isset($_GET['days']) ? intval($_GET['days']) : 30;
$page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
$search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

// Get statistics
$stats = $this->monitor_404->get_statistics($days);

// Get logs
$results = $this->monitor_404->get_404_logs(array(
    'page' => $page,
    'per_page' => 20,
    'search' => $search,
    'days' => $days,
));
?>

<div class="wrap ai-seo-404-monitor">
    <h1><?php _e('404 Error Monitor', 'ai-seo-pro'); ?></h1>

    <!-- Statistics Cards -->
    <div class="stats-grid"
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 20px 0;">
        <div class="stat-card"
            style="background: #fff; border-left: 4px solid #dc3232; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="margin: 0 0 10px 0; color: #666; font-size: 14px;">
                <?php _e('Total 404 Errors', 'ai-seo-pro'); ?></h3>
            <div style="font-size: 36px; font-weight: bold; color: #dc3232;">
                <?php echo number_format($stats['total_404s']); ?>
            </div>
            <p style="margin: 5px 0 0 0; color: #999; font-size: 12px;">
                <?php printf(__('Last %d days', 'ai-seo-pro'), $days); ?>
            </p>
        </div>

        <div class="stat-card"
            style="background: #fff; border-left: 4px solid #ffb900; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <h3 style="margin: 0 0 10px 0; color: #666; font-size: 14px;"><?php _e('Unique URLs', 'ai-seo-pro'); ?>
            </h3>
            <div style="font-size: 36px; font-weight: bold; color: #ffb900;">
                <?php echo number_format($stats['unique_urls']); ?>
            </div>
            <p style="margin: 5px 0 0 0; color: #999; font-size: 12px;">
                <?php _e('Different broken URLs', 'ai-seo-pro'); ?>
            </p>
        </div>
    </div>

    <!-- Top 404s -->
    <?php if (!empty($stats['top_404s'])): ?>
        <div class="top-404s"
            style="background: #fff; padding: 20px; margin: 20px 0; border: 1px solid #ddd; border-radius: 4px;">
            <h2 style="margin-top: 0;"><?php _e('Top 10 Most Hit 404 URLs', 'ai-seo-pro'); ?></h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('URL', 'ai-seo-pro'); ?></th>
                        <th><?php _e('Hits', 'ai-seo-pro'); ?></th>
                        <th><?php _e('Action', 'ai-seo-pro'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stats['top_404s'] as $top): ?>
                        <tr>
                            <td><strong><?php echo esc_html($top->url); ?></strong></td>
                            <td>
                                <span
                                    style="background: #dc3232; color: #fff; padding: 3px 8px; border-radius: 3px; font-size: 12px; font-weight: 600;">
                                    <?php echo number_format($top->total_hits); ?>
                                </span>
                            </td>
                            <td>
                                <button class="button button-small create-redirect-btn"
                                    data-source="<?php echo esc_attr($top->url); ?>">
                                    <?php _e('Create Redirect', 'ai-seo-pro'); ?>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- Filters -->
    <div class="filters"
        style="background: #fff; padding: 15px; margin: 20px 0; border: 1px solid #ddd; border-radius: 4px;">
        <form method="get" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            <input type="hidden" name="page" value="<?php echo $this->plugin_name; ?>-404-monitor">

            <label>
                <?php _e('Time Period:', 'ai-seo-pro'); ?>
                <select name="days" onchange="this.form.submit()">
                    <option value="7" <?php selected($days, 7); ?>><?php _e('Last 7 days', 'ai-seo-pro'); ?>
                    </option>
                    <option value="30" <?php selected($days, 30); ?>><?php _e('Last 30 days', 'ai-seo-pro'); ?>
                    </option>
                    <option value="90" <?php selected($days, 90); ?>><?php _e('Last 90 days', 'ai-seo-pro'); ?>
                    </option>
                    <option value="0" <?php selected($days, 0); ?>><?php _e('All time', 'ai-seo-pro'); ?></option>
                </select>
            </label>

            <div style="flex: 1;">
                <input type="search" name="s" value="<?php echo esc_attr($search); ?>"
                    placeholder="<?php _e('Search 404 URLs...', 'ai-seo-pro'); ?>"
                    style="width: 100%; max-width: 300px;">
                <button type="submit" class="button"><?php _e('Search', 'ai-seo-pro'); ?></button>
            </div>

            <div style="margin-left: auto;">
                <a href="<?php echo wp_nonce_url('?page=' . $this->plugin_name . '-404-monitor&action=export_404&days=' . $days, 'export_404_logs'); ?>"
                    class="button">
                    <?php _e('Export CSV', 'ai-seo-pro'); ?>
                </a>
                <a href="<?php echo wp_nonce_url('?page=' . $this->plugin_name . '-404-monitor&action=clear_all', 'clear_all_404'); ?>"
                    class="button"
                    onclick="return confirm('<?php _e('Are you sure you want to delete all 404 logs? This cannot be undone.', 'ai-seo-pro'); ?>');">
                    <?php _e('Clear All', 'ai-seo-pro'); ?>
                </a>
            </div>
        </form>
    </div>

    <!-- 404 Logs Table -->
    <?php if (empty($results['logs'])): ?>
        <div class="notice notice-info">
            <p><?php _e('No 404 errors found. Great job! 🎉', 'ai-seo-pro'); ?></p>
        </div>
    <?php else: ?>
        <div style="background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 4px;">
            <h2><?php _e('404 Error Logs', 'ai-seo-pro'); ?></h2>

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('URL', 'ai-seo-pro'); ?></th>
                        <th><?php _e('Hits', 'ai-seo-pro'); ?></th>
                        <th><?php _e('Referrer', 'ai-seo-pro'); ?></th>
                        <th><?php _e('Last Hit', 'ai-seo-pro'); ?></th>
                        <th><?php _e('Actions', 'ai-seo-pro'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results['logs'] as $log): ?>
                        <tr>
                            <td>
                                <strong><?php echo esc_html($log->url); ?></strong>
                                <?php if ($log->hits > 10): ?>
                                    <span class="high-priority"
                                        style="background: #dc3232; color: #fff; padding: 2px 6px; border-radius: 3px; font-size: 11px; margin-left: 5px;">
                                        <?php _e('HIGH PRIORITY', 'ai-seo-pro'); ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span style="font-weight: 600; color: #dc3232;">
                                    <?php echo number_format($log->hits); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($log->referrer): ?>
                                    <a href="<?php echo esc_url($log->referrer); ?>" target="_blank" style="font-size: 12px;">
                                        <?php echo esc_html(wp_trim_words($log->referrer, 6, '...')); ?>
                                    </a>
                                <?php else: ?>
                                    <span style="color: #999;">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span style="font-size: 12px; color: #666;">
                                    <?php echo human_time_diff(strtotime($log->updated_at), current_time('timestamp')); ?>
                                    <?php _e('ago', 'ai-seo-pro'); ?>
                                </span>
                            </td>
                            <td>
                                <button class="button button-small create-redirect-btn"
                                    data-source="<?php echo esc_attr($log->url); ?>" data-log-id="<?php echo $log->id; ?>">
                                    <?php _e('Create Redirect', 'ai-seo-pro'); ?>
                                </button>
                                <a href="<?php echo wp_nonce_url('?page=' . $this->plugin_name . '-404-monitor&action=delete&id=' . $log->id, 'delete_404_' . $log->id); ?>"
                                    class="button button-small">
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
        </div>
    <?php endif; ?>
</div>

<!-- Create Redirect Modal -->
<div id="create-redirect-modal" style="display: none;">
    <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.7); z-index: 100000;">
    </div>
    <div
        style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #fff; padding: 30px; border-radius: 4px; z-index: 100001; max-width: 600px; width: 90%; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
        <h2 style="margin-top: 0;"><?php _e('Create Redirect', 'ai-seo-pro'); ?></h2>

        <form method="post" id="create-redirect-form">
            <?php wp_nonce_field('create_redirect_404', 'ai_seo_404_nonce'); ?>
            <input type="hidden" name="action" value="create_redirect_from_404">
            <input type="hidden" name="log_id" id="redirect-log-id">

            <table class="form-table">
                <tr>
                    <th>
                        <label for="redirect-source"><?php _e('From (404 URL):', 'ai-seo-pro'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="redirect-source" name="source_url" class="large-text" readonly
                            style="background: #f5f5f5;">
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="redirect-target"><?php _e('To (Target URL):', 'ai-seo-pro'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="redirect-target" name="target_url" class="large-text" required
                            placeholder="/new-page">
                        <p class="description"><?php _e('Enter the URL to redirect to', 'ai-seo-pro'); ?></p>
                    </td>
                </tr>
            </table>

            <p>
                <button type="submit"
                    class="button button-primary"><?php _e('Create Redirect', 'ai-seo-pro'); ?></button>
                <button type="button" class="button"
                    id="cancel-redirect"><?php _e('Cancel', 'ai-seo-pro'); ?></button>
            </p>
        </form>
    </div>
</div>

<script>
    jQuery(document).ready(function ($) {
        // Create redirect button
        $('.create-redirect-btn').on('click', function () {
            var sourceUrl = $(this).data('source');
            var logId = $(this).data('log-id') || '';

            $('#redirect-source').val(sourceUrl);
            $('#redirect-log-id').val(logId);
            $('#redirect-target').val('').focus();
            $('#create-redirect-modal').fadeIn(200);
        });

        // Cancel redirect
        $('#cancel-redirect, #create-redirect-modal > div:first-child').on('click', function () {
            $('#create-redirect-modal').fadeOut(200);
        });

        // Prevent modal close on form click
        $('#create-redirect-modal > div:last-child').on('click', function (e) {
            e.stopPropagation();
        });
    });
</script>