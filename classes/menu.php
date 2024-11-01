<?php
defined('ABSPATH') or die('This script cannot be accessed directly.');
/**
 * Snillrik restaurant menu class
 *
 * @since 2.8.0
 */
new Snillrik_restaurant_menu();

class Snillrik_restaurant_menu
{

	function __construct()
	{
		add_action('init', [$this, 'snillrik_restaurant_post_menus']);
		add_action('save_post', [$this, 'restaurant_save_menus_meta'], 1, 2); // save the custom fields
		add_filter('the_content', [$this, 'snillrik_menu_content']);
	}

	/**
	 * The meny post type
	 */
	function snillrik_restaurant_post_menus()
	{
		register_taxonomy(
			'menu-categories',
			'menu',
			array(
				'label' => esc_attr__('Menu Categories', SNILLRIKRESTAURANT_NAME),
				'rewrite' => array('slug' => 'menu-categories'),
			)
		);

		$labels = array(
			'name'               => esc_attr_x('Menus', 'Menus', SNILLRIKRESTAURANT_NAME),
			'singular_name'      => esc_attr_x('Menu', 'Menu', SNILLRIKRESTAURANT_NAME),
			'add_new'            => esc_attr_x('Add new', 'menu', SNILLRIKRESTAURANT_NAME),
			'add_new_item'       => esc_attr__('Add new menu', SNILLRIKRESTAURANT_NAME),
			'edit_item'          => esc_attr__('Edit menu', SNILLRIKRESTAURANT_NAME),
			'new_item'           => esc_attr__('New Menu', SNILLRIKRESTAURANT_NAME),
			'all_items'          => esc_attr__('All Menus', SNILLRIKRESTAURANT_NAME),
			'view_item'          => esc_attr__('Show Menu', SNILLRIKRESTAURANT_NAME),
			'search_items'       => esc_attr__('Find Menus', SNILLRIKRESTAURANT_NAME),
			'not_found'          => esc_attr__('No Menus found', SNILLRIKRESTAURANT_NAME),
			'not_found_in_trash' => esc_attr__('No Menus found in trash', SNILLRIKRESTAURANT_NAME),
			'parent_item_colon'  => '',
			'menu_name'          => esc_attr__('Menus', SNILLRIKRESTAURANT_NAME)
		);
		$args = array(
			'labels'        => $labels,
			'description'   => esc_attr__('Menus', SNILLRIKRESTAURANT_NAME),
			'public'        => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'menu_icon'           => 'dashicons-food',
			'supports' => array('title', 'editor', 'author', 'publicize', 'thumbnail', 'custom-fields', 'page-attributes', 'comments', 'revisions', 'post-formats'),
			'rewrite' => array('slug' => apply_filters("snillrik_lm_rewrite_menu", SNILLRIKRESTAURANT_REWRITE_MENU)),
			'has_archive'   => true,
			//'taxonomies' => array('menu-categories'),
			'register_meta_box_cb' => [$this, 'snillrik_restaurant_add_menumetaboxes']
		);
		register_post_type('snillrik_lm_menu', $args);
	}


	// Add the menus Meta Boxes
	function snillrik_restaurant_add_menumetaboxes()
	{
		add_meta_box('snillrik_restaurant_menu_meta_box', 'Menu information', [$this, 'snillrik_restaurant_menuinfo'], 'snillrik_lm_menu', 'normal', 'high');
	}

	// The menus code Metabox
	function snillrik_restaurant_menuinfo()
	{
		global $post;
		// Get the location data if its already been entered
		echo '<input type="hidden" name="menumeta_noncename" id="menumeta_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';

		$selected_boxes = get_post_meta($post->ID, '_selected_boxes', true);
		$selected_boxes_prices = get_post_meta($post->ID, '_selected_boxes_prices', true);
		$snillrik_restaurant_dishes = Snillrik_restaurant_dish::menu_restaurant_dishes($selected_boxes, $selected_boxes_prices);
		$pt_dish = get_post_type_object( 'snillrik_lm_dish' );
		$pt_dish_name = strtolower($pt_dish->labels->singular_name);
		echo '<div class="snillrik-restaurant-menu-admin-block">';
		echo '<div class="snillrik_restaurant-leftadmin">
			<h3>' . esc_attr__('Dishes', SNILLRIKRESTAURANT_NAME) . '</h3>
			<p>' . sprintf(esc_attr__('Select the %s that you want to appear in this menu', SNILLRIKRESTAURANT_NAME),$pt_dish_name) . '</p>
			<p>' . sprintf(esc_attr__('You can add a price for this dish specifically for this menu, but it will not link to the %s single page (because that will show the orignial price)', SNILLRIKRESTAURANT_NAME),$pt_dish_name) . '</p>';
		echo $snillrik_restaurant_dishes;

		$categories = get_terms('dishes-category', ['hide_empty' => false]);
		$categories_options_str = "";
		if (!empty($categories)) {
			$categories_name_arr = array_map(function ($type) {
				return esc_attr($type->name);
			}, $categories);
			$categories_options_str = "<option></option><option>" . implode("</option><option>", $categories_name_arr) . "</option>";
		}
		$orderbys = ["menu_order", "ID", "author", "title", "date", "rand"];
		$orderby_options_str = "<option>" . implode("</option><option>", $orderbys) . "</option>";
		echo "<h3>Shortcode generator</h3>";
		echo "<div class='snillrik_restaurant_shortcode_generator'>";
		
		echo "<input type='hidden' id='snrest_menuid' value=".$post->ID." />";
		echo "<div><h4>".esc_attr__("Show category",SNILLRIKRESTAURANT_NAME)."</h4>
			<select id='snrest_showcategory'>
				<option value='1'>True</option>
				<option value='0'>False</option>
			</select></div>";
			echo "<div><h4>".esc_attr__("Hide image",SNILLRIKRESTAURANT_NAME)."</h4>
			<select id='snrest_hideimage'>
				<option value='1'>True</option>
				<option value='0'>False</option>
			</select></div>";			
		echo "<div><h4>".esc_attr__("Link title",SNILLRIKRESTAURANT_NAME)."</h4>
			<select id='snrest_linktitle'>
				<option value='1'>True</option>
				<option value='0'>False</option>
			</select></div>";
		if ($categories_options_str !== "") {
			echo "<div><h4>".esc_attr__("Category",SNILLRIKRESTAURANT_NAME)."</h4>
			<select id='snrest_category'>
				$categories_options_str
			</select></div>";
		}
		echo "<div><h4>".esc_attr__("Order By",SNILLRIKRESTAURANT_NAME)."</h4>
			<select id='snrest_orderby'>
				$orderby_options_str
			</select></div></div>";
			
			echo "<h4>Genrated shortcode</h4>
				<div id='snillrik_restaurant_shortcode_placer'>[snillrik_restaurant_menu menuid=\"" . intval($post->ID) . "\"]</div>";

		echo "<h3>Shortcodes</h3>
		<p>" . esc_attr__('Or, shortcode for now, just copy the text below and paste where you want your menu to appear.', SNILLRIKRESTAURANT_NAME) . "</p>";
		echo "[snillrik_restaurant_menu menuid=\"" . intval($post->ID) . "\"]<br />" . esc_attr__("Or if you want to use some options:",SNILLRIKRESTAURANT_NAME)."<br />";
		echo  "[snillrik_restaurant_menu menuid=\"" . intval($post->ID) . "\" showcategory=1|0 linktitle=1|0 category=\"\" orderby=\"menu_order\"]";
		echo '</div>';

		$menu_footer = wp_kses_post(get_post_meta($post->ID, '_menu_footer', true));

		echo "<div class='snillrik_restaurant-rightadmin'>
			<h3>" . esc_attr__("Text in menu footer", SNILLRIKRESTAURANT_NAME) . "</h3>
			<p>" . esc_attr__("A short text to put in the footer of the menu.", SNILLRIKRESTAURANT_NAME) . "</p>";
		wp_editor($menu_footer, '_menu_footer');
		echo '</div>';
		echo '</div>';
	}

	// Save the Metabox Data
	function restaurant_save_menus_meta($post_id, $post)
	{
		if ($post->post_type == "snillrik_lm_menu") {
			if (!isset($_POST['menumeta_noncename']))
				return $post->ID;
			if (!wp_verify_nonce($_POST['menumeta_noncename'], plugin_basename(__FILE__))) {
				return $post->ID;
			}
			if (!current_user_can('edit_post', $post->ID))
				return $post->ID;

			$menuscode_meta['_menu_footer'] = sanitize_post($_POST['_menu_footer']);
			$menuscode_meta['_selected_boxes'] = sanitize_text_field($_POST['_selected_boxes']);
			$menuscode_meta['_selected_boxes_prices'] = sanitize_text_field($_POST['_selected_boxes_prices']);

			foreach ($menuscode_meta as $key => $value) {
				if ($post->post_type == 'revision') return;
				$value = implode(',', (array)$value);
				if (get_post_meta($post->ID, $key, FALSE)) {
					update_post_meta($post->ID, $key, $value);
				} else {
					add_post_meta($post->ID, $key, $value);
				}
				if (!$value) delete_post_meta($post->ID, $key);
			}
		}
	}

	function snillrik_menu_content($content)
	{
		if (is_single() && get_post_type() == "snillrik_lm_menu") {
			$content .= do_shortcode("[snillrik_restaurant_menu]");
		}
		return $content;
	}
}
