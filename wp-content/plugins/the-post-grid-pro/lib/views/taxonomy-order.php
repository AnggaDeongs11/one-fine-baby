<?php $taxonomy_objects = rtTPG()->getAllTpgTaxonomyObject(); ?>

<div class="wrap">
	<h2><?php _e( 'Taxonomy Ordering', 'tlp-portfolio-pro' ) ?></h2>
	<?php if ( ! function_exists( 'get_term_meta' ) ) { ?>
		<div class="update-message notice inline notice-error notice-alt"><p>Please update your wordpress to 4.4.0 or
				latest version to use taxonomy order functionality.</p></div>
	<?php } ?>
	<div id="tpg-post-type-wrapper">
		<div class="tpg-form-item-wrap">
			<label>Post Type</label>
			<div class="tpg-form-item">
				<select class="rt-select2" id="tpg-post-type">
					<option value="">Select one post type</option>
					<?php
					$postTypes = rtTPG()->rtPostTypes();
					if ( ! empty( $postTypes ) ) {
						foreach ( $postTypes as $key => $value ) {
							echo "<option value='{$key}'>{$value}</option>";
						}
					}
					?>
				</select>
			</div>
		</div>
		<div class="tpg-form-item-wrap" id="tpg-taxonomy-wrapper">
			<label>Select Taxonomy</label>
			<div class="tpg-form-item">
				<select class="rt-select2" id="tpg-taxonomy">
					<option value="">Select one taxonomy</option>
				</select>
			</div>
		</div>
	</div>
	<div class="ordering-wrapper">
		<div id="term-wrapper">
			<p>No taxonomy selected</p>
		</div>
	</div>
</div>
