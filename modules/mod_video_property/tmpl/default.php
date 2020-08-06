<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Iproperty
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Initialize variables
$usethumb           = $params->get('usethumb', 1);
$iplayout           = $params->get('iplayout', 'rows');
$rowspan            = ($iplayout == 'rows') ? 12 : (12 / $params->get('columns', '12'));
$moduleclass_sfx    = ($params->get('moduleclass_sfx')) ? ' '.htmlspecialchars($params->get('moduleclass_sfx')) : '';

?>

<div class="row-fluid<?php echo $moduleclass_sfx; ?>">
    <?php
    $colcount = 0;
    foreach($items as $video)
    {
        ?>
        <div class="ip-featuredproperties-holder span<?php echo $rowspan; ?>">
            <?php if($iplayout == 'rows') echo '<div class="span12">'; ?>
                <div class="ip-mod-thumb ip-featuredproperties-thumb-holder">
                    <?php
                $a = strstr($video->upload_video,"http");
                if($a){
                        $v = end(explode('=',$video->upload_video));
                        ?>
                        <span class="caption_module"><?php echo $video->caption;?></span>
                            {youtube}<?php echo $v;?>{/youtube}
                        </span>
                        <?php
                    } else {
                        $ext = end(explode('.',$video->upload_video));
                        $pathInfo = pathinfo($video->upload_video);
                        $video->upload_video = $pathInfo['filename'];
                        if($ext == 'mp4'){ ?>
                            {mp4}<?php echo $video->agent_id."/".$video->upload_video;?>{/mp4}
                        <?php } else if($ext == 'avi'){ ?>
                            {avi}<?php echo $video->agent_id."/".$video->upload_video;?>{/avi}
                        <?php } else if($ext == '3gp'){ ?>
                            {3gp}<?php echo $video->agent_id."/".$video->upload_video;?>{/3gp}
                        <?php } else if($ext == 'wmv'){ ?>
                            {wmv}<?php echo $video->agent_id."/".$video->upload_video;?>{/wmv}
                        <?php } else if($ext == 'flv'){ ?>
                            {flv}<?php echo $video->agent_id."/".$video->upload_video;?>{/flv}
                        <?php }
                    }
                ?>
                </div>
            <?php 
            if($iplayout == 'rows'){
                echo '</div>';
            }else{
                echo '<div class="clearfix"></div>';
            }
            ?>
        </div>
        <?php
        $colcount++;
        if($iplayout == 'rows' || ($iplayout == 'columns' && $colcount == $params->get('columns')))
        {
            $colcount = 0;
            echo '</div><div class="row-fluid'.$moduleclass_sfx.'">';
        }
    }
    ?>
</div>
