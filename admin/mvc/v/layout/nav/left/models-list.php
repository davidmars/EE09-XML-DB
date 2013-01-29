<?php
/**
 * models-list is a View template. It works with a VV_models object.
 * display a list of models
 *
 */

/* @var $this View */
/* @var $vv VM_record_list */
$vv = $_vars;
?>
<li class="nav-header">
    Your records ( <?=count($vv->list )?>)
</li>
    <li class="divider"></li>
<?php foreach($vv->list as $record):?>
<li class="<?=$record->cssActive()?>"><a href="<?=$record->hrefEdit?>">
    <img src="<?=$record->model->getThumbnail()->getUrl()?>" style="height: 20px;" > <?=$record->model->getType()." - ".$record->model->getId()?></a>
</li>
<?php endforeach?>