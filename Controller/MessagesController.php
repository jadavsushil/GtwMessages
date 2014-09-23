<?php

/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
  App::uses('CakeTime', 'Utility');
 
 class MessagesController extends AppController {
    
    public $components = array('Paginator', 'RequestHandler');
    public $helpers = array('Time', 'Text');
	public $uses = array('GtwUsers.User','GtwMessages.Message','GtwMessages.SentMessage','GtwMessages.TrashMessage');
    
    public function beforeFilter() {
		if (CakePlugin::loaded('GtwUsers')){
            $this->layout = 'GtwUsers.users';
        }
		$this->set('newMessageCount',$this->Message->find('count',array('conditions'=>array(
																			'Message.recipient_id'=>$this->Session->read('Auth.User.id'),																			
																			'Message.is_read'=>0,
														))));
    }
    public function index($type= 'inbox') {
		$this->set('title_for_layout','Messages');        
		//Validate Type
		if(!in_array($type,array('inbox','trash','sent'))){
			$type = 'inbox';
		}       	   
	   $user_id = $this->Session->read('Auth.User.id');
	   $this->set('type', $type);
	   $arrConditions = array();
		if($type == 'sent'){
			$arrConditions['user_id'] = $user_id;
			
			
			$this->paginate = array(
				'conditions' => $arrConditions,
				'order' => array(
					'created' => 'desc'
				)
			);		
		   $messages = $this->paginate('SentMessage');
		   $this->set('messages',$messages);
		   $this->render('sent');
		   
		}else if($type == 'trash'){
			$arrConditions['OR'] = array(
			 	array('AND' => array('recipient_id' => $user_id,'deleted_by'=>'receiver')),
			 	array('AND' => array('user_id' => $user_id,'deleted_by'=>'sender')),
			);
			
			$this->paginate = array(
				'conditions' => $arrConditions,
				'order' => array(
					'created' => 'desc'
				)
			);		
		   $messages = $this->paginate('TrashMessage');
		   $this->set('messages',$messages);
		   $this->render('trash');
		   
		}else{ // inbox
			$arrConditions['recipient_id'] = $user_id;
			
			$this->paginate = array(
				'conditions' => $arrConditions,
				'order' => array(
					'created' => 'desc'
				)
			);		
		   $messages = $this->paginate('Message');
		   $this->set('messages',$messages);
		}
	   
    }
    public function compose() {
        $this->set('title_for_layout','Compose Message');
		if (!empty($this->request->data)) {
			$this->request->data['Message']['user_id'] = $this->Session->read('Auth.User.id');
			$data['SentMessage'] = $this->request->data['Message'];
			
			if ($this->Message->save($this->request->data)) {
                $this->Session->setFlash(__('Your Message has been sent successfully.'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
				
				$this->SentMessage->save($data);
				
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Unable to send your message.'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
        }
		$this->set('messageType','compose');
		$this->_setUser();
    }
    
    public function delete($messageId,$disp = null) {
			$arrResponse = array(
								'status'=>'fail',
								'message'=>__('Unable to delete message, Pleae try again'),
							);
			if($disp =='trash'){
				// Message deleted
				if ($this->TrashMessage->delete($messageId)) {
						$arrResponse = array(
							'status'=>'success',
							'message'=>__('Message has been deleted permanently'),
						);
				 }
			}else if($disp == 'sent'){
				$message = $this->SentMessage->findById($messageId);
				if (!$message) {
					$this->Session->setFlash('Message not found.', 'alert', array(
							'plugin' => 'BoostCake',
							'class' => 'alert-danger'
						));
					$this->redirect(array('action'=>'index'));	
				}else{
						$this->TrashMessage->create();
						
						$data['TrashMessage'] = $message['SentMessage'];
						$data['TrashMessage']['message_id'] = $message['SentMessage']['id'];
						$data['TrashMessage']['deleted_by'] = 'sender';
						
						$this->TrashMessage->save($data);
						if ($this->SentMessage->delete($messageId)) {
							$arrResponse = array(
								'status'=>'success',
								'message'=>__('Message has been deleted'),
							);
						 }
						
				}
			}else{	//inbox
				$message = $this->Message->findById($messageId);
				if (!$message) {
					$this->Session->setFlash('Message not found.', 'alert', array(
							'plugin' => 'BoostCake',
							'class' => 'alert-danger'
						));
					$this->redirect(array('action'=>'index'));	
				}else{
						$this->TrashMessage->create();
						
						$data['TrashMessage'] = $message['Message'];
						$data['TrashMessage']['message_id'] = $message['Message']['id'];
						$data['TrashMessage']['deleted_by'] = 'receiver';
						$this->TrashMessage->save($data);
						
						if ($this->Message->delete($messageId)) {
							$arrResponse = array(
								'status'=>'success',
								'message'=>__('Message has been deleted'),
							);
						 }
						
				}
			}
	
			if ($this->request->is('ajax')) {
				echo json_encode($arrResponse);
				exit;
			}else{
				$this->Session->setFlash($arrResponse['message'], 'alert', array(
						'plugin' => 'BoostCake',
						'class' => $arrResponse['status']=='fail'?'alert-danger':'alert-success'
					));
			   $this->redirect(array('action' => 'index/'.$disp));
			}
		
    }
    public function view($id = null,$disp= null) {
        if($disp == 'sent') { $message = $this->SentMessage->findById($id); }
		else if($disp == 'trash') { $message = $this->TrashMessage->findById($id); }
		else { $message = $this->Message->findById($id); 
		
			 if (!$message) {
            $this->Session->setFlash('Message not found.', 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
				$this->redirect(array('action'=>'index'));	
			}else{
				$this->set_read_flag($id);
			}
		}
		
	   
        $this->set(compact('message'));
		$this->set('disp',$disp);
		if($disp == 'sent') { $this->render('sent_view'); }
		else if($disp == 'trash') { $this->render('trash_view'); }
		else { $this->render('view'); }
		
    }
	
	 public function set_read_flag($id = null){
	 	$message = $this->Message->findById($id);
        if (!$message) {
            $this->Session->setFlash('Message not found.', 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
			$this->redirect(array('action'=>'index'));	
        }else{
			// set read flag if message is view by recipient
			if($this->Session->read('Auth.User.id') == $message['Message']['recipient_id']){
				$data['Message'] = array('is_read'=>1,'id' =>$id);
				if($message['Message']['read_on_date'] == '0000-00-00 00:00:00'){
					$data['Message'] ['read_on_date'] = date("Y-m-d H:i:s");
				}
				$this->Message->save($data);
			}	
		}
	 }
	
     public function reply($id = null) {
        $titleForLayout = 'Reply: Message';
        $this->set(compact('titleForLayout'));
				
		if (!empty($this->request->data)) {
			$this->request->data['Message']['user_id'] = $this->Session->read('Auth.User.id');
			$this->request->data['Message']['response_to_id'] = $id;
			$data['SentMessage'] = $this->request->data['Message'];
            if ($this->Message->save($this->request->data)) {
				$this ->set_read_flag($id);
                $this->Session->setFlash(__('Your Message has been sent successfully.'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
				
				$this->SentMessage->save($data);
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Unable to send your message.'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
        }  
		$message = $this->Message->findById($id);
        if (!$message) {
            $this->Session->setFlash('Message not found.', 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
			$this->redirect(array('action'=>'index'));	
        }
		$this->set('message',$message);
		$this->_setUser();
		$this->set('messageType','reply');
		$this->render('compose');
    }
	public function forward($id = null) {			
		if (!empty($this->request->data)) {
			$this->request->data['Message']['user_id'] = $this->Session->read('Auth.User.id');
			$this->request->data['Message']['response_to_id'] = $id;
			$data['SentMessage'] = $this->request->data['Message'];
            if ($this->Message->save($this->request->data)) {
				$this ->set_read_flag($id);
                $this->Session->setFlash(__('Your Message has been sent successfully.'), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
				$this->SentMessage->save($data);
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Unable to send your message.'), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
        }  
		
		$message = $this->Message->findById($id);
        if (!$message) {
            $this->Session->setFlash('Message not found.', 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
			$this->redirect(array('action'=>'index'));	
        }
		$this->set('message',$message);
		$this->_setUser();
		$this->set('messageType','forward');
		$this->render('compose');
    }
	
	public function multiple_action($ids = null,$more_action =null,$disp = null){
		$messageIdArr = explode(",",$ids);
		if($more_action == 1){ // Delete
			if($disp =='trash'){
				// Message deleted
				$this->TrashMessage->deleteAll(array('message_id'=>$messageIdArr));
			}else if($disp == 'sent'){
				
				for($i=0; $i<count($messageIdArr);$i++)
				{			
					$messageId = (int)$messageIdArr[$i];
					if($messageId != 0)
					{
						$message = $this->SentMessage->findById($messageId);
						$this->TrashMessage->create();						
						$data['TrashMessage'] = $message['SentMessage'];
						$data['TrashMessage']['message_id'] = $messageId;
						$data['TrashMessage']['deleted_by'] = 'sender';
						$this->TrashMessage->save($data);
						$this->SentMessage->delete($messageId);
					}
				}
						 
			}else{
				for($i=0; $i<count($messageIdArr);$i++)
				{			
					$messageId = (int)$messageIdArr[$i];
					
					if($messageId != 0)
					{
						$message = $this->Message->findById($messageId);
						$this->TrashMessage->create();						
						$data['TrashMessage'] = $message['Message'];
						$data['TrashMessage']['message_id'] = $messageId;
						$data['TrashMessage']['deleted_by'] = 'receiver';
						$this->TrashMessage->save($data);
						$this->Message->delete($messageId);
					}
				}
			}		
		}else if($more_action == 2){ // Mark as read
			$this->Message->updateAll(
				array('is_read' => '1'), 
				array('Message.id' => $messageIdArr)
			);
		}else if($more_action == 3){ // Mark as unread
			$this->Message->updateAll(
				array('is_read' => '0'), 
				array('Message.id' => $messageIdArr)
			);
		}
		$this->redirect($this->referer());
	}
	public function get_user($user_id = null,$field_arr) {
		$users = $this->User->find('all', array('fields'=>$field_arr,'conditions'=>array('validated'=>1,'User.id'=>$user_id)));		
		return $users[0]['User'];
	}
    private function _setUser(){
		$users = $this->User->find('list', array('fields'=>array('id','email'),'conditions'=>array('validated'=>1,'User.id!='.$this->Session->read('Auth.User.id'))));
		$this->set(compact('users'));
	}
}