<?php

/**
 * Plugin Name:       Customer Feedback
 * Plugin URI:        https://github.com/helsingborg-stad/Customer-feedback
 * Description:       Puts a customer feedback form on each page
 * Version:           1.0.0
 * Author:            Kristoffer Svanmark
 * Author URI:        https://github.com/helsingborg-stad
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       customer-feedback
 * Domain Path:       /languages
 */

 // Protect agains direct file access
if (! defined('WPINC')) {
    die;
}

define('CUSTOMERFEEDBACK_PATH', plugin_dir_path(__FILE__));
define('CUSTOMERFEEDBACK_URL', plugins_url('', __FILE__));
define('CUSTOMERFEEDBACK_TEMPLATE_PATH', CUSTOMERFEEDBACK_PATH . 'templates/');

add_action('plugins_loaded', function () {
    load_plugin_textdomain('customer-feedback', false, plugin_basename(dirname(__FILE__)) . '/languages');
});

require_once CUSTOMERFEEDBACK_PATH . 'source/php/Vendor/Psr4ClassLoader.php';
require_once CUSTOMERFEEDBACK_PATH . 'Public.php';

// Instantiate and register the autoloader
$loader = new CustomerFeedbackChat\Vendor\Psr4ClassLoader();
$loader->addPrefix('CustomerFeedback', CUSTOMERFEEDBACK_PATH);
$loader->addPrefix('CustomerFeedback', CUSTOMERFEEDBACK_PATH . 'source/php/');
$loader->register();

// Acf auto import and export
add_action('plugins_loaded', function () {
    $acfExportManager = new \AcfExportManager\AcfExportManager();
    $acfExportManager->setTextdomain('customer-feedback');
    $acfExportManager->setExportFolder(CUSTOMERFEEDBACK_PATH . 'source/php/AcfFields/');
    $acfExportManager->autoExport(array(
        'customer-feedback' => 'group_5729fc6e03367',
        'customer-feedback-settings' => 'group_59118fb9c53de'
    ));
    $acfExportManager->import();
});

// Start application
new CustomerFeedback\App();
