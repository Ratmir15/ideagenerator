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
<style>
fieldset.adminform .radiobtn{
  float: left;
  clear: none;
  min-width: 0;
  padding: 0 20px 0 5px;
}
</style>
<form action="index.php" method="post" name="adminForm">

		<div class="col col100 width-100">
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Details' ); ?></legend>

        <?php echo $this->defaultparams; ?>
        
			</fieldset>
		</div>	
    <div class="clr"></div>
    <div class="col width-50 fltlft">
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Type settings' ); ?></legend>
        <div id="typesettings">
          
        </div>
			</fieldset>
		</div>
		
		<div class="col width-50 fltlft">
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Theme chooser' ); ?></legend>
        <div id="themechooser">
          
        </div>
			</fieldset>
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Theme manager' ); ?></legend>
        <div id="thememanager">
          
        </div>
			</fieldset>
		</div>
		
		<div class="col width-50 fltlft">
			<fieldset class="adminform">
				<legend><?php echo JText::_( 'Slide generator' ); ?></legend>
				<div id="slidegenerator">
        <?php echo $this->generatorparams; ?>
				  
				  <div id="generatorform">
				    
				  </div>
				  
				  
				  <div id="contents">
				  
				  </div>
				  
          <div id="contentmanager">
				    
				  </div>
				  
				  <div id="additionalfields">
				  
				  </div>
				  
				  <div id="contentaddbuttons">
				  
				  </div>
				
				  <div id="captions">
				  
				  </div>
				  
				  <div id="captionmanager">
				  
				  </div>
				  
				  <div id="additionalcaptions">
				
				  </div>
				  
				  <div id="captionaddbuttons">
				  
				  </div>
				  
		  	</div>
			  </fieldset>
		</div>
		
		<div class="clr"></div>

		<input type="hidden" name="option" value="com_smartslider" />
		<input type="hidden" name="controller" value="slider" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="id" value="<?php echo $this->row->id; ?>" />
		<?php echo JHTML::_( 'form.token' ); ?>
</form>