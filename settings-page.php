<?php
/**
 * Add the settings menu link to the Settings menu in admin interface
 */
function wpsos_rfl_add_settings_menu(){
	//Create a new link to the settings menu
	//Returns the suffix of the page that can later be used in the actions etc
	$page = add_options_page(
			'Remove Feed Links', //name of the settings page
			'Remove Feed Links',
			'manage_options',
			'remove-feed-links',
			'wpsos_rfl_display_settings_page' //the function that is going to be called if the created page is loaded
	);
	//If the form was submitted
	if( isset($_POST['wpsos_rfl_settings_save']) ){
		//Add the action to the plugin head to call wpsos_rfl_save_settings
		add_action("admin_head-$page", 'wpsos_rfl_save_settings');
	}
}
add_action( 'admin_menu', 'wpsos_rfl_add_settings_menu' );

/**
 * Display the settings page in the admin interface
 */
function wpsos_rfl_display_settings_page(){
	?>
		<div id="wpsos" class="wrap">
			<div class="wpsos-global-notification">By using this plugin, you’re eligible for a 5% discount on <a href="http://www.wpsos.io">WPSOS' security services</a>: virus cleanup, site securing and security maintenance!</div>
			<h1>Remove feed Links</h1>
			<div class="form-wrapper">
				<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
					<?php if( isset( $_POST['wpsos_rfl_settings_save'] ) ):?>
						<div class="updated"><p><strong><?php _e( 'Options saved.' ); ?></strong></p></div>
					<?php endif; ?>
					<?php wp_nonce_field( 'wpsos-rfl-save-settings' ); ?>
					<?php $options = unserialize( get_option( '_wpsos_rfl_options' ) ); ?>
					<table class="form-table">
						<tr>
							<th>
								<label>
									<input type="checkbox" name="remove-comments-feed" value="1" <?php echo $options['remove_comments_feed'] ? 'checked="checked"' : ''; ?>/>Remove comments' feed
								</label>
							</th>
						</tr>	
						<tr>
							<th>
								<label>
									<input type="checkbox" name="remove-posts-feed" value="1" <?php echo $options['remove_posts_feed'] ? 'checked="checked"' : ''; ?>/>Remove posts' feed
								</label>
							</th>
						</tr>
						<tr>
							<th>
								<label>
									<input type="checkbox" name="remove-category-feed" value="1" <?php echo $options['remove_category_feed'] ? 'checked="checked"' : ''; ?>/>Remove extra feed
								</label>
								<p class="subnote">Removes category, tags, author feeds</p>
							</th>
						</tr>
					</table>
					<p>
						<input class="submit" type="submit" value="Save" name="wpsos_rfl_settings_save">
					</p>
				</form>
			</div><!-- end .form-wrapper -->
		</div>
	<?php
}

/**
 * Save settings
 */
function wpsos_rfl_save_settings(){
	$options = array( 'remove_category_feed'=>$_POST['remove-category-feed'],
			'remove_posts_feed'=>$_POST['remove-posts-feed'],
			'remove_comments_feed'=>$_POST['remove-comments-feed'] );
	update_option( '_wpsos_rfl_options', serialize( $options ) );
}
?>