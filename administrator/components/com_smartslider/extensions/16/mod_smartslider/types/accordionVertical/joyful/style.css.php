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
  background: url('<?php echo $c['url']; ?>images/pattern.png') repeat;
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
  position: relative;
  width: <?php echo $cwidth; ?>px;
  height: <?php echo $cheight+100; ?>px;
}

<?php
  $marginDt = array(2, 2, 0, 2);
  $paddingDt = array(3, 3, 3, 3);
  $cwidth = $cwidth-$marginDt[1]-$marginDt[3];
  $titleHeight = 30;
  $titleWidth = $cwidth;
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
  width: <?php echo $cwidth; ?>px;
  height: <?php echo $titleHeight; ?>px;
  background: url('<?php echo $c['url']; ?>images/pattern.png') repeat;
  overflow: hidden;
  z-index: 50;
  margin: -<?php echo $paddingDt[0]; ?>px 0 0 -<?php echo $paddingDt[3]; ?>px;
  display: block;
}

<?php echo $c['id']; ?> dt.sslide .rotated-0{
  position: absolute;
  right: 10px;
  width: 80%;
  height: <?php echo $titleHeight; ?>px;
  line-height: <?php echo $titleHeight; ?>px;
  display: block;
}

<?php echo $c['id']; ?> dt.sslide .title{
  width: 100%;
  display: block;
  text-align: right;
  line-height: 30px;
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

.dj_ie7 <?php echo $c['id']; ?> dt.selected, .dj_ie7 <?php echo $c['id']; ?> dt.sslide:HOVER{
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
  <?php if ($this->env->slider->params->get('showdots', 1) == 2 || $this->env->slider->params->get('showdots', 1) == 3): ?>
  left: 27px;
  <?php else: ?>
  left: 40px;
  <?php endif; ?>
  <?php if($this->env->slider->params->get('slidenumbering') && $this->env->slider->params->get('slideicons')): ?>
  left: 60px;
  <?php endif; ?>
  top: 0;
  height: <?php echo $titleHeight; ?>px;
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
  margin: <?php echo $titleHeight/2-9/2; ?>px 3px 0 0;
  cursor: pointer;
  float: right;
}

.dj_ie7 <?php echo $c['id']; ?> dt.sslide .dots{
  width: 50px;
}

<?php echo $c['id']; ?> dt.sslide .dots .dot.active{
  background-image: url('<?php echo $c['url']; ?>images/filleddot.png');
  cursor: auto;
}

/*Circles with numbers*/

<?php echo $c['id']; ?> dt.sslide .dots .circle {
  width: 15px;
  height: 15px;
  background: url('<?php echo $c['url']; ?>images/numberingbg.png') no-repeat;
  margin: 7px 0 0 3px;
  text-align: center;
  padding-top: 1px;
  cursor: pointer;
  color: #fff; 
  display: block;
  font-family: Arial;
  font-size: 11px;
  text-shadow: 0 1px 1px #000000;
  top: 0;
  float: right;
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
  background-color: #<?php echo substr($color, 0, 6); ?>;
}

/*Arrows*/

<?php echo $c['id']; ?> dt.sslide .arrowleft {
  visibility: hidden;
  cursor: pointer;
  width: 32px;
  height: 24px;
  background: url('<?php echo $c['url']; ?>images/left.png') no-repeat center center;
 /* border-top: 1px solid #3E3E3E;
  box-shadow: 0px 0.5px 1px rgba(255, 255, 255, 0.4) inset;*/
  margin: 3px 0 3px 3px;
  float: left;
}

<?php echo $c['id']; ?> dt.sslide .topline {
  top: 0;
  height: 24px;
  width: 2px;
  background: url('<?php echo $c['url']; ?>images/black20.png') repeat;
  float: left;
}

<?php echo $c['id']; ?> dt.sslide .arrowleft.show {
  visibility: visible;
}

<?php echo $c['id']; ?> dt.sslide .arrowleft.show:HOVER {
 
 }

<?php echo $c['id']; ?> dt.sslide .arrowright {
  visibility: hidden;
  cursor: pointer;
  width: 32px;
  height: 24px;
  background: url('<?php echo $c['url']; ?>images/right.png') no-repeat center center;
  /*border-bottom: 1px solid #3E3E3E;
  box-shadow: 0px 0.5px 1px rgba(255, 255, 255, 0.4) inset;*/
  margin: 3px 0 3px 3px;
  float: right;
}

<?php echo $c['id']; ?> dt.sslide .bottomnline {
  height: 24px;
  width: 2px;
  background: url('<?php echo $c['url']; ?>images/black20.png') repeat;
  float: right;
}

<?php echo $c['id']; ?> dt.sslide .bottomline {
  background: url('<?php echo $c['url']; ?>images/black20.png') repeat;
  right: 0;
  width: 2px;
  height: 24px;
  position: absolute;
}

<?php echo $c['id']; ?> dt.sslide .arrowright.show {
  visibility: visible;
}

<?php echo $c['id']; ?> dt.sslide .col {
  margin-top: 3px;
  background: url('<?php echo $c['url']; ?>images/black20.png') repeat;
  float: left;
  width: 2px;
  height: 24px;
  
}

<?php echo $c['id']; ?> dt.sslide .arrowright.show:HOVER {

}
<?php echo $c['id']; ?> dt.sslide .dots .numbers{
  margin-top: 3px;
  float: left;
  text-align: center;
}

<?php echo $c['id']; ?> dt.sslide .dots .nums {
  float: left;
 /* border-top: 1px solid #3E3E3E;
  border-bottom: 1px solid #3E3E3E;*/
    width: 32px;
    text-align: center;  
 /* box-shadow: 0px 0.5px 1px rgba(255, 255, 255, 0.4) inset;*/
  padding: 4px 0 4px 0;
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
  display: block;
  position: absolute;
  left: 8px;
  height: <?php echo $titleHeight; ?>px;
  width: <?php echo $titleHeight-6; ?>px;
  line-height: <?php echo $titleHeight; ?>px;
  text-align: center;
  <?php if(!$this->env->slider->params->get('slidenumbering')): ?>
  display: none;
  <?php endif; ?>
  background: url('<?php echo $c['url']; ?>images/black20.png') repeat;
  
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
  top: 0;
  left: 10px;
  <?php if($this->env->slider->params->get('slidenumbering') && $this->env->slider->params->get('slideicons')): ?>
  left: 35px;
  <?php endif; ?>
  height: <?php echo $titleHeight+1; ?>px;
  width: 20px;
  background-repeat: no-repeat;
  background-position: center center;
  <?php if(!$this->env->slider->params->get('slideicons')): ?>
  display: none;
  <?php endif; ?>
}

<?php
  $marginDd = array(-2, 2, 2, 2);
  $paddingDd = array(2, 0, 0, 0);
?>
<?php echo $c['id']; ?> dd.sslide{
  position: relative;
  width: <?php echo $cwidth; ?>px;
  height: 0px;
  float: left;
  margin: <?php echo implode('px ', $marginDd); ?>px;
  padding: <?php echo implode('px ', $paddingDd); ?>px;
  overflow: hidden;
}

<?php echo $c['id']; ?> dd.sslide.selected{
  display: block;
  float:left;
  z-index: 55;
}

<?php
$canvasHeight = $cheight - $count * ($marginDt[0] + $marginDt[2] + $titleHeight + $marginDd[0] + $marginDd[2] + $paddingDd[0] + $paddingDd[2]);
$canvasWidth = $cwidth;
?>
<?php echo $c['id']; ?> dd.sslide.selected{
  height: <?php echo $canvasHeight; ?>px;
}

<?php echo $c['id']; ?> dd.sslide .arrowleft{
  width: 32px;
  height: 32px;
  background: url('<?php echo $c['url']; ?>images/arrowleft.png') no-repeat;
  position: absolute;
  left: 10px;
  top: 50%;
  margin-top: -16px;
  cursor: pointer;
  z-index: 10;
  display: none;
  opacity:0;
}

<?php echo $c['id']; ?> dd.sslide .arrowright{
  width: 32px;
  height: 32px;
  background: url('<?php echo $c['url']; ?>images/arrowright.png') no-repeat;
  position: absolute;
  right: 10px;
  top: 50%;
  margin-top: -16px;
  cursor: pointer;
  z-index: 10;
  display: none;
  opacity:0;
}

<?php echo $c['id']; ?> dd.sslide .arrowright:hover, <?php echo $c['id']; ?> dd.sslide .arrowleft:hover{
  opacity: 1;
}

<?php echo $c['id']; ?> dl dd.sslide .show{
  display: block;
  opacity:0.6;
}

<?php echo $c['id']; ?> dd.sslide .vertical{
  margin: 0;
  padding: 0;
  width: 200000px;
}

<?php echo $c['id']; ?> dd.sslide .vertical li.subslide{
  margin: 0;
  padding: 0;
  position: relative;
  display:block;
  height: <?php echo $canvasHeight; ?>px;
  width: <?php echo $canvasWidth; ?>px;
  float: left;
  overflow: hidden;
}

<?php echo $c['id']; ?> dd.sslide .canvas{
  height: <?php echo $canvasHeight; ?>px;
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
