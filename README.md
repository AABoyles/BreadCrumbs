BreadCrumbs
===========

A Mediawiki Extension to provide a user-browsing history breadcrumb 
trail on wiki pages.


INSTALLING
--------------------------------------------------------------------------

Copy the SelectCategory directory into the extensions folder of your
MediaWiki installation. Then add the following lines to your
LocalSettings.php file (near the end):

    require_once( 'extensions/BreadCrumbs/BreadCrumbs.php' );


PARAMETERS
--------------------------------------------------------------------------

$wgBreadCrumbsDelimiter
 - defines a string which is used to delimit the path sections shown in
  breadcrumb trail
 - Example

    $wgBreadCrumbsDelimiter = ' &gt; ';

$wgBreadCrumbsCount
 - defines the number of breadcrumbs that shoul be kept
 - Example

    $wgBreadCrumbsCount = 5;


APPEARANCE
--------------------------------------------------------------------------

To customize the design of the breadcrumb trail, there are several user-
accessible options:

    # $wgBreadCrumbsDelimiter - set the delimiter
    $wgDefaultUserOptions['breadcrumbs-delimiter'] = '->';

    # $wgBreadCrumbsCount - number of breadcrumbs to use
    $wgDefaultUserOptions['breadcrumbs-numberofcrumbs'] = 5;

    # Whether to provide breadcrumbs to users by default
    $wgDefaultUserOptions['breadcrumbs-showcrumbs'] = true;

    # Whether to show the breadcrumbs' namesoaces
    $wgDefaultUserOptions['breadcrumbs-namespaces'] = true;

    # Where to put the Breadcrumbs
    $wgDefaultUserOptions['breadcrumbs-location'] = 3; #Before Article

    # Whether to ignore pages that are already in breadcrumbs
    $wgDefaultUserOptions['breadcrumbs-filter-duplicates'] = false;

    # Text to appear before breadcrumbs
    $wgDefaultUserOptions['breadcrumbs-preceding-text'] = '';

Additionally, there are some configurations available to administrators

    # Whether to ignore page refreshes
    $wgDefaultUserOptions['breadcrumbs-ignore-refreshes'] = true;

    # Whether to provide the links also for anonymous users:
    $wgBreadCrumbsShowAnons = false;

Any of these may be overridden in LocalSettings.php.


BUGS, CONTACT
--------------------------------------------------------------------------

Write us on http://www.mediawiki.org/wiki/Extension_talk:BreadCrumbs and
I'll see what we can do for you.

Alternatively you can reach me at
- Tony Boyles: <ABoyles@milcord.com>

The download and description page of this extension can be found at:
http://www.mediawiki.org/wiki/Extension:BreadCrumbs


ATTRIBUTION
--------------------------------------------------------------------------

This software was modified to add functionality for a project by
[Milcord llc.](http://milcord.com)