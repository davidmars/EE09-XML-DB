<?php
/**
 * list-records is a View template. It works with a VM_records_selection object.
 * represents a list of records according selection options
 *
 */

/* @var $this View */
/* @var $vv VM_records_selection */
$vv = $_vars;
?>
<!--list-records-->
<h1 class="page-header"><?=$vv->type?></h1>
<div class="row">
    <?php foreach($vv->getList(100) as $record):?>

    <div class="span3">
        <?=$this->render("model-preview/medium",$record)?>
    </div>
    <?php endforeach?>
</div>