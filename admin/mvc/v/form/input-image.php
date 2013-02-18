<?php
/**
 * input-text is a View template. It works with a VM_form_element object.
 *
 *
 */

/* @var $this View */
/* @var $vv VM_form_element */
$vv = $_vars;
/** @var GinetteFileImage $value  */
$value=$vv->value;
$stringValue="";
if(get_class($value)!="GinetteFileImage"){
    $value=false;
}else{
   $stringValue=$value->relativePath;
}
?>
<div class="control-group" ginette-field-file>
    <label class="control-label" for="inputSuccess"><?=$vv->title?></label>
    <div class="controls">
        <div class="input-append">
            <input  class="span3"
                    type="text"
                    id="<?=$vv->varName?>"
                    name="<?=$vv->varName?>"
                    value="<?=$stringValue?>"
                    placeholder="select an image"
                    >
            <button file-manager-action="import-image" class="btn" type="button"><i class="icon-circle-arrow-up"></i> </button>
        </div>
        <?php if($value):?>
            <img class="help-block thumbnail" src="<?php echo $value->sizedShowAll(300,300)?> ">
        <?php else:?>
            <img class="help-block thumbnail" width="300px" src="<?=C_admin::$baseUrl."/pub/img/default-empty-image.png"?>">
        <?php endif?>
        <span class="help-block"><?=$vv->description?></span>
    </div>
</div>