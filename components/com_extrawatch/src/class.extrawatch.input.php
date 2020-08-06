<?php

/**
 * @file
 * ExtraWatch - A real-time ajax monitor and live stats
 * @package ExtraWatch
 * @version 2.3
 * @revision 2650
 * @license http://www.gnu.org/licenses/gpl-3.0.txt     GNU General Public License v3
 * @copyright (C) 2016 by CodeGravity.com - All rights reserved!
 * @website http://www.extrawatch.com
 */

defined('_JEXEC') or die('Restricted access');

if (!defined("_EW_INPUT_IP")) define("_EW_INPUT_IP",1);//
if (!defined("_EW_INPUT_URL")) define("_EW_INPUT_URL",2);//
if (!defined("_EW_INPUT_URI")) define("_EW_INPUT_URI",3);
if (!defined("_EW_INPUT_XPATH")) define("_EW_INPUT_XPATH",4);
if (!defined("_EW_INPUT_ONE_STRING")) define("_EW_INPUT_ONE_STRING",5);//
if (!defined("_EW_INPUT_DIR")) define("_EW_INPUT_DIR",6);
if (!defined("_EW_INPUT_ENV")) define("_EW_INPUT_ENV",7);
if (!defined("_EW_INPUT_FILE_NAME")) define("_EW_INPUT_FILE_NAME",8);//
if (!defined("_EW_INPUT_TOKEN")) define("_EW_INPUT_TOKEN",9);
if (!defined("_EW_INPUT_REFERRER")) define("_EW_INPUT_REFERRER",10);
if (!defined("_EW_INPUT_FILE_PATH")) define("_EW_INPUT_FILE_PATH",11);
if (!defined("_EW_INPUT_FILE_EXTERNAL_PATH")) define("_EW_INPUT_FILE_EXTERNAL_PATH",12);//
if (!defined("_EW_INPUT_EXTRACT")) define("_EW_INPUT_EXTRACT",13);//
if (!defined("_EW_INPUT_QUERY_STRING")) define("_EW_INPUT_QUERY_STRING",14);
if (!defined("_EW_INPUT_HOST")) define("_EW_INPUT_HOST",15);//
if (!defined("_EW_INPUT_SCRIPT_NAME")) define("_EW_INPUT_SCRIPT_NAME",16);
if (!defined("_EW_INPUT_USER_AGENT")) define("_EW_INPUT_USER_AGENT",17);
if (!defined("_EW_INPUT_EMAIL")) define("_EW_INPUT_EMAIL",18);//
if (!defined("_EW_INPUT_REFERRER_SAME_SITE")) define("_EW_INPUT_REFERRER_SAME_SITE",19);//
if (!defined("_EW_INPUT_FILE_ROOT_PATH_HTACCESS")) define("_EW_INPUT_FILE_ROOT_PATH_HTACCESS",20);
if (!defined("_EW_INPUT_FILE_PATH_TMP")) define("_EW_INPUT_FILE_PATH_TMP",21);
if (!defined("_EW_INPUT_LANGUAGE")) define("_EW_INPUT_LANGUAGE",22);


if (!defined("_EW_ALLOWED_PARAMS_TO_EXTRACT")) define("_EW_ALLOWED_PARAMS_TO_EXTRACT", serialize (array (1=>"action", "click", "uri2titleId", "x", "y", "w","h","heatmapToken","xpath",
"extraWatchHeatmap", "extraWatchDay", "getParams","ip")));

if (!defined("_EW_IP_SEPARATOR")) define("_EW_IP_SEPARATOR",",");

if (!class_exists("ExtraWatchInput")) {

class ExtraWatchInput {

    public static function validate($type, $input = "") {
        switch ($type) {
            case _EW_INPUT_IP: {
                $inputModified = $input;
                 if (@strstr($inputModified, _EW_IP_SEPARATOR)) {  //has multiple entries splitted by "," ?
                     self::splitAndValidateMultipleIp($inputModified);
                     return $inputModified;
                 } else {
                $inputModified = self::replaceWildcardWithRealNumber($inputModified);
                if (!(
                    filter_var($inputModified, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ||
                    filter_var($inputModified, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)
                )) {
                    throw new ExtraWatchInputException(_EW_INPUT_IP, $input);
                }
                 }
                return $input;
            }
            case _EW_INPUT_URL: {
                if (!filter_var($input, FILTER_VALIDATE_URL)) {
                    throw new ExtraWatchInputException(_EW_INPUT_URL, $input);
                }
                return $input;
            }
            case _EW_INPUT_ONE_STRING: {
                if (@$input && !filter_var($input, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH)) {
                    throw new ExtraWatchInputException(_EW_INPUT_ONE_STRING, $input);
                }
                return $input;
            }
            case _EW_INPUT_FILE_PATH: {
                $dir = realpath(dirname(__FILE__).DS."..");
                if (!ExtraWatchHelper::startsWith($input, $dir)) {
                    throw new ExtraWatchInputException(_EW_INPUT_FILE_PATH, $input);
                }
                return $input;
            }
            case _EW_INPUT_FILE_PATH_TMP: {
                $dir = self::getUploadTmpDir();
                if (!ExtraWatchHelper::startsWith($input, $dir)) {
                    throw new ExtraWatchInputException(_EW_INPUT_FILE_PATH_TMP, $input);
                }
                return $input;

            }

            case _EW_INPUT_FILE_ROOT_PATH_HTACCESS; {
                $env = ExtraWatchEnvFactory::getEnvironment();
                $rootPath = $env->getRootPath();
                $dir = $rootPath.DS.".htaccess";
                if (!ExtraWatchHelper::startsWith($input, $dir)) {
                    throw new ExtraWatchInputException(_EW_INPUT_FILE_ROOT_PATH_HTACCESS, $input);
                }

                return $input;
            }
            case _EW_INPUT_EXTRACT: {
                if (@$input && $input != "getParams=") {
                    $object = @$input['params'];
                    if ($object) {
                        if ($object && $object != "getParams=") {
                            $getParams = ExtraWatchHelper::convertUrlQuery($object);
                            if ($getParams) {
                                foreach($getParams as $getParam => $getParamValue) {
                                    if ($getParam && !array_search($getParam, unserialize(_EW_ALLOWED_PARAMS_TO_EXTRACT))) {
                                        throw new ExtraWatchInputException(_EW_INPUT_EXTRACT, $getParam);
                                    }
                                }
                            } else {
                                $reflector = new ReflectionClass(get_class($object));
                                $classFileName = dirname($reflector->getFileName());
                                if (!self::validate(_EW_INPUT_FILE_PATH, $classFileName)) {
                                    throw new ExtraWatchInputException(_EW_INPUT_EXTRACT, $classFileName);
                                }
                            }
                        }
                    }
                }
                return $input;
                break;
            }
            case _EW_INPUT_EMAIL: {
                if (filter_var($input, FILTER_VALIDATE_EMAIL) === false) {
                    throw new ExtraWatchInputException(_EW_INPUT_EMAIL, $input);
                }
                return $input;
                break;
            }
            case _EW_INPUT_FILE_NAME: {
                $tmpUploadDir = self::getUploadTmpDir();
                $tmpUploadDir = str_replace("/",DS, $tmpUploadDir);
                if (!ExtraWatchHelper::startsWith($input['file']['tmp_name'], $tmpUploadDir)) {
                    throw new ExtraWatchInputException(_EW_INPUT_FILE_NAME, $input);
                }
                return $input;

            }
            case _EW_INPUT_FILE_EXTERNAL_PATH:
            case _EW_INPUT_DIR: {
                $env = ExtraWatchEnvFactory::getEnvironment();
                if (!ExtraWatchHelper::startsWith(realpath($input), realpath($env->getCMSFileSystemRootPath()))) {
                    throw new ExtraWatchInputException(_EW_INPUT_FILE_EXTERNAL_PATH, $input);
                }

                return $input;
            }

            case _EW_INPUT_HOST: {
                $isIpValid = (
                    filter_var($input, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ||
                    filter_var($input, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)
                );

                $isHostValid = (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $input) //valid chars check
                    && preg_match("/^.{1,253}$/", $input) //overall length check
                    && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $input)   ); //length of each label

                if (!($isIpValid || $isHostValid)) {
                    throw new ExtraWatchInputException(_EW_INPUT_HOST, $input);
                }
                return $input;
            }

            case _EW_INPUT_REFERRER_SAME_SITE: {
                $env = ExtraWatchEnvFactory::getEnvironment();
                if (!ExtraWatchHelper::startsWith($_SERVER["HTTP_REFERER"], $env->getCMSBaseURL())) {
                    throw new ExtraWatchInputException(_EW_INPUT_REFERRER_SAME_SITE, $_SERVER["HTTP_REFERER"]);
                }
                return $input;
            }
            case _EW_INPUT_LANGUAGE: {
                $input = ExtraWatchInput::validate(_EW_INPUT_ONE_STRING, $input);//prevent from using anything else than single string !
                $langDir = realpath(dirname(__FILE__).DS."..".DS."lang".DS.$input.".php");
                if (!file_exists($langDir)) {
                    throw new ExtraWatchInputException(_EW_INPUT_LANGUAGE, $langDir);
                }
                return $input;

            }

            default: {
            return $input;
            }

        }

    }

    /**
     * @return bool|string
     */
    public static function getUploadTmpDir()
    {
        return ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir();
    }

    /**
     * @param $input
     * @param $inputModified
     * @return mixed|string
     */
    private static function replaceWildcardWithRealNumber($inputModified)
    {
        if (strstr($inputModified,":")) {   //possibly a IPv6
            $inputModified = str_replace("*", "0", $inputModified);
        } else if (strstr($inputModified, ".*")) { //IPv4 - contains wildcard
            $dotCount = substr_count($inputModified, ".");
            if ($dotCount == 1) {
                $inputModified = str_replace(".*", ".0.0.0", $inputModified);
                return $inputModified; //replacing with some number
            } else if ($dotCount == 2) {
                $inputModified = str_replace(".*", ".0", $inputModified); //replacing with some number
                $inputModified .= ".0";
                return $inputModified;
            } else if ($dotCount == 3) {
                $inputModified = str_replace(".*", ".0", $inputModified);
                return $inputModified; //replacing with some number
            }return $inputModified;

        }
        return $inputModified;
    }

    /**
     * @param $inputModified
     */
    private static function splitAndValidateMultipleIp($inputModified)
    {
        $inputModifiedArray = explode(_EW_IP_SEPARATOR, $inputModified);
        foreach ($inputModifiedArray as $inputModifiedArrayEntry) {
            ExtraWatchInput::validate(_EW_INPUT_IP, trim($inputModifiedArrayEntry));
        }
    }


}
}


