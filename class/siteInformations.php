<?php
/**
 * Created by PhpStorm.
 * User: Tobias Keller
 * Date: 19.05.2018
 * Time: 14:50
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class bcisSiteInformation {

	function getPageLoad() {
		return timer_stop(0);
	}

	function getSiteQueries() {
		global $wpdb;
		return $wpdb->queries;
	}

	function getQueryTotalTime() {
		$totalQuerieSize = "0";
		foreach ($this->getSiteQueries() as $querie) {
			$totalQuerieSize = $totalQuerieSize + $querie[1];
		}
		return $totalQuerieSize;
	}

	function getThemeVersion() {
		$activeTheme = wp_get_theme();
		return $activeTheme->get('Version');
	}

	function getMemoryLimit() {
		return WP_MEMORY_LIMIT;
	}

	function getMemoryUsage() {
		return round(memory_get_peak_usage() / (1024*1024), 1);
	}

	function getPhpVersion() {
		return (float)phpversion();
	}

	function getMySqlVersion() {
		global $wpdb;
		return $wpdb->db_version();
	}

	function getSiteTitle() {
		return get_bloginfo( 'name' );
	}

}