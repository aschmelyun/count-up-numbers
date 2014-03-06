<?php
/**
 * Plugin Name: Count Up Numbers
 * Plugin URI: http://andrewschmelyun.com/count-up-numbers
 * Description: Uses CountUp.js and JQuery Waypoints to allow certain numbers to count-up after a div has come into the users' sight.
 * Version: 1.0
 * Author: Andrew Schmelyun
 * Author URI: http://andrewschmelyun.com
 */

//Load and enqueue custom css and jquery files for the plugin
add_action( 'wp_enqueue_scripts', 'countup_scripts');

function countup_scripts() {
	wp_register_style('countup_css', plugins_url('styles.css', __FILE__));
	wp_enqueue_style('countup_css');
	wp_enqueue_script( 'countup_jquery', plugins_url('countUp.js', __FILE__));
	wp_enqueue_script( 'countup_waypoints', plugins_url('waypoints.js', __FILE__));
}

//'Subscribe' input box output and shortcode
function countup_numbers_main() {

	$boxOne = get_option('boxOne');
	$boxTwo = get_option('boxTwo'); 
	$boxThree = get_option('boxThree'); 
	$boxFour = get_option('boxFour'); 
	$boxFive = get_option('boxFive');  

	$boxArrayBefore = Array($boxOne, $boxTwo, $boxThree, $boxFour, $boxFive);
	$boxArray = array_filter($boxArrayBefore);

	$countup_numbers_code = '<div id="countup-wrapper">';

	for ($i = 0; $i <= count($boxArray)-1; $i++) {
		$textAndTitle = explode(";", $boxArray[$i]);

		$countup_numbers_code .= '<div class="countup-block"><h3>' . $textAndTitle[0] . '</h3><h1 id="box' . $i . '">0</h1></div>';
	}

	$countup_numbers_code .= '</div>';

	return $countup_numbers_code;
}
add_shortcode('count-up-numbers', 'countup_numbers_main');


//Options for the plugin, as well as the hook for adding in a settings submenu item
add_action( 'admin_menu', 'countup_numbers_plugin_menu' );

function countup_numbers_plugin_menu() {
	add_submenu_page( 'options-general.php', 'Count Up Numbers Options', 'Count Up Numbers', 'manage_options', 'countup-numbers-options', 'countup_numbers_options' );
}

function countup_numbers_footer() { 
	$boxOne = get_option('boxOne');
	$boxTwo = get_option('boxTwo'); 
	$boxThree = get_option('boxThree'); 
	$boxFour = get_option('boxFour'); 
	$boxFive = get_option('boxFive');  

	$boxArrayBefore = Array($boxOne, $boxTwo, $boxThree, $boxFour, $boxFive);
	$boxArray = array_filter($boxArrayBefore);

	?>

	<script>
		$('#countup-wrapper').waypoint(function() {
			<?php 
			for ($i=0; $i <= count($boxArray)-1; $i++) {
				$textAndTitle = explode(";", $boxArray[$i]);
				echo 'var countUpNumbers' . $i . ' = new countUp("box' . $i .'", 0, ' . $textAndTitle[1] . ', 0, 3.0);' . "\r\n";
				echo 'countUpNumbers' . $i . '.start();';
			} ?>
		}, { offset: '95%' });
	</script>

<?php }
add_action('wp_footer', 'countup_numbers_footer');

function countup_numbers_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	} ?>

	<div class="wrap">
		<div id="icon-options-general" class="icon32">
			<br>
		</div>
		<h2>Count Up Numbers Options</h2>
		<form method="post" action="options.php">  
		<?php wp_nonce_field('update-options') ?>  
			<h3>Box titles and numbers - All are optional (Seperate title and number with a semi-colon)</h3>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="boxOne">Box One:</label></th>
					<td><input type="text" id="boxOne" size="48" name="boxOne" placeholder="Words In This Sentence;41" value="<?php echo get_option('boxOne'); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="boxTwo">Box Two:</label></th>
					<td><input type="text" id="boxTwo" size="48" name="boxTwo" placeholder="High Score In Galaga;3052" value="<?php echo get_option('boxTwo'); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="boxThree">Box Three:</label></th>
					<td><input type="text" id="boxThree" size="48" name="boxThree" placeholder="Florida Winter Temperature;79" value="<?php echo get_option('boxThree'); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="boxFour">Box Four:</label></th>
					<td><input type="text" id="boxFour" size="48" name="boxFour" placeholder="Ingredients In Bread;3" value="<?php echo get_option('boxFour'); ?>" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="boxFive">Box Five:</label></th>
					<td><input type="text" id="boxFive" size="48" name="boxFive" placeholder="Highest Score In Bowling;300" value="<?php echo get_option('boxFive'); ?>" /></td>
				</tr>
			</table>
            <p><br /><br /><input type="submit" class="button button-primary" name="Submit" value="Save Options" /></p>  
            <input type="hidden" name="action" value="update" />  
            <input type="hidden" name="page_options" value="boxOne, boxTwo, boxThree, boxFour, boxFive" />  
        </form>  
	</div>
<?php } ?>