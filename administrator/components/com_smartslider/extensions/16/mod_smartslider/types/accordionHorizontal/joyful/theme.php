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
<script type="text/javascript">
var captions = new Array;
</script>
<div id="<?php echo $id; ?>">
  <div class="outer">
    <div class="slinner">
      <dl>
      <?php 
      $x=0;
      foreach($tthis->slides as $slide): 
      $classes = array();
      if($x == 0)
        $classes[] = 'selected';
        
      $class = implode(' ', $classes);
      ?>
        <dt class="<?php echo $class; ?> sslide">
          <span class="slideinner">
            <span class="slidepattern">
              <span class="rotated-90">
                <span class="title">
                  <?php echo $slide->title; ?>
                </span>  
              </span>
              <span class="numbering">
                <?php echo $x+1; ?>
              </span>
              <span class="icon" style="<?php if($slide->icon != '' && $slide->icon != '-1') : ?>background-image:url('<?php echo JURI::root().'images/slidericons/'.$slide->icon; ?>');<?php endif; ?>"></span>
            </span>
          </span>
        </dt>
        <dd class="<?php echo $class; ?> sslide">
          <script type="text/javascript">
            captions[<?php echo $x; ?>] = new Array;
          </script>
          <ul class="vertical">
            <?php $y = 0; foreach($slide->childs AS $child){ ?>
            <li class="subslide">
              <div class="canvas">
                <?php echo $context['helper']->modulePositionReplacer($child->content); ?>
              </div>
              <script type="text/javascript">
                var caption = null;
              </script>
              <div class="caption">
                <?php echo $child->caption; ?>
              </div>
              <script type="text/javascript">
                captions[<?php echo $x; ?>][<?php echo $y; ?>] = caption;
              </script>
            </li>
            <?php ++$y; } ?>
          </ul>
          <div class="arrowdown"></div>
          <div class="arrowup"></div>
        </dd>
      <?php ++$x; endforeach; ?>
      </dl>
    </div>
  </div>
  <div class="shadow"></div>
</div>
<script type="text/javascript">
var <?php echo $id?>captions = sliderDojo.clone(captions);
sliderDojo.addOnLoad(sliderDojo,function(){
  var dojo = this;
  new OfflajnSlider({
    node: dojo.byId('<?php echo $id; ?>'),
    rawcaptions: <?php echo $id?>captions,
    autoplay: <?php echo $tthis->slider->params->get('autoplay', 0); ?>,
    autoplayinterval: <?php echo $tthis->slider->params->get('autoplayinterval', 5000); ?>,
    restartautoplay: <?php echo $tthis->slider->params->get('restartautoplay', 0); ?>,
    maineasing: <?php echo $tthis->slider->params->get('maineasing'); ?>,
    maininterval: <?php echo $tthis->slider->params->get('maininterval'); ?>,
    secondaryinterval: <?php echo $tthis->slider->params->get('secondaryinterval'); ?>,
    secondaryeasing: <?php echo $tthis->slider->params->get('secondaryeasing'); ?>,
    mousescroll: <?php echo $tthis->slider->params->get('mousescroll', 1); ?>,
    showdots: <?php echo $tthis->slider->params->get('showdots', 1); ?>
  });
});
</script>