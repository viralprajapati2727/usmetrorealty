<?php
/*------------------------------------------------------------------------
# mod_SocialLoginandSocialShare
# ------------------------------------------------------------------------
# author    LoginRadius inc.
# copyright Copyright (C) 2013 loginradius.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.loginradius.com
# Technical Support:  Forum - http://community.loginradius.com/
-------------------------------------------------------------------------*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
// Include the syndicate functions only once
require_once dirname(__FILE__).'/helper.php';
$params->def('greeting', 1);
$type = modSocialLoginAndSocialShareHelper::getType();
$lr_settings = modSocialLoginAndSocialShareHelper::sociallogin_getSettings();
$sociallogin = modSocialLoginAndSocialShareHelper::social_url($lr_settings);
$return = modSocialLoginAndSocialShareHelper::getReturnURL($params, $type);
$user = JFactory::getUser();
$userId     = $user->get('id');
$app	= JFactory::getApplication();
$db = JFactory::getDbo();
 
// Create a new query object.
$query = $db->getQuery(true);
 
// Select all records from the user profile table where key begins with "custom.".
// Order it by the ordering field.
$query->select($db->quoteName('icon'));
$query->from($db->quoteName('#__iproperty_agents'));
$query->where($db->quoteName('user_id') . ' = '. $userId);
$query->order('ordering ASC');
 
// Reset the query using our newly populated query object.
$db->setQuery($query);
 
// Load the results as a list of stdClass objects (see later for more options on retrieving data).
$agent_icon = $db->loadResult();
require JModuleHelper::getLayoutPath('mod_socialloginandsocialshare', $params->get('layout', 'default'));