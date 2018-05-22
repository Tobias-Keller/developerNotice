<?php
/**
 * Created by PhpStorm.
 * User: Tobias Keller
 * Date: 19.05.2018
 * Time: 15:10
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function registerOptions(){
	add_option( 'bcisOnlyAdmin', 'True', '', 'yes' );
	add_option( 'bcisShowImage', 'True', '', 'yes' );
	add_option( 'bcisBarColor', '#000000', '', 'yes' );
}

function unregisterOptions(){
	delete_option( 'bcisOnlyAdmin' );
	delete_option( 'bcisShowImage' );
	delete_option( 'bcisBarColor' );
}