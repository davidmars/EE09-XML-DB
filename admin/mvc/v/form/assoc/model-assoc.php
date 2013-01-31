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
    <label class="control-label"><?=$vv->title?></label>
    <div class="controls">

        <?//one model ?>
        <div class="row">
        <div class="span3">
        <?php if($vv->value):?>
            <?=$this->render("model-preview/medium",$value);?>
        <?php endif?>
        </div>
        </div>

        <span class="help-block"><?=$vv->description?></span>
    </div>
</div>