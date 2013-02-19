<?php
/**
 * On the left display the records families browser, on the right, display the list
 *
 */

/* @var $this View */
/* @var $vv VM_records_manager */
$vv = $_vars;
?>
<div class=" full" records-manager >
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Records manager</h3>
    </div>
    <div class="modal-body">

        <?php //---------------------------the left nav ?>
        <div class="left">
            <div class="tree-nav" jacky-activable-item-list>
                <ul>
                <?php foreach($vv->recordsFamilies as $f):?>

                        <div class="line" jacky-activable-item>
                            <a load-in-jaquy-ajax-receiver="records-list"
                               href="<?=C_records::urlListRecords($f->type)?>">
                                <?=$f->type?> (<?=$f->count()?>)
                            </a>
                        </div>

                <?php endforeach?>
                </ul>
            </div>
        </div>

        <?php //---------------------------the selection content ?>
        <div class="right" jaquy-ajax-receiver="records-list">
            <?=$this->insideContent?>
        </div>



    </div>

</div>

