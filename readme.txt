=== Advanced Custom Fields ===
Contributors: Elliot Condon
Donate link: https://www.paypal.com/au/cgi-bin/webscr?cmd=_flow&SESSION=-B2MHZ-ioHQb-z1o22AMmhjSI08rxFqQdljyfqVa1R-4QrbQWPNcfL37jYi&dispatch=5885d80a13c0db1f8e263663d3faee8d5fa8ff279e37c3d9d4e38bdbee0ede69
Tags: custom, field, custom field, advanced, simple fields, magic fields, more fields, post, type, text, textarea, file, image, edit, admin
Requires at least: 3.0
Tested up to: 3.1
Stable tag: 3.1

Completely Customise your edit pages with an assortment of field types: Wysiwyg, text, image, select, checkbox, page link, post object and more! Hide unwanted metaboxes and assign to any edit page!

== Description ==

Advanced Custom Fields is the perfect solution for any wordpress website which needs more flexible data like other Content Management Systems. 

* Visually create your Fields
* Select from multiple input types (text, textarea, wysiwyg, image upload, page link, select, checkbox, more to come)
* Assign your fields to multiple edit pages (specific ID's, post types, post slugs, parent ID's, template names)
* Add, Edit and reorder infinite rows to your fields
* Easily load data through a simple and friendly API
* Uses the native WordPress custom post type for ease of use and fast processing
* Now uses custom Database tables to improve speed, reliability and future development

= Field Types =
* Text (type text, api returns text)
* Text Area (type text, api returns text with `<br />` tags)
* WYSIWYG (a wordpress wysiwyg editor, api returns html)
* Image (upload an image, api returns the url)
* File (upload a file, api returns the url)
* Select (drop down list of choices, api returns chosen item)
* Checkbox (tick for a list of choices, api returns array of choices)
* Page Link (select 1 or more page, post or custom post types, api returns the url)
* Post Object (select 1 or more page, post or custom post types, api returns post objects)
* Date Picker (jquery date picker, options for format, api returns string)
* True / False (tick box with message, api returns true or false)

= Tested on =
* Mac Firefox 	:)
* Mac Safari 	:)
* Mac Chrome	:)
* PC Firefox	:)
* PC ie7	:S

= Video Tutorials =
http://plugins.elliotcondon.com/advanced-custom-fields/user-guide/

= Website =
http://plugins.elliotcondon.com/advanced-custom-fields/

= Bug Submission and Forum Support =
http://support.plugins.elliotcondon.com/categories/advanced-custom-fields/

= Please Vote and Enjoy =
Your votes really make a difference! Thanks.


== Installation ==

1. Upload 'advanced-custom-fields' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Click on Settings -> Adv Custom Fields and create your first matrix!


== Frequently Asked Questions ==
Please View the forum
http://support.plugins.elliotcondon.com/categories/advanced-custom-fields/


== Screenshots ==
1. Creating the Advanced Custom Fields

2. Adding the Custom Fields to a page and hiding the default meta boxes

3. The Page edit screen after creating the Advanced Custom Fields

4. Simple and intuitive API. Read the documentation at: http://plugins.elliotcondon.com/advanced-custom-fields/code-examples/


== Changelog ==

= 1.1.1 =
* Fixed Slashes issue on edit screens for text based fields

= 1.1.0 =
* Lots of Field Type Bug Fixes
* Now uses custom database tables to save and store data!
* Lots of tidying up
* New help button for location meta box
* Added $post_id parameter to API functions (so you can get fields from any post / page)
* Added support for key and value for select and checkbox field types
* Re wrote most of the core files due to new database tables
* Update script should copy across your old data to the new data system
* Added True / False Field Type

= 1.0.5 =
* New Field Type: Post Object
* Added multiple select option to Select field type

= 1.0.4 =
* Updated the location options. New Override Option!
* Fixed un ticking post type problem
* Added JS alert if field has no type

= 1.0.3 =
* Heaps of js bug fixes
* API will now work with looped posts
* Date Picker returns the correct value
* Added Post type option to Page Link Field
* Fixed Image + File Uploads!
* Lots of tidying up!

= 1.0.2 =
* Bug Fix: Stopped Field Options from loosing data
* Bug Fix: API will now work with looped posts

= 1.0.1 =
* New Api Functions: get_fields(), get_field(), the_field()
* New Field Type: Date Picker
* New Field Type: File
* Bug Fixes
* You can now add multiple ACF's to an edit page
* Minor CSS + JS improvements

= 1.0.0 =
* Advanced Custom Fields.
