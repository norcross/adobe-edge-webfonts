<?php
/*
Plugin Name: Adobe Edge WebFonts
Plugin URI: http://andrewnorcross.com/plugins/
Description: 
Version: 0.1
Author: norcross
Author URI: http://andrewnorcross.com
License: GPL v2

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA


  
*/

// Start up the engine 
class AdobeEdgeFonts
{
    /**
     * Static property to hold our singleton instance
     * @var AdobeEdgeFonts
     */
    static $instance = false;


    /**
     * This is our constructor, which is private to force the use of
     * getInstance() to make this a Singleton
     *
     * @return AdobeEdgeFonts
     */
    private function __construct() {
        add_action      ( 'admin_init',             array(&$this, 'reg_settings'    ) );
        add_action      ( 'admin_menu',             array(&$this, 'menu_settings'   ) );
        add_action      ( 'admin_enqueue_scripts',  array(&$this, 'admin_scripts'   ), 10);
        add_action      ( 'wp_ajax_key_change',     array(&$this, 'font_save'       ) );
        add_action      ( 'wp_enqueue_scripts',     array(&$this, 'font_scripts'    ), 999);
        add_action      ( 'wp_head',                array(&$this, 'font_apply'      ) );

    }

    /**
     * If an instance exists, this returns it.  If not, it creates one and
     * retuns it.
     *
     * @return AdobeEdgeFonts
     */
     
    public static function getInstance() {
        if ( !self::$instance )
            self::$instance = new self;
        return self::$instance;
    }

    /**
     * Get plugin version to load in script
     *
     * @return AdobeEdgeFonts
     */

    public function plugin_version() {
        if ( ! function_exists( 'get_plugins' ) )
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        $plugin_folder = get_plugins( '/' . plugin_basename( dirname( __FILE__ ) ) );
        $plugin_file = basename( ( __FILE__ ) );
        return $plugin_folder[$plugin_file]['Version'];
    }


    /**
     * Register settings
     *
     * @return AdobeEdgeFonts
     */


    public function reg_settings() {
        register_setting( 'aef-settings', 'aef-settings');        

    }

    /**
     * Load some CSS
     *
     * @return AdobeEdgeFonts
     */


    public function font_apply() {
        if (is_admin() )
            return;
        ?>

        <style media="screen" type="text/css">
            h1, h2, h3 {font-family: acme, serif;}
        </style>

    <?php
    }

    /**
     * run AJAX saving of options
     *
     * @return AdobeEdgeFonts
     */

    public function font_save() {
/*
        // get keys from POST
        $key_kill  = (isset($_POST['key_kill']) && $_POST['key_kill'] !== '' ? $_POST['key_kill'] : false);

        // set up return array for ajax responses
        $ret = array();

        // missing keys? bail

        if($key_kill === false) {
            $ret['success'] = false;
            $ret['process'] = 'NO_KEY_ENTERED';
            $ret['message'] = 'You must enter a value in this field';
            echo json_encode($ret);
            die();
        }

        // so we have some keys. LETS DO THIS
        $args = array(
            'numberposts'     => -1,
            'meta_key'        => $key_kill,
            );
                
        $keys_array = get_posts( $args );

        $key_count  = count($keys_array);
        $key_exist  = ($key_count) > 0 ? true : false;

        // check count and process accordingly
        if($key_exist === true) {
            
            foreach ($keys_array as $key_post) {
                delete_post_meta($key_post->ID, $key_kill);
            }
            
            $ret['success'] = true;
            $ret['message'] = $key_count.' entries have been deleted.';
            echo json_encode($ret);
            die();

        } else {
            
            $ret['success'] = false;
            $ret['process'] = 'NO_KEYS_FOUND';
            $ret['message'] = 'There were no posts with the <strong><em>'.$key_kill.'</em></strong> meta key found. Please try again.';
            echo json_encode($ret);
            die();      
        
        }

*/

    }

    /**
     * Admin scripts and stylesheets
     *
     * @return AdobeEdgeFonts
     */

    public function admin_scripts($hook) {
        
        $version = $this->plugin_version();

        $current_screen = get_current_screen();
        
        if ( 'appearance_page_aef-settings' == $current_screen->base ) {
            wp_enqueue_style( 'chosen', plugins_url('/lib/css/chosen.css', __FILE__), array(), $version, 'all' );
            
            wp_enqueue_script( 'chosen', plugins_url('/lib/js/chosen.jquery.min.js', __FILE__) , array('jquery'), $version, true );
            wp_enqueue_script( 'admin-init', plugins_url('/lib/js/aef.admin.js', __FILE__) , array('jquery'), $version, true );
        }

    }

    /**
     * load font scripts
     *
     * @return AdobeEdgeFonts
     */

    public function font_scripts() {

        $options    = get_option('aef-settings');

        // no options set? bail
        if (!isset($options) || empty($options))
            return;

        foreach ($options as $font)
            wp_enqueue_script( $font, 'http://use.edgefonts.net/'.$font.'.js' , array('jquery'), null, false );

    }

    /**
     * build out settings page and meta boxes
     *
     * @return AdobeEdgeFonts
     */

    public function menu_settings() {
        add_submenu_page('themes.php', 'Adobe Edge Fonts', 'Adobe Edge Fonts', 'manage_options', 'aef-settings', array( $this, 'aef_display' ));
    }

    /**
     * create array of available fonts
     *
     * @return AdobeEdgeFonts
     */

    public function fonts() {
        // http://peterchondesign.com/adobe-webfonts.html
        $fonts = array (
            'abril-fatface'     => 'Abril Fatface',
            'acme'              => 'Acme',
            'alegreya-sc'       => 'Alegreya SC'
            );

        return $fonts;
    }

    /**
     * Display main options page structure
     *
     * @return AdobeEdgeFonts
     */
     
    public function aef_display() {     
        if (!current_user_can('manage_options') )
            return;
        ?>
    
        <div class="wrap">
            <div id="icon-faq-admin" class="icon32"><br /></div>
            <h2><?php _e('Adobe Edge WebFonts') ?></h2>

            <?php
            if ( isset( $_GET['settings-updated'] ) )
                echo '<div id="message" class="updated below-h2"><p>Font selections updated successfully.</p></div>';
            ?>

            <div id="poststuff" class="metabox-holder has-right-sidebar">

            <?php
            echo $this->settings_side();
            echo $this->settings_open();
            ?>
                
            <form method="post" action="options.php" class="aef-settings-form">

                <?php
                settings_fields( 'aef-settings' );
                $options    = get_option('aef-settings');
                $fonts      = $this->fonts();
                ?>


                <h2 class="inst-title"><?php _e('Available Fonts') ?></h2>
                <p class="font-choice">
                    <select name="aef-settings[]" class="font-choices chzn-select" style="width:350px;" multiple data-placeholder="Select a font...">
                    <?php
                        foreach ($fonts as $name => $display)
                        echo '<option id="'.$name.'" value="'.$name.'">'.$display.'</option>';
                    ?>
                    </select>
                    <label type="select" for="aef-settings[]"><?php _e('Select A Font') ?></label>
                </p>         

                <!-- submit -->
                <p id="aef-submit" class="font-submit"><input type="submit" class="button-primary font-submit" value="<?php _e('Save Changes') ?>" /></p>

                </form>

                <div id="aef-selected">
                   <?php echo preprint($options); ?> 
                </div>

    <?php echo $this->settings_close(); ?>

    </div>
    </div>   
    
    <?php }

    /**
     * Some extra stuff for the settings page
     *
     * this is just to keep the area cleaner 
     *
     * @return AdobeEdgeFonts
     */

    public function settings_side() { ?>

        <div id="side-info-column" class="inner-sidebar">
            <div class="meta-box-sortables">
                <div id="faq-admin-about" class="postbox">
                    <h3 class="hndle" id="about-sidebar"><?php _e('About the Plugin:') ?></h3>
                    <div class="inside">
                        <p>Talk to <a href="http://twitter.com/norcross" target="_blank">@norcross</a> on twitter or visit the <a href="http://wordpress.org/support/plugin/wordpress-faq-manager/" target="_blank">plugin support form</a> for bugs or feature requests.</p>
                        <p><?php _e('<strong>Enjoy the plugin?</strong>') ?><br />
                        <a href="http://twitter.com/?status=I'm using @norcross's WordPress FAQ Manager plugin - check it out! http://l.norc.co/wpfaq/" target="_blank"><?php _e('Tweet about it') ?></a> <?php _e('and consider donating.') ?></p>
                        <p><?php _e('<strong>Donate:</strong> A lot of hard work goes into building plugins - support your open source developers. Include your twitter username and I\'ll send you a shout out for your generosity. Thank you!') ?><br />
                        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                        <input type="hidden" name="cmd" value="_s-xclick">
                        <input type="hidden" name="hosted_button_id" value="11085100">
                        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                        <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                        </form></p>
                    </div>
                </div>
            </div>
            
            <div class="meta-box-sortables">
                <div id="faq-admin-more" class="postbox">
                    <h3 class="hndle" id="about-sidebar"><?php _e('Links:') ?></h3>
                    <div class="inside">
                        <ul>
<!--
                        <li><a href="http://wordpress.org/extend/plugins/wordpress-faq-manager/" target="_blank">Plugin on WP.org</a></li>
                        <li><a href="https://github.com/norcross/WordPress-FAQ-Manager" target="_blank">Plugin on GitHub</a></li>
                        <li><a href="http://wordpress.org/support/plugin/wordpress-faq-manager" target="_blank">Support Forum</a><li>
-->                            
                        </ul>
                    </div>
                </div>
            </div>
        </div> <!-- // #side-info-column .inner-sidebar -->

    <?php }

    public function settings_open() { ?>

        <div id="post-body" class="has-sidebar">
            <div id="post-body-content" class="has-sidebar-content">
                <div id="normal-sortables" class="meta-box-sortables">
                    <div id="about" class="postbox">
                        <div class="inside">

    <?php }

    public function settings_close() { ?>

                        <br class="clear" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php }         

/// end class
}


// Instantiate our class
$AdobeEdgeFonts = AdobeEdgeFonts::getInstance();
