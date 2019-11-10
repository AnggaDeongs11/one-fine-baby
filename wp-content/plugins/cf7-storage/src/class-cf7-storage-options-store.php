<?php

/**
 * WordPress options data store.
 */
class Cf7_Storage_Options_Store {

	/**
	 * Option key. Should be unique so prefix it with something.
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * Setup store.
	 *
	 * @param string $key Option key name.
	 */
	public function __construct( $key ) {
		$this->key = $key;
	}

	/**
	 * Return the setting ID.
	 *
	 * @return string
	 */
	public function key() {
		return $this->key;
	}

	/**
	 * Write to the data store.
	 *
	 * @param mixed $settings Data to save.
	 *
	 * @return boolean If the store was updated.
	 */
	public function set( $settings ) {
		return update_option( $this->key, $settings );
	}

	/**
	 * Read from the data store.
	 *
	 * @return mixed Any data in the store.
	 */
	public function get() {
		return get_option( $this->key );
	}

}
