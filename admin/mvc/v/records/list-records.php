<?php
/**
 * list-records is a View template. It works with a VM_records_selection object.
 * represents a list of records according selection options
 *
 */

/* @var $this View */
/* @var $vv VM_records_selection */
$vv = $_vars;
$inLayout=$vv->rangeStart=="0";
?>
<!--list-records-->
<?php if($inLayout):?>
<h1 class="page-header"><?=$vv->type?></h1>
<div class="zzzzrow">
<?php endif?>

    <?php //----------the list---------------?>
    <?php foreach($vv->records as $record):?>
        <div class="span3">
            <?=$this->render("model-preview/medium",$record)?>
        </div>
    <?php endforeach?>

    <?php if($vv->nextUrl()):?>
        <?php //----------next to load---------------?>
        <div class="" jaquy-autoload="<?=$vv->nextUrl()?>"></div>
    <?php endif?>

<?php if($inLayout):?>
</div>
<?php endif?>