<?php

/**
 * bowobchat.php,v 3.0 2011/05/17 17:25:17
 * @copyright (C) Jonatan Linares
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgSystemBowobchat extends JPlugin
{
  /**
   * Implementation of onAfterRender()
   */
  function onAfterRender()
  {
    $mainframe =& JFactory::getApplication('site');

    $format = JRequest::getVar('format', 'html');
    
    if($mainframe->isAdmin() || $format != 'html')
    {
      return;
    }

    $body = JResponse::getBody();
    $body = preg_replace('/(<\/body[^>]*>)/i', $this->bowob_chat_code() . '$0', $body, 1);
    JResponse::setBody($body);
  }

  /**
   * Gets BoWoB HTML code for show the chat.
   * @return void.
   */
  function bowob_chat_code()
  {
    include(JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'bowobchat' . DS . 'bowob_functions.php');

    return bowob_code(
      $this->params->get('bowob_app_id', ''),
      $this->params->get('bowob_server_address', '')
    );
  }
}

?>
