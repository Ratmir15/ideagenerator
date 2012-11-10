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
?>

<?php echo $c['id']; ?> .slinner{
  width: <?php echo $cwidth; ?>px;
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

<?php echo $c['id']; ?> .slinner .sslide{
  position: absolute;
  top: 50%;
  margin-top: -<?php echo $cheight/2; ?>px;
  left: 50%;
  margin-left: -<?php echo $cwidth/2; ?>px;
  width: <?php echo $cwidth+6; ?>px;
  height: <?php echo $cheight+6; ?>px !important;
  z-index: 1;
  padding: 3px;
  -webkit-backface-visibility: hidden;
  /*-webkit-transform-style: preserve-3d;*/
}

<?php echo $c['id']; ?> .slinner .sslide.selected{
  z-index: 2;
}

<?php
  $borderSlide = array(1, 1, 1, 1);
  $paddingSlide = array(10, 10, 10, 10);
  $cwidth = $cwidth-$paddingSlide[1]-$paddingSlide[3]-$borderSlide[1]-$borderSlide[3];
  $cheight = $cheight-$paddingSlide[0]-$paddingSlide[2]-$borderSlide[0]-$borderSlide[2];
?>

<?php echo $c['id']; ?> .slinner .slideouter{
  border: #c1c1c1 solid 1px;
  border-width: <?php echo implode('px ', $borderSlide); ?>px;
  background-color: #ffffff;
  padding: <?php echo implode('px ', $paddingSlide); ?>px;
  width: <?php echo $cwidth; ?>px;
  height: <?php echo $cheight; ?>px;
  z-index: 1;
  -moz-box-shadow: 0px 0px 3px RGBA(0,0,0,0.5);
  -webkit-box-shadow: 0px 0px 3px RGBA(0,0,0,0.5);
  box-shadow: 0px 0px 3px RGBA(0,0,0,0.5);
}

<?php
  $borderSlideI = array(1, 1, 1, 1);
  $cwidth = $cwidth-$borderSlideI[1]-$borderSlideI[3];
  $cheight = $cheight-$borderSlideI[0]-$borderSlideI[2];
?>

<?php echo $c['id']; ?> .slinner .sslide .slideinner{
  border: #c1c1c1 solid 1px;
  border-width: <?php echo implode('px ', $borderSlideI); ?>px;
  background-color: #ffffff;
  width: <?php echo $cwidth; ?>px;
  height: <?php echo $cheight; ?>px;
}

<?php echo $c['id']; ?> .slinner .sslide .slideinner2{
  width: <?php echo $cwidth; ?>px;
  height: <?php echo $cheight; ?>px;
  position: relative;
  overflow: hidden;
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