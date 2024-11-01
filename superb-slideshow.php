<?php
/*
Plugin Name: Superb Slideshow
Plugin URI: http://www.gopiplus.com/work/2010/07/18/superb-slideshow/
Description: Superb Slideshow script that incorporates some of your most requested features all rolled into one. Each instance of a fade in slideshow on the page is completely independent of the other, with support for different features selectively enabled for each slideshow.  
Author: Gopi Ramasamy
Version: 12.7
Author URI: http://www.gopiplus.com/work/2010/07/18/superb-slideshow/
Donate link: http://www.gopiplus.com/work/2010/07/18/superb-slideshow/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: superb-slideshow
Domain Path: /languages
*/

function sswld_show() 
{
	$arr = array();
	$arr["width"] 		= get_option('sswld_width');
	$arr["height"] 		= get_option('sswld_height');
	$arr["filename"]	= "directory1";
	$arr["random"]		= get_option('sswld_random');	
	echo sswld_shortcode($arr);
}

add_shortcode( 'superb-slideshow', 'sswld_shortcode' );

function sswld_shortcode( $atts ) 
{
	$sswld_package  = "";
	$sswld_pp = "";

	//[superb-slideshow filename="directory1" width="400" height="300" random="Y"]
	if ( ! is_array( $atts ) )
	{
		return '';
	}
	
	if (isset($atts['width'])) {
		$width 	= $atts['width'];
	}
	else {
		$width 	= 200;
	}
	
	if (isset($atts['height'])) {
		$height 	= $atts['height'];
	}
	else {
		$height 	= 150;
	}
	
	if (isset($atts['filename'])) {
		$filename 	= $atts['filename'];
	}
	else {
		$filename 	= "directory1";
	}
	
	if (isset($atts['random'])) {
		$random 	= $atts['random'];
	}
	else {
		$random 	= "Y";
	}
	
	if($filename==""){$filename = "directory1";}
	switch ($filename) 
	{
		case "directory1":
			$location = get_option('sswld_dir1');
			break;
		case "directory2":
			$location = get_option('sswld_dir2');
			break;
		case "directory3":
			$location = get_option('sswld_dir3');
			break;
		default:
			$location = get_option('space_dir_1');
			break;
	}
	
	if(!is_numeric($width)){$width = 200;} 
	if(!is_numeric($height)){$height = 150;} 
	
	$pause 			= get_option('sswld_pause');
	$duration 		= get_option('sswld_duration');
	$cycles 		= get_option('sswld_cycles');
	$displaydesc 	= get_option('sswld_displaydesc');
	
	if(!is_numeric($pause)){$pause = 2500;}
	if(!is_numeric($duration)){$duration = 500;}
	if(!is_numeric($cycles)){$cycles = 0;}
	
	if(is_dir($location))
	{
		$siteurl = get_option('siteurl');
		if(substr($siteurl, -1) !== '/')
		{
			$siteurl = $siteurl . "/";
		}
		
		$f_dirHandle = opendir($location);
		while ($f_file = readdir($f_dirHandle)) 
		{
			$f_file_sm = $f_file;
			$f_file = strtoupper($f_file);
			if(!is_dir($f_file) && (strpos($f_file, '.JPG')>0 or strpos($f_file, '.GIF')>0 or strpos($f_file, '.PNG')>0 or strpos($f_file, '.JPEG')>0)) 
			{
				$path 	= $siteurl . $location . $f_file_sm;
				$link	= $path;
				$target	= "none";
				$title = substr($f_file_sm, 0, strrpos($f_file_sm, "."));
				$title = str_replace("."," ",$title);
				$title = str_replace("-"," ",$title);
				$title = str_replace("_"," ",$title);
		
				$sswld_package = $sswld_package .'["'.$path.'", "'.$link.'", "'.$target.'", "'.$title.'"],';
			}
		}
		
		if($random==""){$random = "Y";}
		if($random=="Y")
		{
			$sswld_package = explode("[", $sswld_package);
			shuffle($sswld_package);
			$sswld_package = implode("[", $sswld_package);
			$sswld_package = '[' . $sswld_package;
			$sswld_package = explode("[[", $sswld_package);
			$sswld_package = implode("[", $sswld_package); // ugly hack to get rid of stray [[
		}
		$sswld_package = substr($sswld_package,0,(strlen($sswld_package)-1));
		
		$filename = str_replace(".","",$filename);
		$filename = str_replace("-","",$filename);
		$filename = str_replace("_","",$filename);
		$filename = $filename . rand(10, 99);
		$sswld_pp = $sswld_pp . '<script type="text/javascript">';
		$sswld_pp = $sswld_pp . 'var sswldgallery=new sswldSlideShow({sswld_wrapperid: "'.$filename.'", sswld_dimensions: ['.$width.', '. $height.'], sswld_imagearray: ['. $sswld_package.'],sswld_displaymode: {type:"auto", pause:'.$pause.', cycles:'. $cycles.', wraparound:false},sswld_persist: false, sswld_fadeduration: "'.$duration.'", sswld_descreveal: "'.$displaydesc.'",sswld_togglerid: ""})';
		$sswld_pp = $sswld_pp . '</script>';
		$sswld_pp = $sswld_pp . '<div style="padding-top:5px;"></div>';
		$sswld_pp = $sswld_pp . '<div id="'.$filename.'"></div>';
		$sswld_pp = $sswld_pp . '<div style="padding-top:5px;"></div>';
	}
	else
	{
		$sswld_pp = "Directory not exists (". $location.")";
	}
	return $sswld_pp;
}

function sswld_install() 
{
	add_option('sswld_dir1', "wp-content/plugins/superb-slideshow/images/");
	add_option('sswld_dir2', "wp-content/plugins/superb-slideshow/images1/");
	add_option('sswld_dir3', "wp-content/plugins/superb-slideshow/images1/");	
	add_option('sswld_random', "Y");
	add_option('sswld_title', "Slideshow");
	add_option('sswld_width', "200");
	add_option('sswld_height', "150");
	add_option('sswld_pause', "2500");
	add_option('sswld_duration', "500");
	add_option('sswld_cycles', "0");
	add_option('sswld_displaydesc', "always");
}

function sswld_widget($args) 
{
	extract($args);
	echo $before_widget . $before_title;
	echo get_option('sswld_title');
	echo $after_title;
	sswld_show();
	echo $after_widget;
}

function sswld_admin_option() 
{
	?>
	<div class="wrap">
	  <div class="form-wrap">
		<div id="icon-edit" class="icon32 icon32-posts-post"></div>
		<h2><?php _e('Superb Slideshow', 'superb-slideshow'); ?></h2>
		<?php
		$sswld_dir1 	= get_option('sswld_dir1');
		$sswld_dir2 	= get_option('sswld_dir2');
		$sswld_dir3 	= get_option('sswld_dir3');
		$sswld_random 	= get_option('sswld_random');
		$sswld_title 	= get_option('sswld_title');
		$sswld_width 	= get_option('sswld_width');
		$sswld_height 	= get_option('sswld_height');
		$sswld_pause 	= get_option('sswld_pause');
		$sswld_duration = get_option('sswld_duration');
		$sswld_cycles 	= get_option('sswld_cycles');
		$sswld_displaydesc = get_option('sswld_displaydesc');
		
		if (isset($_POST['sswld_form_submit']) && $_POST['sswld_form_submit'] == 'yes')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('sswld_form_setting');
			
			$sswld_dir1 		= stripslashes(sanitize_text_field($_POST['sswld_dir1']));	
			$sswld_dir2 		= stripslashes(sanitize_text_field($_POST['sswld_dir2']));	
			$sswld_dir3 		= stripslashes(sanitize_text_field($_POST['sswld_dir3']));	
			$sswld_random 		= stripslashes(sanitize_text_field($_POST['sswld_random']));		
			$sswld_title 		= stripslashes(sanitize_text_field($_POST['sswld_title']));
			$sswld_width 		= stripslashes(intval($_POST['sswld_width']));
			$sswld_height 		= stripslashes(intval($_POST['sswld_height']));
			$sswld_pause 		= stripslashes(intval($_POST['sswld_pause']));
			$sswld_duration 	= stripslashes(intval($_POST['sswld_duration']));
			$sswld_cycles 		= stripslashes(intval($_POST['sswld_cycles']));
			$sswld_displaydesc 	= stripslashes(sanitize_text_field($_POST['sswld_displaydesc']));
			
			if(!is_numeric($sswld_width) || $sswld_width == 0) { $sswld_width = 175; }
			if(!is_numeric($sswld_height) || $sswld_height == 0) { $sswld_height = 150; }
			if(!is_numeric($sswld_pause) || $sswld_pause == 0) { $sswld_pause = 2500; }
			if(!is_numeric($sswld_duration) || $sswld_duration == 0) { $sswld_duration = 500; }
			if(!is_numeric($sswld_cycles) || $sswld_cycles == 0) { $sswld_cycles = 0; }
			
			if($sswld_displaydesc != "ondemand" && $sswld_displaydesc != "always")
			{
				$sswld_displaydesc = "ondemand";
			} 
			
			update_option('sswld_dir1', $sswld_dir1 );
			update_option('sswld_dir2', $sswld_dir2 );
			update_option('sswld_dir3', $sswld_dir3 );
			update_option('sswld_random', $sswld_random );
			update_option('sswld_title', $sswld_title );
			update_option('sswld_width', $sswld_width );
			update_option('sswld_height', $sswld_height );
			update_option('sswld_pause', $sswld_pause );
			update_option('sswld_duration', $sswld_duration );
			update_option('sswld_cycles', $sswld_cycles );
			update_option('sswld_displaydesc', $sswld_displaydesc );
			?>
			<div class="updated fade">
				<p><strong><?php _e('Details successfully updated.', 'superb-slideshow'); ?></strong></p>
			</div>
			<?php
		}
		?>
		
		<form name="sswld_form" method="post" action="#">
			<h3><?php _e('Image Directory Details', 'superb-slideshow'); ?></h3>	
			<label for="tag-title"><?php _e('Image Directory 1 (Default for widget)', 'superb-slideshow'); ?></label>
			<input name="sswld_dir1" type="text" value="<?php echo $sswld_dir1; ?>"  id="sswld_dir1" size="70" maxlength="200">
			<label for="tag-title"><?php _e('Image Directory 2', 'superb-slideshow'); ?></label>
			<input name="sswld_dir2" type="text" value="<?php echo $sswld_dir2; ?>"  id="sswld_dir2" size="70" maxlength="200">
			<label for="tag-title"><?php _e('Image Directory 3', 'superb-slideshow'); ?></label>
			<input name="sswld_dir3" type="text" value="<?php echo $sswld_dir3; ?>"  id="sswld_dir3" size="70" maxlength="200">
			<p><?php _e('Please enter your image directory.', 'superb-slideshow'); ?></p> 
			<p><?php _e('Default image directory :', 'superb-slideshow'); ?> wp-content/plugins/superb-slideshow/images/</p>
			<p><?php _e('Note: Dont upload your original images into default plugins folder. if you upload the images into plugins folder, you may lose the images when you update the plugin to next version. Thus upload your images in "wp-content/uploads/your-folder/" folder and use the folder path as per the example in "Image Directory" text box.', 'superb-slideshow'); ?></p> 
			
			<h3><?php _e('Widget Settings', 'superb-slideshow'); ?></h3>
			<label for="tag-title"><?php _e('Random', 'superb-slideshow'); ?></label>
			<select name="sswld_random" id="sswld_random">
				<option value='Y' <?php if($sswld_random == 'Y') { echo "selected='selected'" ; } ?>>Yes</option>
				<option value='N' <?php if($sswld_random == 'N') { echo "selected='selected'" ; } ?>>No</option>
			</select>
			<p><?php _e('Please select random display option.', 'superb-slideshow'); ?></p>
			<label for="tag-title"><?php _e('Title (Only for widget)', 'superb-slideshow'); ?></label>
			<input name="sswld_title" type="text" value="<?php echo $sswld_title; ?>"  id="sswld_title" maxlength="200">
			<p><?php _e('Please enter widget title.', 'superb-slideshow'); ?></p>
			<label for="tag-title"><?php _e('Width (Only for widget)', 'superb-slideshow'); ?></label>
			<input name="sswld_width" type="text" value="<?php echo $sswld_width; ?>"  id="sswld_width" maxlength="4">
			<p><?php _e('Please enter your slideshow width. This is only for widget option.', 'superb-slideshow'); ?> (Example: 175) </p>
			<label for="tag-title"><?php _e('Height (Only for widget)', 'superb-slideshow'); ?></label>
			<input name="sswld_height" type="text" value="<?php echo $sswld_height; ?>"  id="sswld_height" maxlength="4">
			<p><?php _e('Please enter your slideshow height. This is only for widget option.', 'superb-slideshow'); ?> (Example: 150) </p>
			
			<h3><?php _e('Slideshow Settings', 'superb-slideshow'); ?></h3>
			<label for="tag-title"><?php _e('Pause', 'superb-slideshow'); ?></label>
			<input name="sswld_pause" type="text" value="<?php echo $sswld_pause; ?>"  id="sswld_pause" maxlength="6">
			<p><?php _e('Please enter pause between slides.', 'superb-slideshow'); ?> (Example: 2500)</p>
			<label for="tag-title"><?php _e('Fade duration', 'superb-slideshow'); ?></label>
			<input name="sswld_duration" type="text" value="<?php echo $sswld_duration; ?>"  id="sswld_duration" maxlength="6">
			<p><?php _e('Please enter your fade duration. The duration of the fade effect when transitioning from one image to the next, in milliseconds.', 'superb-slideshow'); ?> (Example: 500)</p>
			<label for="tag-title"><?php _e('Cycles', 'superb-slideshow'); ?></label>
			<input name="sswld_cycles" type="text" value="<?php echo $sswld_cycles; ?>"  id="sswld_cycles" maxlength="1">
			<p><?php _e('The cycles option when set to 0 will cause the slideshow to rotate perpetually, <br />While any number larger than 0 means it will stop after N cycles.', 'superb-slideshow'); ?> (Example: 0)</p>
			<label for="tag-title"><?php _e('Display description', 'superb-slideshow'); ?></label>
			<select name="sswld_displaydesc" id="sswld_displaydesc">
				<option value='ondemand' <?php if($sswld_displaydesc == 'ondemand') { echo "selected='selected'" ; } ?>>On Demand</option>
				<option value='always' <?php if($sswld_displaydesc == 'always') { echo "selected='selected'" ; } ?>>Always</option>
			</select>
			<p><?php _e('On Demand = Show description when the user mouses over the slideshow. <br />Always = Always show description panel at the foot of the slideshow.', 'superb-slideshow'); ?></p>
		
			<div style="height:10px;"></div>
			<input type="hidden" name="sswld_form_submit" value="yes"/>
			<input name="sswld_submit" id="sswld_submit" class="button" value="<?php _e('Submit', 'superb-slideshow'); ?>" type="submit" />
			<a class="button" target="_blank" href="http://www.gopiplus.com/work/2010/07/18/superb-slideshow/"><?php _e('Help', 'superb-slideshow'); ?></a>
			<?php wp_nonce_field('sswld_form_setting'); ?>
		</form>
		</div>
		<h3><?php _e('Plugin configuration option', 'superb-slideshow'); ?></h3>
		<ol>
			<li><?php _e('Drag and drop the widget to your sidebar.', 'superb-slideshow'); ?></li>
			<li><?php _e('Add directly in to the theme using PHP code.', 'superb-slideshow'); ?></li>
			<li><?php _e('Add the plugin in the posts or pages using short code.', 'superb-slideshow'); ?></li>
		</ol>
	<p class="description"><?php _e('Check official website for more information', 'superb-slideshow'); ?> 
	<a target="_blank" href="http://www.gopiplus.com/work/2010/07/18/superb-slideshow/"><?php _e('click here', 'superb-slideshow'); ?></a></p>
	</div>
	<?php
}

function sswld_control()
{
	echo '<p><b>';
	_e('Superb Slideshow', 'superb-slideshow');
	echo '.</b> ';
	_e('Check official website for more information', 'superb-slideshow');
	?> <a target="_blank" href="http://www.gopiplus.com/work/2010/07/18/superb-slideshow/"><?php _e('click here', 'superb-slideshow'); ?></a></p><?php
}

function sswld_widget_init() 
{
	if(function_exists('wp_register_sidebar_widget')) 	
	{
		wp_register_sidebar_widget( 'superb-slideshow', __('Superb Slideshow', 'superb-slideshow'), 'sswld_widget');
	}
	if(function_exists('wp_register_widget_control')) 	
	{
		wp_register_widget_control( 'superb-slideshow', array( __('Superb Slideshow', 'superb-slideshow'), 'widgets'), 'sswld_control');
	} 
}

function sswld_deactivation() 
{
	delete_option('sswld_xml_file');
	delete_option('sswld_random');
	delete_option('sswld_title');
	delete_option('sswld_dir');
	delete_option('sswld_width');
	delete_option('sswld_height');
	delete_option('sswld_pause');
	delete_option('sswld_duration');
	delete_option('sswld_cycles');
	delete_option('sswld_displaydesc');
	delete_option('sswld_dir1');
	delete_option('sswld_dir2');
	delete_option('sswld_dir3');
}

function sswld_add_to_menu() 
{
	add_options_page( __('Superb Slideshow', 'superb-slideshow'), __('Superb Slideshow', 'superb-slideshow'),'manage_options', 'superb-slideshow','sswld_admin_option');  
}

if (is_admin()) 
{
	add_action('admin_menu', 'sswld_add_to_menu');
}

function sswld_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script( 'jquery');
		wp_enqueue_script( 'superb-slideshow', plugins_url().'/superb-slideshow/inc/superb-slideshow.js');
	}
}    
 
function sswld_textdomain() 
{
	  load_plugin_textdomain( 'superb-slideshow', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action('plugins_loaded', 'sswld_textdomain');
add_action('init', 'sswld_add_javascript_files');
add_action("plugins_loaded", "sswld_widget_init");
register_activation_hook(__FILE__, 'sswld_install');
register_deactivation_hook(__FILE__, 'sswld_deactivation');
add_action('init', 'sswld_widget_init');
?>