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
  overflow: hidden;
  position: relative;
  padding: 0;
}

<?php 
  $controllWidth = 33;
  if($this->env->slider->params->get('ctrlbar', 1) == 0){
    $controllWidth = -1;
  }
?>

<?php echo $c['id']; ?> .controll{
  position: absolute;
  width: <?php echo $controllWidth; ?>px;
  height: <?php echo $cheight; ?>px;
  left: 0;
  top: 0;
  background: url('<?php echo $c['url']; ?>images/pattern.png') repeat;
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
  <?php if($this->env->slider->params->get('ctrlbar', 1) == 0):?>
  display: none;
  <?php endif; ?>
}

.dj_ie7 <?php echo $c['id']; ?> .controll{
  background-color: #<?php echo substr($color1, 0, 6); ?>;
  border-right: 1px solid #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> .controllbtn{
  box-shadow:inset 0px 0px 1px RGBA(255,255,255,0.4);
  cursor: pointer;
  width: <?php echo $controllWidth; ?>px;
}

<?php echo $c['id']; ?> .controll .up{
  position: absolute;
  left: 0;
  top: 0;
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

.dj_ie7 <?php echo $c['id']; ?> .controll .up{
  border-bottom: 1px solid #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> .controll .up > div{
  background: url('<?php echo $c['url']; ?>images/arrowup.png') no-repeat center top;
  padding: 20px 0 10px 0;
}

<?php echo $c['id']; ?> .controll .down{
  position: absolute;
  left: 0;
  bottom: 0;
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

.dj_ie7 <?php echo $c['id']; ?> .controll .down{
  border-top: 1px solid #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> .controll .down > div{
  background: url('<?php echo $c['url']; ?>images/arrowdown.png') no-repeat center bottom;
  padding: 10px 0 20px 0;
}

<?php echo $c['id']; ?> .controll .down:hover, <?php echo $c['id']; ?> .controll .up:hover{
  background: url('<?php echo $c['url']; ?>images/pattern.png') repeat;
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

.dj_ie7 <?php echo $c['id']; ?> .controll .down:hover, 
.dj_ie7 <?php echo $c['id']; ?> .controll .up:hover{
  background-color: #<?php echo substr($color, 0, 6); ?>;
}

<?php echo $c['id']; ?> .controll .down:active, <?php echo $c['id']; ?> .controll .up:active{
  box-shadow:inset 1px 1px 5px RGBA(0,0,0,0.6);
}

<?php echo $c['id']; ?> .controll .dots{
  width: <?php echo $controllWidth; ?>px;
  position: absolute;
  top: 50%;
}

<?php echo $c['id']; ?> .controll .dots .dot{
  background: url('<?php echo $c['url']; ?>images/dot.png') no-repeat center center;
  height: 13px;
  width: <?php echo $controllWidth; ?>px;
  padding: 3px 0;
  float: left;
  cursor: pointer;
}

<?php echo $c['id']; ?> .controll .dots .dot.selected{
  background-image: url('<?php if(!$this->calc) echo $this->themeCacheUrl.$c['helper']->ColorizeImage(dirname(__FILE__).'/images/dotselected.png', substr($this->env->slider->params->get('selectedtabbg'),0,6), '188DD9'); ?>');
}

<?php
$canvasWidth = $cwidth - $controllWidth - 1;
$canvasHeight = $cheight;
?>

<?php echo $c['id']; ?> .slinner .slides{
  height: 200000px;
  width: <?php echo $canvasWidth; ?>px;
  position: absolute;
  top: 0;
  left: <?php echo $controllWidth+1; ?>px;
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
  box-shadow:inset 0px 0px 1px RGBA(255,255,255,0.8);
}