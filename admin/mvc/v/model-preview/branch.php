<?php
/* @var $this View */
/* @var $vv VM_branch */
/** @noinspection PhpUndefinedVariableInspection */
$vv = $_vars;
?>
<?php if($vv->branch->isTrunk):?>
    <?=$this->render("model-preview/branch-trunk",$vv)?>
<?php else:?>
    <li  class="js-branch <?=$vv->cssActive()?> <?=$vv->cssEmpty()?>" >
        <input type="checkbox"
               id="<?=$vv->branchId?>"
               <?=$vv->attrOpen()?> >
        <label
                draggable="true"
                ondragstart="onDragStart(event,this)"
                ondragend="onDragEnd(event,this)"

                ondrop="onDrop(event,this)"
                ondragover="onDragOver(event,this)"
                ondragleave="onDragLeave(event,this)"

                for="<?=$vv->branchId?>">

                    <a href="<?=C_editModel::urlEdit($vv->record->getId(),$vv->branch)?>">
                        <?=$vv->branch->model->getType()." ".$vv->branch->model->getId()?>
                    </a>
                    (<?=$vv->numberOfChildren?>)
                    <?=$vv->branchId?>
        </label>
        <ul>
            <?php foreach($vv->branches() as $branch):?>
                <?=$this->render("model-preview/branch",$branch)?>
            <?php endforeach?>
        </ul>
    </li>
<?php endif?>