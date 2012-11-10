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

<?php
  $cwidth = $sp->get('width');
  $cheight = $sp->get('height');
?>

<?php echo $c['id']; ?>{
  position: relative;
  width: <?php echo $cwidth; ?>px;
  margin: 0 auto;
}

<?php echo $c['id']; ?> .controllLeft, <?php echo $c['id']; ?> .controllRight{
  position: absolute;
  left: 0;
  top: 0;
  width: <?php echo round($cwidth*0.1); ?>px;
  height: <?php echo $cheight; ?>px;
  z-index: 1;
  background: url('<?php echo $c['url']; ?>images/arrowleft.png') no-repeat center center;
  cursor: pointer;
  <?php if($this->env->slider->params->get('leftrightcontrol', 1) == 0): ?>
  display: none;
  <?php endif; ?>
}

<?php echo $c['id']; ?> .controllLeft:HOVER{
  background-image: url('<?php echo $c['url']; ?>images/arrowleft-hover.png');
}

<?php echo $c['id']; ?> .controllRight{
  left: auto;
  right: 0;
  background: url('<?php echo $c['url']; ?>images/arrowright.png') no-repeat center center;
}

<?php echo $c['id']; ?> .controllRight:HOVER{
  background-image: url('<?php echo $c['url']; ?>images/arrowright-hover.png');
}

<?php
  $cwidthOld = $cwidth;
  $cheightOld = $cheight;
  if($this->env->slider->params->get('leftrightcontrol', 1) == 1){
    $cwidth=round($cwidth*0.8);
  }
  $cheight=round($cheight*0.8);
  $shadowW = $cwidth+10;
?>

<?php $shadow = ($this->env->slider->params->get('shadow') != '-1' && $this->env->slider->params->get('shadow') != '' && is_file(dirname(__FILE__).'/../../../images/shadows/'.$this->env->slider->params->get('shadow'))); ?>
<?php if($shadow): ?>
  <?php echo $c['id']; ?> .shadow{
    width: <?php echo $shadowW; ?>px;
    background: url('<?php if(!$this->calc) echo $this->themeCacheUrl.$c['helper']->ResizeImage(dirname(__FILE__).'/../../../images/shadows/'.$this->env->slider->params->get('shadow'), $shadowW, 0); ?>') no-repeat;
    height: <?php echo isset($GLOBALS['height'])?$GLOBALS['height']:0; ?>px;
    margin: 0 auto;
    margin-top: -<?php echo round(($cheightOld-$cheight)/2)-6; ?>px;
  }
<?php else: ?>
  <?php echo $c['id']; ?> .shadow{
    display: none;
  }
<?php endif;?>

<?php echo $c['id']; ?> .slinner{
  width: <?php echo $cwidth+6; ?>px;
  height: <?php echo $cheightOld; ?>px;
  position: relative;
  margin: 0 auto;
}

<?php echo $c['id']; ?> .controll{
  height: 17px;
  width: 100%;
  margin: 0 auto;
  <?php if($this->env->slider->params->get('dotcontrol', 1) == 0): ?>
  display: none;
  <?php endif; ?>
  z-index: 5;
  position: relative;
}

<?php echo $c['id']; ?> .controll div{
  height: 17px;
  width: 56px;
  background: url('<?php echo $c['url']; ?>images/dot.png') no-repeat -2px 0;
  float: left;
  cursor: pointer;
}

<?php echo $c['id']; ?> .controll div.first{
  width: 58px;
  background-position: 0 0;
}

<?php echo $c['id']; ?> .controll div.selected{
  background-image: url('<?php echo $c['url']; ?>images/dotselected.png');
}

<?php echo $c['id']; ?> .controll div.last{
  background: url('<?php echo $c['url']; ?>images/dotend.png') no-repeat -2px 0;
  width: 43px;
}

<?php echo $c['id']; ?> .slinner .frame{
  position: absolute;
  top: <?php echo ($cheight*1.25-$cheight)/2; ?>px;
  left: 0px;
  
  width: <?php echo $cwidth+6; ?>px;
  height: <?php echo $cheight+6; ?>px;
  z-index: 1;
  -webkit-backface-visibility: hidden;
  -webkit-transform-style: preserve-3d;
  padding: 3px;
}

<?php echo $c['id']; ?> .slinner .frame1{
  margin-left: 5px;
}

<?php echo $c['id']; ?> .slinner .frame2{
  margin-left: -5px;
}

<?php echo $c['id']; ?> .slinner .mainframe{
  position: absolute;
  top: 50%;
  margin-top: -<?php echo $cheight/2; ?>px;
  left: 50%;
  margin-left: -<?php echo ($cwidth+6)/2; ?>px;
  width: <?php echo $cwidth+6; ?>px;
  height: <?php echo $cheight+6; ?>px;
  z-index: 1;
  padding: 3px;
  overflow: hidden;
}

<?php
  $borderSlide = array(1, 1, 1, 1);
  $paddingSlide = array(10, 10, 10, 10);
  $cwidth = $cwidth-$paddingSlide[1]-$paddingSlide[3]-$borderSlide[1]-$borderSlide[3];
  $cheight = $cheight-$paddingSlide[0]-$paddingSlide[2]-$borderSlide[0]-$borderSlide[2];
?>

<?php echo $c['id']; ?> .slinner .mainframeinner,
<?php echo $c['id']; ?> .slinner .frame div{
  border: #c1c1c1 solid 1px;
  border-width: <?php echo implode('px ', $borderSlide); ?>px;
  background-color: #ffffff;
  padding: <?php echo implode('px ', $paddingSlide); ?>px;
  width: <?php echo $cwidth; ?>px;
  height: <?php echo $cheight; ?>px;
  z-index: 1;
}

<?php
  $borderSlideI = array(1, 1, 1, 1);
  $cwidth = $cwidth-$borderSlideI[1]-$borderSlideI[3];
  $cheight = $cheight-$borderSlideI[0]-$borderSlideI[2];
?>

<?php echo $c['id']; ?> .slinner .mainframeinner2{
  border: #c1c1c1 solid 1px;
  border-width: <?php echo implode('px ', $borderSlideI); ?>px;
  background-color: #ffffff;
  width: <?php echo $cwidth; ?>px;
  height: <?php echo $cheight; ?>px;
  overflow: hidden;
  position:relative
}

<?php echo $c['id']; ?> .slinner .mainframepipe{
  width: <?php echo $count*$cwidth; ?>px;
  height: <?php echo $cheight; ?>px;
  margin-left: 0;
  position: absolute;
  top:0;
  left: 0;
}

<?php echo $c['id']; ?> .slinner .sslide{
  width: <?php echo $cwidth; ?>px;
  height: <?php echo $cheight; ?>px !important;
  position: relative;
  float: left;
}

<?php
$canvasHeight = $cheight;
$canvasWidth = $cwidth;
?>

<?php echo $c['id']; ?> .sslide .canvas{
  height: <?php echo $canvasHeight; ?>px;
  width: <?php echo $canvasWidth; ?>px;
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