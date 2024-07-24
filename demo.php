<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://fonts.google.com
 * @since             1.0.0
 * @package           Demo
 *
 * @wordpress-plugin
 * Plugin Name:       Additional function of displaying specific information
 * Plugin URI:        https://https://fonts.google.com
 * Description:       Demo version
 * Version:           1.0.0
 * Author:            QuangT
 * Author URI:        https://https://fonts.google.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       DEMO
 * Domain Path:       /languages
 */

if (!defined('WPINC')) {
	die;
}

define('DEMO_VERSION', '1.0.0');
!defined('DEMO_PATH') && define('DEMO_PATH', plugin_dir_path(__FILE__));

function activate_demo()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-demo-activator.php';
	Demo_Activator::activate();
}

function deactivate_demo()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-demo-deactivator.php';
	Demo_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_demo');
register_deactivation_hook(__FILE__, 'deactivate_demo');

require plugin_dir_path(__FILE__) . 'includes/class-demo.php';

function run_demo()
{

	$plugin = new Demo();
	$plugin->run();

}
run_demo();

function display_custom_text()
{
	$url = "https://trealet.com/api/my-trealets/1232";
	$list_type_option = get_option("Demo_setting_bool");
	$article_count_on_page = 10;


	$data = get_data($url);
	apply_icon_library();

	echo "<div class='cover'>"; // The cover div will be the overall cover for this component

	echo "<div class='option-section'>"; // The left part of the component

	echo "<div class='header'>Article list</div>";

	sort_option_list();
	display_article_list($data, $list_type_option, $article_count_on_page);

	echo "</div>";

	// The right side below 

	echo "<div class='option_content'>";

	search_bar();

	display_articles($data); 	// This is the content of for each article on the list

	echo "</div>";

	echo "</div>";


	// TESTING

	echo "<div>" . $list_type_option . "</div>";
}

function display_articles($data)
{
	foreach ($data as $key => $value) {

		$jsonToOject = json_decode($value['json'], true);

		if (is_array($jsonToOject)) {

			$trealet = $jsonToOject['trealet'];
			$items = $trealet['items'];
			echo "<h2 class='content-title-name' data-id='$key'>" . $value['title'] . "</h2>";

			foreach ($items as $elements) {
				foreach ($elements as $key1 => $value1) {

					echo "<div class='content_unit' data-id='$key'>";

					if ($key1 == "video") {
						echo "<h3>" . $value1['video_title'] . "</h3>";
						echo "<p>" . $value1['description_video'] . "</p>";
						$linkVideo = "https://trealet.com" . substr($value1['video_src'], 2);
						$videoWidth = 600;
						$videoHeight = 450;
						$videoType = "video/mp4";
						echo "<video width='$videoWidth' height='$videoHeight' controls muted><source src='$linkVideo' type='$videoType'>Video not found</video><br>";
					} else if ($key1 == "picture") {
						echo "<h3>" . $value1['picture_title'] . "</h3>";
						echo "<p>" . $value1['description_image'] . "</p>";
						$linkImage = "https://trealet.com" . substr($value1['picture_src'], 2);
						echo "<img src='$linkImage' class='picture'>";
					} else if ($key1 == "audio") {
						echo "<h3>" . $value1['audio_title'] . "</h3>";
						echo "<p>" . $value1['description_audio'] . "</p>";
						$linkAudio = "https://trealet.com" . substr($value1['audio_src'], 2);
						echo "<audio controls>
								<source src='$linkAudio' class='audio'>
							</audio>";
					} else {
						echo 'Other media';
					}

					echo "</div>";
					// echo "<br>";
				}
			}
		}
	}
}

function get_data($url)
{
	$response = wp_remote_get($url);

	// Function of PLUGIN

	// echo $response;

	if (!is_wp_error($response)) {
		$body = wp_remote_retrieve_body($response);
		$data = json_decode($body, true);

		return $data;
	}

	echo "<div>ERROR !!!!</div>";
}

function apply_icon_library()
{
	echo "<script type='module' src='https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js'></script>
			<script nomodule src='https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js'></script>";
}

function search_bar()
{
	// Search bar element

	echo '
				<div class="search_bar">
					<div class="input-group">
						<label class="label"></label>
						<input autocomplete="off" name="title_search" id="title_search" class="input" type="email" placeholder="Search for article ">
						<button id="search_button">FIND</button>
						<button id="remove_button" disabled="true">RESET</button>
						</div>
				</div>
			';
}

function sort_option_list()
{
	echo "<div class='sort-option'> 
				<h2>Sort by</h2>
				<ul>
					<li><ion-icon name='text-sharp'></ion-icon><span>A - Z</span></li>
					<li><ion-icon name='text-sharp'></ion-icon><span>Z - A</span></li>
					<li><ion-icon name='trending-down-sharp'></ion-icon><span>Number of items</span></li>
				</ul>
			</div>"; // Data sorting options
}

function display_article_list($data, $list_type_option, $article_count_on_page)
{

	$count = 1;

	if (!$data) {
		echo "<div>ERROR with data</div>";
		return 0;
	}

	if ($list_type_option == "true") {
		echo "<ul class='article_list_scroll'>"; // This is the list of articles 


		foreach ($data as $key => $value) {

			$activeStatus = ($key == sizeof($data) - 1) ? true : false;

			echo "<li class='title' data-id='$key' data-active='$activeStatus'><span>" . str_pad($count++, 2, '0', STR_PAD_LEFT) . "</span><p>" . $value['title'] . "</p></li>";
		}

		echo "</ul>";
	} else {
		// echo "Page list";

		$page_count = count($data) / $article_count_on_page + 1;

		$current_page = 1; // add this to a parameter of ul elements

		echo "<ul class='article_list_page'>";

		foreach ($data as $key => $value) {

			$activeStatus = ($key == sizeof($data) - 1) ? true : false;

			echo "<li class='title' data-id='$key' data-active='$activeStatus'><span>" . str_pad($count++, 2, '0', STR_PAD_LEFT) . "</span><p>" . $value['title'] . "</p></li>";
		}

		echo "</ul>";

		echo "<ul class='page_option'>";

		for ($i = 1; $i <= $page_count; $i++) {
			echo "<li>" . $i . "</li>";
		}

		echo "</ul>";
	}

}

// Đăng ký hàm để được gọi từ một shortcode
add_shortcode('display_text', 'display_custom_text');


?>