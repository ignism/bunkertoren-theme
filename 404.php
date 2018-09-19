<?php

$context = Timber::get_context();
$templates = array( 'pages/404.twig' );

Timber::render( $templates, $context);