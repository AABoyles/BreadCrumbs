<?php
/* The BreadCrumbs extension, an extension for providing an breadcrumbs
 * navigation to users.
 *
 * @file
 * @ingroup Extensions
 * @author Manuel Schneider <manuel.schneider@wikimedia.ch>, Tony Boyles <ABoyles@milcord.com>, Ryan Lane
 * @copyright © 2007 by Manuel Schneider, 2012 by Tony Boyles, Milcord llc
 * @licence GNU General Public Licence 2.0 or later
 */

if (!defined('MEDIAWIKI')) {
	echo("This file is an extension to the MediaWiki software and cannot be used standalone.\n");
	die();
}

function fnBreadCrumbsShowHook(&$article) {
	global $wgOut, $wgUser, $wgDefaultUserOptions, $wgBreadCrumbsShowAnons;

	$wluOptions = $wgUser -> getOptions();
	
	# Should we display breadcrumbs?
	if ((!$wgBreadCrumbsShowAnons && $wgUser -> isAnon()) ||
	    (!$wluOptions['breadcrumbs-showcrumbs'])) {
		return true;
	}

	# deserialize data from session into array:
	$m_BreadCrumbs = array();

	# if we have breadcrumbs, let's us them:
	if (isset($_SESSION['BreadCrumbs'])) {
		$m_BreadCrumbs = $_SESSION['BreadCrumbs'];
	}

	# cache index of last element:
	$m_count = count($m_BreadCrumbs) - 1;

	# Title string for the page we're viewing
	$title = $article -> getTitle() -> getPrefixedText();

	# check for doubles:
	/*if (in_array($title, $m_BreadCrumbs)) {
		$val = findString($title, $m_BreadCrumbs);
		if ($m_count >= 1) {
			# reduce the array set, remove older elements:
			$m_BreadCrumbs = array_slice($m_BreadCrumbs, (1 - $wgDefaultUserOptions['breadcrumbs-numberofcrumbs']));
		}
		# add new page:
		array_push($m_BreadCrumbs, $title);
	}*/

	if (!in_array($title, $m_BreadCrumbs)) {
		if ($m_count >= 1) {
			# reduce the array set, remove older elements:
			$m_BreadCrumbs = array_slice($m_BreadCrumbs, (1 - $wluOptions['breadcrumbs-numberofcrumbs']));
		}
		# add new page:
		array_push($m_BreadCrumbs, $title);
	}

	# serialize data from array to session:
	$_SESSION['BreadCrumbs'] = $m_BreadCrumbs;

	# update cache:
	$m_count = count($m_BreadCrumbs) - 1;

	# build the breadcrumbs trail:
	if ($wluOptions['breadcrumbs-subtitle'] == 2){
		$m_trail = "$wgOut->getSubtitle<br />";
	} else {
		$m_trail = "";
	}
	for ($i = 0; $i <= $m_count; $i++) {
		$title = Title::newFromText($m_BreadCrumbs[$i]);
		$m_trail .= Linker::link($title, $m_BreadCrumbs[$i]);
		if ($i < $m_count){
			$m_trail .= ' '.$wluOptions['breadcrumbs-delimiter'].' ';
		}
	}
	if ($wluOptions['breadcrumbs-subtitle'] == 0){
		$m_trail.="<br />$wgOut->getSubtitle";
	}
	
	# ...and add it to the page:
	$wgOut -> setSubtitle($m_trail);

	# invalidate internal MediaWiki cache:
	$wgUser -> invalidateCache();

	# Return true to let the rest work:
	return true;
}

function fnBreadCrumbsAddPreferences( $user, $defaultPreferences ) {
	$defaultPreferences['breadcrumbs-showcrumbs'] = array(
		'type' => 'toggle',
		'section' => 'rendering/breadcrumbs',
		'label-message' => 'prefs-breadcrumbs-showcrumbs',
	);
	
	#TODO: This should be enabled, but I don't feel like figuring out how right now.
	/*$defaultPreferences['breadcrumbs-namespaces'] = array(
		'type' => 'toggle',
		'section' => 'rendering/breadcrumbs',
		'label-message' => 'prefs-breadcrumbs-namespaces',
	);*/

	$defaultPreferences['breadcrumbs-numberofcrumbs'] = array(
		'type' => 'int',
		'min' => 1,
		'max' => 20,
		'section' => 'rendering/breadcrumbs',
		'label-message' => 'prefs-breadcrumbs-numberofcrumbs',
		'help' => wfMsgHtml( 'prefs-breadcrumbs-numberofcrumbs-max' ),
	);	
		
	$defaultPreferences['breadcrumbs-delimiter'] = array(
		'type' => 'text',
		'section' => 'rendering/breadcrumbs',
		'label-message' => 'breadcrumbs-separator',
	);

	$defaultPreferences['breadcrumbs-subtitle'] = array(
		'type' => 'select',
		'section' => 'rendering/breadcrumbs',
		'label-message' => 'prefs-breadcrumbs-subtitle',
		'options' => array(
			'Line Before' => 0,
			'Replace' => 1,
			'Line After' => 2
         )
	);
	
	return true;
}

function findString($needle, $haystack) {
	for ($i = 0; $i < count($haystack); $i++) {
		if (strcmp($haystack[$i], $needle) == 0) {
			return $i;
		}
	}
	return false;
}
