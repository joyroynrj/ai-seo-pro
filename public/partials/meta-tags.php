<?php
/**
 * Meta tags output.
 *
 * @package    AI_SEO_Pro
 * @subpackage AI_SEO_Pro/public/partials
 * 
 * @var WP_Post $post Post object
 * @var array $meta_data Meta data
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get meta values with fallbacks
$meta_title = ! empty( $meta_data['title'] ) 
	? $meta_data['title'] 
	: $this->meta_manager->get_title( $post->ID );

$meta_description = ! empty( $meta_data['description'] ) 
	? $meta_data['description'] 
	: $this->meta_manager->get_description( $post->ID );

$meta_keywords = $meta_data['keywords'];
$canonical_url = ! empty( $meta_data['canonical'] ) 
	? $meta_data['canonical'] 
	: get_permalink( $post->ID );

$robots = $meta_data['robots'];

// Open Graph
$og_title = ! empty( $meta_data['og_title'] ) 
	? $meta_data['og_title'] 
	: $meta_title;

$og_description = ! empty( $meta_data['og_description'] ) 
	? $meta_data['og_description'] 
	: $meta_description;

$og_image = has_post_thumbnail( $post->ID ) 
	? get_the_post_thumbnail_url( $post->ID, 'large' ) 
	: get_option( 'ai_seo_pro_default_og_image', '' );

// Twitter Card
$twitter_title = ! empty( $meta_data['twitter_title'] ) 
	? $meta_data['twitter_title'] 
	: $meta_title;

$twitter_description = ! empty( $meta_data['twitter_description'] ) 
	? $meta_data['twitter_description'] 
	: $meta_description;

$twitter_username = get_option( 'ai_seo_pro_twitter_username', '' );
?>

<!-- AI SEO Pro Meta Tags -->

<?php if ( ! empty( $meta_description ) ) : ?>
<meta name="description" content="<?php echo esc_attr( $meta_description ); ?>">
<?php endif; ?>

<?php if ( ! empty( $meta_keywords ) ) : ?>
<meta name="keywords" content="<?php echo esc_attr( $meta_keywords ); ?>">
<?php endif; ?>

<?php if ( ! empty( $robots ) ) : ?>
<meta name="robots" content="<?php echo esc_attr( $robots ); ?>">
<?php endif; ?>

<link rel="canonical" href="<?php echo esc_url( $canonical_url ); ?>">

<?php if ( get_option( 'ai_seo_pro_og_tags', true ) ) : ?>
<!-- Open Graph / Facebook -->
<meta property="og:type" content="<?php echo is_front_page() ? 'website' : 'article'; ?>">
<meta property="og:url" content="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">
<meta property="og:title" content="<?php echo esc_attr( $og_title ); ?>">
<meta property="og:description" content="<?php echo esc_attr( $og_description ); ?>">
<?php if ( ! empty( $og_image ) ) : ?>
<meta property="og:image" content="<?php echo esc_url( $og_image ); ?>">
<?php endif; ?>
<meta property="og:site_name" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
<meta property="og:locale" content="<?php echo esc_attr( get_locale() ); ?>">
<?php if ( ! is_front_page() ) : ?>
<meta property="article:published_time" content="<?php echo get_the_date( 'c', $post->ID ); ?>">
<meta property="article:modified_time" content="<?php echo get_the_modified_date( 'c', $post->ID ); ?>">
<?php endif; ?>
<?php endif; ?>

<?php if ( get_option( 'ai_seo_pro_twitter_cards', true ) ) : ?>
<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">
<meta name="twitter:title" content="<?php echo esc_attr( $twitter_title ); ?>">
<meta name="twitter:description" content="<?php echo esc_attr( $twitter_description ); ?>">
<?php if ( ! empty( $og_image ) ) : ?>
<meta name="twitter:image" content="<?php echo esc_url( $og_image ); ?>">
<?php endif; ?>
<?php if ( ! empty( $twitter_username ) ) : ?>
<meta name="twitter:site" content="<?php echo esc_attr( $twitter_username ); ?>">
<meta name="twitter:creator" content="<?php echo esc_attr( $twitter_username ); ?>">
<?php endif; ?>
<?php endif; ?>

<!-- /AI SEO Pro Meta Tags -->