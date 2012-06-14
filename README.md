BreadCrumbs
===========

A Mediawiki Extension to provide history-tracking breadcrumbs on wiki pages.


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

To customize the design of the breadcrumb trail use the CSS id
"BreadCrumbsTrail" and attach your own settings to
[[MediaWiki:Common.css]] or your users [[User:USERNAME/Common.css]].

BUGS, CONTACT
--------------------------------------------------------------------------

Write us on http://www.mediawiki.org/wiki/Extension_talk:BreadCrumbs and
I'll see what we can do for you.

Alternatively you can reach me at
- Tony Boyles: <ABoyles@milcord.com>

The download and description page of this extension can be found at:
http://www.mediawiki.org/wiki/Extension:BreadCrumbs
