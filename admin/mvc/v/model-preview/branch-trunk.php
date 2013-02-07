<?php
/* @var $this View */
/* @var $vv VM_branch */
/** @noinspection PhpUndefinedVariableInspection */
$vv = $_vars;
?>
<li  class="js-branch <?=$vv->cssActive()?> <?=$vv->cssEmpty()?>"




     >
    <input type="checkbox"
           id="<?=$vv->branchId?>"
           <?=$vv->attrOpen()?>
            />
    <label for="<?=$vv->branchId?>">
             <?=$vv->branch->tree->getId()?>
            (<?=$vv->numberOfChildren?>)
            <?=$vv->branchId?>
    </label>
    <ul>
        <?php foreach($vv->branches() as $branch):?>
            <?=$this->render("model-preview/branch",$branch)?>
        <?php endforeach?>
    </ul>
</li>