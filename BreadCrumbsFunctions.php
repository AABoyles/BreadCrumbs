<?php
/* The BreadCrumbs extension, an extension for providing an breadcrumbs
 * navigation to users.
 *
 * @file
 * @ingroup Extensions
 * @author Manuel Schneider <manuel.schneider@wikimedia.ch>, Tony Boyles <ABoyles@milcord.com>
 * @copyright © 2007 by Manuel Schneider, Tony Boyles
 * @licence GNU General Public Licence 2.0 or later
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	echo( "This file is an extension to the MediaWiki software and cannot be used standalone.\n" );
	die();
}

function fnBreadCrumbsShowHook( &$article ) {
	global $wgOut, $wgUser;
	global $wgBreadCrumbsDelimiter, $wgBreadCrumbsCount, $wgBreadCrumbsShowAnons;

	# Should we display breadcrumbs?
	if ( !$wgBreadCrumbsShowAnons && $wgUser->isAnon() ){ return true; }

	# deserialize data from session into array:
	$m_BreadCrumbs = array();
	
	# if we have breadcrumbs, let's us them:
	if ( isset( $_SESSION['BreadCrumbs'] ) ){ $m_BreadCrumbs = $_SESSION['BreadCrumbs']; }
	
	# cache index of last element:
	$m_count = count( $m_BreadCrumbs ) - 1;
	
	# Title string for the page we're viewing
	$title = $article->getTitle()->getPrefixedText();

	# check for doubles:
	if ( !in_array( $title, $m_BreadCrumbs ) ){
		if ( $m_count >= 1 ) {
			# reduce the array set, remove older elements:
			$m_BreadCrumbs = array_slice( $m_BreadCrumbs, ( 1 - $wgBreadCrumbsCount ) );
		}
		# add new page:
		array_push( $m_BreadCrumbs, $title );
	}
	
	# serialize data from array to session:
	$_SESSION['BreadCrumbs'] = $m_BreadCrumbs;
	
	# update cache:
	$m_count = count( $m_BreadCrumbs ) - 1;

	# build the breadcrumbs trail:
	$m_trail = "";
	for ( $i = 0; $i <= $m_count; $i++ ) {
		$title = Title::newFromText( $m_BreadCrumbs[$i] );
		$m_trail .= Linker::link( $title, $m_BreadCrumbs[$i] );
		if ( $i < $m_count ) $m_trail .= $wgBreadCrumbsDelimiter;
	}
	
	# ...and add it to the page:
	$wgOut->setSubtitle( $m_trail );
	/*$oldVersion = version_compare( $wgVersion, '1.18', '<=' );
	if ( $oldVersion ) { $wgOut->setSubtitle( $m_trail ); } 
	else { $wgOut->addSubtitle( $m_trail ); }*/
	
	# invalidate internal MediaWiki cache:
	$wgUser->invalidateCache();

	# Return true to let the rest work:
	return true;
}

# Entry point for the hook for printing the CSS:
function fnBreadCrumbsOutputHook( &$outputPage, $parserOutput ) {
	global $wgBreadCrumbsShowAnons;

	if ( $wgBreadCrumbsShowAnons || $outputPage->getUser()->isLoggedIn() ) {
		$outputPage->addModules( 'ext.breadCrumbs' );
	}

	# Be nice:
	return true;
}
