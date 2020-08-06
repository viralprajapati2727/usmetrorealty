<?php
//echo "<pre>"; print_r($this->reply); exit;
?>
<style>
#jform_reply{
	width:auto !important;
    height:auto !important;
}
#jform_message{
    width:auto !important;
    height:auto !important;
}
</style>
<form action="index.php?option=com_transaction&view=transaction&layout=reply" method="post" name="adminForm" id="adminForm" class="form-validate ipform form-horizontal" enctype="multipart/form-data">
            <div class="btn-toolbar">
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary" onclick="Joomla.submitbutton('transaction.replyEmail')">Send</button>
                    <a href="index.php?option=com_transaction&view=transactionlist" class="btn btn-primary">Cancel</a>
                </div>
            </div>
        <div class="panel panel-default" style="width:100%; padding: 10px; margin: 10px">
        <div id="Tabs" role="tabpanel">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="active lastItem firstItem"><a href="#information" aria-controls="personal" role="tab" data-toggle="tab">
                    Reply</a></li>
            </ul>
            <div class="tab-content" style="padding-top: 20px">
            <div role="tabpanel" class="tab-pane active" id="information">
            <div class="control-group">
                        <div class="control-label">
                            <label id="jform_MLS" for="jform_MLS" class="hasTooltip" title="" data-original-title="Subject">MLS #</label>
                        </div>
                        <div class="controls">
                            <input name="jform[MLS]" id="jform_MLS" value="<?php echo $this->reply->MLS; ?>" type="text" readonly>                        
                            </div>
                </div>
                <div class="control-group">
                        <div class="control-label">
                            <label id="jform_transaction-lbl" for="jform_transaction" class="hasTooltip" title="" data-original-title="Transaction.">Transaction.</label>
                        </div>
                        <div class="controls">
                        	<input name="jform[transaction]" id="jform_transaction" value="<?php echo $this->reply->transaction;?>" type="text" readonly> 
                        </div>
                </div>
                <div class="control-group">
                        <div class="control-label">
                            <label id="jform_listing_date-lbl" for="jform_listing_date" class="hasTooltip" title="" data-original-title="Listing Date.">Listing No.</label>
                        </div>
                        <div class="controls">
                        	<input name="jform[listing_date]" id="jform_listing_date" value="<?php echo $this->reply->listing_date;?>" type="text" readonly> 
                        </div>
                </div>
                <div class="control-group">
                        <div class="control-label">
                            <label id="jform_subject-lbl" for="jform_subject" class="hasTooltip" title="" data-original-title="Subject.">Subject</label>
                        </div>
                        <div class="controls">
                        	<input name="jform[subject]" id="jform_subject" value="<?php echo $this->reply->subject;?>" type="text" readonly> 
                        </div>
                </div>
                <div class="control-group">
                        <div class="control-label">
                            <label id="jform_message-lbl" for="jform_message" class="hasTooltip" title="" data-original-title="Message.">Message</label>
                        </div>
                        <div class="controls">
                        <textarea name="jform[message]" id="jform_message" cols="60" rows="6" readonly><?php echo $this->reply->message;?></textarea>
                        </div>
                </div>
                <div class="control-group">
                        <div class="control-label">
                            <label id="jform_reply-lbl" for="jform_reply" class="hasTooltip" title="" data-original-title="Your Reply">Your Reply</label>
                        </div>
                        <div class="controls">
                            <textarea name="jform[reply]" id="jform_reply" cols="60" rows="6"></textarea>
                        </div>
                        <div class="controls">
                        	<input name="jform[id]" id="jform_id" value="<?php echo $this->reply->id;?>" type="hidden" readonly> 
                        </div>
                        <div class="controls">
                        	<input name="jform[agent_id]" id="jform_agent_id" value="<?php echo $this->reply->agent_id;?>" type="hidden" readonly> 
                        </div>
                        <div class="controls">
                            <input name="jform[transaction_id]" id="jform_transaction_id" value="<?php echo $this->reply->transaction_id;?>" type="hidden" readonly> 
                        </div>
                </div>
            </div>
            </div>
        </div>
        </div>

            <input name="task" value="transaction.replyEmail" type="hidden">
</form>