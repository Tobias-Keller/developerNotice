<?php
/**
 * Created by PhpStorm.
 * User: Tobias Keller
 * Date: 19.05.2018
 * Time: 22:22
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class bcisDeveloperSettings {

    function __construct() {
	    add_action( 'admin_menu', array( $this, 'addSubmenuEntry' ));
    }

	function addSubmenuEntry(){
		add_submenu_page(
			'tools.php',
			'Developer Settings',
			'Developer Settings',
			'manage_options',
			'settingsPage',
			array( $this, 'settingsPage' )
		);
	}

	function updateSettings($onlyAdmin, $unicorn, $background){
		wp_cache_delete ( 'alloptions', 'options' );
		update_option('bcisOnlyAdmin', $onlyAdmin, 'yes');
		update_option('bcisShowImage', $unicorn, 'yes');
		update_option('bcisBarColor', $background, 'yes');
	}

	function settingsPage(){
		if (isset($_POST['devsubmit'])) {
			$retrievedNonce = $_REQUEST['_wpnonce'];
			if (wp_verify_nonce($retrievedNonce, 'developerSettings' )) {
				$this->updateSettings( $_POST['onlyAdmin'], $_POST['unicorn'], $_POST['background'] );
			}
		}

		// Add color picker from wordpress core
		wp_enqueue_style( 'bcis-color-picker' );
		wp_enqueue_script( 'bcis-color-picker', plugins_url('js/color-picker.js', dirname(__FILE__) ), array( 'wp-color-picker' ), false, true );
		?>
		<div class="wrap">
			<h2><?php esc_html_e('Developer bar settings', 'bcis_xcron'); ?></h2>

			<form method="post" action="">
				<?php wp_nonce_field('developerSettings'); ?>
				<table class="form-table">

					<tr valign="top">
						<th scope="row" style="width: 300px !important;"><?php esc_html_e('Show developer bar only for admins?', 'bcis-xcron') ?></th>
						<td>
							<label>True</label>
							<input type="radio" name="onlyAdmin" value="True" <?php if(get_option('bcisOnlyAdmin') == 'True') { echo 'checked';}?> />
							<label>False</label>
							<input type="radio" name="onlyAdmin" value="False" <?php if(get_option('bcisOnlyAdmin') == 'False') { echo 'checked';}?> />
						</td>
					</tr>

					<tr valign="top">
						<th scope="row" style="width: 300px !important;"><?php esc_html_e('Show unicorn?', 'bcis-xcron') ?></th>
						<td>
							<label>True</label>
							<input type="radio" name="unicorn" value="True" <?php if(get_option('bcisShowImage') == 'True') { echo 'checked';}?> />
							<label>False</label>
							<input type="radio" name="unicorn" value="False" <?php if(get_option('bcisShowImage') == 'False') { echo 'checked';}?> />
						</td>
					</tr>

					<tr valign="top">
						<th scope="row" style="width: 300px !important;"><?php esc_html_e('Developer bar background-color', 'bcis-xcron') ?></th>
						<td><input type="text" class="bcis-color-field" name="background" value="<?php echo get_option('bcisBarColor'); ?>" /></td>
					</tr>

				</table>

				<p class="submit">
					<input type="submit" name="devsubmit" class="button-primary" value="<?php esc_html_e('Save Changes', 'bcis-xcron') ?>" />
				</p>

			</form>
		</div>
		<?php
	}
}