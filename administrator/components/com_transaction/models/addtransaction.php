<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Registration model class for Users.
 *
 * @since  1.6
 */
class transactionModeladdtransaction extends JModelForm {

	public function getForm($data = array(), $loadData = true) {
		// Get the form.
		// $loadData = JFactory::getApplication()->getUserState('com_register.register', array());
		$form = $this->loadForm('com_transaction.addtransaction', 'addtransaction', array('control' => 'jform', 'load_data' => $loadData));
		// When multilanguage is set, a user's default site language should also be a Content Language
		if (JLanguageMultilang::isEnabled()){
			$form->setFieldAttribute('language', 'type', 'frontend_language', 'params');
		}
		//echo "<pre>"; print_r($form); exit;
		if (empty($form)){
			return false;
		}
		return $form;
	}
	public function getAgents(){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

        $query->select('a.*, a.id as id, c.id AS companyid, c.name AS companyname, CONCAT_WS(" ",fname,lname) AS name, c.alias as co_alias')
            ->from('#__iproperty_agents as a')
            ->leftJoin('#__iproperty_companies as c on c.id = a.company');
        $query->leftJoin('#__iproperty_agentmid as am ON am.agent_id = a.id');
        $query->where('a.state = 1 AND c.state = 1');
        $query->where('a.agent_type = 1');
        $query->group('a.id');
       $db->setQuery($query);
       //echo $query; exit;
       $agents= $db->loadObjectlist();
       //echo "<pre>"; print_r($agents); exit;
        return $agents;
	}
	function save($value1){
		//echo "<pre>"; print_r($value1); exit;
		$app   = JFactory::getApplication();
		$user = JFactory::getUser();
		
		$db = JFactory::getDbo();

		JTable::addIncludePath(JPATH_COMPONENT . '/tables');
		$row = JTable::getInstance('addtransaction', 'Table', array());
		//echo "<pre>"; print_r($row); exit;
		if($value->agent_id){
			$id_agent = $value->agent_id;
		} else {
			$id_agent = $user->id;
		}

		$params = JComponentHelper::getParams('com_transaction');

        $arizona=array('transaction'=>$params->get('arizona_transaction'));
        $oregon=array('transaction'=>$params->get('oregon_transaction'));
        $washington=array('transaction'=>$params->get('washington_transaction'));
        $value2 = array('status'=>'open','agent_id'=>$id_agent,'from_admin'=>'true');
		$value1 = array_merge($value1,$value2);
		//echo "<pre>"; print_r($value1); exit;
        $query1 = $db->getQuery(true);
		$query1->select('*')
			->from($db->quoteName('#__transaction'))
			->where($db->quoteName('state') . ' = \'' .$value1['state'].'\'')
			->order('id DESC')
			->setLimit(1);
    		$db->setQuery($query1);
    		$results = $db->loadObject();
    	//echo "<pre>"; print_r($results); exit;
		if($value1['state']=='AZ'){ 
			if($results->state=='AZ'){
	    		$last_data=substr($results->transaction,11);
	    		$last=$last_data + 1;
	    		$final =array('transaction'=>str_replace($last_data,$last,$results->transaction));
	    		$value = array_merge($value1,$final);
    		} else {
				$value = array_merge($value1,$arizona);
				//echo "<pre>"; print_r($value); exit;
    		}		
		} else if($value1['state']=='OR'){
			if($results->state=='AZ'){
	    		$last_data=substr($results->transaction,11);
	    		$last=$last_data + 1;
	    		$final =array('transaction'=>str_replace($last_data,$last,$results->transaction));
	    		$value = array_merge($value1,$final);
    		} else {
				$value = array_merge($value1,$oregon);
    		}
		}else if($value1['state']=='WA'){
    		if($results->state=='WA'){
	    		$last_data=substr($results->transaction,11);
	    		$last=$last_data + 1;
	    		$final =array('transaction'=>str_replace($last_data,$last,$results->transaction));
	    		$value = array_merge($value1,$final);
    		} else {
				$value = array_merge($value1,$washington);
    		}
   		}
   		//echo "<pre>"; print_r($value); exit;
		$row->bind($value);
		$row->store($value);
		//get last id 
		$query3 = $db->getQuery(true);
		$query3
    	->select('MAX(id) as m_id')
    	->from('#__transaction');
    	$db->setQuery($query3);
    	$last = $db->loadObject();
    			//file upload code
		    	$path = JPATH_SITE.'/media/com_iproperty/transactions';
				$file_ext   = array('jpeg','png','gif','jpg','pdf','doc','docx','xlsx','xls','txt');
				foreach ($_FILES['jform']['name']['upload_files'] as $k=>$v) {
					$photo_name = $_FILES['jform']['name']['upload_files'][$k];
					$photo_size = $_FILES['jform']['size']['upload_files'][$k];
					$photo_tmp = $_FILES['jform']['tmp_name']['upload_files'][$k];
					$photo_error= $_FILES['jform']['error']['upload_files'][$k];

					$ext = end((explode(".", $photo_name)));
				
					$photo_path[]=$path.'/'.$last->m_id.'/'.$photo_name;
					if((($ext == 'jpeg') || ($ext == 'gif')   ||
					   ($ext == 'png') || ($ext == 'jpg') || ($ext == 'pdf')   ||
					   ($ext == 'doc') || ($ext == 'docx') || ($ext == 'xlsx')   ||
					   ($ext == 'xls') || ($ext == 'txt') && $photo_size < 200000000000)){
						if(!file_exists($path.'/'.$last->m_id.'/'.$photo_name)){
							$a = mkdir($path.'/'.$last->m_id, 0777, true);
							move_uploaded_file($photo_tmp,$path.'/'.$last->m_id.'/'.$photo_name);
						}
					}
				}
				$implode_path=implode(',',$photo_path);
				$query4 = $db->getQuery(true);
				$conditions = array($db->quoteName('id') . ' = '.$last->m_id);
				$fields = array($db->quoteName('upload_files') . ' = \'' .$implode_path.'\'');
				$query4->update($db->quoteName('#__transaction'))->set($fields)->where($conditions);
		 		//echo $query4; exit;
				$db->setQuery($query4);
				$result = $db->execute();
				//file upload code end
					$mailer = JFactory::getMailer();
					$config = JFactory::getConfig();
					$subject = 'Transaction Saved';
					$from   = $user->email;
					
					$config = JFactory::getConfig();
					$adminEmail = $config->get( 'mailfrom' );
					$body_content = $params->get('email_editor');
					$link='<a href="'.JURI::base().'usmetrorealty">usmetrorealty</a>';
					
					$language = JFactory::getLanguage();
                    $language->load('com_iproperty', JPATH_SITE, 'en-GB', true);
					$body = sprintf(JText::_('COM_IPROPERTY_TRANSACTION_EMAIL_BODY'),
	                ucwords($res->fname)."  ".ucwords($res->lname),
	                $config->get( 'fromname' ), 
	                $config->get( 'sitename' ), 
	                $link, 
	                $value['transaction'],
	                $value['MLS'],
	                $value['listing_price'],
	                $value['listing_date']);
					
					$sender = array( 
					    $from
					);
					$mailer->setSender($sender); 
					$mailer->addRecipient($adminEmail);
					$mailer->setSubject($subject);
					$mailer->setBody($body);
					$mailer->isHTML(TRUE);
					$send = $mailer->Send();
						//echo "<pre>"; print_r($send); exit;
						if ( $send !== true ) {
						    JFactory::getApplication()->enqueueMessage('errorr..!!');
						} else {
						    JFactory::getApplication()->enqueueMessage('Transaction added & Mail Sent');
						}

						$allDone =& JFactory::getApplication();
						$allDone->redirect('index.php?option=com_transaction&view=transaction');
	}
	function getData(){
			$app   = JFactory::getApplication();
			$db = JFactory::getDbo();
			
			//$user = JFactory::getUser();
			$user = JFactory::getUser();
			

			$query1 = $db->getQuery(true);
			$query1->select('*');
			$query1->from($db->quoteName('#__iproperty_agents'));
			$query1->where($db->quoteName('email')." = ".$db->quote($user->email));
			$db->setQuery($query1);
			$res = $db->loadObject();
			$query = $db->getQuery(true);
			$query->select('*')
			->from($db->quoteName('#__transaction'))
			->where($db->quoteName('agent_id') . ' = ' . $db->quote($res->id));
    		$db->setQuery($query);
    		$results = $db->loadObjectList();
//echo "<pre>"; print_r($results); exit;
    		
			foreach ($results as $k => $v) {
				//echo $v->id;
				$msg = $db->getQuery(true);
				$msg->select('*')
				->from($db->quoteName('#__transaction_message'))
				->where($db->quoteName('transaction_id') . ' = ' . $db->quote($v->id));
	    		$db->setQuery($msg);
	    		$messages = $db->loadObjectList();
	    		$final = array();
		    		foreach ($messages as $msgkey => $msgvalue) {
						$final[] = $msgvalue->id;
					}	
				$im = implode(',',$final);
				$v->transaction_id = $im;
			}
    		return $results;
	}
	function getlistmessage($id){
			$app   = JFactory::getApplication();
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query="SELECT caption,message, post_date, transaction_id,agent_id FROM ( SELECT 'Msg' caption, message, post_date, transaction_id,agent_id FROM c4aqr_transaction_message UNION ALL SELECT 'Rep' caption, reply, post_date, transaction_id, agent_id FROM c4aqr_transaction_message_reply ) subquery WHERE transaction_id =".$id." ORDER BY post_date, FIELD(caption, 'Msg', 'Rep')";
			//echo $query; exit;
			$db->setQuery($query);
			$results = $db->loadObjectlist();
			//echo "<pre>"; print_r($tr_data); exit;
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
	function getreply($id,$transaction_id){
		//echo $transaction_id; exit;
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query
		    ->select(array('t.MLS,t.transaction,t.listing_date,t.id,t.agent_id,tm.*'))
		    ->from($db->quoteName('#__transaction', 't'))
		    ->join('INNER', $db->quoteName('#__transaction_message', 'tm') . ' ON (' . $db->quoteName('t.id') . ' = ' . $db->quoteName('tm.transaction_id') . ')')
		    ->where($db->quoteName('t.id') . ' = '.$transaction_id)
		    ->where($db->quoteName('tm.id') . ' = '.$id)
		    ->where($db->quoteName('tm.status') . ' = '.$db->quote('open'))
		    ->order($db->quoteName('t.id') . ' ASC');
			$db->setQuery($query);
			echo
			$tr_data = $db->loadObject();
    		return $tr_data;
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
	function getedit($id){
		$app   = JFactory::getApplication();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')
		->from($db->quoteName('#__transaction'))
		->where($db->quoteName('id') .'='.$id);
		$db->setQuery($query);
		$results = $db->loadObject();
		//echo "<pre>"; print_r($results); exit;
		return $results;
	}
	public function replyEmail($value){
			//echo "<pre>"; print_r($value); exit;
		$config = JFactory::getConfig();
		$adminEmail = $config->get( 'mailfrom' );
		$adminname = $config->get( 'fromname' );

		$app   = JFactory::getApplication();
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__iproperty_agents'));
		$query->where($db->quoteName('email')." = ".$db->quote($user->email));
		$db->setQuery($query);
		$res = $db->loadObject();


		$reply = $db->getQuery(true);
		$columns = array('reply','transaction_id','agent_id','post_date' );
		//var_dump($columns);exit;
		$values = array($db->quote($value['reply']),$db->quote($value['transaction_id']),$db->quote($value['agent_id']));
		$reply
	    ->insert($db->quoteName('#__transaction_message_reply'))
	    ->columns($db->quoteName($columns))
	    ->values(implode(',', $values).',NOW()');
	    $db->setQuery($reply);
	    //echo $reply; exit;
	    if ( $db->execute() !== true ) {
		    JError::raiseError( 4711, 'A severe error occurred' );
		} else {
			$mailer = JFactory::getMailer();
			$subject = "Message-Reply Received";
			$from   = $user->email;
			$fromname ='Usmetrorealty';
			$body = "You have one message-reply receive from <strong>".$res->fname." ".$res->lname."</strong>.<br/>
					<strong>Message </strong>:- ".$value['message']."<br/>
					<strong>Reply </strong>:- ".$value['reply']."<br/>
					 Link::
					 <div style='margin-top:150px'><strong>Regards</strong><br/><p>".$adminname."</p></div>";

			$sender = array( 
				$from,
				$fromname
			);
			$mailer->setSender($sender); 
			$mailer->addRecipient($adminEmail);
			$mailer->setSubject($subject);
			$mailer->setBody($body);
			$mailer->isHTML(TRUE);
			$send = $mailer->Send();
			if ( $send !== true ) {
				JFactory::getApplication()->enqueueMessage('Mail not send');
			} else {
				JFactory::getApplication()->enqueueMessage('Reply SuccessFully Sent');
			}
		}
		$allDone =& JFactory::getApplication();
		$allDone->redirect('index.php?option=com_transaction&view=transaction&layout=listmessage&id='.$value['transaction_id']);
	}
	public function uploadVideo($youtube,$transaction_id){
		//echo $youtube."---".$transaction_id; exit;
		$app   = JFactory::getApplication();
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		$query1 = $db->getQuery(true);
		$query1->select('*');
		$query1->from($db->quoteName('#__iproperty_agents'));
		$query1->where($db->quoteName('email')." = ".$db->quote($user->email));
		$db->setQuery($query1);
		$res = $db->loadObject();
		//echo "<pre>"; print_r($res); exit;
		if(empty($res->id)){
			$agent_id = $user->id;
		} else {
			$agent_id = $res->id;
		}

		$video = $db->getQuery(true);
    	$columns = array('agent_id','transaction_id','upload_video','upload_date');
		$values = array($db->quote($agent_id),$db->quote($transaction_id),$db->quote($youtube));
		$video
	    ->insert($db->quoteName('#__transaction_video'))
	    ->columns($db->quoteName($columns))
	    ->values(implode(',', $values).',NOW()');
	    $db->setQuery($video);
	    //echo $video; exit;
	    if ( $db->execute() !== true ) {
	    	JError::raiseError( 4711, 'A severe error occurred' );
		} else {
			JFactory::getApplication()->enqueueMessage('Successfully Uploaded');
		}
	}
	public function getVideo($id){
    	$user = JFactory::getUser();
    	$db = JFactory::getDbo();
		$query1 = $db->getQuery(true);
		$query1->select('*');
		$query1->from($db->quoteName('#__iproperty_agents'));
		$query1->where($db->quoteName('email')." = ".$db->quote($user->email));
		$db->setQuery($query1);
		$res = $db->loadObject();
		if(empty($res->id)){
			$agent_id = $user->id;
		} else {
			$agent_id = $res->id;
		}
		
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__transaction_video'));
		$query->where($db->quoteName('agent_id')." = ".$db->quote($agent_id));
		$query->where($db->quoteName('transaction_id')." = ".$db->quote($id));
		$db->setQuery($query);
		$result = $db->loadObjectlist();
		//echo "<pre>"; print_r($result); exit;
		return $result;
	}
	public function deleteVideo($id){
		//echo "<pre>"; print_r($_REQUEST); exit;
		$db = JFactory::getDbo();
 
		$query = $db->getQuery(true);
		$conditions = array(
		    $db->quoteName('id') . ' = '.$id, 
		);
		$query->delete($db->quoteName('#__transaction_video'));
		$query->where($conditions);
		$db->setQuery($query);
		//echo $query; exit;
		$result = $db->execute();
		if($result){
			return true;
		} else {
			return false;
		}
	}
}
?>