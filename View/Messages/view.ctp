<?php
/**
 * Gintonic Web
 * @author    Philippe Lafrance
 * @link      http://gintonicweb.com
 */
?>
<h1><?php echo __('Messages'); ?></h1>
        <div class="row">        
            <div class="col-md-2 col-xs-3">
                <?php echo $this->element('GtwMessages.leftpanel',array('type'=>'view'));?>
            </div>
            <div class="col-md-10  col-xs-9">
                <div class="row bg-success" style='padding: 10px 0;margin-bottom: 20px;'>
                    <div class="col-md-7">
                        <table>
                            <tr>
                                <td class="text-right" style="padding-right:10px;">From :</td>
                                <td><?php echo $message['Sender']['first'].' '.$message['Sender']['last'].'&nbsp;('.$this->Time->timeAgoInWords($message[$model]['created']).')';?></td>
                            </tr>
                            <tr>
                                <td class="text-right" style="padding-right:10px;">Subject :</td>
                                <td><?php echo $message[$model]['title']; ?></td>
                            </tr>
                            <tr>
                                <td class="text-right" style="padding-right:10px;">To :</td>
                                <td><?php echo $message['Receiver']['first'].' '.$message['Receiver']['last'];?></td>
                            </tr>
                        </table>                        
                    </div>
                    <div class="col-md-5 text-right">
                        
                        <?php 
                            echo $this->Html->link('<i class="fa fa-inbox"> </i> Back to ' . $type,array('controller'=>'messages','action'=>'index',$type),array('class'=>'btn btn-default','escape'=>false,'title'=>'Back to '. $type,)). "&nbsp;";
                            if($type !='trash'){
                                echo $this->Html->link('<i class="fa fa-mail-reply"> </i> Reply',array('controller'=>'messages','action'=>'reply',$message[$model]['id'],$type),array('class'=>'btn btn-default','escape'=>false,'title'=>'Reply this message')). "&nbsp;";
                                echo $this->Html->link('<i class="fa fa-mail-forward"> </i> Forward',array('controller'=>'messages','action'=>'forward',$message[$model]['id'],$type),array('class'=>'btn btn-default','escape'=>false,'title'=>'Forward this message'))."&nbsp;";
                            }
                            echo $this->Html->link('<i class="fa fa-trash-o"> </i> Delete',array('controller'=>'messages','action'=>'delete',$message[$model]['id'],$type),array('class'=>'btn btn-default','escape'=>false,'title'=>'Delete this message'),'Are you sure? You want to delete this message.');                             
                        ?>
                        <div class="pad" title="<?php echo $message[$model]['created'];?>">
                            <?php echo $this->Time->niceShort($message[$model]['created']);?>
                        </div>
                    </div>
                </div>        
                <div class="row">
                    <div class="col-md-12">
                        <?php echo nl2br($message[$model]['body']); ?>
                    </div>
                </div>
                <p><?php if($message[$model]['is_read']) { echo 'Read on: ' . $this->Time->timeAgoInWords($message[$model]['read_on_date']); } ?></p>                
            </div>
        </div>
