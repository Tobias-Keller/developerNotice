<?php
/**
 * Created by PhpStorm.
 * User: Tobias Keller
 * Date: 19.05.2018
 * Time: 14:52
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class bcisDeveloperFrontend {
	public $errorCount = "0";
	public $errorCodes = array();


	function __construct() {
		// Set error handler
		set_error_handler(array($this, 'wp_error_handler'));
		// Load in Frontend
		add_action( 'wp_enqueue_scripts', array( $this, 'addStyleSheets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'addJavaScript' ) );
		add_action( 'wp_footer', array( $this, 'printTemplate' ) );

		// Load in Backend
		if (is_admin()) {
			add_action( 'admin_enqueue_scripts', array( $this, 'addStyleSheets' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'addJavaScript' ) );
			add_action( "admin_footer", array( $this, 'printTemplate' ) );
		}
	}
	/*
    * Add JavaScript
	 * */
	function addJavaScript() {
		if (get_option('bcisOnlyAdmin') == 'true' AND !current_user_can('administrator')){
			return;
		}
		else {
			wp_enqueue_script( 'developer-bcis', plugins_url( 'js/main.js',  dirname(__FILE__) ), array(), '1.0.0', true );
			wp_localize_script( 'developer-bcis', 'developePlugin',
				array( 'mainUrl' => get_site_url() ) );
		}
	}

	/*
	 * Add Stylesheets
	 * */
	function addStyleSheets() {
		if (get_option('bcisOnlyAdmin') == 'true' AND !current_user_can('administrator')){
			return;
		}
		else {
			wp_enqueue_style( 'developer-bcis-css', plugins_url( 'css/dev_main.css',  dirname(__FILE__) ) );
		}
	}

	/*
	 * Print Template
	 * */
	function printTemplate(){
		if (get_option('bcisOnlyAdmin') == 'True' AND !current_user_can('administrator')){
			return;
		}
		else {
			$siteInformation = new bcisSiteInformation();

			// Output Main Bar
			echo '
			<div id="dev_plugin" style="background-color: ' . get_option('bcisBarColor') .';">
				<span class="developerVersion">Theme-Version: ' . $siteInformation->getThemeVersion() . '</span>
				<span class="developerVersion">PHP-Version: ' . $siteInformation->getPhpVersion() . '</span>
				<span class="developerVersion">MYSQL-Version: ' . $siteInformation->getMySqlVersion() . '</span>
				<span class="developerVersion">Page-Load: ' . $siteInformation->getPageLoad() . ' Sec.</span>
				<span class="developerVersion">Memory-Usage: ' . $siteInformation->getMemoryUsage() . '/' . $siteInformation->getMemoryLimit() . '</span>
				<span class="developerVersion event" onclick="toggleDisplay(\'developerQueries\')">' . count( $siteInformation->getSiteQueries() ) . ' Queries</span>
				<span class="developerVersion event" onclick="toggleDisplay(\'developerPHPErrors\')">' . $this->errorCount . ' PHP-Errors</span>
        		' . $siteInformation->getSiteTitle() . ' Developer environment
             ';

             // Output Unicorn
             if (get_option('bcisShowImage') == 'True') { 
             	echo '<img id="dev_img" src="' . plugins_url( "developerNotice/img/unicorn.png" ) . '"/> </div>';
             }
             else {
             	echo '</div>';
             }


			// Shows all WP Page queries
			_e('<div class="developerQueries" id="developerQueries">');
			printf(
				esc_html__('Queries: %1$s | Query Totals: %2$s', 'bcis-xcron'),
				count( $siteInformation->getSiteQueries()),
				$siteInformation->getQueryTotalTime()
			);
			?>
			<h3><?php esc_html_e('All Page Queries:', 'bcis-xcron');?></h3>
			<?php
			_e('<pre>');
			print_r( $siteInformation->getSiteQueries() );
			_e('</pre></div>');

			_e("<div class=\"developerQueries\" id=\"developerPHPErrors\"><pre><h3>All PHP-Errors</h3>");
			if ($this->errorCount > 0){
				print_r( $this->errorCodes );
            } else {
			    _e('No errors found', 'bcis-xcron');
            }
			_e("</pre></div>");


			// if wordpress debug is off
			if ( WP_DEBUG_DISPLAY == false OR WP_DEBUG == false ) {
				_e('<div class="developerQueries" id="developerPHPErrors"><pre>');
				if ( WP_DEBUG == false ) {
				?>
					<h3><?php esc_html_e('WP_DEBUG is not active', 'bcis-xcron'); ?></h3>
				<?php
				}
				if ( WP_DEBUG_DISPLAY == false ) {
					?>
					<h3><?php esc_html_e('WP_DEBUG_DISPLAY is not active', 'bcis-xcron'); ?></h3>
					<?php
				}
				esc_html_e('You can activate it in your wp-config.php file', 'bcis-xcron');
				_e('<br>');
				esc_html_e('Example:\\n define(\'WP_DEBUG\', true);\\n define(\'WP_DEBUG_DISPLAY\', true);\\n define( \'WP_DEBUG_LOG\', true );', 'bcis-xcron');
				_e('</pre></div>');
			}
		}
	}

	/*
	 * Custom error handle
	 * */
	function wp_error_handler( $errno, $errstr, $errfile, $errline ) {
		if ( !(error_reporting() & $errno) ) {
			// This error code is not included in error_reporting
			return;
		}
		$err = '';
		if ( $errno === 1024 || $errno === 8 ) {
			$err .= '<b>Notice</b>';
		} elseif ( $errno === 512 || $errno === 2 ) {
			$err .= '<b>Warning</b>';
		} elseif ( $errno === 256 || $errno === 9191 ) {
			$err .= '<b>Error</b>';
		} elseif ( $errno === 4096) {
			$err .= '<b>Fatal Error</b>';
		} elseif ( $errno === 8192 || $errno === 16384) {
			$err .= '<b>Deprecated</b>';
		} else {
			$err .= '<b>Other</b>';
		}
		$err .= ': ' . $errstr . ' In <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
		$this->errorCount++;
		array_push($this->errorCodes, $err);
	}
}