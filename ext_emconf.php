<?php

########################################################################
# Extension Manager/Repository config file for ext "jfmulticontent_viewdemo".
#
# Auto generated 03-08-2011 21:03
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Multiple Content News',
	'description' => 'Extends the EXT:jfmulticontent with the ability to display tt_news',
	'category' => 'plugin',
	'author' => 'Juergen Furrer',
	'author_email' => 'juergen.furrer@gmail.com',
	'shy' => '',
	'dependencies' => 'cms,tt_news,jfmulticontent',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.0.0',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'jfmulticontent' => '2.3.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:10:{s:36:"class.tx_jfmulticontent_viewdemo.php";s:4:"a868";s:21:"ext_conf_template.txt";s:4:"1d87";s:12:"ext_icon.gif";s:4:"8bd2";s:17:"ext_localconf.php";s:4:"f47c";s:14:"ext_tables.php";s:4:"f0bd";s:14:"ext_tables.sql";s:4:"32a6";s:24:"ext_typoscript_setup.txt";s:4:"0353";s:13:"locallang.xml";s:4:"9b3e";s:16:"locallang_db.xml";s:4:"1384";s:14:"doc/manual.sxw";s:4:"ddd7";}',
	'suggests' => array(
	),
);

?>