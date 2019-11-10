<?php /* Template Name: Marketplace */ ?>

<?php ?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title>
	<?php bloginfo('name'); ?> | 
	<?php is_front_page() ? bloginfo('description') : wp_title(''); ?>
</title>

<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link href="//cloud.typenetwork.com/projects/3009/fontface.css/" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
<link rel="stylesheet" href="https://use.typekit.net/tek5vpo.css">
<link rel="stylesheet" type="text/css" href="//onefinebaby.com.au/wp-content/themes/one-fine-baby/fonts/sailec.css">
	
<meta name="google-site-verification" content="OwIySbpsGNhycRaGZokSbshxG7LSSCPHH3jQKtWF8TE" />

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-49979053-1', 'auto');
  ga('send', 'pageview');

</script>

<!-- Google Code for Remarketing Tag -->
<!--------------------------------------------------
Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. See more information and instructions on how to setup the tag on: http://google.com/ads/remarketingsetup
--------------------------------------------------->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 959405392;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/959405392/?guid=ON&script=0"/>
</div>
</noscript>

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-MBBNNK7');</script>
<!-- End Google Tag Manager -->

<script>
window.onscroll = function() {myFunction()};

var header = document.getElementById("myHeader");
var sticky = header.offsetTop;

function myFunction() {
  if (window.pageYOffset > sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
}
</script>

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<header id="masthead" class="site-header">
	<div class="container center">
    	<div class="logo"><a href="https://onefinebaby.com.au"><img src="//onefinebaby.com.au/wp-content/uploads/2019/06/one-fine-baby-blue-logo.png"></a></div>
        <div id="myHeader" class="navigation header">
			<nav class="site-navigation main-navigation">
			    <?php wp_nav_menu( array( 'container_class' => 'main-nav', 'theme_location' => 'marketplace' ) ); ?>
			</nav>
		</div>
	</div>
</header>

<main class="main-content">

	<div id="primary">
		<div id="content" role="main">

			<?php if ( have_posts() ) : ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<article class="post">
						
						<div class="the-content">
							<?php the_content(); ?>
							
							<?php wp_link_pages(); ?>
						</div>
						
					</article>

				<?php endwhile; ?>

			<?php else : ?>
				
				<article class="post error">
					<h1 class="404">Nothing here!</h1>
				</article>

			<?php endif; ?>

		</div>
	</div>

</main>

<footer class="footer marketplace-footer">
	
	<div class="footer-bottom">
		<div class="padded-row">
			<div class="footer-left">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Marketplace - Footer - Left") ) : ?>
				<?php endif;?>
			</div>
			<div class="footer-middle-left">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Marketplace - Footer - Middle Left") ) : ?>
				<?php endif;?>
			</div>
			<div class="footer-middle-right">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Marketplace - Footer - Middle Right") ) : ?>
				<?php endif;?>
			</div>
			<div class="footer-right">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Marketplace - Footer - Right") ) : ?>
				<?php endif;?>
			</div>
		</div>
	</div>

</footer

<?php wp_footer(); ?>

</body>
</html>