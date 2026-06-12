<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="header-fixed-layout">
    <div class="header-inner">
        <div class="logo-area">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                <img src="http://xs888688.xsrv.jp/pluslab-test/wp-content/uploads/2026/05/logo_header.svg" alt="Plus lab">
            </a>
        </div>
        
        <div class="menu-btn-area">
            <?php do_action( 'lightning_site_header_append' ); ?>
        </div>
    </div>
</header>

<div id="content" class="site-content">
<div class="site-body">