<?php


namespace respek_nature\Inc\Api\Callbacks;

use respek_nature\Inc\Base\BaseController;
 /**
*  page template thingz!!!!
*/
class AdminCallbacks extends BaseController{

	public function adminDashboard(){
		return require_once( "$this->plugin_path/templates/admin/respek_settings.php" );
	}
}