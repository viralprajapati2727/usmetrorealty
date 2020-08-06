<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');
jimport('joomla.application.component.modellist');

class TransactionModelTransaction extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'p.id',
                'MLS', 'p.MLS',
				'transaction', 'p.transaction',
                'status', 'p.status',
                'listing_price', 'p.listing_price',
                'listing_date', 'p.listing_date'
			);
		}

		parent::__construct($config);
	}
	protected function getStoreId($id = '')
	{
		$id	.= ':'.$this->getState('filter.id');
		$id	.= ':'.$this->getState('filter.MLS');
        $id	.= ':'.$this->getState('filter.transaction');
        $id	.= ':'.$this->getState('filter.status');
        $id	.= ':'.$this->getState('filter.listing_price');
        $id	.= ':'.$this->getState('filter.listing_date');
		return parent::getStoreId($id);
	}
protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

        $filters = array('search', 'MLS', 'id', 'transaction', 'listing_price', 'listing_date', 'status');

		// Load the filter state.
        foreach ($filters as $f){
            $search = $app->getUserStateFromRequest($this->context.'.filter.'.$f, 'filter_'.$f);
            $this->setState('filter.'.$f, $search);
        }
		// List state information.
		parent::populateState('p.id', 'asc');
	}
	public function getTable($type = 'transaction', $prefix = 'transactionTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
public function getData(){
    		$db         = $this->getDbo();
			$query      = $db->getQuery(true);
    		$query->select(
			$this->getState(
				'list.select',
				'p.id as id, p.MLS, p.transaction, p.listing_price, p.listing_date, p.status, p.agent_id, p.is_approve' 
			)
		);
			$query->from('`#__transaction` AS p');

		$MLS = $this->getState('filter.MLS');
		if ($MLS) {
			$query->where('p.MLS = '.$db->Quote($MLS
			));
		}
		$transaction = $this->getState('filter.transaction');
		if ($transaction) {
			$query->where('p.transaction = '.$db->Quote($transaction
			));
		}
		$id = $this->getState('filter.id');
		if ($id) {
			$query->where('p.id = '.$db->Quote($id
			));
		}
		$listing_price = $this->getState('filter.listing_price');
		if ($listing_price) {
			$query->where('p.listing_price = '.$db->Quote($listing_price
			));
		}
		$listing_date = $this->getState('filter.listing_date');
		if ($listing_date) {
			$query->where('p.listing_date = '.$db->Quote($listing_date
			));
		}
		$status = $this->getState('filter.status');
		if ($status) {
			$query->where('p.status = '.$db->Quote($status
			));
		}
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('p.id = '.(int) substr($search, 3));
			}
			else {
				$search     = JString::strtolower($search);
                $search     = explode(' ', $search);
                $searchwhere   = array();

                if (is_array($search)){ //more than one search word
                    foreach ($search as $word){
                        $searchwhere[] = 'LOWER(p.id) LIKE '.$db->Quote( '%'.$db->escape( $word, true ).'%', false );
                        //echo "<pre>"; print_r($searchwhere); exit;
                        $searchwhere[] = 'LOWER(p.MLS) LIKE '.$db->Quote( '%'.$db->escape( $word, true ).'%', false );
                        $searchwhere[] = 'LOWER(p.transaction) LIKE '.$db->Quote( '%'.$db->escape( $word, true ).'%', false );
                        $searchwhere[] = 'LOWER(p.listing_price) LIKE '.$db->Quote( '%'.$db->escape( $word, true ).'%', false );
                        $searchwhere[] = 'LOWER(p.listing_date) LIKE '.$db->Quote( '%'.$db->escape( $word, true ).'%', false );
                        $searchwhere[] = 'LOWER(p.status) LIKE '.$db->Quote( '%'.$db->escape( $word, true ).'%', false );
                    }
                } else {
                    $searchwhere[] = 'LOWER(p.id) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false );
                  	$searchwhere[] = 'LOWER(p.MLS) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false );
                    $searchwhere[] = 'LOWER(p.transaction) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false );
                    $searchwhere[] = 'LOWER(p.listing_price) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false );
                    $searchwhere[] = 'LOWER(p.listing_date) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false );
                   	$searchwhere[] = 'LOWER(p.status) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false );
                }
                $query->where('('.implode( ' OR ', $searchwhere ).')');
			}
		}
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
     	$query->group('p.id');
		$query->order($db->escape($orderCol.' '.$orderDirn));
		$db->setQuery($query);
		$results = $db->loadObjectList();
		//echo "<pre>"; print_r($results); exit;

		foreach ($results as $k => $v) {
				//echo $v->id;
				$msg = $db->getQuery(true);
				$msg->select('transaction_id')
				->from($db->quoteName('#__transaction_message_reply'))
				->where($db->quoteName('transaction_id') . ' = ' . $db->quote($v->id));
	    		$db->setQuery($msg);
	    		//echo $msg; exit;
	    		$messages = $db->loadObjectList();
	    		//echo "<pre>"; print_r($messages); exit;
	    		$final = array();
		    		foreach ($messages as $msgkey => $msgvalue) {
						$final[] = $msgvalue->transaction_id;
					}	
				$im = implode(',',$final);
				$v->transaction_id = $im;
			}
			//echo "<pre>"; print_r($results); exit;
    		return $results;
	}
	public function delete($cid){
        if(empty($cid[0])){
			$app = JFactory::getApplication();
			$app->redirect(JURI::base().'index.php?option=com_transaction');
		}
		$db = & JFactory::getDBO();   
         $query = $db->getQuery(true);
         $query->delete();
         $query->from('#__transaction');
         $query->where('id IN('.implode(',', $cid).')');
         $db->setQuery($query);
         if (!$db->execute()) {
				JError::raiseError( 4711, 'Please try again' );
			}
			JFactory::getApplication()->enqueueMessage('Seccessfully Deleted');
			$app = JFactory::getApplication();
			$app->redirect(JURI::base().'index.php?option=com_transaction');
	}
	public function approve($cid){
		if(empty($cid[0])){
			$app = JFactory::getApplication();
			$app->redirect(JURI::base().'index.php?option=com_transaction');
		}
		//echo "<pre>"; print_r($cid); exit;
		$db = & JFactory::getDBO();   
        $query = $db->getQuery(true);
		//update data
        $conditions = 'id IN('.implode(',', $cid).')';
    	$fields = array('is_approve' .'='. 1);
     	$query->update($db->quoteName('#__transaction'))->set($fields)->where($conditions);
        $db->setQuery($query);
        //echo $query; exit;
		$result = $db->execute();
		if($result==true){
			$query->select('*');
			$query->from($db->quoteName('#__transaction'));
			$query->where('id IN('.implode(',', $cid).')');
			$db->setQuery($query);
			$res = $db->loadObjectlist();
			//echo "<pre>"; print_r($res); exit;
			foreach ($res as $val) {
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select('*');
				$query->from($db->quoteName('#__iproperty_agents'));
				$query->where($db->quoteName('id')." = ".$val->agent_id);
				$db->setQuery($query);
				$reslt = $db->loadObjectlist();
					// echo "<pre>"; print_r($reslt);
					foreach ($reslt as $value) {
						 
						$config = JFactory::getConfig();
						$params = JComponentHelper::getParams('com_transaction');
						$admin= $params->get('cmpny_email');
						$body_content= $params->get('email_editor');
						$from = $config->get( 'mailfrom' );

						$key = array("submitted", "[ADMINNAME]", "[SITENAME]", "[AGENTNAME]", "[ADMINTRANSACTIONLINK]", "[UTRANID_VAL]", "[MLSNO_VAL]", "[LISTPRI_VAL]", "[LISTDATE_VAL]", "[AGENTNAME]");

						$replace   = array("Approved", $config->get( 'fromname' ), $config->get( 'sitename' ), ucwords($value->fname)."  ".ucwords($value->lname), '<a href="'.JURI::base().'usmetrorealty">usmetrorealty</a>', $val->transaction, $val->MLS, $val->listing_price, $val->listing_date);
							$body =str_replace($key,$replace,$body_content);

						
						$mailer = JFactory::getMailer();
						$subject = 'Approved Mail';
						$fromname =$config->get( 'fromname' );
						$sender = array( 
						    $from,
						    $fromname
						);
						//echo "<pre>"; print_r($sender);
						$mailer->setSender($sender); 
						$mailer->addRecipient($admin);
						$mailer->setSubject($subject);
						$mailer->setBody($body);
						$mailer->isHTML(TRUE);
						$send = $mailer->Send();

						if ( $send !== true ) {
						    JFactory::getApplication()->enqueueMessage('errorr..!!');
						} else {
						   JFactory::getApplication()->enqueueMessage('Successfully Approved');
						}
					}
				}
		}
		$app = JFactory::getApplication();
		$app->redirect(JURI::base().'index.php?option=com_transaction');
	}
	public function disapprove($cid){
		if(empty($cid[0])){
			$app = JFactory::getApplication();
			$app->redirect(JURI::base().'index.php?option=com_transaction');
		}
		$db = & JFactory::getDBO();   
        $query = $db->getQuery(true);
        $conditions = 'id IN('.implode(',', $cid).')';
     	$fields = array('is_approve' .'='. 0);
     	$query->update($db->quoteName('#__transaction'))->set($fields)->where($conditions);
        $db->setQuery($query);
		$result = $db->execute();
		JError::raiseError( 4711, 'Disapprove' );
		$app = JFactory::getApplication();
		$app->redirect(JURI::base().'index.php?option=com_transaction');
	}
	public function getAgentdata($id,$agent_id,$transaction_id){
		//echo $id."**".$agent_id; exit;
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query
	    ->select(array('a.email,a.fname,a.lname,t.MLS,t.transaction,t.listing_date,t.id,t.agent_id'))
	    ->from($db->quoteName('#__iproperty_agents', 'a'))
	    ->join('INNER', $db->quoteName('#__transaction', 't') . ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('t.agent_id') . ')')
	    ->where($db->quoteName('t.agent_id') . ' = '.$agent_id)
	    ->where($db->quoteName('t.id') . ' = '.$id)
	    ->where($db->quoteName('t.status') . ' = '.$db->quote('open'))
	    ->order($db->quoteName('t.id') . ' ASC');
		$db->setQuery($query);
		$tr_data = $db->loadObject();
		//echo "<pre>"; print_r($tr_data);exit;
		return $tr_data;
	}
	public function getMessagedata($id){
		$app   = JFactory::getApplication();
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query="SELECT caption,message,id, post_date, transaction_id,agent_id FROM ( SELECT 'Msg' caption, message,id, post_date, transaction_id,agent_id FROM c4aqr_transaction_message UNION ALL SELECT 'Rep' caption, reply,id, post_date, transaction_id, agent_id FROM c4aqr_transaction_message_reply ) subquery WHERE transaction_id =".$id." ORDER BY post_date, FIELD(caption, 'Msg', 'Rep')";
			//echo $query; exit;
			$db->setQuery($query);
			$results = $db->loadObjectlist();
			//echo "<pre>"; print_r($results); exit;
			foreach ($results as $k => $v) {
				//echo $v->id;
				$msg = $db->getQuery(true);
				$msg->select('*')
				->from($db->quoteName('#__iproperty_agents'))
				->where($db->quoteName('id') . ' = ' . $db->quote($v->agent_id));
	    		$db->setQuery($msg);
	    		$messages = $db->loadObjectList();
	    		$final = array();
		    		foreach ($messages as $msgkey => $msgvalue) {
						$final[] = $msgvalue->fname." ".$msgvalue->lname;
					}	
				$im = implode(',',$final);
				$v->name = $im;
			}
    		return $results;
	}
	public function messageEmail($value){
		//echo "<pre>"; print_r($value); exit;
		$config = JFactory::getConfig();
		$adminEmail = $config->get( 'mailfrom' );
		$adminname = $config->get( 'fromname' );
//echo $adminEmail."---".$adminname; exit;
		$user = JFactory::getUser();
		$app   = JFactory::getApplication();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$curr = date("Y-m-d h:i:sa");
		$columns = array('message','status', 'transaction_id','agent_id','post_date' );
		//var_dump($columns);exit;
		$values = array($db->quote($value['message']),$db->quote('open'),$db->quote($value['transaction_id']), $db->quote($value['agent_id']),$db->quote($curr));
		$query
	    ->insert($db->quoteName('#__transaction_message'))
	    ->columns($db->quoteName($columns))
	    ->values(implode(',', $values));
	    $db->setQuery($query);
	    //echo $query; exit;

	    		
				//echo "<pre>"; print_r($user_data); exit;
	    		
	    if ( $db->execute() !== true ) {
		    JError::raiseError( 4711, 'A severe error occurred' );
		} else {
			$user = $db->getQuery(true);
			$user->select('email');
			$user->from($db->quoteName('#__iproperty_agents'));
			$user->where($db->quoteName('id')." = ".$value['agent_id']);
			$db->setQuery($user);
			$user_data = $db->loadObject();
				
			$mailer = JFactory::getMailer();
			$subject = "Message Received";
			$from   = $adminEmail;
			$transaction_id = $value['transaction_id'];
			//echo $transaction_id; exit;
			$fromname ='Usmetrorealty';
			$body = "You have one message receive from <strong>".$adminname."</strong>.<br/>
					<strong>MLS # </strong>:- ".$value['MLS']."<br/>
					<strong>transaction # </strong>:- ".$value['transaction']."<br/>
					<strong>Listing Date </strong>:- ".$value['listing_date']."<br/>
					<strong>Message </strong>:- ".$value['message']."<br/>
					 Link::<a href=''".JURI::base()."'usmetrorealty/index.php?option=com_transaction&view=transaction&layout=listmessage&id=".$transaction_id."'>Message Reply</a>
					 <div style='margin-top:150px'><strong>Regards</strong><br/><p>".$adminname."</p></div>";
					 //echo $body; exit;
			$sender = array( 
				$from,
				$fromname
			);
		//var_dump($sender); exit;
			//echo $body; exit;
			$mailer->setSender($sender); 
			$mailer->addRecipient($user_data->email);
			$mailer->setSubject($subject);
			$mailer->setBody($body);
			$mailer->isHTML(TRUE);
			$send = $mailer->Send();
			if ( $send !== true ) {
				JFactory::getApplication()->enqueueMessage('Mail not send');
			} else {
				JFactory::getApplication()->enqueueMessage('Comment SuccessFully Sent');
			}
		}
		$allDone =& JFactory::getApplication();
		$allDone->redirect('index.php?option=com_transaction&view=transaction&layout=email&id='.$value["transaction_id"].'&agent_id='.$value['agent_id']);

	}
	function getmyData($id){
			$app   = JFactory::getApplication();
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*')
			->from($db->quoteName('#__transaction'))
			->where($db->quoteName('id') . ' = ' . $db->quote($id));
    		$db->setQuery($query);
    		$results = $db->loadObject();
    		//echo "<pre>"; print_r($results); exit;
    		return $results;
	}
	function getmyimages($id){
			$app   = JFactory::getApplication();
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*')
			->from($db->quoteName('#__transaction_images'))
			->where($db->quoteName('transaction_id') . ' = ' . $db->quote($id));
    		$db->setQuery($query);
    		$results = $db->loadObjectlist();
    		return $results;
	}
	function download($id){
			$link=JRequest::getvar('count');
			$app   = JFactory::getApplication();
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*')
			->from($db->quoteName('#__transaction_images'))
			->where($db->quoteName('id') . ' = ' . $db->quote($id));
    		$db->setQuery($query);
    		$results = $db->loadObject();
    		//echo "<pre>"; print_r($results); exit;
    		/*$a = explode(',',$results->upload_files);
	        foreach ($a as $k=>$value) {
	          	if($k==$link){
	          		$a=explode('/',$value);
	          		$name=$a[count($a)-1];
	          	}
	     	}*/
	     	$path = substr($results->path,1); 
	     	$name = $results->fname.$results->type;
	     	//echo $name; exit;
	     	$final_path = JURI::root().$path.$name;
	     	//echo $final_path; exit;
	     	$headers  = get_headers($final_path, 1);
	     	//echo "<pre>"; print_r($headers); exit;
			$fsize    = $headers['Content-Length'];

	     	//echo filesize($final_path).'M'; exit;
    		header('Content-Description: File Transfer');
		    header('Content-Type: application/force-download');
		    header("Content-Disposition: attachment; filename=\"" . basename($name) . "\";");
		    header('Content-Transfer-Encoding: binary');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . $fsize);
		    ob_clean();
		    flush();
		    readfile($final_path); //showing the path to the server where the file is to be download
		    exit;
	}
	public function update($value){
		//echo "<pre>"; print_r($_REQUEST); exit;
		$id = JRequest::getvar('id');
		//echo $id; exit;
		/*$object = new stdClass();
		// Must be a valid primary key value.
		$object->id = 1;
		$object->title = 'My Custom Record';
		$object->description = 'A custom record being updated in the database.';
		 
		// Update their details in the users table using id as the primary key.
		$result = JFactory::getDbo()->updateObject('#__custom_table', $object, 'id');*/

		$object = new stdClass();

		$object->id = $id;
		$object->MLS = $value['MLS'];
		$object->transaction_type = $value['transaction_type'];
		$object->listing_price = $value['listing_price'];
		$object->listing_date = $value['listing_date'];
		$object->sold_price = $value['sold_price'];
		$object->sold_date = $value['sold_date'];
		$object->address = $value['address'];
		$object->city = $value['city'];
		$object->zip = $value['zip'];
		$object->buyer1Name = $value['buyer1Name'];
		$object->buyer2Name = $value['buyer2Name'];
		$object->buyersfulladdress = $value['buyersfulladdress'];
		$object->buyer_phone = $value['buyer_phone'];
		$object->buyers_Agent = $value['buyers_Agent'];
		$object->buyers_agent_email = $value['buyers_agent_email'];
		$object->buyers_agent_phone = $value['buyers_agent_phone'];
		$object->seller1Name = $value['seller1Name'];
		$object->seller2Name = $value['seller2Name'];
		$object->sellersfulladdress = $value['sellersfulladdress'];
		$object->seller_phone = $value['seller_phone'];
		$object->seller_Agent = $value['seller_Agent'];
		$object->seller_agent_email = $value['seller_agent_email'];
		$object->seller_agent_phone = $value['seller_agent_phone'];
		$object->closing_title_escrow = $value['closing_title_escrow'];
		$object->escrow_tran = $value['escrow_tran'];
		$object->title_full_ddress = $value['title_full_ddress'];
		$object->title_phone = $value['title_phone'];
		$object->title_agent = $value['title_agent'];
		$object->title_email_address = $value['title_email_address'];
		$object->commission_amount = $value['commission_amount'];
		$object->commission_type = $value['commission_type'];
		$object->earnest_money_amount = $value['earnest_money_amount'];
		$object->home_warranty_provided = $value['home_warranty_provided'];
		$object->agent_notes_for_transaction_Office = $value['agent_notes_for_transaction_Office'];
		$object->status = 'open';
		$object->listing_price = $value['listing_price'];

		$result = JFactory::getDbo()->updateObject('#__transaction', $object, 'id');
		if (!$result) {
			JFactory::getApplication()->enqueueMessage('Please try again..');
		} else {
			$user = JFactory::getUser();
			$config = JFactory::getConfig();
			$mailer = JFactory::getMailer();
			$subject = 'Transaction Updated';
			$from   = $user->email;
			$adminEmail = $config->get( 'mailfrom' );
			//echo $adminEmail; exit;
			$language = JFactory::getLanguage();
            $language->load('com_iproperty', JPATH_SITE, 'en-GB', true);
			$body = sprintf(JText::_('COM_IPROPERTY_TRANSACTION_EMAIL_BODY'),
            ucwords($res->fname)."  ".ucwords($res->lname),
            $config->get( 'fromname' ), 
            $config->get( 'sitename' ), 
            $_REQUEST['transaction'],
            $value['MLS'],
            $value['listing_price'],
            $value['listing_date']);
            //var_dump($body); exit;
            //echo $body; exit;
            $sender = array($from);
            
			$mailer->setSender($sender); 
			$mailer->addRecipient($adminEmail);
			$mailer->setSubject($subject);
			$mailer->setBody($body);
			$mailer->isHTML(TRUE);
			$send = $mailer->Send();
			if($send){
				JFactory::getApplication()->enqueueMessage('Your transaction successFully updated');	
				$allDone =& JFactory::getApplication();
				$allDone->redirect('index.php?option=com_transaction&view=addtransaction&layout=transactionedit&id='.$id);
			}	
		}
	}
	public function editComments($value){
		//echo "<pre>"; print_r($value); exit;
		$config = JFactory::getConfig();
		$adminEmail = $config->get( 'mailfrom' );
		$adminname = $config->get( 'fromname' );

		$user = JFactory::getUser();
		$app   = JFactory::getApplication();
		$db = JFactory::getDbo();

		$object = new stdClass();

		$object->id = $value['message_id'];
		$object->message = $value['message'];
		$result = JFactory::getDbo()->updateObject('#__transaction_message', $object, 'id');
		if (!$result) {
			JFactory::getApplication()->enqueueMessage('Please try again..');
		} else {
			$user = $db->getQuery(true);
			$user->select('email');
			$user->from($db->quoteName('#__iproperty_agents'));
			$user->where($db->quoteName('id')." = ".$value['agent_id']);
			$db->setQuery($user);
			$user_data = $db->loadObject();
				
			$mailer = JFactory::getMailer();
			$subject = "Updated Message Received";
			$from   = $adminEmail;
			$transaction_id = $value['transaction_id'];
			//echo $transaction_id; exit;
			$fromname ='Usmetrorealty';
			$body = "You have one message receive from <strong>".$adminname."</strong>.<br/>
					<strong>Message </strong>:- ".$value['message']."<br/>
					 Link::<a href='".JURI::base()."'usmetrorealty/index.php?option=com_transaction&view=transaction&layout=listmessage&id=".$transaction_id."'>Message Reply</a>
					 <div style='margin-top:150px'><strong>Regards</strong><br/><p>".$adminname."</p></div>";
					 //echo $body; exit;
			$sender = array( 
				$from,
				$fromname
			);
		//var_dump($sender); exit;
			//echo $body; exit;
			$mailer->setSender($sender); 
			$mailer->addRecipient($user_data->email);
			$mailer->setSubject($subject);
			$mailer->setBody($body);
			$mailer->isHTML(TRUE);
			$send = $mailer->Send();
			if ( $send !== true ) {
				JFactory::getApplication()->enqueueMessage('Mail not send');
			} else {
				JFactory::getApplication()->enqueueMessage('Updated comments SuccessFully Sent');
			}
		$allDone =& JFactory::getApplication();
		$allDone->redirect('index.php?option=com_transaction&view=transaction&layout=email&id='.$value["transaction_id"].'&agent_id='.$value["agent_id"]);
		}
	}
}