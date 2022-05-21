<?php
namespace WooCommerce\Square\Framework\Addresses;

defined( 'ABSPATH' ) or exit;

/**
 * The base address data class.
 *
 * This serves as a standard address object to be passed around by plugins whenever dealing with address data, and
 * eliminates the need to rely on WooCommerce's address arrays.
 *
 * @since 3.0.0
 */
class Address {


	/** @var string line 1 of the street address */
	protected $line_1 = '';

	/** @var string line 2 of the street address */
	protected $line_2 = '';

	/** @var string line 3 of the street address */
	protected $line_3 = '';

	/** @var string address locality (city) */
	protected $locality = '';

	/** @var string address region (state) */
	protected $region = '';

	/** @var string address country */
	protected $country = '';

	/** @var string address postcode */
	protected $postcode = '';


	/** Getter methods ************************************************************************************************/


	/**
	 * Gets line 1 of the street address.
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */
	public function get_line_1() {

		return $this->line_1;
	}


	/**
	 * Gets line 2 of the street address.
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */
	public function get_line_2() {

		return $this->line_2;
	}


	/**
	 * Gets line 3 of the street address.
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */
	public function get_line_3() {

		return $this->line_3;
	}


	/**
	 * Gets the locality or city.
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */
	public function get_locality() {

		return $this->locality;
	}


	/**
	 * Gets the region or state.
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */
	public function get_region() {

		return $this->region;
	}


	/**
	 * Gets the country.
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */
	public function get_country() {

		return $this->country;
	}


	/**
	 * Gets the postcode.
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */
	public function get_postcode() {

		return $this->postcode;
	}


	/**
	 * Gets the hash representation of this address.
	 *
	 * @see Address::get_hash_data()
	 *
	 * @since 3.0.0
	 *
	 * @return string
	 */
	public function get_hash() {

		return md5( wp_json_encode( $this->get_hash_data() ) );
	}


	/**
	 * Gets the data used to generate a hash for the address.
	 *
	 * @since 3.0.0
	 *
	 * @return string[]
	 */
	protected function get_hash_data() {

		return array(
			$this->get_line_1(),
			$this->get_line_2(),
			$this->get_line_3(),
			$this->get_locality(),
			$this->get_region(),
			$this->get_country(),
			$this->get_postcode(),
		);
	}


	/** Setter methods ************************************************************************************************/


	/**
	 * Sets line 1 of the street address.
	 *
	 * @since 3.0.0
	 *
	 * @param string $value line 1 value
	 */
	public function set_line_1( $value ) {

		$this->line_1 = $value;
	}


	/**
	 * Sets line 2 of the street address.
	 *
	 * @since 3.0.0
	 *
	 * @param string $value line 2 value
	 */
	public function set_line_2( $value ) {

		$this->line_2 = $value;
	}


	/**
	 * Gets line 3 of the street address.
	 *
	 * @since 3.0.0
	 *
	 * @param string $value line 3 value
	 */
	public function set_line_3( $value ) {

		$this->line_3 = $value;
	}


	/**
	 * Gets the locality or city.
	 *
	 * @since 3.0.0
	 *
	 * @param string $value locality value
	 */
	public function set_locality( $value ) {

		$this->locality = $value;
	}


	/**
	 * Gets the region or state.
	 *
	 * @since 3.0.0
	 *
	 * @param string $value region value
	 */
	public function set_region( $value ) {

		$this->region = $value;
	}


	/**
	 * Sets the country.
	 *
	 * @since 3.0.0
	 *
	 * @param string $value country value
	 */
	public function set_country( $value ) {

		$this->country = $value;
	}


	/**
	 * Sets the postcode.
	 *
	 * @since 3.0.0
	 *
	 * @param string $value postcode value
	 */
	public function set_postcode( $value ) {

		$this->postcode = $value;
	}


}
