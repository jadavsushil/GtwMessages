<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
 
 ?>
  <?php 
$this->Helpers->load('GtwRequire.GtwRequire');
echo $this->Require->req('messages/validation'); 
$this->GtwRequire->req('ui/wysiwyg');
?>
<div class="row">
    <div class="col-md-12">
        <h1>Compose</h1>
        <hr/>
		
    </div>
</div>
<div class="row">
    <div class="col-md-9">
        <?php
            echo $this->Form->create('Message', array(
                'inputDefaults' => array(
                    'div' => 'form-group',
                    'wrapInput' => false,
                    'class' => 'form-control',
                ),
            ));
			
			echo $this->Form->input('recipient_id', array(
                'label' => 'Recipient',
                'placeholder' => 'Enter Recipients',
				'options' => $users
            ));
            echo $this->Form->input('title', array(
                'label' => 'Title',
                'placeholder' => '',
				
            ));
            echo $this->Form->input('body', array(
                'label' => 'Body',
                'rows' => '20',
				'cols' => '140',
                'placeholder' => 'Message body',
				 'class' =>'wysiwyg',
            ));
        ?>
    </div>
    
</div>
<div class="row">
    <div class="col-md-12">
        <?php echo $this->Form->submit('Send', array(
            'div' => false,
            'class' => 'btn btn-primary'
        ));?>
        <?php echo $this->Html->actionBtn('Cancel', 'index'); ?>
    </div>
</div>
<?php
    echo $this->Form->end();
?>
