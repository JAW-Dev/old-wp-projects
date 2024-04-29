<?php
/**
 * OAuth Controller
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Crms/XLR8
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Crms\XLR8;

use FP_Core\Crms\SalesForce\OAuthBase;

class OAuthController extends OAuthBase {
	/**
	 * CRM Slug
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var string
	 */
	protected $crm_slug = 'xlr8';
}
