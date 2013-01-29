<?php
/**
 * input-text is a View template. It works with a VM_form_element object.
 *
 *
 */

/* @var $this View */
/* @var $vv VM_form_element */
$vv = $_vars;
/** @var $value ModelXml */
$value=$vv->value;
?>
<div class="control-group">
    <label class="control-label" for=""><?=$vv->title?></label>
    <div class="controls">

        <?//one model ?>
        <?php if($vv->value):?>
            <div class="thumbnail">
                <img src="<?=$value->getThumbnail()->getUrl()?>" alt="">
                <h3><?=$value->getId()?></h3>
                <p><?=$value->getType()?></p>
            </div>
        <?php endif?>

        <span class="help-block"><?=$vv->description?></span>
    </div>
</div>