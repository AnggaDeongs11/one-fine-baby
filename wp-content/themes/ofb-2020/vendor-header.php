<div class="col-12 ">
    <div class="vendor-header d-flex justify-content-center">
        <div class="vendor-header__logo ">
            <?php include_once('images/logo.svg'); ?>
        </div>
        <div class="vendor-header__name">VENDOR PORTAL</div>
    </div>
    <div class="position-absolute vendor-header__logout">
        <a href="<?php echo esc_url(wc_logout_url(wc_get_page_permalink('myaccount'))); ?>" class="vendor-log-out">Log
            out</a>
    </div>
</div>


