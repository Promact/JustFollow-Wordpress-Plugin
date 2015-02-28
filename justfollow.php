<?php
/**
* Plugin Name: justfollow
* Plugin URI: 
* Description: 
* Version: 1.0
* Author: Promact Infotech
* Author URI: 
* Text Domain: jf-follow-button
*/

/**
 * register and enqueue your scripts here
 */
function jf_enq_scripts() {
    wp_register_script('jf-script',plugins_url('/jf-script.js', __FILE__));
    wp_enqueue_script( 'jf-script', plugins_url('/jf-script.js', __FILE__));
}
add_action('wp_enqueue_scripts','jf_enq_scripts');
add_action('init', 'jf_button_init');

$jf_follow_settings = array();

/*
 * register settings
 */
function jf_register_follow_settings() {
    register_setting('jf_follow', 'jf_follow_id');     
    wp_register_style( 'jf-style', plugins_url('jf-style.css', __FILE__) );    
}

/*
 * initialize and get settings option
 */
function jf_follow_init() {
    global $jf_follow_settings;
    global $pages;

    add_action( 'admin_init', 'jf_register_follow_settings' );
    add_filter('admin_menu', 'jf_follow_admin_menu');
    add_option('jf_follow_id',  $jf_follow_settings['id']);    
    add_option('jf_follow_excludepage', $pages);

    $jf_follow_settings['id'] = get_option('jf_follow_id');
}

/*
 * add admin menu
 */
function jf_follow_admin_menu() {
   $res =  add_options_page( __( "JF Plugin Options", "jf-follow-button" ), __( "Just Follow-Settings", "jf-follow-button" ),'manage_options','jffollows', 'jf_plugin_options');    
   add_action( 'admin_print_styles-' . $res, 'jf_admin_styles' ); 
}

/*
 * enqueue admin style.It will be called only on your plugin admin page
 */
function jf_admin_styles() {
       wp_enqueue_style('style',plugins_url('/jf-style.css', __FILE__));
}

/*
 * generate setting form
 */
function jf_plugin_options(){
?>
<form method="post" action="options.php">
    <?php
            settings_fields('jf_follow');
    ?>
    <table class="form-table">
        <h3 class="title"><?php _e("Administrator Settings",'jf-follow-button'); ?></h3>
        <table class="form-table admintbl">
            <tr valign="top">
                <th scope="row"><?php _e("JustFollow ID (Required):", 'jf-follow-button' ); ?></th>
                <td><input type="text" size="35" name="jf_follow_id" value="<?php echo get_option('jf_follow_id'); ?>" required="true"/></td>
            </tr>
        </table>
        <div class="submitform">
            <input type="submit" name="Submit"  class="button1" value="<?php _e('Save Changes', 'jf-follow-button') ?>" />
        </div>
    
    </form>
<p><strong><?php _e("You can also use", 'jf-follow-button' ); ?> [follow] shortcode <?php _e("for showing follow button on a page.", 'jf-follow-button' ); ?><strong> </p> 
<?php
}
jf_follow_init();

/*
 * generate shortcode of plugin
 */
function jf_follow_short() {
    global $jf_follow_settings;

    $purl = get_permalink();
    $button = "\n<div id='followme' data-layout='button'>\n";
    $url = urlencode($purl);
    $separator = '&amp;';
    $url = $url . $separator . 'id='  . $jf_follow_settings['id'];  
    $button .= '<iframe src="http://?href='.$url.'" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden;"></iframe>';
    $button .= "\n</div>\n";
		
    return $button;
}
add_shortcode( 'follow', 'jf_follow_short' );

/*
 * register a plugin to add button
 */
function jf_button_init() {
       //Add a callback to regiser our tinymce plugin 
    add_filter("mce_external_plugins", "jf_register_tinymce_plugin"); 
      // Add a callback to add our button to the TinyMCE toolbar
    add_filter('mce_buttons', 'jf_add_tinymce_button');
}

/*
 * This callback registers our plug-in
 */
function jf_register_tinymce_plugin($plugin_array) {
    $plugin_array['jf_button'] = plugins_url('/shortcode.js', __FILE__);
    return $plugin_array;
}

/*
 * This callback adds our button to the toolbar
 */
function jf_add_tinymce_button($buttons) {
            //Add the button ID to the $button array
    $buttons[] = "jf_button";
    return $buttons;
}
?>

