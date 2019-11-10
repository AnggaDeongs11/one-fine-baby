<?php

if ( ! class_exists( 'rtTTPGTaxOrder' ) ):

	class rtTTPGTaxOrder {
		function __construct() {
			// Add cat columns
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'wp_ajax_tpg-get-taxonomy-list', array( $this, 'tpg_get_taxonomy_list' ) );
			add_action( 'wp_ajax_tpg-get-term-list', array( $this, 'tpg_get_term_list' ) );
			add_action( 'wp_ajax_tpg-update-term-order', array( $this, 'tpg_update_term_order' ) );

		}

		function tpg_get_taxonomy_list() {

			$data  = $msg = null;
			$error = true;
			if ( rtTPG()->verifyNonce() ) {
				$pt = ( ! empty( $_REQUEST['pt'] ) ? $_REQUEST['pt'] : null );
				if ( $pt ) {
					$error            = false;
					$taxonomy_objects = rtTPG()->getAllTpgTaxonomyObject( $pt );
						$data .= "<option value=''>".__("Select one taxonomy","the-post-grid-pro")."</option>";
					if ( ! empty( $taxonomy_objects ) ) {
						foreach ( $taxonomy_objects as $tax ) {
							$data .= "<option value='{$tax->name}'>{$tax->label}</option>";
						}
					} else {
						$msg .= "<p>" . __( 'No term found', 'the-post-grid-pro' ) . "</p>";
					}
				} else {
					$msg .= "<p>" . __( 'Select a post type', 'the-post-grid-pro' ) . "</p>";
				}
			} else {
				$msg .= "<p>" . __( 'Security error', 'the-post-grid-pro' ) . "</p>";
			}

			wp_send_json(
				array(
					'data'  => $data,
					'error' => $error,
					'msg'   => $msg
				)
			);
			die();
		}


		function tpg_update_term_order() {

			$html  = $msg = null;
			$error = true;
			if ( rtTPG()->verifyNonce() ) {
				$terms = ( ! empty( $_REQUEST['terms'] ) ? explode( ',', $_REQUEST['terms'] ) : array() );
				if ( $terms && ! empty( $terms ) ) {
					$error = false;
					foreach ( $terms as $key => $term_id ) {
						update_term_meta( $term_id, '_rt_order', $key + 1 );
					}
				} else {
					$msg .= "<p>" . __( 'No term in list', 'the-post-grid-pro' ) . "</p>";
				}
			} else {
				$msg .= "<p>" . __( 'Security error', 'the-post-grid-pro' ) . "</p>";
			}

			wp_send_json(
				array(
					'data'  => $html,
					'error' => $error,
					'msg'   => $msg
				)
			);
			die();
		}

		function tpg_get_term_list() {

			$html  = $msg = null;
			$error = true;
			if ( rtTPG()->verifyNonce() ) {
				$tax = ( ! empty( $_REQUEST['tax'] ) ? $_REQUEST['tax'] : null );
				if ( $tax ) {
					$error      = false;
					$temp_terms = get_terms( array(
						'taxonomy'   => $tax,
						'hide_empty' => false,
					) );
					if ( ! empty( $temp_terms ) && empty($temp_terms['errors'])) {
						foreach ( $temp_terms as $term ) {
							$order = get_term_meta( $term->term_id, '_rt_order', true );
							if ( $order === "" ) {
								update_term_meta( $term->term_id, '_rt_order', 0 );
							}
						}
					}

					$terms = get_terms( array(
						'taxonomy'   => $tax,
						'orderby'    => 'meta_value_num',
						'meta_key'   => '_rt_order',
						'order'      => 'ASC',
						'hide_empty' => false,
					) );
					if ( ! empty( $terms ) ) {
						$html .= "<ul id='order-target' data-taxonomy='{$tax}'>";
						foreach ( $terms as $term ) {
							$html .= "<li data-id='{$term->term_id}'><span>{$term->name}</span></li>";
						}
						$html .= "</ul>";
					} else {
						$html .= "<p>" . __( 'No term found', 'the-post-grid-pro' ) . "</p>";
					}
				} else {
					$html .= "<p>" . __( 'Select a taxonomy', 'the-post-grid-pro' ) . "</p>";
				}
			} else {
				$html .= "<p>" . __( 'Security error', 'the-post-grid-pro' ) . "</p>";
			}

			wp_send_json(
				array(
					'data'  => $html,
					'error' => $error,
					'msg'   => $msg
				)
			);
			die();
		}

		function admin_enqueue_scripts() {
			global $pagenow, $typenow;
			// validate page
			if ( ! in_array( $pagenow, array( 'edit.php' ) ) && ! empty( $_REQUEST['page'] ) && $_REQUEST['page'] != 'tgp_taxonomy_order' ) {
				return;
			}
			if ( $typenow != rtTPG()->post_type ) {
				return;
			}
			$select2Id = 'rt-select2';
			if ( class_exists( 'WPSEO_Admin_Asset_Manager' ) && class_exists( 'Avada' ) ) {
				$select2Id = 'yoast-seo-select2';
			} elseif ( class_exists( 'WPSEO_Admin_Asset_Manager' ) ) {
				$select2Id = 'yoast-seo-select2';
			} elseif ( class_exists( 'Avada' ) ) {
				$select2Id = 'select2-avada-js';
			}
			wp_enqueue_script( array(
				'jquery',
				'jquery-ui-core',
				'jquery-ui-sortable',
				$select2Id,
				'tpg-admin-taxonomy'
			) );
			wp_enqueue_style( array(
				'rt-select2',
				'rt-tpg-admin'
			) );

			wp_localize_script( 'tpg-admin-taxonomy', 'rttpg',
				array(
					'nonceID' => rtTPG()->nonceId(),
					'nonce'   => wp_create_nonce( rtTPG()->nonceText() ),
					'ajaxurl' => admin_url( 'admin-ajax.php' )
				) );
		}

	}

endif;