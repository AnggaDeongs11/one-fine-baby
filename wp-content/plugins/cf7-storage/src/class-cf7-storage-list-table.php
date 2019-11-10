<?php
/**
 * Table view for the form entries.
 */
class Cf7_Storage_List_Table extends WP_List_Table {

	/**
	 * Custom post type name.
	 *
	 * @var string
	 */
	protected $storage_post_type;

	/**
	 * If currently viewing entries in trash.
	 *
	 * @var boolean
	 */
	protected $is_trash = false;

	/**
	 * Storage instance.
	 *
	 * @var Cf7_Storage
	 */
	protected $storage;

	/**
	 * Setup list view for our custom post type.
	 *
	 * @param string      $post_type Post type used in the list view.
	 * @param Cf7_Storage $storage Instance of the storage plugin.
	 */
	function __construct( $post_type, $storage ) {
		$this->storage_post_type = $post_type;
		$this->storage = $storage;

		parent::__construct(
			array(
				'singular' => 'form-entry',
				'plural' => 'form-entries',
				'ajax' => true,
			)
		);
	}

	/**
	 * Fetch entries for the current view.
	 *
	 * @return void
	 */
	function prepare_items() {
		$per_page = $this->get_items_per_page( 'cf7_entries_per_page' );
		$this->is_trash = isset( $_REQUEST['post_status'] ) && 'trash' === $_REQUEST['post_status'];

		$this->_column_headers = array(
			$this->get_columns(),
			array(),
			$this->get_sortable_columns(),
		);

		$query_args = $this->storage->get_query_args();

		$query_args['posts_per_page'] = $per_page;
		$query_args['offset'] = ( $this->get_pagenum() - 1 ) * $per_page;

		$query = new WP_Query( $query_args );

		$this->items = $query->posts;

		$this->set_pagination_args(
			array(
				'total_items' => $query->found_posts,
				'total_pages' => ceil( $query->found_posts / $per_page ),
				'per_page' => $per_page,
			)
		);
	}

	/**
	 * List of the visible columns.
	 *
	 * @return array
	 */
	function get_columns() {
		// Use the "manage_contact_page_cf7_storage_columns" filter to add extra columns.
		return array(
			'cb' => '<input type="checkbox" />',
			'title' => __( 'Entry', 'cf7-storage' ),
			'contact_form' => __( 'Contact Form', 'cf7-storage' ),
			'date' => __( 'Date', 'cf7-storage' ),
		);
	}

	/**
	 * Print message when no entries found.
	 *
	 * @return void
	 */
	function no_items() {
		esc_html_e( 'All form submissions will appear here. Try submitting one of the forms on the site.', 'cf7-storage' );
	}

	/**
	 * Set the available view filters.
	 *
	 * @return array
	 */
	function get_views() {
		$status_links = array();

		$num_posts = wp_count_posts( $this->storage_post_type, 'readable' );
		$entries_stati = get_post_stati( array( 'show_in_admin_status_list' => true ), 'objects' );

		$view_link = add_query_arg(
			array(
				'page' => 'cf7_storage',
			),
			admin_url( 'admin.php' )
		);

		$status_links['all'] = sprintf(
			'<a href="%s">%s <span class="count">(%d)</span></a>',
			$view_link,
			esc_html__( 'All', 'cf7-storage' ),
			$num_posts->publish
		);

		$status_links['trash'] = sprintf(
			'<a href="%s" %s>%s</a>',
			add_query_arg( 'post_status', 'trash', $view_link ),
			$this->is_trash ? 'class="current"' : null,
			sprintf(
				translate_nooped_plural( $entries_stati['trash']->label_count, $num_posts->trash ),
				number_format_i18n( $num_posts->trash )
			)
		);

		return $status_links;
	}

	/**
	 * List the sortable column IDs.
	 *
	 * @return array
	 */
	function get_sortable_columns() {
		return array(
			'date' => array( 'date', true ),
		);
	}

	/**
	 * Return bulk actions for each entry in the list.
	 *
	 * @return array
	 */
	function get_bulk_actions() {
		$actions = array();

		if ( $this->is_trash ) {
			$actions['untrash'] = __( 'Restore', 'cf7-storage' );
		}

		if ( $this->is_trash || ! EMPTY_TRASH_DAYS ) {
			$actions['delete'] = __( 'Delete Permanently', 'cf7-storage' );
		} else {
			$actions['trash'] = __( 'Move to Trash', 'cf7-storage' );
		}

		return $actions;
	}

	/**
	 * Checkbox column output HTML.
	 *
	 * @param  WP_Post $item Row entry post object.
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="%s[]" value="%s" />',
			esc_attr( $this->_args['singular'] ),
			esc_attr( $item->ID )
		);
	}

	/**
	 * Title column output.
	 *
	 * @param  WP_Post $item Current post object.
	 *
	 * @return string
	 */
	function column_title( $item ) {
		$actions = array();

		$view_link = add_query_arg(
			array(
				'page' => 'cf7_storage',
				'action' => 'view',
				'post_id' => absint( $item->ID ),
			),
			wp_nonce_url( 'admin.php', 'bulk-form-entries' )
		);

		$actions['quick-preview'] = sprintf(
			'<a href="#entry-preview-%d">%s</a>',
			$item->ID,
			__( 'Preview', 'cf7-storage' )
		);

		if ( $this->is_trash ) {
			$actions['untrash'] = sprintf(
				'<a href="%s">%s</a>',
				add_query_arg( 'action', 'untrash', $view_link ),
				__( 'Restore', 'cf7-storage' )
			);

			$actions['export'] = sprintf(
				'<a href="%s">%s</a>',
				add_query_arg( 'action', 'export', $view_link ),
				__( 'Export as CSV', 'cf7-storage' )
			);

			$actions['delete'] = sprintf(
				'<a href="%s">%s</a>',
				add_query_arg( 'action', 'delete', $view_link ),
				__( 'Delete Permanently', 'cf7-storage' )
			);
		} else {
			$actions['view'] = sprintf(
				'<a href="%s">%s</a>',
				$view_link,
				__( 'View', 'cf7-storage' )
			);

			$actions['export'] = sprintf(
				'<a href="%s">%s</a>',
				add_query_arg( 'action', 'export', $view_link ),
				__( 'Export as CSV', 'cf7-storage' )
			);

			$actions['trash'] = sprintf(
				'<a href="%s">%s</a>',
				add_query_arg( 'action', 'trash', $view_link ),
				__( 'Trash', 'cf7-storage' )
			);
		}

		$cf7_edit_url = add_query_arg(
			array(
				'page' => 'wpcf7',
				'action' => 'view',
				'post' => absint( $item->post_parent ),
			),
			admin_url( 'admin.php' )
		);

		$mail_body = apply_filters( 'the_content', get_post_meta( $item->ID, 'mail_body', true ) );
		$mail_body = wp_strip_all_tags( $mail_body );

		return sprintf(
			'<a class="row-entry-title" href="%s" title="%s">
					<h3 class="entry-from">%s</h3>
					<h4 class="entry-subject">%s</h4>
				</a>
				%s
				<div id="entry-preview-%d" class="row-entry-preview">
					<div class="entry-preview-wrap">
						<pre>%s</pre>
					</div>
				</div>',
			$view_link,
			// translators: %s: Contact form title.
			esc_attr( sprintf( __( 'View this submission from %s', 'cf7-storage' ), $item->post_title ) ),
			esc_html( $item->post_title ),
			esc_html( get_post_meta( $item->ID, 'mail_subject', true ) ),
			$this->row_actions( $actions ),
			esc_attr( $item->ID ),
			esc_html( $mail_body )
		);
	}

	/**
	 * Render the date column.
	 *
	 * @param  WP_Post $item Entry post object.
	 *
	 * @return string
	 */
	function column_date( $item ) {
		$t_time = mysql2date( 'r', $item->post_date, true );
		$time = mysql2date( 'G', $item->post_date ) - get_option( 'gmt_offset' ) * 3600;

		$time_diff = time() - $time;

		if ( $time_diff > 0 && $time_diff < 24 * 60 * 60 ) {
			// translators: %s: relative time diff, for example 1 minute, 2 days, etc.
			$h_time = sprintf( __( '%s ago', 'cf7-storage' ), human_time_diff( $time ) );
		} else {
			$h_time = mysql2date( get_option( 'date_format' ), $item->post_date );
		}

		return sprintf(
			'<abbr title="%s">%s</abbr>',
			esc_attr( $t_time ),
			esc_html( $h_time )
		);
	}

	/**
	 * Contact form link column output.
	 *
	 * @param  WP_Post $item Entry post object.
	 *
	 * @return string
	 */
	function column_contact_form( $item ) {
		$cf7_edit_url = add_query_arg(
			array(
				'page' => 'wpcf7',
				'action' => 'view',
				'post' => absint( $item->post_parent ),
			),
			admin_url( 'admin.php' )
		);

		return sprintf(
			'<a href="%s">%s</a>',
			$cf7_edit_url,
			esc_html( get_the_title( $item->post_parent ) )
		);
	}

	/**
	 * Render extra navigation links.
	 *
	 * @param  string $which Link location.
	 *
	 * @return void
	 */
	function extra_tablenav( $which ) {
		?>
		<div class="alignleft actions cf7-entries-actions-filter">
		<?php
		if ( 'top' === $which ) {
			$this->months_dropdown( $this->storage_post_type );

			$forms = get_posts(
				array(
					'posts_per_page' => -1,
					'post_type' => 'wpcf7_contact_form',
					'orderby' => 'title',
					'order' => 'ASC',
				)
			);

			$dropdown_items = array(
				sprintf(
					'<option value="">%s</option>',
					__( 'All Contact Forms', 'cf7-storage' )
				),
			);

			$form_id = isset( $_REQUEST['form_id'] ) ? $_REQUEST['form_id'] : null;

			foreach ( $forms as $form ) {
				$dropdown_items[] = sprintf(
					'<option value="%s" %s>%s</option>',
					esc_attr( $form->ID ),
					selected( $form_id, $form->ID, false ),
					esc_html( $form->post_title )
				);
			}

			printf(
				'<select name="form_id">%s</select>',
				implode( '', $dropdown_items )
			);

			submit_button(
				__( 'Filter', 'cf7-storage' ),
				'button',
				'filter-entries',
				false,
				array( 'id' => 'entries-query-submit' )
			);
		}

		if ( $this->is_trash && current_user_can( get_post_type_object( $this->storage_post_type )->cap->edit_others_posts ) ) {
			submit_button(
				__( 'Empty Trash', 'cf7-storage' ),
				'apply',
				'delete_all',
				false
			);
		}

		?>
		</div>
		<div class="alignleft actions cf7-entries-list-export">
		<?php

		if ( 'top' === $which ) {
			if ( empty( $_GET['form_id'] ) ) {
				printf(
					'<em class="notice-filter">%s</em>',
					esc_html__( 'Filter entries by form to export.', 'cf7-storage' )
				);
			} else {
				$delimiters = $this->storage->get_csv_delimiters();
				$delimiter_selected = $this->storage->get_csv_delimiter();
				$delimiter_dropdown = array();

				foreach ( $delimiters as $value => $label ) {
					$delimiter_dropdown[] = sprintf(
						'<option value="%s" %s>%s</option>',
						esc_attr( $value ),
						selected( $delimiter_selected, $value, false ),
						esc_html( $label )
					);
				}

				printf(
					'<label>
						<select class="export-csv-delimiter" name="export-csv-delimiter" title="%s">
							%s
						</select>
					</label>',
					esc_attr__( 'Choose the CSV delimiter character', 'cf7-storage' ),
					implode( '', $delimiter_dropdown )
				);

				submit_button(
					__( 'Export as CSV', 'cf7-storage' ),
					'button',
					'export-entries',
					false,
					array(
						'id' => 'entries-export-all',
					)
				);
			}
		}
		?>
		</div>
		<?php
	}

}
