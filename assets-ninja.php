<?php
/*
 * Plugin Name:       Assets Ninja
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            John Smith
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ans
 * Domain Path:       /languages
*/
    define("ASSETS_DIR_PATH", plugin_dir_url(__FILE__)."assets/");
    define("ASSETS_PUBLIC_DIR_PATH", plugin_dir_url(__FILE__)."assets/public/");
    define("ASSETS_ADMIN_DIR_PATH", plugin_dir_url(__FILE__)."assets/admin/");

    class AssetsNinja {
        function __construct(){
            add_action("plugin_loaded", array($this, "load_textdomain"));
            add_action("wp_enqueue_scripts", array($this, "public_assets"));
            add_action("admin_enqueue_scripts", array($this, "admin_assets"));
            add_shortcode("bgmedia", array($this, "shortcode_for_bgmedia"));
        }
        function shortcode_for_bgmedia($attributes){
            
            $shortcode_output = <<<EOD
                <div class="bgmedia">
                
                </div>
EOD;
            return $shortcode_output;
        }
        function load_textdomain(){
            load_plugin_textdomain( "ans", false , plugin_dir_url(__FILE__)."languages" );
        }
        function public_assets(){
           
            $js_files = [
                "ans-main-js" => array("path" => ASSETS_PUBLIC_DIR_PATH."js/main.js", "dep" =>  array("jquery")),
                "ans-another-js" => array("path"=> ASSETS_PUBLIC_DIR_PATH."js/another.js", "dep" => array("jquery"))
            ];
            // foreach($js_files as $domain => $file_info){
            //     wp_enqueue_script($domain, $file_info["path"], $file_info["dep"], time(), true);
            // }
            $site_info = [
                "name" => "Lwhh",
                "url" => "lwhh.com",
            ];
            wp_localize_script("ans-main-js", "info", $site_info);
            
            wp_enqueue_style("ans-media-css", ASSETS_PUBLIC_DIR_PATH."css/media.css", null, time());
            $image_src = wp_get_attachment_image_src(7);
            $data = <<<EOD
                .bgmedia{
                    background-image: url({$image_src[0]});
                }
EOD;
            wp_add_inline_style( "ans-media-css", $data );
        }
        function admin_assets($hook){
            $screen = get_current_screen();
            if("edit.php" == $hook && "page" == $screen->post_type){
                wp_enqueue_script("ans-admin-js", ASSETS_ADMIN_DIR_PATH."js/admin.js", null, time(), true);
            }
        }

    }

    new AssetsNinja();




?>