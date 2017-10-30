<?php
/**
 * Plugin Name:     Ninja Split Testing aka A/B Testing
 * Plugin URI:      http://wpmanageninja.com/
 * Description:     A simple plugin to do A/B Testing
 * Author:          Team WPManageNinja
 * Author URI:      https://wpmanageninja.com
 * Version:         1.0
 * Text Domain:     ninja_ab
 * License:         GPL2+
 * License URI:     http://www.gnu.org/licenses/gpl-3.0.txt
 */

// Exit if accessed directly
use NinjaABClass\classes\Redirect;

if( !defined( 'ABSPATH' ) ) {
	die;
}
include 'autoload.php';

if(!class_exists('NinjaSplitTesting')) {
	class NinjaSplitTesting
	{
		public function boot() {
			$this->public_actions();
			$this->adminActions();
		}
		
		public function public_actions() {
			$redirectClass = new Redirect();
			add_action('template_redirect', array($redirectClass, 'checkAbTesting'));
			add_action('ninja_ab_before_redirect_to_selected_url', array($redirectClass, 'logRedirect'), 10, 2);
			add_action('ninja_ab_returning_user_visiting_page', array($redirectClass, 'redirectForReturningUser'), 10, 2);
		}
		
		public function adminActions() {
			
		}
	}
	
	$NinjaSplitTesting = new NinjaSplitTesting();
	$NinjaSplitTesting->boot();
}

// on activation
register_activation_hook( __FILE__, array( 'NinjaABClass\classes\PluginActivation', 'activate' ) );
