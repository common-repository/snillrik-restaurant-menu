<?php
defined('ABSPATH') or die('This script cannot be accessed directly.');
/**
 *
 * The settings page for the plugin.
 */
new Snillrik_restaurant_settings();

class Snillrik_restaurant_settings
{
	function __construct()
	{
		add_action('admin_menu', [$this, 'snillrik_restaurant_create_menu']);
	}

	public function snillrik_restaurant_create_menu()
	{
		add_menu_page(
			esc_attr__('Snillrik Restaurant'),
			esc_attr__('Snillrik Restaurant'),
			'administrator',
			SNILLRIKRESTAURANT_NAME,
			[$this, 'snillrik_restaurant_settings_page'],
			SILLRIKRESTAURANT_PLUGIN_URL . '/images/snillrik_icon.svg'
		);
		add_action('admin_init', [$this, 'snillrik_restaurant_settings']);
	}


	public function snillrik_restaurant_settings()
	{
		$sanitize_args_str = array(
			'type' => 'string',
			'sanitize_callback' => 'sanitize_text_field',
		);
		register_setting('snillrik_restaurant-settings-group', 'snillrik_restaurant_cahstype', $sanitize_args_str);
		register_setting('snillrik_restaurant-settings-group', 'snillrik_restaurant_decimals', $sanitize_args_str);
		register_setting('snillrik_restaurant-settings-group', 'snillrik_restaurant_cahsplace', $sanitize_args_str);
		register_setting('snillrik_restaurant-settings-group', 'snillrik_restaurant_ingredients', $sanitize_args_str);
	}

	public function snillrik_restaurant_settings_page()
	{
		$snillrik_logo = SILLRIKRESTAURANT_PLUGIN_URL . 'images/snillrik_logo_modern.svg';
?>
		<div class="wrap snillrik-main-wrap snillrik-restaurant-main-wrap">
			<div class="snillrik-main-left-side">
				<div class="snillrik-main-side-inner">
					<img src="<?php echo $snillrik_logo; ?>" alt="Snillrik logo" class="snillrik-logo" />
					<h1>Snillrik Restaurant</h1>
					<h3>A plugin for restaurants that need easy to use, often dynamic, menues that change often. Today's special or lunch offerings for instance.</h3>
					<div class="snillrik-restaurant-menu-admin-block">
						<div>
							<h2>Settings</h2>
							<h4>This is a plugin to make menus for restaurants.</h4>
							<p>It was first intended for lunch menus since it's often the same few snillrik_restaurant_dishes that are combiened for different days. So the idea is that you add snillrik_restaurant_dishes and the use them to create different menus.</p>
							<p>Once you have made the menues, you can just use shortchodes and widgets to make pretty restaurant wordpress sites.</p>
						</div>
					</div>
					<div class="snillrik-restaurant-menu-admin-block">
						<div>
							<h3>Filters</h3>

							<p>They default to snillrik_lm_menu and snillrik_lm_dish witch might not be tha pretty, but it's unique... So if you want the urls to be something like /dishes/ or /food/, use these.</p>
							<code>add_filter("snillrik_lm_rewrite_menu",function($thename){
								return "menue";
								},10,1);</code><br />
							<code>add_filter("snillrik_lm_rewrite_dish",function($thename){
								return "dish";
								},10,1);</code>
						</div>
						<div>
							<h3>Shortcodes</h3>

							<p><strong>[snillrik_restaurant_menu menuid="42" showcategory=1|0 hideimage=1|0 showcatdescription=1|0 linktitle=1|0 category="" orderby="menu_order"]</strong></p>
							<p>If you want to not show the category text chose 0 and if you want to link to the dish page chose 1 etc. menu_order is the order param set in admin on each dish. the orderby can be set to common wp orderbys too, like date or title.</p>

							<p><strong>[snillrik_restaurant_dishes]</strong></p>
							<p>A simple list of dishes sorted under categories. Intended to be a side menu etc.</p>
						</div>
					</div>
					<form method="post" action="options.php">
						<?php settings_fields('snillrik_restaurant-settings-group'); ?>
						<?php do_settings_sections('snillrik_restaurant-settings-group'); ?>
						<div class="snillrik-restaurant-menu-admin-block">
							<div>
								<h4>Currency</h4>
								Here's where your currency. So anything like $, Euro, :- or BITCOIN.<br />
								<input name="snillrik_restaurant_cahstype" type=" text" value="<?php echo esc_attr(get_option('snillrik_restaurant_cahstype')); ?>" />
							</div>
							<div>
								<h4>Number of decimals</h4>
								How many decimals to show. 0, 1 or 2. 0 is default.<br />
								<select name="snillrik_restaurant_decimals" id="snillrik_restaurant_decimals">
									<?php
									$current_decimals = intval(get_option('snillrik_restaurant_decimals'));
									foreach (array(0, 1, 2, 3, 4) as $rullcurr) {
										if ($current_decimals == $rullcurr)
											echo "<option value='$rullcurr' selected=selected>$rullcurr</option>";
										else
											echo "<option value='$rullcurr'>$rullcurr</option>";
									}
									?>
								</select>

							</div>
							<div>
								<h4>Place</h4>
								Where to place the sign or text, before or after the cashtype. $19.95 or 199SEK<br />
								<select name="snillrik_restaurant_cahsplace" id="snillrik_restaurant_cahsplace">
									<?php
									$current_beforafter = esc_attr(get_option('snillrik_restaurant_cahsplace'));
									foreach (array("Before", "After") as $rullcurr) {
										if ($current_beforafter == $rullcurr)
											echo "<option value='$rullcurr' selected=selected>$rullcurr</option>";
										else
											echo "<option value='$rullcurr'>$rullcurr</option>";
									}
									?>
								</select>
							</div>
							<div>
								<h4>Text</h4>
								The text over ingredients on singel page (To not use this, just leave blank).<br />
								<input name="snillrik_restaurant_ingredients" type=" text" value="<?php echo esc_attr(get_option('snillrik_restaurant_ingredients')); ?>" />
							</div>


						</div>
						<?php submit_button(); ?>
					</form>

				</div>
			</div>
		</div>

<?php }
} ?>