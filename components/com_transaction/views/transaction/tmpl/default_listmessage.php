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
$get_id = JRequest::getvar('id');
?>
<style type="text/css">
#jform_reply{
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
  background: rgba(255, 198, 26, 0.9);
  position: relative; 
  margin-bottom: 30px;
  border-radius: 10px;
  }
  .agent_chat > p {
    word-break: break-all;
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

$i = 1;
foreach ($this->listmessage as $value) { 
  if($value->caption == 'Msg'){
    $class="admin_msg";
    $admin_cnt += count($value->caption);
  } else if($value->caption == 'Rep'){
    $class="agent_msg";
    //$agent_cnt = count($value);
  }

  ?>
    <?php if($value->caption == 'Msg'){ ?>
  <div class="<?php echo $class; ?>">
    <div class="admin_img">
        <span><img src="<?php echo JURI::base().'media/com_iproperty/agents/kalimqamar2_1469880698.jpg'?>" alt="Usmerorealty"></span>
    </div>
    <div class="admin_chat">
        <p><?php echo $value->message;?></p>
        <div class="post_date">
         <!--  <h5>Post Date:</h5> -->
          <p><?php echo $value->post_date;?></p>
        </div>
    </div>
    
  </div>
  <?php } ?>
  <?php if($value->caption == 'Rep'){ ?>
  <div class="<?php echo $class; ?>">
    <div class="agent_img">
              <span><img width="100" height="100" src="<?php echo JURI::base().'media/com_iproperty/agents/agent.png'?>" alt="Agent"></span>
    </div>
    <div class="agent_chat">
              <p><?php echo $value->message;?></p>
              <div class="post_date">
                <!-- <h5>Post Date:</h5> -->
                <p><?php echo $value->post_date;?></p>
                <a href="#editModal" class="tr_id" data-id="<?php echo $value->id;?>"  data-value ="<?php echo $value->message;?>" data-toggle="modal">Edit</a>
      </div>
    </div>
    
  </div>
  <?php } ?>
<?php 
$i++ ; } 
?>
    <div class="reply_form">
      <form action="index.php?option=com_transaction&view=transaction&layout=listmessage" method="post" name="adminForm" id="adminForm" class="form-validate ipform form-horizontal" enctype="multipart/form-data">
        <div class="control-group ">
                <div class="control-label">
                    <label id="jform_reply-lbl" for="jform_reply" class="hasTooltip" title="" data-original-title="Your Reply">Your Reply</label>
                </div>
                <div class="controls">
                    <textarea name="jform[reply]" id="jform_reply" cols="60" rows="6"></textarea>
                    <input name="jform[agent_id]" id="jform_agent_id" value="<?php echo $this->listmessage[0]->agent_id;?>" type="hidden">
                    <input name="jform[transaction_id]" id="jform_transaction_id" value="<?php echo $this->listmessage[0]->transaction_id;?>" type="hidden">
                    <input name="jform[message]" id="jform_message" value="<?php echo $this->listmessage[$admin_cnt]->message;?>" type="hidden">
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

<div class="modal hide fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="false" style="display: none;">   
        <form name="editmessage" action="index.php?option=com_transaction&view=transaction&layout=listmessage&id=<?php echo $get_id;?>" method="post" class="form-horizontal ip-editmessage-form">        
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
	                        <input name="jform[reply_id]" id="jform_edit_id" type="hidden">
	                        <textarea name="jform[reply]" id="jform_edit_reply" cols="30" rows="6"></textarea>
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
		jQuery('#jform_edit_reply').val(a);
		jQuery('#jform_edit_id').val(b);

	});
</script>