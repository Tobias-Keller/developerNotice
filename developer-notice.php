<?php

/*
Plugin Name: Developer Notice
Plugin URI: https://tobier.de
Description: Shows information for developer
Version: 1.0
Author: Tobias Keller
Author URI: https://tobier.de
Text Domain: bcis-xcron
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) exit;

/*
 * Load classes
 * */
require_once ('class/developerPluginMaintenance.php');
require_once ('class/DeveloperFrontend.php');
require_once ('class/siteInformations.php');
require_once ('class/bcisDeveloperSettings.php');

/*
 * Set activation hook
 * Set uninstall hook
 * */
register_activation_hook( __FILE__, 'registerOptions' );
register_uninstall_hook( __FILE__, 'unregisterOptions');

$developerNotice = new bcisDeveloperMainClass();

class BcisDeveloperMainClass {

	/*
	 * Plugin constructor
	 * */
	function __construct() {
		$this->prepareWordPress();

		$developerFrontend = new bcisDeveloperFrontend();
		$settingsPage = new bcisDeveloperSettings();

		// Create admin page and menu items
		add_action( 'admin_bar_menu', array( $this, 'DeveloperSettings' ), 100 );

	}

	/*
	 * Create menu link in admin bar
	 * */
	function DeveloperSettings($adminBar) {
		$adminBar->add_menu( array(
			'id'        => 'developerSettings',
			'title'     => 'Developer Settings',
			'parent'    => 'top-secondary',
			'href'      => get_site_url() . '/wp-admin/tools.php?page=settingsPage',
			'meta'      => array(
				'title' => __( 'Developer Settings' ),
			),
		) );
	}

	/*
	 * prepare WordPress
	 * set WordPress savequeries const
	 * */
	function prepareWordPress() {
		// disable mySQL Session Cache
		if ( !defined( 'QUERY_CACHE_TYPE_OFF' ) )
		{
			define( 'QUERY_CACHE_TYPE_OFF', TRUE );
		}

		// enable save queries
		if ( !defined( 'SAVEQUERIES' ) )
		{
			define( 'SAVEQUERIES', TRUE );
		}
	}


}