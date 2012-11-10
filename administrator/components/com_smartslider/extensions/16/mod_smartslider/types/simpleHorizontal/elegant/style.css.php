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

<?php echo $c['id']; ?> ul, <?php echo $c['id']; ?> .sslide{
  padding: 0;
  margin: 0;
  list-style-type: none;
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
  border-radius: 3px;
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
  box-shadow: 0px -1px 7px RGBA(0,0,0,0.3);
}

.dj_ie7 <?php echo $c['id']; ?> .outer{
  background-color: #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> .slinner{
  width: <?php echo $cwidth; ?>px;
  height: <?php echo $cheight; ?>px;
  border-radius: 3px;
  overflow: hidden;
  position: relative;
  padding: 0;
}

<?php 
  $controllHeight = 33;
  if($this->env->slider->params->get('ctrlbar', 1) == 0){
    $controllHeight = -1;
  }
?>

<?php echo $c['id']; ?> .controll{
  position: absolute;
  width: <?php echo $cwidth; ?>px;
  height: <?php echo $controllHeight; ?>px;
  left: 0;
  bottom: 0;
  /* Alpha channel*/
  <?php 
    $color1 = $color = $this->env->slider->params->get('tabbg', 'ffffff');
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
  <?php if($this->env->slider->params->get('ctrlbar', 1) == 0):?>
  display: none;
  <?php endif; ?>
}

.dj_ie7 <?php echo $c['id']; ?> .controll{
  background-color: #<?php echo substr($color1, 0, 6); ?>;
  border-top: 1px solid #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> .controllbtn{
  line-height: 32px;
  text-transform: uppercase;
  cursor: pointer;
  /*font chooser*/
  <?php $f = $controllfont; ?>
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

<?php echo $c['id']; ?> .controll .left{
  position: absolute;
  left: 0;
  top: 0;
  /* Alpha channel*/
  <?php 
    $color = $this->env->slider->params->get('outerbordercolor', 'ffffff');
    if(strlen($color) == 6):
  ?>
  border-right: 1px solid #<?php echo $color; ?>;
  <?php else: 
    $alpha = round(hexdec(substr($color, 6, 2))/255, 1);
    if(hexdec(substr($color, 6, 2)) != 1):
  ?>
    border-right: 1px solid <?php echo substr($color, 0, 4); ?>;
    border-right: 1px solid rgba(<?php echo hexdec(substr($color, 0, 2)); ?>, <?php echo hexdec(substr($color, 2, 2)); ?>, <?php echo hexdec(substr($color, 4, 2)); ?>, <?php echo $alpha; ?>);
    <?php endif; ?>
  <?php endif; ?>
  /* Alpha channel*/
  height: <?php echo $controllHeight; ?>px;
  background: url('<?php echo $c['url']; ?>images/arrowleft.png') no-repeat left center;
  padding: 0px 10px 0 20px;
}

.dj_ie7 <?php echo $c['id']; ?> .controll .left{
  border-right: 1px solid #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> .controll .right{
  position: absolute;
  right: 0;
  top: 0;
  /* Alpha channel*/
  <?php 
    $color = $this->env->slider->params->get('outerbordercolor', 'ffffff');
    if(strlen($color) == 6):
  ?>
  border-left: 1px solid #<?php echo $color; ?>;
  <?php else: 
    $alpha = round(hexdec(substr($color, 6, 2))/255, 1);
    if(hexdec(substr($color, 6, 2)) != 1):
  ?>
    border-left: 1px solid <?php echo substr($color, 0, 4); ?>;
    border-left: 1px solid rgba(<?php echo hexdec(substr($color, 0, 2)); ?>, <?php echo hexdec(substr($color, 2, 2)); ?>, <?php echo hexdec(substr($color, 4, 2)); ?>, <?php echo $alpha; ?>);
    <?php endif; ?>
  <?php endif; ?>
  /* Alpha channel*/
  height: <?php echo $controllHeight; ?>px;
  background: url('<?php echo $c['url']; ?>images/arrowright.png') no-repeat right center;
  padding: 0px 20px 0 10px;
  text-align: right;
}

.dj_ie7 <?php echo $c['id']; ?> .controll .right{
  border-left: 1px solid #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> .controll .right:hover, <?php echo $c['id']; ?> .controll .left:hover{
  background-color: #<?php echo substr($this->env->slider->params->get('selectedtabbg'),0,6); ?>;
}

<?php echo $c['id']; ?> .controll .right:active, <?php echo $c['id']; ?> .controll .left:active{
  box-shadow:inset 1px 1px 5px RGBA(0,0,0,0.6);
}

<?php echo $c['id']; ?> .controll .dots{
  height: <?php echo $controllHeight; ?>px;
  margin: 0 auto;
}

<?php echo $c['id']; ?> .controll .dots .dot{
  background: url('<?php echo $c['url']; ?>images/dot.png') no-repeat center center;
  width: 13px;
  height: <?php echo $controllHeight; ?>px;
  padding: 0 3px;
  float: left;
  cursor: pointer;
}

<?php echo $c['id']; ?> .controll .dots .dot.selected{
  background-image: url('<?php if(!$this->calc) echo $this->themeCacheUrl.$c['helper']->ColorizeImage(dirname(__FILE__).'/images/dotselected.png', substr($this->env->slider->params->get('selectedtabbg'),0,6), '188DD9'); ?>');
}

<?php
$canvasWidth = $cwidth;
$canvasHeight = $cheight = $cheight-$controllHeight-1;
?>

<?php echo $c['id']; ?> .slinner .slides{
  height: <?php echo $canvasHeight; ?>px;
  width: 200000px;
  position: absolute;
  top: 0;
  left: 0;
}

<?php echo $c['id']; ?> .slinner .sslide{
  width: <?php echo $canvasWidth; ?>px;
  height: <?php echo $canvasHeight; ?>px !important;
  float: left;
  position: relative;
  overflow: hidden;
  padding: 0;
  border: 0;
}

<?php echo $c['id']; ?> .slinner .canvas{
  width: <?php echo $canvasWidth; ?>px;
  height: <?php echo $canvasHeight; ?>px;
  float: left;
  -webkit-border-top-left-radius: 3px;
  -webkit-border-top-right-radius: 3px;
  -moz-border-radius-topleft: 3px;
  -moz-border-radius-topright: 3px;
  border-top-left-radius: 3px;
  border-top-right-radius: 3px;
}

<?php
if(!$this->calc && isset($c['captioncss'])){
  include($c['captioncss']);
}

if(!$this->calc && isset($c['contentcss'])){
  include($c['contentcss']);
}
?>

<?php echo $c['id']; ?> .slinner .canvas .onlybackground{
  -webkit-border-top-left-radius: 3px;
  -webkit-border-top-right-radius: 3px;
  -moz-border-radius-topleft: 3px;
  -moz-border-radius-topright: 3px;
  border-top-left-radius: 3px;
  border-top-right-radius: 3px;
  box-shadow:inset 0px 0px 1px RGBA(255,255,255,0.8);
}