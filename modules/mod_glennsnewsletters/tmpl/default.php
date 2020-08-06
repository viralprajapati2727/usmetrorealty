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
defined('_JEXEC') or die;

JHtml::_('bootstrap.framework');

$moduleclass_sfx	= $params->get('moduleclass_sfx', '');
$default_fold = $params->get('default_fold', 'images/newsletters');
$ht = $params->get('header_tag');
$set_listheight = $params->get('set_listheight');
$list_height = $params->get('list_height');

?>

<?php if ($set_listheight) : ?>
    <style>
        div.newsletter-inner {
		max-height: <?php echo $list_height; ?>px;
		overflow:hidden;
		overflow-y:scroll;
	}
    </style>
<?php endif ; ?>

<div class="mod_glennsnewsletters<?php echo $moduleclass_sfx; ?>" >
    <div id="myNewsletter<?php echo $module->id; ?>" class="newsletter" data-ride="newsletter">

        <div class="newsletter-inner">

			<?php foreach ($newsItems as $fitem) : ?>

				<p class="newsletter">
					<a class="newsletter" href="<?php echo $fitem->newsfile; ?>" target="_blank" alt="<?php echo $fitem->title; ?>">
						<?php echo $fitem->title; ?>
					</a>
				</p>

            <?php endforeach; ?>

    	</div>
	</div>
	<div style="clear:both;"></div>
</div>