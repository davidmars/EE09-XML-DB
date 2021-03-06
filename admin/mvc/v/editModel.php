<?php
/**
 * editModel is a View template. It works with a GinetteRecord object.
 * The form to edit a model
 *
 */

/* @var $this View */
/* @var $vv VM_editModel */
$vv = $_vars;
$this->inside("layout/layout-edit-record",$vv->getLayout());
?>

<div ginette-record="<?=$vv->model->getId()?>">
    <div class="row">
        <div class="span2">
            <div class="thumbnail">
                <img src="<?=$vv->model->getThumbnail()->sizedShowAll(200,200)?>" alt="">
            </div>
        </div>
        <div class="span6">
            <div class="page-header">
            <h1><?=$vv->model->getId()?> <small><?=$vv->model->getType()?></small></h1>
            <p>
                <?=$vv->definition->description?><br>
                <span class="muted">Created : <?=$vv->model->created->format("Y/m/d h:i:s")?></span><br>
                <span class="muted">Updated : <?=$vv->model->updated->format("Y/m/d h:i:s")?></span>
            </p>
            </div>
        </div>
    </div>




    <?//----------form-------------?>
    <form method="post" action="<?=C_editModel::urlSave($vv->model->getId());?>">
    <div class="row">
        <?php foreach($vv->formElements as $el):?>
            <div class="<?=$el->cssSpan?>">
                <?=$this->render($el->template,$el)?>
            </div>
        <?php endforeach?>

        <div class="span8">
           <button ginette-record-save class="btn btn-success pull-right">Record</button>
        </div>

    </div>
    </form>
</div>
