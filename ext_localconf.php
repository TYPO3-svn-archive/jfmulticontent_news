<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

// Add hook
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['jfmulticontent']['getViews'][$_EXTKEY . '_1'] = 'EXT:' . $_EXTKEY . '/class.tx_jfmulticontent_news.php:tx_jfmulticontent_news';
?>