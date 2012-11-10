<?php 
/*------------------------------------------------------------------------
# smartslider - Smart Slider
# ------------------------------------------------------------------------
# author    Roland Soos 
# copyright Copyright (C) 2011 Offlajn.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.offlajn.com
-------------------------------------------------------------------------*/
?>
<?php
defined('_JEXEC') or die('Restricted access');
?>

<?php
$sp = &$this->env->slider->params;
$count = count($this->env->slides);
?>

<?php
if(!$this->calc && isset($c['clearcss'])){
  include($c['clearcss']);
}
?>

<?php echo $c['id']; ?> a:hover{
  text-decoration: none;
}

<?php echo $c['id']; ?> dl, <?php echo $c['id']; ?> dd, <?php echo $c['id']; ?> dt{
  padding: 0;
  margin: 0;
}

<?php
  $cwidth = $sp->get('width');
  $cheight = $sp->get('height');
?>

<?php echo $c['id']; ?>{
  width: <?php echo $cwidth; ?>px;
  margin: 0 auto;
}

<?php echo $c['id']; ?> .shadow{
  width: <?php echo $cwidth; ?>px;
  background: url('<?php if(!$this->calc && $this->env->slider->params->get('shadow') != '-1' && $this->env->slider->params->get('shadow') != '' && is_file(dirname(__FILE__).'/../../../images/shadows/'.$this->env->slider->params->get('shadow'))) echo $this->themeCacheUrl.$c['helper']->ResizeImage(dirname(__FILE__).'/../../../images/shadows/'.$this->env->slider->params->get('shadow'), $cwidth, 0); ?>') no-repeat;
  height: <?php echo isset($GLOBALS['height'])?$GLOBALS['height']:0; ?>px;
}

<?php
  $paddingOut = array(6, 6, 6, 6);
  $cwidth = $cwidth-$paddingOut[1]-$paddingOut[3];
  $cheight = $cheight-$paddingOut[0]-$paddingOut[2];
?>
<?php echo $c['id']; ?> .outer{
  border-radius: 6px;
  /* Alpha channel*/
  <?php 
    $color = $this->env->slider->params->get('outerbordercolor', 'ffffff');
    if(strlen($color) == 6):
  ?>
  background-color: #<?php echo $color; ?>;
  <?php else: 
    $alpha = round(hexdec(substr($color, 6, 2))/255, 1);
    if(hexdec(substr($color, 6, 2)) != 1):
  ?>
    background-color: #<?php echo substr($color, 0, 6); ?>;
    background-color: rgba(<?php echo hexdec(substr($color, 0, 2)); ?>, <?php echo hexdec(substr($color, 2, 2)); ?>, <?php echo hexdec(substr($color, 4, 2)); ?>, <?php echo $alpha; ?>);
    <?php endif; ?>
  <?php endif; ?>
  /* Alpha channel*/
  width: <?php echo $cwidth; ?>px;
  height: <?php echo $cheight; ?>px;
  padding: <?php echo implode('px ', $paddingOut); ?>px;
}

.dj_ie7 <?php echo $c['id']; ?> .outer{
  background-color: #<?php echo substr($color, 0, 6); ?>;
}

<?php
  $paddingIn = array(2, 2, 2, 2);
  $cwidth = $cwidth-$paddingIn[1]-$paddingIn[3];
  $cheight = $cheight-$paddingIn[0]-$paddingIn[2];
?>
<?php echo $c['id']; ?> .slinner{
  width: <?php echo $cwidth; ?>px;
  height: <?php echo $cheight; ?>px;
  border-radius: 6px;
  /* Alpha channel*/
  <?php 
    $color = $this->env->slider->params->get('innerbordercolor', 'ffffff');
    if(strlen($color) == 6):
  ?>
  background-color: #<?php echo $color; ?>;
  <?php else: 
    $alpha = round(hexdec(substr($color, 6, 2))/255, 1);
    if(hexdec(substr($color, 6, 2)) != 1):
  ?>
    background-color: #<?php echo substr($color, 0, 6); ?>;
    background-color: rgba(<?php echo hexdec(substr($color, 0, 2)); ?>, <?php echo hexdec(substr($color, 2, 2)); ?>, <?php echo hexdec(substr($color, 4, 2)); ?>, <?php echo $alpha; ?>);
    <?php endif; ?>
  <?php endif; ?>
  /* Alpha channel*/
  padding: <?php echo implode('px ', $paddingIn); ?>px;
  overflow: hidden;
}

.dj_ie7 <?php echo $c['id']; ?> .slinner{
  background-color: #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> dl{
  width: <?php echo $cwidth+100; ?>px;
  height: <?php echo $cheight; ?>px;
}

<?php
  $marginDt = array(2, 0, 2, 2);
  $cheight = $cheight-$marginDt[0]-$marginDt[2];
  $titleWidth = 30;
?>

<?php echo $c['id']; ?> dt.sslide{
  position: relative;
  width: <?php echo $titleWidth; ?>px;
  height: <?php echo $cheight; ?>px !important;
  float:left;
  overflow: hidden;
  border-radius: 2px;
  /* Alpha channel*/
  <?php 
    $color = $this->env->slider->params->get('tabbg', 'ffffff');
    if(strlen($color) == 6):
  ?>
  background-color: #<?php echo $color; ?>;
  <?php else: 
    $alpha = round(hexdec(substr($color, 6, 2))/255, 1);
    if(hexdec(substr($color, 6, 2)) != 1):
  ?>
    background-color: #<?php echo substr($color, 0, 6); ?>;
    background-color: rgba(<?php echo hexdec(substr($color, 0, 2)); ?>, <?php echo hexdec(substr($color, 2, 2)); ?>, <?php echo hexdec(substr($color, 4, 2)); ?>, <?php echo $alpha; ?>);
    <?php endif; ?>
  <?php endif; ?>
  /* Alpha channel*/
  box-shadow:inset 1px 1px 1px rgba(254, 254, 254, 0.2);
  cursor: pointer;
  margin: <?php echo implode('px ', $marginDt); ?>px;
  z-index: 50;
}

.dj_ie7 <?php echo $c['id']; ?> dt.sslide{
  background-color: #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> dt.sslide .title{
  /* Alpha channel*/
  <?php 
    $color = $this->env->slider->params->get('tabbg', 'ffffff');
    if(strlen($color) == 6):
  ?>
  background-color: #<?php echo $color; ?>;
  <?php else: 
    $alpha = round(hexdec(substr($color, 6, 2))/255, 1);
    if(hexdec(substr($color, 6, 2)) != 1):
  ?>
    background-color: #<?php echo substr($color, 0, 6); ?>;
    background-color: rgba(<?php echo hexdec(substr($color, 0, 2)); ?>, <?php echo hexdec(substr($color, 2, 2)); ?>, <?php echo hexdec(substr($color, 4, 2)); ?>, <?php echo $alpha; ?>);
    <?php endif; ?>
  <?php endif; ?>
  /* Alpha channel*/
}

.dj_ie7 <?php echo $c['id']; ?> dt.sslide .title{
  background-color: #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> dt.sslide .rotated-90{
  display: block;
  -moz-transform-origin: center center;
  -moz-transform: rotate(-90deg);
  -webkit-transform: rotate(-90deg);
  -o-transform: rotate(-90deg);
  zoom: 1;
  height: <?php echo $cheight; ?>px;
  width: <?php echo $cheight; ?>px;
  padding: 3px 3px;
}

.dj_ie7 <?php echo $c['id']; ?> dt.sslide .rotated-90,
.dj_ie8 <?php echo $c['id']; ?> dt.sslide .rotated-90{
  filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
}

.dj_ie9 <?php echo $c['id']; ?> dt.sslide .rotated-90{
  -ms-transform: rotate(-90deg);
}

<?php echo $c['id']; ?> dt.sslide .title{
  height: <?php echo $titleWidth-6; ?>px;
  width: <?php echo $cheight-20; ?>px;
  line-height: <?php echo $titleWidth-6; ?>px;
  margin: 0 0 0 10px;
  padding: 0 1px; /* IE7-8 fix to prevent bad font antialias */
  display: block;
  text-align: right;
  float: left;
  
  /*font chooser*/
  <?php $f = $tabfont; ?>
  color: #<?php echo $f[6]?>;
  font-family: <?php echo ($f[2] ? '"'.$f[2].'"':'').($f[2] && $f[1] ? ',':'').$f[1];?>;
  font-weight: <?php echo $f[4]? 'bold' : 'normal';?>;
  font-style: <?php echo $f[5]? 'italic' : 'normal';?>;
  font-size: <?php echo $f[3]?>;
  <?php if($this->env->slider->params->get('textshadow') && $f[7]): ?>
  text-shadow: #<?php echo $f[11]?> <?php echo $f[8]?> <?php echo $f[9]?> <?php echo $f[10]?>;
  <?php else: ?>
  text-shadow: none;
  <?php endif; ?>
  /*font chooser*/
}


<?php echo $c['id']; ?> dt.sslide.selected{
  border-top-right-radius: 0px;
  border-bottom-right-radius: 0px;
  /* Alpha channel*/
  <?php 
    $color = $this->env->slider->params->get('selectedtabbg', 'ffffff');
    if(strlen($color) == 6):
  ?>
  background-color: #<?php echo $color; ?>;
  <?php else: 
    $alpha = round(hexdec(substr($color, 6, 2))/255, 1);
    if(hexdec(substr($color, 6, 2)) != 1):
  ?>
    background-color: #<?php echo substr($color, 0, 6); ?>;
    background-color: rgba(<?php echo hexdec(substr($color, 0, 2)); ?>, <?php echo hexdec(substr($color, 2, 2)); ?>, <?php echo hexdec(substr($color, 4, 2)); ?>, <?php echo $alpha; ?>);
    <?php endif; ?>
  <?php endif; ?>
  /* Alpha channel*/
  width: <?php echo $titleWidth-1; ?>px;
  border-right: 1px solid #5e5e5e;
  cursor: auto;
}

.dj_ie7 <?php echo $c['id']; ?> dt.sslide.selected{
  background-color: #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> dt.sslide:HOVER{
  /* Alpha channel*/
  <?php 
    $color = $this->env->slider->params->get('selectedtabbg', 'ffffff');
    if(strlen($color) == 6):
  ?>
  background-color: #<?php echo $color; ?>;
  <?php else: 
    $alpha = round(hexdec(substr($color, 6, 2))/255, 1);
    if(hexdec(substr($color, 6, 2)) != 1):
  ?>
    background-color: #<?php echo substr($color, 0, 6); ?>;
    background-color: rgba(<?php echo hexdec(substr($color, 0, 2)); ?>, <?php echo hexdec(substr($color, 2, 2)); ?>, <?php echo hexdec(substr($color, 4, 2)); ?>, <?php echo $alpha; ?>);
    <?php endif; ?>
  <?php endif; ?>
  /* Alpha channel*/
}

.dj_ie7 <?php echo $c['id']; ?> dt.sslide:HOVER{
  background-color: #<?php echo substr($color, 0, 6); ?>;
}


<?php echo $c['id']; ?> dt.sslide.selected .title,
<?php echo $c['id']; ?> dt.sslide:HOVER .title{
  /* Alpha channel*/
  <?php 
    $color = $this->env->slider->params->get('selectedtabbg', 'ffffff');
    if(strlen($color) == 6):
  ?>
  background-color: #<?php echo $color; ?>;
  <?php else: 
    $alpha = round(hexdec(substr($color, 6, 2))/255, 1);
    if(hexdec(substr($color, 6, 2)) != 1):
  ?>
    background-color: #<?php echo substr($color, 0, 6); ?>;
    background-color: rgba(<?php echo hexdec(substr($color, 0, 2)); ?>, <?php echo hexdec(substr($color, 2, 2)); ?>, <?php echo hexdec(substr($color, 4, 2)); ?>, <?php echo $alpha; ?>);
    <?php endif; ?>
  <?php endif; ?>
  /* Alpha channel*/
}

.dj_ie7 <?php echo $c['id']; ?> dt.sslide.selected .title,
.dj_ie7 <?php echo $c['id']; ?> dt.sslide:HOVER .title{
  background-color: #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> dt.sslide.selected .title, <?php echo $c['id']; ?> dt.sslide:HOVER .title{
  /*font chooser*/
  <?php $f = $selectedtabfont; ?>
  color: #<?php echo $f[6]?>;
  font-family: <?php echo ($f[2] ? '"'.$f[2].'"':'').($f[2] && $f[1] ? ',':'').$f[1];?>;
  font-weight: <?php echo $f[4]? 'bold' : 'normal';?>;
  font-style: <?php echo $f[5]? 'italic' : 'normal';?>;
  font-size: <?php echo $f[3]?>;
  <?php if($this->env->slider->params->get('textshadow') && $f[7]): ?>
  text-shadow: #<?php echo $f[11]?> <?php echo $f[8]?> <?php echo $f[9]?> <?php echo $f[10]?>;
  <?php else: ?>
  text-shadow: none;
  <?php endif; ?>
  /*font chooser*/
}

<?php echo $c['id']; ?> dt.sslide .dots{
  position: absolute;
  bottom: 30px;
  <?php if($this->env->slider->params->get('slidenumbering') && $this->env->slider->params->get('slideicons')): ?>
  bottom: 50px;
  <?php endif; ?>
  width: <?php echo $titleWidth; ?>px;
    <?php if (!$this->env->slider->params->get('showdots', 1)): ?> 
   display: none;    
  <?php endif; ?>
}

<?php echo $c['id']; ?> dt.sslide .dots .dot{
  width: 10px;
  height: 11px;
  background: url('<?php echo $c['url']; ?>images/emptydot.png') no-repeat;
  margin: 3px auto 0;
  cursor: pointer;
}

/*Circles with numbers*/

<?php echo $c['id']; ?> dt.sslide .dots .circle {
  width: 18px;
  height: 17px;
  background: url('<?php echo $c['url']; ?>images/numberingbg.png') no-repeat;
  margin: 3px auto 0;
  text-align: center;
  padding-top: 3px;
  cursor: pointer;
  color: #fff; 
  display: block;
  font-family: Arial;
  font-size: 11px;
  text-shadow: 0 1px 1px #000000;
  top: 0;
}

<?php echo $c['id']; ?> dt.sslide .dots .circle.active{
  /* Alpha channel*/
  <?php 
    $color = $this->env->slider->params->get('selectedtabbg', 'ffffff');
    if(strlen($color) == 6):
  ?>
  color: #<?php echo $color; ?>;
  <?php else: 
    $alpha = round(hexdec(substr($color, 6, 2))/255, 1);
    if(hexdec(substr($color, 6, 2)) != 1):
  ?>
    color: <?php echo substr($color, 0, 4); ?>;
    color: rgba(<?php echo hexdec(substr($color, 0, 2)); ?>, <?php echo hexdec(substr($color, 2, 2)); ?>, <?php echo hexdec(substr($color, 4, 2)); ?>, <?php echo $alpha; ?>);
    <?php endif; ?>
  <?php endif; ?>
  /* Alpha channel*/
  font-weight: bold;
}

.dj_ie7 <?php echo $c['id']; ?> dt.sslide .dots .circle.active{
  color: #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> dt.sslide .dots .dot.active {
  background-image: url('<?php if(!$this->calc) echo $this->themeCacheUrl.$c['helper']->ColorizeImage(dirname(__FILE__).'/images/filleddot.png', substr($this->env->slider->params->get('tabbg'),0,6), '188DD9'); ?>');
  cursor: auto;
}

<?php echo $c['id']; ?> dt.sslide.selected .dots .dot.active, <?php echo $c['id']; ?> dt.sslide:hover .dots .dot.active{
  background-image: url('<?php if(!$this->calc) echo $this->themeCacheUrl.$c['helper']->ColorizeImage(dirname(__FILE__).'/images/filleddotselected.png', substr($this->env->slider->params->get('selectedtabbg'),0,6), '188DD9'); ?>');
}

/*Arrows*/

<?php echo $c['id']; ?> dt.sslide .arrowup {
  visibility: hidden;
  cursor: pointer;
  width: 30px;
  height: 30px;
  background: url('<?php echo $c['url']; ?>images/up.png') no-repeat center center;
  box-shadow: 0.5px 0.5px 1px rgba(255, 255, 255, 0.4) inset;
  /* Alpha channel*/
  <?php 
    $color = $this->env->slider->params->get('innerbordercolor', 'ffffff');
    if(strlen($color) == 6):
  ?>
  border-top: 1px solid #<?php echo $color; ?>;
  <?php else: 
    $alpha = round(hexdec(substr($color, 6, 2))/255, 1);
    if(hexdec(substr($color, 6, 2)) != 1):
  ?>
    border-top: 1px solid <?php echo substr($color, 0, 4); ?>;
    border-top: 1px solid rgba(<?php echo hexdec(substr($color, 0, 2)); ?>, <?php echo hexdec(substr($color, 2, 2)); ?>, <?php echo hexdec(substr($color, 4, 2)); ?>, <?php echo $alpha; ?>);
    <?php endif; ?>
  <?php endif; ?>
  /* Alpha channel*/
}

.dj_ie7 <?php echo $c['id']; ?> dt.sslide .arrowup{
  border-top: #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> dt.sslide.selected .arrowup, <?php echo $c['id']; ?> dt.sslide:HOVER .arrowup{
  /* Alpha channel*/
  <?php 
    $color = $this->env->slider->params->get('outerbordercolor', 'ffffff');
    if(strlen($color) == 6):
  ?>
  border-top: 1px solid #<?php echo $color; ?>;
  <?php else: 
    $alpha = round(hexdec(substr($color, 6, 2))/255, 1);
    if(hexdec(substr($color, 6, 2)) != 1):
  ?>
    border-top: 1px solid <?php echo substr($color, 0, 4); ?>;
    border-top: 1px solid rgba(<?php echo hexdec(substr($color, 0, 2)); ?>, <?php echo hexdec(substr($color, 2, 2)); ?>, <?php echo hexdec(substr($color, 4, 2)); ?>, <?php echo $alpha; ?>);
    <?php endif; ?>
  <?php endif; ?>
  /* Alpha channel*/
}

.dj_ie7 <?php echo $c['id']; ?> dt.sslide.selected .arrowup,
.dj_ie7 <?php echo $c['id']; ?> dt.sslide:HOVER .arrowup{
  border-top: #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> dt.sslide .arrowup.show {
  visibility: visible;
}

<?php echo $c['id']; ?> dt.sslide .col {
  /* Alpha channel*/
  <?php 
    $color = $this->env->slider->params->get('innerbordercolor', 'ffffff');
    if(strlen($color) == 6):
  ?>
  border-top: 1px solid #<?php echo $color; ?>;
  <?php else: 
    $alpha = round(hexdec(substr($color, 6, 2))/255, 1);
    if(hexdec(substr($color, 6, 2)) != 1):
  ?>
    border-top: 1px solid <?php echo substr($color, 0, 4); ?>;
    border-top: 1px solid rgba(<?php echo hexdec(substr($color, 0, 2)); ?>, <?php echo hexdec(substr($color, 2, 2)); ?>, <?php echo hexdec(substr($color, 4, 2)); ?>, <?php echo $alpha; ?>);
    <?php endif; ?>
  <?php endif; ?>
  /* Alpha channel*/
 height: 1px;
}

.dj_ie7 <?php echo $c['id']; ?> dt.sslide .col {
  border-top: #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> dt.sslide.selected .col {
  /* Alpha channel*/
  <?php 
    $color = $this->env->slider->params->get('outerbordercolor', 'ffffff');
    if(strlen($color) == 6):
  ?>
  border-top: 1px solid #<?php echo $color; ?>;
  <?php else: 
    $alpha = round(hexdec(substr($color, 6, 2))/255, 1);
    if(hexdec(substr($color, 6, 2)) != 1):
  ?>
    border-top: 1px solid <?php echo substr($color, 0, 4); ?>;
    border-top: 1px solid rgba(<?php echo hexdec(substr($color, 0, 2)); ?>, <?php echo hexdec(substr($color, 2, 2)); ?>, <?php echo hexdec(substr($color, 4, 2)); ?>, <?php echo $alpha; ?>);
    <?php endif; ?>
  <?php endif; ?>
  /* Alpha channel*/
}

.dj_ie7 <?php echo $c['id']; ?> dt.sslide.selected .col {
  border-top: #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> dt.sslide .arrowdown {
  visibility: hidden;
  cursor: pointer;
  width: 30px;
  height: 30px;
  background: url('<?php echo $c['url']; ?>images/down.png') no-repeat center center;
  /* Alpha channel*/
  <?php 
    $color = $this->env->slider->params->get('innerbordercolor', 'ffffff');
    if(strlen($color) == 6):
  ?>
  border-bottom: 1px solid #<?php echo $color; ?>;
  <?php else: 
    $alpha = round(hexdec(substr($color, 6, 2))/255, 1);
    if(hexdec(substr($color, 6, 2)) != 1):
  ?>
    border-bottom: 1px solid <?php echo substr($color, 0, 4); ?>;
    border-bottom: 1px solid rgba(<?php echo hexdec(substr($color, 0, 2)); ?>, <?php echo hexdec(substr($color, 2, 2)); ?>, <?php echo hexdec(substr($color, 4, 2)); ?>, <?php echo $alpha; ?>);
    <?php endif; ?>
  <?php endif; ?>
  /* Alpha channel*/
  box-shadow: 0.5px 0.5px 1px rgba(255, 255, 255, 0.4) inset;
}

.dj_ie7 <?php echo $c['id']; ?> dt.sslide .arrowdown {
  border-bottom: #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> dt.sslide.selected .arrowdown, <?php echo $c['id']; ?> dt.sslide:HOVER .arrowdown {
  /* Alpha channel*/
  <?php 
    $color = $this->env->slider->params->get('outerbordercolor', 'ffffff');
    if(strlen($color) == 6):
  ?>
  border-bottom: 1px solid #<?php echo $color; ?>;
  <?php else: 
    $alpha = round(hexdec(substr($color, 6, 2))/255, 1);
    if(hexdec(substr($color, 6, 2)) != 1):
  ?>
    border-bottom: 1px solid <?php echo substr($color, 0, 4); ?>;
    border-bottom: 1px solid rgba(<?php echo hexdec(substr($color, 0, 2)); ?>, <?php echo hexdec(substr($color, 2, 2)); ?>, <?php echo hexdec(substr($color, 4, 2)); ?>, <?php echo $alpha; ?>);
    <?php endif; ?>
  <?php endif; ?>
  /* Alpha channel*/
}

.dj_ie7 <?php echo $c['id']; ?> dt.sslide.selected .arrowdown, 
.dj_ie7 <?php echo $c['id']; ?> dt.sslide:HOVER .arrowdown {
  border-bottom: #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> dt.sslide .arrowdown.show {
  visibility: visible;
}

<?php echo $c['id']; ?> dt.sslide .arrowdown.show:HOVER {

}

<?php echo $c['id']; ?> dt.sslide .dots .numbers {
  text-align: center;
  /* Alpha channel*/
  <?php 
    $color1 = $color = $this->env->slider->params->get('innerbordercolor', 'ffffff');
    if(strlen($color) == 6):
  ?>
  border-top: 1px solid #<?php echo $color; ?>;
  <?php else: 
    $alpha = round(hexdec(substr($color, 6, 2))/255, 1);
    if(hexdec(substr($color, 6, 2)) != 1):
  ?>
    border-top: 1px solid <?php echo substr($color, 0, 4); ?>;
    border-top: 1px solid rgba(<?php echo hexdec(substr($color, 0, 2)); ?>, <?php echo hexdec(substr($color, 2, 2)); ?>, <?php echo hexdec(substr($color, 4, 2)); ?>, <?php echo $alpha; ?>);
    <?php endif; ?>
  <?php endif; ?>
  /* Alpha channel*/
  /* Alpha channel*/
  <?php 
    $color = $this->env->slider->params->get('innerbordercolor', 'ffffff');
    if(strlen($color) == 6):
  ?>
  border-bottom: 1px solid #<?php echo $color; ?>;
  <?php else: 
    $alpha = round(hexdec(substr($color, 6, 2))/255, 1);
    if(hexdec(substr($color, 6, 2)) != 1):
  ?>
    border-bottom: 1px solid <?php echo substr($color, 0, 4); ?>;
    border-bottom: 1px solid rgba(<?php echo hexdec(substr($color, 0, 2)); ?>, <?php echo hexdec(substr($color, 2, 2)); ?>, <?php echo hexdec(substr($color, 4, 2)); ?>, <?php echo $alpha; ?>);
    <?php endif; ?>
  <?php endif; ?>
  /* Alpha channel*/
  box-shadow: 0.5px 0.5px 1px rgba(255, 255, 255, 0.4) inset;
  padding: 7px 0 7px 0;
   /*font chooser*/
  <?php $f = $slidenumberfont; ?>
  color: #<?php echo $f[6]?>;
  font-family: <?php echo ($f[2] ? '"'.$f[2].'"':'').($f[2] && $f[1] ? ',':'').$f[1];?>;
  font-weight: <?php echo $f[4]? 'bold' : 'normal';?>;
  font-style: <?php echo $f[5]? 'italic' : 'normal';?>;
  font-size: <?php echo $f[3]?>;
  <?php if($this->env->slider->params->get('textshadow') && $f[7]): ?>
  text-shadow: #<?php echo $f[11]?> <?php echo $f[8]?> <?php echo $f[9]?> <?php echo $f[10]?>;
  <?php else: ?>
  text-shadow: none;
  <?php endif; ?>
  /*font chooser*/
}

.dj_ie7 <?php echo $c['id']; ?> dt.sslide .dots .numbers{
  border-top: #<?php echo substr($color1, 0, 6); ?>;
  border-bottom: #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> dt.sslide.selected .dots .numbers, <?php echo $c['id']; ?> dt.slide:HOVER .dots .numbers {
  /* Alpha channel*/
  <?php 
    $color1 = $color = $this->env->slider->params->get('outerbordercolor', 'ffffff');
    if(strlen($color) == 6):
  ?>
  border-top: 1px solid #<?php echo $color; ?>;
  <?php else: 
    $alpha = round(hexdec(substr($color, 6, 2))/255, 1);
    if(hexdec(substr($color, 6, 2)) != 1):
  ?>
    border-top: 1px solid <?php echo substr($color, 0, 4); ?>;
    border-top: 1px solid rgba(<?php echo hexdec(substr($color, 0, 2)); ?>, <?php echo hexdec(substr($color, 2, 2)); ?>, <?php echo hexdec(substr($color, 4, 2)); ?>, <?php echo $alpha; ?>);
    <?php endif; ?>
  <?php endif; ?>
  /* Alpha channel*/
  /* Alpha channel*/
  <?php 
    $color = $this->env->slider->params->get('outerbordercolor', 'ffffff');
    if(strlen($color) == 6):
  ?>
  border-bottom: 1px solid #<?php echo $color; ?>;
  <?php else: 
    $alpha = round(hexdec(substr($color, 6, 2))/255, 1);
    if(hexdec(substr($color, 6, 2)) != 1):
  ?>
    border-bottom: 1px solid <?php echo substr($color, 0, 4); ?>;
    border-bottom: 1px solid rgba(<?php echo hexdec(substr($color, 0, 2)); ?>, <?php echo hexdec(substr($color, 2, 2)); ?>, <?php echo hexdec(substr($color, 4, 2)); ?>, <?php echo $alpha; ?>);
    <?php endif; ?>
  <?php endif; ?>
  /* Alpha channel*/
}

.dj_ie7 <?php echo $c['id']; ?> dt.sslide.selected .dots .numbers, 
.dj_ie7 <?php echo $c['id']; ?> dt.slide:HOVER .dots .numbers{
  border-top: #<?php echo substr($color1, 0, 6); ?>;
  border-bottom: #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> dt.sslide .dots .nums {
  text-align: center;
}

<?php echo $c['id']; ?> dt.sslide .numbering{
  display: block;
  position: absolute;
  bottom: 5px;
  width: <?php echo $titleWidth; ?>px;
  text-align: center;
  <?php if(!$this->env->slider->params->get('slidenumbering')): ?>
  display: none;
  <?php endif; ?>

  /*font chooser*/
  <?php $f = $tabfont; ?>
  color: #<?php echo $f[6]?>;
  font-family: <?php echo ($f[2] ? '"'.$f[2].'"':'').($f[2] && $f[1] ? ',':'').$f[1];?>;
  font-weight: <?php echo $f[4]? 'bold' : 'normal';?>;
  font-style: <?php echo $f[5]? 'italic' : 'normal';?>;
  font-size: <?php echo $f[3]?>;
  <?php if($this->env->slider->params->get('textshadow') && $f[7]): ?>
  text-shadow: #<?php echo $f[11]?> <?php echo $f[8]?> <?php echo $f[9]?> <?php echo $f[10]?>;
  <?php else: ?>
  text-shadow: none;
  <?php endif; ?>
  /*font chooser*/
}

<?php echo $c['id']; ?> dt.sslide.selected .numbering, <?php echo $c['id']; ?> dt.sslide:HOVER .numbering{
  /*font chooser*/
  <?php $f = $selectedtabfont; ?>
  color: #<?php echo $f[6]?>;
  font-family: <?php echo ($f[2] ? '"'.$f[2].'"':'').($f[2] && $f[1] ? ',':'').$f[1];?>;
  font-weight: <?php echo $f[4]? 'bold' : 'normal';?>;
  font-style: <?php echo $f[5]? 'italic' : 'normal';?>;
  font-size: <?php echo $f[3]?>;
  <?php if($this->env->slider->params->get('textshadow') && $f[7]): ?>
  text-shadow: #<?php echo $f[11]?> <?php echo $f[8]?> <?php echo $f[9]?> <?php echo $f[10]?>;
  <?php else: ?>
  text-shadow: none;
  <?php endif; ?>
  /*font chooser*/
}

<?php echo $c['id']; ?> dt.sslide .icon{
  display: block; 
  position: absolute;
  bottom: 5px;
  <?php if($this->env->slider->params->get('slidenumbering') && $this->env->slider->params->get('slideicons')): ?>
  bottom: 25px;
  <?php endif; ?>
  width: <?php echo $titleWidth; ?>px;
  height: 20px;
  background-repeat: no-repeat;
  background-position: center center;
  <?php if(!$this->env->slider->params->get('slideicons')): ?>
  display: none;
  <?php endif; ?>
}

<?php
  $marginDd = array(2, 2, 2, -2);
  $paddingDd = array(0, 0, 0, 2);
?>
<?php echo $c['id']; ?> dd.sslide{
  position: relative;
  height: <?php echo $cheight; ?>px;
  width: 0px;
  float: left;
  margin: <?php echo implode('px ', $marginDd); ?>px;
  padding: <?php echo implode('px ', $paddingDd); ?>px;
  overflow: hidden;
  border-top-right-radius: 2px;
  border-bottom-right-radius: 2px;
}

<?php echo $c['id']; ?> dd.sslide.selected{
  display: block;
  float:left;
  z-index: 55;
}

<?php
$canvasWidth = $cwidth - $count * ($marginDt[1] + $marginDt[3] + $titleWidth + $marginDd[1] + $marginDd[3] + $paddingDd[1] + $paddingDd[3]);
$canvasHeight = $cheight;
?>
<?php echo $c['id']; ?> dd.sslide.selected{
  width: <?php echo $canvasWidth; ?>px;
}

<?php echo $c['id']; ?> dd.sslide .arrowdown{
  width: 32px;
  height: 32px;
  background: url('<?php echo $c['url']; ?>images/arrowdown.png') no-repeat;
  position: absolute;
  left: 50%;
  bottom: 10px;
  margin-left: -16px;
  cursor: pointer;
  z-index: 10;
  display: none;
  opacity:0;
}

<?php echo $c['id']; ?> dd.sslide .arrowup{
  width: 32px;
  height: 32px;
  background: url('<?php echo $c['url']; ?>images/arrowup.png') no-repeat;
  position: absolute;
  left: 50%;
  top: 10px;
  margin-left: -16px;
  cursor: pointer;
  z-index: 10;
  display: none;
  opacity:0;
}

<?php echo $c['id']; ?> dd.sslide .arrowdown:hover, <?php echo $c['id']; ?> dd.sslide .arrowup:hover{
  opacity: 1;
}

<?php echo $c['id']; ?> dl dd.sslide .show{
  display: block;
  opacity:0.6;
}

<?php echo $c['id']; ?> dd.sslide .vertical{
  margin: 0;
  padding: 0;
  position: absolute;
}

<?php echo $c['id']; ?> dd.sslide .vertical li.subslide{
  margin: 0;
  padding: 0;
  position: relative;
  display:block;
  height: <?php echo $cheight; ?>px;
  width: <?php echo $canvasWidth; ?>px;
  float: left;
  overflow: hidden;
}

<?php echo $c['id']; ?> dd.sslide .canvas{
  height: <?php echo $cheight; ?>px;
  width: <?php echo $canvasWidth; ?>px;
  float: left;
  overflow: hidden;
  border-top-right-radius: 2px;
  border-bottom-right-radius: 2px;
}

<?php echo $c['id']; ?> dd.sslide .arrow{
  display: none;
}

<?php echo $c['id']; ?> dd.sslide.selected .arrow{
  z-index: 50;
  display: block;
  position: absolute;
  top: 20px;
  left: -2px;
  width: 19px;
  height: 30px;
  background-image: url('<?php if(!$this->calc) echo $this->themeCacheUrl.$c['helper']->ColorizeImage(dirname(__FILE__).'/images/blue-arrow.png', substr($this->env->slider->params->get('selectedtabbg'),0,6), '188dd9'); ?>');
  background-position: -3px 0;
}

<?php
if(!$this->calc && isset($c['captioncss'])){
  include($c['captioncss']);
}

if(!$this->calc && isset($c['contentcss'])){
  include($c['contentcss']);
}
?>
