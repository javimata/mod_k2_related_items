<?php
/**
 * @version		1.0.0
 * @package		Module Items related for K2
 * @author		@Javi_Mata http://www.javimata.com
 * @copyright	Copyright (c) 2016. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'utilities.php');

class modK2RelatedHelper
{

	public static function getItems(&$params, $format = 'html')
	{

		$menuParams = JFactory::getApplication()->getMenu()->getActive()->params;
		$categorias = $menuParams->get("categories");
		$itemId     = JRequest::getInt('id');
		$taskView   = JRequest::getVar('task');

		$limit    = $params->get('itemCount', 5);
		// $cid   = $params->get('category_id', NULL);
		$ordering = $params->get('itemsOrdering', '');
		$db       = JFactory::getDBO();

		if (!$itemId){

			$cid = $categorias;
			$tipo_listado = 1; 

		}else{

			if ($taskView == "category"){

				$cid = $itemId;
				$tipo_listado = 2;

			}else{

				$squery = "SELECT catid FROM #__k2_items WHERE id = " . $itemId;
				$db->setQuery($squery);
				$cid = $db->loadResult();
				$tipo_listado = 3;
			}

		}

		/* Tipo de listado
		** 1 - Menu
		** 2 - Categoria
		** 3 - Item (Interna)
		*/

		//echo $cid;

		// Inicia Query
		$query  = "SELECT c.*";
		$query .= " FROM #__k2_items as c";
		$query .= " WHERE c.published = 1";

		if (!is_null($cid))
		{
			if (is_array($cid))
			{
				$count = 0;
				foreach ($cid as $cat) {
					if ( $count >= 1 ) { $sep = " OR "; } else { $sep = " AND "; }
					$query .= $sep ."c.catid = " . $cat;
					$count++;
				}

			}
			else
			{
				$query .= " AND c.catid = " . $cid;
			}
		}


		switch ($ordering)
		{

			case 'date' :
				$orderby = 'c.id ASC';
				break;

			case 'name' :
				$orderby = 'c.name ASC';
				break;


			case 'order' :
				$orderby = 'c.ordering ASC';
				break;

			case 'rand' :
				$orderby = 'RAND()';
				break;

			default :
				$orderby = 'c.id DESC';
				break;
		}

		$query .= " ORDER BY ".$orderby;
		$db->setQuery($query, 0, $limit);
		$items = $db->loadObjectList();

		$model = K2Model::getInstance('Item', 'K2Model');

		if (count($items))
		{

			foreach ($items as $item)
			{
			    $item->event = new stdClass;

				//Clean title
				$item->title = JFilterOutput::ampReplace($item->title);

				//Images
				if ($params->get('itemImgShow'))
				{

					$date = JFactory::getDate($item->modified);
					$timestamp = '?t='.$date->toUnix();

					if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$item->id).'_XS.jpg'))
					{
						$item->imageXSmall = JURI::base(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_XS.jpg';
					}

					if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$item->id).'_S.jpg'))
					{
						$item->imageSmall = JURI::base(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_S.jpg';
					}

					if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$item->id).'_M.jpg'))
					{
						$item->imageMedium = JURI::base(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_M.jpg';
					}

					if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$item->id).'_L.jpg'))
					{
						$item->imageLarge = JURI::base(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_L.jpg';
					}

					if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$item->id).'_XL.jpg'))
					{
						$item->imageXLarge = JURI::base(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_XL.jpg';
					}

					if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$item->id).'_Generic.jpg'))
					{
						$item->imageGeneric = JURI::base(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_Generic.jpg';
					}

					$image = 'image'.$params->get('itemImgSize', 'Small');
					if (isset($item->$image))
						$item->image = $item->$image;

				}

				//Read more link
				$item->link = urldecode(JRoute::_(K2HelperRoute::getItemRoute($item->id.':'.urlencode($item->alias))));


				// Introtext
				$item->text = '';
				if ($params->get('itemIntroText'))
				{
					// Word limit
					$item->text .= $item->description;
				}


                // Restore the intotext variable after plugins execution
                $item->introtext = $item->text;
                $item->tipo_listado = $tipo_listado;


				$rows[] = $item;
			}
			return $rows;

		}

	}

}
