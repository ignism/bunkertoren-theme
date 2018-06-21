<?php
/* Template Name: About */

$context = Timber::get_context();
$context['post'] = new TimberPost();
$templates = array( 'pages/page-about.twig' );

Timber::render( $templates, $context);