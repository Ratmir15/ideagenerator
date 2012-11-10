-- Joomla Module --
UPDATE #__components 
  SET name = 'Smart Slider', admin_menu_alt='Smart Slider'
  WHERE name LIKE 'COM_SMARTSLIDER_MENU';
  
  
UPDATE `#__plugins` SET published=1 WHERE element='smartsliderinsert'