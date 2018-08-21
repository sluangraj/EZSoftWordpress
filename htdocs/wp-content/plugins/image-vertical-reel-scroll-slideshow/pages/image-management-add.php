<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
<?php
$ivrss_errors = array();
$ivrss_success = '';
$ivrss_error_found = FALSE;

// Preset the form fields
$form = array(
	'ivrss_path' => '',
	'ivrss_link' => '',
	'ivrss_target' => '',
	'ivrss_title' => '',
	'ivrss_order' => '',
	'ivrss_status' => '',
	'ivrss_type' => ''
);

// Form submitted, check the data
if (isset($_POST['ivrss_form_submit']) && $_POST['ivrss_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('ivrss_form_add');
	
	$form['ivrss_path'] = isset($_POST['ivrss_path']) ? esc_url_raw($_POST['ivrss_path']) : '';
	if ($form['ivrss_path'] == '')
	{
		$ivrss_errors[] = __('Please enter the image path.', 'image-vertical-reel-scroll-slideshow');
		$ivrss_error_found = TRUE;
	}

	$form['ivrss_link'] = isset($_POST['ivrss_link']) ? esc_url_raw($_POST['ivrss_link']) : '';
	if ($form['ivrss_link'] == '')
	{
		$ivrss_errors[] = __('Please enter the target link.', 'image-vertical-reel-scroll-slideshow');
		$ivrss_error_found = TRUE;
	}
	
	$form['ivrss_target'] = isset($_POST['ivrss_target']) ? sanitize_text_field($_POST['ivrss_target']) : '';
	if($form['ivrss_target'] != "_blank" && $form['ivrss_target'] != "_parent" && $form['ivrss_target'] != "_self" && $form['ivrss_target'] != "_new")
	{
		$form['udisg_target'] = "_blank";
	}
	
	$form['ivrss_title'] = isset($_POST['ivrss_title']) ? sanitize_text_field($_POST['ivrss_title']) : '';
	
	$form['ivrss_order'] = isset($_POST['ivrss_order']) ? intval($_POST['ivrss_order']) : '';
	
	$form['ivrss_status'] = isset($_POST['ivrss_status']) ? sanitize_text_field($_POST['ivrss_status']) : '';
	if($form['ivrss_status'] != "YES" && $form['ivrss_status'] != "NO")
	{
		$form['ivrss_status'] = "YES";
	}
	
	$form['ivrss_type'] = isset($_POST['ivrss_type']) ? sanitize_text_field($_POST['ivrss_type']) : '';

	//	No errors found, we can add this Group to the table
	if ($ivrss_error_found == FALSE)
	{
		$sql = $wpdb->prepare(
			"INSERT INTO `".WP_ivrss_TABLE."`
			(`ivrss_path`, `ivrss_link`, `ivrss_target`, `ivrss_title`, `ivrss_order`, `ivrss_status`, `ivrss_type`)
			VALUES(%s, %s, %s, %s, %d, %s, %s)",
			array($form['ivrss_path'], $form['ivrss_link'], $form['ivrss_target'], $form['ivrss_title'], $form['ivrss_order'], $form['ivrss_status'], $form['ivrss_type'])
		);
		$wpdb->query($sql);
		
		$ivrss_success = __('New image details was successfully added.', 'image-vertical-reel-scroll-slideshow');
		
		// Reset the form fields
		$form = array(
			'ivrss_path' => '',
			'ivrss_link' => '',
			'ivrss_target' => '',
			'ivrss_title' => '',
			'ivrss_order' => '',
			'ivrss_status' => '',
			'ivrss_type' => ''
		);
	}
}

if ($ivrss_error_found == TRUE && isset($ivrss_errors[0]) == TRUE)
{
	?>
	<div class="error fade">
		<p><strong><?php echo $ivrss_errors[0]; ?></strong></p>
	</div>
	<?php
}
if ($ivrss_error_found == FALSE && strlen($ivrss_success) > 0)
{
	?>
	  <div class="updated fade">
		<p><strong><?php echo $ivrss_success; ?> 
		<a href="<?php echo WP_ivrss_ADMIN_URL; ?>"><?php _e('Click here to view the details', 'image-vertical-reel-scroll-slideshow'); ?></a></strong></p>
	  </div>
	  <?php
	}
?>
<script language="JavaScript" src="<?php echo WP_ivrss_PLUGIN_URL; ?>/pages/setting.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($){
    $('#upload-btn').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            console.log(uploaded_image);
            var ivrss_path = uploaded_image.toJSON().url;
			var ivrss_title = uploaded_image.toJSON().title;
            // Let's assign the url value to the input field
            $('#ivrss_path').val(ivrss_path);
			$('#ivrss_title').val(ivrss_title);
        });
    });
});
</script>
<?php
wp_enqueue_script('jquery'); // jQuery
wp_enqueue_media(); // This will enqueue the Media Uploader script
?>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php _e('Image vertical reel scroll slideshow', 'image-vertical-reel-scroll-slideshow'); ?></h2>
	<form name="ivrss_form" method="post" action="#" onsubmit="return ivrss_submit()"  >
      <h3><?php _e('Add new image details', 'image-vertical-reel-scroll-slideshow'); ?></h3>
      <label for="tag-image"><?php _e('Enter image path', 'image-vertical-reel-scroll-slideshow'); ?></label>
      <input name="ivrss_path" type="text" id="ivrss_path" value="" size="80" />
	  <input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="Upload Image">
      <p><?php _e('Where is the picture located on the internet', 'image-vertical-reel-scroll-slideshow'); ?> 
	  (Ex: http://www.gopiplus.com/work/wp-content/uploads/pluginimages/250x167/250x167_1.jpg)</p>
      <label for="tag-link"><?php _e('Enter target link', 'image-vertical-reel-scroll-slideshow'); ?></label>
      <input name="ivrss_link" type="text" id="ivrss_link" value="#" size="80" />
      <p><?php _e('When someone clicks on the picture, where do you want to send them', 'image-vertical-reel-scroll-slideshow'); ?></p>
      <label for="tag-target"><?php _e('Enter target option', 'image-vertical-reel-scroll-slideshow'); ?></label>
      <select name="ivrss_target" id="ivrss_target">
        <option value='_blank'>_blank</option>
        <option value='_parent'>_parent</option>
        <option value='_self'>_self</option>
        <option value='_new'>_new</option>
      </select>
      <p><?php _e('Do you want to open link in new window?', 'image-vertical-reel-scroll-slideshow'); ?></p>
      <label for="tag-title"><?php _e('Enter image reference', 'image-vertical-reel-scroll-slideshow'); ?></label>
      <input name="ivrss_title" type="text" id="ivrss_title" value="" size="80" />
      <p><?php _e('Enter image reference. This is only for reference.', 'image-vertical-reel-scroll-slideshow'); ?></p>
      <label for="tag-select-gallery-group"><?php _e('Select gallery type', 'image-vertical-reel-scroll-slideshow'); ?></label>
      <select name="ivrss_type" id="ivrss_type">
        <option value='GROUP1'>Group1</option>
        <option value='GROUP2'>Group2</option>
        <option value='GROUP3'>Group3</option>
        <option value='GROUP4'>Group4</option>
        <option value='GROUP5'>Group5</option>
        <option value='GROUP6'>Group6</option>
        <option value='GROUP7'>Group7</option>
        <option value='GROUP8'>Group8</option>
        <option value='GROUP9'>Group9</option>
        <option value='GROUP0'>Group0</option>
		<option value='Widget'>Widget</option>
		<option value='Sample'>Sample</option>
      </select>
      <p><?php _e('This is to group the images. Select your slideshow group.', 'image-vertical-reel-scroll-slideshow'); ?></p>
      <label for="tag-display-status"><?php _e('Display status', 'image-vertical-reel-scroll-slideshow'); ?></label>
      <select name="ivrss_status" id="ivrss_status">
        <option value='YES'>Yes</option>
        <option value='NO'>No</option>
      </select>
      <p><?php _e('Do you want the picture to show in your galler?', 'image-vertical-reel-scroll-slideshow'); ?></p>
      <label for="tag-display-order"><?php _e('Display order', 'image-vertical-reel-scroll-slideshow'); ?></label>
      <input name="ivrss_order" type="text" id="ivrss_order" size="10" value="1" maxlength="3" />
      <p><?php _e('What order should the picture be played in. should it come 1st, 2nd, 3rd, etc.', 'image-vertical-reel-scroll-slideshow'); ?></p>
      <input name="ivrss_id" id="ivrss_id" type="hidden" value="">
      <input type="hidden" name="ivrss_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button-primary" value="<?php _e('Insert Details', 'image-vertical-reel-scroll-slideshow'); ?>" type="submit" />
        <input name="publish" lang="publish" class="button-primary" onclick="ivrss_redirect()" value="<?php _e('Cancel', 'image-vertical-reel-scroll-slideshow'); ?>" type="button" />
        <input name="Help" lang="publish" class="button-primary" onclick="ivrss_help()" value="<?php _e('Help', 'image-vertical-reel-scroll-slideshow'); ?>" type="button" />
      </p>
	  <?php wp_nonce_field('ivrss_form_add'); ?>
    </form>
</div>
	<p class="description">
		<?php _e('Check official website for more information', 'image-vertical-reel-scroll-slideshow'); ?>
		<a target="_blank" href="<?php echo WP_ivrss_FAV; ?>"><?php _e('click here', 'image-vertical-reel-scroll-slideshow'); ?></a>
	</p>
</div>