<?php

function init_snillrik_restaurant_widgets(){
	register_widget( 'Snillrik_restaurant_dish_Widget' );
}
add_action( 'widgets_init', 'init_snillrik_restaurant_widgets' );


/**
 * Snillrik restaurant dish Widget widget class
 *
 * @since 2.8.0
 */
class Snillrik_restaurant_dish_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
		'snillrik_restaurant_dish_widget',
			esc_attr__( 'Restaurant Widget', SNILLRIKRESTAURANT_NAME),
			array( 'description' => esc_attr__( 'To display a tasty snillrik restaurant dish on your site.' ,SNILLRIKRESTAURANT_NAME) )
		);
	}

	function widget( $args, $instance ) {
		extract($args, EXTR_SKIP);
		$snillrik_restaurant_dish_id = isset($instance["snillrik_restaurant_dish_id"]) ? $instance["snillrik_restaurant_dish_id"] : false;

		if($snillrik_restaurant_dish_id){
			$post_custom = get_post_custom($snillrik_restaurant_dish_id);
			$post = get_post($snillrik_restaurant_dish_id);
			$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), "medium");
			$imgen  = '<img src="'.esc_url($thumb['0']).'" alt="'.esc_attr($post->post_title).'" />';
			$cashtype = get_option('snillrik_restaurant_cahstype');
    		$cashplace = get_option('snillrik_restaurant_cahsplace');
			$the_url = get_permalink($post->ID);

			echo $before_widget . "<div class='snillrik_restaurant_widgetbox'>";
			echo "<div class='snillrik_restaurant_widgetbox_image'><a href='$the_url'>".$imgen."</a></div>";
			if($cashplace == "After")
    			echo "<div class='snillrik_restaurant_widgetbox_price'>".esc_attr($post_custom["_snillrik_restaurant_dishprice"][0])."".esc_attr($cashtype)."</div>";
    		else
    			echo "<div class='snillrik_restaurant_widgetbox_price'>".esc_attr($cashtype)."".esc_attr($post_custom["_snillrik_restaurant_dishprice"][0])."</div>";
			echo "<div class='snillrik_restaurant_widgetbox_title'><a href='$the_url'><h4>".esc_attr($post->post_title)."</h4></a></div>";
			echo "<div class='snillrik_restaurant_widgetbox_description'>".wp_kses_post($post_custom["_snillrik_restaurant_dish_ingredients"][0])."</div>";
			echo "</div>" . $after_widget;
		}


		if ( is_admin()) {
			// Display All Links widget as such in the widgets screen
			echo $before_widget . $before_title . esc_attr_x('All snillrik_restaurant_dishes',SNILLRIKRESTAURANT_NAME) . $after_title . $after_widget;
			return;
		}
	}

	function form( $instance ) {
		$snillrik_restaurant_dish_id = isset( $instance['snillrik_restaurant_dish_id']) ? esc_attr( $instance['snillrik_restaurant_dish_id'] ) : '';

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

		$posts_array = get_posts( $args );

		?>
<p>
	<label for="<?php echo $this->get_field_id('snillrik_restaurant_dish_id'); ?>"><?php esc_html_e( 'Dishes:',SNILLRIKRESTAURANT_NAME ); ?>
	</label>
		<select id="<?php echo $this->get_field_id('snillrik_restaurant_dish_id'); ?>" name="<?php echo $this->get_field_name('snillrik_restaurant_dish_id'); ?>">
		<?php foreach($posts_array as $catta): ?>
			<option value="<?php echo $catta->ID; ?>" <?php echo $catta->ID == $snillrik_restaurant_dish_id ? "selected" : ""; ?>>
				<?php echo $catta->post_title; ?>
			</option>
		<?php endforeach; ?>
	</select>
</p>



		<?php
	}


	function update( $new_instance, $old_instance ) {

		$instance = array();
		$instance['snillrik_restaurant_dish_id'] = intval($new_instance['snillrik_restaurant_dish_id']);

		return $instance;
	}
}
?>
