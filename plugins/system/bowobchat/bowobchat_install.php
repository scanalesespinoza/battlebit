<?php

/**
 * bowobchat_install.php,v 3.0 2011/05/17 17:25:17
 * @copyright (C) Jonatan Linares
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

/**
* Script file of BoWoB Chat Plugin
*/
class plgSystemBowobchatInstallerScript
{
  /**
   * Implementation of install()
   */
  function install($parent)
  {
    $db =& JFactory::getDBO();

    $db->setQuery('DROP TABLE IF EXISTS ' . $db->nameQuote('#__bowob'));
    $db->query();

    $db->setQuery('
      CREATE TABLE ' . $db->nameQuote('#__bowob') . ' (
        id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        auth VARCHAR(50) NOT NULL DEFAULT \'\',
        creation INT(10) UNSIGNED NOT NULL DEFAULT \'0\',
        user_id INT(10) UNSIGNED NOT NULL DEFAULT \'0\',
        user_nick VARCHAR(50) NOT NULL DEFAULT \'\',
        user_name VARCHAR(50) NOT NULL DEFAULT \'\',
        user_avatar VARCHAR(200) NOT NULL DEFAULT \'\',
        user_type INT(10) UNSIGNED NOT NULL DEFAULT \'0\',
        PRIMARY KEY  (id)
      ) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8'
    );
    $db->query();

    echo '<p>' . JText::_('<strong>BoWoB Chat is almost ready</strong>. You must <a href="' . JRoute::_('index.php?option=com_plugins&view=plugins') . '">update your BoWoB Chat settings</a> for it to work') . '</p>';
  }

  /**
   * Implementation of uninstall()
   */
  function uninstall($parent)
  {
    $db =& JFactory::getDBO();

    $db->setQuery('DROP TABLE IF EXISTS ' . $db->nameQuote('#__bowob'));
    $db->query();
  }

  /**
   * Implementation of update()
   */
  function update($parent)
  {
    self::install($parent);
  }
}

?>
