<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */

 $this->Helpers->load('GtwRequire.GtwRequire');
echo $this->Require->req('messages/forward_validation'); 
$this->GtwRequire->req('ui/wysiwyg');
 ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="row">
			<div class="col-md-8"><h3 style='margin-top:0px'>Messages</h3></div>
            <div class="col-md-4 text-right">				
			</div>			
		</div>
	</div>
	<div class="panel-body gtw-message">
		<div class="row">		
			<div class="col-md-2 col-xs-3">
				<?php echo $this->element('GtwMessages.leftpanel',array('type'=>'forward'));?>
			</div>
			<div class="col-md-10  col-xs-9">
				<?php
					echo $this->Form->create('Message', array(
							'inputDefaults' => array(
								'div' => 'form-group',
								'wrapInput' => false,
								'class' => 'form-control',					
							),
							'id' => 'MessageComposeForm'
						));
					echo $this->Form->input('recipient_id', array(
							'label' => false,
							'placeholder' => 'Enter Recipients',
							'options' => $users,
							'selected' => $message['Message']['user_id'],
							'empty' => 'Select Recipient',
							'before'=>'<div class="input-group"><span class="input-group-addon">Reply TO:</span>',
							'after' =>'</div>',
							'readonly'=>true
						));
					echo $this->Form->input('title', array(
							'label' => false,
							'placeholder' => 'Subject',
							'value' => 'Fwd:'.$message['Message']['title'],
							'before'=>'<div class="input-group"><span class="input-group-addon">Subject:</span>',
							'after' =>'</div>'
						));
					$txt = "On ".$message['Message']['created'].", ".$message['Message']['send_by']." <".$message['Message']['email']."> wrote:<br />";
					echo $this->Form->input('body', array(
							'label' => 'Body',
							'placeholder' => '',
							'value' =>$txt . '< '.$message['Message']['body'],
							'rows' => '20',
							'cols' => '140',
							'class' =>'wysiwyg',
						));
				?>
				<div class="modal-footer clearfix">
					<div class='pull-left'>
						<button type="submit" class="btn btn-primary"><i class="fa fa-reply"></i> Reply</button>
						<?php echo $this->Html->actionIconBtn('fa fa-times','Cancel', 'index',null,'btn-danger'); ?>
					</div>
				</div>
				<?php echo $this->Form->end();?>
			</div>
		</div>
	</div>
</div>