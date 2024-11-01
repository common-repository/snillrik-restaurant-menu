<?php
defined('ABSPATH') or die('This script cannot be accessed directly.');
/**
 * Snillrik restaurant dish Widget widget class
 *
 * @since 2.8.0
 */
new Snillrik_restaurant_dish();

class Snillrik_restaurant_dish
{

	function __construct()
	{
		add_action('init', [$this, 'snillrik_restaurant_post_snillrik_restaurant_dishs']);
		add_action('save_post', [$this, 'restaurant_save_snillrik_restaurant_dishs_meta'], 1, 2); // save the custom fields
		add_filter('the_content', [$this, 'snillrik_dish_content']);
	}

	/**
	 * snillrik_restaurant_post_snillrik_restaurant_dishs to create the snillrik_restaurant_dish post type.
	 */
	function snillrik_restaurant_post_snillrik_restaurant_dishs()
	{
		register_taxonomy(
			'dishes-tags',
			'snillrik_restaurant_dish',
			array(
				'label' => esc_attr__('Dish Tags', SNILLRIKRESTAURANT_NAME),
				'rewrite' => array('slug' => 'dishes-tags'),
			)
		);


		$labels = array(
			'name'              => esc_attr_x('Dish-categories', 'taxonomy general name', SNILLRIKRESTAURANT_NAME),
			'singular_name'     => esc_attr_x('Dish-category', 'taxonomy singular name', SNILLRIKRESTAURANT_NAME),
			'search_items'      => esc_attr__('Search Dish-types', SNILLRIKRESTAURANT_NAME),
			'all_items'         => esc_attr__('All Dish-types', SNILLRIKRESTAURANT_NAME),
			'parent_item'       => esc_attr__('Parent Dish-type', SNILLRIKRESTAURANT_NAME),
			'parent_item_colon' => esc_attr__('Parent Dish-type:', SNILLRIKRESTAURANT_NAME),
			'edit_item'         => esc_attr__('Edit Dish-type', SNILLRIKRESTAURANT_NAME),
			'update_item'       => esc_attr__('Update Dish-type', SNILLRIKRESTAURANT_NAME),
			'add_new_item'      => esc_attr__('Add New Dish-type', SNILLRIKRESTAURANT_NAME),
			'new_item_name'     => esc_attr__('New Dish-type Name', SNILLRIKRESTAURANT_NAME),
			'menu_name'         => esc_attr__('Dish cat', SNILLRIKRESTAURANT_NAME),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array('slug' => 'dish-type'),
		);

		register_taxonomy('dishes-category', array('dish'), $args);


		$labels = array(
			'name'               => esc_attr_x('Dishes', 'dishes', SNILLRIKRESTAURANT_NAME),
			'singular_name'      => esc_attr_x('Dish', 'Dish', SNILLRIKRESTAURANT_NAME),
			'add_new'            => esc_attr_x('Add new', 'snillrik_restaurant_dish', SNILLRIKRESTAURANT_NAME),
			'add_new_item'       => esc_attr__('Add new dish', SNILLRIKRESTAURANT_NAME),
			'edit_item'          => esc_attr__('Edit dish', SNILLRIKRESTAURANT_NAME),
			'new_item'           => esc_attr__('New dish', SNILLRIKRESTAURANT_NAME),
			'all_items'          => esc_attr__('All dishes', SNILLRIKRESTAURANT_NAME),
			'view_item'          => esc_attr__('Show dish', SNILLRIKRESTAURANT_NAME),
			'search_items'       => esc_attr__('Find dishes', SNILLRIKRESTAURANT_NAME),
			'not_found'          => esc_attr__('No dishes found', SNILLRIKRESTAURANT_NAME),
			'not_found_in_trash' => esc_attr__('No dishes found in trash', SNILLRIKRESTAURANT_NAME),
			'parent_item_colon'  => '',
			'menu_name'          => esc_attr__('Dishes', SNILLRIKRESTAURANT_NAME)
		);
		$args = array(
			'labels'        => $labels,
			'description'   => esc_attr__('Dishes', SNILLRIKRESTAURANT_NAME),
			'public'        => true,
			'publicly_queryable'  => true,
			'exclude_from_search' => false,
			'menu_icon' => 'dashicons-food',
			'supports' => array('title', 'editor', 'author', 'publicize', 'thumbnail', 'custom-fields', 'page-attributes', 'comments', 'revisions', 'post-formats'),
			'rewrite' => array('slug' => apply_filters("snillrik_lm_rewrite_dish", SNILLRIKRESTAURANT_REWRITE_DISH)),
			'has_archive'   => true,
			'taxonomies' => array('dishes-category', 'dishes-tags'),
			'register_meta_box_cb' => [$this, 'snillrik_restaurant_add_dishesmetaboxes']
		);
		register_post_type('snillrik_lm_dish', $args);
	}


	/**
	 * Add the snillrik_restaurant_dishes Meta Boxes
	 */
	function snillrik_restaurant_add_dishesmetaboxes()
	{
		add_meta_box('snillrik_restaurant_dishes_meta_box', 'dish information', [$this, 'snillrik_restaurant_info'], 'snillrik_lm_dish', 'normal', 'high');
	}

	/**
	 * The snillrik_restaurant_dishs code Metabox
	 */
	function snillrik_restaurant_info()
	{
		global $post;
		// Get the location data if its already been entered
		echo '<input type="hidden" name="snillrik_restaurant_dishmeta_noncename" id="snillrik_restaurant_dishmeta_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';

		$price = get_post_meta($post->ID, '_snillrik_restaurant_dishprice', true);
		// Echo out the field
		echo '<div class="snillrik-restaurant-menu-admin-block">';
		echo '<div class="snillrik_restaurant-leftadmin">
				<h4>' . esc_attr__("Price", SNILLRIKRESTAURANT_NAME) . '</h4>
				<p>' . esc_attr__("Set a price for this dish, it can be set to a specific price in the menu too, but defaults to this price.", SNILLRIKRESTAURANT_NAME) . '</p>
				<input type="number" step="0.01" name="_snillrik_restaurant_dishprice" value="' . floatval($price)  . '" class="widefat" />
			</div>';

		$ingredients = wp_kses_post(get_post_meta($post->ID, '_snillrik_restaurant_dish_ingredients', true));
		// Echo out the field

		echo '<div class="snillrik_restaurant-rightadmin">
			<h4>Ingredients / More info</h4>
			<p>Here you can list ingredients, more info and so on.</p>';
		wp_editor($ingredients, '_snillrik_restaurant_dish_ingredients');
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Save the Metabox Data
	 * @param number $post_id the post id
	 * @param object $post the post.
	 */
	function restaurant_save_snillrik_restaurant_dishs_meta($post_id, $post)
	{

		if ($post->post_type == "snillrik_lm_dish") {
			if (!isset($_POST['snillrik_restaurant_dishmeta_noncename']))
				return $post->ID;
			if (!wp_verify_nonce($_POST['snillrik_restaurant_dishmeta_noncename'], plugin_basename(__FILE__))) {
				return $post->ID;
			}

			if (!current_user_can('edit_post', $post->ID))
				return $post->ID;

			$snillrik_restaurant_dishscode_meta['_snillrik_restaurant_dishprice'] = sanitize_text_field($_POST['_snillrik_restaurant_dishprice']);
			$snillrik_restaurant_dishscode_meta['_snillrik_restaurant_dish_ingredients'] = sanitize_post($_POST['_snillrik_restaurant_dish_ingredients']);

			foreach ($snillrik_restaurant_dishscode_meta as $key => $value) {
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

	/**
	 * snillrik_restaurant_dishes for adding to the menus in admin.
	 * @param string $selected the selected box, or boxes.
	 * @return string
	 */
	public static function menu_restaurant_dishes($selected = "", $selected_prices = "")
	{

		$args = array(
			'posts_per_page'   => -1,
			'offset'           => 0,
			'category'         => '',
			'orderby'          => 'post_date',
			'order'            => 'DESC',
			'include'          => '',
			'exclude'          => '',
			'meta_key'         => '',
			'meta_value'       => '',
			'post_type'        => 'snillrik_lm_dish',
			'post_mime_type'   => '',
			'post_parent'      => '',
			'post_status'      => 'publish',
			'suppress_filters' => true
		);

		$posts_array = get_posts($args);

		$selected_prices_list = [];
		$selected_prices_arr = explode(",", $selected_prices);
		foreach (explode(",", $selected) as $key => $sel) {
			if (isset($selected_prices_arr[$key]))
				$selected_prices_list[$sel] = $selected_prices_arr[$key];
		}

		$output = '<div class="snillrik_restaurant_dishboxes_admin">
		<input type="hidden" id="_selected_boxes" name="_selected_boxes" value="' . esc_attr($selected) . '" />
		<input type="hidden" id="_selected_boxes_prices" name="_selected_boxes_prices" value="' . esc_attr($selected_prices) . '" />';

		$selcted_boxes_arr = explode(",", $selected);

		foreach ($posts_array as $post) {

			$price = floatval(get_metadata("post", $post->ID, "_snillrik_restaurant_dishprice", true));
			$set_price = isset($selected_prices_list[$post->ID]) && is_numeric($selected_prices_list[$post->ID])
				? $selected_prices_list[$post->ID]
				: "";

			$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID));
			$imgen  = $thumb ? '<img src="' . reset($thumb) . '" alt="Text_2" />' : '';
			//link tt edit post
			$link = '<a href="' . get_edit_post_link($post->ID) . '">' . $post->post_title . '</a>';

			$sel_str = in_array($post->ID, $selcted_boxes_arr) ? " snillrik_restaurant_dishbox_selected" : "";

			$output .= '<div class="snillrik_restaurant_dishbox_admin' . $sel_str . '" id="snillrik_restaurant_dish_' . $post->ID . '">';
			$output .= '<div class="snillrik_restaurant_dishbox_admin_clickable" data-dish-id="' . $post->ID . '">
				<div><h4>' . $link . '</h4>';
			$output .= '<span class="snillrik_restaurant_dishbox_admin_price">'
				. self::snillrik_restaurant_price_format($price)
				. '</span>';
			$output .= '</div>
			<div class="snillrik_restaurant_dishbox_admin_img">' . $imgen . '</div>
			</div>';
			$output .= '<div class="snillrik_restaurant_dishbox_admin_price_menuprice">
					<div>' . esc_attr__("Set price for this menu", SNILLRIKRESTAURANT_NAME) . '</div>
					<div><input type="number" step="0.01" name="snillrik_restaurant_menu_dish_price_' . $post->ID . '" id="snillrik_restaurant_menu_dish_price_' . $post->ID . '" class="snillrik_restaurant_dishbox_admin_price_clickable" data-dish-id="' . $post->ID . '" value="' . $set_price . '" /></div>
				</div>';
			$output .= '</div>';
		}

		$output .= '</div>';

		return $output;
	}

	/**
	 * frontend dish box
	 */
	public static function dishbox($post, $linktitle, $showingridents, $menu_style = "default", $hideimage = false, $set_price = false)
	{

		$return_html = "";
		$atts = [];
		$post = is_numeric($post) ? get_post($post) : $post;
		
		$the_price = floatval(get_metadata("post", $post->ID, "_snillrik_restaurant_dishprice", true));
		$price = $set_price!==false && is_numeric($set_price) ? $set_price : $the_price;

		$link_this = $linktitle && $the_price == $price ? true : false;

		$snillrik_restaurant_dish_ingredients = wp_kses_post(get_metadata("post", $post->ID, "_snillrik_restaurant_dish_ingredients", true));
		$the_url = get_permalink($post->ID);

		$tag_strings = "";
		$the_tags = get_the_terms($post->ID, "dishes-tags");
		if (!is_wp_error($the_tags) && is_array($the_tags)) {
			foreach ($the_tags as $tag) {
				$tag_strings .= "<span class='snillrik_restaurant_tag snillrik_restaurant_" . sanitize_key($tag->name) . "'>" . esc_attr($tag->name) . "</span>";
			}
		}

		$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
		$src_set = wp_get_attachment_image_srcset(get_post_thumbnail_id($post->ID), 'large');
		$imgen = $thumb ? '<img src="' . esc_url($thumb['0']) . '" srcset="' . $src_set . '" alt="' . esc_attr($post->post_title) . '" />' : '';

		$linktitle_str = "";
		$content_str = "";
		$ingriedients_str = "";
		$thumb_str = "";
		$thumb_data_url= $link_this ? $the_url : "";
		if (!$hideimage && isset($thumb['0']) && $thumb['0'] != "") {
			$thumb_str = '<div class="snillrik_restaurant_dishbox_item snillrik_restaurant_dishbox_img" data-url="'.$thumb_data_url.'">
					<div class="snillrik_restaurant_dishbox_img_inner">' . $imgen . '</div>
				</div>';
		}

		if ($link_this) //yeh, bit weird but on the dish page it wont know what the price in the menu is.
			$linktitle_str = "<a href='$the_url'><h3>" . esc_attr($post->post_title) . "</h3></a>";
		else
			$linktitle_str = "<h3>" . esc_attr($post->post_title) . "</h3>";

		if ($post->post_content != "")
			$content_str = "<p>" . wp_kses_post($post->post_content) . "</p>";
		if ($snillrik_restaurant_dish_ingredients != "" && $showingridents)
			$ingriedients_str = "<p>" . wp_kses_post($snillrik_restaurant_dish_ingredients) . "</p>";

		$price_str = $price > 0 ? Snillrik_restaurant_dish::snillrik_restaurant_price_format($price) : "";

		switch ($menu_style) {
			case "fancy":
				$return_html .= "
				$thumb_str
				<div class='snillrik_restaurant_dishbox_item snillrik_restaurant_dishbox_main'>
					<div class='snillrik_restaurant_dishbox_content'>
						<div class='snillrik_restaurant_dishbox_tags'>$tag_strings</div>
						$linktitle_str
						$content_str
						$ingriedients_str
					</div>
					<div class='snillrik_restaurant_dishbox_price'>
						<span class='snillrik_restaurant_dishbox_price_inner'>$price_str</span>
					</div>
				</div>";
				break;
			case "default":
				$return_html .= "
				<div class='snillrik_restaurant_dishbox_item snillrik_restaurant_dishbox_main'>
					<div class='snillrik_restaurant_dishbox_content'>
						<div class='snillrik_restaurant_dishbox_tags'>$tag_strings</div>
						$linktitle_str
						$content_str
						$ingriedients_str
					</div>
					<div class='snillrik_restaurant_dishbox_price'>
						<span class='snillrik_restaurant_dishbox_price_inner'>$price_str</span>
					</div>
				</div>
				$thumb_str
				";
				break;
		}
		//filter for return html
		$atts = [
			"dish" => $post,
			"title" => $linktitle_str,
			"content" => $content_str,
			"tags" => $tag_strings,
			"ingredients" => $ingriedients_str,
			"price" => $price_str,
			"thumb" => $thumb_str,
			"image" => $imgen,
			"image_url" => isset($thumb['0']) ? $thumb['0'] : "",
			"menu_style" => $menu_style
		];
		$return_html = apply_filters("snillrik_restaurant_dishbox_html", $return_html, $atts);
		return "<div class='snillrik_restaurant_dishbox' id='snillrik_restaurant_dish_" . intval($post->ID) . "'>"
		.$return_html
		."</div>";
	}


	/** 
	 * The extra content of the dishes single page
	 */
	function snillrik_dish_content($content)
	{
		if (is_single() && get_post_type() == "snillrik_lm_dish") {
			wp_enqueue_style('snillrik_restaurant');
			$post = get_post();
			$price = floatval(get_metadata("post", $post->ID, "_snillrik_restaurant_dishprice", true));
			$snillrik_restaurant_dish_ingredients = wp_kses_post(get_metadata("post", $post->ID, "_snillrik_restaurant_dish_ingredients", true));
			$ingredients = wp_kses_post(get_option('snillrik_restaurant_ingredients'));
			$ingredients_str = $ingredients == "" ? "" : "<h4>$ingredients</h4>";
			$tag_strings = "";
			$the_tags = get_the_terms($post->ID, "dishes-tags");
			if (!is_wp_error($the_tags) && is_array($the_tags)) {
				foreach ($the_tags as $tag) {
					$tag_strings .= "<span class='snillrik_restaurant_tag snillrik_restaurant_" . sanitize_key($tag->name) . "'>" . esc_attr($tag->name) . "</span>";
				}
			}

			$thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
			$src_set = wp_get_attachment_image_srcset(get_post_thumbnail_id($post->ID), 'large');
			$imgen = $thumb ? '<img src="' . esc_url($thumb['0']) . '" srcset="' . $src_set . '" alt="' . esc_attr($post->post_title) . '" />' : '';
			$price_str = $price > 0 ? self::snillrik_restaurant_price_format($price) : "";

			$content = "<div class='snillrik_restaurant_dishbox_item snillrik_restaurant_dishbox_main'>
			<div class='snillrik_restaurant_dishbox_content'>
				<div class='snillrik_restaurant_dishbox_tags'>$tag_strings</div>";
			if ($post->post_content != "" || $snillrik_restaurant_dish_ingredients != "")
				$content .= "<p>" . wp_kses_post($post->post_content) . "</p>" . $ingredients_str . $snillrik_restaurant_dish_ingredients . "";
			$content .= "<div class='snillrik_restaurant_dishbox_price'>
				<span class='snillrik_restaurant_dishbox_price_inner'>" . $price_str . "</span>
				</div></div>";
			if ($imgen != "")
				$content .= "<div class='snillrik_restaurant_dishbox_item snillrik_restaurant_dishbox_img'>$imgen</div>";
			$content .= "</div>";
			$content = "<div class='snillrik_restaurant_dishbox'>" . $content . "</div>";
		}
		return $content;
	}

	/**
	 * The price format
	 */
	public static function snillrik_restaurant_price_format($price)
	{
		if(!is_numeric($price) || $price == 0)
			return "";
		$cashtype = get_option('snillrik_restaurant_cahstype');
		$cashplace = get_option('snillrik_restaurant_cahsplace');
		$decimals = intval(get_option('snillrik_restaurant_decimals'));

		//check floatval and set two decimals
		$price_out = floatval($price);
		$price_out = number_format($price_out, $decimals, '.', '');

		$price_str = $cashplace == "After" ? $price_out . "&#160;" . esc_attr($cashtype) : esc_attr($cashtype) . "" . $price_out;

		return "<span>" . $price_str . "</span>";
	}
}
