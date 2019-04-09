<?php
/**
 * @package    JUG.Banner
 *
 * @author     HR-IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2019 HR-IT-Solutions GmbH
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Factory;
use Joomla\CMS\Cache\Cache;

/**
 * JUG.Banner Plugin
 *
 * @since  1.0
 */
class PlgAJAXDDJUGbanner extends JPlugin
{
	/**
	 * @var
	 */
	protected $app;

	/**
	 * @var string
	 */
	protected static $hash = 'sha512';

	/**
	 * onAjaxBannerList.
	 *
	 */
	public function onAjaxBannerList()
	{
		$cacheDataId = 'ajaxjugbanner';

		$options = array(
			'defaultgroup'  => $cacheDataId,
			'storage'       => Factory::getConfig()->get('cache_handler', ''),
			'caching'       => true,
			'lifetime'      => 60,
		);

		$cache = Cache::getInstance('', $options);

		if ($cache->get($cacheDataId) !== false)
		{
			$data = $cache->get($cacheDataId);
		}
		else
		{
			$list = $this->getBannersList($this->params);
			$data = $this->generateXML($list);

			$cache->store($data, $cacheDataId);
		}

		echo $data;
	}

	/**
	 * onAjaxBannerHash.
	 *
	 */
	public function onAjaxBannerHash()
	{
		$list = $this->getBannersList($this->params);

		$xml = $this->generateXML($list);

		echo hash(static::$hash, $xml);
	}

	/**
	 * Retrieve list of banners
	 * Adapted from Joomla! mod_banners
	 *
	 * @param   \Joomla\Registry\Registry  &$params  plugin parameters
	 *
	 * @return  mixed
	 */
	protected function getBannersList(&$params)
	{
		BaseDatabaseModel::addIncludePath(JPATH_ROOT . '/components/com_banners/models', 'BannersModel');

		$model = BaseDatabaseModel::getInstance('Banners', 'BannersModel', array('ignore_request' => true));
		$model->setState('filter.category_id', $params->get('catid', array()));

		$banners = $model->getItems();

		return $banners;
	}

	/**
	 * generateXML
	 *
	 * @param array $list  The com_banners getItems array list
	 *
	 * @since  1.0
	 * @return string
	 */
	protected function generateXML($list)
	{

		$xml = new SimpleXmlElement('<banners></banners>');

		if (count($list))
		{
			foreach ($list as $item)
			{
				$link = new Uri($item->clickurl);

				$file = JPATH_ROOT .  '/' . $item->params->get('imageurl');
				$file = Path::check($file);

				if (is_file($file))
				{
					$banner = $xml->addChild('banner');

					$banner->addChild('image', Uri::root() . $item->params->get('imageurl'));
					$banner->addChild('link', $link->toString());

					$file_content = file_get_contents($file);
					$banner->addChild('verify', hash('sha256', hash(static::$hash, $file_content)));
				}
			}
		}

		return $xml->asXML();
	}

}
