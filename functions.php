<?php

if (! class_exists('Timber')) {
    add_action('admin_notices', function () {
        echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url(admin_url('plugins.php#timber')) . '">' . esc_url(admin_url('plugins.php')) . '</a></p></div>';
    });
    
    add_filter('template_include', function ($template) {
        return get_stylesheet_directory() . '/static/no-timber.html';
    });
    
    return;
}

add_action('after_setup_theme', 'wpdocs_theme_setup');

function cc_mime_types($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

function wpdocs_theme_setup()
{
    add_image_size('bunker-fullwidth', 2560);            // width: 1920, height: auto
    add_image_size('bunker-large', 1920); 				 // width: 1280, height: auto
    add_image_size('bunker-landscape', 1280, 720, true); // width: 1280, height: 720, cropped
    add_image_size('bunker-portrait', 720, 1280, true);  // width: 1280, height: 720, cropped
}

Timber::$dirname = array('templates', 'views');

class StarterSite extends TimberSite
{
    public function __construct()
    {
        add_theme_support('post-formats');
        add_theme_support('post-thumbnails');
        add_theme_support('menus');
        add_theme_support('html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ));
        add_filter('timber_context', array( $this, 'add_to_context' ));
        add_filter('get_twig', array( $this, 'add_to_twig' ));
        add_action('init', array( $this, 'register_post_types' ));
        add_action('init', array( $this, 'register_taxonomies' ));
        parent::__construct();
    }

    public function register_post_types()
    {
        //this is where you can register custom post types
        require get_template_directory() . '/inc/custom-post-types.php';
    }

    public function register_taxonomies()
    {
        //this is where you can register custom taxonomies
    }

    public function add_to_context($context)
    {
        $context['foo'] = 'bar';
        $context['stuff'] = 'I am a value set in your functions.php file';
        $context['notes'] = 'These values are available everytime you call Timber::get_context();';
        $context['menu'] = new TimberMenu('primary');
        $context['site'] = $this;
        return $context;
    }

    public function myfoo($text)
    {
        $text .= ' bar!';
        return $text;
    }

    public function share($kuula_post)
    {
        $share = str_replace('post', 'share', $kuula_post);
        return $share;
    }

    public function menu_class($item)
    {
        $class = 'nav-link';
        $slug = strtolower($item);

        if ($slug == 'inschrijven') {
            $class .= ' button nav-inschrijven';
        }

        return $class;
    }

    public function webcal($http_url)
    {
        $webcal_url = str_replace('http', 'webcal', $http_url);

        return $webcal_url;
    }

    public function remove_breaks($text)
    {
        $filtered = str_replace('<br>', ' ', $text);
        $filtered = str_replace('-', ' ', $filtered);
        $filtered = str_replace('  ', ' ', $filtered);

        return $filtered;
    }

    public function title_to_class($text)
    {
        $filtered = str_replace('<br>', ' ', $text);
        $filtered = str_replace('  ', ' ', $filtered);
        $filtered = str_replace(' ', '-', $filtered);
        $filtered = strtolower($filtered);
        
        return $filtered;
    }

    public function datum($date)
    {
        $parts = explode('-', $date);
        $datum = '';

        switch($parts[0]) {
            case 'Mon':
            $datum = 'Maandag';
            break;
            case 'Tue':
            $datum = 'Dinsdag';
            break;
            case 'Wed':
            $datum = 'Woensdag';
            break;
            case 'Thu':
            $datum = 'Donderdag';
            break;
            case 'Fri':
            $datum = 'Vrijdag';
            break;
            case 'Sat':
            $datum = 'Zaterdag';
            break;
            case 'Sun':
            $datum = 'Zondag';
            break;
        }

        $datum .= ' ' . $parts[1] . ' ';

        switch($parts[2]) {
            case '1':
            $datum .= 'januari';
            break;
            case '2':
            $datum .= 'februari';
            break;
            case '3':
            $datum .= 'maart';
            break;
            case '4':
            $datum .= 'april';
            break;
            case '5':
            $datum .= 'mei';
            break;
            case '6':
            $datum .= 'juni';
            break;
            case '7':
            $datum .= 'juli';
            break;
            case '8':
            $datum .= 'augustus';
            break;
            case '9':
            $datum .= 'september';
            break;
            case '10':
            $datum .= 'oktober';
            break;
            case '11':
            $datum .= 'november';
            break;
            case '12':
            $datum .= 'december';
            break;
        }
        
        $datum .= ' ' . $parts[3];

        return $datum;
    }

    public function get_sign_in_url($menu) {
        $menu_items = wp_get_nav_menu_items($menu);

        foreach ($menu_items as $item) {
            if (strtolower($item->title) === 'inschrijven') {
                return $item->url;
            }

        }
        return '#';
    }

    public function add_to_twig($twig)
    {
        /* this is where you can add your own functions to twig */
        $twig->addExtension(new Twig_Extension_StringLoader());
        
        $twig->addFilter('myfoo', new Twig_SimpleFilter('myfoo', array($this, 'myfoo')));
        
        $twig->addFilter('menu_class', new Twig_SimpleFilter('menu_class', array($this, 'menu_class')));
        
        $twig->addFilter('webcal', new Twig_SimpleFilter('webcal', array($this, 'webcal')));
        
        $twig->addFilter('share', new Twig_SimpleFilter('share', array($this, 'share')));
        
        $twig->addFilter('datum', new Twig_SimpleFilter('datum', array($this, 'datum')));

        $twig->addFilter('remove_breaks', new Twig_SimpleFilter('remove_breaks', array($this, 'remove_breaks')));
        
        $twig->addFilter('title_to_class', new Twig_SimpleFilter('title_to_class', array($this, 'title_to_class')));

        $twig->addFilter('get_sign_in_url', new Twig_SimpleFilter('get_sign_in_url', array($this, 'get_sign_in_url')));
        
        return $twig;
    }
}

new StarterSite();
