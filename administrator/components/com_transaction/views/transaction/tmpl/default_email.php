<?php

/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */
//echo "<pre>"; print_r($this->reply);exit;
defined( '_JEXEC' ) or die( 'Restricted access');
//echo $this->loadTemplate('toolbar');
?>
<style type="text/css">
#jform_message{
    width:auto !important;
    height:auto !important;
}
.main_msg{width: 70%; margin: 0 auto;}
.admin_msg{overflow: hidden;}
.admin_msg .admin_img, .agent_msg .agent_img{width: 15%; float:left;height: 100px;}
.agent_msg .agent_img{float: right; text-align: right;}
.admin_msg .admin_img img, .agent_msg .agent_img img{width: 100px; height: 100px; border-radius: 100px;}
.admin_msg .admin_chat{
  width: 78%;
  float: right;
  padding: 30px 20px 20px 20px; 
  background: rgba(255, 198, 26 , 0.9);
  position: relative; 
  margin-bottom: 30px;
  border-radius: 10px;
  }
  .admin_msg .admin_chat p, , .agent_msg .agent_chat p {word-break: break-all;}
  .admin_msg .admin_chat:before{
    content: "";
    width: 0;
    height: 0;
    border-top: 0px solid transparent;
    border-right: 18px solid rgba(255, 198, 26 , 0.9);
    border-bottom: 15px solid transparent;
    display: inline-block;
    position: absolute;
    top: 35px;
    left: -17px;
  }
.agent_msg .agent_chat {
    width: 78%;
    padding: 30px 20px 20px 20px;
    background: rgba(47, 197, 234, 0.3);
    margin-bottom: 30px;
    position: relative;
    border-radius: 10px;
}
.agent_msg .agent_chat:after{
    content: "";
    width: 0;
    height: 0;
    border-top: 0px solid transparent;
    border-left: 18px solid rgba(47, 197, 234, 0.3);
    border-bottom: 15px solid transparent;
    display: inline-block;
    position: absolute;
    top: 35px;
    right: -18px;
}
.post_date h5, .post_date p{font-size: 14px; font-weight: 500; margin-bottom: 5px; display: inline-block;}
.post_date p{margin-left: 5px;}
.agent_chat .post_date, .admin_chat .post_date  {
    position: absolute;
    display: inline-block;
    right: 10px;
    top: 5px;
}
.agent_chat .post_date p, .admin_chat .post_date p{margin: 0; padding: 0; color: #9da29e; font-size: 12px;}
</style>

<div class="main_msg" id="main_msg">
<h1 style="text-align:center;">Transaction Messages</h1>
<?php
foreach ($this->reply as $value) { 
  if($value->caption == 'Msg'){
    $class="admin_msg";
  } else if($value->caption == 'Rep'){
    $class="agent_msg";
  }
  ?>
    <?php if($value->caption == 'Msg'){ ?>
  <div class="<?php echo $class; ?>">
    <div class="admin_img">
        <span><img src="<?php echo JURI::root().'media/com_iproperty/agents/kalimqamar2_1469880698.jpg'?>" alt="Usmerorealty"></span>
    </div>
    <div class="admin_chat">
        <p><?php echo $value->message;?></p>
        <div class="post_date">
         <!--  <h5>Post Date:</h5> -->
          <p><?php echo $value->post_date;?></p>
          <a href="#editModal" class="tr_id" data-id="<?php echo $value->id;?>"  data-value ="<?php echo $value->message;?>" data-toggle="modal">Edit</a>
        </div>
    </div>
    
  </div>
  <?php } ?>
  <?php if($value->caption == 'Rep'){ ?>
  <div class="<?php echo $class; ?>">
    <div class="agent_img">
              <span><img width="100" height="100" src="<?php echo JURI::root().'media/com_iproperty/agents/agent.png'?>" alt="Agent"></span>
    </div>
    <div class="agent_chat">
              <p><?php echo $value->message;?></p>
              <div class="post_date">
                <!-- <h5>Post Date:</h5> -->
                <p><?php echo $value->post_date;?></p>
      </div>
    </div>
    
  </div>
  <?php } ?>
<?php } ?>
    <div class="reply_form">
      <form action="index.php?option=com_transaction&view=transaction" method="post" name="adminForm" id="adminForm" class="form-validate ipform form-horizontal" enctype="multipart/form-data">
        <div class="control-group ">
                <div class="control-label">
                    <label id="jform_message-lbl" for="jform_message" class="hasTooltip" title="" data-original-title="Your Message">Your Message</label>
                </div>
                <div class="controls">
                    <textarea name="jform[message]" id="jform_message" cols="60" rows="6"></textarea>
                    <input name="jform[agent_id]" id="jform_agent_id" value="<?php echo $this->res->agent_id;?>" type="hidden" readonly>
                    <input name="jform[transaction_id]" id="jform_transaction_id" value="<?php echo $this->res->id;?>" type="hidden" readonly>
                    <input name="jform[MLS]" id="jform_MLS" value="<?php echo $this->res->MLS;?>" type="hidden" readonly>
                    <input name="jform[transaction]" id="jform_transaction" value="<?php echo $this->res->transaction;?>" type="hidden" readonly>
                    <input name="jform[listing_date]" id="jform_listing_date" value="<?php echo $this->res->listing_date;?>" type="hidden" readonly>
            </div>
            <div class="btn-toolbar reply_button">
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary" onclick="Joomla.submitbutton('transaction.messageEmail')">Send</button>
                    <a href="index.php?option=com_transaction&view=transaction" class="btn btn-primary">Cancel</a>
                </div>
            </div>
            <input name="task" value="transaction.messageEmail" type="hidden">
      </form>
    </div>
</div>

<div class="modal hide fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="false" style="display: none;">   
        <form name="editmessage" action="index.php?option=com_transaction&view=transaction&layout=listmessage&id=2" method="post" class="form-horizontal ip-editmessage-form">        
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="editModalLabel" class="visible visible-first">Edit Message</h3>
            </div>
            <div class="modal-body">            
                <div class="row-fluid">
                    <div class="clearfix"></div>
                    <div class="control-group">
                        <div class="control-label">Message</div>
                        <div class="controls">
                          <input name="jform[message_id]" id="jform_edit_id" type="hidden">
                          <input name="jform[agent_id]" id="jform_agent_id" value="<?php echo $this->res->agent_id;?>" type="hidden" readonly>
                          <input name="jform[transaction_id]" id="jform_transaction_id" value="<?php echo $this->res->id;?>" type="hidden" readonly>
                          <textarea name="jform[message]" id="jform_edit_message" cols="30" rows="6"></textarea>
                        </div>
                    </div>
                     
                </div> 
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                <button class="btn btn-primary" type="submit">Edit Message</button>
            </div>
            <input name="task" value="transaction.editComments" type="hidden">
    </form>            
</div>
<script type="text/javascript">
  jQuery(document).on('click', ".tr_id", function(e){
    var a = jQuery(this).attr('data-value');
    var b = jQuery(this).attr('data-id');
    jQuery('#jform_edit_message').val(a);
    jQuery('#jform_edit_id').val(b);

  });
</script>