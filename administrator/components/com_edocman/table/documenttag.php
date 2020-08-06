<?php
/**
 * @version        1.9.7
 * @package        Joomla
 * @subpackage     Edocman
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2011 - 2018 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

// No direct access
defined('_JEXEC') or die();

class EDocmanTableDocumentTag extends JTable
{

	/**
	 * Constructor
	 *
	 * @param
	 *            JDatabase A database connector object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__edocman_document_tags', 'id', $db);
	}
}
