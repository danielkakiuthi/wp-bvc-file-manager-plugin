<?php
/**
 * Updates class - Advanced File Manager - Addon
 * Copyright: modalweb
 */
class file_manager_advanced_shortcode_updates 
{
	private $current_version;
	private $update_path;
	private $plugin_slug;
	private $slug;
	private $license_user;
	private $license_key;
	public function __construct( $current_version, $update_path, $plugin_slug, $license_user = '', $license_key = '' )
	{
		$this->current_version = $current_version;
		$this->update_path = $update_path;
		$this->license_user = $license_user;
		$this->license_key = $license_key;
		$this->plugin_slug = $plugin_slug;
		list ($t1, $t2) = explode( '/', $plugin_slug );
		$this->slug = str_replace( '.php', '', $t2 );		
        set_site_transient('update_plugins', null);
		add_filter( 'pre_set_site_transient_update_plugins', array( &$this, 'check_fmas_update' ) );
		add_filter( 'plugins_api', array( &$this, 'check_info' ), 10, 3 );
	}
	public function check_fmas_update( $transient )
	{
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		// Get the remote version
		$remote_version = self::getRemote('version');

		// If a newer version is available, add the update
		if ( version_compare( $this->current_version, $remote_version->new_version, '<' ) ) {
			$obj = new stdClass();
			$obj->slug = $this->slug;
			$obj->new_version = $remote_version->new_version;
			$obj->url = $remote_version->url;
			$obj->plugin = $this->plugin_slug;
			$obj->package = $remote_version->package;
			$obj->tested = $remote_version->tested;
			$transient->response[$this->plugin_slug] = $obj;
		}
		return $transient;
	}

	/**
	 * Add our self-hosted description to the filter
	 *
	 * @param boolean $false
	 * @param array $action
	 * @param object $arg
	 * @return bool|object
	 */
	public function check_info($obj, $action, $arg)
	{
		if (($action=='query_plugins' || $action=='plugin_information') && 
		    isset($arg->slug) && $arg->slug === $this->slug) {
			return self::getRemote('info');
		}
		
		return $obj;
	}

	/**
	 * Return the remote version
	 * 
	 * @return string $remote_version
	 */
	public function getRemote($action = '')
	{
		$params = array(
			'body' => array(
				'action'       => $action,
				'license_user' => $this->license_user,
				'license_key'  => $this->license_key,
			),
		);
		
		// Make the POST request
		$request = wp_remote_post($this->update_path, $params );
		
		// Check if response is valid
		if ( !is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
			return @unserialize( $request['body'] );
		}
		
		return false;
	}
}
