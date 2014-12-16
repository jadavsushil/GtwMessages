<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
class Message extends AppModel
{
    public $uses = array('GtwMessages.SentMessage');
    var $belongsTo = array(
        'Sender' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'fields' => array('id', 'first', 'last', 'email')
        ),
        'Receiver' => array(
            'className' => 'User',
            'foreignKey' => 'recipient_id',
            'fields' => array('id', 'first', 'last', 'email')
        )
    );
    public $validate = array(
        'recipient_id' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please select recipient'
            )
        ),
        'title' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'Please enter subject'
            )
        )
    );
    function setRead($message){
        if(empty($message['Message']['is_read']) || $message['Message']['read_on_date'] == '0000-00-00 00:00:00'){
            $this->updateAll(array('Message.is_read'=>'"1"','Message.read_on_date'=>'"'.date("Y-m-d H:i:s").'"'),array('Message.id'=>$message['Message'] ['id']));
        }        
    }
    
    public function getMessages($type)
    {
        //Validate Type
        if(!in_array($type,array('inbox','trash','sent'))){
            $type = 'inbox';
        }
        $model = "Message";
        $user_id = CakeSession::read('Auth.User.id');
       
       $arrConditions = array();
        if($type == 'sent'){ //Sent Item
            $model = "SentMessage";
            $arrConditions['user_id'] = $user_id;
        }else if($type == 'trash'){ // Trash Item
            $model = "TrashMessage";
            $arrConditions['OR'] = array(
                 array('AND' => array('recipient_id' => $user_id,'deleted_by'=>'inbox')),
                 array('AND' => array('user_id' => $user_id,'deleted_by'=>'sent')),
            );            
        }else{ //Inbox
            $arrConditions['recipient_id'] = $user_id;            
        }
        $response['conditions'] = array(
                'conditions' => $arrConditions,
                'order' => array(
                    'created' => 'desc'
                )
            );
        $response['model'] = $model;
        return $response;
    }
    
    public function process($data,$model,$id = null,$type = null)
    {
        if (!empty($data)) {
            $this->SentMessage = ClassRegistry::init('GtwMessages.SentMessage');
            $response['redirect'] = Router::url(array('controller'=>'messages','action'=>'compose'));
            $response['message'] = __('Unable to send your message.');
            $response['class'] = 'alert-danger';
                
            $data['user_id'] = CakeSession::read('Auth.User.id');
            $sentData['SentMessage'] = $data;
            if($model != 'compose'){
                $data['response_to_id'] = $id;
                //$response['redirect'] = Router::url(array('controller'=>'messages','action'=>'index',$type));
            }
            if ($this->save($data)) {
                $this->SentMessage->save($sentData);
                $response = $this->__setSuccess($type,__('Your Message has been sent successfully.'));
            }
            return $response;
        }
    }
    
    public function performAction($type, $ids, $action)
    {
        $response['redirect'] = Router::url(array('controller'=>'messages','action'=>'index',$type));
        $response['message'] = __('Unable to complete action. Please try agian !!!');
        $response['class'] = 'alert-danger';
        if(in_array($type,array('inbox','sent','trash'))){
            $this->SentMessage = ClassRegistry::init('GtwMessages.SentMessage');
            $this->TrashMessage = ClassRegistry::init('GtwMessages.TrashMessage');
            $model = 'Message';
            if($type=='trash'){
                $model = 'TrashMessage';
            }elseif($type=='sent'){
                $model = 'SentMessage';
            }
            $messageIdArr = explode(",",$ids);
            if($action == 'delete'){ // Delete
                if($type =='trash'){
                    // Message deleted
                    if($this->TrashMessage->deleteAll(array('TrashMessage.id'=>$messageIdArr))){
                        $response = $this->__setSuccess($type);
                    }
                    
                }else{
                    for($i=0; $i<count($messageIdArr);$i++){
                        $messageId = $messageIdArr[$i];
                        if(!empty($messageId) && is_numeric($messageId)){
                            if($model == 'Message'){
                                $message = $this->findById($messageId);
                            } else{
                                $message = $this->{$model}->findById($messageId);
                            }
                            $this->TrashMessage->create();
                            $data['TrashMessage'] = $message[$model];
                            $data['TrashMessage']['message_id'] = $message[$model]['id'];
                            $data['TrashMessage']['deleted_by'] = $type;
                            if(isset($data['TrashMessage']['id'])){
                                unset($data['TrashMessage']['id']);
                            }
                            $this->TrashMessage->save($data);
                            if($model == 'Message'){
                                $this->delete($messageId);
                            } else{
                                $this->{$model}->delete($messageId);
                            }
                        }
                    }
                    $response = $this->__setSuccess($type);
                }
            }else if($action == 'read'){ // Mark as read
                if($model == 'Message'){
                    if($this->updateAll(array('is_read' => '1'), array('Message.id' => $messageIdArr))){
                        $response = $this->__setSuccess($type);
                    }
                } else{
                    if($this->{$model}->updateAll(array('is_read' => '1'), array('Message.id' => $messageIdArr))){
                        $response = $this->__setSuccess($type);
                    }
                }
            }else if($action == 'unread'){ // Mark as unread
                if($model == 'Message'){
                    if($this->updateAll(array('is_read' => '0'), array('Message.id' => $messageIdArr))){
                        $response = $this->__setSuccess($type);
                    }
                } else{
                    if($this->{$model}->updateAll(array('is_read' => '0'), array('Message.id' => $messageIdArr))){
                        $response = $this->__setSuccess($type);
                    }
                }
            }
        }
        return $response;
    }
    public function deleteMessage($type=null, $messageId = null)
    {
        $response = array(
                            'class'=>'alert-danger',
                            'message'=>__('Unable to delete message, Please try again'),
                            'redirect' => Router::url(array('controller'=>'messages','action'=>'index',$type))
                        );
        if(in_array($type,array('inbox','sent','trash'))){
            $this->TrashMessage = ClassRegistry::init('GtwMessages.TrashMessage');
            $this->SentMessage = ClassRegistry::init('GtwMessages.SentMessage');
            if($type =='trash'){
                if ($this->TrashMessage->delete($messageId)) {
                    $response = $this->__setSuccess($type,__('Message has been deleted successfully'));
                }
            }else{
                $model = ($type == 'sent')?'SentMessage':'Message';
                $message = ($type == 'sent')?$this->{$model}->findById($messageId):$this->findById($messageId);
                if (!empty($message)) {
                    $this->TrashMessage->create();    
                    $data['TrashMessage'] = $message[$model];
                    $data['TrashMessage']['message_id'] = $message[$model]['id'];
                    $data['TrashMessage']['deleted_by'] = $type;
                    if(isset($data['TrashMessage']['id'])){
                        unset($data['TrashMessage']['id']);
                    }
                    $this->TrashMessage->save($data);
                    if (($type == 'sent')?$this->{$model}->delete($messageId):$this->delete($messageId)) {
                        $response = $this->__setSuccess($type,__('Message has been deleted successfully'));
                    }
                }
            }
        }
        return $response;
    }
    private function __setSuccess($type = null,$msg = null)
    {
        $response['redirect'] = empty($type)?Router::url(array('controller'=>'messages','action'=>'index')):Router::url(array('controller'=>'messages','action'=>'index',$type));
        $response['message'] = !empty($msg)?$msg:__('Action has been completed successfully');
        $response['class'] = 'alert-success';
        
        return $response;
    }
}