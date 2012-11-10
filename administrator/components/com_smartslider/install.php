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

jimport('joomla.installer.helper');

function com_install(){
	$installer = new Installer();	
	echo "<H3>Installing Offlajn Smart Slider component and module Success</h3>"; 
	if(version_compare(JVERSION,'1.7.0','ge')) {
    $installer->vers = 16;
	} elseif(version_compare(JVERSION,'1.6.0','ge')) {
    $installer->vers = 16;
	} else {
    $installer->vers = 15;
	}
	$installer->install();
	return true;

}
function com_uninstall(){
	$installer = new Installer();	
	$installer->uninstall();
/*  if(version_compare(JVERSION,'1.6.0','ge')) {
    $db =& JFactory::getDBO();
    $query = "
      DELETE FROM #__menu WHERE title = 'COM_SMARTSLIDER_MENU';
      ";
    $db->setQuery($query);
    $db->query();
	} */
	return true;
}

class Installer extends JObject {

	function install() {
		if (!$this->executeSQL('install')) {
			return;
		}

    $installer = new JInstaller();
    $installer->setOverwrite(true);

    $pkg_path = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_smartslider'.DS.'extensions'.DS.$this->vers.DS;
    $pkgs = array( 
      'mod_smartslider'=>'Smart Slider - module',
      'plg_smartsliderinsert'=>'Smart Insert - plugin',
      'plg_smartslidertabber'=>'Smart Tabber - plugin'
    );
             
    foreach( $pkgs as $pkg => $pkgname ):
      if( $installer->install( $pkg_path.DS.$pkg ) )
      {
        $msgcolor = "#E0FFE0";
        $msgtext  = "$pkgname successfully installed.";
      }
      else
      {
        $msgcolor = "#FFD0D0";
        $msgtext  = "ERROR: Could not install the $pkgname. Please contact us on our support page: http://offlajn.com/support.html";
      }
      ?>
      <table bgcolor="<?php echo $msgcolor; ?>" width ="100%">
        <tr style="height:30px">
          <td width="50px"><img src="/administrator/images/tick.png" height="20px" width="20px"></td>
          <td><font size="2"><b><?php echo $msgtext; ?></b></font></td>
        </tr>
      </table>
    <?php
    endforeach;
	}

	function uninstall() {
  }

	function executeSQL($_sqlf){

		jimport('joomla.installer.helper');
		$_db = JFactory::getDBO();

		$_sqlf2 = (JPATH_ADMINISTRATOR. DS .'components'.DS.'com_smartslider'.DS.$_sqlf.'.sql');

    if(!$this->installSQL($_sqlf2)){
      return false;
    }

		if(version_compare(JVERSION,'1.7.0','ge')) {
			// Joomla! 1.7 code here
			$_sqlf .= '16';
		} elseif(version_compare(JVERSION,'1.6.0','ge')) {
			// Joomla! 1.6 code here
			$_sqlf .= '16';
		} else {
			$_sqlf .= '15';
		}

		$_sqlf = (JPATH_ADMINISTRATOR. DS .'components'.DS.'com_smartslider'.DS.$_sqlf.'.sql');

    if(!$this->installSQL($_sqlf)){
      return false;
    }
    
		return true;
	}
	
	function installSQL($_sqlf){
    if ( !file_exists($_sqlf) ) {
			JError::raiseWarning(500, 'SQL file ' . $_sqlf . ' not found');
			return false;
		}
    $_db = JFactory::getDBO();
		$_qr = JInstallerHelper::splitSql(file_get_contents($_sqlf));

		foreach ($_qr as $_q) {
			$_q = trim($_q);
			if ($_q != '' && $_q{0} != '#') {
				$_db->setQuery($_q);
				if (!$_db->query()) {
					JError::raiseWarning(500, 'JInstaller::install: '.JText::_('SQL Error')." ".$_db->stderr(true));
					return false;
				}
			}
		}
		return true;
  }
}