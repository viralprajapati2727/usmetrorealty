<?php
//echo "<pre>"; print_r($this->reply); exit;
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
h1 {
  text-align: center;
}
</style>
<h1>Transaction Messages</h1>
<div class="message_reply" id="message_reply">
<?php
$pspan = ($this->settings->show_propupdate) ? 6 : 5;
 foreach ($this->reply as $value) { 
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
              <span><img src="<?php echo JURI::root().'media/com_iproperty/agents/kalimqamar2_1469880698.jpg'?>" alt="Usmerorealty"></span>
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
              <span><img src="<?php echo JURI::root().'media/com_iproperty/agents/agent.png'?>" alt="Usmerorealty"></span>
            </div>
      </div>
  <?php } }?>








 <div class="message_form span12">
<form action="index.php?option=com_transaction&view=transaction&layout=email" method="post" name="adminForm" id="adminForm" class="form-validate ipform form-horizontal" enctype="multipart/form-data">
        <div class="panel panel-default" style="width:100%; padding: 10px; margin: 10px">
        <div id="Tabs" role="tabpanel">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="active lastItem firstItem"><a href="#information" aria-controls="personal" role="tab" data-toggle="tab">
                    Message</a></li>
            </ul>
            <div class="tab-content" style="padding-top: 20px">
            <div role="tabpanel" class="tab-pane active" id="information">
                <div class="control-group">
                        <div class="control-label">
                            <label id="jform_MLS-lbl" for="jform_MLS" class="hasTooltip" title="" data-original-title="MLS.">MLS #</label>
                        </div>
                        <div class="controls">
                        	<input name="jform[MLS]" id="jform_MLS" value="<?php echo $this->res->MLS;?>" type="text" readonly> 
                        </div>
                </div>
                <div class="control-group">
                        <div class="control-label">
                            <label id="jform_transaction-lbl" for="jform_transaction" class="hasTooltip" title="" data-original-title="Transaction.">Transaction.</label>
                        </div>
                        <div class="controls">
                        	<input name="jform[transaction]" id="jform_transaction" value="<?php echo $this->res->transaction;?>" type="text" readonly> 
                        </div>
                </div>
                <div class="control-group">
                        <div class="control-label">
                            <label id="jform_listing_date-lbl" for="jform_listing_date" class="hasTooltip" title="" data-original-title="Listing Date.">Listing Date.</label>
                        </div>
                        <div class="controls">
                        	<input name="jform[listing_date]" id="jform_listing_date" value="<?php echo $this->res->listing_date;?>" type="text" readonly> 
                        </div>
                </div>
                <div class="control-group">
                        <div class="control-label">
                            <label id="jform_name-lbl" for="jform_name" class="hasTooltip" title="" data-original-title="Name.">Name</label>
                        </div>
                        <div class="controls">
                        	<input name="jform[name]" id="jform_name" value="<?php echo $this->res->fname." ".$this->res->lname;?>" type="text" readonly> 
                        </div>
                </div>
                <div class="control-group">
                        <div class="control-label">
                            <label id="jform_email-lbl" for="jform_email" class="hasTooltip" title="" data-original-title="Email.">Email</label>
                        </div>
                        <div class="controls">
                        	<input name="jform[email]" id="jform_email" value="<?php echo $this->res->email;?>" type="text" readonly> 
                        </div>
                </div>
                <div class="control-group">
                        <div class="control-label">
                            <label id="jform_question-lbl" for="jform_question" class="hasTooltip" title="" data-original-title="Your Message">Your Message</label>
                        </div>
                        <div class="controls">
                            <textarea name="jform[message]" id="jform_message" cols="60" rows="6"></textarea>
                        </div>
                        <div class="controls">
                        	<input name="jform[id]" id="jform_id" value="<?php echo $this->res->id;?>" type="hidden" readonly> 
                        </div>
                        <div class="controls">
                        	<input name="jform[agent_id]" id="jform_agent_id" value="<?php echo $this->res->agent_id;?>" type="hidden" readonly> 
                        </div>
                        <div class="btn-toolbar">
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary" onclick="Joomla.submitbutton('transaction.messageEmail')">Send</button>
                    <a href="index.php?option=com_transaction" class="btn btn-primary">Cancel</a>
                </div>
            </div>
        </div>
                </div>
            </div>
            </div>
        </div>


            <input name="task" value="transaction.messageEmail" type="hidden">
</form>
</div>
</div>