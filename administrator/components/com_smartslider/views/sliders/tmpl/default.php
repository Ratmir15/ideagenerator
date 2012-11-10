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
<form action="index.php?option=com_smartslider&controller=slider" method="post" name="adminForm">		
  <table>		
    <tr>			
      <td align="left" width="100%">				
        <?php echo JText::_( 'Filter' ); ?>: 				
        <input type="text" name="search" id="search" value="<?php if(isset($this->lists['search'])) echo htmlspecialchars($this->lists['search']);?>" class="text_area" onchange="document.adminForm.submit();" />				
        <button onclick="this.form.submit();">
          <?php echo JText::_( 'Go' ); ?>
        </button>				
        <button onclick="document.getElementById('search').value='';this.form.getElementById('filter_catid').value='0';this.form.getElementById('filter_state').value='';this.form.submit();">
          <?php echo JText::_( 'Filter Reset' ); ?>
        </button>			</td>			
      <td nowrap="nowrap">				
<?php
				if(isset($this->lists['state'])) echo $this->lists['state'];
        				?>			</td>		
    </tr>		
  </table>			
  <table class="adminlist">			
    <thead>				
      <tr>					
        <th width="20">						
          <?php echo JText::_( 'Num' ); ?>					
        </th>					
        <th width="20">						
          <input type="checkbox" name="toggle" value=""  onclick="checkAll(<?php echo count( $this->items ); ?>);" />					
        </th>					
        <th nowrap="nowrap" class="title">						
          <?php echo JHTML::_('grid.sort',  'Slider name', 'a.name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>					
        </th>
        <th width="18%" nowrap="nowrap">						
          <?php echo JText::_( 'Slide count' ); ?>					
        </th>
        <th width="13%" nowrap="nowrap">						
          <?php echo JText::_( 'Create module' ); ?>					
        </th>							
        <th width="10%" nowrap="nowrap">						
          <?php echo JHTML::_('grid.sort',   'Created by', 'a.created_by', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>					
        </th>					
        <th width="5%" nowrap="nowrap">						
          <?php echo JHTML::_('grid.sort',   'Published', 'a.published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>					
        </th>							
        <th width="1%" nowrap="nowrap">						
          <?php echo JHTML::_('grid.sort',   'ID', 'a.id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>					
        </th>				
      </tr>			
    </thead>			
    <tfoot>				
      <tr>					
        <td colspan="13">						
          <?php echo $this->pageNav->getListFooter(); ?>					</td>				
      </tr>			
    </tfoot>			
    <tbody>			
<?php
			$k = 0;
			for ($i=0, $n=count( $this->items ); $i < $n; $i++) {
				$row = &$this->items[$i];
				$link		= JRoute::_( 'index.php?option=com_smartslider&controller=slider&task=edit&id='. $row->id );
				$published		= JHTML::_('grid.published', $row, $i );
				$checked		= JHTML::_('grid.checkedout',   $row, $i );
      				?>				
      <tr class="<?php echo "row$k"; ?>">					
        <td align="center">						
          <?php echo $this->pageNav->getRowOffset($i); ?>					</td>					
        <td align="center">						
          <?php echo $checked; ?>					</td>					<td>					
          <span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit' );?>::<?php echo htmlspecialchars($row->name); ?>"> 						
<?php
						if ( JTable::isCheckedOut($this->user->get('id'), $row->checked_out ) ) {
							echo htmlspecialchars($row->name);
						} else {
            							?> 							
            <a href="<?php echo $link; ?>">								
              <?php echo htmlspecialchars($row->name); ?></a>							
<?php
						}
            						?>						
          </span>					</td>
        <td align="center">						
          <?php echo $row->count;?> 
          [<a href="<?php echo JRoute::_( 'index.php?option=com_smartslider&controller=slide&task=add&sliderid='. $row->id ); ?>"><b><?php echo JText::_('Add new slide'); ?></b></a>]
          [<a href="<?php echo JRoute::_( 'index.php?option=com_smartslider&controller=slide&filter_slider='. $row->id ); ?>"><b><?php echo JText::_('Show slides'); ?></b></a>]
        </td>					
        <td align="center">		
        <?php if(version_compare(JVERSION,'1.6.0','ge')) : ?>
          [<a href="<?php echo JRoute::_( 'index.php?option=com_modules&task=module.add&eid='. $this->module_id ); ?>"><b><?php echo JText::_('Create module from this slider'); ?></b></a>]
        <?php else : ?>				
          [<a href="<?php echo JRoute::_( 'index.php?option=com_modules&task=edit&module=mod_smartslider&created=1&client=0&slider='. $row->id ); ?>"><b><?php echo JText::_('Create module from this slider'); ?></b></a>]
        <?php endif; ?>
        </td>		
        <td align="center">						
          <?php echo htmlspecialchars($row->uname);?></td>		
        <td align="center">						
          <?php echo $published;?>					</td>									
        <td align="center">						
          <?php echo $row->id; ?>					</td>				
      </tr>				
<?php
				$k = 1 - $k;
			}
      			?>			
    </tbody>			
  </table>		
  <input type="hidden" name="option" value="com_smartslider" />	
	<input type="hidden" name="controller" value="slider" />	
  <input type="hidden" name="task" value="" />		
  <input type="hidden" name="boxchecked" value="0" />		
  <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />		
  <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />		
  <?php echo JHTML::_( 'form.token' ); ?>		
</form>