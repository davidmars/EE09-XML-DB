<?php
/**
 * input-text is a View template. It works with a VM_form_element object.
 *
 *
 */

/* @var $this View */
/* @var $vv VM_form_element */
$vv = $_vars;
/** @var DateTime $value  */
$value=$vv->value;
?>
<div class="control-group">
    <label class="control-label" for=""><?=$vv->title?></label>
    <div class="controls">

        <div class="input-append">
            <input
                class="span3"
                <?=$vv->attrDisabled()?>
                id=""
                name="<?=$vv->varName?>"
                type="text"
                value="<?=$value->format("Y/m/d h:i:s")?>">
            <button class="btn" type="button"><i class="icon-calendar"></i> </button>
        </div>

        <span class="help-block"><?=$vv->description?></span>

    </div>
</div>
