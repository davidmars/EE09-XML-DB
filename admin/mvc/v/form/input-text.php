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
        <input class="span3"type="text" <?=$vv->attrDisabled()?> id="<?=$vv->varName?>" value="<?=$vv->value?>">
        <span class="help-block"><?=$vv->description?></span>
    </div>
</div>