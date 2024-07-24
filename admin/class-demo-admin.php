<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://https://fonts.google.com
 * @since      1.0.0
 *
 * @package    Demo
 * @subpackage Demo/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Demo
 * @subpackage Demo/admin
 * @author     QuangT <TuanLe30122003@gmail.com>
 */
class Demo_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/demo-admin.css', array(), $this->version, 'all');

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/demo-admin.js', array(), $this->version, false);

	}

	/**
	 * The options name to be used in this plugin
	 *
	 * @since  	1.0.0
	 * @access 	private
	 * @var  	string 		$option_name 	Option name of this plugin
	 */
	private $option_name = 'Demo_setting';

	/**
	 * Register the setting parameters
	 *
	 * @since  	1.0.0
	 * @access 	public
	 */
	public function register_demo_plugin_settings()
	{
		// Add a General section
		add_settings_section(
			$this->option_name . '_general',
			__('General', 'Demo'),
			array($this, $this->option_name . '_general_cb'),
			$this->plugin_name
		);
		// Add a boolean field
		add_settings_field(
			$this->option_name . '_bool',
			__('Type of articles list will be used ?', 'Demo'),
			array($this, $this->option_name . '_bool_cb'),
			$this->plugin_name,
			$this->option_name . '_general',
			array('label_for' => $this->option_name . '_bool')
		);
		// Add a numeric field
		add_settings_field(
			$this->option_name . '_number',
			__('Number setting', 'Demo'),
			array($this, $this->option_name . '_number_cb'),
			$this->plugin_name,
			$this->option_name . '_general',
			array('label_for' => $this->option_name . '_number')
		);
		// Register the boolean field
		register_setting($this->plugin_name, $this->option_name . '_bool', array($this, $this->option_name . '_sanitize_bool'));
		// Register the numeric field
		register_setting($this->plugin_name, $this->option_name . '_number', 'integer');
	}

	/**
	 * Render the text for the general section
	 *
	 * @since  	1.0.0
	 * @access 	public
	 */
	public function demo_setting_general_cb()
	{
		echo '<p>' . __('Please change the settings accordingly.', 'Demo') . '</p>';
	}

	/**
	 * Render the number input for this plugin
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function demo_setting_number_cb()
	{
		$val = get_option($this->option_name . '_number');
		echo '<input type="text" name="' . $this->option_name . '_number' . '" id="' . $this->option_name . '_number' . '" value="' . $val . '"> ' . __('(unity of measure)', 'Demo');
	}

	/**
	 * Render the radio input field for boolean option
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function demo_setting_bool_cb()
	{
		$val = get_option($this->option_name . '_bool');
		?>
<fieldset>
    <label>
        <input type="radio" name="<?php echo $this->option_name . '_bool' ?>"
            id="<?php echo $this->option_name . '_bool' ?>" value="true" <?php checked($val, 'true'); ?>>
        <?php _e('Scrolling list', 'Demo'); ?>
    </label>
    <br>
    <label>
        <input type="radio" name="<?php echo $this->option_name . '_bool' ?>" value="false"
            <?php checked($val, 'false'); ?>>
        <?php _e('Page list', 'Demo'); ?>
    </label>
</fieldset>
<?php
	}

	/**
	 * Include the setting page
	 *
	 * @since  1.0.0
	 * @access public
	 */
	function demo_init()
	{
		if (!current_user_can('manage_options')) {
			return;
		}
		include DEMO_PATH . 'admin/partials/demo-admin-display.php';

	}

	public function demo_plugin_setup_menu()
	{
		add_menu_page('demo settings', 'Settings Text Display', 'manage_options', 'demo', array($this, 'demo_init'), 'dashicons-welcome-learn-more');

	}

	public function test()
	{
		echo "TEST";
	}

}