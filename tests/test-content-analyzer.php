<?php
/**
 * Content Analyzer Tests
 *
 * @package    AI_SEO_Pro
 * @subpackage AI_SEO_Pro/tests
 */

class Test_AI_SEO_Pro_Content_Analyzer extends WP_UnitTestCase {

	private $analyzer;

	/**
	 * Setup test.
	 */
	public function setUp() {
		parent::setUp();
		$this->analyzer = new AI_SEO_Pro_Content_Analyzer();
	}

	/**
	 * Test word count.
	 */
	public function test_count_words() {
		$content = '<p>This is a test content with ten words here.</p>';
		$reflection = new ReflectionClass( $this->analyzer );
		$method = $reflection->getMethod( 'count_words' );
		$method->setAccessible( true );
		
		$count = $method->invoke( $this->analyzer, $content );
		$this->assertEquals( 10, $count );
	}

	/**
	 * Test keyword density calculation.
	 */
	public function test_calculate_keyword_density() {
		$content = 'test word test word test word other words here';
		$keyword = 'test';
		
		$reflection = new ReflectionClass( $this->analyzer );
		$method = $reflection->getMethod( 'calculate_keyword_density' );
		$method->setAccessible( true );
		
		$density = $method->invoke( $this->analyzer, $content, $keyword );
		
		// 3 occurrences of "test" in 9 words = 33.33%
		$this->assertGreaterThan( 30, $density );
		$this->assertLessThan( 35, $density );
	}

	/**
	 * Test analyze method.
	 */
	public function test_analyze() {
		$content = '<h1>Test Heading</h1><p>This is test content with the keyword test multiple times.</p>';
		$keyword = 'test';
		
		$analysis = $this->analyzer->analyze( $content, $keyword );
		
		$this->assertIsArray( $analysis );
		$this->assertArrayHasKey( 'word_count', $analysis );
		$this->assertArrayHasKey( 'keyword_density', $analysis );
		$this->assertArrayHasKey( 'keyword_in_headings', $analysis );
		$this->assertArrayHasKey( 'recommendations', $analysis );
		
		$this->assertGreaterThan( 0, $analysis['word_count'] );
		$this->assertGreaterThan( 0, $analysis['keyword_in_headings'] );
	}

	/**
	 * Test image alt tag checking.
	 */
	public function test_check_image_alt_tags() {
		$content = '<img src="test1.jpg" alt="Test Image"><img src="test2.jpg">';
		
		$reflection = new ReflectionClass( $this->analyzer );
		$method = $reflection->getMethod( 'check_image_alt_tags' );
		$method->setAccessible( true );
		
		$result = $method->invoke( $this->analyzer, $content );
		
		$this->assertEquals( 2, $result['total'] );
		$this->assertEquals( 1, $result['with_alt'] );
		$this->assertEquals( 1, $result['without_alt'] );
	}
}