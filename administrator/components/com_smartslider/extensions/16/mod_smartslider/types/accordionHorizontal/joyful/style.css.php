<?php 
/*------------------------------------------------------------------------
# smartslider - Smart Slider
# ------------------------------------------------------------------------
# author    Roland Soos 
# copyright Copyright (C) 2011 Offlajn.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.offlajn.com
-------------------------------------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
$sp = &$this->env->slider->params;
$count = count($this->env->slides);
if(!$this->calc &&  isset($c['clearcss'])){
  include($c['clearcss']);
}
?>

<?php echo $c['id']; ?> a:hover{
  text-decoration: none;
}

<?php echo $c['id']; ?> dl, <?php echo $c['id']; ?> dd, <?php echo $c['id']; ?> dt{
  padding: 0;
  margin: 0;
  float:left;
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
  $paddingOut = array(1, 1, 1, 1);
  $cwidth = $cwidth-$paddingOut[1]-$paddingOut[3];
  $cheight = $cheight-$paddingOut[0]-$paddingOut[2];
?>
<?php echo $c['id']; ?> .outer{
  border-radius: 1px;
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
  $paddingIn = array(6, 6, 6, 6);
  $cwidth = $cwidth-$paddingIn[1]-$paddingIn[3];
  $cheight = $cheight-$paddingIn[0]-$paddingIn[2];
?>
<?php echo $c['id']; ?> .slinner{
  width: <?php echo $cwidth; ?>px;
  height: <?php echo $cheight; ?>px;
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
  $paddingDt = array(3, 3, 3, 3);
  $cheight = $cheight-$marginDt[0]-$marginDt[2];
  $titleWidth = 38;
  $titleHeight = $cheight;
?>

<?php echo $c['id']; ?> dt.sslide{
  position: relative;
  width: <?php echo $titleWidth - $paddingDt[1] - $paddingDt[3]; ?>px;
  height: <?php echo $titleHeight - $paddingDt[0] - $paddingDt[2]; ?>px !important;
  float:left;
  background: url('<?php echo $c['url']; ?>images/black10.png') repeat;
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
  cursor: pointer;
  margin: <?php echo implode('px ', $marginDt); ?>px;
  padding: <?php echo implode('px ', $paddingDt); ?>px;
}

.dj_ie7 <?php echo $c['id']; ?> dt.sslide{
  background-color: #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> dt .slideinner{
  float: left;
  width: <?php echo $titleWidth - $paddingDt[1] - $paddingDt[3]; ?>px;
  height: <?php echo $titleHeight - $paddingDt[0] - $paddingDt[2]; ?>px;
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
  display: block;
}

.dj_ie7 <?php echo $c['id']; ?> dt .slideinner{
  background-color: #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> dt .slidepattern{
  position: relative;
  width: <?php echo $titleWidth; ?>px;
  height: <?php echo $cheight; ?>px;
  background: url('<?php echo $c['url']; ?>images/pattern.png') repeat;
  overflow: hidden;
  z-index: 50;
  margin: -<?php echo $paddingDt[0]; ?>px 0 0 -<?php echo $paddingDt[3]; ?>px;
  display: block;
}

<?php echo $c['id']; ?> dt.sslide .rotated-90{
  -moz-transform-origin: center center;
  -moz-transform: rotate(-90deg);
  -webkit-transform: rotate(-90deg);
  -o-transform: rotate(-90deg);
  zoom: 1;

  height: <?php echo $cheight-10; ?>px;
  width: <?php echo $cheight-10; ?>px;
  display: block;
  margin: 5px;
}

.dj_ie7 <?php echo $c['id']; ?> dt.sslide .rotated-90,
.dj_ie8 <?php echo $c['id']; ?> dt.sslide .rotated-90{
  filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
}

.dj_ie9 <?php echo $c['id']; ?> dt.sslide .rotated-90{
  -ms-transform: rotate(-90deg);
}

<?php echo $c['id']; ?> dt.sslide .title{
  height: <?php echo $titleWidth-5*2; ?>px;
  line-height: <?php echo $titleWidth-5*2; ?>px;
  padding: 0 0 0 10px;
  display: block;
  text-align: left;
  
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

.dj_ie7 <?php echo $c['id']; ?> dt.sslide .title,
.dj_ie8 <?php echo $c['id']; ?> dt.sslide .title{
  background: url('<?php echo $c['url']; ?>images/patternrot90.png') repeat;
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
    <?php endif; ?>
  <?php endif; ?>
  /* Alpha channel*/
}

<?php echo $c['id']; ?> dt.selected, <?php echo $c['id']; ?> dt.sslide:HOVER{
  background: url('<?php echo $c['url']; ?>images/black20.png') repeat;
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

.dj_ie7 <?php echo $c['id']; ?> dt.selected, 
.dj_ie7 <?php echo $c['id']; ?> dt.sslide:HOVER{
  background-color: #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> dt.selected .slideinner, <?php echo $c['id']; ?> dt.sslide:HOVER .slideinner{
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

.dj_ie7 <?php echo $c['id']; ?> dt.selected .slideinner, 
.dj_ie7 <?php echo $c['id']; ?> dt.sslide:HOVER .slideinner{
  background-color: #<?php echo substr($color, 0, 6); ?>;
}


.dj_ie7 <?php echo $c['id']; ?> dt.sslide.selected .title,
.dj_ie8 <?php echo $c['id']; ?> dt.sslide.selected .title,
.dj_ie7 <?php echo $c['id']; ?> dt.sslide:HOVER .title,
.dj_ie8 <?php echo $c['id']; ?> dt.sslide:HOVER .title{
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
    <?php endif; ?>
  <?php endif; ?>
  /* Alpha channel*/
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

<?php echo $c['id']; ?> dt.selected{
  cursor: auto;
}

<?php echo $c['id']; ?> dt.sslide .dots{
  position: absolute;
  left: 0;
  top: 45px;
  <?php if($this->env->slider->params->get('slidenumbering') && $this->env->slider->params->get('slideicons')): ?>
  top: 65px;
  <?php endif; ?>
  width: <?php echo $titleWidth; ?>px;
  z-index: 56;
  display: block;
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
  display: block;
}

<?php echo $c['id']; ?> dt.sslide .dots .dot.active{
  background-image: url('<?php echo $c['url']; ?>images/filleddot.png');
  cursor: auto;
}

<?php echo $c['id']; ?> dt.sslide.selected .dots .dot.active, <?php echo $c['id']; ?> dt.sslide:hover .dots .dot.active{
}

/*Circles with numbers*/

<?php echo $c['id']; ?> dt.sslide .dots .circle {
  width: 15px;
  height: 15px;
  background: url('<?php echo $c['url']; ?>images/numberingbg.png') no-repeat;
  margin: 3px auto 0;
  text-align: center;
  padding-top: 1px;
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
  font-weight: bold;
}

.dj_ie7 <?php echo $c['id']; ?> dt.sslide .dots .circle.active{
  background-color: #<?php echo substr($color, 0, 6); ?>;
}

/*Arrows*/

<?php echo $c['id']; ?> dt.sslide .arrowup {
  visibility: hidden;
  cursor: pointer;
  width: 32px;
  height: 30px;
  background: url('<?php echo $c['url']; ?>images/up.png') no-repeat center center;
 /* border-top: 1px solid #3E3E3E;
  box-shadow: 0px 0.5px 1px rgba(255, 255, 255, 0.4) inset;*/
  margin-left: 3px;
}

<?php echo $c['id']; ?> dt.sslide .topline {
  top: 0;
  height: 2px;
  background: url('<?php echo $c['url']; ?>images/black20.png') repeat;
}

<?php echo $c['id']; ?> dt.sslide .col {
  height: 2px;
  width: 32px;
  background: url('<?php echo $c['url']; ?>images/black20.png') repeat;
  margin-left: 3px;
}

<?php echo $c['id']; ?> dt.sslide .arrowup.show {
  visibility: visible;
}

<?php echo $c['id']; ?> dt.sslide .arrowup.show:HOVER {
 
 }

<?php echo $c['id']; ?> dt.sslide .arrowdown {
  visibility: hidden;
  cursor: pointer;
  width: 32px;
  height: 30px;
  background: url('<?php echo $c['url']; ?>images/down.png') no-repeat center center;
  /*border-bottom: 1px solid #3E3E3E;
  box-shadow: 0px 0.5px 1px rgba(255, 255, 255, 0.4) inset;*/
  margin-left: 3px;
}

<?php echo $c['id']; ?> dt.sslide .bottomnline {
  height: 2px;
  background: url('<?php echo $c['url']; ?>images/black20.png') repeat;
}

<?php echo $c['id']; ?> dt.sslide .bottomline {
  height: 2px;
  background: url('<?php echo $c['url']; ?>images/black20.png') repeat;
  bottom: 0;
  width: 32px;
  position: absolute;
}

<?php echo $c['id']; ?> dt.sslide .arrowdown.show {
  visibility: visible;
}

<?php echo $c['id']; ?> dt.sslide .arrowdown.show:HOVER {

}
<?php echo $c['id']; ?> dt.sslide .dots .numbers{
  margin-left: 3px;
  width: 32px;
}

<?php echo $c['id']; ?> dt.sslide .dots .nums {
  text-align: center;
 /* border-top: 1px solid #3E3E3E;
  border-bottom: 1px solid #3E3E3E;*/
  
 /* box-shadow: 0px 0.5px 1px rgba(255, 255, 255, 0.4) inset;*/
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

<?php echo $c['id']; ?> dt.sslide .numbering{
  position: absolute;
  top: 8px;
  width: 100%;
  padding: 5px 0;
  text-align: center;
  <?php if(!$this->env->slider->params->get('slidenumbering')): ?>
  display: none;
  <?php endif; ?>
  background: url('<?php echo $c['url']; ?>images/black20.png') repeat;
  display: block;

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
  top: 10px;
  <?php if($this->env->slider->params->get('slidenumbering') && $this->env->slider->params->get('slideicons')): ?>
  top: 45px;
  <?php endif; ?>
  width: 100%;
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
}

.dj_ie7 <?php echo $c['id']; ?> dd.sslide{

}

<?php echo $c['id']; ?> dd.sslide.selected{

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
}

<?php
if(!$this->calc && isset($c['captioncss'])){
  include($c['captioncss']);
}

if(!$this->calc && isset($c['contentcss'])){
  include($c['contentcss']);
}
?>
