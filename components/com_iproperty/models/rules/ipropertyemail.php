<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');

require(JPATH_SITE.'/components/com_contact/models/rules/contactemail.php');

/**
 * JFormRule for com_iproperty to make sure the E-Mail adress is not blocked.
 * Uses banned email list from the com_contact configuration - extend here if needed
 *
 * @package     Joomla.Site
 * @subpackage  com_iproperty
 */
class JFormRuleIpropertyEmail extends JFormRuleContactEmail
{
}