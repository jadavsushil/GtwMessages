<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
 //echo $this->Require->req('messages/select');
echo $this->GtwRequire->req($this->Html->url('/',true).'GtwMessages/js/messages.js');
//echo $this->Require->req('messages/checkallnone');
//echo $this->Require->req('messages/moreaction');
?>
<h1><?php echo __('Messages'); ?></h1>
<div class="row">        
    <div class="col-md-2 col-xs-3">
        <?php echo $this->element('GtwMessages.leftpanel'); ?>
    </div>
    <div class="col-md-10  col-xs-9">		
        <?php echo $this->Form->create('morefuntion', array('id' => 'morefunid', 'class' => 'form-horizontal')); ?>
        <?php echo $this->Form->input('type', array('type' => 'hidden', 'id' => 'gtwMessagetype', 'value' => $type)); ?>
        <?php if (!empty($messages)) { ?>
            <div class="row pad">
                <div class="col-sm-6">
					<div class='pagination'>
						<label style="margin-right: 10px;">
							<input type="checkbox" id="check-all"/>
						</label>
						<!-- Action button -->
						<div class="btn-group">
							<button type="button" class="btn btn-default btn-sm btn-flat dropdown-toggle" data-toggle="dropdown">
								More Actions <span class="caret"></span>
							</button>
							<ul class="dropdown-menu" role="menu" data-url="<?php echo $this->Html->url(array('controller'=>'messages','action'=>'multiple_action'));?>">
								<?php if ($type == 'inbox') { ?>
									<li><a href="#" data-value='read' class="preventDefault">Mark as read</a></li>
									<li><a href="#" data-value='unread' class="preventDefault">Mark as unread</a></li>
									<li class="divider"></li>
								<?php } ?>
								<li><a href="#" data-value='delete' class="preventDefault">Delete</a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-sm-6 text-right">					
					<ul class="pagination">
						<li>
							<a><?php 
							echo $this->Paginator->counter(array(
									'format' => __('<strong>{:start} - {:end}</strong> of <strong>{:count}</strong>')
								));
						?></a>
							</li>
						<?php
							echo $this->Paginator->prev('<< ' . __('Previous'), array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li', 'disabledTag' => 'a'));
							echo $this->Paginator->next(__('Next') . ' >>', array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li', 'disabledTag' => 'a'));
						?>
					</ul>
                </div>                    
            </div>
        <?php } ?>
        <div class="table-responsive">
            <?php if (empty($messages)) { ?>
                <div class='text-warning'><?php echo __('No message.') ?></div>
                <?php
            } else {
                ?>
                <table class="table table-mailbox">
                    <?php foreach ($messages as $message) { ?>
                        <tr class="<?php echo (!$message[$model]['is_read'] && $type == 'inbox') ? 'unread' : '' ?>">
                            <td class="small-col">
                                <input type="checkbox" name="data[morefuntion][chk]" id="<?php echo $message[$model]['id'] ?>" value="<?php echo $message[$model]['id'] ?>" class="chkcls">
                            </td>
                            <td class="small-action">
                                <?php
                                if ($type == "trash" || $type == "sent") {
                                    echo $this->Html->link('<i class="fa fa-trash-o"> </i>', array('controller' => 'messages', 'action' => 'delete', $message[$model]['id'], $type), array('escape' => false, 'title' => 'Delete this message'), 'Are you sure? You want to delete this message permanently.');
                                } else {
                                    echo $this->Html->link('<i class="fa fa-mail-reply"> </i>', array('controller' => 'messages', 'action' => 'reply', $message[$model]['id'], $type), array('escape' => false, 'title' => 'Reply this message', 'class' => 'navigation'));
                                    echo '&nbsp|&nbsp';
                                    echo $this->Html->link('<i class="fa fa-trash-o"> </i>', array('controller' => 'messages', 'action' => 'delete', $message[$model]['id'], $type), array('escape' => false, 'title' => 'Delete this message'), 'Are you sure? You want to delete this message.');
                                }
                                ?>
                            </td>
                            <td class="inbox-data-from hidden-xs hidden-sm">
                                <div>
                                    <?php
                                    if ($type == 'sent') {
                                        echo $message['Receiver']['first'] . ' ' . $message['Receiver']['last'];
                                    } else {
                                        echo $message['Sender']['first'] . ' ' . $message['Sender']['last'];
                                    }
                                    ?>
                                </div>
                            </td>
                            <td class="inbox-data-message">
                                <div>
                                    <span>
                                        <?php echo $this->Html->link($message[$model]['title'], array('controller' => 'messages', 'action' => 'view', $message[$model]['id'], $type), array('title' => 'Click here to View Message', 'class' => 'navigation')); ?>
                                    </span>
                                </div>
                            </td>
                            <td class="col-md-2 col-lg-2 col-sm-3 hidden-xs">
                                <div>
                                    <?php echo $this->Time->timeAgoInWords($message[$model]['created']); ?>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>                                                            
                </table>
            <?php } ?>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>
