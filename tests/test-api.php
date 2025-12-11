<?php
/**
 * API Tests
 *
 * @package    AI_SEO_Pro
 * @subpackage AI_SEO_Pro/tests
 */

class Test_AI_SEO_Pro_API extends WP_UnitTestCase
{

	/**
	 * Test API factory creation.
	 */
	public function test_api_factory_create()
	{
		$api_key = 'test_key_12345';

		// Test Anthropic
		$api = AI_SEO_Pro_API_Factory::create('anthropic', $api_key);
		$this->assertInstanceOf('AI_SEO_Pro_Anthropic_API', $api);

		// Test Gemini
		$api = AI_SEO_Pro_API_Factory::create('gemini', $api_key);
		$this->assertInstanceOf('AI_SEO_Pro_Gemini_API', $api);

		// Test invalid provider
		$api = AI_SEO_Pro_API_Factory::create('invalid', $api_key);
		$this->assertWPError($api);
	}

	/**
	 * Test API factory without key.
	 */
	public function test_api_factory_without_key()
	{
		$api = AI_SEO_Pro_API_Factory::create('gemini', '');
		$this->assertWPError($api);
	}

	/**
	 * Test get providers.
	 */
	public function test_get_providers()
	{
		$providers = AI_SEO_Pro_API_Factory::get_providers();

		$this->assertIsArray($providers);
		$this->assertArrayHasKey('anthropic', $providers);
		$this->assertArrayHasKey('gemini', $providers);
	}

	/**
	 * Test content preparation.
	 */
	public function test_content_preparation()
	{
		$api_key = 'test_key_12345';
		$api = AI_SEO_Pro_API_Factory::create('gemini', $api_key);

		$content = '<p>This is <strong>test</strong> content.</p>';
		$reflection = new ReflectionClass($api);
		$method = $reflection->getMethod('prepare_content');
		$method->setAccessible(true);

		$result = $method->invoke($api, $content);

		$this->assertStringNotContainsString('<p>', $result);
		$this->assertStringNotContainsString('<strong>', $result);
	}
}