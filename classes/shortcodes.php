<?php
defined('ABSPATH') or die('This script cannot be accessed directly.');
/**
 * Snillrik restaurant dish Widget widget class
 *
 * @since 2.8.0
 */
new Snillrik_restaurant_shortcodes();

class Snillrik_restaurant_shortcodes
{

	function __construct()
	{
		add_shortcode('snillrik_restaurant_menu', [$this, 'menu']);
		add_shortcode('snillrik_restaurant_dishes', [$this, 'dishes']);
	}


	function menu($atts, $content = null)
	{
		wp_enqueue_style('snillrik_restaurant');
		wp_enqueue_script('snillrik_restaurant_front');
		extract(shortcode_atts(
			array(
				'category' => '', // menu category
				'menuid' => '',
				'showcategory' => true,
				'showcatdescription' => true,
				'showingridents' => false,
				'hideimage' => false,
				'linktitle' => true,
				'orderby' => 'menu_order',
				'menu_style' => 'default', // default, fancy
			) // menu category
			,
			$atts
		));

		$tags = get_terms('dishes-tags', 'orderby=count&order=DESC&hide_empty=1&exclude=7');
		$categories = get_terms('dishes-category', ['orderby' => 'count', 'order' => 'DESC', 'hide_empty' => 1]);
		$output = '<div class="snillrik_restaurant_menu snillrik_restaurant_menu_' . $menu_style . '">';

		foreach ($categories as $cats) {
			if ($category == '' || $category == $cats->name) {
				$custom_fields = get_post_custom($menuid);

				if (!isset($custom_fields["_selected_boxes"]))
					return esc_attr__("No dishes set", SNILLRIKRESTAURANT_NAME);
				$sels = explode(",", reset($custom_fields["_selected_boxes"]));

				$sels_prices = [];
				if(isset($custom_fields["_selected_boxes_prices"])) {
					$prices = explode(",", reset($custom_fields["_selected_boxes_prices"]));
					foreach($sels as $key => $sel) {
						$sels_prices[$sel] = isset($prices[$key]) ? $prices[$key] : false;
					}
				}	

				$posts_array = get_posts(array(
					'post__in' => $sels,
					'posts_per_page' => -1,
					'post_type' => "snillrik_lm_dish",
					'order' => 'ASC',
					'taxonomy' => $cats->taxonomy,
					'term' => $cats->slug,
					'orderby' => $orderby
				));

				$showcategory = filter_var($showcategory, FILTER_VALIDATE_BOOLEAN);
				$showcatdescription = filter_var($showcatdescription, FILTER_VALIDATE_BOOLEAN);
				$hideimage = filter_var($hideimage, FILTER_VALIDATE_BOOLEAN);
				$linktitle = filter_var($linktitle, FILTER_VALIDATE_BOOLEAN);

				if (count($posts_array) > 0) {
					if ($showcategory)
						$output .= "<h2>" . esc_attr($cats->name) . "</h2>";
					if ($cats->description != "" && $showcatdescription)
						$output .= "<p>" . esc_attr($cats->description) . "</p>";
				}
				$output .= "<!--posts_array--><div class='snillrik_restaurant_category_box'>";

				foreach ($posts_array as $post) {
					$dish_price = isset($sels_prices[$post->ID]) ? $sels_prices[$post->ID] : false;
					$output .= Snillrik_restaurant_dish::dishbox($post, $linktitle, $showingridents, $menu_style, $hideimage, $dish_price);
				}

				$output .= '</div><!--posts_array-->';

				if (is_array($posts_array) && count($posts_array) > 0) {
					if (isset($custom_fields["_menu_footer"])) {
						$output .= "<div class='snillrik-menu-footer-text'>" . wp_kses_post($custom_fields["_menu_footer"][0]) . "</div>";
					} else {
						$output .= "<div class='snillrik-menu-footer-text'></div>";
					}
				}
			}
		};

		return $output . "</div>";
	}

	/**
	 * Short code for getting snillrik_restaurant_dishes to display in pretty list.
	 *
	 * @param array $atts
	 *        	shortcode attributes
	 * @param string $content
	 *        	content string.
	 * @return string list of snillrik_restaurant_dishes.
	 */
	function dishes($atts, $content = null)
	{
		// extract( shortcode_atts( array(), $atts ) ); not used yet.
		$categories = get_terms('dishes-category', 'orderby=count&order=DESC&hide_empty=1');
		$output = "";
		foreach ($categories as $category) :
			$posts_array = get_posts(array(
				'posts_per_page' => -1,
				'post_type' => SNILLRIKRESTAURANT_REWRITE_DISH,
				'order' => 'ASC',
				'taxonomy' => $category->taxonomy,
				'term' => $category->slug,
				'orderby' => 'dishes-category'
			));

			if (count($posts_array) > 0) {
				$output .= "<h4>" . esc_attr($category->name) . "</h4>";
				$output .= "<div class='snillrik_restaurant_dishlist_box'><ul>";

				foreach ($posts_array as $post) {
					$the_url = get_permalink($post->ID);
					$title = esc_attr($post->post_title);
					$output .= "<li><a href='$the_url'>$title</a></li>";
				}
				$output .= '</ul></div>';
			}
		endforeach;

		return $output;
	}
}
