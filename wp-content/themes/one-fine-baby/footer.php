<?php ?>

</main>

<footer class="footer">


	<div class="footer-top">
		<div class="padded-row">
			<div class="footer-left-top">
				Thanks to our event sponsors
			</div>
			<div class="footer-right-top">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer - Sponsors") ) : ?>
				<?php endif;?>
			</div>
		</div>
	</div>
		
	<div class="clear"></div>
	
	<div class="footer-bottom">
		<div class="padded-row">
			<div class="footer-left">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer - Left") ) : ?>
				<?php endif;?>
			</div>
			<div class="footer-middle-left">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer - Middle Left") ) : ?>
				<?php endif;?>
			</div>
			<div class="footer-middle-right">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer - Middle Right") ) : ?>
				<?php endif;?>
			</div>
			<div class="footer-right">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Footer - Right") ) : ?>
				<?php endif;?>
			</div>
		</div>
	</div>

</footer

<?php wp_footer(); ?>
<script>
    jQuery('a[href*="tel:"]').on('click',  function(){
        ga('send','event','phone-number','click');
    });
    jQuery('a[href*="mailto:"]').on('click',  function(){
        ga('send','event','email-address','click');
    });	
    jQuery('#mc4wp-form-1').on('submit',  function(){
        ga('send','event','newsletter','submit');
    });		
    document.addEventListener( 'wpcf7mailsent', function( event ) {
        if ( '308' == event.detail.contactFormId ) {
          ga('send','event','contact-form','submit');
        }
    }, false );	
</script>

</body>
</html>