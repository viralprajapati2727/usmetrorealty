<?php
/*
 * ARI Docs Viewer
 *
 * @package		ARI Docs Viewer
 * @version		1.0.0
 * @author		ARI Soft
 * @copyright	Copyright (c) 2009 www.ari-soft.com. All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
 * 
 */

defined('_JEXEC') or die('Restricted access');

class pkg_aridocsviewerInstallerScript
{
	function preflight($type, $parent)
	{
		$type = strtolower($type);
		if ($type == 'install' || $type == 'update')
		{
            if (!$this->isARISoftLibInstalled())
                $this->installARISoftLib();

            $baseDir = dirname(__FILE__);
            if (!$this->extractFiles($baseDir . '/shared/arisoft_lib.zip', JPATH_LIBRARIES . '/arisoft') ||
                !$this->extractFiles($baseDir . '/shared/arisoft_media.zip', JPATH_ROOT . '/media/arisoft'))
                return false;
		}
	}

	function postflight($type, $parent)
	{
		$type = strtolower($type);
		if ($type == 'install')
		{
            $this->enablePlugins();
		}
	}

    function enablePlugins()
    {
        $db = JFactory::getDBO();

        $db->setQuery(
            sprintf(
                'UPDATE #__extensions SET %1$s = 1 WHERE %2$s = \'plugin\' AND %3$s = \'aridocsviewer\' AND %4$s IN (\'editors-xtd\', \'system\')',
                $db->quoteName('enabled'),
                $db->quoteName('type'),
                $db->quoteName('element'),
                $db->quoteName('folder')
            )
        );
        $db->query();
    }

    private function installARISoftLib()
    {
        $extPath = dirname(__FILE__) . '/packages/lib_arisoft.zip';
        $installResult = JInstallerHelper::unpack($extPath);
        if (empty($installResult))
        {
            return false;
        }

        $installer = new JInstaller();
        $installer->setOverwrite(true);
        if (!$installer->install($installResult['extractdir']))
        {
            return false;
        }
    }

    private function isARISoftLibInstalled()
    {
        $db = JFactory::getDBO();

        $query = $db->getQuery(true);
        $query->select($db->quoteName('extension_id'))
            ->from($db->quoteName('#__extensions'))
            ->where($db->quoteName('type') . '=' . $db->quote('library'))
            ->where($db->quoteName('element') . '=' . $db->quote('arisoft'))
            ->setLimit(10);

        $db->setQuery($query);

        $extId = $db->loadResult();

        return !empty($extId);
    }

    private function extractFiles($archivePath, $destPath)
    {
        if (!JFolder::exists($destPath) && !JFolder::create($destPath))
        {
            JFactory::getApplication()->enqueueMessage(
                'ARI Docs Viewer: the installer can\'t create "' . $destPath . "' folder. Check file permissions or create the folder manually and install the extension again.",
                'error'
            );

            return false;
        }

        $packager = null;
        if (!($packager = JArchive::getAdapter('zip')) || !$packager->extract($archivePath, $destPath))
        {
            JFactory::getApplication()->enqueueMessage(
                'ARI Docs Viewer: could not extract files from ' . basename($archivePath) . ' archive.',
                'error'
            );

            return false;
        }

        return true;
    }
}