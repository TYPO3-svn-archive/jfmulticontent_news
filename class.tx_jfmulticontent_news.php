<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Juergen Furrer <juergen.furrer@gmail.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/


/**
 * News for 'jfmulticontent'.
 *
 * @author     Juergen Furrer <juergen.furrer@gmail.com>
 * @package    TYPO3
 * @subpackage tx_jfmulticontent_news
 */
class tx_jfmulticontent_news extends tslib_pibase
{
	/**
	 * Identifier for your view (unique)
	 * @var string
	 */
	private $identifier = "news";

	/**
	 * Configuration from parent
	 * @var array
	 */
	public $conf = null;

	/**
	 * cObject from parent
	 * @var object
	 */
	public $cObj = null;

	/**
	 * Titles to use
	 * @var array
	 */
	private $titles = array();

	/**
	 * Contents to use
	 * @var array
	 */
	private $elements = array();

	/**
	 * ID's to use
	 * @var array
	 */
	private $ids = array();

	/**
	 * Main function to render the content, here the titles, elements and ids will be set
	 * @param string $content
	 * @param array $conf
	 * @param object $parent
	 */
	public function main($content, $conf, $parent)
	{
		$this->conf = $conf;
		$this->cObj = $parent->cObj;
		$this->getNewsRecords();
		// set the titels from flexform
		$this->titles = t3lib_div::trimExplode(chr(10), $this->cObj->data['pi_flexform']['data']['title']['lDEF']['titles']['vDEF']);

		// set the view
		$view = $this->conf['views.']['tt_news.'];

		// get the News
		$pids = t3lib_div::trimExplode(",", $this->cObj->data['pages']);
		$pids = array(1,2);

		// get the informations for every content
		for ($a=0; $a < count($pids); $a++) {
			$GLOBALS['TSFE']->register['uid'] = $pids[$a];
			$GLOBALS['TSFE']->register['title'] = $this->titles[$a];
			if ($this->titles[$a] == '' || !isset($this->titles[$a])) {
				$this->titles[$a] = $this->cObj->cObjGetSingle($view['title'], $view['title.']);
				$GLOBALS['TSFE']->register['title'] = $this->titles[$a];
			}
			$this->elements[$a] = $this->cObj->cObjGetSingle($view['content'], $view['content.']);
			$this->ids[$a] = $pids[$a];
		}

		return $content;
	}

	/**
	 * Returns the identifier of this view
	 * @return string
	 */
	public function getIdentifier() {
		return $this->identifier;
	}

	/**
	 * Returns the name of the view (readable)
	 * @return string
	 */
	public function getName() {
		return $GLOBALS['LANG']->sL('LLL:EXT:jfmulticontent_news/locallang.xml:identifier');
	}

	/**
	 * Returns all titles
	 * @return array
	 */
	public function getTitles() {
		return $this->titles;
	}

	/**
	 * Returns all elements
	 * @return array
	 */
	public function getElements() {
		return $this->elements;
	}

	/**
	 * Returns all ID's
	 * @return array
	 */
	public function getIds() {
		return $this->ids;
	}

	/**
	 * Returns true if view is active
	 * @return array
	 */
	public function isActive() {
		$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['jfmulticontent_news']);
		return (isset($confArr['activateNews']) ? $confArr['activateNews'] : true);
	}

	protected function getNewsRecords() {
		// get tt_news setup & conf
		$_tt_news_setup = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tt_news.'];
		$_tt_news_confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tt_news']);
		// makeInstance - tt_news
		$_tt_news_obj = t3lib_div::makeInstance('tx_ttnews');
		$_tt_news_obj->cObj = &$this->cObj;
		$newsConf = array();
		// SELECT
		$newsConf['selectFields'] = $this->pi_prependFieldsWithTable('tt_news','*');
		// WHERE
		$newsConf['where'] = ' 1=1';
		$newsConf['where'] .= $_tt_news_obj->getLanguageWhere();
		$newsConf['where'] .= ' AND tt_news.pid > 0';
		// ttnewsType
		if( $this->conf['ttnewsType'] < 3 ) {
			$newsConf['where'] .= ' AND tt_news.type = '.$this->conf['ttnewsType'];
		}
		// ttnewsSysFolder
		$newsConf['pidInList'] = $this->conf['ttnewsSysFolder'] ? $this->conf['ttnewsSysFolder'] : $_tt_news_setup['pid_list'];
		// sliderCat
		if ( $this->conf['sliderCat'] ) {
			$newsConf['leftjoin'] = 'tt_news_cat_mm ON tt_news.uid = tt_news_cat_mm.uid_local';
			$newsConf['where'] .= ' AND (tt_news_cat_mm.uid_foreign IN ('.$this->conf['sliderCat'].'))';
		}
		// ascDesc
		$newsConf['orderBy'] = 'tt_news.'.($this->conf['ascDescField'] ? $this->conf['ascDescField'] : 'datetime').' DESC';
		if ( $this->conf['ascDesc'] == 1 ) $newsConf['orderBy'] = 'tt_news.'.$this->conf['ascDescField'].' ASC';
		if ( $this->conf['ascDesc'] == 2 ) $newsConf['orderBy'] = 'RAND()';
		// newsArchive
		if ( !$this->conf['newsArchive'] ) {
			$time = $GLOBALS['SIM_ACCESS_TIME'];
			$newsConf['where'] .= ' AND (tt_news.archivedate = 0 OR tt_news.archivedate > '. $time .')';
			if ( $_tt_news_setup['datetimeMinutesToArchive'] || $_tt_news_setup['datetimeHoursToArchive'] || $_tt_news_setup['datetimeDaysToArchive'] ) {
				if ( $_tt_news_setup['datetimeMinutesToArchive'] ) {
					$theTime = $time - t3lib_div::intval_positive($_tt_news_setup['datetimeMinutesToArchive'] * 60);
				} elseif ( $_tt_news_setup['datetimeHoursToArchive'] ) {
					$theTime = $time - t3lib_div::intval_positive($_tt_news_setup['datetimeHoursToArchive'] * 3600);
				} else {
					$theTime = $time - t3lib_div::intval_positive($_tt_news_setup['datetimeDaysToArchive'] * 86400);
				}
				$newsConf['where'] .= ' AND tt_news.datetime > ' . $theTime;
			}
		}
		// newsLimit
		$newsConf['max'] = $this->conf['newsLimit'] ? $this->conf['newsLimit'] : 20;
		// languageField
		$newsConf['languageField'] = $GLOBALS['TSFE']->sys_language_uid ? $GLOBALS['TSFE']->sys_language_uid : 0;
		// showNewsWithoutDefaultTranslation
		if ($_tt_news_setup['showNewsWithoutDefaultTranslation']) {
			$newsConf['where'] = '(' . $newsConf['where'] . ' OR (tt_news.sys_language_uid=' . $GLOBALS['TSFE']->sys_language_content . ' AND NOT tt_news.l18n_parent))';
		}

		// get the result
		$newsRes = $_tt_news_obj->cObj->exec_getQuery('tt_news', $newsConf);
		$newsRow = array();
		$newsRecords = array();
		$categories = array();
		while ( $newsRow = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($newsRes) ) {
			print_r($newsRow);
		}
		return false;
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/jfmulticontent_news/class.tx_jfmulticontent_news.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/jfmulticontent_news/class.tx_jfmulticontent_news.php']);
}

?>