<?php
/**
 * SEO Score Tests
 *
 * @package    AI_SEO_Pro
 * @subpackage AI_SEO_Pro/tests
 */

class Test_AI_SEO_Pro_SEO_Score extends WP_UnitTestCase {

	private $scorer;
	private $post_id;

	/**
	 * Setup test.
	 */
	public function setUp() {
		parent::setUp();
		
		$this->scorer = new AI_SEO_Pro_SEO_Score();
		
		// Create test post with content
		$this->post_id = $this->factory->post->create( array(
			'post_title'   => 'SEO Test Post',
			'post_content' => '<h1>Main Heading</h1><h2>Subheading</h2><p>This is test content with sufficient words to pass the content length test. It contains multiple sentences and paragraphs to ensure proper SEO scoring.</p><p>Another paragraph with <strong>important</strong> information and <a href="http://example.com">external link</a>.</p>',
		) );
	}

	/**
	 * Test calculate score.
	 */
	public function test_calculate_score() {
		// Set some meta data
		update_post_meta( $this->post_id, '_ai_seo_title', 'This is a properly sized SEO title that is optimal' );
		update_post_meta( $this->post_id, '_ai_seo_description', 'This is a properly sized meta description that contains enough characters to be considered optimal for search engines and users alike.' );
		update_post_meta( $this->post_id, '_ai_seo_focus_keyword', 'test' );
		
		$score = $this->scorer->calculate_score( $this->post_id );
		
		$this->assertIsInt( $score );
		$this->assertGreaterThanOrEqual( 0, $score );
		$this->assertLessThanOrEqual( 100, $score );
	}

	/**
	 * Test get score status.
	 */
	public function test_get_score_status() {
		// Test excellent score
		$status = $this->scorer->get_score_status( 90 );
		$this->assertEquals( 'good', $status['status'] );
		$this->assertEquals( 'Excellent', $status['label'] );
		
		// Test poor score
		$status = $this->scorer->get_score_status( 30 );
		$this->assertEquals( 'poor', $status['status'] );
		$this->assertEquals( 'Poor', $status['label'] );
	}

	/**
	 * Test score without meta data.
	 */
	public function test_score_without_meta() {
		$score = $this->scorer->calculate_score( $this->post_id );
		
		// Without meta data, score should be low but not zero
		$this->assertGreaterThan( 0, $score );
		$this->assertLessThan( 50, $score );
	}

	/**
	 * Test score with complete meta data.
	 */
	public function test_score_with_complete_meta() {
		// Set optimal meta data
		update_post_meta( $this->post_id, '_ai_seo_title', 'Perfect SEO Title Between 50 and 60 Characters Long' );
		update_post_meta( $this->post_id, '_ai_seo_description', 'Perfect meta description that is between 150 and 160 characters long and provides valuable information to users about the content of the page.' );
		update_post_meta( $this->post_id, '_ai_seo_focus_keyword', 'seo' );
		
		// Update post content to include focus keyword
		wp_update_post( array(
			'ID'           => $this->post_id,
			'post_content' => '<h1>SEO Test Heading</h1><h2>Another SEO Subheading</h2><p>This is SEO test content with the keyword SEO appearing multiple times. It contains sufficient words and proper structure for optimal SEO scoring.</p><p>Another paragraph with more SEO content and <a href="' . home_url() . '">internal link</a>.</p><img src="test.jpg" alt="SEO Test Image">',
		) );
		
		$score = $this->scorer->calculate_score( $this->post_id );
		
		// With complete optimal data, score should be high
		$this->assertGreaterThan( 70, $score );
	}
}