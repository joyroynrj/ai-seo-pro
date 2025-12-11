<?php
/**
 * Breadcrumbs output.
 *
 * @package    AI_SEO_Pro
 * @subpackage AI_SEO_Pro/public/partials
 * 
 * @var WP_Post $post Post object
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_front_page() ) {
	return;
}

$items = array();

// Home
$items[] = array(
	'title' => __( 'Home', 'ai-seo-pro' ),
	'url'   => home_url(),
);

// Category for posts
if ( $post->post_type === 'post' ) {
	$categories = get_the_category( $post->ID );
	if ( ! empty( $categories ) ) {
		$category = $categories[0];
		$items[] = array(
			'title' => $category->name,
			'url'   => get_category_link( $category->term_id ),
		);
	}
}

// Parent pages
if ( $post->post_parent ) {
	$parent_id = $post->post_parent;
	$breadcrumbs = array();
	
	while ( $parent_id ) {
		$page = get_post( $parent_id );
		$breadcrumbs[] = array(
			'title' => get_the_title( $page ),
			'url'   => get_permalink( $page ),
		);
		$parent_id = $page->post_parent;
	}
	
	$items = array_merge( $items, array_reverse( $breadcrumbs ) );
}

// Current page
$items[] = array(
	'title' => get_the_title( $post->ID ),
	'url'   => '',
);
?>

<nav class="ai-seo-breadcrumbs" aria-label="Breadcrumb">
	<?php foreach ( $items as $index => $item ) : ?>
		<?php if ( $index > 0 ) : ?>
			<span class="separator">/</span>
		<?php endif; ?>
		
		<?php if ( ! empty( $item['url'] ) ) : ?>
			<a href="<?php echo esc_url( $item['url'] ); ?>">
				<?php echo esc_html( $item['title'] ); ?>
			</a>
		<?php else : ?>
			<span class="current"><?php echo esc_html( $item['title'] ); ?></span>
		<?php endif; ?>
	<?php endforeach; ?>
</nav>