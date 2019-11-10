<?php

/**
 * Register our custom form settings.
 */
class Cf7_Storage_Form_Settings {

	/**
	 * Settings object used for storing form settings.
	 *
	 * @var Cf7_Storage_Meta_Store
	 */
	protected $store;

	/**
	 * Create the form settings instance.
	 *
	 * @param Cf7_Storage_Meta_Store $store Settings data store instance.
	 */
	public function __construct( $store ) {
		$this->store = $store;
	}

	/**
	 * Get entry from store.
	 *
	 * @param  int $id Entry ID.
	 *
	 * @return array
	 */
	public function get( $id ) {
		return $this->sanitize( $this->store->get( $id ) );
	}

	/**
	 * Update entry.
	 *
	 * @param integer $id Entry ID.
	 * @param array   $settings List of settings.
	 */
	public function set( $id, $settings ) {
		return $this->store->set( $id, $this->sanitize( $settings ) );
	}

	/**
	 * Return fields to exclude from export for form.
	 *
	 * @param  int $id Form ID.
	 *
	 * @return array
	 */
	public function excluded_fields( $id ) {
		$settings = $this->get( $id );

		return $settings['exclude'];
	}

	/**
	 * Sanitize form settings.
	 *
	 * @param  array $input Form setting input.
	 *
	 * @return array
	 */
	public function sanitize( $input ) {
		$settings = array(
			'exclude' => array(),
		);

		if ( ! is_array( $input ) ) {
			$input = array();
		}

		if ( ! empty( $input['exclude'] ) && is_array( $input['exclude'] ) ) {
			$settings['exclude'] = array_map( 'strval', $input['exclude'] );
		}

		return $settings;
	}

}
