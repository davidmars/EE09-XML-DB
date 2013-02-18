<?php
/**
 * files-dir is a View template. It works with a VM_file_dir object.
 * A tree to navigate through database files directories
 *
 */

/* @var $this View */
/* @var VM_file_dir $vv  */
$vv = $_vars;
?>
<ul>

    <?php //the title ?>

    <div class="line" jacky-activable-item>
        <a load-in-jaquy-ajax-receiver="foldercontent"
           href="<?=C_files::urlDirContent($vv->dir->relativePath)?>">
            <?=$vv->dir->fileName()?> (<?=count($vv->dir->childrenImages())?>)
        </a>
    </div>

    <?php // the recursive sub list ?>

    <?php foreach($vv->childrenDir() as $dir):?>
        <li>
            <?=$this->render($this->path,$dir)?>
        </li>
    <?php endforeach?>
</ul>
