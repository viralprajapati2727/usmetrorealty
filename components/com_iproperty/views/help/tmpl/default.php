<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */
JHtml::_('behavior.modal');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', '.ipform select');

defined( '_JEXEC' ) or die( 'Restricted access');
echo $this->loadTemplate('toolbar');
?>
<h1><?php echo $this->msg; ?></h1>
<?php
$i = 1;
foreach ($this->answer as $value) {
$a1=strtotime($value->post_date);
    if(!empty($a1)){
        $date1=date('l,F d, Y - H:i:s A',$a1);
    }
?>

<div class="span12" style="margin-top:2%;border: 1px solid #000000;border-bottom-left-radius: 2em;width:80%" id="answer">
    <div class="span12">
        <span><strong style="font-size:25px"><?php echo $i;?></strong> Reply by <strong><?php echo $value->agent_name; ?></strong> Posted on <?php echo $date1;?></span>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <strong>Question:: </strong><span><?php echo $value->question;?></span>
        </div>
        <div class="span12">
            <strong>Answer:: </strong><span><?php echo $value->answer;?></span>
        </div>
    </div>
</div>

<?php $i++; } ?>
<!-- above code for question and answer -->
<div class="span8">
<form action="index.php?option=com_iproperty&view=help" method="post" name="adminForm" id="adminForm" class="form-validate ipform form-horizontal" enctype="multipart/form-data">
            <div class="btn-toolbar">
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary" onclick="Joomla.submitbutton('help.save')">Send</button>
                    <a href="index.php?option=com_iproperty&view=help" class="btn btn-primary">Cancel</a>
                </div>
            </div>
        <div class="panel panel-default" style="width:100%; padding: 10px; margin: 10px">
        <div id="Tabs" role="tabpanel">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="active"><a href="#information" aria-controls="personal" role="tab" data-toggle="tab">
                    Question</a></li>
            </ul>
            <div class="tab-content" style="padding-top: 20px">
            <div role="tabpanel" class="tab-pane active" id="information">
                <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('subject'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('subject'); ?>
                        </div>
                </div>
                <div class="control-group row-fluid">
                    <div class="span8">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('firstlast'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('firstlast'); ?>
                        </div>
                    </div>
                    <div class="span2">
                        <div id='loadingmessage' style="display:none;">
                            <img width="60" height="60" src='<?php echo JURI::base();?>media/com_iproperty/load.gif'/>
                        </div>
                    </div>
                </div>
                
                <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('listing_no'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('listing_no'); ?>
                        </div>
                </div>
                <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('question'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('question'); ?>
                        </div>
                </div>
            </div>
            </div>
        </div>
        </div>

            <input type="hidden" name="task" value="help.save">
</form>
</div>

<script type="text/javascript">
jQuery(function () {
        jQuery("#jform_firstlast").change(function () {
            var selectedText = jQuery(this).find("option:selected").text();
            var selectedValue = jQuery(this).val();
          	 jQuery("#jform_listing_no").html("").trigger("liszt:updated");
            jQuery('#loadingmessage').show();
           
          /* viral code
          jQuery.ajax({
                type:"GET",
                url : "index.php?option=com_iproperty&task=help.getListNumber",
                data : "fullname="+selectedValue,
                async: false,
                success : function(data) {
                   var obj = JSON.parse(data);
                   jQuery('#loadingmessage').hide();
                    jQuery("#jform_listing_no").html("<option value=''>Select Listing-Number</option>");
                    jQuery.each(obj, function(i, m){
                        console.log(m.text);
                            jQuery("#jform_listing_no").append("<option value='"+m.value+"'>"+m.text+"</option>").trigger( "liszt:updated" );
                    });
                },
                error: function() {
                    alert('Error occured');
                }
            });
          */ 
          // addd by mahesh
            var url = "index.php?option=com_iproperty&task=help.getListNumber&fullname="+selectedValue;
              jQuery.ajax({
                url: url, 
                dataType: 'json',        
                success:function(data){
                  jQuery("#jform_listing_no").html(data.option).trigger("liszt:updated");
                  jQuery('#loadingmessage').hide();
                }
              });
    	// addded  by mahesh  
          
          
          
          
        });
    });
</script>