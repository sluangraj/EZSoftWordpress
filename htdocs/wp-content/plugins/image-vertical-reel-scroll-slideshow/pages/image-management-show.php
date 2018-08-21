<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
// Form submitted, check the data
if (isset($_POST['frm_ivrss_display']) && $_POST['frm_ivrss_display'] == 'yes')
{
	$did = isset($_GET['did']) ? intval($_GET['did']) : '0';
	if(!is_numeric($did)) { die('<p>Are you sure you want to do this?</p>'); }
	
	$ivrss_success = '';
	$ivrss_success_msg = FALSE;
	
	// First check if ID exist with requested ID
	$sSql = $wpdb->prepare(
		"SELECT COUNT(*) AS `count` FROM ".WP_ivrss_TABLE."
		WHERE `ivrss_id` = %d",
		array($did)
	);
	$result = '0';
	$result = $wpdb->get_var($sSql);
	
	if ($result != '1')
	{
		?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist', 'image-vertical-reel-scroll-slideshow'); ?></strong></p></div><?php
	}
	else
	{
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('ivrss_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".WP_ivrss_TABLE."`
					WHERE `ivrss_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$ivrss_success_msg = TRUE;
			$ivrss_success = __('Selected record was successfully deleted.', 'image-vertical-reel-scroll-slideshow');
		}
	}
	
	if ($ivrss_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $ivrss_success; ?></strong></p></div><?php
	}
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php _e('Image vertical reel scroll slideshow', 'image-vertical-reel-scroll-slideshow'); ?>
	<a class="add-new-h2" href="<?php echo WP_ivrss_ADMIN_URL; ?>&amp;ac=add"><?php _e('Add New', 'image-vertical-reel-scroll-slideshow'); ?></a></h2>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".WP_ivrss_TABLE."` order by ivrss_type, ivrss_order";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<script language="JavaScript" src="<?php echo WP_ivrss_PLUGIN_URL; ?>/pages/setting.js"></script>
		<form name="frm_ivrss_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
			<th scope="col"><?php _e('Type/Group', 'image-vertical-reel-scroll-slideshow'); ?></th>
			<th scope="col"><?php _e('Reference', 'image-vertical-reel-scroll-slideshow'); ?></th>
            <th scope="col"><?php _e('Image', 'image-vertical-reel-scroll-slideshow'); ?></th>
			<th scope="col"><?php _e('Link', 'image-vertical-reel-scroll-slideshow'); ?></th>
			<th scope="col"><?php _e('Target', 'image-vertical-reel-scroll-slideshow'); ?></th>
            <th scope="col"><?php _e('Order', 'image-vertical-reel-scroll-slideshow'); ?></th>
            <th scope="col"><?php _e('Display', 'image-vertical-reel-scroll-slideshow'); ?></th>
          </tr>
        </thead>
		<tfoot>
          <tr>
			<th scope="col"><?php _e('Type/Group', 'image-vertical-reel-scroll-slideshow'); ?></th>
			<th scope="col"><?php _e('Reference', 'image-vertical-reel-scroll-slideshow'); ?></th>
            <th scope="col"><?php _e('Image', 'image-vertical-reel-scroll-slideshow'); ?></th>
			<th scope="col"><?php _e('Link', 'image-vertical-reel-scroll-slideshow'); ?></th>
			<th scope="col"><?php _e('Target', 'image-vertical-reel-scroll-slideshow'); ?></th>
            <th scope="col"><?php _e('Order', 'image-vertical-reel-scroll-slideshow'); ?></th>
            <th scope="col"><?php _e('Display', 'image-vertical-reel-scroll-slideshow'); ?></th>
          </tr>
        </tfoot>
		<tbody>
			<?php 
			$i = 0;
			if(count($myData) > 0 )
			{
				foreach ($myData as $data)
				{
					?>
					<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
						<td>
						<strong><?php echo esc_html(stripslashes($data['ivrss_type'])); ?></strong>
						<div class="row-actions">
						<span class="edit">
							<a title="Edit" href="<?php echo WP_ivrss_ADMIN_URL; ?>&amp;ac=edit&amp;did=<?php echo $data['ivrss_id']; ?>">
							<?php _e('Edit', 'image-vertical-reel-scroll-slideshow'); ?></a> | 
						</span>
						<span class="trash">
							<a onClick="javascript:ivrss_delete('<?php echo $data['ivrss_id']; ?>')" href="javascript:void(0);"><?php _e('Delete', 'image-vertical-reel-scroll-slideshow'); ?></a>
						</span> 
						</div>
						</td>
						<td><?php echo esc_html(stripslashes($data['ivrss_title'])); ?></td>
						<td><a href="<?php echo esc_html($data['ivrss_path']); ?>" target="_blank"><img src="<?php echo WP_ivrss_PLUGIN_URL; ?>/inc/image-icon.png"  /></a></td>
						<td><a href="<?php echo esc_html($data['ivrss_link']); ?>" target="_blank"><img src="<?php echo WP_ivrss_PLUGIN_URL; ?>/inc/link-icon.gif"  /></a></td>
						<td><?php echo esc_html(stripslashes($data['ivrss_target'])); ?></td>
						<td><?php echo esc_html(stripslashes($data['ivrss_order'])); ?></td>
						<td><?php echo esc_html(stripslashes($data['ivrss_status'])); ?></td>
					</tr>
					<?php 
					$i = $i+1; 
				}
			}
			else
			{
				?><tr><td colspan="7" align="center"><?php _e('No records available.', 'image-vertical-reel-scroll-slideshow'); ?></td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('ivrss_form_show'); ?>
		<input type="hidden" name="frm_ivrss_display" value="yes"/>
      </form>	
	  <div class="tablenav bottom">
		  <a href="<?php echo WP_ivrss_ADMIN_URL; ?>&amp;ac=add"><input class="button action" type="button" value="<?php _e('Add New', 'image-vertical-reel-scroll-slideshow'); ?>" /></a>
		  <a href="<?php echo WP_ivrss_ADMIN_URL; ?>&amp;ac=set"><input class="button action" type="button" value="<?php _e('Widget Setting', 'image-vertical-reel-scroll-slideshow'); ?>" /></a>
		  <a target="_blank" href="<?php echo WP_ivrss_FAV; ?>"><input class="button action" type="button" value="<?php _e('Help', 'image-vertical-reel-scroll-slideshow'); ?>" /></a>
	  </div>
	<p class="description">
		<?php _e('Check official website for more information', 'image-vertical-reel-scroll-slideshow'); ?>
		<a target="_blank" href="<?php echo WP_ivrss_FAV; ?>"><?php _e('click here', 'image-vertical-reel-scroll-slideshow'); ?></a>
	</p>
	</div>
</div>