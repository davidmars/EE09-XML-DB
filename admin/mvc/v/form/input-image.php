<?php
/**
 * input-text is a View template. It works with a VM_form_element object.
 *
 *
 */

/* @var $this View */
/* @var $vv VM_form_element */
$vv = $_vars;
/** @var FileImage $value  */
$value=$vv->value;
?>
<div class="control-group">
    <label class="control-label" for="inputSuccess"><?=$vv->title?></label>
    <div class="controls">
        <div class="input-append">
            <input  class="span3"
                    type="text"
                    id="<?=$vv->varName?>"
                    name="<?=$vv->varName?>"
                    value="<?=$vv->value?>"
                    >
            <button class="btn" type="button"><i class="icon-circle-arrow-up"></i> </button>
        </div>

        <img class="help-block" src="<?=$value->getUrl()?>">

        <span class="help-block"><?=$vv->description?></span>
    </div>
</div>