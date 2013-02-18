<?php
/**
 * main-nav is a View template. It works with a VM_layout object.
 * The main navigation in the admin
 *
 */

/* @var $this View */
/* @var $vv VM_layout */
$vv = $_vars;
?>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>


            <a class="brand" href="#">Ginette Admin</a>
            <div class="nav-collapse collapse">
                <ul class="nav">


                    <?php//------database selector----------?>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Database : <?=VM_admin::$db->name()?> <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">browse</a></li>
                            <li><a href="#">databases</a></li>
                            <li><a href="#">to build</a></li>
                            <li class="divider"></li>
                            <li class="nav-header">this</li>
                            <li><a href="#">list</a></li>
                        </ul>
                    </li>

                    <?php//------records----------?>
                    <li class="active"><a href="<?=C_editModel::urlHome()?>">Records</a></li>

                    <?php//------files----------?>
                    <li><a href="<?=C_files::urlHome()?>">files</a></li>


                </ul>
                <form class="navbar-form pull-right">

                    <input class="span2" type="text" placeholder="Email">
                    <input class="span2" type="password" placeholder="Password">
                    <button type="submit" class="btn">Sign in</button>
                </form>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>