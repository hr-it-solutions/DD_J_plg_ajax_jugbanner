<?php
/**
 * @package    JUG.Banner
 *
 * @author     HR-IT-Solutions Florian Häusler <info@hr-it-solutions.com>
 * @copyright  Copyright (C) 2019 HR-IT-Solutions GmbH
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

defined('_JEXEC') or die;

use Joomla\CMS\Http\HttpFactory;

JFormHelper::loadFieldClass('spacer');

class JFormFieldAJAXurl extends JFormFieldSpacer {

	protected $type = 'AJAXurl';

	protected $componentURL = 'index.php?option=com_ajax';

	public function getInput()
	{
		return parent::getInput() . $this->getPluginInfos();
	}

	protected function getHash()
	{
		$response = HttpFactory::getHttp()->get(JUri::root() .
			$this->componentURL . '&format=raw&plugin=BannerHash&group=ajax');

		if($response->code !== 200){
			return JText::_('PLG_AJAX_DD_JUGBANNER_HASH_URL_FAILED') . $response->code;
		}

		$hash = strip_tags($response->body);

		return $hash;
	}

	protected function getPluginInfos()
	{
		$URL = $this->componentURL . '&format=xml&plugin=BannerList&group=ajax';
		$html = '<b>' . JText::_('PLG_AJAX_DD_JUGBANNER_XML_URL') . '</b><br><a title="' .
			JText::_('PLG_AJAX_DD_JUGBANNER_BANNERLIST') . ' XML" href="' .
			JURI::root() . $URL . '" target="_blank">' .
			$URL . '</a><br>';

		$URL = $this->componentURL . '&format=raw&plugin=BannerHash&group=ajax';
		$html .= '<br><b>' . JText::_('PLG_AJAX_DD_JUGBANNER_HASH_URL') . '</b><br><a title="' .
			JText::_('PLG_AJAX_DD_JUGBANNER_BANNERHASH') . ' XML" href="' .
			JURI::root() . $URL . '" target="_blank">' .
			$URL . '</a><br>';

		$html .= '<br><b>' . JText::_('PLG_AJAX_DD_JUGBANNER_HASH') . '</b><br>';
		$html .= '<div class="well">' . $this->getHash() . '</div>';

		return $html;
	}
}
