<?php
/**
 * editModel is a View template. It works with a ModelXml object.
 * The form to edit a model
 *
 */

/* @var $this View */
/* @var $vv VM_editModel */
$vv = $_vars;
$this->inside("mvc/v/layout/admin",$vv->getLayout());
?>
<!--editModel-->

    <div class="row">
        <div class="span2">
            <div class="thumbnail">
                <img src="<?=$vv->model->getThumbnail()->getUrl()?>" alt="">
            </div>
        </div>
        <div class="span6">
            <div class="page-header">
            <h1><?=$vv->model->getId()?> <small><?=$vv->model->getType()?></small></h1>
            <p>
                <?=$vv->definition->description?>
            </p>
            </div>
        </div>
    </div>

        <hr>



<div class="row">
    <?php foreach($vv->formElements as $el):?>
        <div class="<?=$el->cssSpan?>">
            <?=$this->render($el->template,$el)?>
        </div>
    <?php endforeach?>
</div>
