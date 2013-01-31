<?php
/**
 * input-text is a View template. It works with a VM_form_element object.
 *
 *
 */

/* @var $this View */
/* @var $vv VM_form_element */
$vv = $_vars;
?>
<div class="control-group">
    <label class="control-label" for=""><?=$vv->title?></label>
    <div class="controls">
        <textarea <?=$vv->attrDisabled()?>
                rows="5"
                class="span7"
                name="<?=$vv->varName?>"
                id="<?=$vv->varName?>"
                ><?=htmlentities($vv->value)?></textarea>
        <span class="help-block"><?=$vv->description?></span>
    </div>
</div>