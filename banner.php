

<?php
/**
* Plugin Name: Banner
* Plugin URI: http://lakpro.github.io
* Description: A nice way to greet you customers using a banner at the top of the page.
* Version: 1.0
* Author: Lakshay Setia
* Author URI: https://www.linkedin.com/in/lakshay-setia/
**/

// Enqueue your CSS file
function enqueue_banner_styles() {
    wp_enqueue_style('banner-styles', plugins_url('main.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'enqueue_banner_styles');



// adding the banner
add_action('wp_body_open','banner');

function banner(){
    // Get the current hour in 24-hour format
    $currentHour = date('H');

    // Define greetings based on the time of day
    if ($currentHour >= 5 && $currentHour < 12) {
        $greeting = 'it\'s Morning';
    } elseif ($currentHour >= 12 && $currentHour < 18) {
        $greeting = 'it\'s Afternoon';
    } else {
        $greeting = 'it\'s Evening'; 
    }

    // Check if user is logged in and display the name
    if(is_user_logged_in()){
        $user = wp_get_current_user();
        $greeting = $user -> user_login;
    }

    if(!get_option('banner_value')){
        $msg = 'Welcome aboard, and grab that 15% discount before you go. 🎉🎉';
    }else{
        $msg = get_option('banner_value');
    }   

    echo '<h3 class="msg">Hey, ' . $greeting . '! ' . $msg . '</h3>';
}


// Custom banner plugin page
add_action('admin_menu', 'banner_plugin_page');

function banner_plugin_page() {
    $page_title = 'Banner Options';
    $menu_title = 'Banner';
	$capatibily = 'manage_options';
	$slug = 'banner-plugin';
	$callback = 'banner_page_html';
	$icon = 'dashicons-schedule';
	$position = 60;

	add_menu_page($page_title, $menu_title, $capatibily, $slug, $callback, $icon, $position);
}



// register a setting
add_action('admin_init', 'banner_register_settings');

function banner_register_settings() {
	register_setting('banner_option_group', 'banner_value');
}

// creating banner page ui
function banner_page_html() { ?>
    <div class="wrap banner-wrapper">
        <form method="post" action="options.php">
            <?php settings_errors() ?>
            <?php settings_fields('banner_option_group'); ?>
            <label for="banner_value_id">Banner Text:</label>
            <input name="banner_value" id="banner_value_id" type="text" value="<?php echo esc_attr(get_option('banner_value')); ?>">
            <?php submit_button(); ?>
        </form>
    </div>
    
<?php }


