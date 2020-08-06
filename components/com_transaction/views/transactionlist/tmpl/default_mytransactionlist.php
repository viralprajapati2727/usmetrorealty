<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$document->addStyleSheet('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css');
$document->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js');
$document->addScript('http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js');
//echo "<pre>"; print_r($this->mydata); exit;
echo $this->loadTemplate('toolbar');
?>
<h1>My personal transaction</h1>
<div class="container">          
  <table class="table table-bordered">
      <tr>
        <th>Status</th>
        <td><?php echo $this->mydata->status;?></td>
      </tr>
      <tr>
        <th>MLS # </th>
        <td><?php echo $this->mydata->MLS;?></td>
      </tr>
      <tr>
        <th>Transaction # </th>
        <td><?php echo $this->mydata->transaction;?></td>
      </tr>
      <tr>
        <th>Transaction Type</th>
        <td><?php echo $this->mydata->transaction_type;?></td>
      </tr>
      <tr>
        <th>Listing Price</th>
        <td><?php echo $this->mydata->listing_price;?></td>
      </tr>
      <tr>
        <th>Listing Date</th>
        <td><?php echo $this->mydata->listing_date;?></td>
      </tr>
      <tr>
        <th>Sold Price</th>
        <td><?php echo $this->mydata->sold_price;?></td>
      </tr>
      <tr>
        <th>Sold Date</th>
        <td><?php echo $this->mydata->sold_date;?></td>
      </tr>
      <tr>
        <th>Address</th>
        <td><?php echo $this->mydata->address;?></td>
      </tr>
      <tr>
        <th>State</th>
        <td><?php echo $this->mydata->state;?></td>
      </tr>
      <tr>
        <th>City</th>
        <td><?php echo $this->mydata->city;?></td>
      </tr>
      <tr>
        <th>Zip</th>
        <td><?php echo $this->mydata->zip;?></td>
      </tr>
      <tr>
        <th>Buyer 1 Name</th>
        <td><?php echo $this->mydata->buyer1Name;?></td>
      </tr>
      <tr>
        <th>Buyer 2 Name</th>
        <td><?php echo $this->mydata->buyer2Name;?></td>
      </tr>
      <tr>
        <th>Buyers Full Address</th>
        <td><?php echo $this->mydata->buyersfulladdress;?></td>
      </tr>
      <tr>
        <th>Buyer Phone</th>
        <td><?php echo $this->mydata->buyer_phone;?></td>
      </tr>
      <tr>
        <th>Buyer's Agent</th>
        <td><?php echo $this->mydata->buyers_Agent;?></td>
      </tr>
      <tr>
        <th>Buyer's Agent Email</th>
        <td><?php echo $this->mydata->buyers_agent_email;?></td>
      </tr>
      <tr>
        <th>Buyer's Agent Phone</th>
        <td><?php echo $this->mydata->buyers_agent_phone;?></td>
      </tr>
      <tr>
        <th>Seller 1 Name</th>
        <td><?php echo $this->mydata->seller1Name;?></td>
      </tr>
      <tr>
        <th>Seller 2 Name</th>
        <td><?php echo $this->mydata->seller2Name;?></td>
      </tr>
      <tr>
        <th>Seller Full Address</th>
        <td><?php echo $this->mydata->sellersfulladdress;?></td>
      </tr>
      <tr>
        <th>Seller Phone</th>
        <td><?php echo $this->mydata->seller_phone;?></td>
      </tr>
      <tr>
        <th>Seller Agent</th>
        <td><?php echo $this->mydata->seller_Agent;?></td>
      </tr>
      <tr>
        <th>Seller's Agent Email</th>
        <td><?php echo $this->mydata->seller_agent_email;?></td>
      </tr>
      <tr>
        <th>Seller's Agent Phone</th>
        <td><?php echo $this->mydata->seller_agent_phone;?></td>
      </tr>
      <tr>
        <th>Closing Title/Escrow</th>
        <td><?php echo $this->mydata->closing_title_escrow;?></td>
      </tr>
      <tr>
        <th>Escrow Tran #</th>
        <td><?php echo $this->mydata->escrow_tran;?></td>
      </tr>
      <tr>
        <th>Title Full Address</th>
        <td><?php echo $this->mydata->title_full_ddress;?></td>
      </tr>
      <tr>
        <th>Title Phone</th>
        <td><?php echo $this->mydata->title_phone;?></td>
      </tr>
      <tr>
        <th>Title Agent</th>
        <td><?php echo $this->mydata->title_agent;?></td>
      </tr>
      <tr>
        <th>Title Email Address</th>
        <td><?php echo $this->mydata->title_email_address;?></td>
      </tr>
      <tr>
        <th>Commission Amount</th>
        <td><?php echo $this->mydata->commission_amount;?></td>
      </tr>
      <tr>
        <th>Commission Type</th>
        <td><?php echo $this->mydata->commission_type;?></td>
      </tr>
      <tr>
        <th>Earnest Money Amount</th>
        <td><?php echo $this->mydata->earnest_money_amount;?></td>
      </tr>
      <tr>
        <th>Earnest Money Held By</th>
        <td><?php echo $this->mydata->earnest_money_held_by;?></td>
      </tr>
      <tr>
        <th>Earnest Money Held By</th>
        <td><?php echo $this->mydata->home_warranty_provided;?></td>
      </tr>
      <tr>
        <th>Notes For Broker Instructions</th>
        <td><?php echo $this->mydata->notes_for_broker_instructions;?></td>
      </tr>
      <tr>
        <th>Agent Notes For This Transaction And For Office</th>
        <td><?php echo $this->mydata->agent_notes_for_transaction_Office;?></td>
      </tr>
      <tr>
        <th>File</th>
        <td><?php 
        $cnt=0;
        //checkimage($this->mydata->id);
        //echo "<pre>"; print_r($this->mydata->upload_files);
          //$a = explode(',',$this->mydata->upload_files);
          foreach ($this->myimages as $images) {
            //print_r($images);
            $path = substr($images->path, 1);
            $fname = substr($images->fname, 1);
             $main_path = JURI::root().$path.'/'.$fname.$images->type;
            //$a=explode('/',$value);
        ?>
        <a href="index.php?option=com_transaction&task=transaction.download&count=<?php echo $cnt; ?>&id=<?php echo $images->id;?>"><?php echo $fname.$images->type;?></a><br/>
        <?php $cnt++; } ?></td>
      </tr>
  </table>
</div>
<script>
//modalToggle
jQuery(".modalToggle").click(function(){
    jQuery('#modal').removeClass('hide');
    });

</script>

       