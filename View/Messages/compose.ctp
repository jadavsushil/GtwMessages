<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */

$this->Helpers->load('GtwRequire.GtwRequire');
$this->GtwRequire->req('ui/wysiwyg');
echo $this->GtwRequire->req($this->Html->url('/',true).'GtwMessages/js/messages.js');
$subject = $body = '';
$submitButton = __('Send');
if($messageType=='reply'){
    $submitButton = __('Reply');
    $subject = 'Re:'.$message['Message']['title'];
    $body = "On ".$message['Message']['created'].", ".$message['Sender']['first'].' '.$message['Sender']['last']." <".$message['Sender']['email']."> wrote:<br />< <blockquote>".$message['Message']['body']."</blockquote>";
}elseif($messageType=='forward'){
    $submitButton = __('forward');
    $subject = 'Fwd:'.$message['Message']['title'];
    $body = "On ".$message['Message']['created'].", ".$message['Sender']['first'].' '.$message['Sender']['last']." <".$message['Sender']['email']."> wrote:<br />< <blockquote>".$message['Message']['body']."</blockquote>";
}

?>
<link rel="stylesheet" type="text/css" media="screen" href="http://silviomoreto.github.io/bootstrap-select/stylesheets/bootstrap-select.css">
<h1><?php echo __('Messages'); ?></h1>

        <div class="row">        
            <div class="col-md-2 col-xs-3">
                <?php echo $this->element('GtwMessages.leftpanel',array('type'=>$messageType));?>
            </div>
            <div class="col-md-10  col-xs-9">
                <?php
                    echo $this->Form->create('Message', array(
                            'inputDefaults' => array(
                                'div' => 'form-group',
                                'wrapInput' => false,
                                'class' => 'form-control',
                            ),
                            'id' => 'MessageComposeForm',
                            'class' => 'messageForm'
                        ));
                    echo $this->Form->input('recipient_id', array(
                            'label' => false,
                            'options' => $users,
                            'empty' => 'Select Recipient',
                            'before'=>'<div class="input-group"><span class="input-group-addon">TO:</span>',
                            'after' =>'</div>',
                            'selected' => isset($message['Message']['user_id'])?$message['Message']['user_id']:'',
                            'class'=>'selectpicker',
                            'data-live-search'=>'true'
                        ));
                    echo $this->Form->input('title', array(
                            'label' => false,
                            'placeholder' => 'Subject',
                            'value' => $subject,
                            'before'=>'<div class="input-group"><span class="input-group-addon">Subject:</span>',
                            'after' =>'</div>'
                        ));
                    echo $this->Form->input('body', array(
                            'label' => false,
                            'placeholder' => 'Message body',
                            'value' =>$body,
                            'rows' => '15',
                            'cols' => '140',
                            'class' =>'wysiwyg',
                        ));
                ?>
                <div class="modal-footer clearfix">
                    <div class='pull-left'>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-send"></i> <?php echo $submitButton;?></button>
                        <?php echo $this->Html->actionIconBtn('fa fa-times',' Cancel', 'index',null,'btn-danger'); ?>
                    </div>
                </div>
                <?php echo $this->Form->end();?>
            </div>
        </div>
