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
	$breadcrumbs = '';
	for ($i = 0; $i <= $m_count; $i++) {
		$title = Title::newFromText($m_BreadCrumbs[$i]);
		$breadcrumbs .= Linker::link($title, $m_BreadCrumbs[$i]);
		if ($i < $m_count) {
			$breadcrumbs .= ' ' . $wluOptions['breadcrumbs-delimiter'] . ' ';
		}
	}
	
	#TODO: This is hideous.  Is there some cleaner way?
	switch($wluOptions['breadcrumbs-location']){
		case 0:
			$m_trail = $breadcrumbs.'<br />'.$wgOut -> getSubtitle();
			$wgOut -> setSubtitle($m_trail);
			break;
		case 1:
			$wgOut -> setSubtitle($breadcrumbs);
			break;
		case 2:
			$m_trail = $wgOut->getSubtitle() . '<br />' . $breadcrumbs;
			$wgOut -> setSubtitle($m_trail);
			break;
		case 3:
			$wgOut->prependHTML($breadcrumbs);
			break;
		#TODO: It would be awesome to have these cases working.
		/*case 4:
			$wgOut->addHTML('<br />' . $breadcrumbs);
			break;
		case 5:
			$wgOut->addWikiMsg("Breadcrumbs", $breadcrumbs);
			break;*/
	}

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
	
	$defaultPreferences['breadcrumbs-location'] = array(
		'type' => 'select',
		'section' => 'rendering/breadcrumbs',
		'label-message' => 'prefs-breadcrumbs-location',
		'options' => array(
			'Before Subtitle' => 0,
			'Instead of Subtitle' => 1,
			'After Subtitle' =>2,
			'Before Article' => 3,
			/*'After Article' => 4,
			'In Header' => 5*/
         )
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
