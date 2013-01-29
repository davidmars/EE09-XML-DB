<?php
/**
 * default is a View template. It works with a VM_form_element object.
 * The default template for a field
 *
 */

/* @var $this View */
/* @var $vv VM_form_element */
$vv = $_vars;
?>
<!--default-->
<h3>
    <?=$vv->title?>
</h3>
<div>
    <?=$vv->field->type?>
</div>