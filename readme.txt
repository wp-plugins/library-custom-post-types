=== Library Custom Post Types ===
Contributors: thecorkboard
Donate link: http://thecorkboard.org/
Tags: custom post types, custom taxonomies, custom columns, custom metaboxes, library, libraries
Requires at least: 3.2
Tested up to: 3.2
Stable tag: 1.4

This plugin packs contains several different custom post types for use in WordPress powered library websites.

== Description ==

---IN DEVELOPMENT, CHANGE TO COME---

This plugin pack has been built with library websites in mind where common content types like a staff directory, database listing, and journal listing are typically in use.  It's also meant to act as a sort of framework to help libraries better take advantage of WordPress' custom types by demonstrating working examples.


Three different custom post types are included in this plugin pack:

* Databases
* Staff Directory
* Journals

Each custom post type has been built as a plugin and can be turned on/off in the plugins section.

Elements of the custom post types:

* Custom metaboxes (specific information like ISSN, time span coverage, staff member's phone number, etc.)
* Custom taxonomies (like subjects that the journal or database is associated with)
* Custom columns (information straight from the metaboxes when you view all your items for that custom post type)

== Installation ==

Installing the custom post types works like any other plugin:

1. Upload the `library-cpt` folder to the `/wp-content/plugins/` directory
1. Activate the custom post types of your choosing through the 'Plugins' menu in WordPress
1. Create templates to use the custom post type data

== Frequently Asked Questions ==

= Do these custom post types work straight "out of the box"? =

Nope.  You'll need to create the [template](http://codex.wordpress.org/Templates) to show the data you enter.  I hope to package some basic templates to work with in a future release.

= Could you create *this* kind of custom post type for me? =

Well - I'm up for suggestions.  Use the support forum for this plugin pack to request future post types.  But I also suggest getting your hands dirty with the packaged custom post types.  I've tried to keep my code clean enough for you to replicate in other post types and I'll continue to make it better with each iteration by adding inline documentation.

== Screenshots ==

1. The custom post types in the admin navigation.
2. The entry for an individual database.
3. The listing of all databases entered.
4. The entry for an individual staff member in the directory.
5. The listing of all staff members entered in the directory.
6. The entry for an individual journal.
7. The listing of all journals entered.

== Changelog ==

= 1.4 =
* Removed cpt-TEST.php plugin header

= 1.3 =
* Added has_archive to custom post types and adjusted rewrite to ignore "blogs" front on multisite installs
* Removed support for custom fields and author choice in all post types
* Minor changes (code cleanup, index fixes, etc.)

= 1.2 =
* Fixed an icon image path issue

= 1.1 =
* Changed staff directory member's photo to a text box for image URL entry - hope to build in a straight-to-media library functionality at a later time

= 1.0 =
* First release