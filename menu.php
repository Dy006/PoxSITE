<?php session_start();
require "function.php";

if ($_GET['l'] != NULL )
{
    $_SESSION['language'] = $_GET['l'];
}

if($_COOKIE['pox'] == NULL)
{
    setcookie('pox', 'yes', time() + 365*24*3600, null, null, false, true);
}?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="img/pox_icon.png"/>
    <title>Pox !</title>

    <!-- Les styles -->
    <link href="http://twitter.github.com/bootstrap/assets/css/bootstrap.css" rel="stylesheet">
    <link href="http://twitter.github.com/bootstrap/assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">

    <script src="http://jasny.github.com/bootstrap/assets/js/jquery.js"></script>
    <script src="http://jasny.github.com/bootstrap/assets/js/bootstrap-transition.js"></script>
    <script src="http://jasny.github.com/bootstrap/assets/js/bootstrap-alert.js"></script>
    <script src="http://jasny.github.com/bootstrap/assets/js/bootstrap-modal.js"></script>
    <script src="http://jasny.github.com/bootstrap/assets/js/bootstrap-dropdown.js"></script>
    <script src="http://jasny.github.com/bootstrap/assets/js/bootstrap-scrollspy.js"></script>
    <script src="http://jasny.github.com/bootstrap/assets/js/bootstrap-tab.js"></script>
    <script src="http://jasny.github.com/bootstrap/assets/js/bootstrap-tooltip.js"></script>
    <script src="http://jasny.github.com/bootstrap/assets/js/bootstrap-popover.js"></script>
    <script src="http://jasny.github.com/bootstrap/assets/js/bootstrap-button.js"></script>
    <script src="http://jasny.github.com/bootstrap/assets/js/bootstrap-collapse.js"></script>
    <script src="http://jasny.github.com/bootstrap/assets/js/bootstrap-carousel.js"></script>
    <script src="http://jasny.github.com/bootstrap/assets/js/bootstrap-typeahead.js"></script>
    <script src="http://jasny.github.com/bootstrap/assets/js/bootstrap-affix.js"></script>
    <script src="http://jasny.github.com/bootstrap/assets/js/bootstrap-inputmask.js"></script>
    <script src="http://jasny.github.com/bootstrap/assets/js/bootstrap-rowlink.js"></script>
    <script src="http://jasny.github.com/bootstrap/assets/js/bootstrap-fileupload.js"></script>
    <script src="js/ajax.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.validate.js"></script>
    <script src="js/jquery.uniform.js" ></script>
    <script src="js/form.js"></script>
    <script src="js/js.js"></script>

    <script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-39109371-1']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();

        $(function () {
            $('.Tooltip').tooltip()
            $('.Popover').popover()
            $('.dropdown-toggle').dropdown()
        })
    </script>
</head>
<body>

<!-- NAVBAR
================================================== -->
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a class="brand" href="index.php">Pox</a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li class="active" id="li_1">
                        <a href="index.php" onclick="tab_change_focus('li_1','1');"><?php echo getText1('menu.nav.1'); ?></a>
                    </li>
                    <li id="li_2"><a href="news.php"
                                     onclick="tab_change_focus('li_2','1');"><?php echo getText1('menu.nav.2'); ?></a>
                    </li>
                    <li id="li_3"><a href="programs.php?p=loquii"
                                     onclick="tab_change_focus('li_3','1');"><?php echo getText1('menu.nav.3'); ?></a>
                    </li>
                    <li id="li_4"><a href="tutorials.php"
                                     onclick="tab_change_focus('li_4','1');"><?php echo getText1('menu.nav.4'); ?></a>
                    </li>
                    <li id="li_5"><a href="wol.php"
                                     onclick="tab_change_focus('li_5','1');"><?php echo getText1('menu.nav.5'); ?></a>
                    </li>
                    <li id="li_6"><a href="forum.php"
                                     onclick="tab_change_focus('li_6','1');"><?php echo getText1('menu.nav.6'); ?></a>
                    </li>
                    <li id="li_7"><a href="contact.php"
                                     onclick="tab_change_focus('li_7','1');"><?php echo getText1('menu.nav.7'); ?></a>
                    </li>
                    <li id="li_8"><a href="faq.php"
                                     onclick="tab_change_focus('li_8','1');"><?php echo getText1('menu.nav.8'); ?></a>
                    </li>
                    <?php
                    if($_SESSION['pseudo'] == NULL)
                    {?>
                        <li id="li_9"><a href="register.php"
                                         onclick="tab_change_focus('li_9','1','1');"><?php echo getText1('menu.nav.9'); ?></a>
                        </li>
                    <?php
                    }?>
                </ul>
                <?php
                if($_SESSION['pseudo'] != NULL)
                {?>
                   <div class="pull-right">
                       <ul class="nav">
                           <li class="dropdown">
                               <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                   <i class="icon-user icon-white"></i><?php echo ' ' . $_SESSION['pseudo']; ?>
                                   <b class="caret"></b>
                               </a>
                               <ul class="dropdown-menu">
                                   <li id="li_10"><a href="account.php" onclick="tab_change_focus('li_10','1','2');"><i class="icon-home"></i> <?php echo getText1('menu.user.1'); ?></a></li>
                                   <li id="li_11"><a href="account.php?type=contact" onclick="tab_change_focus('li_11','1','2');"><i class="icon-th-list"></i> <?php echo getText1('menu.user.2'); get_number_inv_contact(); ?></a></li>
                                   <li id="li_12"><a href="account.php?type=mails" onclick="tab_change_focus('li_12','1','2');"><i class="icon-envelope"></i> <?php echo getText1('menu.user.3'); get_number_mails(); ?></a></li>
                                   <?php get_option_edit_website(); ?>
                                   <li class="divider"></li>
                                   <li id="li_15"><a href="account.php?type=tutorials" onclick="tab_change_focus('li_15','1','2');"><i class="icon-pencil"></i> <?php echo getText1("menu.user.6"); ?></a></li>
                                   <li id="li_16"><a href="account.php?type=programs" onclick="tab_change_focus('li_16','1','2');"><i class="icon-th-large"></i> <?php echo getText1("menu.user.7"); ?></a></li>
                                   <li class="divider"></li>
                                   <li><a href="bdd.php?a=deco"><i class="icon-off"></i> <?php echo getText1('menu.user.8'); ?></a></li>
                                   <li><a href="faq.php"><?php echo getText1('menu.user.9'); ?></a></li>
                               </ul>
                           </li>
                       </ul>
                   </div>
                <?php
                }
                else
                {?>
                    <form class="navbar-form pull-right" name="ident" method="POST" action="#" onsubmit="Co(this.pseudo.value, this.password.value);return false;">
                        <input class="span2" type="text"  name="pseudo" placeholder="<?php echo getText1('menu.signin.text.1'); ?>">
                        <input class="span2" type="password" name="password" placeholder="<?php echo getText1('menu.signin.text.2'); ?>">
                        <button type="submit" class="btn btn-primary"><?php echo getText1('menu.signin.button.1'); ?></button>
                    </form>
                <?php
                }?>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

<!-- Carousel
================================================== -->
<div id="myCarousel" class="carousel slide">
    <div class="carousel-inner">
        <div class="item active">
            <img src="img/backgroundcarousel.jpg" alt="">
            <div class="container">
                <div class="jumbotron">
                    <h1><?php echo getText1('menu.carousel.title.1'); ?></h1>
                    <p class="lead"><?php echo getText1('menu.carousel.text.1'); ?></p>
                    <a class="btn btn-large btn-success" href="register.php"><?php echo getText1('menu.carousel.button.1'); ?></a>
                </div>
            </div>
        </div>
        <div class="item">
            <img src="img/backgroundcarousel2.jpg" alt="">
            <div class="container">
                <div class="carousel-caption">
                    <h1><?php echo getText1('menu.carousel.title.2'); ?></h1>
                    <p class="lead"><?php echo getText1('menu.carousel.text.2'); ?></p>
                </div>
            </div>
        </div>
        <div class="item">
            <img src="img/backgroundcarousel.jpg" alt="">
            <div class="container">
                <div class="carousel-caption">
                    <h1><?php echo getText1('menu.carousel.title.3'); ?></h1>
                    <p class="lead"><?php echo getText1('menu.carousel.text.3'); ?></p>
                </div>
            </div>
        </div>
        <div class="item">
            <img src="img/backgroundcarousel2.jpg" alt="">
            <div class="container">
                <div class="carousel-caption">
                    <h1><?php echo getText1('menu.carousel.title.4'); ?></h1>
                    <p class="lead"><?php echo getText1('menu.carousel.text.4'); ?></p>
                </div>
            </div>
        </div>
    </div>
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">‹</a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">›</a>

    <?php
    if($_COOKIE['pox'] == NULL)
    {?>
        <center>
            <div class="hero-unit alert-info">
                <h2>
                    <?php echo getText1('menu.welcome.text.1'); ?><span class="muted"> <?php echo getText1('menu.welcome.text.2'); ?></span>
                    <a href="register.php" class="btn btn-primary btn-large">
                        <?php echo getText1('menu.welcome.button.1'); ?>
                    </a>
                    <a href="register.php" class="btn btn-primary btn-large">
                        <?php echo getText1('menu.welcome.button.2'); ?>
                    </a>
                </h2>
            </div>
        </center>

    <?php
    }?>
</div><!-- /.carousel -->

<div class="container marketing">

    <?php
    if($_GET['error'] != NULL)
    {
        echo '<div class="alert alert-error"><center><h1>' . getText1('alert.error.title.1') . ', ' . getText1('alert.error.text.1') . '</h1></center></div>';

    }?>

    <?php include "information.php"; ?>

    <div id="alert"></div>