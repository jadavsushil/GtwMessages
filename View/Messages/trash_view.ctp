<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
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
				<?php echo $this->element('GtwMessages.leftpanel',array('type'=>'view'));?>
			</div>
			<div class="col-md-10  col-xs-9">
				<div class="row bg-success" style='padding: 10px 0;margin-bottom: 20px;'>
					<div class="col-md-8">
						<table>
							<tr>
								<td class="text-right" style="padding-right:10px;">From :</td>
								<td><?php echo $message['Sender']['first'].' '.$message['Sender']['last'].'&nbsp;('.$this->Time->timeAgoInWords($message['TrashMessage']['created']).')';?></td>
							</tr>
							<tr>
								<td class="text-right" style="padding-right:10px;">Subject :</td>
								<td><?php echo $message['TrashMessage']['title']; ?></td>
							</tr>
							<tr>
								<td class="text-right" style="padding-right:10px;">To :</td>
								<td><?php echo $message['Receiver']['first'].' '.$message['Receiver']['last'];?></td>
							</tr>
						</table>						
					</div>
					<div class="col-md-4 text-right">
						<?php 
							if($disp != '') {$bck = $disp; } else { $bck = 'Inbox';} 
							echo $this->Html->link('<i class="fa fa-inbox"> </i> Back to ' . $bck,array('controller'=>'messages','action'=>'index',$disp),array('class'=>'btn btn-default','escape'=>false,'title'=>'Back to '. $bck,)). "&nbsp;";
							echo $this->Html->link('<i class="fa fa-mail-reply"> </i> Reply',array('controller'=>'messages','action'=>'reply',$message['TrashMessage']['id']),array('class'=>'btn btn-default','escape'=>false,'title'=>'Reply this message')). "&nbsp;";
							echo $this->Html->link('<i class="fa fa-mail-forward"> </i> Forward',array('controller'=>'messages','action'=>'forward',$message['TrashMessage']['id']),array('class'=>'btn btn-default','escape'=>false,'title'=>'Forward this message'))."&nbsp;";
							echo $this->Html->link('<i class="fa fa-trash-o"> </i> Delete',array('controller'=>'messages','action'=>'delete',$message['TrashMessage']['id']),array('class'=>'btn btn-default','escape'=>false,'title'=>'Delete this message'),'Are you sure? You want to delete this message.'); 							
						?>
						<div class="pad" title="<?php echo $message['TrashMessage']['created'];?>">
							<?php echo $this->Time->niceShort($message['TrashMessage']['created']);?>
						</div>
					</div>
				</div>		
				<div class="row">
					<div class="col-md-12">
						<?php echo nl2br($message['TrashMessage']['body']); ?>
					</div>
				</div>
				<p><?php if($message['TrashMessage']['is_read']) { echo 'Read on: ' . $this->Time->timeAgoInWords($message['TrashMessage']['read_on_date']); } ?></p>				
			</div>
		</div>
	</div>
</div>