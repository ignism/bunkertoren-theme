<?php
/* Template Name: Front Page */

$context = Timber::get_context();
$context['posts'] = new TimberPost();
$templates = array( 'pages/page-front.twig' );

Timber::render( $templates, $context, 1, Timber\Loader::CACHE_NONE);