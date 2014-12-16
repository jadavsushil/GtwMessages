<?php

/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
class SentMessage extends AppModel
{

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
        App::import('Model', 'GtwFiles.SentMessage');
        $this->SentMessage = new SentMessage();
        if(empty($message['SentMessage']['is_read']) || $message['SentMessage']['read_on_date'] == '0000-00-00 00:00:00'){
            $this->updateAll(array('SentMessage.is_read'=>'"1"','SentMessage.read_on_date'=>'"'.date("Y-m-d H:i:s").'"'),array('SentMessage.id'=>$message['SentMessage'] ['id']));
        }        
    }
}