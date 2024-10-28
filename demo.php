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

$option_name = 'Demo_setting';

function display_custom_text()
{
	$url = "https://trealet.com/api/my-trealets/1232";
	$is_dark_mode = get_option("Demo_setting_bool1") == "true" ? true : false; // is dark $list_type_option
	$list_type_option = get_option("Demo_setting_bool");

	$theme_background = $is_dark_mode ? "dark-theme-background" : "light-theme-background";
	$theme_item = $is_dark_mode ? "item-dark-theme" : "";
	$white_color = $is_dark_mode ? "name-color-darkmode" : "";
	$white_border = $is_dark_mode ? "white-border" : "";

	$article_count_on_page = 10;

	$data = get_data($url);
	apply_icon_library();

	echo "<div class='cover'>"; // The cover div will be the overall cover for this component

	echo "<div class='overlay'>";

	echo "<div class='login-phrase'>
		<h1>Enter user ID to open Trealer data</h1>
		<div class='id-input'>
			<input placeholder='Enter ID here' type='number'/>
			<div class=''><ion-icon name='enter-outline'></ion-icon></div>
		</div>		
	</div>";

	echo "</div>";

	echo "<div class='bot-cover {$theme_background}'>";

	echo "<div class='option-section {$theme_background}'>"; // The left part of the component

	echo "<div class='header {$white_color}'>
			<div class='user'>
				<div class='user_info'>
					<h1>QuangTuann</h1>
				</div>
			</div>
				<div class='log_out'>
					<ion-icon name='log-out-outline'></ion-icon>
				</div>
	</div>";

	search_bar();

	// sort_option_list();
	display_article_list($data, $list_type_option, $article_count_on_page);

	echo "</div>";

	// The right side below 

	echo "<div class='option_content {$theme_item}'>";

	display_articles($data); 	// This is the content of for each article on the list

	echo "</div>";

	echo "</div>";


	// TESTING

	echo "<div>" . $is_dark_mode . "</div>";

	echo "</div>";

	echo "</div>";
}

function display_articles($data)
{
	foreach ($data as $key => $value) {

		$jsonToOject = json_decode($value['json'], true);

		if (is_array($jsonToOject)) {

			$trealet = $jsonToOject['trealet'];
			$items = $trealet['items'];

			echo "<h2 class='content-title-name' data-id='$key'>" . $value['title'] . "</h2>";
			// echo "<div class='content-wrap'>";

			foreach ($items as $elements) {
				foreach ($elements as $key1 => $value1) {
					if ($key1 == "video") {
						echo "<div class='content_unit' data-id='$key'>";

						$videoTitle = $value1['video_title'] != "" ? $value1['video_title'] : "(User didn't add title yet)";

						echo "<div class='content_unit_title'>";
						echo "<h3>" . $videoTitle . "</h3>";
						echo "<p>" . $value1['description_video'] . "</p>";
						echo "</div>";
						$linkVideo = "https://trealet.com" . substr($value1['video_src'], 2);
						$videoWidth = 250;
						$videoHeight = 250;
						$videoType = "video/mp4";
						echo "<div><video width='$videoWidth' height='$videoHeight' controls muted><source src='$linkVideo' type='$videoType'>Video not found</video></div>";
						echo "</div>";

					} else if ($key1 == "picture") {
						echo "<div class='content_unit' data-id='$key'>";
						$pic_title = $value1['picture_title'] != "" ? $value1['picture_title'] : "(User didn't add title yet)";


						echo "<div class='content_unit_title'>";
						echo "<h3>" . $pic_title . "</h3>";
						echo "<p>" . $value1['description_image'] . "</p>";
						echo "</div>";
						$linkImage = "https://trealet.com" . substr($value1['picture_src'], 2);
						echo "<img src='$linkImage' class='picture'>";
						echo "</div>";

					} else if ($key1 == "audio") {
						echo "<div class='content_unit' data-id='$key'>";

						echo "<div class='content_unit_title'>";
						echo "<h3>" . $value1['audio_title'] . "</h3>";
						echo "<p>" . $value1['description_audio'] . "</p>";
						echo "</div>";
						$linkAudio = "https://trealet.com" . substr($value1['audio_src'], 2);
						echo "<audio controls>
								<source src='$linkAudio' class='audio'>
							</audio>";
						echo "</div>";

					} else {
						// echo 'Other media';
					}
				}
			}
			// echo "</div>";
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
					<input class="search-input" placeholder="Search for article ..." />
					<div class="search-icon"><ion-icon name="search-outline"></ion-icon></div>
				</div>

			';
}

function change_class()
{
	return 0;
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
			// echo "<li class='title' data-id='$key' data-active='$activeStatus'><span>" . str_pad($count++, 2, '0', STR_PAD_LEFT) . "</span><p>" . 'Tieu de cua du lieu' . "</p></li>";

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