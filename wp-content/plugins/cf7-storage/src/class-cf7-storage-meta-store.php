<?php

/**
 * WordPress post meta data store.
 */
class Cf7_Storage_Meta_Store {

	/**
	 * Meta key used for the data store.
	 *
	 * @var [type]
	 */
	protected $key;

	/**
	 * Setup store.
	 *
	 * @param string $key Post meta key ID.
	 */
	public function __construct( $key ) {
		$this->key = $key;
	}

	/**
	 * Meta key ID used for the meta store.
	 *
	 * @return string
	 */
	public function key() {
		return $this->key;
	}

	/**
	 * Write to the data store.
	 *
	 * @param integer $id Post ID used for storing.
	 * @param mixed   $settings Data to save.
	 *
	 * @return boolean If the store was updated.
	 */
	public function set( $id, $settings ) {
		return update_post_meta( $id, $this->key, $settings );
	}

	/**
	 * Read from the data store.
	 *
	 * @param integer $id Post ID.
	 *
	 * @return mixed  Any data in the store.
	 */
	public function get( $id ) {
		return get_post_meta( $id, $this->key, true );
	}

}
