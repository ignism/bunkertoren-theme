<?php
/* Template Name: Front Page */

$context = Timber::get_context();
$context['post'] = new TimberPost();
$templates = array( 'pages/page-front.twig' );

Timber::render( $templates, $context);