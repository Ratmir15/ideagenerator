<?php 
/*------------------------------------------------------------------------
# mod_vm_accordion - Accordion Menu for Virtuemart 
# ------------------------------------------------------------------------
# author    Roland Soos 
# copyright Copyright (C) 2011 Offlajn.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.offlajn.com
-------------------------------------------------------------------------*/
?>
<?php
// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

class fixedVirtueMartModelCategory extends VirtueMartModelCategory{
    function GetTreeCat($id=0,$maxLevel = 1000) {
        self::treeCat($id ,$maxLevel) ;
        return $this->container ;
    }
 
    function treeCat($id=0,$maxLevel =1000) {
        static $level = 0;
        static $num = -1 ;
        $db = & JFactory::getDBO();
        $q = 'SELECT `category_child_id`,`category_name` FROM `#__virtuemart_categories_'.VMLANG.'`
        LEFT JOIN `#__virtuemart_category_categories` on `#__virtuemart_categories_'.VMLANG.'`.`virtuemart_category_id`=`#__virtuemart_category_categories`.`category_child_id`
        WHERE `category_parent_id`='.(int)$id;
        $db->setQuery($q);
        $num ++;
        // if it is a leaf (no data underneath it) then return
        $childs = $db->loadObjectList();
        if ($level==$maxLevel) return;
        if ($childs) {
            $level++;
            foreach ($childs as $child) {
                $this->container[$num]->id = $child->category_child_id;
                $this->container[$num]->name = $child->category_name;
                $this->container[$num]->level = $level;
                self::treeCat($child->category_child_id,$maxLevel );
            }
            $level--;
        }
    }
}
?>