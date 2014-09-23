<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
echo $this->Require->req('messages/checkallnone'); 
echo $this->Require->req('messages/moreaction'); 
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
				<?php echo $this->element('GtwMessages.leftpanel');?>
			</div>
			<div class="col-md-10  col-xs-9">
				<?php echo $this->Form->create('morefuntion',array('id'=>'morefunid','class'=>'form-horizontal'));?>
					<?php echo $this->Form->input('type',array('type'=>'hidden','id' =>'gtwMessagetype','value'=>$type));?>
					<?php if(!empty($messages)){?>
						 <div class="row pad">
							<div class="col-sm-6">
								<label style="margin-right: 10px;">
									<input type="checkbox" id="check-all"/>
								</label>
								<!-- Action button -->
								<div class="btn-group">
									<button type="button" class="btn btn-default btn-sm btn-flat dropdown-toggle" data-toggle="dropdown">
										More Actions <span class="caret"></span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<?php if($type=='inbox'){?>
											<li><a href="#" data-value='2'>Mark as read</a></li>
											<li><a href="#" data-value='3'>Mark as unread</a></li>
											<li class="divider"></li>
										<?php }?>
										<li><a href="#" data-value='1'>Delete</a></li>
									</ul>
								</div>
							</div>					
						</div>
					<?php }?>
					<div class="table-responsive">
						<?php if(empty($messages)){?>
							<div class='text-warning'><?php echo __('No message.')?></div>
						<?php 
							}else{
						?>
								<table class="table table-mailbox">
									<?php foreach ($messages as $message){?>
										<tr class="<?php echo (!$message['Message']['is_read'] && $type=='inbox')?'unread':''?>">
											<td class="small-col">
												<input type="checkbox" name="data[morefuntion][chk]" id="<?php echo $message['Message']['id']?>" value="<?php echo $message['Message']['id']?>" class="chkcls">
											</td>
											<td class="small-action">
												<?php 
													if($type == "trash" || $type == "sent"){
														echo $this->Html->link('<i class="fa fa-trash-o"> </i>',array('controller'=>'messages','action'=>'delete',$message['Message']['id'],$type),array('escape'=>false,'title'=>'Delete this message'),'Are you sure? You want to delete this message permanently.');
													}else{
														echo $this->Html->link('<i class="fa fa-mail-reply"> </i>',array('controller'=>'messages','action'=>'reply',$message['Message']['id']),array('escape'=>false,'title'=>'Reply this message'));																
														echo '&nbsp|&nbsp';
														echo $this->Html->link('<i class="fa fa-trash-o"> </i>',array('controller'=>'messages','action'=>'delete',$message['Message']['id'],$type),array('escape'=>false,'title'=>'Delete this message'),'Are you sure? You want to delete this message.');
													}
												?>
											</td>
											<td class="name">
												<?php 
													echo $message['Sender']['first'].' '.$message['Sender']['last'];

													
												?>
											</td>
											<td class="subject">
												<?php echo $this->Html->link($message['Message']['title'],array('controller'=>'messages','action'=>'view',$message['Message']['id'],$type),array('title'=>'Click here to View Message'));?>
											</td>
											<td class="time"><?php echo $this->Time->timeAgoInWords($message['Message']['created']); ?></td>
										</tr>
									<?php }?>															
								</table>
							<?php }?>
					</div>
				<?php echo $this->Form->end();?>
			</div>
		</div>
	</div>
</div>
