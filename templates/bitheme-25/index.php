<?php
defined('_JEXEC') or die;
// detecting site title
$app = JFactory::getApplication();
?>


<!DOCTYPE html>
<html lang="en">
	<head>
	    <meta charset="utf-8">
		<jdoc:include type="head" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/template.css" type="text/css" />
        <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/jquery.js"></script>
		<script type="text/javascript">
        jQuery.noConflict();
        </script> 
		<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/template.js"></script>
		<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/cufon.js"></script>
		<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/js/Europe_700.font.js"></script>
		<script type="text/javascript">
			Cufon.replace('#grey h1, .moduletable-grey h3, .caption h2,  .caption h4, .caption p, .caption span, .on-blue > li a, .item-page h2, .bt-title-nointro, .moduletable-comments h3, #comments h4, .moduletable-moreidea h3, .moduletable-news h3');
		</script>
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/assets/css/bootstrap.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/assets/css/bootstrap-responsive.css" type="text/css" media="screen" />
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
</head>

	<body>

<div id="wrapper" class="container-fluid">
<div class="row-fluid inner header">
<div class="span2 logo">
<div id="logo">
<a href="/"><img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/logo.png" width="225" height="120" alt="Твой город" /></a>
</div>
<?php if($this->countModules('top')) : ?>
<div class="navbar">
<jdoc:include type="modules" name="top" style="xhtml" />
</div>
<?php endif; ?>
</div>
<?php if($this->countModules('topmenu')) : ?>
<div class="navbar span6">
<jdoc:include type="modules" name="topmenu" style="xhtml" />
</div>
<?php endif; ?>
<?php if($this->countModules('login')) : ?>
<div class="span4">
<jdoc:include type="modules" name="login" style="xhtml" />
</div>
<?php endif; ?>
</div>
<?php if($this->countModules('user1')) : ?>
<div class="row-fluid grey-stripe hidden-tablet hidden-phone">
<div class="inner">
<div class="span12">
<jdoc:include type="modules" name="user1" style="xhtml" />
</div>
</div>
</div>
<?php endif; ?>
<div id="maincontent" class="row-fluid">
<div class="inner">
<?php if($this->countModules('user2')) : ?>
<div class="user2 span2 pull-right leftmenu">
<jdoc:include type="modules" name="user2" style="xhtml" />
</div>
<?php endif; ?>
<?php if($this->countModules('user3')) : ?>
<div class="user3 span6 pull-right slider hidden-phone">
<jdoc:include type="modules" name="user3" style="none" />
</div>
<?php endif; ?>
<?php if($this->countModules('user7')) : ?>
<div class="user7 span4 pull-right">
<jdoc:include type="modules" name="user7" style="xhtml" />
</div>
<?php endif; ?>
<jdoc:include type="component" />
<?php if($this->countModules('user5')) : ?>
<div class="row-fluid">
<div class="user5 span12 hidden-phone">
<jdoc:include type="modules" name="user5" style="none" />
</div>
</div>
<?php endif; ?>
<?php if($this->countModules('user6')) : ?>
<div class="row-fluid">
<div class="user6 span12">
<jdoc:include type="modules" name="user6" style="xhtml" />
</div>
</div>
<?php endif; ?>
</div>
</div>
<?php if($this->countModules('user8 or user9')) : ?>
<div class="row-fluid">
<div class="inner">
<div class="user8 span6">
<jdoc:include type="modules" name="user8" style="xhtml" />
</div>
<div class="user9 span6">
<jdoc:include type="modules" name="user9" style="xhtml" />
</div>
</div>
</div>
<?php endif; ?>
<?php if($this->countModules('user4')) : ?>
<div class="blue-stripe row-fluid">
<div class="inner">
<jdoc:include type="modules" name="user4" style="none" />
</div>
</div>
<?php endif; ?>

<div class="row-fluid inner footer">
<p>
<a href="/">Твой Город</a> (с) 2012<br />
Все права защищены
</p>
<jdoc:include type="modules" name="footer" style="none" />
</div>
</div>

    <script src="/<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/assets/js/bootstrap.min.js"></script>
	</body>
</html>
