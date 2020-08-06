<?php
/**
 * @version        1.0
 * @package        Joomla
 * @subpackage     EDocman
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2011 - 2018 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');
require_once JPATH_ROOT . '/plugins/edocman/googledrive/vendor/autoload.php';
require_once JPATH_ROOT . '/components/com_edocman/helper/helper.php';

class plgEDocmanGoogledrive extends JPlugin
{

	/***
	 * @var Filesystem
	 */
	private $filesytem;


	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);

	}

	/**
	 * Get file system object
	 *
	 * @return Filesystem
	 */
	private function getFileSystem()
	{
		if (!$this->filesytem)
		{
            JLoader::register('EdocmanGoogle', JPATH_PLUGINS.'/edocman/googledrive/Google.php');
			$this->filesytem = new EdocmanGoogle();
			$this->createRootFolder();
		}
		return $this->filesytem;
	}

    /**
     * This function is used to create root folder
     */
	private function createRootFolder(){
        $db                 = JFactory::getDbo();
        $config             = EDocmanHelper::getConfig();
        $filesystem         = $this->getFileSystem();
        $addrootpath        = 0;
        $root_path          = $this->params->get('root_path','edocman');
        $db->setQuery("Select count(id) from #__edocman_googledrive where `type` = 0 and element_name like '$root_path' and element_id = '0'");
        $count              = $db->loadResult();
        if($count == 0)
        {
            $addrootpath    = 1;
        }
        else
        {
            $db->setQuery("Select cloud_id from #__edocman_googledrive where `type` = 0 and element_name like '$root_path' and element_id = '0'");
            $cloud_id       = $db->loadResult();
            if($cloud_id    != '')
            {
                if(! $filesystem->folderExists($cloud_id))
                {
                    $addrootpath = 1;
                }
            }
        }
        if ($addrootpath == 1)
        {
            $folderId       = $filesystem->createFolder($root_path);
            $folderId       = $filesystem->getFileObj($folderId);
            $folderId       = $folderId->file_id;
            $db->setQuery("Select count(id) from #__edocman_googledrive where `type` = 0 and element_id = '0' and element_name like '$root_path' ");
            $count          = $db->loadResult();
            if($count == 0)
            {
                $db->setQuery("Insert into #__edocman_googledrive (id,`type`,element_id,element_name,cloud_id) values (NULL,'0','0','$root_path','$folderId')");
                $db->execute();
            }
            else
            {
                $db->setQuery("Update #__edocman_googledrive set cloud_id = '$folderId' where `type`= '0' and element_name like '$root_path' and element_id = '0'");
                $db->execute();
            }
        }
    }

    private function getRootFolderId(){
        $root_path          = $this->params->get('root_path','edocman');
	    $db = JFactory::getDbo();
	    $db->setQuery("Select cloud_id from #__edocman_googledrive where `type` = 0 and element_name like '$root_path' and element_id = '0'");
	    return $db->loadResult();
    }

    private function importLog($element_id,$cloud_id){
	    $db = JFactory::getDbo();
        $db->setQuery("Select count(id) from #__edocman_googledrive where `type` = 1 and element_id = '$element_id'");
        $count          = $db->loadResult();
        if($count == 0){
            $db->setQuery("Insert into #__edocman_googledrive (id,`type`,element_id,element_name,cloud_id) values (NULL,'1','$element_id','','$cloud_id')");
            $db->execute();
        }else{
            $db->setQuery("Update #__edocman_googledrive set cloud_id = '$cloud_id' where `type`= '1' and element_id = '$element_id'");
            $db->execute();
        }
    }

    private function getCloudId($element_id){
	    $db = JFactory::getDbo();
        $db->setQuery("Select cloud_id from #__edocman_googledrive where `type` = 1 and element_id = '$element_id'");
        return $db->loadResult();
    }

	/**
	 * Create folder on dropbox if needed
	 *
	 * @param $context
	 * @param $row
	 * @param $isNew
	 */
	public function onCategoryAfterSave($context, $row, $isNew)
	{

	}

    /**
     * This function is used to upload documents into Dropbox through Batchupload tool
     * @param $savedFilename
     * @param $file
     * @param $path
     * @return bool
     */
    public function onDocumentBatchUpload($savedFilename, $file, $path)
    {
        $google_client_id       = $this->params->get('google_client_id','');
        $google_client_secret   = $this->params->get('google_client_secret','');
        if($google_client_id == '' || $google_client_secret == '')
        {
            $return         = array();
            $return[0]      = false;
            return $return;
        }
        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');
        $config                 = EDocmanHelper::getConfig();
        $filesystem             = $this->getFileSystem();
        $filesize               = $file['size'];
        $return                 = array();
        if($file)
        {
            if (!JFolder::exists(JPATH_ROOT . '/tmp/edupload'))
            {
                JFolder::create(JPATH_ROOT . '/tmp/edupload');
            }
            JFile::upload($file['tmp_name'], JPATH_ROOT . '/tmp/edupload/' . $savedFilename);
            $filecontent        = file_get_contents(JPATH_ROOT . '/tmp/edupload/' . $savedFilename);
            $cloud_id           = $this->getRootFolderId();
            $fileObj            = $filesystem->uploadFile($savedFilename, $filecontent, $file['type'], $cloud_id);
            $fileObj            = $filesystem->getFileObj($fileObj);
            $fileid             = $fileObj->file_id;
            Jfile::delete(JPATH_ROOT . '/tmp/edupload/' . $savedFilename);
            $return[0]          = true;
            $return[1]          = $filesize;
            $return[2]          = $fileid;
            return $return;
        }
        else
        {
            $return[0]          = false;
            return $return;
        }
    }

	/**
	 * Process file upload
	 *
	 * @param $row
	 * @param $isNew
	 * @param $file
	 * @param $path
	 * @param $fileName
	 *
	 * @return bool
	 */
	public function onDocumentUpload($row, $isNew, $file, $path, $fileName, $categoryId)
	{
        $google_client_id       = $this->params->get('google_client_id','');
        $google_client_secret   = $this->params->get('google_client_secret','');
        if($google_client_id == '' || $google_client_secret == '')
        {
            $return         = array();
            $return[0]      = false;
            return $return;
        }
        $filesystem = $this->getFileSystem();
	    jimport('joomla.filesystem.folder');
	    jimport('joomla.filesystem.file');
        $filesize = $file['size'];
        if($file){
            if(!JFolder::exists(JPATH_ROOT.'/tmp/edupload')){
                JFolder::create(JPATH_ROOT.'/tmp/edupload');
            }
            JFile::upload($file['tmp_name'],JPATH_ROOT.'/tmp/edupload/'.$fileName);
            $filecontent = file_get_contents(JPATH_ROOT.'/tmp/edupload/'.$fileName);
            $cloud_id = $this->getRootFolderId();
            $fileObj = $filesystem->uploadFile($fileName,$filecontent,$file['type'],$cloud_id);
            $fileObj = $filesystem->getFileObj($fileObj);
            Jfile::delete(JPATH_ROOT . '/tmp/edupload/' . $fileName);
            $fileid  = $fileObj->file_id;
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->clear();
            $query->update('#__edocman_documents')->set('file_size="'.$file['size'].'"')->where('id="'.$row->id.'"');
            $db->setQuery($query);
            $db->execute();
            if($row->id > 0) {
                $this->importLog($row->id, $fileid);
            }else{
                $session = JFactory::getSession();
                $session->set('fileid',$fileid);
            }
            $data['file_size'] = $filesize;
            return true;
        }
        return false;
	}

	/**
	 * Process file upload
	 *
	 * @param $row
	 * @param $isNew
	 * @param $file
	 * @param $path
	 * @param $fileName
	 *
	 * @return bool
	 */
	public function onFilesizeUpload($row, $isNew, $path, $fileName)
	{
        $filesystem = $this->getFileSystem();
        $fileid = $this->getCloudId($row->id);
        $data = $filesystem->getFileInfos($fileid);
        $filesize = $data['size'];
		return $filesize;
	}

	/**
	 * Get the document stream used for processing download
	 *
	 * @param $row
	 *
	 * @return array
	 */
	public function onGetDocumentFile($filename, $fileid = 0)
	{
        $google_client_id       = $this->params->get('google_client_id','');
        $google_client_secret   = $this->params->get('google_client_secret','');
        if($google_client_id == '' || $google_client_secret == '')
        {
            return array();
        }
        $filesystem             = $this->getFileSystem();
        $cloud_id               = $this->getCloudId($fileid);
        $file                   = $filesystem->download($cloud_id);
        if (!is_object($file))
        {
            return array();
        }
        $file_name_head         = htmlspecialchars($file->title ) . '"';
        header('Content-Disposition: attachment; filename="' . $file_name_head);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        if ($file->size != 0)
        {
            header('Content-Length: ' . $file->size);
        }
        ob_clean();
        flush();
        echo $file->datas;
        jexit();
	}

    /**
     * Remove file from DropBox
     * @param $task
     * @param $row
     */
    public function onDocumentBeforeDelete($task,$row)
    {
        $filesystem = $this->getFileSystem();
        $id = $this->getCloudId($row->id);
        $filesystem->delete($id);

        $db = JFactory::getDbo();
        $db->setQuery("Delete from #__edocman_googledrive where type = '1' and element_id = '$row->id'");
        $db->execute();
    }
}