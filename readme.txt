=== Snillrik Restaurant===
Contributors: Mattias Kallio
Tags: restaurant, shortcodes, lunch menu
Requires at least: 3.0
Tested up to: 6.2
Stable tag: 2.1.2

Easiest way to maintain a menu that changes every day, like a lunchmenu or "Today's special".

== Description ==

The idea is that you can create a bunch of different dishes, with images, price, ingredients etc... Then these are used to make up menus that are easily changed from day to day. So a particular use would be a lunch menu where you would want the same menu and shortcode but you want to change dishes every day. You could also have several menus, maybe one for each day of the week.

There are also categories for the different dishes, so if you have like Meat, Fish, Veg, Ice cream etc, the menu will be in that order.

So, the plugin adds th post types Dishes and Menus that can be added and edited the wordpress way. Then you can use the widget to add specific dishes and a shortcode for the menu on a page.

Shortcodes
[snillrik_restaurant_menu menuid="42" showcategory=1|0 hideimage=1|0 linktitle=1|0 category="" orderby="menu_order"]
If you want to not show the category text chose 0 and if you want to link to the dish page chose 1 etc. menu_order is the order param set in admin on each dish. the orderby can be set to common wp orderbys too, like date or title.

[snillrik_restaurant_dishes]
A simple list of dishes sorted under categories. Intended to be a side menu etc.

Filters
They default to snillrik_lm_menu and snillrik_lm_dish witch might not be tha pretty, but it's unique... So if you want the urls to be something like /dishes/ or /food/, use these.
add_filter("snillrik_lm_rewrite_menu",function($thename){
    return "menue";
},10,1);
add_filter("snillrik_lm_rewrite_dish",function($thename){
    return "dish";
},10,1);

Template / html for each dish.
The default template is a bit basic, but it's easy to style and it's a good start. If you want to change the html for each dish, use this filter.
<code>add_filter('snillrik_restaurant_dishbox_html', function ($return_html, $atts) {
    $post_title = $atts['title'];
    $tag_strings = $atts['tags'];
    $content_str = $atts['content'];
    $ingredients_str = $atts['ingredients'];
    $price_str = $atts['price'];
    $thumb = $atts['thumb'];
    return "
    <div class='snillrik_restaurant_dishbox_item snillrik_restaurant_dishbox_main'>
    
    <div class='snillrik_restaurant_dishbox_content'>
        <div class='snillrik_restaurant_dishbox_tags'>$tag_strings</div>
        $post_title
        $content_str
        $ingredients_str
    </div>
    <div class='snillrik_restaurant_dishbox_price'>
        <span class='snillrik_restaurant_dishbox_price_inner'>$price_str</span>
    </div>
    $thumb
    </div>";
}, 10, 2);
</code>

= Active Contributors =
<li>[Mattias P Kallio](http://webbigt.se) (Training)</li>

== Installation ==

1. Upload the plugin folder to the '/wp-content/plugins/' directory of your WordPress site,
2. Activate the plugin through the 'Plugins' menu in WordPress,

== Frequently Asked Questions ==

= Do I need to have a category for the dishes =
Yes. I will fix this in a later version, but for now the menues list all the categories and the foods belonging to that category under it.

= Where can I report bugs, leave my feedback and get support? =

Our team answers your questions at:
http://www.snillrik.se

== Changelog ==

=2.1.2=
If there is a price set on a menu the link to the dish page will be removed. (because it wont know what price to show on the dish page).
"linktitle" parameter was not working poparly, fixed that.
Fixed some minor not-even-bugs mostly regarding prices and links, but things that could be better. :)
If price is set to zero on dish (or no price at all) no price will be shown on dish page (it showed 0 before).

=2.1.1=
Prettyfying UI.
If price is 0 nothing is shown.
Check compability with WP 6.2

=2.1.0=
Got som tips and ideas from Alex C. Thanks for that. :)
Fixed som weird look in admin on mobile. 
Added som divs and classes to make it easier to style the menu.
Added a filter for the html of each dish in the menu, to

=2.0.0=
Added possibility to set a price for each product in the menu. 
Settings page now has a possibility to set decimals for prices.
Some styling and code prettfying.

= 1.9.0 =
Making single dish page a bit more styleable
fixing a bit more easy to use css-classes etc.
(also a bit hidden paramter 'menu_style' => 'default', // default, fancy, it's not that big of a differance, but it's to make it more usable for non-webbnerds later.)

= 1.8.6 =
Added parameter for showing or hiding images in shortcode: hideimage=1|0
Some styling and minor stuff (like space between price and sign)

= 1.8.5 =
Testing version 6.1.1 and some minor style fixes.

= 1.8.4 =
I got a bunch of suggestions, tips and some testing, so there are a lot of updates now. :)
If price is 0 nothing is shown instead. 

= 1.8.3 =
Fixed category naming bug when using dishes shortcode

= 1.8.2 =
Fixed bug in translations.

= 1.8.0 =
Added filters for rewriting the urls (as displayed in readme)
Re-thought the categories and tags and it now works more like commonly in WP. 
Added a shortcode generator on each menu for when using it on pages.
Added tags to the only template that currently exists.
Got a bunch of cool ideas from Jarko, so thanks for that. :)

= 1.7.2 =
Test of WP 6.0 and making it a bit more easy to style the menues.

= 1.7.1 =
Made it a bit easier to style the menu, it's now css flex.

= 1.7 =
Some more escaping output and some fix, trix and just some TLC.

= 1.6.4 =
Escaping text and securing output to browser.

= 1.6.3 =
Testing for 5.8, no updates

= 1.6.2 =
Making code a bit more consistant, and prettified a bit too. 
Made the menu-urls show dishes but the shortcodes in pages is probably still the easiest way to show menus.
And minor bugfixes.
Some text changes.

= 1.5.2 =
Minor bugfix in shortcode showing title when showcategory set to string "true".
Added parameter showcatdescription to hide description text even if set in category

= 1.5.1 =
Minor update, mostly just a version check for WP 5.7

= 1.0 =
* New
