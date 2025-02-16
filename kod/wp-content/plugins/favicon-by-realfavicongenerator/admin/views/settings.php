<?php
// Copyright 2014 RealFaviconGenerator
?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

<?php if ( $new_favicon_params_url ) { ?>
	<div id="install_in_progress_message" class="updated">
		<p><?php _e( 'Favicon installation in progress. Please wait...', FBRFG_PLUGIN_SLUG ) ?></p>
	</div>

	<div id="install_completed_message" class="updated" style="display:none">
		<p><?php _e( 'Favicon installed!', FBRFG_PLUGIN_SLUG ) ?></p>
	</div>
	<div id="install_error_message" class="error" style="display:none"><p></p></div>

	<div id="install_completed_container" style="display:none">
		<h3><?php _e( 'Current favicon', FBRFG_PLUGIN_SLUG ) ?></h3>
		<p><?php _e( 'The favicon is up and ready.', FBRFG_PLUGIN_SLUG ) ?></p>
		<img id="preview_image">
	</div>
<?php } else { ?>
	<h3><?php _e( 'Current favicon', FBRFG_PLUGIN_SLUG ) ?></h3>

<?php 	if ( $favicon_configured) { ?>
	<p><?php _e( 'The favicon is up and ready.', FBRFG_PLUGIN_SLUG ) ?></p>
<?php 	} else { ?>
	<p><?php _e( 'No favicon has been configured yet.', FBRFG_PLUGIN_SLUG ) ?></p>
<?php 	} ?>
	
<?php 	if ( $favicon_configured ) {
			if ( $preview_url ) { ?>

	<img src="<?php echo $preview_url ?>">

<?php 		}
		}
	  } ?>

	<div id="favicon_form_container" <?php echo $new_favicon_params_url ? 'style="display:none"' : '' ?>>
		<h3><?php _e( 'Favicon generation', FBRFG_PLUGIN_SLUG ) ?></h3>
<?php if ( $favicon_configured || $new_favicon_params_url ) { ?>
	<p><?php _e( 'You can replace the existing favicon.', FBRFG_PLUGIN_SLUG ) ?></p>
<?php } ?>
		<form role="form" method="post" action="http://realfavicongenerator.net/api/favicon_generator" id="favicon_form">
			<input type="hidden" name="json_params" id="json_params"/>
			<table class="form-table"><tbody>
				<tr valign="top">
					<th scope="row">
						<label for="master_picture_url"><?php _e( 'Master picture URL', FBRFG_PLUGIN_SLUG ) ?></label>
					</th>
					<td>
						<input id="master_picture_url" name="master_picture_url" size="55">
						<button id="upload_image_button" value="<?php _e( 'Select from the Media Library', FBRFG_PLUGIN_SLUG ) ?>">
							<?php _e( 'Select from the Media Library', FBRFG_PLUGIN_SLUG ) ?>
						</button>
						<p class="description">
							<?php _e( 'Submit a square picture, at least 70x70 (recommended: 260x260 or more)', FBRFG_PLUGIN_SLUG ) ?>
							<br>
							<?php _e( 'If the picture is on your hard drive, you can leave this field blank and upload the picture from RealFaviconGenerator.', FBRFG_PLUGIN_SLUG ) ?>
						</p>
					</td>
				</tr>

<?php if ( $can_rewrite ) { ?>
				<tr valign="top">
					<th scope="row">
						<label for="rewrite"><?php _e( 'Favicon files in root directory', FBRFG_PLUGIN_SLUG ) ?></label>
					</th>
					<td>
						<input type="checkbox" name="rewrite" id="rewrite" checked="true">
						<p class="description">
							<?php _e( 'The plugin always stores the favicon files in a dedicated directory.', FBRFG_PLUGIN_SLUG ) ?>
							<br>
							<?php _e( 'However, if this option is enabled, the plugin takes advantage of the permalink feature and the favicon files appear to be in the root directory', FBRFG_PLUGIN_SLUG ) ?>
							(<a href="http://realfavicongenerator.net/faq#why_icons_in_root"><?php _e( 'recommended', FBRFG_PLUGIN_SLUG ) ?></a>)
						</p>
					</td>
				</tr>
<?php } ?>
			</tbody></table>

			<p class="submit">
				<input type="submit" name="Generate favicon" id="generate_favicon_button" class="button-primary"
					value="<?php _e( 'Generate favicon', FBRFG_PLUGIN_SLUG ) ?>">
			</p>
		</form>
	</div>
</div>

<script type="text/javascript">
	var picData = null;
	
	// See http://stackoverflow.com/questions/934012/get-image-data-in-javascript
	// Credits: Matthew Crumley
	function getBase64Image(img) {
		var canvas = document.createElement("canvas");
		canvas.width = img.width;
		canvas.height = img.height;
		
		var ctx = canvas.getContext("2d");
		ctx.drawImage(img, 0, 0);
	
		var dataURL = canvas.toDataURL("image/png");
			
		return dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
	}
	
	function computeJson() {
		var params = { favicon_generation: { 
			callback: {},
			master_picture: {},
			files_location: {},
			api_key: "87d5cd739b05c00416c4a19cd14a8bb5632ea563"
		}};
		
		if (jQuery('#master_picture_url').val().length <= 0) {
			params.favicon_generation.master_picture.type = "no_picture";
			params.favicon_generation.master_picture.demo = true;
		}
		else {
			params.favicon_generation.master_picture.type = "url";
			params.favicon_generation.master_picture.url = jQuery('#master_picture_url').val();
		}

<?php if ( $can_rewrite ) { ?>
		if ( jQuery("#rewrite").is(':checked') ) {
			params.favicon_generation.files_location.type = 'root';
		}
		else {
			params.favicon_generation.files_location.type = 'path';
			params.favicon_generation.files_location.path = '<?php echo $pic_path ?>';
		}
<?php } else { ?>
		params.favicon_generation.files_location.type = 'path';
		params.favicon_generation.files_location.path = '<?php echo $pic_path ?>';
<?php } ?>

		params.favicon_generation.callback.type = 'url';
		params.favicon_generation.callback.url = "<?php echo admin_url('themes.php?page=favicon-by-realfavicongenerator/admin/class-favicon-by-realfavicongenerator-admin.phpfavicon_settings_menu') ?>";
		params.favicon_generation.callback.short_url = 'true';

		return params;
	}

<?php if ( $new_favicon_params_url ) { ?>
	var data = {
		action: '<?php echo Favicon_By_RealFaviconGenerator_Common::PLUGIN_PREFIX . '_install_new_favicon' ?>',
		json_result_url: '<?php echo $new_favicon_params_url ?>'
	};
<?php } ?>
	
	jQuery(document).ready(function() {
		jQuery('#favicon_form').submit(function(e) {
			jQuery('#json_params').val(JSON.stringify(computeJson()));
		});

<?php if ( $new_favicon_params_url ) { ?>
		jQuery.get('<?php echo $ajax_url ?>', data)
			.done(function(response) {
				if (response.status == 'success') {
					jQuery('#preview_image').attr('src', response.preview_url);
					jQuery('#install_in_progress_message').fadeOut(function() {
						jQuery('#install_completed_message').fadeIn();
						jQuery('#install_completed_container').fadeIn();
						jQuery('#favicon_form_container').fadeIn();
					});
				}
				else {
					var msg = "An error occured";
					if (response.message != null) {
						msg += ": " + response.message;
					}
					jQuery('#install_error_message p').html(msg);
					jQuery('#install_in_progress_message').fadeOut(function() {
						jQuery('#install_error_message').fadeIn();
					});
				}
			})
			.fail(function() {
				var msg = "An internal error occured";
				jQuery('#install_error_message p').html(msg);
				jQuery('#install_in_progress_message').fadeOut(function() {
					jQuery('#install_error_message').fadeIn();
				});
			});
<?php } ?>

		var fileFrame;
	 
		jQuery('#upload_image_button').live('click', function(event) {
			event.preventDefault();
	 
			if (fileFrame) {
				fileFrame.open();
				return;
			}
		 
			// Create the media frame.
			fileFrame = wp.media.frames.file_frame = wp.media({
				title: jQuery(this).data('uploader_title'),
				button: {
					text: jQuery(this).data('uploader_button_text'),
				},
				multiple: false
			});
		 
			fileFrame.on('select', function() {
				attachment = fileFrame.state().get('selection').first().toJSON();
				jQuery('#master_picture_url').val(attachment.url);
			});
		 
			fileFrame.open();
		});

	});
</script>
