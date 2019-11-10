<?php 
$jquery_version = isset ( $wp_scripts->registered ['jquery-ui-core']->ver ) ? $wp_scripts->registered ['jquery-ui-core']->ver : '1.9.2';
//wp_enqueue_style( 'jquery-ui', 'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css' );
//
//wp_enqueue_style( 'jquery-ui-style');
//wp_enqueue_style( 'jquery-ui-core');
//wp_enqueue_style( 'jquery-ui-accordion');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_script('moment-with-locales.js');

wp_enqueue_script('jquery-ui-accordion');
$wishlist = '';
if ($wid) {
	$wishlist = Magenest_Giftregistry_Model::get_wishlist($wid);
}

/* enable full mode */

$enable_full_mode = get_option('giftregistry_allow_fullmode');
?>
<style>
	#ui-datepicker-div{
		border-radius: 4px !important;
		border: 1px solid #aaaaaa !important;
		padding: 0px 15px !important;
	}
	.ui-datepicker td {
		background-color: #8b8b8b !important;
		padding: 10px !important;
	}
</style>
<div id="accordion-giftregisty">
	<h3> <?php echo __('Gift Registry Info', GIFTREGISTRY_TEXT_DOMAIN)?></h3>

	<div id="accordion-giftregisty-content" <?php if (is_admin() ) :?> class="admin-gift-table" <?php  endif;?>">
		<form class="giftregistry-form" enctype="multipart/form-data" method="POST">
			<input type="hidden" name="giftregistry_id" id="giftregistry_id" value="<?php if (is_object($wishlist)) : echo $wishlist->id ; endif;?>"/>
			<input name="create_giftregistry" id="create_giftregistry" type="hidden" value="1"/>

			<div class="form-field">
				<label for="title"><?php echo __('Title', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
				<input name="title" id="title" type="text" value="<?php if (is_object($wishlist)) : echo $wishlist->title ; endif;?>" size="40">
			</div>

			<h3> <?php echo __('Registrant', GIFTREGISTRY_TEXT_DOMAIN) ?></h3>

			<div class="form-field">
				<label for="registrant_firstname"><?php echo __('First name', GIFTREGISTRY_TEXT_DOMAIN) ?><span class="required">*</span></label>
				<input name="registrant_firstname" id="registrant_firstname"
					type="text"  size="40" value="<?php if (is_object($wishlist)) : echo $wishlist->registrant_firstname ; endif;?>">
			</div>

			<div class="form-field">
				<label for="registrant_lastname"><?php echo __('Last name', GIFTREGISTRY_TEXT_DOMAIN) ?><span class="required">*</span></label>
				<input name="registrant_lastname" id="registrant_lastname" value="<?php if (is_object($wishlist)) : echo $wishlist->registrant_lastname ; endif;?>" type="text"
					value="" size="40">
			</div>

			<div class="form-field">
				<label for="registrant_email"><?php echo __('Email', GIFTREGISTRY_TEXT_DOMAIN) ?><span class="required">*</span></label>
				<input name="registrant_email" id="registrant_firstname" type="text" value="<?php if (is_object($wishlist)) : echo $wishlist->registrant_email ; endif;?>"
					value="" size="40">
			</div>

			<?php  if ($enable_full_mode == 'yes')  { ?>
				<div class="form-field">
					<input type="file" name="registrant_image" id="registrant_image"  multiple="false" />
				</div>
				<div class="form-field">
					<?php echo wp_get_attachment_image( $wishlist->registrant_image, 'large' ) ?>
				</div>
				<div class="form-field">
					<label for="registrant_description"><?php echo __('Description', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
					<textarea rows="6" name="registrant_description"><?php if (is_object($wishlist)) : echo stripslashes($wishlist->registrant_description) ; endif;?></textarea>
				</div>
			<?php  } ?>

			<h3 style="margin-top: 40px;"> <?php echo __('Are there CoRegistrants?', GIFTREGISTRY_TEXT_DOMAIN) ?></h3>

			<div class="form-field" style="margin-bottom: 40px;">
				<select name="co_r" id="co_r">
					<?php
					if(empty($co_registrant)){?>
						<option value="1"><?=__('Yes',GIFTREGISTRY_TEXT_DOMAIN)?></option>
						<option value="0" selected><?=__('No',GIFTREGISTRY_TEXT_DOMAIN)?></option>
					<?php
					}else{?>
						<option value="1" selected><?=__('Yes',GIFTREGISTRY_TEXT_DOMAIN)?></option>
						<option value="0"><?=__('No',GIFTREGISTRY_TEXT_DOMAIN)?></option>
					<?php
					}
					?>

				</select>
			</div>
			<div id="co_registrants">
				<h3> <?php echo __('CoRegistrant', GIFTREGISTRY_TEXT_DOMAIN) ?></h3>

				<div class="form-field">
					<label for="coregistrant_firstname"><?php echo __('First name', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
					<input name="coregistrant_firstname" id="coregistrant_firstname"
						   type="text" value="<?php if (is_object($wishlist)) : echo $wishlist->coregistrant_firstname ; endif;?>" size="40">
				</div>

				<div class="form-field">
					<label for="coregistrant_lastname"><?php echo __('Last name', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
					<input name="coregistrant_lastname" id="coregistrant_lastname"
						   type="text" value="<?php if (is_object($wishlist)) : echo $wishlist->coregistrant_lastname ; endif;?>" size="40">
				</div>

				<div class="form-field">
					<label for="coregistrant_email"><?php echo __('Email', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
					<input name="coregistrant_email" id="coregistrant_email"
						   type="text" value="<?php if (is_object($wishlist)) : echo $wishlist->coregistrant_email ; endif;?>" size="40">
				</div>

				<?php  if ($enable_full_mode == 'yes')  { ?>
					<div class="form-field">
						<input type="file" name="coregistrant_image" id="coregistrant_image"  multiple="false" />
						<?php echo wp_get_attachment_image( $wishlist->coregistrant_image, 'large' ) ?>
					</div>
					<div class="form-field">
						<label for="coregistrant_description"><?php echo __('Description', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
						<textarea rows="6" name="coregistrant_description"><?php if (is_object($wishlist)) : echo stripslashes($wishlist->coregistrant_description) ; endif;?></textarea>
					</div>
				<?php  } ?>
			</div>
				<hr>
				<h3> <?php echo __('Event', GIFTREGISTRY_TEXT_DOMAIN) ?></h3>

				<div class="form-field">
					<label for="event_datetime"><?php echo __('Event date', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
					<input name="event_date_time" id="event_date_time" type="text"
						   value="<?php if (is_object($wishlist)) {
							   $eventdate = new DateTime($wishlist->event_date_time);
							   echo $eventdate ->format('d-m-Y') ;
						   }?>" size="40">
				</div>

				<div class="form-field">
					<label for="event_location"><?php echo __('Event location', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
					<input name="event_location" id="event_location" type="text" value="<?php if (is_object($wishlist)) : echo $wishlist->event_location ; endif;?>"
						   size="40">
				</div>

				<div class="form-field">
					<label for="message"><?php echo __('Message for guests', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
					<textarea id="message" name="message" rows="" cols=""><?php
							if (is_object($wishlist)) : echo $wishlist->message ; endif;
						?></textarea>
				</div>

				<?php  if ($enable_full_mode)  { ?>
					<div class="form-field">
						<input type="file" name="background_image" id="background_image"  multiple="false" />
						<?php echo wp_get_attachment_image( $wishlist->background_image, 'large' ) ?>
					</div>

				<?php  } ?>


			<h3 style="margin-top: 40px;"> <?php echo __('Role gift registry', GIFTREGISTRY_TEXT_DOMAIN) ?></h3>

			<div class="form-field" style="margin-bottom: 40px;">
				<label for="role"><?php echo __('Role', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
				<select name="role" id="role">
					<?php
						$role = $wishlist->role;
						if($role == 1){?>
							<option value="0"><?=__('Public',GIFTREGISTRY_TEXT_DOMAIN)?></option>
							<option value="1" selected="selected"><?=__('Private',GIFTREGISTRY_TEXT_DOMAIN)?></option>
					<?php
						}else{?>
							<option value="0" selected="selected"><?=__('Public',GIFTREGISTRY_TEXT_DOMAIN)?></option>
							<option value="1"><?=__('Private',GIFTREGISTRY_TEXT_DOMAIN)?></option>
					<?php
						}
					?>

				</select>
			</div>
			<div class="form-field" style="margin-bottom: 40px;" id="check_pass">
				<label for="role"><?php echo __('Password', GIFTREGISTRY_TEXT_DOMAIN) ?></label>
				<input type="text" name="password" id="password" value="<?= isset($wishlist->password)?$wishlist->password:''; ?>"/>
			</div>
			<input type="submit" value="<?=__('Save',GIFTREGISTRY_TEXT_DOMAIN)?>">
		</form>
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery("#accordion-giftregisty").accordion({
      collapsible: true
    });

    jQuery('#event_date_time').datepicker();
    jQuery('#event_date_time').datepicker({
        language: 'es'
    });
	//co_r
	var co_re = jQuery('#co_r').val();
	switch (co_re){
		case '1':
			jQuery('#co_registrants').show();
			break;
		default:
			jQuery('#co_registrants').hide();
			break;
	}
	jQuery('#co_r').on('change', function (event) {
		var co_re = jQuery(this).val();
		if (co_re == '1'){

		}
		switch (co_re){
			case '1':
				jQuery('#co_registrants').show();
				break;
			default:
				jQuery('#co_registrants').hide();
				break;
		}
	})
	var $pass = jQuery('#role').val();
	switch ($pass){
		case '1':
			jQuery('#check_pass').show();
			break;
		default:
			jQuery('#check_pass').hide();
			break;
	}
	jQuery('#role').on('change', function(event) {
		var $pass = jQuery(this).val();
		if($pass == '1'){

		}
		switch ($pass){
			case '1':
				jQuery('#check_pass').show();
				break;
			default:
				jQuery('#check_pass').hide();
				break;
		}
	});
}) ;
</script>

<hr>

<?php
	if( isset( $wid ) ) {
		if (function_exists('jquery_html5_file_upload_hook')) 
		jquery_html5_file_upload_hook();
	}
?>