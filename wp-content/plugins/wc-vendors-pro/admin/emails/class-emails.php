<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC Vendors Pro Emails Class
 *
 * @author     WC Vendors, Lindeni Mahlalela
 * @package    WCVendors_Pro
 * @subpackage WCVendors_Pro/emails/
 */
class WCV_Pro_Emails {
	/**
	 * Construct, add ajax hooks
	 *
	 * @package
	 * @since
	 *
	 * @param
	 */
	public function __construct() {
		add_filter( 'woocommerce_email_classes', array( $this, 'email_classes' ) );
		add_action( 'wp_ajax_wcv_quick_contact', array( $this, 'process_quick_contact' ) );
		add_action( 'wp_ajax_nopriv_wcv_quick_contact', array( $this, 'process_quick_contact' ) );
	}

	/**
	 * Add email class to woocomerce emails
	 *
	 * @param array $emails
	 *
	 * @return array $emails
	 * @since 1.5.4
	 */
	public function email_classes( $emails ) {

		require_once 'class-vendor-contact-widget-email.php';
		$emails['WC_Vendors_Pro_Email_Vendor_Contact_Widget'] = new WC_Vendors_Pro_Email_Vendor_Contact_Widget();

		return $emails;
	}

	/**
	 * Process the ajax request to send the email
	 *
	 * @return void
	 * @since 1.5.4
	 */
	public function process_quick_contact() {
		global $woocommerce;

		$emails = $woocommerce->mailer()->get_emails();

		if ( isset( $_REQUEST['vendor'] ) ) {
			$emails['WC_Vendors_Pro_Email_Vendor_Contact_Widget']->send_email();
		}

	}
}
