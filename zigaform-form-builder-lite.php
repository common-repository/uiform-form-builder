<?php
/*
 * Plugin Name: Zigaform Form Builder Lite
 * Plugin URI: http://wordpress-form-builder.zigaform.com/
 * Description: Zigaform Form builder makes you build forms in few steps. <strong>(Lite Edition)</strong>
 * Version: 2.9.5
 * Author: SoftDiscover
 * Author URI: http://www.softdiscover.com
 */

if (!defined('ABSPATH')) {
    die('Access denied.');
}
if (!class_exists('UiformFormbuilder_Express')) {

    final class UiformFormbuilder_Express {

        /**
         * The only instance of the class
         *
         * @var RocketForm
         * @since 1.0
         */
        private static $instance;

        /**
         * The Plug-in version.
         *
         * @var string
         * @since 1.0
         */
        public $version = '2.9.5';

        /**
         * The minimal required version of WordPress for this plug-in to function correctly.
         *
         * @var string
         * @since 1.0
         */
        public $wp_version = '3.6';

        /**
         * The minimal required version of WordPress for this plug-in to function correctly.
         *
         * @var string
         * @since 1.0
         */
        public $php_version = '5.3';

        /**
         * Class name
         *
         * @var string
         * @since 1.0
         */
        public $class_name;

        /**
         * An array of defined constants names
         *
         * @var array
         * @since 1.0
         */
        public $defined_constants;

        /**
         * Create a new instance of the main class
         *
         * @since 1.0
         * @static
         * @return RocketForm
         */
        public static function instance() 
        {
            $class_name = get_class();
            if (!isset(self::$instance) && !( self::$instance instanceof $class_name )) {
                self::$instance = new $class_name;
            }

            return self::$instance;
        }

        public function __construct() 
        {
            // Save the class name for later use
            $this->class_name = get_class();
             //
            //  Plug-in requirements
            //
            if (!$this->check_requirements()) {
                add_action('admin_notices', array(&$this, 'uiform_requirements_error'));
                return;
            }
            
            //
            // Declare constants and load dependencies
            //
            $this->define_constants();
            $this->load_dependencies();
            $this->check_updateChanges();
            try {

                if (class_exists('Uiform_Bootstrap')) {
                    $GLOBALS['wprockf'] = Uiform_Bootstrap::get_instance();
                    register_activation_hook(__FILE__, array($GLOBALS['wprockf'], 'activate'));
                    register_deactivation_hook(__FILE__, array($GLOBALS['wprockf'], 'deactivate'));
                }
            } catch (exception $e) {
                $error = $e->getMessage() . "\n";
                echo $error;
            }
        }

       
        /**
        * check_requirements()
        * Checks that the WordPress setup meets the plugin requirements
        * 
        * @return boolean
        */
        private function check_requirements() {
            global $wp_version;
            if (!version_compare($wp_version, $this->wp_version, '>=')) {
                add_action('admin_notices', array(&$this, 'display_req_notice'));

                return false;
            }

            if (version_compare(PHP_VERSION, $this->php_version, '<')) {
                return false;
            }
           
            return true;
        }

        public function uiform_requirements_error() {
            global $wp_version;
            require_once dirname(__FILE__) . '/views/requirements-error.php';
        }

        /**
         * Define constants needed across the plug-in.
         */
        private function define_constants() {
            $this->define('UIFORM_FILE', __FILE__);
            $this->define('UIFORM_FOLDER', plugin_basename(dirname(__FILE__)));
            $this->define('UIFORM_BASENAME', plugin_basename(__FILE__));
            $this->define('UIFORM_ADMINPATH', get_admin_url());
            $this->define('UIFORM_APP_NAME', "Uiform - Wordpress Form Builder Express");
            $this->define('UIFORM_VERSION', $this->version);
            $this->define('UIFORM_FORMS_DIR', dirname(__FILE__));
            $this->define('UIFORM_FORMS_URL', plugins_url() . '/'.UIFORM_FOLDER);
            $this->define('UIFORM_FORMS_LIBS', UIFORM_FORMS_DIR . '/libraries');
            $this->define('UIFORM_DEBUG', 0);
            if (UIFORM_DEBUG == 1) {
                error_reporting(E_ALL);
                ini_set('display_errors', 1);
            }
        }

        /**
         * Define constant if not already set
         * @param  string $name
         * @param  string|bool $value
         */
        private function define($name, $value) 
        {
            if (!defined($name)) {
                define($name, $value);
                $this->defined_constants[] = $name;
            }
        }

        /**
         * Loads PHP files that required by the plug-in
         */
        private function load_dependencies() {
            // Admin Panel
            if (is_admin()) {
                require_once UIFORM_FORMS_DIR . '/classes/uiform-base-module.php';
                require_once UIFORM_FORMS_DIR . '/classes/uiform-form-helper.php';
                require_once UIFORM_FORMS_DIR . '/classes/uiform-bootstrap.php';
                include UIFORM_FORMS_DIR . '/helpers/styles-font-menu/plugin.php';
            }

            // Front-End Site
            if (!is_admin()) {
                require_once UIFORM_FORMS_DIR . '/classes/uiform-base-module.php';
                require_once UIFORM_FORMS_DIR . '/classes/uiform-form-helper.php';
                require_once UIFORM_FORMS_DIR . '/classes/uiform-bootstrap.php';
            }
        }
        
        /**
         * Loads PHP files that required by the plug-in
         */
        private function check_updateChanges() {
            global $wpdb;
            $version=UIFORM_VERSION;
            $install_ver = get_option("uifmfbuild_version");
             
            if (!$install_ver || version_compare($version,$install_ver, '>')) {
                
                if (!$install_ver || version_compare($install_ver,"1.7.3.6", '<')) {
                    $tbname = $wpdb->prefix . "uiform_form_records";
                    
                    if ((string)$wpdb->get_var("SHOW TABLES LIKE '$tbname'") === $tbname) {
                        $row = $wpdb->get_var("SHOW COLUMNS FROM " . $tbname . " LIKE 'fbh_data_rec'");
                        if (empty($row)) {
                            $sql = "ALTER TABLE " . $tbname . " ADD  fbh_data_rec longtext NOT NULL;";
                            $wpdb->query($sql);
                        }

                        $row = $wpdb->get_var("SHOW COLUMNS FROM " . $tbname . " LIKE 'fbh_data_xml'");
                        if (!empty($row)) {
                            $sql = "ALTER TABLE " . $tbname . " CHANGE fbh_data_xml fbh_data_rec_xml longtext;";
                        $wpdb->query($sql);
                        }
                    }
                     
                }
                 update_option("uifmfbuild_version", $version);
            }
            
            
        }

    }

}

function uiform_uninstall()
{
   require_once( UIFORM_FORMS_DIR . '/classes/uiform-installdb.php');
   $installdb = new Uiform_InstallDB();
   $installdb->uninstall();
   return true;
}

function wpRFRM_Express() {
    register_uninstall_hook(__FILE__, 'uiform_uninstall');
    return UiformFormbuilder_Express::instance();
}

wpRFRM_Express();
?>
