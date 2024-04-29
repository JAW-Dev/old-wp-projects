<?php
/**
 * IntegrateCrms
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Crms
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

use FP_Core\Initter;
use FP_Core\Crms\Redtail\RedtailUserIntegration;
use FP_Core\Crms\Wealthbox\WealthboxUserIntegration;
use FP_Core\Crms\SalesForce\SalesforceUserIntegration;
use FP_Core\Crms\XLR8\XLR8UserIntegration;
use FP_Core\Crms\UserIntegrationsList;

$wealthbox_integration  = new WealthboxUserIntegration();
$salesforce_integration = new SalesforceUserIntegration();
$xlr8_integration       = new XLR8UserIntegration();
$redtail_integration    = new RedtailUserIntegration();

$user_integrations = array(
	$wealthbox_integration,
	$salesforce_integration,
	$xlr8_integration,
	$redtail_integration,
);

$inittables = array(
	$wealthbox_integration,
	$salesforce_integration,
	$xlr8_integration,
	$redtail_integration, // must go after the $wealthbox_integration
	new UserIntegrationsList( ...$user_integrations ),
);

$initter = new Initter( ...$inittables );

add_action( 'plugins_loaded', array( $initter, 'init' ) );
