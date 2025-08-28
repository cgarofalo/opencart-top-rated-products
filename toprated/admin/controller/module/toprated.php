<?php

namespace Opencart\Admin\Controller\Extension\Toprated\Module;

class Toprated extends \Opencart\System\Engine\Controller {

	private $error = [];

	/**
	 * Handles the display of the admin settings page for the Top Rated module.
	 *
	 * This method performs the following tasks:
	 * - Loads language files and sets the document title.
	 * - Prepares breadcrumb navigation for the admin page.
	 * - Handles save and back button URLs based on whether a module ID is provided.
	 * - Retrieves existing module information if applicable, including name, status, and module ID.
	 * - Loads header, column left, and footer components.
	 * - Outputs the administrative view for the module.
	 *
	 * @return void Outputs the rendered view for the Top Rated module settings page in the admin panel.
	 */
	public function index(): void {
		// Get language files and set the document title
		$this->load->language( 'extension/toprated/module/toprated' );
		$this->document->setTitle( $this->language->get( 'heading_title' ) );

		// Breadcrumbs for the admin page
		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get( 'text_home' ),
			'href' => $this->url->link( 'common/dashboard', 'user_token=' . $this->session->data['user_token'] )
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get( 'text_extension' ),
			'href' => $this->url->link( 'marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module' )
		];

		if ( ! isset( $this->request->get['module_id'] ) ) {
			$data['breadcrumbs'][] = [
				'text' => $this->language->get( 'heading_title' ),
				'href' => $this->url->link( 'extension/toprated/module/toprated', 'user_token=' . $this->session->data['user_token'] )
			];
		} else {
			$data['breadcrumbs'][] = [
				'text' => $this->language->get( 'heading_title' ),
				'href' => $this->url->link( 'extension/toprated/module/toprated', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'] )
			];
		}

		// Save button
		if ( ! isset( $this->request->get['module_id'] ) ) {
			$data['save'] = $this->url->link( 'extension/toprated/module/toprated.save', 'user_token=' . $this->session->data['user_token'] );
		} else {
			$data['save'] = $this->url->link( 'extension/toprated/module/toprated.save', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'] );
		}

		$data['back'] = $this->url->link( 'marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module' );

		// Extension - Save custom fields
		if ( isset( $this->request->get['module_id'] ) ) {
			$this->load->model( 'setting/module' );

			$module_info = $this->model_setting_module->getModule( $this->request->get['module_id'] );
		}

		// Name field
		if ( isset( $module_info['name'] ) ) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		// Status field
		if ( isset( $module_info['status'] ) ) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		// Axis field
		if (isset($module_info['axis'])) {
			$data['axis'] = $module_info['axis'];
		} else {
			$data['axis'] = '';
		}

		// Limit field
		if (isset($module_info['limit'])) {
			$data['limit'] = $module_info['limit'];
		} else {
			$data['limit'] = 5;
		}

		// Width field
		if (isset($module_info['width'])) {
			$data['width'] = $module_info['width'];
		} else {
			$data['width'] = 200;
		}

		// Height field
		if (isset($module_info['height'])) {
			$data['height'] = $module_info['height'];
		} else {
			$data['height'] = 200;
		}

		// Module ID
		if ( isset( $this->request->get['module_id'] ) ) {
			$data['module_id'] = (int) $this->request->get['module_id'];
		} else {
			$data['module_id'] = 0;
		}

		// Load the header, column_left and footer
		$data['header']      = $this->load->controller( 'common/header' );
		$data['column_left'] = $this->load->controller( 'common/column_left' );
		$data['footer']      = $this->load->controller( 'common/footer' );

		// Load the view
		$this->response->setOutput( $this->load->view( 'extension/toprated/module/toprated', $data ) );
	}

	/**
	 * Handles the saving process of the module configuration. Validates user permissions, ensures the required
	 * fields meet defined criteria, and saves data to the database. If successful, appropriate success feedback
	 * is returned in the response. If validations or permissions fail, error messages are included in the response.
	 *
	 * @return void Adds a JSON output to the response containing success or error messages based on the operation result.
	 */
	public function save(): void {
		$this->load->language( 'extension/toprated/module/toprated' );
		$json = [];

		// Check is user has permission to modify
		if ( ! $this->user->hasPermission( 'modify', 'extension/toprated/module/toprated' ) ) {
			$json['error']['warning'] = $this->language->get( 'error_permission' );
		}

		$required = [
			'module_id' => 0,
			'name'      => '',
			'width'     => 0,
			'height'    => 0
		];

		$post_info = $this->request->post + $required;

		// Validate name
		if ( ! oc_validate_length( $post_info['name'], 3, 64 ) ) {
			$json['error']['name'] = $this->language->get( 'error_name' );
		}

		// Validate axis, can only be horizontal or vertical
		if ( $post_info['axis'] != 'horizontal' && $post_info['axis'] != 'vertical' ) {
			$json['error']['axis'] = $this->language->get( 'error_axis' );
		}

		// Validate limit, between 1 and 100
		if ( ! is_numeric( $post_info['limit'] ) || $post_info['limit'] < 1 || $post_info['limit'] > 100 ) {
			$json['error']['limit'] = $this->language->get( 'error_limit' );
		}

		// Validate width, between 200 and 3000
		if ( ! is_numeric( $post_info['width'] ) || $post_info['width'] < 200 || $post_info['width'] > 3000 ) {
			$json['error']['width'] = $this->language->get( 'error_width' );
		}

		// Validate height, between 200 and 3000
		if ( ! is_numeric( $post_info['height'] ) || $post_info['height'] < 200 || $post_info['height'] > 3000 ) {
			$json['error']['height'] = $this->language->get( 'error_height' );
		}

		// Prepare data for saving
		if ( ! $json ) {
			$this->load->model( 'setting/module' );

			if ( ! $post_info['module_id'] ) {
				$json['module_id'] = $this->model_setting_module->addModule( 'toprated.toprated', $post_info );
			} else {
				$this->model_setting_module->editModule( $post_info['module_id'], $post_info );
			}

			$this->cache->delete( 'product' );

			$json['success'] = $this->language->get( 'text_success' );
		}

		// Return JSON response
		$this->response->addHeader( 'Content-Type: application/json' );
		$this->response->setOutput( json_encode( $json ) );
	}
}