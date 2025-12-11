<?php
/**
 * Anthropic (Claude) API implementation - FIXED
 *
 * @package    AI_SEO_Pro
 * @subpackage AI_SEO_Pro/includes/api
 * @author     Joy Roy
 */
class AI_SEO_Pro_Anthropic_API extends AI_SEO_Pro_API_Base {

	/**
	 * API endpoint
	 *
	 * @var string
	 */
	protected $endpoint = 'https://api.anthropic.com/v1/messages';

	/**
	 * Model to use - UPDATED to latest stable version
	 *
	 * @var string
	 */
	protected $model = 'claude-sonnet-4-20250514';

	/**
	 * Generate meta tags using Anthropic Claude
	 *
	 * @param string $title         Post title
	 * @param string $content       Post content
	 * @param string $focus_keyword Focus keyword
	 * @return array|WP_Error
	 */
	public function generate_meta_tags( $title, $content, $focus_keyword = '' ) {
		
		if ( empty( $this->api_key ) ) {
			return new WP_Error( 'no_api_key', __( 'Anthropic API key is not configured', 'ai-seo-pro' ) );
		}

		// Prepare content
		$clean_content = $this->prepare_content( $content );
		
		if ( empty( $clean_content ) ) {
			return new WP_Error( 'no_content', __( 'Content is empty', 'ai-seo-pro' ) );
		}

		// Build prompt
		$prompt = $this->build_prompt( $title, $clean_content, $focus_keyword );

		// Make API request with proper headers
		$headers = array(
			'Content-Type'      => 'application/json',
			'x-api-key'         => $this->api_key,
			'anthropic-version' => '2023-06-01',
		);

		$body = array(
			'model'      => $this->model,
			'max_tokens' => 1024,
			'messages'   => array(
				array(
					'role'    => 'user',
					'content' => $prompt,
				),
			),
			
		);

		// Log request for debugging
		error_log( 'Anthropic API Request Body: ' . wp_json_encode( $body ) );

		// Use custom request method with better error handling
		$response = wp_remote_post( $this->endpoint, array(
			'timeout' => $this->timeout,
			'headers' => $headers,
			'body'    => wp_json_encode( $body ),
		) );

		// Check for WordPress HTTP errors
		if ( is_wp_error( $response ) ) {
			error_log( 'Anthropic API WordPress Error: ' . $response->get_error_message() );
			return $response;
		}

		$status_code = wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );

		// Log response for debugging
		error_log( 'Anthropic API Status Code: ' . $status_code );
		error_log( 'Anthropic API Response: ' . $response_body );

		// Handle non-200 responses
		if ( $status_code !== 200 ) {
			$error_data = json_decode( $response_body, true );
			
			// Parse Anthropic error format
			if ( isset( $error_data['error'] ) ) {
				$error = $error_data['error'];
				
				// Handle different error types
				if ( isset( $error['type'] ) && isset( $error['message'] ) ) {
					$error_message = sprintf(
						'Anthropic API Error (%s): %s',
						$error['type'],
						$error['message']
					);
				} else {
					$error_message = isset( $error['message'] ) ? $error['message'] : 'Unknown API error';
				}
			} else {
				$error_message = sprintf( 
					__( 'Anthropic API request failed with status code: %d', 'ai-seo-pro' ), 
					$status_code 
				);
			}
			
			error_log( 'Anthropic API Error Message: ' . $error_message );
			return new WP_Error( 'api_error', $error_message );
		}

		// Parse response
		$data = json_decode( $response_body, true );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			error_log( 'Anthropic API JSON Parse Error: ' . json_last_error_msg() );
			return new WP_Error( 'json_error', __( 'Failed to parse API response', 'ai-seo-pro' ) );
		}

		// Extract content from response - Anthropic format
		if ( ! isset( $data['content'] ) || ! is_array( $data['content'] ) || empty( $data['content'] ) ) {
			error_log( 'Anthropic API Invalid Response Structure: ' . print_r( $data, true ) );
			return new WP_Error( 'invalid_response', __( 'Invalid response structure from Anthropic', 'ai-seo-pro' ) );
		}

		// Get text from first content block
		$content_text = '';
		foreach ( $data['content'] as $content_block ) {
			if ( isset( $content_block['type'] ) && $content_block['type'] === 'text' && isset( $content_block['text'] ) ) {
				$content_text = $content_block['text'];
				break;
			}
		}

		if ( empty( $content_text ) ) {
			error_log( 'Anthropic API No Text in Response: ' . print_r( $data, true ) );
			return new WP_Error( 'no_content', __( 'No text content in API response', 'ai-seo-pro' ) );
		}

		error_log( 'Anthropic API Content Text: ' . $content_text );

		// Parse JSON from the text content
		return $this->parse_json_response( $content_text );
	}
}