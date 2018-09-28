<?php
/* Template Name: Woningzoeker DEV */

$args = array(
    'post_type' => 'woning'
);

$context = Timber::get_context();
$context['post'] = new TimberPost();
$context['appartments'] = Timber::get_posts($args);
$templates = array( 'pages/page-woningzoeker.twig' );

Timber::render( $templates, $context);