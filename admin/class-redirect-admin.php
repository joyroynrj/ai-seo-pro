<?php
/**
 * Redirect Admin - Admin interface for redirects
 *
 * @package    AI_SEO_Pro
 * @subpackage AI_SEO_Pro/admin
 * @author     Joy Roy
 */
class AI_SEO_Pro_Redirect_Admin {

	/**
	 * Plugin name
	 *
	 * @var string
	 */
	private $plugin_name;

	/**
	 * Redirect manager
	 *
	 * @var AI_SEO_Pro_Redirect_Manager
	 */
	private $redirect_manager;

	/**
	 * 404 monitor
	 *
	 * @var AI_SEO_Pro_404_Monitor
	 */
	private $monitor_404;

	/**
	 * Constructor
	 *
	 * @param string $plugin_name The plugin name.
	 */
	public function __construct( $plugin_name ) {
		$this->plugin_name      = $plugin_name;
		$this->redirect_manager = new AI_SEO_Pro_Redirect_Manager();
		$this->monitor_404      = new AI_SEO_Pro_404_Monitor();

		// Handle actions early, before any output.
		add_action( 'admin_init', array( $this, 'handle_redirect_actions' ) );
		add_action( 'admin_init', array( $this, 'handle_404_actions' ) );
	}

	/**
	 * Add menu pages
	 */
	public function add_menu_pages() {
		// Redirects page.
		add_submenu_page(
			$this->plugin_name,
			__( 'Redirects', 'ai-seo-pro' ),
			__( 'Redirects', 'ai-seo-pro' ),
			'manage_options',
			$this->plugin_name . '-redirects',
			array( $this, 'display_redirects_page' )
		);

		// 404 Monitor page.
		add_submenu_page(
			$this->plugin_name,
			__( '404 Monitor', 'ai-seo-pro' ),
			__( '404 Monitor', 'ai-seo-pro' ),
			'manage_options',
			$this->plugin_name . '-404-monitor',
			array( $this, 'display_404_monitor_page' )
		);
	}

	/**
	 * Display redirects page
	 */
	public function display_redirects_page() {
		$action      = isset( $_GET['action'] ) ? sanitize_text_field( wp_unslash( $_GET['action'] ) ) : 'list';
		$redirect_id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

		?>
		<div class="wrap ai-seo-pro-redirects">
			<h1 class="wp-heading-inline">
				<?php esc_html_e( 'Redirects', 'ai-seo-pro' ); ?>
			</h1>

			<?php if ( 'list' === $action ) : ?>
				<a href="?page=<?php echo esc_attr( $this->plugin_name ); ?>-redirects&action=add" class="page-title-action">
					<?php esc_html_e( 'Add New', 'ai-seo-pro' ); ?>
				</a>
			<?php endif; ?>

			<hr class="wp-header-end">

			<?php
			switch ( $action ) {
				case 'add':
					$this->render_add_redirect_form();
					break;
				case 'edit':
					$this->render_edit_redirect_form( $redirect_id );
					break;
				case 'import':
					$this->render_import_form();
					break;
				default:
					$this->render_redirects_list();
					break;
			}
			?>
		</div>
		<?php
	}

	/**
	 * Display 404 monitor page
	 */
	public function display_404_monitor_page() {
		require_once AI_SEO_PRO_PLUGIN_DIR . 'admin/views/404-monitor.php';
	}

	/**
	 * Render redirects list
	 */
	private function render_redirects_list() {
		$page   = isset( $_GET['paged'] ) ? max( 1, intval( $_GET['paged'] ) ) : 1;
		$search = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';

		$results = $this->redirect_manager->get_redirects(
			array(
				'page'     => $page,
				'per_page' => 20,
				'search'   => $search,
			)
		);

		require_once AI_SEO_PRO_PLUGIN_DIR . 'admin/views/redirects-list.php';
	}

	/**
	 * Render add redirect form
	 */
	private function render_add_redirect_form() {
		$redirect = (object) array(
			'source_url'    => '',
			'target_url'    => '',
			'redirect_type' => '301',
			'is_regex'      => 0,
			'is_active'     => 1,
		);

		require_once AI_SEO_PRO_PLUGIN_DIR . 'admin/views/redirect-form.php';
	}

	/**
	 * Render edit redirect form
	 *
	 * @param int $redirect_id The redirect ID.
	 */
	private function render_edit_redirect_form( $redirect_id ) {
		$redirect = $this->redirect_manager->get_redirect( $redirect_id );

		if ( ! $redirect ) {
			wp_die( esc_html__( 'Redirect not found.', 'ai-seo-pro' ) );
		}

		require_once AI_SEO_PRO_PLUGIN_DIR . 'admin/views/redirect-form.php';
	}

	/**
	 * Render import form
	 */
	private function render_import_form() {
		require_once AI_SEO_PRO_PLUGIN_DIR . 'admin/views/redirect-import.php';
	}

	/**
	 * Handle redirect actions
	 *
	 * @access public (required for WordPress hooks)
	 */
	public function handle_redirect_actions() {
		// Only run on our redirect pages.
		if (
			! isset( $_GET['page'] ) ||
			( sanitize_text_field( wp_unslash( $_GET['page'] ) ) !== $this->plugin_name . '-redirects' )
		) {
			return;
		}

		// Check nonce for POST actions.
		if ( ! isset( $_POST['ai_seo_redirect_nonce'] ) && ! isset( $_GET['_wpnonce'] ) ) {
			return;
		}

		// Export CSV - must be first, before any output.
		if ( isset( $_GET['action'] ) && 'export' === $_GET['action'] ) {
			if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'export_redirects' ) ) {
				wp_die( esc_html__( 'Security check failed.', 'ai-seo-pro' ) );
			}

			$csv = $this->redirect_manager->export_to_csv();

			// Clear any output buffers.
			if ( ob_get_length() ) {
				ob_end_clean();
			}

			header( 'Content-Type: text/csv; charset=utf-8' );
			header( 'Content-Disposition: attachment; filename="ai-seo-redirects-' . gmdate( 'Y-m-d' ) . '.csv"' );
			header( 'Pragma: no-cache' );
			header( 'Expires: 0' );

			echo "\xEF\xBB\xBF"; // UTF-8 BOM.
			echo $csv; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSV output.
			exit;
		}

		// Import CSV.
		if ( isset( $_POST['action'] ) && 'import_csv' === $_POST['action'] ) {
			check_admin_referer( 'import_redirects', 'ai_seo_redirect_nonce' );

			if ( isset( $_FILES['csv_file'] ) && UPLOAD_ERR_OK === $_FILES['csv_file']['error'] ) {
				$results = $this->redirect_manager->import_from_csv( $_FILES['csv_file']['tmp_name'] );

				$message = sprintf(
					/* translators: 1: number of successful imports, 2: number of failed imports */
					__( 'Import complete: %1$d succeeded, %2$d failed.', 'ai-seo-pro' ),
					$results['success'],
					$results['failed']
				);

				add_settings_error(
					'ai_seo_redirects',
					'import_complete',
					$message,
					$results['failed'] > 0 ? 'warning' : 'success'
				);
			} else {
				add_settings_error(
					'ai_seo_redirects',
					'import_error',
					__( 'No file was uploaded or there was an upload error.', 'ai-seo-pro' ),
					'error'
				);
			}

			set_transient( 'ai_seo_redirect_notices', get_settings_errors( 'ai_seo_redirects' ), 30 );
			wp_safe_redirect( admin_url( 'admin.php?page=' . $this->plugin_name . '-redirects' ) );
			exit;
		}

		// Save redirect.
		if ( isset( $_POST['action'] ) && 'save_redirect' === $_POST['action'] ) {
			check_admin_referer( 'ai_seo_redirect_save', 'ai_seo_redirect_nonce' );

			$redirect_id = isset( $_POST['redirect_id'] ) ? intval( $_POST['redirect_id'] ) : 0;

			$data = array(
				'source_url'    => isset( $_POST['source_url'] ) ? sanitize_text_field( wp_unslash( $_POST['source_url'] ) ) : '',
				'target_url'    => isset( $_POST['target_url'] ) ? sanitize_text_field( wp_unslash( $_POST['target_url'] ) ) : '',
				'redirect_type' => isset( $_POST['redirect_type'] ) ? sanitize_text_field( wp_unslash( $_POST['redirect_type'] ) ) : '301',
				'is_regex'      => isset( $_POST['is_regex'] ) ? 1 : 0,
				'is_active'     => isset( $_POST['is_active'] ) ? 1 : 0,
			);

			if ( $redirect_id > 0 ) {
				// Update.
				$result  = $this->redirect_manager->update_redirect( $redirect_id, $data );
				$message = __( 'Redirect updated successfully.', 'ai-seo-pro' );
			} else {
				// Add.
				$result  = $this->redirect_manager->add_redirect( $data );
				$message = __( 'Redirect added successfully.', 'ai-seo-pro' );
			}

			if ( is_wp_error( $result ) ) {
				add_settings_error(
					'ai_seo_redirects',
					'redirect_error',
					$result->get_error_message(),
					'error'
				);
			} else {
				add_settings_error(
					'ai_seo_redirects',
					'redirect_success',
					$message,
					'success'
				);
			}

			set_transient( 'ai_seo_redirect_notices', get_settings_errors( 'ai_seo_redirects' ), 30 );
			wp_safe_redirect( admin_url( 'admin.php?page=' . $this->plugin_name . '-redirects' ) );
			exit;
		}

		// Delete redirect.
		if ( isset( $_GET['action'] ) && 'delete' === $_GET['action'] && isset( $_GET['id'] ) ) {
			$delete_id = intval( $_GET['id'] );
			if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'delete_redirect_' . $delete_id ) ) {
				wp_die( esc_html__( 'Security check failed.', 'ai-seo-pro' ) );
			}

			$this->redirect_manager->delete_redirect( $delete_id );

			add_settings_error(
				'ai_seo_redirects',
				'redirect_deleted',
				__( 'Redirect deleted successfully.', 'ai-seo-pro' ),
				'success'
			);

			set_transient( 'ai_seo_redirect_notices', get_settings_errors( 'ai_seo_redirects' ), 30 );
			wp_safe_redirect( admin_url( 'admin.php?page=' . $this->plugin_name . '-redirects' ) );
			exit;
		}

		// Bulk delete.
		if ( isset( $_POST['action'] ) && 'bulk_delete' === $_POST['action'] && isset( $_POST['redirect_ids'] ) ) {
			check_admin_referer( 'bulk_delete_redirects', 'ai_seo_redirect_nonce' );

			$ids   = array_map( 'intval', wp_unslash( $_POST['redirect_ids'] ) );
			$count = 0;

			foreach ( $ids as $id ) {
				if ( $this->redirect_manager->delete_redirect( $id ) ) {
					$count++;
				}
			}

			add_settings_error(
				'ai_seo_redirects',
				'redirects_deleted',
				sprintf(
					/* translators: %d: number of deleted redirects */
					__( '%d redirect(s) deleted successfully.', 'ai-seo-pro' ),
					$count
				),
				'success'
			);

			set_transient( 'ai_seo_redirect_notices', get_settings_errors( 'ai_seo_redirects' ), 30 );
			wp_safe_redirect( admin_url( 'admin.php?page=' . $this->plugin_name . '-redirects' ) );
			exit;
		}
	}

	/**
	 * Handle 404 actions
	 *
	 * @access public (required for WordPress hooks)
	 */
	public function handle_404_actions() {
		// Only run on our 404 monitor page.
		if (
			! isset( $_GET['page'] ) ||
			( sanitize_text_field( wp_unslash( $_GET['page'] ) ) !== $this->plugin_name . '-404-monitor' )
		) {
			return;
		}

		// Check nonce.
		if ( ! isset( $_GET['_wpnonce'] ) && ! isset( $_POST['ai_seo_404_nonce'] ) ) {
			return;
		}

		// Export 404 logs - must be first.
		if ( isset( $_GET['action'] ) && 'export_404' === $_GET['action'] ) {
			if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'export_404_logs' ) ) {
				wp_die( esc_html__( 'Security check failed.', 'ai-seo-pro' ) );
			}

			$days = isset( $_GET['days'] ) ? intval( $_GET['days'] ) : 30;
			$csv  = $this->monitor_404->export_to_csv( $days );

			// Clear any output buffers.
			if ( ob_get_length() ) {
				ob_end_clean();
			}

			header( 'Content-Type: text/csv; charset=utf-8' );
			header( 'Content-Disposition: attachment; filename="ai-seo-404-logs-' . gmdate( 'Y-m-d' ) . '.csv"' );
			header( 'Pragma: no-cache' );
			header( 'Expires: 0' );

			echo "\xEF\xBB\xBF"; // UTF-8 BOM.
			echo $csv; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- CSV output.
			exit;
		}

		// Delete 404 log.
		if ( isset( $_GET['action'] ) && 'delete' === $_GET['action'] && isset( $_GET['id'] ) ) {
			$delete_id = intval( $_GET['id'] );
			if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'delete_404_' . $delete_id ) ) {
				wp_die( esc_html__( 'Security check failed.', 'ai-seo-pro' ) );
			}

			$this->monitor_404->delete_log( $delete_id );

			add_settings_error(
				'ai_seo_404',
				'log_deleted',
				__( '404 log deleted successfully.', 'ai-seo-pro' ),
				'success'
			);

			set_transient( 'ai_seo_404_notices', get_settings_errors( 'ai_seo_404' ), 30 );
			wp_safe_redirect( admin_url( 'admin.php?page=' . $this->plugin_name . '-404-monitor' ) );
			exit;
		}

		// Clear all logs.
		if ( isset( $_GET['action'] ) && 'clear_all' === $_GET['action'] ) {
			if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'clear_all_404' ) ) {
				wp_die( esc_html__( 'Security check failed.', 'ai-seo-pro' ) );
			}

			$this->monitor_404->clear_all_logs();

			add_settings_error(
				'ai_seo_404',
				'logs_cleared',
				__( 'All 404 logs cleared successfully.', 'ai-seo-pro' ),
				'success'
			);

			set_transient( 'ai_seo_404_notices', get_settings_errors( 'ai_seo_404' ), 30 );
			wp_safe_redirect( admin_url( 'admin.php?page=' . $this->plugin_name . '-404-monitor' ) );
			exit;
		}

		// Create redirect from 404.
		if ( isset( $_POST['action'] ) && 'create_redirect_from_404' === $_POST['action'] ) {
			check_admin_referer( 'create_redirect_404', 'ai_seo_404_nonce' );

			$source_url = isset( $_POST['source_url'] ) ? sanitize_text_field( wp_unslash( $_POST['source_url'] ) ) : '';
			$target_url = isset( $_POST['target_url'] ) ? sanitize_text_field( wp_unslash( $_POST['target_url'] ) ) : '';

			$result = $this->redirect_manager->add_redirect(
				array(
					'source_url'    => $source_url,
					'target_url'    => $target_url,
					'redirect_type' => '301',
				)
			);

			if ( is_wp_error( $result ) ) {
				add_settings_error(
					'ai_seo_404',
					'redirect_error',
					$result->get_error_message(),
					'error'
				);
			} else {
				// Delete the 404 log.
				if ( isset( $_POST['log_id'] ) && $_POST['log_id'] ) {
					$this->monitor_404->delete_log( intval( $_POST['log_id'] ) );
				}

				add_settings_error(
					'ai_seo_404',
					'redirect_created',
					__( 'Redirect created successfully.', 'ai-seo-pro' ),
					'success'
				);
			}

			set_transient( 'ai_seo_404_notices', get_settings_errors( 'ai_seo_404' ), 30 );
			wp_safe_redirect( admin_url( 'admin.php?page=' . $this->plugin_name . '-404-monitor' ) );
			exit;
		}
	}

	/**
	 * Display admin notices
	 */
	public function display_notices() {
		$notices = get_transient( 'ai_seo_redirect_notices' );

		if ( $notices ) {
			foreach ( $notices as $notice ) {
				printf(
					'<div class="notice notice-%s is-dismissible"><p>%s</p></div>',
					esc_attr( $notice['type'] ),
					esc_html( $notice['message'] )
				);
			}
			delete_transient( 'ai_seo_redirect_notices' );
		}

		$notices_404 = get_transient( 'ai_seo_404_notices' );

		if ( $notices_404 ) {
			foreach ( $notices_404 as $notice ) {
				printf(
					'<div class="notice notice-%s is-dismissible"><p>%s</p></div>',
					esc_attr( $notice['type'] ),
					esc_html( $notice['message'] )
				);
			}
			delete_transient( 'ai_seo_404_notices' );
		}
	}
}