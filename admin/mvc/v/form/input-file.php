<?php
/**
 * input-text is a View template. It works with a VM_form_element object.
 *
 *
 */

/* @var $this View */
/* @var $vv VM_form_element */
$vv = $_vars;
/** @var File $value  */
$value=$vv->value;
?>
<div class="control-group">
    <label class="control-label" for="inputSuccess"><?=$vv->title?></label>
    <button records-manager-action="import-record" class="btn" type="button"><i class="icon-circle-arrow-up"></i> </button>
    <div class="controls">

        <div class="input-append">
            <input  class="span3" type="text" id="<?=$vv->varName?>" value="<?=$vv->value?>">
            <button class="btn" type="button"><i class="icon-circle-arrow-up"></i> </button>
        </div>
        <span class="help-block">
            File size :<?=$value->getFileSize()?>
            <br>
            <?=$vv->description?>
        </span>
    </div>
</div>