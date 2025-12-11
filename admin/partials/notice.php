<?php
/**
 * Admin notice partial.
 *
 * @package    AI_SEO_Pro
 * @subpackage AI_SEO_Pro/admin/partials
 * 
 * @var string $type    Notice type (success, error, warning, info)
 * @var string $message Notice message
 * @var bool   $dismissible Whether notice is dismissible
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$type = isset( $type ) ? $type : 'info';
$message = isset( $message ) ? $message : '';
$dismissible = isset( $dismissible ) ? $dismissible : true;

$classes = array( 'notice', 'notice-' . $type );
if ( $dismissible ) {
	$classes[] = 'is-dismissible';
}
?>

<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
	<p><?php echo wp_kses_post( $message ); ?></p>
</div>