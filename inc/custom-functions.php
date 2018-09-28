<?php

function get_sign_in_url() {
    $menuItems = wp_get_nav_menu_items( "primary" );

    return $menuItems;
}