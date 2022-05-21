<?php
/**
 * Apple Pay Frontend Class
 *
 * This file defines a class responsible to load features
 * related to Apply Pay on the front end.
 * Features such as loading assets, rendering Apply Pay button, etc.
 *
 * @package WooCommerce Square
 * @subpackage Apple Pay
 * @since 3.0.0
 */

namespace WooCommerce\Square\Framework\PaymentGateway\ApplePay;
use WooCommerce\Square\Framework as SquareFramework;

defined( 'ABSPATH' ) or exit;

/**
 * Sets up the Apple Pay front-end functionality.
 *
 * @since 3.0.0
 */
class Payment_Gateway_Apple_Pay_Frontend {

	/** @var SquareFramework\PaymentGateway\Payment_Gateway_Plugin $plugin the gateway plugin instance */
	protected $plugin;

	/** @var Payment_Gateway_Apple_Pay $handler the Apple Pay handler instance */
	protected $handler;

	/** @var SquareFramework\PaymentGateway\Payment_Gateway $gateway the gateway instance */
	protected $gateway;


	/**
	 * Constructs the class.
	 *
	 * @since 3.0.0
	 *
	 * @param SquareFramework\PaymentGateway\Payment_Gateway_Plugin $plugin the gateway plugin instance
	 * @param Payment_Gateway_Apple_Pay $handler the Apple Pay handler instance
	 */
	public function __construct( SquareFramework\PaymentGateway\Payment_Gateway_Plugin $plugin, Payment_Gateway_Apple_Pay $handler ) {

		$this->plugin = $plugin;

		$this->handler = $handler;

		$this->gateway = $this->get_handler()->get_processing_gateway();

		if ( $this->get_handler()->is_available() ) {

			add_action( 'wp', array( $this, 'init' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}
	}


	/**
	 * Initializes the scripts and hooks.
	 *
	 * @since 3.0.0
	 */
	public function init() {

		$locations = $this->get_display_locations();

		if ( is_product() && in_array( 'product', $locations, true ) ) {
			$this->init_product();
		} else if ( is_cart() && in_array( 'cart', $locations, true ) ) {
			$this->init_cart();
		} else if ( is_checkout() && in_array( 'checkout', $locations, true ) ) {
			$this->init_checkout();
		}
	}


	/**
	 * Gets the configured display locations.
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */
	protected function get_display_locations() {

		return get_option( 'sv_wc_apple_pay_display_locations', array() );
	}


	/**
	 * Enqueues the scripts.
	 *
	 * @since 3.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_style( 'wc-square-apple-pay', $this->get_plugin()->get_plugin_url() . '/assets/css/frontend/wc-square-payment-gateway-apple-pay.css', array(), $this->get_plugin()->get_version() ); // TODO: min

		wp_enqueue_script( 'wc-square-apple-pay', $this->get_plugin()->get_plugin_url() . '/assets/js/frontend/wc-square-payment-gateway-apple-pay.min.js', array( 'jquery' ), $this->get_plugin()->get_version(), true );

		/**
		 * Filters the Apple Pay JS handler params.
		 *
		 * @since 3.0.0
		 * @param array $params the JS params
		 */
		$params = apply_filters( 'sv_wc_apple_pay_js_handler_params', array(
			'gateway_id'               => $this->get_gateway()->get_id(),
			'gateway_id_dasherized'    => $this->get_gateway()->get_id_dasherized(),
			'merchant_id'              => $this->get_handler()->get_merchant_id(),
			'ajax_url'                 => admin_url( 'admin-ajax.php' ),
			'validate_nonce'           => wp_create_nonce( 'sv_wc_apple_pay_validate_merchant' ),
			'recalculate_totals_nonce' => wp_create_nonce( 'sv_wc_apple_pay_recalculate_totals' ),
			'process_nonce'            => wp_create_nonce( 'sv_wc_apple_pay_process_payment' ),
			'generic_error'            => __( 'An error occurred, please try again or try an alternate form of payment', 'woocommerce-square' ),
		) );

		wp_localize_script( 'wc-square-apple-pay', 'sv_wc_apple_pay_params', $params );
	}


	/**
	 * Renders an Apple Pay button.
	 *
	 * @since 3.0.0
	 */
	public function render_button() {

		$button_text = '';
		$classes     = array(
			'sv-wc-apple-pay-button',
		);

		switch ( $this->get_handler()->get_button_style() ) {

			case 'black':
				$classes[] = 'apple-pay-button-black';
			break;

			case 'white':
				$classes[] = 'apple-pay-button-white';
			break;

			case 'white-with-line':
				$classes[] = 'apple-pay-button-white-with-line';
			break;
		}

		// if on the single product page, add some text
		if ( is_product() ) {
			$classes[]   = 'apple-pay-button-buy-now';
			$button_text = __( 'Buy with', 'woocommerce-square' );
		}

		if ( $button_text ) {
			$classes[] = 'apple-pay-button-with-text';
		}

		echo '<button class="' . implode( ' ', array_map( 'sanitize_html_class', $classes ) ) . '" lang="' . esc_attr( substr( get_locale(), 0, 2 ) ) . '">';

			if ( $button_text ) {
				echo '<span class="text">' . esc_html( $button_text ) . '</span><span class="logo"></span>';
			}

		echo '</button>';
	}


	/**
	 * Initializes Apple Pay on the single product page.
	 *
	 * @since 3.0.0
	 */
	public function init_product() {

		$args = array();

		try {

			$product = wc_get_product( get_the_ID() );

			if ( ! $product ) {
				throw new \Exception( __( 'Product does not exist.', 'woocommerce-square' ) );
			}

			$payment_request = $this->get_handler()->get_product_payment_request( $product );

			$args['payment_request'] = $payment_request;

		} catch ( \Exception $e ) {

			$this->get_handler()->log( sprintf( 'Could not initialize Apple Pay. %s', $e->getMessage() ) );
		}

		/**
		 * Filters the Apple Pay product handler args.
		 *
		 * @since 3.0.0
		 * @param array $args
		 */
		$args = apply_filters( 'sv_wc_apple_pay_product_handler_args', $args );

		wc_enqueue_js( sprintf( 'window.sv_wc_apple_pay_handler = new Square_Apple_Pay_Product_Handler(%s);', wp_json_encode( $args ) ) );

		add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'render_button' ) );
	}


	/** Cart functionality ****************************************************/


	/**
	 * Initializes Apple Pay on the cart page.
	 *
	 * @since 3.0.0
	 */
	public function init_cart() {

		$args = array();

		try {

			$payment_request = $this->get_handler()->get_cart_payment_request( WC()->cart );

			$args['payment_request'] = $payment_request;

		} catch ( \Exception $e ) {

			$args['payment_request'] = false;
		}

		/**
		 * Filters the Apple Pay cart handler args.
		 *
		 * @since 3.0.0
		 * @param array $args
		 */
		$args = apply_filters( 'sv_wc_apple_pay_cart_handler_args', $args );

		wc_enqueue_js( sprintf( 'window.sv_wc_apple_pay_handler = new Square_Apple_Pay_Cart_Handler(%s);', wp_json_encode( $args ) ) );

		add_action( 'woocommerce_proceed_to_checkout', array( $this, 'render_button' ) );
	}


	/** Checkout functionality ************************************************/


	/**
	 * Initializes Apple Pay on the checkout page.
	 *
	 * @since 3.0.0
	 */
	public function init_checkout() {

		/**
		 * Filters the Apple Pay checkout handler args.
		 *
		 * @since 3.0.0
		 * @param array $args
		 */
		$args = apply_filters( 'sv_wc_apple_pay_checkout_handler_args', array() );

		wc_enqueue_js( sprintf( 'window.sv_wc_apple_pay_handler = new Square_Apple_Pay_Checkout_Handler(%s);', wp_json_encode( $args ) ) );

		if ( $this->get_plugin()->is_plugin_active( 'woocommerce-checkout-add-ons.php' ) ) {
			add_action( 'woocommerce_review_order_before_payment', array( $this, 'render_button' ) );
		} else {
			add_action( 'woocommerce_before_checkout_form', array( $this, 'render_checkout_button' ), 15 );
		}
	}


	/**
	 * Renders the Apple Pay button for checkout.
	 *
	 * @since 3.0.0
	 */
	public function render_checkout_button() {

		?>

		<div class="sv-wc-apply-pay-checkout">

			<?php /** translators: Phrase that preceeds the Apple Pay logo, i.e. "Pay with [logo]" */
			$button_text = __( 'Pay with', 'woocommerce-square' );

			$this->render_button(); ?>

			<span class="divider">
				<?php /** translators: "or" as in "Pay with Apple Pay [or] regular checkout" */
				esc_html_e( 'or', 'woocommerce-square' ); ?>
			</span>

		</div>

		<?php
	}


	/**
	 * Gets the gateway instance.
	 *
	 * @since 3.0.0
	 *
	 * @return SquareFramework\PaymentGateway\Payment_Gateway
	 */
	protected function get_gateway() {

		return $this->gateway;
	}


	/**
	 * Gets the gateway plugin instance.
	 *
	 * @since 3.0.0
	 *
	 * @return SquareFramework\PaymentGateway\Payment_Gateway_Plugin
	 */
	protected function get_plugin() {

		return $this->plugin;
	}

	/**
	 * Gets the Apple Pay handler instance.
	 *
	 * @since 3.0.0
	 *
	 * @return Payment_Gateway_Apple_Pay
	 */
	protected function get_handler() {

		return $this->handler;
	}
}
