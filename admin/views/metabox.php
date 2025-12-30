<?php
/**
 * Meta box view with tabs - SEO & Readability
 *
 * @package    AI_SEO_Pro
 * @subpackage AI_SEO_Pro/admin/views
 *
 * @var WP_Post $post Post object
 * @var array $meta_data Meta data
 * @var int $seo_score SEO score
 * @var array $content_analysis Content analysis
 * @var array $seo_analysis SEO analysis results
 * @var array $readability_analysis Readability analysis results
 */

if (!defined('ABSPATH')) {
	exit;
}

$score_status = (new AI_SEO_Pro_SEO_Score())->get_score_status($seo_score ? $seo_score : 0);

// Count problems and good results.
$seo_problems = isset($seo_analysis['problems']) ? count($seo_analysis['problems']) : 0;
$seo_good = isset($seo_analysis['good']) ? count($seo_analysis['good']) : 0;
$readability_problems = isset($readability_analysis['problems']) ? count($readability_analysis['problems']) : 0;
$readability_good = isset($readability_analysis['good']) ? count($readability_analysis['good']) : 0;

// Get exclude from sitemap value
$exclude_sitemap = get_post_meta($post->ID, '_ai_seo_exclude_sitemap', true);
?>

<div class="ai-seo-pro-metabox">
	<!-- Tab Navigation -->
	<div class="ai-seo-tabs-nav">
		<button type="button" class="ai-seo-tab-btn active" data-tab="seo">
			<span class="dashicons dashicons-search"></span>
			<?php esc_html_e('SEO', 'ai-seo-pro'); ?>
			<?php if ($seo_problems > 0): ?>
				<span class="tab-badge badge-problems"><?php echo esc_html($seo_problems); ?></span>
			<?php endif; ?>
		</button>
		<button type="button" class="ai-seo-tab-btn" data-tab="readability">
			<span class="dashicons dashicons-editor-paragraph"></span>
			<?php esc_html_e('Readability', 'ai-seo-pro'); ?>
			<?php if ($readability_problems > 0): ?>
				<span class="tab-badge badge-problems"><?php echo esc_html($readability_problems); ?></span>
			<?php endif; ?>
		</button>
	</div>

	<!-- SEO Tab Content -->
	<div class="ai-seo-tab-content active" data-tab="seo">
		<!-- SEO Score -->
		<div class="seo-score-section">
			<div class="score-display">
				<div class="score-circle" style="border-color: <?php echo esc_attr($score_status['color']); ?>">
					<span class="score-number"><?php echo esc_html($seo_score ? $seo_score : 0); ?></span>
					<span class="score-label">/100</span>
				</div>
				<div class="score-info">
					<h4><?php esc_html_e('SEO Score', 'ai-seo-pro'); ?></h4>
					<p class="score-status <?php echo esc_attr($score_status['status']); ?>"
						style="color: <?php echo esc_attr($score_status['color']); ?>">
						<?php echo esc_html($score_status['label']); ?>
					</p>
				</div>
			</div>
		</div>

		<!-- AI Generation Options -->
		<div class="ai-options-section">
			<label class="toggle-option">
				<input type="checkbox" name="ai_seo_auto_generate" id="ai_seo_auto_generate" value="1" <?php checked($meta_data['auto_generate'], '1'); ?>>
				<span><?php esc_html_e('Auto-generate with AI on save', 'ai-seo-pro'); ?></span>
			</label>

			<!-- Generation Filter Checkboxes -->
			<div class="ai-generation-filters">
				<p class="filters-title">
					<?php esc_html_e('Select fields to generate:', 'ai-seo-pro'); ?>
				</p>
				<div class="filter-options">
					<label>
						<input type="checkbox" id="ai_generate_title" name="ai_generate_title" value="1" checked>
						<span><?php esc_html_e('Meta Title', 'ai-seo-pro'); ?></span>
					</label>

					<label>
						<input type="checkbox" id="ai_generate_description" name="ai_generate_description" value="1"
							checked>
						<span><?php esc_html_e('Meta Description', 'ai-seo-pro'); ?></span>
					</label>

					<label>
						<input type="checkbox" id="ai_generate_keywords" name="ai_generate_keywords" value="1" checked>
						<span><?php esc_html_e('Meta Keywords', 'ai-seo-pro'); ?></span>
					</label>
				</div>
			</div>

			<button type="button" id="ai_seo_generate_now" class="button button-primary">
				<span class="dashicons dashicons-superhero"></span>
				<?php esc_html_e('Generate Now with AI', 'ai-seo-pro'); ?>
			</button>

			<span class="spinner"></span>
		</div>

		<!-- Focus Keyword -->
		<div class="meta-field">
			<label for="ai_seo_focus_keyword">
				<strong><?php esc_html_e('Focus Keyphrase', 'ai-seo-pro'); ?></strong>
			</label>
			<input type="text" id="ai_seo_focus_keyword" name="ai_seo_focus_keyword"
				value="<?php echo esc_attr($meta_data['focus_keyword']); ?>" class="widefat"
				placeholder="<?php esc_attr_e('Enter your focus keyphrase', 'ai-seo-pro'); ?>">
			<p class="description">
				<?php esc_html_e('The main keyphrase you want this content to rank for', 'ai-seo-pro'); ?>
			</p>
		</div>

		<!-- Meta Title -->
		<div class="meta-field">
			<label for="ai_seo_title">
				<strong><?php esc_html_e('SEO Title', 'ai-seo-pro'); ?></strong>
				<span class="character-counter" data-field="ai_seo_title" data-max="60">
					0 / 60
				</span>
			</label>
			<input type="text" id="ai_seo_title" name="ai_seo_title"
				value="<?php echo esc_attr($meta_data['title']); ?>" class="widefat" maxlength="70"
				placeholder="<?php esc_attr_e('Enter SEO title or generate with AI', 'ai-seo-pro'); ?>">
			<p class="description">
				<?php esc_html_e('Recommended: 50-60 characters', 'ai-seo-pro'); ?>
			</p>
		</div>

		<!-- Slug Preview -->
		<div class="meta-field">
			<label>
				<strong><?php esc_html_e('Slug', 'ai-seo-pro'); ?></strong>
			</label>
			<div class="slug-preview">
				<code><?php echo esc_html($post->post_name); ?></code>
			</div>
		</div>

		<!-- Meta Description -->
		<div class="meta-field">
			<label for="ai_seo_description">
				<strong><?php esc_html_e('Meta Description', 'ai-seo-pro'); ?></strong>
				<span class="character-counter" data-field="ai_seo_description" data-max="160">
					0 / 160
				</span>
			</label>
			<textarea id="ai_seo_description" name="ai_seo_description" rows="3" class="widefat" maxlength="170"
				placeholder="<?php esc_attr_e('Enter meta description or generate with AI', 'ai-seo-pro'); ?>"><?php echo esc_textarea($meta_data['description']); ?></textarea>
			<p class="description">
				<?php esc_html_e('Recommended: 150-160 characters', 'ai-seo-pro'); ?>
			</p>
		</div>

		<!-- Keywords -->
		<div class="meta-field">
			<label for="ai_seo_keywords">
				<strong><?php esc_html_e('Meta Keywords', 'ai-seo-pro'); ?></strong>
			</label>
			<input type="text" id="ai_seo_keywords" name="ai_seo_keywords"
				value="<?php echo esc_attr($meta_data['keywords']); ?>" class="widefat"
				placeholder="<?php esc_attr_e('keyword1, keyword2, keyword3', 'ai-seo-pro'); ?>">
			<p class="description">
				<?php esc_html_e('Comma-separated keywords (optional)', 'ai-seo-pro'); ?>
			</p>
		</div>

		<!-- Search Preview -->
		<div class="search-preview-section">
			<h4><?php esc_html_e('Search Engine Preview', 'ai-seo-pro'); ?></h4>
			<div class="search-preview">
				<div class="preview-title">
					<?php echo esc_html($meta_data['title'] ? $meta_data['title'] : get_the_title($post->ID)); ?>
				</div>
				<div class="preview-url">
					<?php echo esc_url(get_permalink($post->ID)); ?>
				</div>
				<div class="preview-description">
					<?php echo esc_html($meta_data['description'] ? $meta_data['description'] : wp_trim_words($post->post_excerpt, 20)); ?>
				</div>
			</div>
		</div>

		<!-- SEO Analysis Accordion -->
		<div class="analysis-accordion">
			<button type="button" class="accordion-toggle" data-accordion="seo-analysis">
				<span class="toggle-icon dashicons dashicons-arrow-down-alt2"></span>
				<?php esc_html_e('SEO Analysis', 'ai-seo-pro'); ?>
				<span class="analysis-summary">
					<?php if ($seo_problems > 0): ?>
						<span class="summary-problems">
							<?php
							printf(
								/* translators: %d: number of problems */
								esc_html(_n('%d problem', '%d problems', $seo_problems, 'ai-seo-pro')),
								$seo_problems
							);
							?>
						</span>
					<?php endif; ?>
					<?php if ($seo_good > 0): ?>
						<span class="summary-good">
							<?php
							printf(
								/* translators: %d: number of good results */
								esc_html(_n('%d good result', '%d good results', $seo_good, 'ai-seo-pro')),
								$seo_good
							);
							?>
						</span>
					<?php endif; ?>
				</span>
			</button>
			<div class="accordion-content" id="seo-analysis" style="display: none;">
				<!-- Problems -->
				<?php if (!empty($seo_analysis['problems'])): ?>
					<div class="analysis-group problems-group">
						<h5 class="group-title">
							<span class="dashicons dashicons-warning"></span>
							<?php
							printf(
								/* translators: %d: number of problems */
								esc_html__('Problems (%d)', 'ai-seo-pro'),
								count($seo_analysis['problems'])
							);
							?>
						</h5>
						<ul class="analysis-list">
							<?php foreach ($seo_analysis['problems'] as $item): ?>
								<li class="analysis-item problem">
									<span class="item-indicator"></span>
									<span class="item-title"><?php echo esc_html($item['title']); ?>:</span>
									<span class="item-message"><?php echo esc_html($item['message']); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>

				<!-- Good Results -->
				<?php if (!empty($seo_analysis['good'])): ?>
					<div class="analysis-group good-group">
						<h5 class="group-title">
							<span class="dashicons dashicons-yes-alt"></span>
							<?php
							printf(
								/* translators: %d: number of good results */
								esc_html__('Good results (%d)', 'ai-seo-pro'),
								count($seo_analysis['good'])
							);
							?>
						</h5>
						<ul class="analysis-list">
							<?php foreach ($seo_analysis['good'] as $item): ?>
								<li class="analysis-item good">
									<span class="item-indicator"></span>
									<span class="item-title"><?php echo esc_html($item['title']); ?>:</span>
									<span class="item-message"><?php echo esc_html($item['message']); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>

				<?php if (empty($seo_analysis['problems']) && empty($seo_analysis['good'])): ?>
					<p class="no-analysis">
						<?php esc_html_e('Enter a focus keyphrase and save the post to see SEO analysis.', 'ai-seo-pro'); ?>
					</p>
				<?php endif; ?>
			</div>
		</div>

		<!-- Advanced Options Accordion -->
		<div class="analysis-accordion">
			<button type="button" class="accordion-toggle" data-accordion="advanced-options">
				<span class="toggle-icon dashicons dashicons-arrow-down-alt2"></span>
				<?php esc_html_e('Advanced Options', 'ai-seo-pro'); ?>
				<span class="analysis-summary">
					<span
						class="summary-info"><?php esc_html_e('Canonical, Robots, Social Tags', 'ai-seo-pro'); ?></span>
				</span>
			</button>
			<div class="accordion-content" id="advanced-options" style="display: none;">
				<div class="advanced-options-inner">
					<!-- Sitemap Settings -->
					<div class="meta-field sitemap-field">
						<label class="toggle-option exclude-sitemap-toggle">
							<input type="checkbox" name="ai_seo_exclude_sitemap" id="ai_seo_exclude_sitemap" value="1"
								<?php checked($exclude_sitemap, '1'); ?>>
							<span>
								<strong><?php esc_html_e('Exclude from XML Sitemap', 'ai-seo-pro'); ?></strong>
							</span>
						</label>
						<p class="description" style="margin-left: 24px;">
							<?php esc_html_e('When enabled, this page will not appear in the XML sitemap.', 'ai-seo-pro'); ?>
						</p>
					</div>

					<hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
					<!-- Canonical URL -->
					<div class="meta-field">
						<label for="ai_seo_canonical">
							<strong><?php esc_html_e('Canonical URL', 'ai-seo-pro'); ?></strong>
						</label>
						<input type="url" id="ai_seo_canonical" name="ai_seo_canonical"
							value="<?php echo esc_url($meta_data['canonical']); ?>" class="widefat"
							placeholder="<?php echo esc_url(get_permalink($post->ID)); ?>">
						<p class="description">
							<?php esc_html_e('Override the default canonical URL for this page', 'ai-seo-pro'); ?>
						</p>
					</div>

					<!-- Robots Meta -->
					<div class="meta-field">
						<label for="ai_seo_robots">
							<strong><?php esc_html_e('Robots Meta', 'ai-seo-pro'); ?></strong>
						</label>
						<select id="ai_seo_robots" name="ai_seo_robots" class="widefat">
							<option value=""><?php esc_html_e('Default (index, follow)', 'ai-seo-pro'); ?></option>
							<option value="noindex" <?php selected($meta_data['robots'], 'noindex'); ?>>
								<?php esc_html_e('No Index', 'ai-seo-pro'); ?>
							</option>
							<option value="nofollow" <?php selected($meta_data['robots'], 'nofollow'); ?>>
								<?php esc_html_e('No Follow', 'ai-seo-pro'); ?>
							</option>
							<option value="noindex,nofollow" <?php selected($meta_data['robots'], 'noindex,nofollow'); ?>>
								<?php esc_html_e('No Index, No Follow', 'ai-seo-pro'); ?>
							</option>
						</select>
					</div>

					<!-- Open Graph Title -->
					<div class="meta-field">
						<label for="ai_seo_og_title">
							<strong><?php esc_html_e('Open Graph Title', 'ai-seo-pro'); ?></strong>
						</label>
						<input type="text" id="ai_seo_og_title" name="ai_seo_og_title"
							value="<?php echo esc_attr($meta_data['og_title']); ?>" class="widefat" maxlength="60"
							placeholder="<?php esc_attr_e('Leave blank to use SEO title', 'ai-seo-pro'); ?>">
					</div>

					<!-- Open Graph Description -->
					<div class="meta-field">
						<label for="ai_seo_og_description">
							<strong><?php esc_html_e('Open Graph Description', 'ai-seo-pro'); ?></strong>
						</label>
						<textarea id="ai_seo_og_description" name="ai_seo_og_description" rows="2" class="widefat"
							maxlength="160"
							placeholder="<?php esc_attr_e('Leave blank to use meta description', 'ai-seo-pro'); ?>"><?php echo esc_textarea($meta_data['og_description']); ?></textarea>
					</div>

					<!-- Twitter Title -->
					<div class="meta-field">
						<label for="ai_seo_twitter_title">
							<strong><?php esc_html_e('Twitter Title', 'ai-seo-pro'); ?></strong>
						</label>
						<input type="text" id="ai_seo_twitter_title" name="ai_seo_twitter_title"
							value="<?php echo esc_attr($meta_data['twitter_title']); ?>" class="widefat"
							maxlength="60"
							placeholder="<?php esc_attr_e('Leave blank to use SEO title', 'ai-seo-pro'); ?>">
					</div>

					<!-- Twitter Description -->
					<div class="meta-field">
						<label for="ai_seo_twitter_description">
							<strong><?php esc_html_e('Twitter Description', 'ai-seo-pro'); ?></strong>
						</label>
						<textarea id="ai_seo_twitter_description" name="ai_seo_twitter_description" rows="2"
							class="widefat" maxlength="160"
							placeholder="<?php esc_attr_e('Leave blank to use meta description', 'ai-seo-pro'); ?>"><?php echo esc_textarea($meta_data['twitter_description']); ?></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Readability Tab Content -->
	<div class="ai-seo-tab-content" data-tab="readability">
		<!-- Readability Score -->
		<div class="readability-score-section">
			<?php
			$flesch_score = isset($readability_analysis['flesch_score']) ? $readability_analysis['flesch_score'] : 0;
			$grade_level = isset($readability_analysis['grade_level']) ? $readability_analysis['grade_level'] : '';
			$readability_status = $this->get_readability_status($flesch_score);
			?>
			<div class="score-display">
				<div class="score-circle" style="border-color: <?php echo esc_attr($readability_status['color']); ?>">
					<span class="score-number"><?php echo esc_html(round($flesch_score)); ?></span>
					<span class="score-label">/100</span>
				</div>
				<div class="score-info">
					<h4><?php esc_html_e('Readability Score', 'ai-seo-pro'); ?></h4>
					<p class="score-status" style="color: <?php echo esc_attr($readability_status['color']); ?>">
						<?php echo esc_html($readability_status['label']); ?>
					</p>
					<?php if ($grade_level): ?>
						<p class="grade-level"><?php echo esc_html($grade_level); ?></p>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<!-- Readability Analysis -->
		<div class="analysis-accordion">
			<button type="button" class="accordion-toggle active" data-accordion="readability-analysis">
				<span class="toggle-icon dashicons dashicons-arrow-up-alt2"></span>
				<?php esc_html_e('Readability Analysis', 'ai-seo-pro'); ?>
				<span class="analysis-summary">
					<?php if ($readability_problems > 0): ?>
						<span class="summary-problems">
							<?php
							printf(
								/* translators: %d: number of problems */
								esc_html(_n('%d problem', '%d problems', $readability_problems, 'ai-seo-pro')),
								$readability_problems
							);
							?>
						</span>
					<?php endif; ?>
					<?php if ($readability_good > 0): ?>
						<span class="summary-good">
							<?php
							printf(
								/* translators: %d: number of good results */
								esc_html(_n('%d good result', '%d good results', $readability_good, 'ai-seo-pro')),
								$readability_good
							);
							?>
						</span>
					<?php endif; ?>
				</span>
			</button>
			<div class="accordion-content" id="readability-analysis">
				<!-- Problems -->
				<?php if (!empty($readability_analysis['problems'])): ?>
					<div class="analysis-group problems-group">
						<h5 class="group-title">
							<span class="dashicons dashicons-warning"></span>
							<?php
							printf(
								/* translators: %d: number of problems */
								esc_html__('Problems (%d)', 'ai-seo-pro'),
								count($readability_analysis['problems'])
							);
							?>
						</h5>
						<ul class="analysis-list">
							<?php foreach ($readability_analysis['problems'] as $item): ?>
								<li class="analysis-item problem">
									<span class="item-indicator"></span>
									<span class="item-title"><?php echo esc_html($item['title']); ?>:</span>
									<span class="item-message"><?php echo esc_html($item['message']); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>

				<!-- Good Results -->
				<?php if (!empty($readability_analysis['good'])): ?>
					<div class="analysis-group good-group">
						<h5 class="group-title">
							<span class="dashicons dashicons-yes-alt"></span>
							<?php
							printf(
								/* translators: %d: number of good results */
								esc_html__('Good results (%d)', 'ai-seo-pro'),
								count($readability_analysis['good'])
							);
							?>
						</h5>
						<ul class="analysis-list">
							<?php foreach ($readability_analysis['good'] as $item): ?>
								<li class="analysis-item good">
									<span class="item-indicator"></span>
									<span class="item-title"><?php echo esc_html($item['title']); ?>:</span>
									<span class="item-message"><?php echo esc_html($item['message']); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>

				<?php if (empty($readability_analysis['problems']) && empty($readability_analysis['good'])): ?>
					<p class="no-analysis"><?php esc_html_e('Add content to see readability analysis.', 'ai-seo-pro'); ?>
					</p>
				<?php endif; ?>
			</div>
		</div>

		<!-- Readability Tips -->
		<div class="readability-tips">
			<h4><?php esc_html_e('Readability Tips', 'ai-seo-pro'); ?></h4>
			<ul>
				<li><?php esc_html_e('Use short sentences (under 20 words)', 'ai-seo-pro'); ?></li>
				<li><?php esc_html_e('Break up long paragraphs', 'ai-seo-pro'); ?></li>
				<li><?php esc_html_e('Use subheadings to structure content', 'ai-seo-pro'); ?></li>
				<li><?php esc_html_e('Use transition words for better flow', 'ai-seo-pro'); ?></li>
				<li><?php esc_html_e('Avoid passive voice when possible', 'ai-seo-pro'); ?></li>
			</ul>
		</div>
	</div>
</div>