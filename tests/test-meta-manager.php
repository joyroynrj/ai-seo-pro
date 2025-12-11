<?php
/**
 * Meta Manager Tests
 *
 * @package    AI_SEO_Pro
 * @subpackage AI_SEO_Pro/tests
 */

class Test_AI_SEO_Pro_Meta_Manager extends WP_UnitTestCase {

	private $meta_manager;
	private $post_id;

	/**
	 * Setup test.
	 */
	public function setUp() {
		parent::setUp();
		
		$this->meta_manager = new AI_SEO_Pro_Meta_Manager();
		
		// Create test post
		$this->post_id = $this->factory->post->create( array(
			'post_title'   => 'Test Post',
			'post_content' => 'Test content',
		) );
	}

	/**
	 * Test save and get post meta.
	 */
	public function test_save_and_get_post_meta() {
		$data = array(
			'title'       => 'Test Meta Title',
			'description' => 'Test meta description',
			'keywords'    => 'test, keywords',
		);
		
		$result = $this->meta_manager->save_post_meta( $this->post_id, $data );
		$this->assertTrue( $result );
		
		$saved_data = $this->meta_manager->get_post_meta( $this->post_id );
		
		$this->assertEquals( $data['title'], $saved_data['title'] );
		$this->assertEquals( $data['description'], $saved_data['description'] );
		$this->assertEquals( $data['keywords'], $saved_data['keywords'] );
	}

	/**
	 * Test delete post meta.
	 */
	public function test_delete_post_meta() {
		$data = array(
			'title'       => 'Test Meta Title',
			'description' => 'Test meta description',
		);
		
		$this->meta_manager->save_post_meta( $this->post_id, $data );
		$this->meta_manager->delete_post_meta( $this->post_id );
		
		$saved_data = $this->meta_manager->get_post_meta( $this->post_id );
		
		$this->assertEmpty( $saved_data['title'] );
		$this->assertEmpty( $saved_data['description'] );
	}

	/**
	 * Test get title with fallback.
	 */
	public function test_get_title_with_fallback() {
		// Without custom title
		$title = $this->meta_manager->get_title( $this->post_id );
		$this->assertStringContainsString( 'Test Post', $title );
		
		// With custom title
		update_post_meta( $this->post_id, '_ai_seo_title', 'Custom Title' );
		$title = $this->meta_manager->get_title( $this->post_id );
		$this->assertEquals( 'Custom Title', $title );
	}
}