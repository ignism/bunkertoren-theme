<?php
/* Template Name: Content */

$context = Timber::get_context();
$context['post'] = new TimberPost();
$templates = array( 'pages/page-content.twig' );

Timber::render( $templates, $context);