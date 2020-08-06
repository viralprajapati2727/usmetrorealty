<?php
/**
 * Edocman
 * @package Edocman
 * @copyright Copyright (C) 2018 Ossolution (http://www.joomdonation.com). All rights reserved.
 * @license GNU General Public License version 2 or later; http://www.gnu.org/licenses/gpl-2.0.html
 *
 */

// no direct access
defined('_JEXEC') or die;
jimport('joomla.filesystem.file');

/**
 * Class EdocmanGoogle initialization and connection Google drive
 */
class EdocmanGoogle
{

    /**
     * @var
     */
    protected $params;
    /**
     * @var
     */
    protected $lastError;


    /**
     * DropfilesGoogle constructor.
     *
     */
    public function __construct()
    {
        set_include_path(__DIR__ . PATH_SEPARATOR . get_include_path());
        require_once 'vendor/autoload.php';
        $this->loadParams();
    }

    /**
     * @return mixed
     *
     */
    public function getLastError()
    {
        return $this->lastError;
    }

    /**
     *Load Params in config
     */
    protected function loadParams()
    {
        $plugin = JPluginHelper::getPlugin('edocman','googledrive');
        $params = new JRegistry($plugin->params);
        $db = JFactory::getDbo();
        $db->setQuery("Select `credentials` from #__edocman_googledrive_credentials");
        $google_credentials = $db->loadResult();
        $this->params = new stdClass();
        $this->params->google_client_id     = $params->get('google_client_id');
        $this->params->google_client_secret = $params->get('google_client_secret');
        $this->params->google_credentials   = $google_credentials;
    }

    /**
     *Save params
     */
    protected function saveParams()
    {
        $db = JFactory::getDbo();
        $db->setQuery("Select count(`credentials`) from #__edocman_googledrive_credentials");
        $count = $db->loadResult();
        if($count == 0){
            $db->setQuery("Insert into #__edocman_googledrive_credentials (`credentials`) values ('".$this->params->google_credentials."')");
            $db->execute();
        }else{
            $db->setQuery("Update #__edocman_googledrive_credentials set `credentials` = '".$this->params->google_credentials."'");
            $db->execute();
        }
    }


    /**
     * Get GGD Author Url
     * @return string
     *
     */
    public function getAuthorisationUrl()
    {
        $client = new Google_Client();
        $client->setClientId($this->params->google_client_id);
        $google_redirect = JURI::root() . 'administrator/index.php?option=com_edocman&task=googledriveauthenticate';
        $client->setRedirectUri($google_redirect);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $client->setState('');
        $client->setScopes(array(
            'https://www.googleapis.com/auth/drive',
            'https://www.googleapis.com/auth/userinfo.email',
            'https://www.googleapis.com/auth/userinfo.profile'));
        $tmpUrl = parse_url($client->createAuthUrl());
        $query = explode('&', $tmpUrl['query']);
        $return = $tmpUrl['scheme'] . '://' . $tmpUrl['host'] . @$tmpUrl['port'];
        $return .= $tmpUrl['path'] . '?' . implode('&', $query);
        return $return;
    }

    /**
     * Authenticate google drive
     * @return string
     *
     */
    public function authenticate()
    {
        $code = JFactory::getApplication()->input->get('code', '', 'RAW');
        $client = new Google_Client();
        $client->setClientId($this->params->google_client_id);
        $client->setClientSecret($this->params->google_client_secret);
        $google_redirect = JURI::root() . 'administrator/index.php?option=com_edocman&task=googledriveauthenticate';
        $client->setRedirectUri($google_redirect);

        return $client->authenticate($code);
    }

    /**
     *Logout GGD
     */
    public function logout()
    {
        $client = new Google_Client();
        $client->setClientId($this->params->google_client_id);
        $client->setClientSecret($this->params->google_client_secret);
        $client->setAccessToken($this->params->google_credentials);
        $client->revokeToken();

        $db = JFactory::getDbo();
        $db->setQuery("Update #__edocman_googledrive_credentials set `credentials` = ''");
        $db->execute();
    }

    /**
     * Store Credentials
     * @param $credentials
     *
     */
    public function storeCredentials($credentials)
    {
        $this->params->google_credentials = $credentials;
        $this->saveParams();
    }

    /**
     * Get Credentials
     * @return mixed
     *
     */
    public function getCredentials()
    {
        return $this->params->google_credentials;
    }

    /**
     * Check Auth Google drive
     *
     * @return bool
     *
     */
    public function checkAuth()
    {
        $client = new Google_Client();
        $client->setClientId($this->params->google_client_id);
        $client->setClientSecret($this->params->google_client_secret);

        try {
            $client->setAccessToken($this->params->google_credentials);
            $service = new Google_Service_Drive($client);
            $service->files->listFiles(array());
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
        return true;
    }

    /**
     * Check folder exist in google drive
     * @param $id
     * @return bool
     *
     */
    public function folderExists($id)
    {
        $client = new Google_Client();
        $client->setClientId($this->params->google_client_id);
        $client->setClientSecret($this->params->google_client_secret);
        $client->setAccessToken($this->params->google_credentials);

        $service = new Google_Service_Drive($client);
        try {
            $service->files->get($id);
            return true;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
        return false;
    }

    /**
     * Create new folder in Google drive
     * @param $title
     * @param null $parentId
     * @return bool|Google_Service_Drive_DriveFile
     *
     */
    public function createFolder($title, $parentId = null)
    {
        $client = new Google_Client();
        $client->setClientId($this->params->google_client_id);
        $client->setClientSecret($this->params->google_client_secret);
        $client->setAccessToken($this->params->google_credentials);

        $service = new Google_Service_Drive($client);
        $file = new Google_Service_Drive_DriveFile();
        $file->title = $title;
        $file->mimeType = "application/vnd.google-apps.folder";

        if ($parentId != null) {
            $parent = new Google_Service_Drive_ParentReference();
            $parent->setId($parentId);
            $file->setParents(array($parent));
        }

        try {
            $fileId = $service->files->insert($file);
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
        return $fileId;
    }

    /**
     * Get all item in folder id with ordering
     * @param $folder_id
     * @param string $ordering
     * @param string $direction
     * @return array|bool
     *
     */
    public function listFiles($folder_id, $ordering = 'ordering', $direction = 'asc')
    {
        $client = new Google_Client();
        $client->setClientId($this->params->google_client_id);
        $client->setClientSecret($this->params->google_client_secret);

        try {
            $client->setAccessToken($this->params->google_credentials);

            //        if($client->isAccessTokenExpired()){
            //            $client->refreshToken($creds->refresh_token);
            //


            $service = new Google_Service_Drive($client);
            $q = "'" . $folder_id;
            $q .= "' in parents and trashed=false and mimeType != 'application/vnd.google-apps.folder' ";
            $fs = $service->files->listfiles(array('q' => $q));
            //$fs = $service->files->listfiles(array('q' =>  "'".$folder_id."' in parents"));

            $files = array();
            foreach ($fs as $f) {
                if ($f->getMimeType() != 'application/vnd.google-apps.folder') {
                    $file = new stdClass();
                    $file->id = $f->getId();
                    $file->title = JFile::stripExt($f->getTitle());
                    $file->description = $f->getDescription();
                    $file->ext = $f->fileExtension ? $f->fileExtension : JFile::getExt($f->originalFilename);
                    $file->size = $f->getFileSize();
                    $file->created_time = date('Y-m-d H:i:s', strtotime($f->getCreatedDate()));
                    $file->modified_time = date('Y-m-d H:i:s', strtotime($f->getModifiedDate()));
                    $file->version = '';
                    $file->hits = 0;
                    $file->ordering = 0;
                    $file->file_tags = $this->getFileTags($service, $file->id, 'file_tags', 'PRIVATE');
                    $properties = $f->getProperties();
                    if ($f->fileExtension == null && $f->size == null && isset($f->id)) {
                        $ExportLinks = $f->getExportLinks();
                        if ($ExportLinks != null) {
                            uksort($ExportLinks, create_function('$a,$b', 'return strlen($a) < strlen($b);'));
                            $ext_tmp = explode('=', reset($ExportLinks));
                            $file->ext = end($ext_tmp);
                        }
                        $file->created_time = $f->getCreatedDate();
                        $file->modified_time = $f->getModifiedDate();
                        $file->version = $f->getVersion();
                    }
                    if (!empty($properties)) {
                        foreach ($properties as $property) {
                            switch ($property->key) {
                                case 'version':
                                    $file->version = $property->value;
                                    break;
                                case 'hits':
                                    $file->hits = $property->value;
                                    break;
                                case 'ordering':
                                    $file->ordering = $property->value;
                            }
                        }
                    }
                    $files[] = $file;
                    unset($file);
                }
            }

            $files = $this->subvalSort($files, $ordering, $direction);
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
        return $files;
    }

    /**
     * Subval Sort
     *
     * @param $a
     * @param $subkey
     * @param $direction
     * @return array
     *
     */
    private function subvalSort($a, $subkey, $direction)
    {
        $c = null;
        if (empty($a)) {
            return $a;
        }
        foreach ($a as $k => $v) {
            $b[$k] = strtolower($v->$subkey);
        }
        if ($direction == 'asc') {
            asort($b);
        } else {
            arsort($b);
        }
        foreach ($b as $key => $val) {
            $c[] = $a[$key];
        }
        return $c;
    }

    /**
     * Get all files in folder
     * @param $folder_id
     * @return array|bool
     *
     */
    public function listFilesInFolder($folder_id)
    {
        $client = new Google_Client();
        $client->setClientId($this->params->google_client_id);
        $client->setClientSecret($this->params->google_client_secret);

        try {
            $client->setAccessToken($this->params->google_credentials);

            $service = new Google_Service_Drive($client);
            $q = "'" . $folder_id;
            $q .= "' in parents and trashed=false and mimeType != 'application/vnd.google-apps.folder' ";
            $fs = $service->files->listfiles(array('q' => $q));

            $files = array();
            foreach ($fs as $f) {
                $file = new stdClass();
                $file->id = $f->getId();
                $file->title = JFile::stripExt($f->getTitle());
                $file->description = $f->getDescription();
                $file->ext = $f->fileExtension ? $f->fileExtension : JFile::getExt($f->originalFilename);
                $file->size = $f->getFileSize();
                $file->created_time = date('Y-m-d H:i:s', strtotime($f->getCreatedDate()));
                $file->modified_time = date('Y-m-d H:i:s', strtotime($f->getModifiedDate()));
                $file->file_tags = $this->getFileTags($service, $file->id, 'file_tags', 'PRIVATE');
                $properties = $f->getProperties();

                if (!empty($properties)) {
                    foreach ($properties as $property) {
                        switch ($property->key) {
                            case 'version':
                                $file->version = $property->value;
                                break;
                            case 'hits':
                                $file->hits = $property->value;
                                break;
                            case 'ordering':
                                $file->ordering = $property->value;
                        }
                    }
                }

                if ($f->fileExtension == null && $f->size == null && isset($f->id)) {
                    $ExportLinks = $f->getExportLinks();
                    if ($ExportLinks != null) {
                        uksort($ExportLinks, create_function('$a,$b', 'return strlen($a) < strlen($b);'));
                        $ext_tmp = explode('=', reset($ExportLinks));
                        $file->ext = end($ext_tmp);
                    }
                    $file->created_time = $f->getCreatedDate();
                    $file->modified_time = $f->getModifiedDate();
                }

                $files[$file->id] = $file;
                unset($file);
            }
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
        return $files;
    }

    /**
     * Upload file to google drive
     * @param $filename
     * @param $fileContent
     * @param $mime
     * @param $id_folder
     * @return bool|Google_Service_Drive_DriveFile
     *
     */
    public function uploadFile($filename, $fileContent, $mime, $id_folder)
    {
        $client = new Google_Client();
        $client->setClientId($this->params->google_client_id);
        $client->setClientSecret($this->params->google_client_secret);
        $client->setAccessToken($this->params->google_credentials);

        $file = new Google_Service_Drive_DriveFile();
        $parent = new Google_Service_Drive_ParentReference();
        $parent->setId($id_folder);
        $file->setParents(array($parent));
        $file->setTitle($filename);
        $file->setMimeType($mime);

        try {
            $service = new Google_Service_Drive($client);
            $insertedFile = $service->files->insert(
                $file,
                array('data' => $fileContent, 'mimeType' => $mime, 'uploadType' => 'media')
            );
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
        return $insertedFile;
    }

    /**
     * Get File object
     * @param $f
     * @return stdClass
     *
     */
    public function getFileObj($f)
    {
        $file = new stdClass();
        $file->id = $f->getId();
        $file->file_id = $file->id;
        $file->title = JFile::stripExt($f->getTitle());
        $file->description = $f->getDescription();
        $file->ext = $f->fileExtension ? $f->fileExtension : JFile::getExt($f->originalFilename);
        $file->size = $f->getFileSize();

        $date = JFactory::getDate();
        $file->created_time = $date->setTimestamp(strtotime($f->getCreatedDate()))->toSql();
        $file->modified_time = $date->setTimestamp(strtotime($f->getModifiedDate()))->toSql();

        if ($f->fileExtension == null && $f->size == null && isset($f->id)) {
            $ExportLinks = $f->getExportLinks();
            if ($ExportLinks != null) {
                uksort($ExportLinks, create_function('$a,$b', 'return strlen($a) < strlen($b);'));
                $ext_tmp = explode('=', reset($ExportLinks));
                $file->ext = end($ext_tmp);
            }
            $file->created_time = $date->setTimestamp(strtotime($f->getCreatedDate()))->toSql();
            $file->modified_time = $date->setTimestamp(strtotime($f->getModifiedDate()))->toSql();
        }
        return $file;
    }

    /**
     * Get file info
     * @param $id
     * @param null $cloud_id
     * @return array|bool
     *
     */
    public function getFileInfos($id, $cloud_id = null)
    {
        $client = new Google_Client();
        $client->setClientId($this->params->google_client_id);
        $client->setClientSecret($this->params->google_client_secret);
        $client->setAccessToken($this->params->google_credentials);

        try {
            $service = new Google_Service_Drive($client);
            $file = $service->files->get($id);

            if ($cloud_id !== null) {
                $found = false;
                foreach ($file->getParents() as $parent) {
                    if ($parent->id == $cloud_id) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    return false;
                }
            }

            $data = array();
            $data['id'] = $id;
            $data['title'] = JFile::stripExt($file->title);
            $data['description'] = $file->description;
            $data['file'] = $file->title;
            $data['ext'] = $file->fileExtension ? $file->fileExtension : JFile::getExt($file->originalFilename);
            $data['created_time'] = date('Y-m-d H:i:s', strtotime($file->createdDate));
            $data['modified_time'] = date('Y-m-d H:i:s', strtotime($file->modifiedDate));
            $data['file_tags'] = $this->getFileTags($service, $id, 'file_tags', 'PRIVATE');
            try {
                $hits = $service->properties->get($id, 'hits', array('visibility' => 'PRIVATE'));
                $hits = $hits->value;
            } catch (Exception $ex) {
                $hits = 0;
            }
            $data['hits'] = $hits;
            $data['size'] = $file->fileSize;
            try {
                $version = $service->properties->get($id, 'version', array('visibility' => 'PRIVATE'));
                $version = $version->value;
            } catch (Exception $e) {
                $version = '';
            }
            $data['version'] = $version;
            try {
                $order = $service->properties->get($id, 'order', array('visibility' => 'PRIVATE'));
                $order = $order->value;
            } catch (Exception $e) {
                $order = 0;
            }
            try {
                $publish = $service->properties->get($id, 'publish', array('visibility' => 'PRIVATE'));
                $publish = $publish->value;
            } catch (Exception $e) {
                $publish = '';
            }
            $data['publish'] = $publish;

            try {
                $publish_down = $service->properties->get($id, 'publish_down', array('visibility' => 'PRIVATE'));
                $publish_down = $publish_down->value;
            } catch (Exception $e) {
                $publish_down = '';
            }
            $data['publish_down'] = $publish_down;

            try {
                $canview = $service->properties->get($id, 'canview', array('visibility' => 'PRIVATE'));
                $canview = $canview->value;
            } catch (Exception $e) {
                $canview = '';
            }
            $data['canview'] = $canview;

            $data['ordering'] = $order;
            if ($file->fileExtension == null && $file->size == null && $file->id != null) {
                $ExportLinks = $file->getExportLinks();
                if ($ExportLinks != null) {
                    uksort($ExportLinks, create_function('$a,$b', 'return strlen($a) < strlen($b);'));
                    $ext_tmp = explode('=', reset($ExportLinks));
                    $data['ext'] = end($ext_tmp);
                    $data['size'] = 0;
                    $data['version'] = $file->getVersion();
                }
            }
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
        return $data;
    }

    /**
     * Save file info
     * @param $datas
     * @param null $cloud_id
     * @return bool
     *
     */
    public function saveFileInfos($datas, $cloud_id = null)
    {
        if (empty($datas['id'])) {
            $datas['id'] = JFactory::getApplication()->input->getString('id');
        }
        $client = new Google_Client();
        $client->setClientId($this->params->google_client_id);
        $client->setClientSecret($this->params->google_client_secret);
        $client->setAccessToken($this->params->google_credentials);

        try {
            $service = new Google_Service_Drive($client);
            $file = $service->files->get($datas['id']);
            $params = array('uploadType' => 'multipart');
            if ($cloud_id !== null) {
                $found = false;
                foreach ($file->getParents() as $parent) {
                    if ($parent->id == $cloud_id) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    return false;
                }
            }


            if (isset($datas['title'])) {
                $file->setTitle($datas['title'] . '.' . $file->fileExtension);
            }
            if (isset($datas['description'])) {
                $file->setDescription($datas['description']);
            }
            if (isset($datas['data'])) {
                $params['data'] = $datas['data'];
            }
            if (isset($datas['newRevision'])) {
                $params['newRevision'] = true;
            } else {
                $params['newRevision'] = false;
            }
            $service->files->update($datas['id'], $file, $params);

            $properties = $service->properties->listProperties($datas['id']);
            $propertiesList = $properties->getItems();

            $google_file_properties = array('version', 'hits', 'publish', 'publish_down', 'canview');
            if (!empty($propertiesList)) {
                foreach ($propertiesList as $property) {
                    if (in_array($property->key, $google_file_properties)) {
                        $property->setValue($datas[$property->key]);
                        $arr_param = array('visibility' => 'PRIVATE');
                        $service->properties->patch($datas['id'], $property->key, $property, $arr_param);
                    }
                }
            } else {
                foreach ($google_file_properties as $property) {
                    $newProperty = new Google_Service_Drive_Property();
                    $newProperty->setKey($property);
                    $newProperty->setValue($datas[$property]);
                    $newProperty->setVisibility('PRIVATE');
                    $service->properties->insert($datas['id'], $newProperty);
                }
            }

            return true;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
        return true;
    }

    /**
     * Change file name
     * @param $id
     * @param $filename
     * @return bool
     *
     */
    public function changeFilename($id, $filename)
    {
        $client = new Google_Client();
        $client->setClientId($this->params->google_client_id);
        $client->setClientSecret($this->params->google_client_secret);
        $client->setAccessToken($this->params->google_credentials);

        try {
            $service = new Google_Service_Drive($client);
            $file = $service->files->get($id);
            $file->setTitle($filename);
            $service->files->update($id, $file, array());
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
        return true;
    }

    /**
     * Insert hits file
     * @param $id
     * @return bool
     *
     */
    public function incrHits($id)
    {
        $client = new Google_Client();
        $client->setClientId($this->params->google_client_id);
        $client->setClientSecret($this->params->google_client_secret);
        $client->setAccessToken($this->params->google_credentials);

        try {
            $service = new Google_Service_Drive($client);
            try {
                $hits = $service->properties->get($id, 'hits', array('visibility' => 'PRIVATE'));
                $hits = $hits->value;
            } catch (Exception $e) {
                $hits = 0;
            }

            $newProperty = new Google_Service_Drive_Property();
            $newProperty->setKey('hits');
            $newProperty->setValue($hits + 1);
            $newProperty->setVisibility('PRIVATE');
            $service->properties->insert($id, $newProperty);
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
        return true;
    }

    /**
     * Reorder items
     * @param $files
     * @return bool
     *
     */
    public function reorder($files)
    {
        $client = new Google_Client();
        $client->setClientId($this->params->google_client_id);
        $client->setClientSecret($this->params->google_client_secret);
        $client->setAccessToken($this->params->google_credentials);

        try {
            $service = new Google_Service_Drive($client);
            foreach ($files as $key => $file) {
                $newProperty = new Google_Service_Drive_Property();
                $newProperty->setKey('order');
                $newProperty->setValue($key);
                $newProperty->setVisibility('PRIVATE');
                $service->properties->insert($file, $newProperty);
            }
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
        return true;
    }

    /**
     * Download file
     *
     * @param $id
     * @param null $cloud_id
     * @param null $version
     * @param int $preview
     * @return bool|stdClass
     *
     */
    public function download($id, $cloud_id = null, $version = null, $preview = 0)
    {
        $client = new Google_Client();
        $client->setClientId($this->params->google_client_id);
        $client->setClientSecret($this->params->google_client_secret);
        $client->setAccessToken($this->params->google_credentials);

        try {
            $service = new Google_Service_Drive($client);
            $file = $service->files->get($id);

            if ($cloud_id !== null) {
                $found = false;
                foreach ($file->getParents() as $parent) {
                    if ($parent->id == $cloud_id) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    return false;
                }
            }


            $downloadUrl = $file->getDownloadUrl();
            $mineType = $file->getMimeType();
            if ($file->getDownloadUrl() == null && $file->getFileSize() == null && $file->getId() != null) {
                $ExportLinks = $file->getExportLinks();

                if ($ExportLinks != null) {
                    if ($preview && isset($ExportLinks['application/pdf'])
                        && strpos($mineType, 'vnd.google-apps') !== false) {
                        $downloadUrl = $ExportLinks['application/pdf'];
                    } else {
                        uksort($ExportLinks, create_function('$a,$b', 'return strlen($a) < strlen($b);'));
                        $ext_tmp = explode('=', reset($ExportLinks));
                        $downloadUrl = reset($ExportLinks);
                    }
                } else {
                    $downloadUrl = $file->getAlternateLink();
                }
            }
            if ($version !== null) {
                $revision = $service->revisions->get($id, $version);
            }
            if ($downloadUrl) {
                $request = new Google_Http_Request($downloadUrl);
                $httpRequest = $client->getAuth()->authenticatedRequest($request);

                if ($httpRequest->getResponseHttpCode() == 200) {
                    $ret = new stdClass();
                    $ret->datas = $httpRequest->getResponseBody();

                    if ($file->title) {
                        $ret->title = $file->title;
                    } else {
                        $ret->title = JFile::stripExt($file->getOriginalFilename());
                        $ret->title = JFile::stripExt($ret->title);
                    }

                    if (isset($revision) && $revision->originalFilename != null) {
                        $ext_file_name = JFile::getExt($revision->originalFilename);
                        $ret->ext = $revision->fileExtension ? $revision->fileExtension : $ext_file_name;
                        $ret->size = $revision->fileSize;
                    } else {
                        $ext_file_name = JFile::getExt($file->originalFilename);
                        $ret->ext = $file->fileExtension ? $file->fileExtension : $ext_file_name;
                        $ret->size = $file->getFileSize();
                    }
                    if ($file->getFileExtension() == null && isset($ext_tmp)) {
                        $ret->ext = end($ext_tmp);
                    }
                    if ($preview && strpos($mineType, 'vnd.google-apps') !== false) {
                        $ret->ext = 'pdf';
                    }
                    return $ret;
                } else {
                    // An error occurred.
                    return false;
                }
            } else {
                // The file doesn't have any content stored on Drive.
                return false;
            }
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    /**
     * Delete items
     *
     * @param $id
     * @param null $cloud_id
     * @return bool
     *
     */
    public function delete($id, $cloud_id = null)
    {
        $client = new Google_Client();
        $client->setClientId($this->params->google_client_id);
        $client->setClientSecret($this->params->google_client_secret);
        $client->setAccessToken($this->params->google_credentials);

        $service = new Google_Service_Drive($client);
        try {
            $file = $service->files->get($id);
            if ($cloud_id !== null) {
                $found = false;
                foreach ($file->getParents() as $parent) {
                    if ($parent->id == $cloud_id) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    return false;
                }
            }
            $service->files->delete($id);
        } catch (Exception $e) {
            if ($e->getCode() == 404) { // file already deleted on GD
                return true;
            }

            $this->lastError = $e->getMessage();
            return false;
        }
        return true;
    }

    /**
     * Get all file version
     *
     * @param $id
     * @param null $cloud_id
     * @return array|bool
     *
     */
    public function listVersions($id, $cloud_id = null)
    {
        $client = new Google_Client();
        $client->setClientId($this->params->google_client_id);
        $client->setClientSecret($this->params->google_client_secret);
        $client->setAccessToken($this->params->google_credentials);

        try {
            $service = new Google_Service_Drive($client);
            $file = $service->files->get($id);

            if ($cloud_id !== null) {
                $found = false;
                foreach ($file->getParents() as $parent) {
                    if ($parent->id == $cloud_id) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    return false;
                }
            }
            $revisions = $service->revisions->listRevisions($id);
            $revs = array();
            foreach ($revisions as $revision) {
                if ($revision->id !== $file->headRevisionId) {
                    $rev = new stdClass();
                    $rev->id = $id;
                    $rev->id_version = $revision->id;
                    $rev->size = $revision->fileSize;
                    $rev->created_time = date('Y-m-d H:i:s', strtotime($revision->modifiedDate));
                    $revs[] = $rev;
                }
            }
            return $revs;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    /**
     * Delete reversion
     * @param $id
     * @param null $revision
     * @param null $cloud_id
     * @return bool
     *
     */
    public function deleteRevision($id, $revision = null, $cloud_id = null)
    {
        $client = new Google_Client();
        $client->setClientId($this->params->google_client_id);
        $client->setClientSecret($this->params->google_client_secret);
        $client->setAccessToken($this->params->google_credentials);

        try {
            $service = new Google_Service_Drive($client);
            $file = $service->files->get($id);

            if ($cloud_id !== null) {
                $found = false;
                foreach ($file->getParents() as $parent) {
                    if ($parent->id == $cloud_id) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    return false;
                }
            }
            $service->revisions->delete($id, $revision);
            return true;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    /**
     * Update revision
     *
     * @param $id
     * @param null $revision
     * @param null $cloud_id
     * @return bool|Google_Service_Drive_Revision
     *
     */
    public function updateRevision($id, $revision = null, $cloud_id = null)
    {

        $client = new Google_Client();
        $client->setClientId($this->params->google_client_id);
        $client->setClientSecret($this->params->google_client_secret);
        $client->setAccessToken($this->params->google_credentials);

        try {
            $service = new Google_Service_Drive($client);
            $file = $service->files->get($id);

            if ($cloud_id !== null) {
                $found = false;
                foreach ($file->getParents() as $parent) {
                    if ($parent->id == $cloud_id) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    return false;
                }
            }
            $revisionOb = $service->revisions->get($id, $revision);
            $revisionOb->setPinned(true);
            $ac = $service->revisions->update($id, $revision, $revisionOb);
            return $ac;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    /**
     * Get files in folder
     *
     * @param $folderId
     * @param $datas
     * @throws Exception
     *
     */
    public function getFilesInFolder($folderId, &$datas)
    {
        $client = new Google_Client();
        $client->setClientId($this->params->google_client_id);
        $client->setClientSecret($this->params->google_client_secret);
        $client->setAccessToken($this->params->google_credentials);
        $params = JComponentHelper::getParams('com_edocman');
        $base_folder = $params->get('google_base_folder');

        $pageToken = null;
        if ($datas === false) {
            throw new Exception('getFilesInFolder - datas error ');
        }
        if (!is_array($datas)) {
            $datas = array();
        }
        do {
            try {
                $service = new Google_Service_Drive($client);
                $parameters = array();
                $parameters['q'] = 'trashed=false';
                if ($pageToken) {
                    $parameters['pageToken'] = $pageToken;
                }
                $q = "'" . $folderId;
                $q .= "' in parents and trashed=false and mimeType = 'application/vnd.google-apps.folder' ";
                $fs = $service->files->listfiles(array('q' => $q, 'fields' => "items(id,title)"));
                $items = $fs->getItems();
                if ($params->get('sync_log_option') == 1) {
                    $erros = "folderId - " . $folderId . PHP_EOL;
                    JLog::add($erros, JLog::INFO, 'com_edocman');
                }
                foreach ($items as $f) {
                    $idFile = $f->getId();
                    if ($folderId != $base_folder) {
                        $datas[$idFile] = array('title' => $f->getTitle(), 'parent_id' => $folderId);
                    } else {
                        $datas[$idFile] = array('title' => $f->getTitle(), 'parent_id' => 1);
                    }
                    if ($params->get('sync_log_option') == 1) {
                        $erros = "Child - " . $idFile . ": " . json_encode($datas[$idFile]) . PHP_EOL;
                        JLog::add($erros, JLog::INFO, 'com_edocman');
                    }
                    $this->getFilesInFolder($idFile, $datas);
                }


                // $pageToken = $children->getNextPageToken();
            } catch (Exception $e) {
                print "An error occurred: " . $e->getMessage() . $e->getTraceAsString();
                if ($params->get('sync_log_option') == 1) {
                    $erros = $e->getMessage() . $e->getTraceAsString() . PHP_EOL;
                    JLog::add($erros, JLog::ERROR, 'com_edocman');
                }
                $datas = false;
                $pageToken = null;
                throw new Exception('getFilesInFolder - Google_Http_REST error ' . $e->getCode());
            }
        } while ($pageToken);
    }


    /**
     * Get List folder on Google Drive
     *
     * @param $folderId
     * @return array
     *
     */
    public function getListFolder($folderId)
    {
        $datas = array();
        $this->getFilesInFolder($folderId, $datas);
        return $datas;
    }

    /**
     * Move a file.
     *
     * @param $fileId
     * @param $newParentId
     * @return Google_Service_Drive_DriveFile
     *
     */
    public function moveFile($fileId, $newParentId)
    {
        $client = new Google_Client();
        $client->setClientId($this->params->google_client_id);
        $client->setClientSecret($this->params->google_client_secret);
        $client->setAccessToken($this->params->google_credentials);
        $service = new Google_Service_Drive($client);
        $updatedFile = null;
        try {
            $file = new Google_Service_Drive_DriveFile();

            $parent = new Google_Service_Drive_ParentReference();
            $parent->setId($newParentId);

            $file->setParents(array($parent));

            $updatedFile = $service->files->patch($fileId, $file);
        } catch (Exception $e) {
            print "An error occurred: " . $e->getMessage();
        }
        return $updatedFile;
    }


    /**
     * Copy a file.
     *
     * @param $fileId
     * @param $newParentId
     *
     */
    public function copyFile($fileId, $newParentId)
    {
        $client = new Google_Client();
        $client->setClientId($this->params->google_client_id);
        $client->setClientSecret($this->params->google_client_secret);
        $client->setAccessToken($this->params->google_credentials);
        $service = new Google_Service_Drive($client);
        try {
            $copyFile = new Google_Service_Drive_DriveFile();
            $parent = new Google_Service_Drive_ParentReference();
            $parent->setId($newParentId);
            $copyFile->setParents(array($parent));

            $service->files->copy($fileId, $copyFile);
        } catch (Exception $e) {
            print "An error occurred: " . $e->getMessage();
        }
    }

    /**
     * Insert a new custom file property.
     *
     * @param $fileId ID of the file to insert property for.
     * @param $key ID of the property.
     * @param $value Property value.
     * @param $visibility 'PUBLIC' to make the property visible by all apps,
     * @return bool|Google_Service_Drive_Property The inserted property. NULL is returned if an API error occurred.
     *
     */
    public function insertProperty($fileId, $key, $value, $visibility)
    {
        $client = new Google_Client();
        $client->setClientId($this->params->google_client_id);
        $client->setClientSecret($this->params->google_client_secret);
        $client->setAccessToken($this->params->google_credentials);
        $service = new Google_Service_Drive($client);
        $newProperty = new Google_Service_Drive_Property();
        $newProperty->setKey($key);
        $newProperty->setValue($value);
        $newProperty->setVisibility($visibility);
        try {
            return $service->properties->insert($fileId, $newProperty);
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    /**
     *  Print information about the specified custom property.
     *
     * @param $service Google_Service_Drive Drive API service instance.
     * @param String $fileId ID of the file to print property for.
     * @param String $key ID of the property to print.
     * @param String $visibility The type of property ('PUBLIC' or 'PRIVATE').
     * @return bool|string
     *
     */
    public function getFileTags($service, $fileId, $key, $visibility)
    {
        try {
            $optParams = array('visibility' => $visibility);
            $property = $service->properties->get($fileId, $key, $optParams);
            //$propertyJSON .= $property->getKey().',';
            $propertyJSON = $property->getValue();
            //print "Visibility: " . $property->getVisibility();
            return $propertyJSON;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    /**
     * Get all tags file on Google
     * @param $googleCats
     * @return array|bool
     *
     */
    public function getAllTagsFileOnGoogle($googleCats)
    {
        $catTags = array();
        if (count($googleCats)) {
            $q_tmp = array();
            foreach ($googleCats as $gCat) {
                $q_tmp[] = " '" . $gCat->cloud_id . "' in parents ";
            }
            $q1 = "(" . implode(' or ', $q_tmp) . ")";

            $client = new Google_Client();
            $client->setClientId($this->params->google_client_id);
            $client->setClientSecret($this->params->google_client_secret);
            try {
                $client->setAccessToken($this->params->google_credentials);
                $service = new Google_Service_Drive($client);
                $q = $q1 . " and trashed=false and mimeType != 'application/vnd.google-apps.folder' ";
                //$q .= " and properties has { key='file_tags' and value !='' and visibility='PRIVATE' }";
                $fs = $service->files->listfiles(array('q' => $q));
                $client->setUseBatch(true);
                $batch = new Google_Http_Batch($client);
                $optParams = array('visibility' => 'PRIVATE');
                $keys = array();
                $fParents = array();
                foreach ($fs as $f) {
                    $fid = $f->getId();
                    $keys[] = $fid;
                    $fParents[$fid] = $f->parents[0]->getId();
                    $req1 = $service->properties->get($fid, 'file_tags', $optParams);
                    $batch->add($req1, $fid);
                }

                $results = $batch->execute();

                foreach ($keys as $key) {
                    $property = $results['response-' . $key];

                    if (is_object($property) && get_class($property) == 'Google_Service_Drive_Property') {
                        $file_tags = $property->getValue();
                        if (!empty($file_tags)) {
                            $pid = $fParents[$key];
                            if (isset($catTags[$pid])) {
                                $catTags[$pid] = array_merge($catTags[$pid], explode(",", $file_tags));
                            } else {
                                $catTags[$pid] = explode(",", $file_tags);
                            }
                        }
                    }
                }


                foreach ($catTags as $key => $tags) {
                    $catTags[$key] = array_unique($tags);
                }

                return $catTags;
            } catch (Exception $e) {
                $this->lastError = $e->getMessage();
                return false;
            }
        }

        return $catTags;
    }

    /**
     * Retrieve a list of File resources.
     *
     * @param $q
     * @return array List of Google_Service_Drive_DriveFile resources.
     *
     */
    public function getAllFilesInAppFolder($q)
    {
        $client = new Google_Client();
        $client->setClientId($this->params->google_client_id);
        $client->setClientSecret($this->params->google_client_secret);
        $client->setAccessToken($this->params->google_credentials);
        $service = new Google_Service_Drive($client);
        $result = array();
        $pageToken = null;
        $listfiles = array();
        do {
            try {
                $parameters = array();
                $parameters['q'] = $q;
                if ($pageToken) {
                    $parameters['pageToken'] = $pageToken;
                }
                $files = $service->files->listFiles($parameters);

                $result = array_merge($result, $files->getItems());
                $pageToken = $files->getNextPageToken();
            } catch (Exception $e) {
                $this->lastError = $e->getMessage();
                return false;
            }
        } while ($pageToken);
        foreach ($result as $k => $f) {
            $file = new stdClass();
            $file->id = $f->getId();
            $file->title = JFile::stripExt($f->getTitle());
            $file->ext = $f->fileExtension ? $f->fileExtension : JFile::getExt($f->originalFilename);
            $file->size = $f->getFileSize();
            $file->created_time = date('Y-m-d H:i:s', strtotime($f->getCreatedDate()));
            $file->modified_time = date('Y-m-d H:i:s', strtotime($f->getModifiedDate()));
            if ($f->fileExtension == null && $f->size == null && isset($f->id)) {
                $ExportLinks = $f->getExportLinks();
                if ($ExportLinks != null) {
                    uksort($ExportLinks, create_function('$a,$b', 'return strlen($a) < strlen($b);'));
                    $ext_tmp = explode('=', reset($ExportLinks));
                    $file->ext = end($ext_tmp);
                    $file->urlDownload = reset($ExportLinks);
                }
            }
            $file->created_time = $f->getCreatedDate();
            $file->modified_time = $f->getModifiedDate();
            $file->file_tags = $this->getFileTags($service, $file->id, 'file_tags', 'PRIVATE');

            $listfiles[] = $file;
        }
        return $listfiles;
    }

    /**
     * Print a file's parents.
     *
     * @param $fileId ID of the file to print parents for.
     * @return array|bool
     *
     */
    public function getParentInfo($fileId)
    {
        try {
            $client = new Google_Client();
            $client->setClientId($this->params->google_client_id);
            $client->setClientSecret($this->params->google_client_secret);
            $client->setAccessToken($this->params->google_credentials);
            $service = new Google_Service_Drive($client);
            $parents = $service->parents->listParents($fileId);

            $item = $parents->getItems();
            $parent_id = $item[0]->getId();
            $item_tmp = $this->getFileInfos($parent_id);
            return $item_tmp;
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    /**
     * Search condition on google
     *
     * @param $params
     * @param $q
     * @return string
     *
     */
    public function searchCondition($params, $q)
    {
        $folders = $this->getListFolder($params->get('google_base_folder'));
        foreach ($folders as $id => $folder) {
            $q .= " '" . $id . "' in parents or";
        }
        return $q;
    }
}
