

<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */
//echo "<pre>"; print_r($this->listmessage);exit;
defined( '_JEXEC' ) or die( 'Restricted access');
echo $this->loadTemplate('toolbar');
?>
<style>
#jform_reply{
    width:auto !important;
    height:auto !important;
}
.reply_form,.reply_button{
  margin-top: 20px;
}
.message_answer{
  border: 1px solid #000000;
  margin-top:2%;
}
.message_answer,.message_reply{
  margin-left: 3% !important;
}
.message_reply{
  border: 1px solid #000000;
  margin-top:2%;
}
.message_name{
  margin-top:2%;
  height: auto;
}
</style>
<div class="message_rep" id="message_reply">
<?php
$pspan = ($this->settings->show_propupdate) ? 6 : 5;
 foreach ($this->listmessage as $value) { 
  if($value->caption == 'Msg'){
    $class="message_answer";
  } else if($value->caption == 'Rep'){
    $class="message_reply";
  }
  ?>
  </div>
  <div class="span12 ">
  <?php if($value->caption == 'Msg'){?>
      <div class="span3 message_name">
            <div class="span12">
              <span><img src="<?php echo JURI::base().'media/com_iproperty/agents/kalimqamar2_1469880698.jpg'?>" alt="Usmerorealty"></span>
            </div>
      </div>
      <?php } ?>
    <div class="span6 <?php echo $class;?>" id="message_answer">
      <div class="span12">
      <?php if($value->caption == 'Msg'){?>
          <div class="span6">
            <span><strong style="">Message</span>
          </div>
        <?php } if($value->caption == 'Rep'){?>
            <div class="span6">
            <span><strong style="">Reply</span>
          </div>
          <?php } ?>
          <div class="span6">
            <p><?php echo $value->message;?></p>
          </div>
      </div>
      <div class="span12">
          <div class="span6">
            <span><strong style="">Post Date</strong></span>
          </div>
          <div class="span6">
            <span><?php echo $value->post_date;?></span>
          </div>
      </div>
  </div>
  <?php if($value->caption == 'Rep'){?>
  <div class="span3 message_name">
            <div class="span12">
              <span><img src="<?php echo JURI::base().'media/com_iproperty/agents/agent.png'?>" alt="Usmerorealty"></span>
            </div>
      </div>
  <?php } }?>
    <div class="reply_form span12">
      <form action="index.php?option=com_transaction&view=transaction&layout=listmessage" method="post" name="adminForm" id="adminForm" class="form-validate ipform form-horizontal" enctype="multipart/form-data">
            <div class="control-group ">
                        <div class="control-label">
                            <label id="jform_reply-lbl" for="jform_reply" class="hasTooltip" title="" data-original-title="Your Reply">Your Reply</label>
                        </div>
                        <div class="controls">
                            <textarea name="jform[reply]" id="jform_reply" cols="60" rows="6"></textarea>
                            <?php foreach ($this->listmessage as $val) { ?>
                              <input name="jform[agent_id]" id="jform_agent_id" value="<?php echo $val->agent_id;?>" type="hidden">
                              <input name="jform[transaction_id]" id="jform_transaction_id" value="<?php echo $val->transaction_id;?>" type="hidden">
                               <?php if($val->caption == 'Msg'){?>
                                  <input name="jform[message]" id="jform_message" value="<?php echo $val->message;?>" type="hidden">
                            <?php } } ?>
                        </div>
                </div>
                <div class="btn-toolbar reply_button">
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary" onclick="Joomla.submitbutton('transaction.replyEmail')">Send</button>
                    <a href="index.php?option=com_transaction&view=transactionlist" class="btn btn-primary">Cancel</a>
                </div>
            </div>
            <input name="task" value="transaction.replyEmail" type="hidden">
      </form>
    </div>
  </div>

  <style type="text/css">
#jform_answer{height:auto;width:auto;}
div #message_answer:hover:nth-child(odd)
{
    background: #D3D3D3;
}
div #message_answer:hover:nth-child(even)
{
    background: #A9A9A9;
}
</style>
      