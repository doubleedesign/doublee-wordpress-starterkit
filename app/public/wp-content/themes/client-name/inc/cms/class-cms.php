<?php
require_once('class-admin.php');
require_once('class-media.php');

/**
 * The CMS-specific settings and customisations for the theme.
 * @since 2.0.0
 */
class ClientName_CMS {

	public function __construct() {
		new ClientName_Admin();
		new ClientName_Media();
	}
}
