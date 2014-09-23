<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */

class TrashMessage extends AppModel {

	var $belongsTo =array(
		'Sender' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'fields' => array('id','first','last','email')
		),
		'Receiver' => array(
			'className' => 'User',
			'foreignKey' => 'recipient_id',
			'fields' => array('id','first','last','email')
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
}