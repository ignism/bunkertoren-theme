<?php
/* Template Name: Appartments */

$args = array(
    'post_type' => 'woning'
);

$context = Timber::get_context();
$context['post'] = new TimberPost();
$context['appartments'] = Timber::get_posts($args);
$templates = array( 'pages/page-appartments.twig' );

Timber::render( $templates, $context);