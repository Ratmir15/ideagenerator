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
$ordering = ($this->lists['order'] == 'c.name');
?>
<form action="index.php?option=com_smartslider&controller=slide" method="post" name="adminForm">		
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
        <!--				
        <th width="5%" nowrap="nowrap" class="nowrap">						
          <?php echo JText::_( 'Grouped with the previous' ); ?>					
        </th>	
        -->				
        <th nowrap="nowrap" class="title">						
          <?php echo JHTML::_('grid.sort',  'Title', 'a.title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>					
        </th>					
        <th width="20%" nowrap="nowrap">						
          <?php echo JHTML::_('grid.sort',   'Slider', 'c.name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>					
        </th>					
        <th width="8%">
						<?php echo JHTML::_('grid.sort',   'Order', 'a.ordering', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
						<?php //if($ordering) echo JHTML::_('grid.order',  $this->items ); ?>
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
			$main = null;
			for ($i=0, $n=count( $this->items ); $i < $n; $i++) {
				$row = &$this->items[$i];
				if(!$main || $row->groupprev == 0){
          $main = &$row;
          $row->groupprev = 0;
        }
				$link		= JRoute::_( 'index.php?option=com_smartslider&controller=slide&task=edit&id='. $row->id );
				$published		= JHTML::_('grid.published', $row, $i );
				$checked		= JHTML::_('grid.checkedout',   $row, $i );
      				?>				
      <tr class="<?php echo "row$k"; ?>">					
        <td align="center">						
          <?php echo $this->pageNav->getRowOffset($i); ?>					</td>					
        <td align="center">						
          <?php echo $checked; ?>					</td>
        <!--
        <td align="center">						
          <?php echo ($row->groupprev ? JText::_( 'Yes' ) : '');?>
        </td>
        -->							
          
          <td>
          <?php if($row->groupprev): ?>
            <span class="gi" style="color: #c0c0c0; font-weight: bold; margin-right: 5px;">|&mdash;</span>
          <?php endif; ?>					
          <span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit' );?>::<?php if(property_exists($row, 'name')) echo htmlspecialchars($row->name); ?>"> 						
<?php
						if ( JTable::isCheckedOut($this->user->get('id'), $row->checked_out ) ) {
							echo htmlspecialchars($row->title);
						} else {
            							?> 							
            <a href="<?php echo $link; ?>">								
              <?php echo htmlspecialchars($row->title); ?></a>							
<?php
						}
            						?>						
          </span>					</td>					
        <td align="center">						
          <a href="index.php?option=com_smartslider&controller=slider&task=edit&id=<?php echo $row->slider ?>"><?php echo htmlspecialchars($row->slidername);?></a> [<a href="<?php echo JRoute::_( 'index.php?option=com_smartslider&controller=slide&task=add&sliderid='. $row->slider ); ?>"><b><?php echo JText::_('Add new slide'); ?></b></a>]</td>		
				<td class="order">
				  <?php if($ordering) : ?>
					<span><?php echo $this->pageNav->orderUpIcon( $i, ($row->slider == @$this->items[$i-1]->slider), 'orderup', 'Move Up', $ordering); ?></span>
					<span><?php echo $this->pageNav->orderDownIcon( $i, $n, ($row->slider == @$this->items[$i+1]->slider), 'orderdown', 'Move Down', $ordering ); ?></span>
					<?php endif; ?>
					<?php $disabled = 0 ?  '' : 'disabled="disabled"'; ?>
					<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled; ?> class="text_area" style="text-align: center" />
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
  <input type="hidden" name="controller" value="slide" />		
  <input type="hidden" name="task" value="" />		
  <input type="hidden" name="boxchecked" value="0" />		
  <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />		
  <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />		
  <?php echo JHTML::_( 'form.token' ); ?>		
</form>