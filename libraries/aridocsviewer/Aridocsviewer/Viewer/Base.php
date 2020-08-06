<?php
/*
 * ARI Docs Viewer
 *
 * @package		ARI Docs Viewer
 * @version		2.0.0
 * @author		ARI Soft
 * @copyright	Copyright (c) 2010 www.ari-soft.com. All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
 * 
 */

namespace Aridocsviewer\Viewer;

defined('_JEXEC') or die;

abstract class Base 
{
	function processContent($params, $content)
	{
		return '';
	}
}