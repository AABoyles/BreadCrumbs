<?php
/* The BreadCrumbs extension, an extension for providing a breadcrumbs navigation
 * to users.
 *
 * @file BreadCrumbsFunctions.php
 * @ingroup BreadCrumbs
 * @author Manuel Schneider <manuel.schneider@wikimedia.ch>, Tony Boyles <ABoyles@milcord.com>
 * @copyright © 2007 by Manuel Schneider, 2012 by Tony Boyles, Milcord llc
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	echo( "This file is an extension to the MediaWiki software and cannot be used standalone.\n" );
	die();
}

function fnBreadCrumbsShowHook( $wgOut, $parseroutput ) {
	global $wgUser, $wgDefaultUserOptions, $wgRequest;
	global $wgBreadCrumbsShowAnons,
			$wgBreadCrumbsIgnoreRefreshes,
			$wgBreadCrumbsRearrangeHistory,
			$wgBreadCrumbsLink,
			$wgBreadCrumbsIgnoreNameSpaces;

	$wluOptions = $wgUser -> getOptions();

	# If we are Anons and should see breadcrumbs, but there's no session, let's start one so we can track from page-to-page
	if ( session_id() === "" ) {
		wfSetupSession();
	}

	# Get our data from $_SESSION:
	$m_BreadCrumbs = $wgRequest -> getSessionData( 'BreadCrumbs' );

	# if we have breadcrumbs, let's use them:
	if ( $m_BreadCrumbs === NULL ) {
		$m_BreadCrumbs = array();
	}

	# add new page:
	$m_BreadCrumbs[] = $wgOut -> getTitle() -> getPrefixedText();

	# serialize data from array to session:
	$wgRequest -> setSessionData( "BreadCrumbs", $m_BreadCrumbs );

	# cache number of elements:
	$m_count = count( $m_BreadCrumbs );

	# Should we build the breadcrumbs trail?
	if ( $wluOptions['breadcrumbs-showcrumbs'] || ( $wgBreadCrumbsShowAnons && $wgUser -> isAnon() ) ) {
		$breadcrumbs = array();
		$max = min( array( $wluOptions['breadcrumbs-numberofcrumbs'], $m_count ) );
		for ( $i = 1; count( $breadcrumbs ) < $max; $i++ ) {
			$j = $m_count - $i;
			$title = Title::newFromText( $m_BreadCrumbs[$j] );
			if ( !in_array( $title -> getNsText(), $wgBreadCrumbsIgnoreNameSpaces ) ) {
				if ( $wluOptions['breadcrumbs-namespaces'] ) {
					$breadcrumb = Linker::link( $title, $m_BreadCrumbs[$j] );
				} else {
					$breadcrumb = Linker::link( $title, $title -> getText() );
				}
				if ( count( $breadcrumbs ) > 0 ) {
					if ( !( $wluOptions['breadcrumbs-ignore-refreshes']  && ( strcmp( $breadcrumb, $breadcrumbs[( count( $breadcrumbs ) -1 )] ) === 0 ) ) &&
				   	   !( $wluOptions['breadcrumbs-filter-duplicates'] && ( array_search( $breadcrumb, $breadcrumbs ) !== FALSE ) ) ) {
						$breadcrumbs[] = $breadcrumb;
					} else if ( $wluOptions['breadcrumbs-numberofcrumbs'] > $m_count || $wluOptions['breadcrumbs-numberofcrumbs'] > count( array_unique( $m_BreadCrumbs ) ) ) {
						$max--;
					}
				} else {
					$breadcrumbs[] = $breadcrumb;
				}
			}
		}

		$breadcrumbString = $wluOptions['breadcrumbs-preceding-text'] . ' ' . implode( ' ' . htmlspecialchars( $wluOptions['breadcrumbs-delimiter'] ) . ' ', array_reverse( $breadcrumbs ) );

		$container = "<div id='breadcrumbs' style='position:relative;height:10px;font-size:0.8em;top:-15px;'>$breadcrumbString</div>";

		# And add our BreadCrumbs!
		$wgOut -> prependHTML( $container );

		# Finally, invalidate internal MediaWiki cache:
		$wgUser -> invalidateCache();
		# Must be done so that stale Breadcrumbs aren't cached into pages the user visits repeatedly.
		# This makes this a risky extension to run on a wiki which relies heavily on caching.

	}

	# Return true to let the rest work.
	return true;
}

function fnBreadCrumbsAddPreferences( $user, $defaultPreferences ) {
	global $wgBreadCrumbsAllowUPOs;

	if ( $wgBreadCrumbsAllowUPOs ) {
		$defaultPreferences['breadcrumbs-showcrumbs'] = array(
			'type' => 'toggle',
			'section' => 'rendering/breadcrumbs',
			'label-message' => 'prefs-breadcrumbs-showcrumbs' );

		$defaultPreferences['breadcrumbs-namespaces'] = array(
			'type' => 'toggle',
			'section' => 'rendering/breadcrumbs',
			'label-message' => 'prefs-breadcrumbs-namespaces', );

		$defaultPreferences['breadcrumbs-filter-duplicates'] = array(
			'type' => 'toggle',
			'section' => 'rendering/breadcrumbs',
			'label-message' => 'prefs-breadcrumbs-filter-duplicates' );

		$defaultPreferences['breadcrumbs-ignore-refreshes'] = array(
			'type' => 'toggle',
			'section' => 'rendering/breadcrumbs',
			'label-message' => 'prefs-breadcrumbs-ignore-refreshes' );

		$defaultPreferences['breadcrumbs-numberofcrumbs'] = array(
			'type' => 'int',
			'min' => 1,
			'max' => 20,
			'section' => 'rendering/breadcrumbs',
			'size' => 2,
			'maxlength' => 2,
			'label-message' => 'prefs-breadcrumbs-numberofcrumbs',
			'help-message' => 'prefs-breadcrumbs-numberofcrumbs-max' );

		$defaultPreferences['breadcrumbs-preceding-text'] = array(
			'type' => 'text',
			'section' => 'rendering/breadcrumbs',
			'size' => 34,
			'maxlength' => 30,
			'label-message' => 'prefs-breadcrumbs-preceding-text',
			'help-message' => 'prefs-breadcrumbs-preceding-text-max' );

		$defaultPreferences['breadcrumbs-delimiter'] = array(
			'type' => 'text',
			'section' => 'rendering/breadcrumbs',
			'size' => 2,
			'maxlength' => 2,
			'label-message' => 'prefs-breadcrumbs-separator',
			'help-message' => 'prefs-breadcrumbs-separator-max' );
	}

	return true;
}
