<?php
/*
# ------------------------------------------------------------------------
# @copyright   Copyright (C) 2014. All rights reserved.
# @license     GNU General Public License version 2 or later
# Author:      Glenn Arkell
# Websites:    https://www.glennarkell.com.au
# ------------------------------------------------------------------------
*/
// no direct access
defined('_JEXEC') or die( 'Restricted access' );
require_once( dirname(__FILE__).'/helper.php' );
$newsItems = modGlennsNewslettersHelper::getNewsletters( $params );
require( JModuleHelper::getLayoutPath( 'mod_glennsnewsletters' ) );
?>