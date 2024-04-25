<?php

/**
 * This class defines functions to add admin messages.
 *
 * @since      1.0.0
 * @package    clientname
 * @author     Leesa Ward
 */
class ClientName_Admin_Notices {

	public function __construct() {
		add_action('admin_notices', array($this, 'required_plugins_notification'));
	}

	/**
	 * The admin notice for if required plugins are missing
	 * @wp-hook
	 *
	 * @return void
	 */
	function required_plugins_notification(): void {
		$warnings = array();
		if (!is_plugin_active('doublee-base-plugin/doublee.php')) {
			$warnings[] = 'Double-E Design base plugin';
		}

		if (count($warnings) > 0) {
			echo '<div class="notice notice-error">';
			echo '<p>The ' . Doublee::get_name() . ' plugin requires the following plugins to be installed and activated for full functionality. Without them, some features may be missing or not work as expected.</p>';
			echo '<ul>';
			foreach ($warnings as $warning) {
				echo '<li>' . $warning . '</li>';
			}
			echo '</ul>';
			echo '</div>';
		}
	}
}
