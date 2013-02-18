<?php
/* @var $this View */
/* @var $vv VM_tree */
$vv = $_vars;
?>
<div class="js-jaquy-tree css-treeview" >
    <?php if(VM_tree::$openedBranch):?>
        <h4><?=VM_tree::$openedBranch->localId()?></h4>
    <?php endif?>
    <ul class="b">
        <?=$this->render("model-preview/branch",$vv->trunk)?>
        <?php /*
        <li class="js-branch">
            <input type="checkbox"
                   id="treeRoot"
                   checked="checked" />
            <label for="treeRoot"><?=$vv->tree->getId()?> (<?=$vv->tree->trunk->countAllChildren()?>) </label>
            <ul>
                <?php foreach($vv->branches() as $branch):?>

                <?php endforeach?>
            </ul>
        </li>
        */?>
    </ul>
    <div class="bra"></div>
</div>
