<?php

/**
 * Generic settings store.
 */
class Cf7_Storage_Plugin_Settings {

	/**
	 * Data store object.
	 *
	 * @var Cf7_Storage_Options_Store
	 */
	protected $store;

	/**
	 * Setup the data store.
	 *
	 * @param Cf7_Storage_Options_Store $store Data store.
	 */
	public function __construct( $store ) {
		$this->store = $store;
	}

	/**
	 * Get the storage key ID.
	 *
	 * @return string
	 */
	public function id() {
		return $this->store->key();
	}

	/**
	 * Save settings to the data store.
	 *
	 * @param  array $settings Settings to save.
	 *
	 * @return boolean If the settings were stored.
	 */
	public function set( $settings = array() ) {
		return $this->store->set( $this->sanitize( $settings ) );
	}

	/**
	 * Get settings or a specific setting field.
	 *
	 * @param  array $path Path of array keys for lookup.
	 *
	 * @return mixed
	 */
	public function get( $path = null ) {
		$settings = $this->sanitize( $this->store->get() );

		if ( ! isset( $path ) ) {
			return $settings;
		}

		foreach ( $path as $field ) {
			if ( isset( $settings[ $field ] ) ) {
				$settings = $settings[ $field ];
			} else {
				return null;
			}
		}

		return $settings;
	}

	/**
	 * Is storing user IP disabled.
	 *
	 * @return boolean
	 */
	public function is_meta_ip_disabled() {
		return ! empty( $this->get( array( 'meta-disable', 'user-ip' ) ) );
	}

	/**
	 * Is storing user browser agent disabled.
	 *
	 * @return boolean
	 */
	public function is_meta_user_agent_disabled() {
		return ! empty( $this->get( array( 'meta-disable', 'user-agent' ) ) );
	}

	/**
	 * Sanitize settings according to rules.
	 *
	 * @param  mixed $input Settings to store.
	 *
	 * @return array
	 */
	public function sanitize( $input ) {
		if ( ! is_array( $input ) || empty( $input ) ) {
			$input = array();
		}

		$settings = array(
			'meta-disable' => array(
				'user-agent' => 0,
				'user-ip' => 0,
			),
		);

		if ( ! empty( $input['meta-disable'] ) && is_array( $input['meta-disable'] ) ) {
			foreach ( $settings['meta-disable'] as $field_name => $default_value ) {
				if ( isset( $input['meta-disable'][ $field_name ] ) ) {
					$settings['meta-disable'][ $field_name ] = intval( $input['meta-disable'][ $field_name ] );
				}
			}
		}

		return $settings;
	}

}
