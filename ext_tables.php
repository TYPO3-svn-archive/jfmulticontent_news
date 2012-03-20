<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}


$tempColumns = array(
	'tx_jfmulticontentnews_pages' => array(
		'exclude' => 1,
		'displayCond' => 'FIELD:tx_jfmulticontent_view:IN:news',
		'label' => 'LLL:EXT:jfmulticontent_news/locallang_db.xml:tt_content.tx_jfmulticontentnews_pages',
		'config' => array(
			'type' => 'group',
			'internal_type' => 'db',
			'allowed' => 'pages',
			'size' => 4,
			'minitems' => 0,
			'maxitems' => 1000,
		)
	),
);


t3lib_div::loadTCA('tt_content');
t3lib_extMgm::addTCAcolumns('tt_content', $tempColumns, 1);
$subtypes_addlist = t3lib_div::trimExplode(",", $TCA['tt_content']['types']['list']['subtypes_addlist']['jfmulticontent_pi1']);
$first = array(
	array_shift($subtypes_addlist),
	'pages',
	// 'tx_jfmulticontentnews_pages',
);

$TCA['tt_content']['types']['list']['subtypes_excludelist']['jfmulticontent_pi1'] = str_replace(",pages", "", $TCA['tt_content']['types']['list']['subtypes_excludelist']['jfmulticontent_pi1']);
$TCA['tt_content']['types']['list']['subtypes_addlist']['jfmulticontent_pi1'] = implode(",", array_merge($first, $subtypes_addlist));


t3lib_extMgm::addStaticFile($_EXTKEY, 'static/', 'Multi Content News');

?>