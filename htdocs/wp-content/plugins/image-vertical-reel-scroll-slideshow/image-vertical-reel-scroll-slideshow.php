<?php
/*
Plugin Name: Image vertical reel scroll slideshow
Plugin URI: http://www.gopiplus.com/work/2011/05/30/wordpress-plugin-image-vertical-reel-scroll-slideshow/
Description: Image vertical reel scroll slideshow wordpress plugin will create the vertical scroll slideshow on the website. This will create the slideshow like reel. The images will scroll one by one from bottom to top. Each slide can be optionally hyper linked.
Author: Gopi Ramasamy
Version: 8.3
Author URI: http://www.gopiplus.com/work/
Donate link: http://www.gopiplus.com/work/2011/05/30/wordpress-plugin-image-vertical-reel-scroll-slideshow/
Tags: vertical, image, reel, scroll, slideshow, gallery
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: image-vertical-reel-scroll-slideshow
Domain Path: /languages
*/

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb, $wp_version;
define("WP_ivrss_TABLE", $wpdb->prefix . "ivrss_plugin");
define('WP_ivrss_FAV', 'http://www.gopiplus.com/work/2011/05/30/wordpress-plugin-image-vertical-reel-scroll-slideshow/');

if ( ! defined( 'WP_ivrss_BASENAME' ) )
	define( 'WP_ivrss_BASENAME', plugin_basename( __FILE__ ) );
	
if ( ! defined( 'WP_ivrss_PLUGIN_NAME' ) )
	define( 'WP_ivrss_PLUGIN_NAME', trim( dirname( WP_ivrss_BASENAME ), '/' ) );
	
if ( ! defined( 'WP_ivrss_PLUGIN_URL' ) )
	define( 'WP_ivrss_PLUGIN_URL', plugins_url() . '/' . WP_ivrss_PLUGIN_NAME );
	
if ( ! defined( 'WP_ivrss_ADMIN_URL' ) )
	define( 'WP_ivrss_ADMIN_URL', admin_url() . 'options-general.php?page=image-vertical-reel-scroll-slideshow' );

function ivrss() 
{
	global $wpdb;
	$ivrss_html = "";
	$ivrss_js = "";
	$ivrss_speed = "2";
	$ivrss_waitseconds = "2";
	
	$ivrss_scrollercount = get_option('ivrss_scrollercount');
	$ivrss_scrollerheight = get_option('ivrss_scrollerheight');
	$ivrss_type = get_option('ivrss_type');
	$ivrss_random = get_option('ivrss_random');
	
	$ivrss_speed = get_option('ivrss_speed');
	$ivrss_waitseconds = get_option('ivrss_waitseconds');
	
	if(!is_numeric($ivrss_speed)) { $ivrss_speed = 2; }
	if(!is_numeric($ivrss_waitseconds)) { $ivrss_waitseconds = 2; }
	
	if(!is_numeric($ivrss_scrollerheight))
	{
		$ivrss_scrollerheight = 50;
	}
	if(!is_numeric($ivrss_scrollercount))
	{
		$ivrss_scrollercount = 5;
	}
	if($ivrss_type == "")
	{
		$ivrss_type = "Please enter gallery type";
	}
	
	$sSql = "select * from ".WP_ivrss_TABLE." where ivrss_status='YES' and ivrss_type='$ivrss_type'";
	if($ivrss_random == "YES")
	{
	 	$sSql  = $sSql . " ORDER BY rand()";
	}
	else
	{
		$sSql  = $sSql . " ORDER BY ivrss_order";
	}
	
	$data = $wpdb->get_results($sSql);

	if ( ! empty($data) ) 
	{
		$ivrss_count = 0;
		foreach ( $data as $data ) 
		{
			$ivrss_path = $data->ivrss_path;
			$ivrss_link = $data->ivrss_link;
			$ivrss_target = $data->ivrss_target;

			$dis_height = $ivrss_scrollerheight."px";
			$ivrss_html = $ivrss_html . "<div class='cas_div' style='height:$dis_height;padding:2px 0px 2px 0px;'>"; 
			$ivrss_html = $ivrss_html . "<a style='text-decoration:none' target='$ivrss_target' class='cas_div' href='$ivrss_link'><img border='0' src='$ivrss_path'></a>";
			$ivrss_html = $ivrss_html . "</div>";
			
			$ivrss_js = $ivrss_js . "ivrss_array[$ivrss_count] = '<div class=\'cas_div\' style=\'height:$dis_height;padding:2px 0px 2px 0px;\'><a style=\'text-decoration:none\'  target=\'$ivrss_target\' href=\'$ivrss_link\'><img border=\'0\' src=\'$ivrss_path\'></a></div>'; ";	
			
			$ivrss_count++;
		}
	
		$ivrss_scrollerheight = $ivrss_scrollerheight + 4;
		if($ivrss_count >= $ivrss_scrollercount)
		{
			$ivrss_count = $ivrss_scrollercount;
			$ivrss_height = ($ivrss_scrollerheight * $ivrss_scrollercount);
		}
		else
		{
			$ivrss_count = $ivrss_count;
			$ivrss_height = ($ivrss_count*$ivrss_scrollerheight);
		}
		$ivrss_height1 = $ivrss_scrollerheight."px";
		?>
		<div style="padding-top:8px;padding-bottom:8px;">
			<div style="text-align:left;vertical-align:middle;text-decoration: none;overflow: hidden; position: relative; margin-left: 1px; height: <?php echo $ivrss_height1; ?>;" id="ivrss_holder1">
				<?php echo $ivrss_html; ?>
			</div>
		</div>
		<script type="text/javascript">
        var ivrss_array	= new Array();
        var ivrss_obj	= '';
        var ivrss_scrollPos 	= '';
        var ivrss_numScrolls	= '';
        var ivrss_heightOfElm = '<?php echo $ivrss_scrollerheight; ?>';
        var ivrss_numberOfElm = '<?php echo $ivrss_count; ?>';
		var ivrss_speed = '<?php echo $ivrss_speed; ?>';
		var ivrss_waitseconds = '<?php echo $ivrss_waitseconds; ?>';
        var ivrss_scrollOn 	= 'true';
        function ivrss_createscroll() 
        {
            <?php echo $ivrss_js; ?>
            ivrss_obj	= document.getElementById('ivrss_holder1');
            ivrss_obj.style.height = (ivrss_numberOfElm * ivrss_heightOfElm) + 'px';
            ivrss_content();
        }
        </script>
        <script type="text/javascript">
        ivrss_createscroll();
        </script>
		<?php
	}
	else
	{
		echo "<div style='padding-bottom:5px;padding-top:5px;'>No data available fot this type : $ivrss_type</div>";
	}
}

function ivrss_install() 
{
	global $wpdb;
	
	if($wpdb->get_var("show tables like '". WP_ivrss_TABLE . "'") != WP_ivrss_TABLE) 
	{
		$sSql = "CREATE TABLE IF NOT EXISTS `". WP_ivrss_TABLE . "` (";
		$sSql = $sSql . "`ivrss_id` INT NOT NULL AUTO_INCREMENT ,";
		$sSql = $sSql . "`ivrss_path` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,";
		$sSql = $sSql . "`ivrss_link` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,";
		$sSql = $sSql . "`ivrss_target` VARCHAR( 50 ) NOT NULL ,";
		$sSql = $sSql . "`ivrss_title` VARCHAR( 500 ) NOT NULL ,";
		$sSql = $sSql . "`ivrss_order` INT NOT NULL ,";
		$sSql = $sSql . "`ivrss_status` VARCHAR( 10 ) NOT NULL ,";
		$sSql = $sSql . "`ivrss_type` VARCHAR( 100 ) NOT NULL ,";
		$sSql = $sSql . "`ivrss_extra1` VARCHAR( 100 ) NOT NULL ,";
		$sSql = $sSql . "`ivrss_extra2` VARCHAR( 100 ) NOT NULL ,";
		$sSql = $sSql . "`ivrss_date` datetime NOT NULL default '0000-00-00 00:00:00' ,";
		$sSql = $sSql . "PRIMARY KEY ( `ivrss_id` )";
		$sSql = $sSql . ") ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
		$wpdb->query($sSql);
		
		$IsSql = "INSERT INTO ". WP_ivrss_TABLE . " (ivrss_path, ivrss_link, ivrss_target, ivrss_title, ivrss_order, ivrss_status, ivrss_type, ivrss_date)"; 
		
		$sSql = $IsSql . " VALUES ('".WP_ivrss_PLUGIN_URL."/images/250x167_1.jpg', '#', '_self', 'Image 1', '1', 'YES', 'GROUP1', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
		
		$sSql = $IsSql . " VALUES ('".WP_ivrss_PLUGIN_URL."/images/250x167_2.jpg' ,'#', '_self', 'Image 2', '2', 'YES', 'GROUP1', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
		
		$sSql = $IsSql . " VALUES ('".WP_ivrss_PLUGIN_URL."/images/250x167_3.jpg', '#', '_self', 'Image 3', '1', 'YES', 'Widget', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);
		
		$sSql = $IsSql . " VALUES ('".WP_ivrss_PLUGIN_URL."/images/250x167_4.jpg', '#', '_self', 'Image 4', '2', 'YES', 'Widget', '0000-00-00 00:00:00');";
		$wpdb->query($sSql);

	}
	add_option('ivrss_title', "Vertical Slideshow");
	add_option('ivrss_scrollercount', "2");
	add_option('ivrss_scrollerheight', "170");
	add_option('ivrss_random', "YES");
	add_option('ivrss_type', "GROUP1");
	
	add_option( 'ivrss_speed', "2" );
	add_option( 'ivrss_waitseconds', "2" );
}

function ivrss_control() 
{
	echo '<p><b>';
	_e('Image vertical reel scroll slideshow', 'image-vertical-reel-scroll-slideshow');
	echo '.</b> ';
	_e('Check official website for more information', 'image-vertical-reel-scroll-slideshow');
	?> <a target="_blank" href="<?php echo WP_ivrss_FAV; ?>"><?php _e('click here', 'image-vertical-reel-scroll-slideshow'); ?></a></p><?php
}

function ivrss_widget($args) 
{
	extract($args);
	echo $before_widget . $before_title;
	echo get_option('ivrss_Title');
	echo $after_title;
	ivrss();
	echo $after_widget;
}

function ivrss_admin_options() 
{
	global $wpdb;
	$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
	switch($current_page)
	{
		case 'edit':
			include('pages/image-management-edit.php');
			break;
		case 'add':
			include('pages/image-management-add.php');
			break;
		case 'set':
			include('pages/image-setting.php');
			break;
		default:
			include('pages/image-management-show.php');
			break;
	}
}

add_shortcode( 'ivrss-gallery', 'ivrss_shortcode' );

function ivrss_shortcode( $atts ) 
{
	global $wpdb;
	
	$ivrss = "";
	$ivrss_html = "";
	$ivrss_js = "";
	$ivrss_speed = 2;
	$ivrss_waitseconds = 2;
	
	// New code
	//[ivrss-gallery type="GROUP1" display="2" height="170" random="YES"]
	//[ivrss-gallery type="GROUP1" display="2" height="170" random="YES" speed="2" waitseconds="2"]
	if ( ! is_array( $atts ) ) { return ''; }
	$ivrss_type = $atts['type'];
	$ivrss_scrollercount = $atts['display'];
	$ivrss_scrollerheight = $atts['height'];
	$ivrss_random = $atts['random'];
	
	if(isset($atts['speed']))
	{
		$ivrss_speed = $atts['speed'];
	}

	if(isset($atts['waitseconds']))
	{
		$ivrss_waitseconds = $atts['waitseconds'];
	}
	
	if(!is_numeric($ivrss_speed)) { $ivrss_speed = 2; }
	if(!is_numeric($ivrss_waitseconds)) { $ivrss_waitseconds = 2; }
	
	if(!is_numeric(@$ivrss_scrollercount)) { @$ivrss_scrollercount = 2 ;}
	if(!is_numeric(@$ivrss_scrollerheight)) { @$ivrss_scrollerheight = 100; }
	
	$sSql = "select * from ".WP_ivrss_TABLE." where 1=1";
	if($ivrss_type <> ""){ $sSql = $sSql . " and ivrss_type='".$ivrss_type."'"; }
	if($ivrss_random == "YES"){ $sSql = $sSql . " ORDER BY RAND()"; }else{ $sSql = $sSql . " ORDER BY ivrss_order"; }
	
	$data = $wpdb->get_results($sSql);
	
	if ( ! empty($data) ) 
	{
		$ivrss_count = 0;
		foreach ( $data as $data ) 
		{
			$ivrss_path = $data->ivrss_path;
			$ivrss_link = $data->ivrss_link;
			$ivrss_target = $data->ivrss_target;

			$dis_height = $ivrss_scrollerheight."px";
			
			$ivrss_html = $ivrss_html . "<div class='cas_div' style='height:$dis_height;padding:2px 0px 2px 0px;'>"; 
			$ivrss_html = $ivrss_html . "<a style='text-decoration:none' target='$ivrss_target' class='cas_div' href='$ivrss_link'><img border='0' src='$ivrss_path' /></a>";
			$ivrss_html = $ivrss_html . "</div>";
			
			$ivrss_js = $ivrss_js . "ivrss_array[$ivrss_count] = '<div class=\'cas_div\' style=\'height:$dis_height;padding:2px 0px 2px 0px;\'><a style=\'text-decoration:none\' target=\'$ivrss_target\' href=\'$ivrss_link\'><img border=\'0\' src=\'$ivrss_path\'></a></div>'; ";	
			
			$ivrss_count++;
		}
	
		$ivrss_scrollerheight = $ivrss_scrollerheight + 4;
		if($ivrss_count >= $ivrss_scrollercount)
		{
			$ivrss_count = $ivrss_scrollercount;
			$ivrss_height = ($ivrss_scrollerheight * $ivrss_scrollercount);
		}
		else
		{
			$ivrss_count = $ivrss_count;
			$ivrss_height = ($ivrss_count*$ivrss_scrollerheight);
		}
		$ivrss_height1 = $ivrss_scrollerheight."px";
		
		$ivrss_pluginurl = get_option('siteurl') . "/wp-content/plugins/image-vertical-reel-scroll-slideshow";
		
		$ivrss = $ivrss .'<div style="padding-top:8px;padding-bottom:8px;">';
			$ivrss = $ivrss .'<div style="text-align:left;vertical-align:middle;text-decoration: none;overflow: hidden; position: relative; margin-left: 1px; height:'.$ivrss_height1.'" id="ivrss_holder2">';
				$ivrss = $ivrss . $ivrss_html;
			$ivrss = $ivrss .'</div>';
		$ivrss = $ivrss .'</div>';
		//$ivrss = $ivrss .'<script type="text/javascript" src="'.$ivrss_pluginurl.'/image-vertical-reel-scroll-slideshow.js"><script>';
		
        $ivrss = $ivrss .'<script type="text/javascript">' ;
			$ivrss = $ivrss .'var ivrss_array	= new Array();' ;
			$ivrss = $ivrss .'var ivrss_obj	= "";' ;
			$ivrss = $ivrss .'var ivrss_scrollPos 	= "";' ;
			$ivrss = $ivrss .'var ivrss_numScrolls	= "";' ;
			$ivrss = $ivrss .'var ivrss_heightOfElm = "'.$ivrss_scrollerheight.'";' ;
			$ivrss = $ivrss .'var ivrss_numberOfElm = "'.$ivrss_count.'";' ;
			$ivrss = $ivrss .'var ivrss_speed = "'.$ivrss_speed.'";' ;
			$ivrss = $ivrss .'var ivrss_waitseconds = "'.$ivrss_waitseconds.'";' ;
			$ivrss = $ivrss .'var ivrss_scrollOn 	= "true";' ;
			$ivrss = $ivrss .'function ivrss_createscroll()' ;
			$ivrss = $ivrss .'{' ;
				$ivrss = $ivrss . $ivrss_js; ;
				$ivrss = $ivrss .'ivrss_obj	= document.getElementById("ivrss_holder2");' ;
				$ivrss = $ivrss .'ivrss_obj.style.height = (ivrss_numberOfElm * ivrss_heightOfElm) + "px";' ;
				$ivrss = $ivrss .'ivrss_content();' ;
			$ivrss = $ivrss .'}' ;
        $ivrss = $ivrss .'</script>' ;
        $ivrss = $ivrss .'<script type="text/javascript">' ;
        	$ivrss = $ivrss .'ivrss_createscroll();' ;
        $ivrss = $ivrss .'</script>' ;
	}
	else
	{
		$ivrss = __('No image availabe for the mentioned group! please check the short code.', 'image-vertical-reel-scroll-slideshow');
	}
	return $ivrss;
}

function ivrss_add_to_menu() 
{
	if (is_admin()) 
	{
		add_options_page(__('Image vertical reel scroll slideshow', 'image-vertical-reel-scroll-slideshow'), 
					__('Image vertical reel scroll slideshow', 'image-vertical-reel-scroll-slideshow'), 'manage_options', "image-vertical-reel-scroll-slideshow", 'ivrss_admin_options' );
	}
}

function ivrss_init()
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget('Image-vertical-reel-scroll-slideshow', __('Image vertical reel scroll slideshow', 'image-vertical-reel-scroll-slideshow'), 'ivrss_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control('Image-vertical-reel-scroll-slideshow', array(__('Image vertical reel scroll slideshow', 'image-vertical-reel-scroll-slideshow'), 'widgets'), 'ivrss_control');
	} 
}

function ivrss_deactivation() 
{
	// No action required.
}

function ivrss_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script( 'image-vertical-reel-scroll-slideshow', WP_ivrss_PLUGIN_URL.'/image-vertical-reel-scroll-slideshow.js');
	}	
}

function ivrss_textdomain() 
{
	  load_plugin_textdomain( 'image-vertical-reel-scroll-slideshow', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action('plugins_loaded', 'ivrss_textdomain');
add_action('admin_menu', 'ivrss_add_to_menu');
add_action('wp_enqueue_scripts', 'ivrss_add_javascript_files');
add_action("plugins_loaded", "ivrss_init");
register_activation_hook(__FILE__, 'ivrss_install');
register_deactivation_hook(__FILE__, 'ivrss_deactivation');
?>