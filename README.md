BreadCrumbs
===========

A Mediawiki Extension to provide a user-browsing history breadcrumb
trail on wiki pages.


INSTALLING
--------------------------------------------------------------------------

Copy the BreadCrumbs directory into the extensions folder of your
MediaWiki installation. Then add the following lines to your
LocalSettings.php file (near the end):

    require_once( 'extensions/BreadCrumbs/BreadCrumbs.php' );


PARAMETERS/APPEARANCE
--------------------------------------------------------------------------
To customize the behavior of the breadcrumb trail, there are several
options available to administrators:

    # Whether to provide the links also for anonymous users:
    $wgBreadCrumbsShowAnons = true;

    # $wgBreadCrumbsAllowUPOs - Should users be allowed to configure
    #  BreadCrumbs Options?
    $wgBreadCrumbsAllowUPOs = true;

    # If you don't want certain Namespaces recorded, add them here:
    $wgBreadCrumbsIgnoreNameSpaces = array();


Additionally, there are several user-accessible options which can be
modified from [[Special:Preferences/Appearance#BreadCrumbs]] (default
values given):

    # Whether to provide breadcrumbs to users by default
    $wgDefaultUserOptions['breadcrumbs-showcrumbs'] = true;

    # Text to appear before breadcrumbs
    $wgDefaultUserOptions['breadcrumbs-preceding-text'] = '';

    # Whether to show the breadcrumbs' namespaces
    $wgDefaultUserOptions['breadcrumbs-namespaces'] = true;

    # $wgBreadCrumbsDelimiter - set the delimiter
    $wgDefaultUserOptions['breadcrumbs-delimiter'] = '>';

    # $wgBreadCrumbsCount - number of breadcrumbs to use
    $wgDefaultUserOptions['breadcrumbs-numberofcrumbs'] = 5;

    # Whether to ignore pages that are already in breadcrumbs
    $wgDefaultUserOptions['breadcrumbs-filter-duplicates'] = false;

    # Whether to ignore page refreshes
    $wgDefaultUserOptions['breadcrumbs-ignore-refreshes'] = true;


Any of these may be overridden in LocalSettings.php.


BUGS, CONTACT
--------------------------------------------------------------------------

Write us on http://www.mediawiki.org/wiki/Extension_talk:BreadCrumbs and
we'll see what we can do for you.

Alternatively you can reach me (Tony Boyles)
[by e-mail](mailto:ABoyles@milcord.com)

The description page of this extension can be found at:
http://www.mediawiki.org/wiki/Extension:BreadCrumbs


ATTRIBUTION
--------------------------------------------------------------------------

This software was originally written by Manuel Schneider. It was modified 
(and is maintained) by Tony Boyles to add functionality for a project by
[Milcord llc.](http://milcord.com)