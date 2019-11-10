<?php
$vendorHeader = is_vendor_page();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>" />
        <meta name="viewport" content="width=device-width" />
        <title>
            <?php bloginfo('name'); ?> |
            <?php is_front_page() ? bloginfo('description') : wp_title(''); ?>
        </title>

        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        <link href="//cloud.typenetwork.com/projects/3009/fontface.css/" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.typekit.net/tek5vpo.css">
        <link rel="stylesheet" type="text/css" href="/wp-content/themes/ofb-2020/fonts/MyFontsWebfontsKit.css">
        <?php wp_head(); ?>

    </head>

    <body <?php body_class(); ?>>

        <header id="masthead" class="site-header">
            <div class="container center">
                <?php if (!$vendorHeader) :?>
                    <div class="logo">
                        <div class="container">
                            <?php // echo do_shortcode('[ivory-search id="134" title="Default Search Form"]'); ?>
                            <?php echo do_shortcode('[aws_search_form]'); ?>
    <!--                        <form role="search" class="nav-search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon" id="basic-addon2">
                                             <button type="submit" class="search-btn">
                                                <i class="fas fa-search search-icon"></i>
                                            </button>
                                        </span>
                                        <input type="text" class="form-control" placeholder="Search" name="s" value="<?php get_search_query(); ?>">
                                        <input type="hidden" name="post_type" value="product" />
                                    </div>
                                </div>
                            </form>-->
                            <a href="<?php echo get_site_url(); ?>/shop" class="logo-header"><img src="<?php echo get_site_url(); ?>/wp-content/uploads/2019/06/one-fine-baby-blue-logo.png"></a>
                            <!--                        <form class="nav-search-2" method="get" action="#">
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <span class="input-group-addon" id="basic-addon2">
                                                                    <button type="submit" class="search-btn">
                                                                        <i class="fas fa-search search-icon"></i>
                                                                    </button>
                                                                </span>
                                                                <input type="text" class="form-control" placeholder="Search" name="search">
                                                            </div>
                                                        </div>
                                                    </form>-->
                            <ul class="navbar-nav ml-auto links-nav d0fkex align-items-center">
                                <?php
                                $user = wp_get_current_user();
                                if ((in_array('customer', (array) $user->roles) ||  in_array('administrator', (array) $user->roles)) && is_user_logged_in()) :
                                    ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>">Account</a>
                                    </li>
                                    <?php
                                endif;
                                if (in_array('vendor', (array) $user->roles) && is_user_logged_in()) :
                                    ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo home_url('dashboard'); ?>">Dashboard</a>
                                    </li>
                                    <?php
                                endif;
                                ?>

                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo (is_user_logged_in()) ? esc_url(wc_logout_url(wc_get_page_permalink('myaccount'))) : home_url('/my-account'); ?>"><?php echo (is_user_logged_in()) ? 'Logout' : 'Join/Login'; ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo home_url('/wishlist');?>">
                                        <i class="far fa-heart"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo get_site_url(); ?>/cart" id="st-cart-btn">
                                        <i class="fas fa-shopping-cart"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                <div id="myHeader" class="navigation header">
                    <nav class="site-navigation main-navigation">
                        <?php wp_nav_menu(array('container_class' => 'main-nav', 'theme_location' => 'primary')); ?>
                    </nav>
                </div>
                <?php endif; ?>
            </div>
        </header>

        <main class="main-content">
