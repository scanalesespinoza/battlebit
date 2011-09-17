<?php

/**
 * bowob_functions.php,v 3.0 2011/05/17 17:25:17
 * @copyright (C) Jonatan Linares
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/

defined('_JEXEC') or die('Restricted access');

/**
 * Prints synchronization data to be readed by BoWoB server.
 * @return void.
 */
function bowob_server_sync()
{
  header('Cache-Control: no-cache, must-revalidate');
  header('Content-Type: text/plain; charset=utf-8');

  include(dirname(realpath(__FILE__)) . DS . 'bowob_api.php');

  echo bowob_api_get_sync_data(
    JRequest::getInt('id', '-1', 'GET'),
    JRequest::getVar('sync', '', 'GET', 'STRING'),
    JRequest::getVar('name', '', 'GET', 'STRING') == '1',
    JRequest::getVar('avatar', '', 'GET', 'STRING') == '1',
    JRequest::getVar('friends', '', 'GET', 'STRING') == '1'
  );
}

/**
 * Creates a synchronization record and prints record identifiers to be readed by BoWoB client.
 * @return void.
 */
function bowob_client_sync()
{
  header('Cache-Control: no-cache, must-revalidate');
  header('Content-Type: text/plain');

  include(dirname(realpath(__FILE__)) . DS . 'bowob_api.php');

  echo bowob_api_new_sync(
    JRequest::getVar('nick', '', 'GET', 'STRING'),
    JRequest::getVar('name', '', 'GET', 'STRING') == '1',
    JRequest::getVar('avatar', '', 'GET', 'STRING') == '1'
  );
}

/**
 * Redirects to login page.
 * @return void.
 */
function bowob_redirect_login()
{
  $mainframe =& JFactory::getApplication('site');

  $mainframe->redirect(JRoute::_('index.php?option=com_users&view=login'));
  exit();
}

/**
 * Redirects to user profile page.
 * @return void.
 */
function bowob_redirect_profile()
{
  $mainframe =& JFactory::getApplication('site');
  $userid = JRequest::getInt('id', '-1', 'GET');

  if($userid > 0)
  {
    bowob_jomsocial_load();
    if(defined('BOWOB_JOMSOCIAL'))
    {
      CFactory::load('helpers', 'url');
      if(class_exists('CUrlHelper'))
      {
        $mainframe->redirect(CUrlHelper::userLink($userid, true));
        exit();
      }
    }
  }
  
  $mainframe->redirect(JURI::base());
  exit();
}

/**
 * Gets BoWoB HTML code for show the chat.
 * @return string The HTML code.
 */
function bowob_code($app_id, $server_address)
{
  include(dirname(realpath(__FILE__)) . DS . 'bowob_api.php');

  return bowob_api_get_code($app_id, $server_address);
}

/**
 * Checks if current user is logued.
 * @return boolean User is logued.
 */
function bowob_is_user_logued()
{
  $user =& JFactory::getUser();

  return !($user->guest);
}

/**
 * Gets current user id.
 * @return int User id.
 */
function bowob_get_user_id()
{
  $user =& JFactory::getUser();

  return $user->id;
}

/**
 * Gets current user nick.
 * @return string User nick.
 */
function bowob_get_user_nick()
{
  $user =& JFactory::getUser();

  if($user->guest)
  {
    return '';
  }
  else
  {
    return $user->username;
  }
}

/**
 * Gets current user name.
 * @return string User name.
 */
function bowob_get_user_name()
{
  $user =& JFactory::getUser();

  if(!$user->guest)
  {
    bowob_jomsocial_load();
    if(defined('BOWOB_JOMSOCIAL'))
    {
      $js_user =& CFactory::getUser();

      $visiblename = $js_user->getDisplayName();
    }
    else
    {
      $visiblename = $user->name;
    }
  }
  else
  {
    $visiblename = '';
  }

  if($visiblename == $user->username)
  {
    return '';
  }
  else
  {
    return $visiblename;
  }
}

/**
 * Gets current user avatar url.
 * @return string User avatar.
 */
function bowob_get_user_avatar()
{
  $user =& JFactory::getUser();

  if(!$user->guest)
  {
    bowob_jomsocial_load();
    if(defined('BOWOB_JOMSOCIAL'))
    {
      $js_user =& CFactory::getUser();

      return $js_user->getThumbAvatar();
    }
  }

  return '';
}

/**
 * Gets current user friends.
 * @param int $id User id.
 * @param string $separator Separator between nicks.
 * @return string User friends.
 */
function bowob_get_user_friends($id, $separator)
{
  if($id <= 0)
  {
    return '';
  }

  $output = '';

  bowob_jomsocial_load();
  if(defined('BOWOB_JOMSOCIAL'))
  {
    $js_friends =& CFactory::getModel('friends');
    $friends_id = $js_friends->getFriendIds($id);

    foreach($friends_id as $friend_id)
    {
      $friend =& JFactory::getUser($friend_id);

      if($friend)
      {
        $output .= $friend->username . $separator;
      }
    }
  }

  return $output;
}

/**
 * Stores a synchronization record in database.
 * @param string $auth Record auth.
 * @param int $creation Record creation time.
 * @param int $user_id Record user id.
 * @param string $user_nick Record user nick.
 * @param string $user_name Record user name.
 * @param string $user_avatar Record user avatar.
 * @param int $user_type Record user type.
 * @return int Record id.
 */
function bowob_store_sync($auth, $creation, $user_id, $user_nick, $user_name, $user_avatar, $user_type)
{
  $db = JFactory::getDbo();
  $query = $db->getQuery(true);

  $query->insert('#__bowob');
  $query->set('auth = ' . $db->quote($auth));
  $query->set('creation = ' . (int)$creation);
  $query->set('user_id = ' . (int)$user_id);
  $query->set('user_nick = ' . $db->quote($user_nick));
  $query->set('user_name = ' . $db->quote($user_name));
  $query->set('user_avatar = ' . $db->quote($user_avatar));
  $query->set('user_type = ' . (int)$user_type);
  $db->setQuery((string)$query);

  if(!$db->query())
  {
    return 0;
  }

  return $db->insertid();
}

/**
 * Extracts a synchronization record from database.
 * @param int $id Record id.
 * @param string $auth Record auth.
 * @param int $expiration Record expiration time.
 * @return array Record values.
 */
function bowob_extract_sync($id, $auth, $expiration)
{
  $db = JFactory::getDbo();
  $query = $db->getQuery(true);

  $query->delete();
  $query->from('#__bowob');
  $query->where('creation < ' . (int)$expiration);
  $db->setQuery((string)$query);
  $db->query();

  $query->clear();
  $query->select('auth, user_id, user_nick, user_name, user_avatar, user_type');
  $query->from('#__bowob');
  $query->where('id = ' . (int)$id);
  $db->setQuery((string)$query);
  $rs = $db->loadObject();

  if(!$rs || $rs->auth != $auth)
  {
    return array();
  }
  else
  {
    $query->clear();
    $query->delete();
    $query->from('#__bowob');
    $query->where('id = ' . (int)$id);
    $db->setQuery((string)$query);
    $db->query();

    return array(
      'user_id' => $rs->user_id,
      'user_nick' => $rs->user_nick,
      'user_name' => $rs->user_name,
      'user_avatar' => $rs->user_avatar,
      'user_type' => $rs->user_type,
    );
  }
}

function bowob_jomsocial_load()
{
  if(!defined('BOWOB_JOMSOCIAL_LOAD'))
  {
    $path = JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php';

    if(file_exists($path))
    {
      include($path);

      define('BOWOB_JOMSOCIAL', 1);
    }

    define('BOWOB_JOMSOCIAL_LOAD', 1);
  }
}

?>
