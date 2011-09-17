<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: showlist.php 6643 2011-06-08 09:10:21Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.model');

class ImageShowModelShowList extends JModel
{
	var $_id = null;
	var $_data = null;

	function __construct()
	{
		parent::__construct();

		$array  = JRequest::getVar('cid', array(0), '', 'array');
		$edit	= JRequest::getVar('edit',true);
		if($edit)
		{
			$this->setId((int)$array[0]);
		}
	}

	function setId($id)
	{
		$this->_id		= $id;
		$this->_data	= null;
	}

	function getData()
	{

		if ($this->_loadData()){
			return $this->_data;
		}else{
			return $this->_initData();
		}
	}

	function store($data)
	{
		$row = $this->getTable();

		if (!$row->bind($data)){
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store()){
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		$this->setId($row->showlist_id);
		$row->reorder();
		return true;
	}

	function _loadData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$query = 'SELECT * FROM #__imageshow_showlist WHERE showlist_id = '.(int) $this->_id;

			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();
			$result = (boolean) $this->_data;

			if($result)
			{
				$this->_data->article_title 		= @$this->getArticleTitleByID($this->_data->alter_id);
				$this->_data->aut_article_title 	= @$this->getArticleTitleByID($this->_data->alter_autid);
				$this->_data->alter_module_title 	= @$this->getModuleTitleByID($this->_data->alter_module_id);
				$this->_data->seo_module_title		= @$this->getModuleTitleByID($this->_data->seo_module_id);
				$this->_data->seo_article_title		= @$this->getArticleTitleByID($this->_data->seo_article_id);
				return (boolean) $this->_data;
			}
			else
			{
				return $result;
			}
		}
		//return true;
	}

	function _initData()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$showlist 					= new stdClass();
			$showlist->showlist_id 		= 0;
			$showlist->showlist_title	= null ;
			$showlist->published 		= 0 ;
			$showlist->ordering 		= 0 ;
			$showlist->access			= 0 ;
			$showlist->hits 			= 0 ;
			$showlist->description 		= null ;
			$showlist->showlist_link 	= null ;
			$showlist->alter_id 		= 0 ;
			$showlist->alter_autid 		= 0 ;
			$showlist->alter_module_id  = 0;
			$showlist->alter_image_path = null;
			$showlist->seo_module_id   	= 0;
			$showlist->seo_article_id	= 0;
			$showlist->date_create 		= null ;
			$showlist->date_modified 		= null ;
			$showlist->showlist_source 		= null ;
			$showlist->configuration_id 	= 0;
			$showlist->alternative_status 	= 0;
			$showlist->seo_status 			= 0;
			$showlist->authorization_status	= 0;
			$showlist->override_title 		= 0;
			$showlist->override_description = 0;
			$showlist->override_link 		= 0;

			$this->_data = $showlist;
			return $this->_data;
		}
		return true;
	}

	function accesslevel( &$row )
	{
		$db =& JFactory::getDBO();

		$query = 'SELECT id AS value, name AS text'
		. ' FROM #__groups'
		. ' ORDER BY id'
		;
		$db->setQuery( $query );
		$groups = $db->loadObjectList();
		$access = JHTML::_('select.genericlist',   $groups, 'access', 'class="inputbox"', 'value', 'text', $row->access, '', 1 );

		return $access;
	}

function getModuleTitleByID($id)
	{
		$query = 'SELECT title FROM #__modules WHERE id = '.(int)$id;
		$this->_db->setQuery($query);
		$result = @$this->_db->loadObject();
		return $result->title;
	}

	function getArticleTitleByID($id)
	{
		$query = 'SELECT title FROM #__content WHERE id = '.(int)$id;
		$this->_db->setQuery($query);
		$result = @$this->_db->loadObject();
		return $result->title;
	}
		}
?>