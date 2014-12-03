<?php

require 'class-yoast-gp-seo.php';

$yoast_gp_seo = new Yoast_GP_SEO;
$yoast_gp_seo->set_homepage_title( 'Translate Yoast Plugins to your language!' );
$yoast_gp_seo->set_separator( '•' );
$yoast_gp_seo->set_site_name( 'Yoast Translate' );
$yoast_gp_seo->set_homepage_description( 'This the home of the Yoast Translate project, where all Yoast WordPress Plugins and Themes are being translated. Join today!' );
$yoast_gp_seo->run();
GP::$plugins->yoast_gp_seo = $yoast_gp_seo;
