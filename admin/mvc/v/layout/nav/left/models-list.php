<?php
/**
 * models-list is a View template. It works with a VV_models object.
 * display a list of models
 *
 */

/* @var $this View */
/* @var $vv VM_record_list */
$vv = $_vars;
$i=0;
?>
<li class="nav-header">
    Your records ( <?=count($vv->list )?>)
</li>
    <li class="divider"></li>
<?php foreach($vv->list as $record):?>
    <?php if($i++<200000):?>
<li class="<?=$record->cssActive()?>"><a href="<?=$record->hrefEdit?>">
    <?php //img src="<?=$record->model->getThumbnail()->getUrl()"
         //style="height: 20px;" ?> <?=$record->model->getType()." - ".$record->model->getId()?></a>
</li>
    <?php endif?>
<?php endforeach?>