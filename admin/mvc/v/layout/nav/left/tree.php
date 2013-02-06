<?php
/* @var $this View */
/* @var $vv VM_tree */
$vv = $_vars;
?>
<div class="css-treeview">
    <?php if(VM_tree::$openedBranch):?>
        <h4><?=VM_tree::$openedBranch->localId()?></h4>
    <?php endif?>
    <ul>
        <li class="js-branch">
            <input type="checkbox"
                   id="treeRoot"
                   checked="checked" /><label
                            for="treeRoot"><?=$vv->tree->getId()?></label>
            <ul>
                <?php foreach($vv->branches() as $branch):?>
                    <?=$this->render("model-preview/branch",$branch)?>
                <?php endforeach?>
            </ul>
        </li>
    </ul>
</div>