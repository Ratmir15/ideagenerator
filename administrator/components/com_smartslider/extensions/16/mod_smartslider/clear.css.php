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
?>

div<?php echo $c['id']; ?> div,
div<?php echo $c['id']; ?> dl,
div<?php echo $c['id']; ?> dd,
div<?php echo $c['id']; ?> ul.slides,
div<?php echo $c['id']; ?> li.sslide,
div<?php echo $c['id']; ?> li.slide,
div<?php echo $c['id']; ?> ul.vertical,
div<?php echo $c['id']; ?> li.subslide,
div<?php echo $c['id']; ?> h1,
div<?php echo $c['id']; ?> h2,
div<?php echo $c['id']; ?> h3,
div<?php echo $c['id']; ?> h4,
div<?php echo $c['id']; ?> h5,
div<?php echo $c['id']; ?> h6,
div<?php echo $c['id']; ?> p{
  padding: 0;
  margin: 0;
  border: 0;
  list-style-type: none;
  text-align: left;
  background-color: transparent;
  position: static;
  text-transform: none;
}


div<?php echo $c['id']; ?> div div .sslide a{
  padding: 0;
  margin: 0;
  border: 0;
  background-color: transparent;
}

<?php echo $c['id']; ?> dt.sslide, 
<?php echo $c['id']; ?> dd.sslide, 
<?php echo $c['id']; ?> li.subslide{
  clear: none;
  border: 0;
}