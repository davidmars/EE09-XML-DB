<?php
/**
 * admin is a View template. It works with a mixed object.
 * layout for the admin pages
 *
 */

/* @var $this View */
/* @var $vv VM_layout */
$vv = $_vars;
$this->inside("layout/html5bp",$vv);

?>
<!-- This code is taken from http://twitter.github.com/bootstrap/examples/hero.html -->

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

                    <li class="active"><a href="#">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>

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

        <div class="container">
            <div class="row">


                <?php //---------------------------the left nav ?>
                <div class="span4">
                    <?php
                    /*
                    <ul class="nav nav-list">
                        <?php //echo $this->render("layout/nav/left/models-list",$vv->getModelList())?>

                    </ul>
                    */?>
                    <?php echo $this->render("layout/nav/left/tree",$vv->getTree())?>
                </div>
                <?php //---------------------------the page content ?>
                <div class="span8">
                    <?=$this->insideContent?>
                </div>
            </div>




            <footer>
                <p>&copy; Cocorico cowboy!</p>
            </footer>

        </div> <!-- /container -->