<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
  App::uses('CakeTime', 'Utility');
 
 class MessagesController extends AppController
 {    
    public $components = array('Paginator', 'RequestHandler');
    public $helpers = array('Time', 'Text');
    public $uses = array('GtwUsers.User','GtwMessages.Message','GtwMessages.SentMessage','GtwMessages.TrashMessage');
    public function beforeFilter()
    {
        parent::beforeFilter();                                        
    }
    public function beforeRender()
    {
        parent::beforeRender();        
    }
    public function index($type= 'inbox')
    {
        $this->set('title_for_layout','Messages');
        $response = $this->Message->getMessages($type);
        $model = $response['model'];
        $this->paginate = $response['conditions'];
        $messages = $this->paginate($model);
        $this->set(compact('type','model','messages'));
    }
    public function view($id = null,$type= 'inbox')
    {
        $model = 'Message';
        $message = array();
        if($type == 'sent') { 
            $model = 'SentMessage';
            $message = $this->SentMessage->findById($id); 
        }else if($type == 'trash') { 
            $model = 'TrashMessage';
            $message = $this->TrashMessage->findById($id); 
        }
        else { 
            $message = $this->Message->findById($id);              
        }
        if (empty($message)) {
            $this->Session->setFlash('Message not found.');
            $this->redirect(array('action'=>'index'));
        }else{
            $this->{$model}->setRead($message);
        }
        $this->set(compact('message','model','type'));
    }
    public function compose()
    {
        $this->set('title_for_layout','Compose Message');
        if (!empty($this->request->data['Message'])) {
            $response = $this->Message->process($this->request->data['Message'],'compose',null,null);
            $this->Session->setFlash($response['message'], 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => $response['class']
                    ));
            echo json_encode($response);
            exit;
        }
        $this->set('messageType','compose');
        $this->_setUser();
    }
    public function reply($id = null,$type)
    {
        $this->set('title_for_layout',__('Reply: Message'));
        if (!empty($this->request->data['Message'])) {
            $response = $this->Message->process($this->request->data['Message'],'reply',$id,$type);
            $this->Session->setFlash($response['message'], 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => $response['class']
                    ));
            echo json_encode($response);
            exit;
        }  
        $model = 'Message';
        if(strtolower($type)=='sent'){
            $model = 'SentMessage';
        }elseif(strtolower($type)=='trash'){
            $model = 'TrashMessage';
        }
        $message = $this->{$model}->findById($id);
        if (empty($message)){
            $this->redirect(array('action'=>'index',$type));            
        }
        $message['Message'] = $message[$model];
        
        $this->set('message',$message);
        $this->_setUser();
        $this->set('messageType','reply');
        $this->render('compose');
    }
    public function forward($id = null,$type)
    {
        if (!empty($this->request->data['Message'])) {
            $response = $this->Message->process($this->request->data['Message'],'forward',$id,$type);
            $this->Session->setFlash($response['message'], 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => $response['class']
                    ));
            echo json_encode($response);
            exit;
        }
        $model = 'Message';
        if(strtolower($type)=='sent'){
            $model = 'SentMessage';
        }elseif(strtolower($type)=='trash'){
            $model = 'TrashMessage';
        }
        $message = $this->{$model}->findById($id);
        if (empty($message)){
            $this->redirect(array('action'=>'index',$type));            
        }
        $message['Message'] = $message[$model];
        $this->set('message',$message);
        $this->_setUser();
        $this->set('messageType','forward');
        $this->render('compose');
    }
    public function delete($messageId,$type = null) {
        $response = $this->Message->deleteMessage($type,$messageId);
        $this->Session->setFlash($response['message'], 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => $response['class']
                    ));
        $this->redirect($this->referer());
    }
    public function multiple_action($ids = null,$action =null,$type = null){
        $response = $this->Message->performAction($type, $ids, $action);
        $this->Session->setFlash($response['message'], 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => $response['class']
                    ));
        if($this->request->is('ajax')){
            echo json_encode($response);
            exit;
        }else{
            $this->redirect($this->referer());
        }
        exit;
    }
    public function get_user($user_id = null,$field_arr) {
        $users = $this->User->find('all', array('fields'=>$field_arr,'conditions'=>array('validated'=>1,'User.id'=>$user_id)));        
        return $users[0]['User'];
    }
    private function _setUser(){
        $users = $this->User->find('list', array('fields'=>array('id','email'),'conditions'=>array('User.id!='.$this->Session->read('Auth.User.id')),'recursive'=>-1));
        $this->set(compact('users'));
    }
    public function get_inbox_count(){
         echo $this->Message->find('count',array('conditions'=>array(
                                    'Message.recipient_id'=>$this->Session->read('Auth.User.id'),
                                    
                                    'Message.is_read'=>0,
                                )));
         exit;
    }
    private function _getMessageFromId($id,$type){
        
    }
}