<?php
/**
 * Includes functions for all admin page templates and
 * functions that add menu pages in the dashboard. Also
 * has code for saving settings with defaults.
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

function sb_instagram_menu() {
	$cap = current_user_can( 'manage_instagram_feed_options' ) ? 'manage_instagram_feed_options' : 'manage_options';

	$cap = apply_filters( 'sbi_settings_pages_capability', $cap );

	global $sb_instagram_posts_manager;
	$notice = '';
	if ( $sb_instagram_posts_manager->are_critical_errors() ) {
		$notice = ' <span class="update-plugins sbi-error-alert"><span>!</span></span>';
	}

	$sbi_notifications = new SBI_Notifications();
	$notifications     = $sbi_notifications->get();

	$notice_bubble = '';
	if ( empty( $notice ) && ! empty( $notifications ) && is_array( $notifications ) ) {
		$notice_bubble = ' <span class="sbi-notice-alert"><span>' . count( $notifications ) . '</span></span>';
	}

	add_menu_page(
		__( 'Instagram Feed', 'instagram-feed' ),
		__( 'Instagram Feed', 'instagram-feed' ) . $notice . $notice_bubble,
		$cap,
		'sb-instagram-feed',
		'sb_instagram_settings_page'
	);
	add_submenu_page(
		'sb-instagram-feed',
		__( 'Settings', 'instagram-feed' ),
		__( 'Settings', 'instagram-feed' ) . $notice,
		$cap,
		'sb-instagram-feed',
		'sb_instagram_settings_page'
	);
	add_submenu_page(
		'sb-instagram-feed',
		__( 'Customize', 'instagram-feed' ),
		__( 'Customize', 'instagram-feed' ),
		$cap,
		'sb-instagram-feed&tab=customize',
		'sb_instagram_settings_page'
	);
	$multisite_super_admin_only = get_site_option( 'sbi_super_admin_only', false );

	if ( ! is_multisite() || ($multisite_super_admin_only !== 'on' && $multisite_super_admin_only !== true )|| current_user_can( 'manage_network' ) ) {
		add_submenu_page(
			'sb-instagram-feed',
			__( 'License', 'instagram-feed' ),
			__( 'License', 'instagram-feed' ),
			$cap,
			'sb-instagram-license',
			'sbi_license_page'
		);
	}
	add_submenu_page(
		'sb-instagram-feed',
		__( 'About Us', 'instagram-feed' ),
		__( 'About Us', 'instagram-feed' ),
		$cap,
		'sb-instagram-feed-about',
		'sb_instagram_about_page'
	);
	add_submenu_page(
		'sb-instagram-feed',
		__( 'oEmbeds', 'instagram-feed' ),
		'<svg style="height: 14px; margin: 0 8px 0 0; position: relative; top: 2px;" aria-hidden="true" focusable="false" data-prefix="far" data-icon="code" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="svg-inline--fa fa-code fa-w-18 fa-2x"><path fill="currentColor" d="M234.8 511.7L196 500.4c-4.2-1.2-6.7-5.7-5.5-9.9L331.3 5.8c1.2-4.2 5.7-6.7 9.9-5.5L380 11.6c4.2 1.2 6.7 5.7 5.5 9.9L244.7 506.2c-1.2 4.3-5.6 6.7-9.9 5.5zm-83.2-121.1l27.2-29c3.1-3.3 2.8-8.5-.5-11.5L72.2 256l106.1-94.1c3.4-3 3.6-8.2.5-11.5l-27.2-29c-3-3.2-8.1-3.4-11.3-.4L2.5 250.2c-3.4 3.2-3.4 8.5 0 11.7L140.3 391c3.2 3 8.2 2.8 11.3-.4zm284.1.4l137.7-129.1c3.4-3.2 3.4-8.5 0-11.7L435.7 121c-3.2-3-8.3-2.9-11.3.4l-27.2 29c-3.1 3.3-2.8 8.5.5 11.5L503.8 256l-106.1 94.1c-3.4 3-3.6 8.2-.5 11.5l27.2 29c3.1 3.2 8.1 3.4 11.3.4z" class=""></path></svg>' . __( 'oEmbeds', 'instagram-feed' ),
		$cap,
		'sbi-oembeds',
		'sbi_oembeds_page'
	);
	add_submenu_page(
		'sb-instagram-feed',
		__( 'Create a Social Wall', 'instagram-feed' ),
		'<span style="color:#f18500">' . __( 'Create a Social Wall', 'instagram-feed' ) . '</span>',
		$cap,
		'sbi-sw',
		'sbi_social_wall_page'
	);
}
add_action( 'admin_menu', 'sb_instagram_menu' );

function sb_instagram_about_page() {
	do_action( 'sbi_admin_page' );
}

function sbi_oembeds_page() {
	?>

	<div id="sbi_admin" class="wrap sbi-oembeds">
		<?php
		$lite_notice_dismissed = get_transient( 'instagram_feed_dismiss_lite' );

		if ( ! $lite_notice_dismissed ) :
			?>
			<div id="sbi-notice-bar" style="display:none">
				<span class="sbi-notice-bar-message"><?php echo wp_kses_post( __( 'You\'re using Instagram Feed Lite. To unlock more features consider <a href="https://smashballoon.com/instagram-feed/?utm_campaign=instagram-free&utm_source=notices&utm_medium=litenotice" target="_blank" rel="noopener noreferrer">upgrading to Pro</a>.', 'instagram-feed' ) ); ?></span>
				<button type="button" class="dismiss" title="<?php esc_html_e( 'Dismiss this message.', 'instagram-feed' ); ?>" data-page="overview">
				</button>
			</div>
		<?php endif; ?>
		<div id="header">
			<h1><?php esc_html_e( 'Instagram oEmbeds', 'instagram-feed' ); ?></h1>
		</div>

		<p>
			<?php
			esc_html_e( 'You can use the Instagram Feed plugin to power your Instagram oEmbeds, both old and new.', 'instagram-feed' );
			if ( ! SB_Instagram_Oembed::can_do_oembed() ) {
				echo ' ';
				esc_html_e( "Just click the button below and we'll do the rest.", 'instagram-feed' );
			}
			?>
		</p>

		<div class="sbi-oembed-button">

			<?php
			$admin_url_state = admin_url( 'admin.php?page=sbi-oembeds' );
			$encryption      = new SB_Instagram_Data_Encryption();

			$oembed_token_settings   = get_option( 'sbi_oembed_token', array() );
			$saved_access_token_data = isset( $oembed_token_settings['access_token'] ) ? $oembed_token_settings['access_token'] : false;
			if ( $saved_access_token_data && ! $encryption->decrypt( $saved_access_token_data ) ) {
				$saved_access_token_data = $encryption->encrypt( $saved_access_token_data );
			}

			$access_token_error          = false;
			$valid_new_access_token      = false;
			$error_message               = '';
			$show_token_expiration_modal = false;

			$nonce_verified = isset( $_GET['sbi_con'] ) && wp_verify_nonce( $_GET['sbi_con'], 'sbi-oembed' );
			if ( $nonce_verified && ! empty( $_GET['sbi_access_token'] ) && strlen( $_GET['sbi_access_token'] ) <= 20 ) {
				$access_token_error = true;
				$error_message      = __( 'There was a problem with the access token that was retrieved.', 'instagram-feed' );

			} elseif ( $nonce_verified && ! empty( $_GET['transfer'] ) ) {
				if ( class_exists( 'CFF_Oembed' ) ) {
					$cff_oembed_token       = CFF_Oembed::last_access_token();
					$valid_new_access_token = $cff_oembed_token;
				}
			} else {

				$valid_new_access_token = ! empty( $_GET['sbi_access_token'] ) && strlen( $_GET['sbi_access_token'] ) > 20 && $saved_access_token_data !== $_GET['sbi_access_token'] ? sbi_sanitize_alphanumeric_and_equals( $_GET['sbi_access_token'] ) : false;
				if ( $valid_new_access_token ) {
					$args = array(
						'timeout' => 20,
					);
					if ( version_compare( get_bloginfo( 'version' ), '3.7', '<' ) ) {
						$args['sslverify'] = false;
					}
					$pages_data_connection = wp_remote_get( esc_url_raw( 'https://graph.facebook.com/me/accounts?limit=500&access_token=' . $valid_new_access_token ), $args );

					if ( ! is_wp_error( $pages_data_connection ) && isset( $pages_data_connection['body'] ) ) {
						$pages_data = json_decode( $pages_data_connection['body'], true );
						if ( isset( $pages_data['data'][0]['access_token'] ) ) {
							$oembed_token_settings['expiration_date'] = 'never';
						} else {
							$oembed_token_settings['expiration_date'] = time() + ( 60 * DAY_IN_SECONDS );
							$show_token_expiration_modal              = true;
						}
					} else {
						$oembed_token_settings['expiration_date'] = 'unknown';
					}
				}
			}

			$token_href            = 'https://www.facebook.com/dialog/oauth?client_id=254638078422287&redirect_uri=https://api.smashballoon.com/v2/instagram-graph-api-redirect.php&scope=pages_show_list&state=' . $admin_url_state;
			$need_to_connect_class = ' sbi-need-to-connect';
			if ( class_exists( 'CFF_Oembed' ) ) {
				$cff_oembed_token = CFF_Oembed::last_access_token();

				if ( ! empty( $cff_oembed_token ) ) {
					$need_to_connect_class = '';
					$token_href            = add_query_arg( 'transfer', '1', $admin_url_state );
				}
			}
			$token_href = str_replace( esc_html( '&sbi_con' ), ',sbi_con', wp_nonce_url( $token_href, 'sbi-oembed', 'sbi_con' ) );

			if ( ! $saved_access_token_data && ! $valid_new_access_token && ! SB_Instagram_Oembed::can_do_oembed() ) {
				if ( $access_token_error ) {
					?>
					<p class="sbi-error"><?php echo wp_kses_post( $error_message ); ?></p>
					<?php
				}
				?>

				<a href="<?php echo esc_url( $token_href ); ?>" class="sbi-oembed-connect-btn<?php echo esc_attr( $need_to_connect_class ); ?>"><i class="fa fa-instagram"></i> <?php esc_html_e( 'Enable Instagram oEmbeds', 'instagram-feed' ); ?></a>
				<div id="sbi_config_info" class="sb_get_token" style="display: none;">
					<div class="sbi_config_modal">
						<?php esc_html_e( 'As Instagram is part of Facebook, in order to display Instagram oEmbeds, you must connect to Facebook. Click on the button below to connect', 'instagram-feed' ); ?>

						<p>
							<a style="display: inline-block; float: none; margin-bottom: 0;" href="<?php echo esc_url( $token_href ); ?>" class="sbi-oembed-connect-btn"><?php esc_html_e( 'Connect to Facebook', 'instagram-feed' ); ?></a>
						</p>

						<a href="JavaScript:void(0);"><i class="sbi_modal_close fa fa-times"></i></a>
					</div>
				</div>
				<div class="sbi-oembed-promo sbi-oembed-desc">
					<div class="sbi-col">
						<h2><?php esc_html_e( 'What are oEmbeds?', 'instagram-feed' ); ?></h2>
						<p><?php echo wp_kses_post( __( "Anytime you share a link to an Instagram post in WordPress, it is automatically converted into an embedded version of that Instagram post (an \"oEmbed\").</p><p>However, WordPress is discontinuing support for Instagram oEmbeds due to them now requiring an Access Token to work. Don't worry though, we have your back. Just use the button above to connect to Facebook and we'll make sure your Instagram oEmbeds keep working.", 'instagram-feed' ) ); ?></p>
					</div>

					<img src="<?php echo esc_url( SBI_PLUGIN_URL . 'img/sbi-oembed.png' ); ?>" style="padding: 0; background: white;" alt="<?php esc_attr_e( 'Oembed example', 'instagram-feed' ); ?>">
				</div>
				<?php
			} else {
				if ( $valid_new_access_token ) {
					if ( ! is_array( $oembed_token_settings ) ) {
						$oembed_token_settings = array(
							'access_token' => '',
							'disabled'     => '',
						);
					}
					if ( $valid_new_access_token && ! $encryption->decrypt( $valid_new_access_token ) ) {
						$valid_new_access_token = $encryption->encrypt( $valid_new_access_token );
					}
					$oembed_token_settings['access_token'] = $valid_new_access_token;
					$oembed_token_settings['disabled']     = false;
					update_option( 'sbi_oembed_token', $oembed_token_settings );
					?>
					<div><p class="sbi-success-message"><?php esc_html_e( "You're all set! Instagram Feed will replace your old oEmbeds automatically and generate all new content for all new links.", 'instagram-feed' ); ?> <a href="javascript:void(0);" id="sbi-oembed-disable"><?php esc_html_e( 'Disable', 'instagram-feed' ); ?></a></p></div>
					<?php if ( $show_token_expiration_modal ) : ?>
						<div id="sbi_config_info" class="sb_get_token">
							<div class="sbi_config_modal">
								<p><strong><?php esc_html_e( 'Heads up!', 'instagram-feed' ); ?></strong><br></p>

								<p>
									<?php esc_html_e( 'Your access token will expire in 60 days. Facebook requires that users have a role on a Facebook page in order to create access tokens that don\'t expire. Click the button below for instructions on creating a Facebook page and extending your access token to never expire.', 'instagram-feed' ); ?>
								</p>
								<p>
									<a style="display: inline-block; float: none; margin-bottom: 0;" href="https://smashballoon.com/doc/how-to-prevent-your-oembed-access-token-from-expiring/?instagram" class="sbi-oembed-connect-btn" target="_blank" rel="noopener"><?php esc_html_e( 'How to Create a Facebook Page', 'instagram-feed' ); ?></a>
									&nbsp;&nbsp;<a href="<?php echo esc_url( $token_href ); ?>" class="button button-secondary" style="height: 47px;line-height: 47px;font-size: 14px;padding: 0 21px;"><?php esc_html_e( 'Try Again', 'instagram-feed' ); ?></a>
								</p>

								<a href="JavaScript:void(0);"><i class="sbi_modal_close fa fa-times"></i></a>
							</div>
						</div>
					<?php endif; ?>
					<?php
				} else {
					if ( ! isset( $oembed_token_settings['expiration_date'] ) || (int) $oembed_token_settings['expiration_date'] === 0 || $oembed_token_settings['expiration_date'] > time() ) :
						?>
						<div><p class="sbi-success-message"><?php esc_html_e( 'The Instagram Feed plugin is now powering your Instagram oEmbeds.', 'instagram-feed' ); ?> <a href="javascript:void(0);" id="sbi-oembed-disable"><?php esc_html_e( 'Disable', 'instagram-feed' ); ?></a></p></div>
					<?php
					endif;
					if ( ! empty( $oembed_token_settings['expiration_date'] ) && $oembed_token_settings['expiration_date'] !== 'never' ) :
						$link_1 = '<a href="https://smashballoon.com/doc/how-to-prevent-your-oembed-access-token-from-expiring/?instagram" target="_blank" rel="noopener">';
						$link_2 = '</a>';
						$class  = 'sbi_warning';
						if ( $oembed_token_settings['expiration_date'] > time() ) {
							$days_to_expire = floor( ( $oembed_token_settings['expiration_date'] - time() ) / DAY_IN_SECONDS );
							$message        = sprintf( __( '%1$1sImportant:%2$2s Your access token for powering oEmbeds will expire in %3$3s days.', 'instagram-feed' ), '<strong>', '</strong>', $days_to_expire );
						} else {
							$class   = 'sb_instagram_notice';
							$message = __( 'Your access token for powering oEmbeds has expired.', 'instagram-feed' );
						}
						?>
						<div class="<?php echo esc_attr( $class ); ?>" style="display:inline-block;width: auto;">
							<p>
								<?php echo wp_kses_post( $message ); ?>
							</p>
							<p>
								<?php echo wp_kses_post( sprintf( __( 'Instagram requires that users have a role on a Facebook page in order to create access tokens that don\'t expire. Visit %1$1sthis link%2$2s for instructions on extending your access token to never expire.', 'instagram-feed' ), $link_1, $link_2 ) ); ?>
							</p>
							<p>
								<a style="display: inline-block; float: none; margin-bottom: 0;" href="<?php echo esc_url( $token_href ); ?>" class="sbi-oembed-connect-btn"><?php esc_html_e( 'Connect to Facebook and Recheck Access Token', 'instagram-feed' ); ?></a>
							</p>
						</div>

					<?php endif; ?>

				<?php } ?>
				<div class="sbi-oembed-promo">
					<h2><?php esc_html_e( 'Did you know, you can also use this Instagram Feed plugin to easily add Instagram content on your website?', 'instagram-feed' ); ?></h2>
					<div class="sbi-reasons">
						<div><svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="clock" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-clock fa-w-16 fa-2x"><path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z" class=""></path></svg><span><?php esc_html_e( 'Save time', 'instagram-feed' ); ?></span></div>
						<div><svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="chart-line" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-chart-line fa-w-16 fa-2x"><path fill="currentColor" d="M117.65 277.65c6.25 6.25 16.38 6.25 22.63 0L192 225.94l84.69 84.69c6.25 6.25 16.38 6.25 22.63 0L409.54 200.4l29.49 29.5c15.12 15.12 40.97 4.41 40.97-16.97V112c0-8.84-7.16-16-16-16H363.07c-21.38 0-32.09 25.85-16.97 40.97l29.5 29.49-87.6 87.6-84.69-84.69c-6.25-6.25-16.38-6.25-22.63 0l-74.34 74.34c-6.25 6.25-6.25 16.38 0 22.63l11.31 11.31zM496 400H48V80c0-8.84-7.16-16-16-16H16C7.16 64 0 71.16 0 80v336c0 17.67 14.33 32 32 32h464c8.84 0 16-7.16 16-16v-16c0-8.84-7.16-16-16-16z" class=""></path></svg><span><?php esc_html_e( 'Increase social engagement', 'instagram-feed' ); ?></span></div>
						<div><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="heart" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-heart fa-w-16 fa-2x"><path fill="currentColor" d="M462.3 62.7c-54.5-46.4-136-38.7-186.6 13.5L256 96.6l-19.7-20.3C195.5 34.1 113.2 8.7 49.7 62.7c-62.8 53.6-66.1 149.8-9.9 207.8l193.5 199.8c6.2 6.4 14.4 9.7 22.6 9.7 8.2 0 16.4-3.2 22.6-9.7L472 270.5c56.4-58 53.1-154.2-9.7-207.8zm-13.1 185.6L256.4 448.1 62.8 248.3c-38.4-39.6-46.4-115.1 7.7-161.2 54.8-46.8 119.2-12.9 142.8 11.5l42.7 44.1 42.7-44.1c23.2-24 88.2-58 142.8-11.5 54 46 46.1 121.5 7.7 161.2z" class=""></path></svg><span><?php esc_html_e( 'Keep Your Site Looking Fresh.', 'instagram-feed' ); ?></span></div>
					</div>
					<p>
						<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-check fa-w-16 fa-2x"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z" class=""></path></svg><span><?php esc_html_e( 'Super simple to set up', 'instagram-feed' ); ?></span>
						<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-check fa-w-16 fa-2x"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z" class=""></path></svg><span><?php esc_html_e( 'Optimized for speed', 'instagram-feed' ); ?></span>
						<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-check fa-w-16 fa-2x"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z" class=""></path></svg><span><?php esc_html_e( 'Completely customizable', 'instagram-feed' ); ?></span>
						<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-check fa-w-16 fa-2x"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z" class=""></path></svg><span><?php esc_html_e( 'SEO friendly', 'instagram-feed' ); ?></span>
					</p>
					<a href="?page=sb-instagram-feed" class="button button-primary"><?php esc_html_e( 'Add an Instagram feed now', 'instagram-feed' ); ?></a>
				</div>

			<?php } ?>

		</div>
	</div>
	<?php
}

function sbi_social_wall_page() {
	if ( is_plugin_active( 'social-wall/social-wall.php' ) ) {
		return;
	}
	?>

	<div id="sbi_admin" class="wrap sw-landing-page">

		<div class="sbi-sw-icons">

			<span style="display: inline-block; padding: 0 0 12px 0; width: 360px; max-width: 100%;">
				<svg viewBox="0 0 9161 1878" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2">
					<path d="M671.51192 492.98498c-131.56765-59.12206-268.60859-147.41608-396.53319-188.5154 45.4516 108.39585 83.81326 223.88002 123.5099 338.03081-79.17849 59.49897-171.6647 105.68858-260.02357 156.01204C213.65642 872.8361 320.1446 915.85885 404.9893 980.52836c-67.96118 83.8619-201.48512 171.0179-234.02089 247.0198 140.6921-17.62678 304.63665-46.21028 435.53762-52.00414 28.76425 144.58318 43.59867 303.0974 84.5075 435.5368 60.92028-175.2656 116.0013-356.3729 188.5158-520.0447 111.90636 46.28566 248.28994 102.72599 357.52876 130.01178-76.6463-107.53462-146.59336-221.76932-214.51645-338.02878 100.51155-72.83872 202.17166-144.52441 299.02516-221.02077-136.89504-12.61227-278.73407-20.28825-422.53587-25.99863-22.85286-148.332-16.84825-325.5158-52.00496-461.53949-53.19323 111.48812-115.96685 213.3914-175.51405 318.52475m65.00509 1228.60643c-18.07949 77.37581 41.48757 109.11319 32.50294 156.01204-58.81404-20.26799-103.0575-30.6796-182.01552-19.50201 2.47017-60.37032 56.76657-68.90954 45.50428-143.0107-841.40803-95.6632-843.09804-1616.06909-6.50107-1709.64388C1672.04777-111.55711 1704.8713 1694.70523 736.517 1721.5914" fill="#e34f0e"/>
					<path d="M847.02597 174.46023c35.15671 136.0237 29.1521 313.20749 52.00455 461.53544 143.80221 5.71443 285.63962 13.38636 422.53628 26.00268-96.8531 76.49636-198.51483 148.18205-299.02556 221.01874 67.92349 116.2623 137.87014 230.49416 214.51847 338.03-109.24085-27.2866-245.62443-83.72572-357.5308-130.0126-72.51448 163.67262-127.5955 344.77992-188.51538 520.04553-40.90924-132.4394-55.74325-290.95364-84.5079-435.53681-130.90057 5.79548-294.84472 34.37736-435.53722 52.00415 32.53577-76.0007 166.0589-163.15589 234.02008-247.02021-84.8451-64.67032-191.33207-107.69066-266.52343-182.01472 88.35886-50.32346 180.84346-96.51307 260.02276-156.01609-39.69705-114.14674-78.05668-229.63091-123.50868-338.02675C402.9013 345.5689 539.94427 433.86292 671.51192 492.98498c59.5468-105.13335 122.32082-207.03663 175.51405-318.52475" fill="#fff"/>
					<path d="M1782.27033 1236.51938c41.18267 21.61921 126.79927 44.31938 214.58338 44.31938 213.49962 0 311.03752-107.01507 311.03752-232.40646 0-101.61027-58.52274-171.87269-189.65702-220.5159-92.11913-33.50977-131.13429-48.6432-131.13429-85.39586 0-32.4288 32.51263-54.04801 92.11913-54.04801 72.61154 0 126.79927 20.53824 158.22814 34.59073l41.18267-155.65828c-47.6852-21.6192-110.54295-37.83361-197.2433-37.83361-184.23826 0-293.69746 99.44834-293.69746 228.08262 0 108.09602 82.36534 176.19652 205.91335 219.43493 82.36533 28.10497 114.87797 48.64321 114.87797 84.3149 0 36.75265-32.51264 59.45282-99.70541 59.45282-73.6953 0-145.2231-22.70017-189.65703-45.40034l-36.84765 161.06308zM3019.37602 1270.02915h189.65702l-36.84765-728.56722h-256.8498l-55.27148 194.57285c-21.67508 76.74818-45.51768 179.4394-66.10902 268.07815h-3.25126c-15.17256-88.63875-36.84765-185.92517-57.43898-266.99719l-47.6852-195.6538h-263.35233l-45.51768 728.56721h179.90323l11.9213-260.51142c3.25127-83.23394 6.50253-191.32997 10.83755-294.0212h2.1675c17.34008 99.44835 39.01517 207.54438 58.52274 286.45448l60.69025 252.9447h152.80938l72.61154-254.02566c23.8426-79.99106 54.18773-189.16805 76.94657-285.37352h3.25126c0 113.50083 1.08376 210.78726 4.33502 294.0212l8.67004 260.51142zM3699.9738 1101.39935l46.60144 168.6298h211.33211l-217.83464-728.56722H3478.8879l-211.33211 728.56722h202.66208l41.18267-168.6298h188.57327zm-162.56317-143.76772l31.42888-130.79619c9.7538-41.07649 20.59134-101.61026 31.42888-143.76771h2.1675c11.9213 42.15745 26.01012 102.69122 36.84766 143.76771l33.59639 130.7962h-135.4693zM4016.4301 1236.51938c41.18266 21.61921 126.79926 44.31938 214.58337 44.31938 213.49962 0 311.03752-107.01507 311.03752-232.40646 0-101.61027-58.52274-171.87269-189.65702-220.5159-92.11913-33.50977-131.1343-48.6432-131.1343-85.39586 0-32.4288 32.51264-54.04801 92.11914-54.04801 72.61154 0 126.79926 20.53824 158.22814 34.59073l41.18267-155.65828c-47.6852-21.6192-110.54295-37.83361-197.2433-37.83361-184.23826 0-293.69746 99.44834-293.69746 228.08262 0 108.09602 82.36534 176.19652 205.91335 219.43493 82.36533 28.10497 114.87797 48.64321 114.87797 84.3149 0 36.75265-32.51264 59.45282-99.70541 59.45282-73.6953 0-145.2231-22.70017-189.65703-45.40034l-36.84765 161.06308zM4623.27688 541.46193v728.56722h196.15955V981.41276h237.34222v288.6164h196.15955V541.46192h-196.15955v269.1591h-237.34222v-269.1591h-196.15955z" fill="#282828" fill-rule="nonzero"/>
					<g>
						<path d="M6900.00785 293.7053c5.29-14.371 11.90999-24.77099 19.84998-31.19998 7.94-6.429 16.07-9.644 24.38998-9.644 8.32 0 15.7 2.08 22.12999 6.241 6.43 4.16 10.39999 9.265 11.90999 15.31599 2.27 43.86896 4.16 92.65493 5.67 146.35689 1.51 53.70296 2.65 109.86291 3.4 168.48187.76 58.61796 1.52 118.74891 2.26999 180.39386.76 61.64396 1.33 122.71991 1.71 183.22987.37 60.50695.56 119.1269.56 175.85686 0 56.72996.38 109.28992 1.14 157.69988-3.78 12.1-10.59 20.98999-20.41999 26.65998-9.83999 5.68-19.85998 8.14-30.06997 7.38-10.21-.76-19.28999-4.73-27.22998-11.91-7.94-7.18999-11.91-17.58998-11.91-31.19997l-3.4-983.66226zm173.57987 0c5.3-14.371 11.90999-24.77099 19.85998-31.19998 7.94-6.429 16.06999-9.644 24.38998-9.644 8.32 0 15.69 2.08 22.11999 6.241 6.43 4.16 10.39999 9.265 11.91999 15.31599 2.27 43.86896 4.15 92.65493 5.67 146.35689 1.51 53.70296 2.64 109.86291 3.4 168.48187.76 58.61796 1.51999 118.74891 2.26999 180.39386.76 61.64396 1.33 122.71991 1.7 183.22987.38 60.50695.57 119.1269.57 175.85686 0 56.72996.38 109.28992 1.13 157.69988-3.78 12.1-10.59 20.98999-20.41999 26.65998-9.82999 5.68-19.84998 8.14-30.05998 7.38-10.20999-.76-19.28998-4.73-27.22997-11.91-7.94-7.18999-11.92-17.58998-11.92-31.19997l-3.4-983.66226zm-419.49969 980.25225c-6.81-4.54-13.60999-12.66999-20.41998-24.38998-6.81-11.71999-13.61-24.57998-20.41999-38.57997-6.81-13.98999-13.61999-28.16998-20.41998-42.53997-6.81-14.36999-13.99999-26.84998-21.55998-37.43997-7.56-10.58999-15.51-18.33998-23.82999-23.25998-8.31999-4.92-17.38998-4.73-27.22998.57-15.11998 24.95998-30.43997 49.15996-45.93996 72.60994-15.50999 23.44999-32.52998 43.48997-51.05996 60.12996-18.52999 16.63999-39.70997 28.35998-63.52995 35.16997-23.82999 6.81-51.62997 6.05-83.38994-2.27-31.01998-8.31999-56.16996-24.57998-75.44994-48.77996-19.28999-24.20998-33.65998-52.94996-43.10997-86.22993-9.46-33.27998-14.19-69.77995-14.19-109.48992 0-39.70397 4.35-79.22394 13.05-118.55591 8.7-39.33097 21.36998-77.14894 38.00997-113.45492 16.63999-36.30597 36.67997-67.50595 60.12995-93.60093 23.44999-26.09398 50.10997-45.75996 79.98994-58.99595 29.86998-13.237 62.20996-16.82999 96.99993-10.779 32.51998 6.051 59.36996 19.855 80.54994 41.41198 21.17998 21.55598 38.76997 47.65096 52.75996 78.28394 13.98999 30.63297 24.95998 64.47995 32.89998 101.54192 7.93999 37.06197 15.12998 74.12394 21.55998 111.18692 6.43 37.06197 12.85999 72.42194 19.28999 106.08192 6.41999 33.65997 14.92998 62.58995 25.51998 86.78993 10.58999 24.20998 24.01998 41.97997 40.27997 53.32996 16.25998 11.34 37.62997 12.84999 64.09995 4.53 30.25997-31.00998 54.45996-51.61996 72.60994-61.82996 18.15999-10.20999 31.38998-13.60999 39.70997-10.20999 8.32 3.4 11.91 11.91 10.78 25.52998-1.13 13.61-6.05 28.73998-14.75 45.37997-8.69999 16.63999-20.60998 32.89997-35.73997 48.77996-15.11999 15.88999-32.32997 27.98998-51.61996 36.30997-19.28998 8.32-40.46997 11.16-63.52995 8.51-23.06998-2.65-47.08997-14.56-72.04995-35.73998zm2413.83818 6.81c-2.26-39.32997-5.67-82.25994-10.20999-128.7699-4.53-46.51997-10.58-92.84993-18.14999-138.9899-7.55999-46.13396-16.63998-89.81493-27.22998-131.0369-10.58999-41.22197-23.06998-76.01494-37.43997-104.37892-14.36999-28.36298-30.81997-48.21797-49.34996-59.56396-18.52999-11.34499-39.51997-9.83199-62.96995 4.539-23.44998 14.37099-49.34997 43.30197-77.71994 86.79293-28.35998 43.49097-59.93996 106.08092-94.72993 187.76786-3.03 6.05-7 15.88-11.91 29.49998-4.91999 13.60999-10.20999 28.92998-15.88998 45.94997-5.67 17.01998-11.91 34.97997-18.71999 53.88996-6.8 18.90998-13.03999 37.05997-18.71998 54.45995-5.67 17.4-10.78 32.89998-15.31 46.50997-4.53999 13.61999-7.56999 23.82998-9.07998 30.63998-6.05 15.11998-13.62 23.62998-22.68999 25.52998-9.08 1.89-18.14998.18-27.22998-5.11-9.07999-5.3-17.39998-12.47999-24.95998-21.55998-7.56-9.07-12.09999-17.01999-13.61999-23.81999 6.81-26.47998 12.86-55.96995 18.15999-88.49993 5.29-32.51997 9.45-69.57995 12.47999-111.17991 3.02-41.60397 4.16-88.68794 3.4-141.2559-.76-52.56696-4.54-112.13091-11.35-178.69186 8.32-17.39599 16.65-27.03998 24.96999-28.93098 8.31999-1.891 16.63998.756 24.94998 7.942 8.32 7.18499 16.07999 17.77498 23.25998 31.76697 7.19 13.99299 13.61999 28.17498 19.28999 42.54597 5.67 14.37099 10.20999 27.79698 13.61998 40.27697 3.4 12.47999 5.1 20.61098 5.1 24.39298 16.63999-14.371 31.95998-32.71298 45.94997-55.02596 13.98999-22.31298 28.35997-44.62597 43.10996-66.93895 14.75-22.31298 30.82998-42.16697 48.21997-59.56396 17.39998-17.39598 38.19997-27.98597 62.39995-31.76697 49.91996-9.077 92.27993-3.215 127.0699 17.58499 34.79998 20.79998 63.34996 50.67696 85.65994 89.62993 22.30998 38.95297 39.32997 84.14593 51.05996 135.5789 11.72 51.43296 20.03999 103.05492 24.95998 154.86588 4.91 51.80996 6.99 101.34992 6.24 148.62989-.76 47.26996-2.65 86.02993-5.68 116.2899-8.32 17.39-19.46998 26.08999-33.46997 26.08999-13.99 0-25.13998-8.7-33.46998-26.08998zm-1029.72922-9.08c-43.86997-18.14998-78.46994-41.97996-103.80992-71.46994-25.33998-29.49998-43.10997-61.83995-53.32996-97.00993-10.21-35.16997-13.61-72.03994-10.21-110.61791 3.41-38.57497 12.48-76.20395 27.22999-112.88792 14.74998-36.68397 34.41997-71.28794 58.99995-103.81092 24.57998-32.52398 52.56996-60.32095 83.95994-83.38994 31.38997-23.06898 65.79995-40.08797 103.23992-51.05496 37.43997-10.967 76.20994-13.42599 116.28991-7.375 33.27998 5.295 61.83995 20.99 85.65994 47.08397 23.82998 26.09498 42.73996 58.42996 56.72995 97.00493 13.99 38.57397 22.87999 80.93094 26.65998 127.0699 3.78 46.13797 1.7 91.70893-6.24 136.7079-7.93999 45.00996-21.55997 86.79993-40.83996 125.3699-19.28999 38.57998-44.62997 69.77995-76.01994 93.59993-31.38998 23.82999-69.39995 37.81998-114.01992 41.97997-44.62996 4.16-96.05992-6.24-154.29988-31.19997zm-642.42952 0c-43.86996-18.14998-78.46994-41.97996-103.80992-71.46994-25.33998-29.49998-43.10997-61.83995-53.31996-97.00993-10.20999-35.16997-13.61999-72.03994-10.20999-110.61791 3.4-38.57497 12.48-76.20395 27.21998-112.88792 14.74999-36.68397 34.41997-71.28794 58.99996-103.81092 24.57998-32.52398 52.56996-60.32095 83.95993-83.38994 31.38998-23.06898 65.79995-40.08797 103.23992-51.05496 37.43998-10.967 76.20995-13.42599 116.29992-7.375 33.27997 5.295 61.82995 20.99 85.64993 47.08397 23.82998 26.09498 42.73997 58.42996 56.72996 97.00493 13.98999 38.57397 22.87998 80.93094 26.65998 127.0699 3.79 46.13797 1.71 91.70893-6.24 136.7079-7.94 45.00996-21.54998 86.79993-40.83997 125.3699-19.28998 38.57998-44.62996 69.77995-76.01994 93.59993-31.38997 23.82999-69.38995 37.81998-114.01991 41.97997-44.61997 4.16-96.05993-6.24-154.29989-31.19997zm-1823.64862-14.69998c-5.29-34.31998-9.64-71.39995-13.04999-111.24992-3.4-39.85997-6.24-80.95994-8.5-123.2999-2.27-42.34497-3.79-85.24294-4.54-128.6939-.75999-43.45198-1.13999-86.07294-1.13999-127.86391 0-41.78997.38-81.91994 1.14-120.38991.75-38.46997 1.89-74.30995 3.4-107.52092 2.27-9.41 8.13-15.63699 17.58998-18.68199 9.45-3.044 19.65999-3.736 30.62998-2.075 10.97 1.66 20.98998 5.12 30.06998 10.378 9.07 5.259 13.98999 11.48599 14.73999 18.68198-1.51 31.54998-2.64 62.40896-3.4 92.57593-.76 30.16698-.57 59.91796.57 89.25494 1.13 29.33597 3.4 58.81095 6.81 88.42493 3.4 29.61298 8.12999 59.64095 14.17998 90.08493 35.54998-34.31797 72.03995-55.90596 109.47992-64.76195 37.43997-8.856 72.79995-8.441 106.07992 1.245 33.27998 9.687 63.72995 26.56898 91.32993 50.64796 27.60998 24.07798 49.54996 51.61496 65.80995 82.61194 16.25999 31.00198 25.89998 63.65195 28.92998 97.97192 3.02 34.31998-3.22 66.41995-18.71999 96.30993-15.50998 29.88998-41.40996 55.62996-77.71994 77.21994-36.29997 21.58999-85.46993 35.42998-147.48989 41.50997-27.22998 2.77-50.86996 4.99-70.90994 6.65-20.03999 1.66-38.94997 1.8-56.72996.41-17.76999-1.38-35.91997-5.12-54.45996-11.21-18.52998-6.08999-39.89997-15.49998-64.09995-28.22997zm85.08994-154.42989c-9.83 32.09998-11.34 58.25996-4.53 78.45994 6.8 20.20999 18.89998 35.00998 36.29997 44.41997 17.39999 9.41 38.57997 14.11999 63.53995 14.11999 24.95998 0 50.66997-3.74 77.13995-11.21 26.47998-7.46999 52.37996-18.12998 77.71994-31.96997 25.33998-13.83999 47.08996-30.15997 65.23995-48.97996 13.60999-13.83999 20.79998-30.58998 21.55998-50.23996.75-19.64999-2.84-39.70997-10.78-60.18996-7.94998-20.47998-19.85998-40.13097-35.73996-58.95095-15.88-18.81999-33.65998-34.31798-53.31996-46.49597-19.66999-12.17699-40.65997-19.64998-62.96996-22.41698-22.31998-2.768-44.24996 1.799-65.80995 13.69899-21.54998 11.90099-41.78996 32.10397-60.69995 60.61095-18.90999 28.50398-34.78997 68.22395-47.64996 119.14391zm2380.9882 74.95995c49.15996 31.76997 93.21993 45.00996 132.1799 39.70997 38.94997-5.29 71.65995-21.92999 98.12993-49.91997 26.47998-27.97997 46.32996-63.71995 59.56995-107.20991 13.24-43.48997 18.90999-87.92994 17.01999-133.3119-1.9-45.38197-11.73-87.54994-29.49998-126.5029-17.77999-38.95298-44.81997-68.26196-81.11994-87.92694-20.41998-10.59-44.24997-10.022-71.47994 1.701-27.22998 11.72399-53.88996 30.63297-79.97994 56.72795-26.09998 26.09498-49.73997 57.29496-70.90995 93.60093-21.17999 36.30498-35.54997 73.55695-43.11997 111.75292-7.56 38.19897-6.62 75.06894 2.84 110.61892 9.45 35.54997 31.57998 65.79995 66.36995 90.75993zm-642.42952 0c49.16997 31.76997 93.21993 45.00996 132.1799 39.70997 38.94997-5.29 71.65995-21.92999 98.13993-49.91997 26.46998-27.97997 46.31997-63.71995 59.55996-107.20991 13.23999-43.48997 18.90998-87.92994 17.01998-133.3119-1.89-45.38197-11.71999-87.54994-29.49998-126.5029-17.76998-38.95298-44.80996-68.26196-81.11993-87.92694-20.41999-10.59-44.24997-10.022-71.47995 1.701-27.22998 11.72399-53.88996 30.63297-79.97994 56.72795-26.09998 26.09498-49.72996 57.29496-70.90995 93.60093-21.17998 36.30498-35.54997 73.55695-43.10996 111.75292-7.57 38.19897-6.62 75.06894 2.83 110.61892 9.45999 35.54997 31.57997 65.79995 66.36994 90.75993zm-1159.18912-39.69997c19.65998 30.24997 40.26997 47.64996 61.82995 52.18996 21.55999 4.53 42.53997.56 62.96995-11.92 20.41999-12.47998 39.70997-31.00997 57.85996-55.58995 18.14999-24.57998 33.65998-50.86996 46.51997-78.84994 12.84999-27.98998 22.30998-55.40696 28.35997-82.25794 6.05-26.85098 7.56-48.97496 4.54-66.37095-3.78-18.15299-6.81-34.41497-9.08-48.78596-2.27-14.371-4.72999-27.22898-7.36999-38.57497-2.65-11.345-5.68-21.74599-9.07999-31.19998-3.4-9.455-8.13-19.09799-14.17999-28.93098-30.25998-21.17898-58.42996-29.49898-84.52994-24.95998-26.08998 4.538-49.53996 17.39599-70.33994 38.57397-20.79999 21.17898-38.18997 48.40796-52.18996 81.68794-13.99 33.27997-24.19998 68.07295-30.62998 104.37892-6.43 36.30597-8.51 71.47995-6.24 105.50992 2.27 34.03998 9.45 62.39995 21.55999 85.09994z" fill="#282828" fill-rule="nonzero"/>
						<path d="M6892.93785 1141.07765l-2.93-847.33736c-.01-1.191.2-2.374.61-3.492 6.06-16.43098 13.87-28.16497 22.94999-35.51497 9.95999-8.065 20.24998-11.87199 30.67997-11.87199 10.37 0 19.54999 2.66 27.55998 7.845 8.86 5.732 14.1 12.94799 16.18 21.28698.16.625.25 1.264.29 1.908 2.26999 43.93997 4.15999 92.80393 5.67999 146.59289 1.51 53.75096 2.65 109.96191 3.4 168.63387.76 58.61996 1.52 118.75391 2.27 180.39986.76 61.66396 1.33 122.76091 1.71 183.28987.37 60.52995.56 119.1699.56 175.91986 0 56.66996.38 109.18992 1.13999 157.54988.01 1.06-.14 2.12-.46 3.13-4.6 14.73-12.99999 25.43998-24.96998 32.34998-11.7 6.75-23.64998 9.58-35.79997 8.68-12.44-.92-23.51999-5.71-33.19998-14.47-9.87-8.93-15.19999-21.69998-15.19999-38.57997l-.25-72.25994c-2.06 5.06-4.48 10.24999-7.27 15.58998-9.08998 17.41-21.52998 34.43998-37.35996 51.04997-16.08 16.88998-34.38998 29.74997-54.89996 38.58997-20.83999 8.98999-43.70997 12.12999-68.62995 9.25999-24.60998-2.82-50.33996-15.20999-76.94994-37.68997-7.62-5.23-15.41999-14.25-23.02998-27.34998-6.92-11.92-13.84-24.98998-20.75999-39.21997-6.83-14.02-13.64999-28.23998-20.46998-42.63997-6.53-13.77999-13.4-25.75998-20.65999-35.90997-6.62-9.27-13.48999-16.15999-20.76998-20.45999-4.67-2.76-9.71-2.7-15.12-.35-14.69998 24.18998-29.57997 47.66997-44.62996 70.42995-16.00999 24.20998-33.58997 44.87997-52.71996 62.05995-19.67998 17.66999-42.16997 30.11998-67.46995 37.34997-25.32998 7.23-54.88996 6.63-88.72993-2.23-33.15997-8.89999-60.03995-26.31997-80.66994-52.20995-20.07998-25.18998-35.06997-55.08996-44.90996-89.72994-9.7-34.10997-14.57-71.50994-14.57-112.21991 0-40.42697 4.43-80.66694 13.29-120.71491 8.84999-40.02697 21.73998-78.51394 38.67997-115.46191 17.08998-37.28898 37.69997-69.31695 61.77995-96.11793 24.43998-27.19398 52.23996-47.66197 83.36994-61.45595 31.65997-14.024 65.90995-17.899 102.88992-11.467 34.67997 6.452 63.26995 21.24799 85.85994 44.23397 21.94998 22.34798 40.20996 49.38096 54.70995 81.13794 14.28 31.25498 25.48998 65.78695 33.58998 103.60192 7.97 37.19097 15.17999 74.38195 21.62998 111.57192 6.42 37.00197 12.84 72.31194 19.25999 105.91192 6.27 32.82997 14.53999 61.05995 24.85998 84.65993 9.73 22.24999 21.89998 38.70997 36.83997 49.12997 13.55 9.45999 31.25998 10.32999 53.02996 3.92 30.31998-30.90998 54.72996-51.40997 73.05995-61.72996 12.16999-6.84 22.40998-10.8 30.62997-12.17 7.06-1.17999 12.97-.53999 17.76999 1.42 3.08 1.26 5.82 2.97 8.15 5.15zm171.26987-850.82935c-.41 1.118-.62 2.301-.62 3.492l3.4 983.65725c0 16.87999 5.34 29.64998 15.21 38.57997 9.67998 8.76 20.75997 13.55 33.19997 14.47 12.14999.9 24.09998-1.93 35.79997-8.68 11.95999-6.91 20.36998-17.61999 24.96998-32.34998.32-1.01.47-2.07.45-3.13-.75-48.35996-1.13-100.87992-1.13-157.54988 0-56.74995-.19-115.3899-.57-175.91986-.38-60.52896-.94-121.62591-1.7-183.28987-.76-61.64595-1.51-121.7799-2.27-180.39986-.76-58.67196-1.89-114.88291-3.41-168.63387-1.51-53.78896-3.4-102.65292-5.67999-146.5929-.03-.644-.13-1.283-.28-1.90799-2.09-8.339-7.32-15.55499-16.17999-21.28698-8.02-5.185-17.18998-7.845-27.55998-7.845-10.43999 0-20.71998 3.807-30.68997 11.872-9.08 7.34999-16.88999 19.08398-22.93999 35.51497zm1588.0788 521.3466c11.02-11.49199 21.36999-24.98198 31.06998-40.44997 14.03-22.37998 28.44998-44.75996 43.23997-67.13995 15.13999-22.89798 31.63998-43.26796 49.48996-61.12095 18.93999-18.93699 41.57997-30.45998 67.67995-34.53497 52.65996-9.574 97.29993-3.098 133.9899 18.84098 36.21997 21.64899 65.98995 52.69896 89.20993 93.24193 22.76999 39.74697 40.15997 85.84694 52.12996 138.3279 11.82 51.85696 20.20999 103.90492 25.15998 156.14788 4.96 52.18996 7.05 102.09992 6.29 149.72989-.77 47.60996-2.68 86.64993-5.73 117.1199-.11 1.16-.43 2.28-.92 3.32-10.40999 21.74999-24.99998 31.77998-42.49996 31.77998-17.48999 0-32.07998-10.03-42.48997-31.77997-.56-1.17-.88-2.44-.96-3.73-2.26-39.21997-5.65-82.00994-10.18-128.3799-4.51999-46.29997-10.53998-92.40994-18.06998-138.3399-7.51-45.82997-16.51999-89.21993-27.03998-130.1689-10.38999-40.41497-22.58998-74.53795-36.67997-102.34693-13.35999-26.36698-28.42998-45.00796-45.64997-55.55495-15.47998-9.474-32.93997-7.465-52.51996 4.536-22.56998 13.82998-47.26996 41.87496-74.56994 83.72993-28.12998 43.12897-59.40996 105.21592-93.90993 186.22486-.08.19-.17.37-.26.55-2.91 5.83-6.71 15.30999-11.45 28.42998-4.88999 13.53999-10.15998 28.77998-15.79998 45.70996-5.7 17.09-11.95999 35.12998-18.79998 54.11996-6.77 18.80999-12.98 36.85997-18.61999 54.16996-5.68 17.41999-10.79 32.93998-15.33999 46.57997-4.39 13.16999-7.33 23.04998-8.8 29.63997-.12.52-.28 1.04-.48 1.54-7.70999 19.27999-18.35998 29.19998-29.92997 31.59998-11.43 2.39-22.87998.41-34.30997-6.25-10.03-5.85-19.24999-13.76999-27.59998-23.78998-8.86-10.63999-13.93-20.08998-15.7-28.05998-.33999-1.54-.30999-3.14.08-4.66 6.74-26.20997 12.73-55.41995 17.97-87.60993 5.25-32.26997 9.36999-69.03995 12.36999-110.30991 3.01-41.34297 4.13-88.13794 3.38-140.3819-.75-52.31096-4.52-111.58291-11.29-177.81786-.19-1.829.13-3.674.92-5.332 10.19-21.30698 21.57999-32.05198 31.76998-34.36797 11.17999-2.541 22.52998.468 33.70997 10.12499 9.13 7.881 17.73999 19.41898 25.61998 34.76697 7.34 14.288 13.9 28.76898 19.68999 43.44197 5.82 14.74199 10.46999 28.51598 13.95999 41.31797.7 2.54 1.32 4.919 1.87 7.135zm-1260.43904 469.29265c-45.43997-18.81999-81.21994-43.59997-107.46992-74.15995-26.30998-30.62997-44.73997-64.20995-55.34996-100.72992-10.55-36.33997-14.07999-74.42994-10.56-114.28691 3.48-39.54797 12.79-78.12894 27.90999-115.73892 15.06999-37.49597 35.16997-72.86794 60.28995-106.11092 25.18998-33.31797 53.85996-61.78595 86.01994-85.41793 32.32997-23.76398 67.77995-41.29597 106.34992-52.59396 38.82997-11.373 79.02994-13.941 120.6799-7.653 35.51998 5.652 66.02996 22.35899 91.46994 50.21697 24.64998 26.99898 44.25996 60.42495 58.73995 100.33692 14.28 39.36297 23.36998 82.58094 27.22998 129.6629 3.85 46.99997 1.73 93.42293-6.36 139.2649-8.10999 45.98996-22.03998 88.68993-41.74996 128.1099-20.00999 40.01997-46.33997 72.36995-78.90994 97.08993-32.80998 24.89998-72.49995 39.61997-119.13991 43.96996-46.01997 4.29-99.08993-6.22-159.14988-31.95997zm642.41951 0c-45.43996-18.81999-81.21994-43.59997-107.46992-74.15995-26.30998-30.62997-44.73996-64.20995-55.33995-100.72992-10.55-36.33997-14.08-74.42994-10.57-114.28691 3.49-39.54797 12.79-78.12894 27.90998-115.73892 15.08-37.49597 35.17998-72.86794 60.29996-106.11092 25.17998-33.31797 53.85996-61.78595 86.00993-85.41793 32.33998-23.76398 67.78995-41.29597 106.35992-52.59396 38.82997-11.373 79.01994-13.941 120.66991-7.653 35.52997 5.652 66.03995 22.35899 91.47993 50.21697 24.64998 26.99898 44.25997 60.42495 58.73996 100.33692 14.27999 39.36297 23.36998 82.58094 27.22998 129.6629 3.85 46.99997 1.73 93.42293-6.36 139.2649-8.12 45.98996-22.03998 88.68993-41.74997 128.1099-20.00998 40.01997-46.33996 72.36995-78.90994 97.08993-32.80997 24.89998-72.49994 39.61997-119.1399 43.96996-46.01997 4.29-99.09993-6.22-159.15989-31.95997zM6968.3578 276.0543c-1.1-3.399-3.7-6.152-7.41999-8.557-4.84-3.135-10.41999-4.636-16.68999-4.636-6.2 0-12.17999 2.622-18.09998 7.417-6.5 5.259-11.73 13.762-16.13999 25.24198l3.4 981.84726c0 10.31 2.6 18.33999 8.62 23.77998 6.20999 5.62 13.27998 8.76 21.25998 9.36 8.26999.61 16.35998-1.47 24.32998-6.07 7.31-4.21 12.36999-10.78 15.39999-19.52998-.75-47.98997-1.12-100.04993-1.12-156.16989 0-56.70995-.19-115.30991-.56-175.79486-.38-60.48896-.95-121.54591-1.7-183.16987-.76-61.64195-1.52-121.7709-2.27-180.38686-.76-58.56596-1.89-114.67491-3.4-168.32887-1.5-53.15996-3.37-101.49493-5.61-145.0029zm173.57988 0c-1.1-3.399-3.69-6.152-7.41-8.557-4.84-3.135-10.42-4.636-16.68999-4.636-6.21 0-12.17999 2.622-18.09998 7.417-6.5 5.259-11.74 13.762-16.14 25.24198l3.39 981.84726c0 10.31 2.61 18.33999 8.63 23.77998 6.2 5.62 13.27999 8.76 21.25998 9.36 8.27.61 16.36-1.47 24.31999-6.07 7.31-4.21 12.36999-10.78 15.39998-19.52998-.74-47.98997-1.11-100.04993-1.11-156.16989 0-56.70995-.19-115.30991-.57-175.79486-.37-60.48896-.94-121.54591-1.7-183.16987-.75-61.64195-1.51-121.7709-2.27-180.38686-.75-58.56596-1.88999-114.67491-3.39999-168.32887-1.49-53.15996-3.36-101.49493-5.61-145.0029zm-1474.8589 611.05154c32.78998-28.61098 66.40996-46.87097 100.71993-54.98596 39.23997-9.282 76.29994-8.777 111.17992 1.375 34.64997 10.08599 66.35995 27.64098 95.10993 52.71196 28.56997 24.91798 51.24996 53.42596 68.07995 85.50393 16.88998 32.18698 26.89997 66.10695 30.03997 101.73693 3.2 36.27997-3.42 70.20994-19.80998 101.79992-16.27999 31.37997-43.34997 58.53995-81.47994 81.19994-37.32997 22.19998-87.83993 36.60997-151.58989 42.86996-27.29998 2.78-50.99996 5-71.08994 6.66-20.60999 1.71-40.05997 1.84-58.32996.42-18.53999-1.44-37.47997-5.33-56.80996-11.68-18.96998-6.22999-40.84997-15.83998-65.62995-28.87997-2.81-1.47-4.75-4.19-5.23-7.32-5.32999-34.52997-9.70999-71.83994-13.12998-111.92991-3.41-39.95997-6.26-81.15994-8.53-123.6199-2.28-42.45897-3.79-85.47694-4.55-129.0499-.76-43.51098-1.14-86.18994-1.14-128.03791 0-41.85797.38-82.05394 1.14-120.58691.76-38.56197 1.89-74.48795 3.41-107.77892.03-.637.12-1.27.27-1.889 3.13-12.99999 11.18-21.65098 24.23999-25.85598 10.86999-3.498 22.58998-4.353 35.19997-2.445 12.24999 1.856 23.43998 5.739 33.57997 11.614 12.52 7.25499 18.62999 16.35998 19.67999 26.28797.05.506.07 1.016.04 1.524-1.51 31.47298-2.64 62.25596-3.39 92.34793-.75 29.95198-.57 59.49096.56 88.61794 1.12 29.08597 3.37 58.30895 6.75 87.66993 2.72 23.63898 6.28 47.54596 10.70999 71.71995zm992.55926 378.53171c-5.84-3.89-11.48-11.03-17.31999-21.08998-6.7-11.53-13.38999-24.16999-20.07998-37.92998-6.79-13.95998-13.58-28.10997-20.37999-42.44996-7.08-14.97-14.57999-27.94998-22.44998-38.97997-8.51-11.9-17.51999-20.51999-26.87998-26.04998-11.32-6.69-23.67998-6.83-37.05997.37-1.57.85-2.88 2.1-3.81 3.62-15.05999 24.84997-30.29998 48.93996-45.73996 72.27994-15 22.68998-31.45998 42.10997-49.38997 58.20995-17.37998 15.61-37.24997 26.60998-59.59995 32.99998-22.31999 6.37-48.34997 5.46-78.10994-2.33-28.79998-7.73-52.21996-22.82998-70.15995-45.34996-18.49999-23.20999-32.24998-50.79997-41.31997-82.71994-9.21-32.44998-13.79999-68.03995-13.79999-106.75992 0-38.98097 4.27-77.78094 12.81-116.39591 8.54998-38.63497 20.98998-75.78495 37.33996-111.44792 16.19-35.32397 35.65998-65.69495 58.47996-91.08393 22.45998-24.99598 47.97996-43.85797 76.59994-56.53696 28.08998-12.44899 58.50996-15.75999 91.23993-10.069 30.24998 5.628 55.35996 18.44 75.12995 38.56698 20.39998 20.76598 37.30997 45.92097 50.78996 75.43094 13.70999 30.00998 24.43998 63.17396 32.21997 99.48293 7.92 36.93297 15.08 73.86594 21.48999 110.79991 6.43 37.12298 12.86999 72.53295 19.30998 106.24292 6.59 34.48998 15.34 64.12996 26.18998 88.92994 11.45 26.16998 26.13998 45.24996 43.71997 57.51995 18.48999 12.9 42.71997 15.33 72.81994 5.87 1.58-.49 3.01-1.37 4.16-2.55 29.34998-30.08998 52.73996-50.19996 70.35995-60.09995 8.15-4.59 15.17999-7.72 21.11998-9.24 4.06-1.05 7.35-1.48 9.9-.44 4.83 1.98 5.26 7.53 4.6 15.45-1.04 12.47998-5.67 26.31997-13.65 41.57996-8.3 15.86999-19.68998 31.36998-34.11997 46.51997-14.17 14.87998-30.26998 26.22998-48.33997 34.01997-17.73998 7.65-37.21997 10.19-58.42995 7.76-21.40999-2.46-43.55997-13.78-66.71995-33.42998l-.92-.7zm2465.44814 12.35c2.91-29.76999 4.72-67.65996 5.46-113.66992.75-46.92997-1.32-96.09993-6.2-147.5199-4.87-51.38895-13.12999-102.58491-24.74998-153.59388-11.49-50.38496-28.12998-94.67092-49.98996-132.8309-21.39999-37.36197-48.73997-66.06595-82.10994-86.01693-32.88998-19.65999-72.95995-24.90898-120.38991-16.28799-22.05998 3.447-41.01997 13.102-56.87996 28.95798-16.93999 16.93999-32.57997 36.27997-46.93996 58.00796-14.71 22.24498-29.03998 44.49096-42.98997 66.73695-14.56999 23.23798-30.54998 42.31396-47.87996 57.28095-2.96 2.557-7.14 3.153-10.7 1.525-3.56-1.628-5.84-5.181-5.84-9.093 0-3.38099-1.70999-10.60698-4.74999-21.76198-3.32-12.15799-7.74-25.23598-13.26999-39.23597-5.55-14.06799-11.84999-27.95098-18.87998-41.64996-6.49-12.637-13.39-22.27799-20.89999-28.76698-5.47-4.718-10.73999-7-16.20999-5.759-2.45.558-4.67 2.587-7.11999 5.432-3.3 3.817-6.54 9.02999-9.82 15.58699 6.66 65.73995 10.36 124.6399 11.11 176.70886.76 52.89196-.39 100.26493-3.43 142.1199-3.05 41.92996-7.25 79.28994-12.57999 112.06991-5.18 31.79998-11.08 60.72995-17.68999 86.79993 1.68 5.13 5.45 10.9 10.96 17.51 6.77 8.11999 14.18999 14.57998 22.31998 19.31998 6.72 3.93 13.41999 5.36 20.14998 3.96 6.46-1.35 10.86-8.16 15.16-18.77 1.62-7.01999 4.65999-17.27998 9.15999-30.76997 4.53-13.58999 9.62999-29.07998 15.29998-46.44996 5.7-17.48999 11.97-35.73998 18.80999-54.74996 6.78-18.82999 12.99999-36.71997 18.63999-53.65996 5.71-17.10999 11.02999-32.49998 15.96998-46.18997 5.02-13.88999 9.11-23.97298 12.22-30.26797 35.04997-82.24394 66.88994-145.2539 95.45992-189.06286 29.42998-45.12797 56.52996-74.94494 80.85994-89.85593 27.31998-16.744 51.82996-17.75999 73.41995-4.541 19.83998 12.144 37.66997 33.21197 53.04996 63.57295 14.64998 28.91898 27.40998 64.38095 38.20997 106.40992 10.65999 41.49597 19.79998 85.46594 27.40998 131.9149 7.6 46.34997 13.67999 92.88993 18.23998 139.6299 4.47 45.84996 7.84 88.22993 10.12 127.1199 6.08999 12 13.56998 18.70999 23.59998 18.70999 10.08999 0 17.58998-6.77 23.68998-18.86999zm-1725.4887-15.54c-42.25997-17.47998-75.64994-40.33997-100.04992-68.74995-24.36999-28.36997-41.48997-59.44995-51.30996-93.27993-9.87-33.99997-13.14-69.64994-9.85-106.94891 3.31-37.60098 12.17-74.27895 26.53998-110.03592 14.43-35.87297 33.65998-69.70795 57.69996-101.51292 23.97998-31.72998 51.27996-58.85496 81.89994-81.36094 30.43997-22.37399 63.81995-38.87897 100.12992-49.51597 36.05997-10.56199 73.38995-12.91099 111.98992-7.084 30.95997 4.925 57.54995 19.607 79.76994 43.93898 22.99998 25.18998 41.19997 56.43395 54.70996 93.67193 13.70999 37.78597 22.38998 79.28094 26.09998 124.4769 3.71 45.27597 1.67 89.99593-6.12 134.1609-7.77 44.01997-21.07998 84.89994-39.94997 122.6299-18.55999 37.11998-42.89997 67.17996-73.10994 90.10994-29.96998 22.74998-66.29995 36.00997-108.90992 39.98997-43.22997 4.03-93.00993-6.26-149.42989-30.43998l-.11-.05zm642.41952 0c-42.24997-17.47998-75.63995-40.33997-100.04993-68.74995-24.35998-28.36997-41.47997-59.44995-51.29996-93.27993-9.87-33.99997-13.14999-69.64994-9.86-106.94891 3.32-37.60098 12.17-74.27895 26.54999-110.03592 14.41999-35.87297 33.65997-69.70795 57.69995-101.51292 23.97999-31.72998 51.27997-58.85496 81.89994-81.36094 30.43998-22.37399 63.81995-38.87897 100.12993-49.51597 36.05997-10.56199 73.38994-12.91099 111.98991-7.084 30.94998 4.925 57.54996 19.607 79.76994 43.93898 22.99999 25.18998 41.19997 56.43395 54.70996 93.67193 13.7 37.78597 22.38998 79.28094 26.08998 124.4769 3.71 45.27597 1.68 89.99593-6.12 134.1609-7.76999 44.01997-21.06998 84.89994-39.93996 122.6299-18.55999 37.11998-42.90997 67.17996-73.10995 90.10994-29.96998 22.74998-66.29995 36.00997-108.90992 39.98997-43.22996 4.03-93.00993-6.26-149.42988-30.43998l-.12-.05zM5632.4288 546.7151c-.72-4.174-4.34-7.351-9.72999-10.47199-8.01-4.642-16.86999-7.678-26.54998-9.144-9.33-1.413-18.01998-.883-26.06998 1.707-5.56 1.792-9.16 5.322-10.71 10.675-1.47999 32.83197-2.59999 68.23495-3.33999 106.20592-.76 38.40597-1.14 78.47094-1.14 120.1929 0 41.73398.38 84.29694 1.14 127.68891.75 43.32997 2.26 86.10694 4.52 128.3289 2.26 42.23997 5.09 83.22994 8.49 122.97991 3.21999 37.68997 7.27999 72.88995 12.20998 105.58992 21.78999 11.26 41.14997 19.67999 58.09996 25.24998 17.72999 5.83 35.09997 9.42 52.10996 10.74 17.26999 1.35 35.64997 1.2 55.11996-.41 19.99998-1.66 43.56997-3.87 70.75994-6.63 60.26996-5.91 108.08992-19.17999 143.3599-40.15997 34.48997-20.49998 59.21995-44.82997 73.94994-73.21994 14.61999-28.18998 20.48999-58.46996 17.63999-90.82994-2.91-32.99997-12.19-64.39995-27.82998-94.20593-15.68999-29.91597-36.86997-56.48395-63.51995-79.72193-26.46998-23.08499-55.63996-39.29498-87.54994-48.58197-31.67997-9.221-65.34995-9.546-100.98992-1.115-35.87997 8.488-70.76995 29.33298-104.83992 62.22396-2.63 2.541-6.44 3.442-9.93 2.349-3.49-1.093-6.10999-4.005-6.81999-7.594-6.11-30.71598-10.88-61.01395-14.30999-90.89293-3.43-29.86598-5.72-59.59296-6.86-89.17993-1.15-29.54598-1.34-59.50996-.58-89.89194.75-29.94797 1.88-60.57595 3.37-91.88193zm15.14 553.17259c13.18998-52.14997 29.57997-92.78993 48.95996-122.00191 19.95998-30.08698 41.44996-51.27696 64.19995-63.83695 23.53998-12.994 47.49996-17.891 71.86994-14.869 23.73999 2.944 46.07997 10.883 66.99995 23.83899 20.53999 12.71799 39.10997 28.89298 55.69996 48.54796 16.63999 19.71899 29.09998 40.32097 37.41997 61.78096 8.47 21.83998 12.25 43.24996 11.45 64.19995-.86 22.23998-9.01 41.18997-24.34999 56.78995-18.82998 19.51999-41.36997 36.46998-67.63995 50.81997-26.01998 14.20999-52.61996 25.13998-79.79994 32.80997-27.39998 7.74-54.02996 11.59-79.85994 11.59-26.84998 0-49.58996-5.2-68.29994-15.32-19.60999-10.60999-33.33998-27.23998-41.01997-50.02996-7.32-21.70998-6.15-49.83996 4.37-84.31993zm19.33998 5.12c12.51999-49.58997 27.86998-88.30994 46.28996-116.06692 17.85999-26.92498 36.82998-46.14197 57.19996-57.38296 19.56999-10.80799 39.46997-15.04399 59.73996-12.52999 20.87998 2.59 40.51996 9.597 58.92995 20.99499 18.78999 11.63699 35.76997 26.45898 50.94996 44.44396 15.12 17.92099 26.48998 36.61097 34.04998 56.11096 7.42 19.12999 10.81999 37.84997 10.10999 56.19996-.65 17.04998-6.87 31.58997-18.68999 43.59996-17.54998 18.2-38.49997 33.89998-62.89995 47.22997-24.65998 13.46999-49.86996 23.83998-75.63994 31.10998-25.53998 7.20999-50.34996 10.83999-74.42995 10.83999-23.07998 0-42.69996-4.21-58.77995-12.91-15.18-8.20999-25.64998-21.19998-31.58998-38.81996-6.28-18.63999-4.44-42.72997 4.63-72.33995l.13-.48zm1723.4387 80.90993c51.62996 33.36998 98.03992 46.77997 138.9499 41.21997 41.29996-5.61 75.97994-23.27998 104.04991-52.95996 27.45998-29.02998 48.13997-66.05995 61.86996-111.16992 13.55999-44.57996 19.37998-90.12293 17.43998-136.6379-1.95-46.72396-12.08999-90.13293-30.38997-130.2379-18.71999-41.02096-47.21997-71.85994-85.45994-92.56893-23.01998-11.93999-49.70996-11.81599-80.18994 1.31-28.27998 12.173-56.00995 31.74398-83.09993 58.84096-26.66998 26.66498-50.83997 58.53395-72.47995 95.63293-21.75998 37.30897-36.50997 75.59694-44.27997 114.84991-7.87999 39.75097-6.86 78.13094 2.98 115.13091 10.02 37.67997 33.31998 69.85995 70.19995 96.31993l.41.27zm642.41951 0c51.62996 33.36998 98.04993 46.77997 138.9499 41.21997 41.30997-5.61 75.98994-23.27998 104.05992-52.95996 27.45998-29.02998 48.12996-66.05995 61.86995-111.16992 13.56-44.57996 19.37999-90.12293 17.43999-136.6379-1.95-46.72396-12.09-90.13293-30.38998-130.2379-18.71998-41.02096-47.22996-71.85994-85.45993-92.56893-23.01998-11.93999-49.70996-11.81599-80.18994 1.31-28.27998 12.173-56.00996 31.74398-83.10994 58.84096-26.65998 26.66498-50.82996 58.53395-72.46994 95.63293-21.76999 37.30897-36.51998 75.59694-44.28997 114.84991-7.87 39.75097-6.86 78.13094 2.98 115.13091 10.02999 37.67997 33.32997 69.85995 70.20994 96.31993l.4.27zm11.07-16.65999c46.60996 30.07998 88.23993 43.08997 125.1899 38.06997 36.59997-4.98 67.34995-20.58998 92.21993-46.88996 25.47998-26.93998 44.51997-61.38995 57.25996-103.24992 12.90999-42.40997 18.43998-85.73594 16.58999-129.9859-1.83-44.03997-11.35-84.96594-28.59998-122.76691-16.82999-36.88497-42.40997-64.66495-76.62995-83.20194-17.97998-9.323-38.93997-8.313-62.91995 2.009-26.17998 11.274-51.76996 29.52098-76.85994 54.61396-25.52998 25.52498-48.62996 56.05596-69.34995 91.56793-20.58998 35.30297-34.57997 71.51695-41.93997 108.65792-7.24999 36.63597-6.38 72.00594 2.69 106.10592 8.87 33.34997 29.74998 61.62995 62.34996 85.06993zm-642.42952 0c46.60996 30.07998 88.24993 43.08997 125.1899 38.06997 36.59998-4.98 67.34995-20.58998 92.21994-46.88996 25.48998-26.93998 44.51996-61.38995 57.25995-103.24992 12.91-42.40997 18.43999-85.73594 16.59999-129.9859-1.84-44.03997-11.36-84.96594-28.60998-122.76691-16.82999-36.88497-42.39997-64.66495-76.61994-83.20194-17.97999-9.323-38.94997-8.313-62.91995 2.009-26.18998 11.274-51.77996 29.52098-76.86995 54.61396-25.52998 25.52498-48.62996 56.05596-69.33994 91.56793-20.59999 35.30297-34.58998 71.51695-41.94997 108.65792-7.25 36.63597-6.37 72.00594 2.7 106.10592 8.86999 33.34997 29.73997 61.62995 62.33995 85.06993zm-1173.21912-25.98998c21.51999 33.09998 44.56997 51.54996 68.15995 56.51996 24.03999 5.06 47.46997.75 70.23995-13.16999 21.39998-13.06999 41.66997-32.41998 60.68995-58.17996 18.56-25.12998 34.41998-52.00996 47.55997-80.61994 13.16999-28.64997 22.83998-56.73495 29.03998-84.22993 6.4-28.42898 7.83-51.86396 4.63-70.28295l-.06-.326c-3.75-17.97399-6.74-34.07597-8.99-48.30596-2.31-14.636-4.82-27.73198-7.52-39.28697-2.74-11.752-5.86999-22.52199-9.39999-32.31498-3.62-10.059-8.64-20.32498-15.06999-30.78498-.72-1.164-1.67-2.168-2.79-2.952-32.86997-23.00798-63.61995-31.54997-91.96992-26.61997-28.08998 4.885-53.36996 18.62598-75.75995 41.41997-21.60998 21.99998-39.73997 50.24796-54.27996 84.81893-14.26999 33.96098-24.69998 69.46395-31.25997 106.51092-6.57 37.13497-8.69 73.11395-6.37 107.92392 2.38 35.65997 10.03 65.34995 22.70999 89.12993l.44.75zm223.31984-388.7207c-26.98998-18.50399-52.01996-26.18998-75.36995-22.12799-24.10998 4.192-45.70996 16.16699-64.91995 35.72898-19.99998 20.35698-36.65997 46.56796-50.10996 78.55694-13.70999 32.59997-23.70998 66.68295-29.99998 102.24692-6.29 35.47697-8.33 69.84595-6.11 103.10592 2.15 32.21998 8.8 59.13996 20.2 80.67994 17.73998 27.17998 35.82996 43.38997 55.26995 47.47996 19.06999 4.02 37.61997.38 55.68996-10.65999 19.44998-11.87999 37.74997-29.59997 55.02996-52.99996 17.74998-24.02998 32.90997-49.72996 45.47996-77.08994 12.55-27.30998 21.78999-54.06896 27.68998-80.27594 5.69-25.21598 7.29-45.98996 4.46-62.34495-3.79-18.24499-6.83-34.59698-9.12-49.05396-2.22-14.106-4.63-26.72698-7.22999-37.86298-2.55-10.93899-5.47-20.96898-8.75-30.08497-2.98-8.28-7.05999-16.709-12.20998-25.29798z" fill="#fff"/>
					</g>
				</svg>
			</span>
			<br />

			<span style="width: 34px; top: -5px;"><svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="facebook" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-facebook fa-w-16 fa-2x"><path fill="#475e8f" d="M504 256C504 119 393 8 256 8S8 119 8 256c0 123.78 90.69 226.38 209.25 245V327.69h-63V256h63v-54.64c0-62.15 37-96.48 93.67-96.48 27.14 0 55.52 4.84 55.52 4.84v61h-31.28c-30.8 0-40.41 19.12-40.41 38.73V256h68.78l-11 71.69h-57.78V501C413.31 482.38 504 379.78 504 256z" class=""></path></svg></span>

			<span class="sbi-sb-plus"><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="svg-inline--fa fa-plus fa-w-12 fa-2x"><path fill="currentColor" d="M376 232H216V72c0-4.42-3.58-8-8-8h-32c-4.42 0-8 3.58-8 8v160H8c-4.42 0-8 3.58-8 8v32c0 4.42 3.58 8 8 8h160v160c0 4.42 3.58 8 8 8h32c4.42 0 8-3.58 8-8V280h160c4.42 0 8-3.58 8-8v-32c0-4.42-3.58-8-8-8z" class=""></path></svg></span>

			<span><svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="instagram" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-instagram fa-w-14 fa-2x"><path fill="#e15073" d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z" class=""></path></svg></span>

			<span class="sbi-sb-plus"><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="svg-inline--fa fa-plus fa-w-12 fa-2x"><path fill="currentColor" d="M376 232H216V72c0-4.42-3.58-8-8-8h-32c-4.42 0-8 3.58-8 8v160H8c-4.42 0-8 3.58-8 8v32c0 4.42 3.58 8 8 8h160v160c0 4.42 3.58 8 8 8h32c4.42 0 8-3.58 8-8V280h160c4.42 0 8-3.58 8-8v-32c0-4.42-3.58-8-8-8z" class=""></path></svg></span>

			<span style="top: -4px;"><svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="twitter" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-twitter fa-w-16 fa-2x"><path fill="#1a92dc" d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z" class=""></path></svg></span>

			<span class="sbi-sb-plus"><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="svg-inline--fa fa-plus fa-w-12 fa-2x"><path fill="currentColor" d="M376 232H216V72c0-4.42-3.58-8-8-8h-32c-4.42 0-8 3.58-8 8v160H8c-4.42 0-8 3.58-8 8v32c0 4.42 3.58 8 8 8h160v160c0 4.42 3.58 8 8 8h32c4.42 0 8-3.58 8-8V280h160c4.42 0 8-3.58 8-8v-32c0-4.42-3.58-8-8-8z" class=""></path></svg></span>

			<span style="width: 35px; top: -5px;"><svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="youtube" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="svg-inline--fa fa-youtube fa-w-18 fa-2x"><path fill="#f5413d" d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z" class=""></path></svg></span>
		</div>

		<h1>Combine all your social media channels into one single wall.</h1>
		<h2>Maximize your social content and get more followers.</h2>

		<div style="text-align: center;">
			<a href="https://smashballoon.com/social-wall/?utm_source=plugin-pro&utm_campaign=sbi&utm_medium=sw-cta-1" target="_blank" class="cta button button-primary">Get the Social Wall plugin</a>
		</div>

		<div class="sbi-sw-info">
			<div class="sbi-sw-features">
				<p><span>A dash of Instagram</span>Add posts from your profile, public hashtag posts, or posts you're tagged in.</p>
				<p><span>A sprinkle of Facebook</span>Include posts from your page or group timeline, or from your photos, videos, albums, and events pages.</p>
				<p><span>A spoonful of Twitter</span>Add Tweets from any Twitter account, hashtag Tweets, mentions, and more.</p>
				<p><span>And a dollop of YouTube</span>Embed videos from any public YouTube channel, playlists, searches, and more.</p>
				<p><span>All in the same feed</span>Combine feeds from all of our Smash Balloon Pro plugins into one single wall feed, and show off all your social media content in one place.</p>
			</div>
			<a class="sbi-sw-screenshot" href="https://smashballoon.com/social-wall/demo?utm_source=plugin-pro&utm_campaign=sbi&utm_medium=sw-demo" target="_blank">
				<span class="cta">View Demo</span>

				<img src="<?php echo esc_url( SBI_PLUGIN_URL . 'img/sw-screenshot.png' ); ?>" alt="Smash Balloon Social Wall plugin screenshot showing Facebook, Instagram, Twitter, and YouTube posts combined into one wall.">
			</a>
		</div>

		<div class="sbi-sw-footer-cta">
			<a href="https://smashballoon.com/social-wall/?utm_source=plugin-pro&utm_campaign=sbi&utm_medium=sw-cta-2" target="_blank"><span>🚀</span>Get Social Wall and Increase Engagement >></a>
		</div>

	</div>

	<?php
}

//Add Welcome page
add_action( 'admin_menu', 'sbi_welcome_menu' );
function sbi_welcome_menu() {
	$capability = current_user_can( 'manage_instagram_feed_options' ) ? 'manage_instagram_feed_options' : 'manage_options';

	add_submenu_page(
		'sb-instagram-feed',
		__( "What's New?", 'instagram-feed' ),
		__( "What's New?", 'instagram-feed' ),
		$capability,
		'sbi-welcome-new',
		'sbi_welcome_screen_new_content'
	);
	add_submenu_page(
		'sb-instagram-feed',
		__( 'Getting Started', 'instagram-feed' ),
		__( 'Getting Started', 'instagram-feed' ),
		$capability,
		'sbi-welcome-started',
		'sbi_welcome_screen_started_content'
	);
}

function sbi_welcome_screen_new_content() {
	?>
	<div class="wrap about-wrap sbi-welcome">
		<?php sbi_welcome_header(); ?>

		<h2 class="nav-tab-wrapper">
			<a href="?page=sbi-welcome-new" class="nav-tab nav-tab-active"><?php esc_html_e( "What's New?", 'instagram-feed' ); ?></a>
			<a href="?page=sbi-welcome-started" class="nav-tab"><?php esc_html_e( 'Getting Started', 'instagram-feed' ); ?></a>
		</h2>

		<div class="sbi-welcome-intro">
			<p class="about-description">
				<?php
				$version_array = explode( '.', SBIVER );
				$major_version = $version_array[0] . '.' . $version_array[1];
				echo esc_html( sprintf( __( "Let's take a look at what's new in version %s", 'instagram-feed' ), $major_version ) );
				?>
			</p>

		</div>

		<div class="changelog">
			<h3><?php esc_html_e( 'Introducing Support for IGTV' ); ?></h3>
			<div class="feature-section">
				<div class="sbi-feature-section-media">
					<img src="<?php echo esc_url( SBI_PLUGIN_URL . 'img/welcome-igtv.jpg' ); ?>" alt="<?php esc_attr_e( 'Introducing Support for IGTV' ); ?>" style="padding: 0; background: #fff;">
				</div>

				<div class="sbi-feature-section-content">
					<p><?php esc_html_e( "Instagram has announced support for IGTV in their API and so we're wasting no time in adding it into the plugin! Your feed will now automatically include IGTV posts when using a connected Instagram businesss account." ); ?></p>

					<h4><?php esc_html_e( 'Directions' ); ?></h4>
					<p><?php esc_html_e( 'When creating an IGTV post, keep the "Post a Preview" setting enabled and the IGTV post will appear in your feed.' ); ?></p>
					<p><?php esc_html_e( 'By default, these IGTV posts will be included with other posts in your feed. To create a feed of show IGTV posts, just use the following setting: Customize > Posts > Media Type to Display > Videos only > IGTV videos.' ); ?></p>
					<p><?php echo wp_kses_post( __( 'You can also add <strong>media="videos" videotypes="igtv"</strong> to your shortcodes.' ) ); ?></p>
				</div>
			</div>
		</div>

		<p class="about-description">Here are some other features that were recently added:</p>

		<div class="changelog">
			<h3><?php esc_html_e( 'Compatibility with GDPR Consent Plugins' ); ?></h3>
			<div class="feature-section">
				<div class="sbi-feature-section-media">
					<img src="<?php echo esc_url( SBI_PLUGIN_URL . 'img/welcome-gdpr.png' ); ?>" alt="<?php esc_attr_e( 'Compatibility with GDPR Consent Plugins' ); ?>" style="padding: 0; background: #fff;">
				</div>

				<div class="sbi-feature-section-content">
					<p><?php esc_html_e( 'Do you have a GDPR related consent plugin enabled? We now offer privacy enhancement features that integrate with some popular solutions.' ); ?></p>

					<h4><?php esc_html_e( 'Current Compatible Consent Plugins', 'instagram-feed' ); ?></h4>
					<ul>
						<li><a href="https://wordpress.org/plugins/cookie-notice/" target="_blank" rel="noopener noreferrer">Cookie Notice</a> by dFactory</li>
						<li><a href="https://wordpress.org/plugins/cookie-law-info/" target="_blank" rel="noopener noreferrer">GDPR Cookie Consent</a> by WebToffee</li>
						<li><a href="https://wordpress.org/plugins/cookiebot/" target="_blank" rel="noopener noreferrer">Cookiebot</a> by Cybot A/S</li>
						<li><a href="https://wordpress.org/plugins/complianz-gdpr/" target="_blank" rel="noopener noreferrer">Complianz</a> by Really Simple Plugins</li>
						<li><a href="https://borlabs.io/borlabs-cookie/" target="_blank" rel="noopener noreferrer">Borlabs Cookie</a> by Borlabs</li>
					</ul>
					<h4><?php esc_html_e( 'Directions' ); ?></h4>
					<p><?php echo wp_kses_post( sprintf( __( 'If you are using one of these plugins then the plugin will display a GDPR compliant feed unless consent is given by the user. If you are not using a consent plugin, then you can enable the new GDPR setting in the Instagram Feed plugin to display a GDPR compliant feed to all users. %1$sClick here%2$s to learn more.', 'instagram-feed' ), '<a href="https://smashballoon.com/doc/instagram-feed-gdpr-compliance/?instagram" target="_blank" rel="noopener">', '</a>' ) ); ?></p>
				</div>
			</div>
		</div>


		<div class="changelog">
			<h3><?php esc_html_e( 'Support for oEmbeds' ); ?></h3>
			<div class="feature-section">
				<div class="sbi-feature-section-media">
					<img src="<?php echo esc_url( SBI_PLUGIN_URL . 'img/welcome-oembed-1.png' ); ?>" alt="<?php esc_attr_e( 'Oembed Example 1' ); ?>" style="padding: 0; background: #fff;">
					<img src="<?php echo esc_url( SBI_PLUGIN_URL . 'img/welcome-oembed-2.png' ); ?>" alt="<?php esc_attr_e( 'Oembed Example 2' ); ?>" style="padding: 0; background: #fff;">

				</div>

				<div class="sbi-feature-section-content">
					<p><?php esc_html_e( 'On October 24, 2020, WordPress is discontinuing support for Instagram oEmbeds and so any existing or new embeds will no longer work. The good news is, we have your back! The Instagram Feed plugin will automatically use any connected business accounts to power your existing oembeds as well as any new ones you post.' ); ?></p>

					<p><?php esc_html_e( "Don't have a business account? You can use your Facebook account to get an access token for oembeds. Just follow the directions below:" ); ?></p>
					<ul>
						<li><?php echo wp_kses_post( sprintf( __( 'From the dashboard, go to %1$sInstagram Feed%2$s and then %3$soEmbeds%4$s', 'instagram-feed' ), '<strong>', '</strong>', '<strong>', '</strong>' ) ); ?></li>
						<li><?php esc_html_e( 'Click on the big blue button to get an access token using Facebook.', 'instagram-feed' ); ?></li>
						<li><?php esc_html_e( 'To ensure your access token doesn\'t expire, choose an existing page that you have a role for, or create a new one.', 'instagram-feed' ); ?></li>
					</ul>

					<h4><?php esc_html_e( "What's an oEmbed?" ); ?></h4>
					<p><?php esc_html_e( 'When you share a link to an Instagram post in a WordPress post or page, WordPress automatically converts it into an embedded Instagram post for you (an "oEmbed"). See the screenshot to the right for an example. Once WordPress removes support, these posts will no longer display. We released this update to fix this issue and allow embedded posts to keep displaying.' ); ?></p>
				</div>
			</div>
		</div>

		<div class="changelog">
			<h3><?php esc_html_e( 'Added Compatability with our New Social Wall Plugin' ); ?></h3>
			<div class="feature-section">
				<div class="sbi-feature-section-media">
					<img src="<?php echo esc_url( SBI_PLUGIN_URL . 'img/welcome-sw.png' ); ?>" alt="<?php esc_attr_e( 'Social Wall' ); ?>" style="padding: 0; background: #fff;">
				</div>

				<div class="sbi-feature-section-content">
					<p><?php esc_html_e( 'In this update, we\'ve added compatability with our new Social Wall plugin, which allows you to combine feeds from Facebook, Twitter, Instagram, and YouTube into one social media "wall".' ); ?></p>

					<p><?php esc_html_e( 'This allows you to display all of your social media channels in one centralized feed, boosting engagement with your website visitors, and increasing your social media followers.' ); ?></p>
					<p><a href="admin.php?page=sbi-sw" class="button button-primary"><?php esc_html_e( 'Find out more about the Social Wall plugin' ); ?></a></p>

				</div>
			</div>
		</div>

		<p class="sbi-footnote"><i class="fa fa-heart"></i><?php echo wp_kses_post( sprintf( __( 'Your friends %s', 'instagram-feed' ), '@ <a href="https://smashballoon.com/?utm_campaign=instagram-pro&utm_source=wlecome&utm_medium=friends" target="_blank">Smash Balloon</a>' ) ); ?>

			<?php
			$disable_welcome = get_user_meta( get_current_user_id(), 'sbi_disable_welcome', true );

			if ( empty( $disable_welcome ) ) :
				?>
				<a class="sbi-redirect-disable" href="JavaScript:void(0);"><?php esc_html_e( 'Disable welcome page', 'instagram-feed' ); ?></a>
			<?php endif; ?>
		</p>

	</div>
	<?php
}
function sbi_welcome_screen_started_content() {
	?>
	<div class="wrap about-wrap sbi-welcome">
		<?php sbi_welcome_header(); ?>

		<h2 class="nav-tab-wrapper">
			<a href="?page=sbi-welcome-new" class="nav-tab"><?php esc_html_e( "What's New?", 'instagram-feed' ); ?></a>
			<a href="?page=sbi-welcome-started" class="nav-tab nav-tab-active"><?php esc_html_e( 'Getting Started', 'instagram-feed' ); ?></a>
		</h2>

		<p class="about-description"><?php esc_html_e( "Your first time using the plugin? Let's help you get started...", 'instagram-feed' ); ?></p>

		<div class="sbi-123">
			<div class="changelog">
				<div class="feature-section">
					<div class="sbi-feature-section-media">
						<img src="<?php echo esc_url( SBI_PLUGIN_URL . 'img/welcome-license.png' ); ?>" alt="<?php esc_attr_e( 'Enter license key', 'instagram-feed' ); ?>">
					</div>

					<div class="sbi-feature-section-content">
						<h3><span class="sbi-big-text">1</span><?php esc_html_e( 'Activate Your License Key', 'instagram-feed' ); ?></h3>
						<p><?php echo wp_kses_post( sprintf( __( "In order to receive updates for the plugin you'll need to activate your license key by entering it %s.", 'instagram-feed' ), '<a href="admin.php?page=sb-instagram-license" target="_blank">here</a>' ) ); ?></p>
					</div>
				</div>
			</div>

			<div class="changelog">
				<div class="feature-section">
					<div class="sbi-feature-section-media">
						<img src="<?php echo esc_url( SBI_PLUGIN_URL . 'img/welcome-token.jpg' ); ?>" alt="<?php esc_attr_e( 'Enter access token', 'instagram-feed' ); ?>">
					</div>
					<div class="sbi-feature-section-content">
						<h3><span class="sbi-big-text">2</span><?php esc_html_e( 'Connect an Instagram Account', 'instagram-feed' ); ?></h3>
						<p><?php echo wp_kses_post( sprintf( __( "We've made configuring your feed super simple. Just use the big blue button on the plugin's %s to connect your Instagram account.", 'instagram-feed' ), '<a href="admin.php?page=sb-instagram-feed&amp;tab=configure" target="_blank">' . __( 'Settings page' ) . '</a>' ) ); ?></p>
					</div>
				</div>
			</div>

			<div class="changelog">
				<div class="feature-section">
					<div class="sbi-feature-section-media">
						<img src="<?php echo esc_url( SBI_PLUGIN_URL . 'img/welcome-type.png' ); ?>" alt="<?php esc_attr_e( 'Select feed type', 'instagram-feed' ); ?>">
					</div>
					<div class="sbi-feature-section-content">
						<h3><span class="sbi-big-text">3</span><?php esc_html_e( 'Select your Feed Type', 'instagram-feed' ); ?></h3>
						<p><?php esc_html_e( 'Choose to display posts from a user account, hashtag, or both.', 'instagram-feed' ); ?></p>
					</div>
				</div>
			</div>

			<div class="changelog">
				<div class="feature-section">
					<div class="sbi-feature-section-media">
						<img src="<?php echo esc_url( SBI_PLUGIN_URL . 'img/welcome-shortcode.png' ); ?>" alt="<?php esc_attr_e( 'Embed shortcode', 'instagram-feed' ); ?>">
					</div>
					<div class="sbi-feature-section-content">
						<h3><span class="sbi-big-text">4</span><?php esc_html_e( 'Display Your Feed', 'instagram-feed' ); ?></h3>
						<p><?php echo wp_kses_post( sprintf( __( 'To display your feed simply copy and paste the %s shortcode wherever you want the feed to show up; any page, post, or widget. It really is that simple!', 'instagram-feed' ), '&nbsp;<code>[instagram-feed]</code>&nbsp;' ) ); ?></p>
						<p><i class="fa fa-life-ring" aria-hidden="true"></i>&nbsp; <?php echo wp_kses_post( sprintf( __( 'Need more help? See our %s.', 'instagram-feed' ), '<a href="admin.php?page=sb-instagram-feed&amp;tab=support" target="_blank">' . esc_html__( 'Support Section', 'instagram-feed' ) . '</a>' ) ); ?></p>
					</div>
				</div>
			</div>
		</div>

		<div class="changelog">
			<div class="feature-section">
				<div class="sbi-feature-section-media">
					<img src="<?php echo esc_url( SBI_PLUGIN_URL . 'img/welcome-multiple.jpg' ); ?>" alt="<?php esc_attr_e( 'Display multiple feeds', 'instagram-feed' ); ?>">
				</div>
				<div class="sbi-feature-section-content">
					<h3><?php esc_html_e( 'Multiple Feeds', 'instagram-feed' ); ?></h3>
					<p><?php echo wp_kses_post( sprintf( __( "You can display as many feeds on your site as you'd like. Just use our handy %s to customize each one as needed.", 'instagram-feed' ), '<a href="admin.php?page=sb-instagram-feed&amp;tab=display" target="_blank">' . __( 'shortcode options', 'instagram-feed' ) . '</a>' ) ); ?></p>

					<p><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp; <a href="https://smashballoon.com/display-multiple-instagram-feeds/" target="_blank"><?php esc_html_e( 'More help', 'instagram-feed' ); ?></a></p>
				</div>
			</div>
		</div>

		<div class="changelog">
			<div class="feature-section">
				<div class="sbi-feature-section-media">
					<img src="<?php echo esc_url( SBI_PLUGIN_URL . 'img/welcome-customize.png' ); ?>" alt="<?php esc_attr_e( 'Customize your feeds', 'instagram-feed' ); ?>">
				</div>
				<div class="sbi-feature-section-content">
					<h3><?php esc_html_e( 'Customize Your Feed', 'instagram-feed' ); ?></h3>
					<p><?php esc_html_e( 'There are countless ways to customize your Instagram feed. Whether it be translating the text, changing layouts and colors, or using powerful custom code snippets.', 'instagram-feed' ); ?></p>

					<h4><?php esc_html_e( 'Layout', 'instagram-feed' ); ?></h4>
					<p><?php esc_html_e( 'Choose from different feed types, change the layout, and even display your content in a rotating carousel.', 'instagram-feed' ); ?></p>

					<p><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp; <?php esc_html_e( 'Find out more:', 'instagram-feed' ); ?>
						<a href="https://smashballoon.com/creating-basic-instagram-slideshow/" target="_blank"><?php esc_html_e( 'Creating carousels', 'instagram-feed' ); ?></a>.
					</p>

					<h4><?php esc_html_e( 'Styling options' ); ?></h4>
					<p><?php esc_html_e( 'Choose which information to show or hide, customize colors and text, and style each individual part of your feed.', 'instagram-feed' ); ?> <a href="admin.php?page=sb-instagram-feed&amp;tab=customize"><?php esc_html_e( 'Go to the Customize page', 'instagram-feed' ); ?></a>.</p>

					<h4><?php esc_html_e( 'Advanced Customizations', 'instagram-feed' ); ?></h4>
					<p><?php esc_html_e( "You can achieve some pretty advanced customizations using the plugin. Here's some examples:", 'instagram-feed' ); ?></p>

					<p><i class="fa fa-file-text-o" aria-hidden="true"></i> <a href="https://smashballoon.com/guide-to-moderation-mode/" target="_blank"><?php esc_html_e( 'Moderating your feed', 'instagram-feed' ); ?></a> &nbsp;&middot;&nbsp;
						<i class="fa fa-file-text-o" aria-hidden="true"></i> <a href="https://smashballoon.com/can-display-photos-specific-hashtag-specific-user-id/" target="_blank"><?php esc_html_e( 'Filtering posts by word or hashtag', 'instagram-feed' ); ?></a> &nbsp;&middot;&nbsp;
						<i class="fa fa-file-text-o" aria-hidden="true"></i> <a href="https://smashballoon.com/make-a-shoppable-feed/" target="_blank"><?php esc_html_e( 'Creating a "Shoppable" feed', 'instagram-feed' ); ?></a>
					</p>
				</div>
			</div>
		</div>

		<div class="changelog">
			<div class="feature-section">
				<div class="sbi-feature-section-media">
					<a href='admin.php?page=sbi-top&amp;tab=support'><img src="<?php echo esc_url( SBI_PLUGIN_URL . 'img/welcome-support.png' ); ?>" alt="<?php esc_attr_e( 'Get technical support', 'instagram-feed' ); ?>"></a>
				</div>
				<div class="sbi-feature-section-content">
					<h3><i class="fa fa-life-ring" aria-hidden="true"></i>&nbsp; <?php esc_html_e( 'Need some more help?', 'instagram-feed' ); ?></h3>
					<p><?php echo wp_kses_post( sprintf( __( 'Check out our %s which includes helpful links, a tutorial video, and more.', 'instagram-feed' ), '<a href="admin.php?page=sb-instagram-feed&tab=support">' . __( 'Support Section', 'instagram-feed' ) . '</a>' ) ); ?></p>
				</div>
			</div>
		</div>

		<p class="sbi-footnote"><i class="fa fa-heart"></i><?php echo wp_kses_post( sprintf( __( 'Your friends %s', 'instagram-feed' ), '@ <a href="https://smashballoon.com/?utm_campaign=instagram-pro&utm_source=welcome&utm_medium=friends" target="_blank">Smash Balloon</a>' ) ); ?></p>

	</div>
	<?php
}
function sbi_welcome_header() {
	?>
	<?php
	//Set an option that shows that the welcome page has been seen
	update_option( 'sbi_welcome_seen', true );
	// user has seen notice
	add_user_meta( get_current_user_id(), 'sbi_seen_welcome_' . SBI_WELCOME_VER, 'true', true );

	?>
	<div id="sbi-header">
		<a href="admin.php?page=sb-instagram-feed" class="sbi-welcome-close"><i class="fa fa-times"></i></a>
		<a href="https://smashballoon.com/?utm_campaign=instagram-pro&utm_source=welcome&utm_medium=header" class="sbi-welcome-image" title="Your friends at Smash Balloon" target="_blank">
			<img src="<?php echo esc_url( SBI_PLUGIN_URL . 'img/balloon.png' ); ?>" alt="Instagram Feed Pro">
		</a>
		<h1><?php esc_html_e( 'Welcome to Instagram Feed Pro', 'instagram-feed' ); ?></h1>
		<p class="about-text">
			<?php
			$version_array = explode( '.', SBIVER );
			$major_version = $version_array[0] . '.' . $version_array[1];
			echo wp_kses_post( sprintf( __( "Thanks for installing <strong>Version %s</strong> of the Instagram Feed Pro plugin! Use the tabs below to see what's new or to get started using the plugin.", 'instagram-feed' ), $major_version ) );
			?>
		</p>
	</div>
	<?php
}

add_action( 'admin_notices', 'sbi_welcome_page_notice' );
function sbi_welcome_page_notice() {

	if ( current_user_can( 'manage_options' ) ) {

		if ( get_transient( 'sbi_show_welcome_notice_transient' ) || ! get_option( 'sbi_welcome_' . SBI_WELCOME_VER . '_transient_set' ) ) {

			// Use these to show notice again for testing
			// delete_user_meta($user_id, 'sbi_ignore_'.SBI_WELCOME_VER.'_welcome_notice');
			// delete_user_meta($user_id, 'sbi_seen_welcome_'.SBI_WELCOME_VER);

		} else {

			//If the transient hasn't been set before then set it for 7 days
			if ( ! get_option( 'sbi_welcome_' . SBI_WELCOME_VER . '_transient_set' ) ) {
				set_transient( 'sbi_show_welcome_notice_transient', 'true', WEEK_IN_SECONDS );
				update_option( 'sbi_welcome_' . SBI_WELCOME_VER . '_transient_set', true );
			}
		}
	}

}

add_action( 'admin_init', 'sbi_welcome_page_banner_ignore' );
function sbi_welcome_page_banner_ignore() {
	global $current_user;
	$user_id = $current_user->ID;
	if ( isset( $_GET[ 'sbi_ignore_' . SBI_WELCOME_VER . '_welcome_notice' ] ) && '0' === $_GET[ 'sbi_ignore_' . SBI_WELCOME_VER . '_welcome_notice' ] ) {
		add_user_meta( $user_id, 'sbi_ignore_' . SBI_WELCOME_VER . '_welcome_notice', 'true', true );
	}

	if ( isset( $_GET['openssldismiss'] ) ) {
		add_user_meta( $user_id, 'sbi_ignore_openssl', 'true', true );
	}
}

add_action( 'admin_init', 'sbi_welcome_screen_do_activation_redirect' );
function sbi_welcome_screen_do_activation_redirect() {
	//sbi_clear_it();
	// Delete settings for testing
	// delete_user_meta(get_current_user_id(), 'sbi_seen_welcome_'.SBI_WELCOME_VER);
	// delete_option( 'sbi_ver' );
	// Check whether a 30-second transient has been set by the activation function. If it has then potentially redirect to the Getting Started page.
	$capability = current_user_can( 'manage_instagram_feed_options' ) ? 'manage_instagram_feed_options' : 'manage_options';

	if ( get_transient( '_sbi_activation_redirect' ) ) {

		// Delete the redirect transient
		delete_transient( '_sbi_activation_redirect' );

		// Bail if activating from network, or bulk
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}

		$sbi_ver = get_option( 'sbi_ver' );
		if ( ! $sbi_ver ) {
			update_option( 'sbi_ver', SBIVER );
			sb_instagram_clear_page_caches();
			$disable_welcome = get_user_meta( get_current_user_id(), 'sbi_disable_welcome', true );
			if ( empty( $disable_welcome ) && current_user_can( $capability ) ) {
				update_option( 'sbi_welcome_seen', true );
				// user has seen notice
				add_user_meta( get_current_user_id(), 'sbi_seen_welcome_' . SBI_WELCOME_VER, 'true', true );
				wp_safe_redirect( admin_url( 'admin.php?page=sbi-welcome-new' ) );
				exit;
			}
		}
	} else {

		if ( isset( $_GET['page'] ) && 'sb-instagram-feed' === $_GET['page'] && ! get_user_meta( get_current_user_id(), 'sbi_seen_welcome_' . SBI_WELCOME_VER ) ) {
			$disable_welcome = get_user_meta( get_current_user_id(), 'sbi_disable_welcome', true );

			if ( empty( $disable_welcome ) && current_user_can( $capability ) ) {
				update_option( 'sbi_welcome_seen', true );
				// user has seen notice
				add_user_meta( get_current_user_id(), 'sbi_seen_welcome_' . SBI_WELCOME_VER, 'true', true );
				wp_safe_redirect( admin_url( 'admin.php?page=sbi-welcome-new' ) );
				exit;
			}
		}
	}

}


function sbi_register_option() {
	// creates our settings in the options table
	register_setting( 'sbi_license', 'sbi_license_key', 'sbi_sanitize_license' );
}
add_action( 'admin_init', 'sbi_register_option' );

function sbi_sanitize_license( $new ) {
	$old = get_option( 'sbi_license_key' );
	if ( $old && $old !== $new ) {
		delete_option( 'sbi_license_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}

function sbi_activate_license() {

	// listen for our activate button to be clicked
	if ( isset( $_POST['sbi_license_activate'] ) ) {

		// run a quick security check
		if ( ! check_admin_referer( 'sbi_nonce', 'sbi_nonce' ) ) {
			return; // get out if we didn't click the Activate button
		}

		// retrieve the license from the database
		$sbi_license = trim( get_option( 'sbi_license_key' ) );

		// data to send in our API request
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $sbi_license,
			'item_name'  => urlencode( SBI_PLUGIN_NAME ), // the name of our product in EDD
			'url'        => home_url(),
		);

		// Call the custom API.
		$response = wp_remote_get(
			add_query_arg( $api_params, SBI_STORE_URL ),
			array(
				'timeout'   => 15,
				'sslverify' => false,
			)
		);

		// make sure the response came back okay
		if ( is_wp_error( $response ) ) {
			return false;
		}

		// decode the license data
		$sbi_license_data = json_decode( wp_remote_retrieve_body( $response ) );

		//store the license data in an option
		update_option( 'sbi_license_data', $sbi_license_data );

		// $license_data->license will be either "valid" or "invalid"

		update_option( 'sbi_license_status', $sbi_license_data->license );

	}
}
add_action( 'admin_init', 'sbi_activate_license' );

function sbi_deactivate_license() {

	// listen for our activate button to be clicked
	if ( isset( $_POST['sbi_license_deactivate'] ) ) {

		// run a quick security check
		if ( ! check_admin_referer( 'sbi_nonce', 'sbi_nonce' ) ) {
			return; // get out if we didn't click the Activate button
		}

		// retrieve the license from the database
		$sbi_license = trim( get_option( 'sbi_license_key' ) );

		// data to send in our API request
		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license'    => $sbi_license,
			'item_name'  => urlencode( SBI_PLUGIN_NAME ), // the name of our product in EDD
			'url'        => home_url(),
		);

		// Call the custom API.
		$response = wp_remote_get(
			add_query_arg( $api_params, SBI_STORE_URL ),
			array(
				'timeout'   => 15,
				'sslverify' => false,
			)
		);

		// make sure the response came back okay
		if ( is_wp_error( $response ) ) {
			return false;
		}

		// decode the license data
		$sbi_license_data = json_decode( wp_remote_retrieve_body( $response ) );

		// $license_data->license will be either "deactivated" or "failed"
		if ( $sbi_license_data->license === 'deactivated' ) {
			delete_option( 'sbi_license_status' );
		}
	}
}
add_action( 'admin_init', 'sbi_deactivate_license' );


//License page
function sbi_license_page() {
	$sbi_license = trim( get_option( 'sbi_license_key' ) );
	$sbi_status  = get_option( 'sbi_license_status' );
	?>

	<div id="sbi_admin" class="wrap">
		<?php do_action( 'sbi_admin_overview_before_table' ); ?>

		<div id="header">
			<h1><?php esc_html_e( 'Instagram Feed Pro', 'instagram-feed' ); ?></h1>
		</div>

		<?php sbi_expiration_notice(); ?>

		<form name="form1" method="post" action="options.php">

			<h2 class="nav-tab-wrapper">
				<a href="?page=sb-instagram-feed&amp;tab=configure" class="nav-tab"><?php esc_html_e( '1. Configure', 'instagram-feed' ); ?></a>
				<a href="?page=sb-instagram-feed&amp;tab=customize" class="nav-tab"><?php esc_html_e( '2. Customize', 'instagram-feed' ); ?></a>
				<a href="?page=sb-instagram-feed&amp;tab=display" class="nav-tab"><?php esc_html_e( '3. Display Your Feed', 'instagram-feed' ); ?></a>
				<a href="?page=sb-instagram-feed&amp;tab=support" class="nav-tab"><?php esc_html_e( 'Support', 'instagram-feed' ); ?></a>
				<a href="?page=sb-instagram-license" class="nav-tab nav-tab-active"><?php esc_html_e( 'License', 'instagram-feed' ); ?></a>
				<?php if ( ! is_plugin_active( 'social-wall/social-wall.php' ) ) { ?>
					<a href="?page=sbi-sw" class="nav-tab"><?php esc_html_e( 'Create a Social Wall' ); ?><span class="sbi-alert-bubble">New</span></a>
				<?php } ?>
			</h2>

			<?php settings_fields( 'sbi_license' ); ?>

			<?php
			// data to send in our API request
			$sbi_api_params = array(
				'edd_action' => 'check_license',
				'license'    => $sbi_license,
				'item_name'  => urlencode( SBI_PLUGIN_NAME ), // the name of our product in EDD
			);

			// Call the custom API.
			$sbi_response = wp_remote_get(
				add_query_arg( $sbi_api_params, SBI_STORE_URL ),
				array(
					'timeout'   => 60,
					'sslverify' => false,
				)
			);

			// decode the license data
			$sbi_license_data = (array) json_decode( wp_remote_retrieve_body( $sbi_response ) );

			//Store license data in db unless the data comes back empty as wasn't able to connect to our website to get it
			if ( ! empty( $sbi_license_data ) ) {
				update_option( 'sbi_license_data', $sbi_license_data );
			}

			?>

			<table class="form-table" aria-describedby="License">
				<tbody>
				<h3><?php esc_html_e( 'License', 'instagram-feed' ); ?></h3>

				<tr >
					<th scope="row" >
						<?php esc_html_e( 'Enter your license key', 'instagram-feed' ); ?>
					</th>
					<td>
						<?php
						$license_output = apply_filters( 'sbi_license_page_output', '', get_current_user_id(), $sbi_license_data['license'] );
						if ( empty( $license_output ) ) :
							?>
							<input id="sbi_license_key" name="sbi_license_key" type="text" class="regular-text" value="<?php echo esc_attr( $sbi_license ); ?>" />

							<?php if ( false !== $sbi_license ) { ?>

							<?php if ( $sbi_status === 'valid' ) { ?>
								<?php wp_nonce_field( 'sbi_nonce', 'sbi_nonce' ); ?>
								<input type="submit" class="button-secondary" name="sbi_license_deactivate" value="<?php esc_html_e( 'Deactivate License', 'instagram-feed' ); ?>"/>

								<?php if ( $sbi_license_data['license'] === 'expired' ) { ?>
									<span class="sbi_license_status" style="color:red;"><?php esc_html_e( 'Expired', 'instagram-feed' ); ?></span>
								<?php } else { ?>
									<span class="sbi_license_status" style="color:green;"><?php esc_html_e( 'Active', 'instagram-feed' ); ?></span>
								<?php } ?>

								<?php
							} else {
								wp_nonce_field( 'sbi_nonce', 'sbi_nonce' );
								?>
								<input type="submit" class="button-secondary" name="sbi_license_activate" value="<?php esc_html_e( 'Activate License', 'instagram-feed' ); ?>"/>

								<?php if ( $sbi_license_data['license'] === 'expired' ) { ?>
									<span class="sbi_license_status" style="color:red;"><?php esc_html_e( 'Expired', 'instagram-feed' ); ?></span>
								<?php } else { ?>
									<span class="sbi_license_status" style="color:red;"><?php esc_html_e( 'Inactive', 'instagram-feed' ); ?></span>
								<?php } ?>

							<?php } ?>
						<?php } ?>

							<br />
							<i style="color: #666; font-size: 11px;"><?php esc_html_e( 'The license key you received when purchasing the plugin.', 'instagram-feed' ); ?></i>
							<?php global $sbi_download_id; ?>
							<div style="font-size: 13px;">
								<a href="<?php echo esc_url( 'https://smashballoon.com/checkout/?edd_license_key=' . trim( $sbi_license ) . '&download_id=' . $sbi_download_id ); ?>" target='_blank'><?php esc_html_e( 'Renew your license', 'instagram-feed' ); ?></a>
								&nbsp;&nbsp;&nbsp;&middot;
								<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'Upgrade your license', 'instagram-feed' ); ?></a>
								<div class="sbi_tooltip">
									<?php esc_html_e( 'You can upgrade your license in two ways:', 'instagram-feed' ); ?><br />
									&bull;&nbsp; <?php echo wp_kses_post( sprintf( __( "Log into %s and click on the 'Upgrade my License' tab", 'instagram-feed' ), '<a href="https://smashballoon.com/account" target="_blank">' . __( 'your Account', 'instagram-feed' ) . '</a>' ) ); ?><br />
									&bull;&nbsp; <a href='https://smashballoon.com/contact/?utm_campaign=instagram-pro&utm_source=license&utm_medium=upgrade' target='_blank'><?php esc_html_e( 'Contact us directly', 'instagram-feed' ); ?></a>
								</div>

							</div>
						<?php
						else :
							echo wp_kses_post( $license_output );
						endif;
						?>

					</td>
				</tr>

				</tbody>
			</table>

			<?php if ( empty( $license_output ) ) : ?>
				<p style="margin: 20px 0 0 0; height: 35px;">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_html_e( 'Save Changes' ); ?>">
					<button name="sbi-test-license" id="sbi-test-license-btn" class="button button-secondary"><?php esc_html_e( 'Test Connection', 'instagram-feed' ); ?></button>
				</p>

				<div id="sbi-test-license-connection" style="display: none;">
					<?php
					if ( isset( $sbi_license_data['item_name'] ) ) {
						echo '<p class="sbi-success" style="display: inline-block; padding: 10px 15px; border-radius: 5px; margin: 0; background: #dceada; border: 1px solid #6ca365; color: #3e5f1c;"><i class="fa fa-check"></i> &nbsp;Connection Successful</p>';
					} else {
						echo '<div class="sbi-test-license-error">';
						highlight_string( var_export( $sbi_response, true ) );
						echo '<br />';
						highlight_string( var_export( $sbi_license_data, true ) );
						echo '</div>';
					}
					?>
				</div>
				<script type="text/javascript">
					jQuery('#sbi-test-license-btn').on('click', function(e){
						e.preventDefault();
						jQuery('#sbi-test-license-connection').toggle();
					});
				</script>
			<?php endif; ?>
		</form>

	</div>

	<?php
} //End License page

function sb_instagram_settings_page() {

	$sbi_welcome_seen = get_option( 'sbi_welcome_seen' );
	if ( $sbi_welcome_seen === false ) {
		?>
		<p class="sbi-page-loading"><?php esc_html_e( 'Loading...', 'instagram-feed' ); ?></p>
		<script>window.location = "<?php echo esc_url( admin_url( 'admin.php?page=sbi-welcome-new' ) ); ?>";</script>
		<?php
	}

	//Hidden fields
	$sb_instagram_settings_hidden_field               = 'sb_instagram_settings_hidden_field';
	$sb_instagram_configure_hidden_field              = 'sb_instagram_configure_hidden_field';
	$sb_instagram_customize_hidden_field              = 'sb_instagram_customize_hidden_field';
	$sb_instagram_customize_posts_hidden_field        = 'sb_instagram_customize_posts_hidden_field';
	$sb_instagram_customize_moderation_hidden_field   = 'sb_instagram_customize_moderation_hidden_field';
	$sb_instagram_customize_advanced_hidden_field     = 'sb_instagram_customize_advanced_hidden_field';
	$sb_instagram_customize_integrations_hidden_field = 'sb_instagram_customize_integration_hidden_field';

	//Declare defaults
	$sb_instagram_settings_defaults = array(
		'sb_instagram_at'                     => '',
		'sb_instagram_type'                   => 'user',
		'sb_instagram_order'                  => 'top',
		'sb_instagram_user_id'                => '',
		'sb_instagram_tagged_ids'             => '',
		'sb_instagram_hashtag'                => '',
		'sb_instagram_type_self_likes'        => '',
		'sb_instagram_location'               => '',
		'sb_instagram_coordinates'            => '',
		'sb_instagram_preserve_settings'      => '',
		'sb_instagram_ajax_theme'             => false,
		'gdpr'                                => 'auto',
		'enqueue_js_in_head'                  => false,
		'disable_js_image_loading'            => false,
		'sb_instagram_disable_resize'         => false,
		'sb_instagram_favor_local'            => true,
		'sb_instagram_cache_time'             => '1',
		'sb_instagram_cache_time_unit'        => 'hours',
		'sbi_caching_type'                    => 'page',
		'sbi_cache_cron_interval'             => '12hours',
		'sbi_cache_cron_time'                 => '1',
		'sbi_cache_cron_am_pm'                => 'am',

		'sb_instagram_width'                  => '100',
		'sb_instagram_width_unit'             => '%',
		'sb_instagram_feed_width_resp'        => false,
		'sb_instagram_height'                 => '',
		'sb_instagram_num'                    => '20',
		'sb_instagram_nummobile'              => '',
		'sb_instagram_height_unit'            => '',
		'sb_instagram_cols'                   => '4',
		'sb_instagram_colsmobile'             => 'auto',
		'sb_instagram_image_padding'          => '5',
		'sb_instagram_image_padding_unit'     => 'px',

		//Layout Type
		'sb_instagram_layout_type'            => 'grid',
		'sb_instagram_highlight_type'         => 'pattern',
		'sb_instagram_highlight_offset'       => 0,
		'sb_instagram_highlight_factor'       => 6,
		'sb_instagram_highlight_ids'          => '',
		'sb_instagram_highlight_hashtag'      => '',

		//Hover style
		'sb_hover_background'                 => '',
		'sb_hover_text'                       => '',
		'sbi_hover_inc_username'              => true,
		'sbi_hover_inc_icon'                  => true,
		'sbi_hover_inc_date'                  => true,
		'sbi_hover_inc_instagram'             => true,
		'sbi_hover_inc_location'              => false,
		'sbi_hover_inc_caption'               => false,
		'sbi_hover_inc_likes'                 => false,
		'sb_instagram_sort'                   => 'none',
		'sb_instagram_disable_lightbox'       => false,
		'sb_instagram_captionlinks'           => false,
		'sb_instagram_offset'                 => 0,
		'sb_instagram_background'             => '',
		'sb_instagram_show_btn'               => true,
		'sb_instagram_btn_background'         => '',
		'sb_instagram_btn_text_color'         => '',
		'sb_instagram_btn_text'               => __( 'Load More', 'instagram-feed' ),
		'sb_instagram_image_res'              => 'auto',
		'sb_instagram_media_type'             => 'all',
		'videotypes'                          => 'regular,igtv',
		'sb_instagram_moderation_mode'        => 'manual',
		'sb_instagram_hide_photos'            => '',
		'sb_instagram_block_users'            => '',
		'sb_instagram_ex_apply_to'            => 'all',
		'sb_instagram_inc_apply_to'           => 'all',
		'sb_instagram_show_users'             => '',
		'sb_instagram_exclude_words'          => '',
		'sb_instagram_include_words'          => '',

		//Text
		'sb_instagram_show_caption'           => true,
		'sb_instagram_caption_length'         => '50',
		'sb_instagram_caption_color'          => '',
		'sb_instagram_caption_size'           => '13',

		//lightbox comments
		'sb_instagram_lightbox_comments'      => true,
		'sb_instagram_num_comments'           => '20',

		//Meta
		'sb_instagram_show_meta'              => true,
		'sb_instagram_meta_color'             => '',
		'sb_instagram_meta_size'              => '13',
		//Header
		'sb_instagram_show_header'            => true,
		'sb_instagram_header_color'           => '',
		'sb_instagram_header_style'           => 'standard',
		'sb_instagram_show_followers'         => true,
		'sb_instagram_show_bio'               => true,
		'sb_instagram_custom_bio'             => '',
		'sb_instagram_custom_avatar'          => '',
		'sb_instagram_header_primary_color'   => '517fa4',
		'sb_instagram_header_secondary_color' => 'eeeeee',
		'sb_instagram_header_size'            => 'small',
		'sb_instagram_outside_scrollable'     => false,
		'sb_instagram_stories'                => true,
		'sb_instagram_stories_time'           => 5000,

		//Follow button
		'sb_instagram_show_follow_btn'        => true,
		'sb_instagram_folow_btn_background'   => '',
		'sb_instagram_follow_btn_text_color'  => '',
		'sb_instagram_follow_btn_text'        => __( 'Follow on Instagram', 'instagram-feed' ),

		//Autoscroll
		'sb_instagram_autoscroll'             => false,
		'sb_instagram_autoscrolldistance'     => 200,

		//Misc
		'sb_instagram_custom_css'             => '',
		'sb_instagram_custom_js'              => '',
		'sb_instagram_requests_max'           => '5',
		'sb_instagram_minnum'                 => '0',
		'sb_instagram_cron'                   => 'unset',
		'sb_instagram_disable_font'           => false,
		'sb_instagram_backup'                 => true,
		'sb_ajax_initial'                     => false,
		'enqueue_css_in_shortcode'            => false,
		'sb_instagram_disable_mob_swipe'      => false,
		'sbi_br_adjust'                       => true,
		'sb_instagram_media_vine'             => false,
		'custom_template'                     => false,
		'disable_admin_notice'                => false,
		'enable_email_report'                 => 'on',
		'email_notification'                  => 'monday',
		'email_notification_addresses'        => get_option( 'admin_email' ),

		//Carousel
		'sb_instagram_carousel'               => false,
		'sb_instagram_carousel_rows'          => 1,
		'sb_instagram_carousel_loop'          => 'rewind',
		'sb_instagram_carousel_arrows'        => false,
		'sb_instagram_carousel_pag'           => true,
		'sb_instagram_carousel_autoplay'      => false,
		'sb_instagram_carousel_interval'      => '5000',

	);

	if ( is_multisite() ) {
		$sb_instagram_settings_defaults['sbi_super_admin_only'] = false;
	}
	//Save defaults in an array
	$options = wp_parse_args( get_option( 'sb_instagram_settings' ), $sb_instagram_settings_defaults );
	update_option( 'sb_instagram_settings', $options );
	if ( isset( $_POST['sbi_just_saved'] ) ) {
		echo '<input id="sbi_just_saved" type="hidden" name="sbi_just_saved" value="1">';
	}
	//Set the page variables
	$sb_instagram_at         = $options['sb_instagram_at'];
	$sb_instagram_type       = $options['sb_instagram_type'];
	$sb_instagram_order      = $options['sb_instagram_order'];
	$sb_instagram_user_id    = $options['sb_instagram_user_id'];
	$sb_instagram_hashtag    = $options['sb_instagram_hashtag'];
	$sb_instagram_tagged_ids = $options['sb_instagram_tagged_ids'];

	$sb_instagram_type_self_likes   = $options['sb_instagram_type_self_likes'];
	$sb_instagram_location          = $options['sb_instagram_location'];
	$sb_instagram_coordinates       = $options['sb_instagram_coordinates'];
	$sb_instagram_preserve_settings = $options['sb_instagram_preserve_settings'];
	$sb_instagram_ajax_theme        = $options['sb_instagram_ajax_theme'];
	$gdpr                           = $options['gdpr'];

	$enqueue_js_in_head          = $options['enqueue_js_in_head'];
	$disable_js_image_loading    = $options['disable_js_image_loading'];
	$sb_instagram_disable_resize = $options['sb_instagram_disable_resize'];
	$sb_instagram_favor_local    = $options['sb_instagram_favor_local'];

	$sb_instagram_cache_time      = $options['sb_instagram_cache_time'];
	$sb_instagram_cache_time_unit = $options['sb_instagram_cache_time_unit'];
	if ( $sb_instagram_cache_time_unit === 'days' ) {
		$sb_instagram_cache_time_unit = 'hours';
		$sb_instagram_cache_time      = 24;
	}
	$sbi_caching_type        = $options['sbi_caching_type'];
	$sbi_cache_cron_interval = $options['sbi_cache_cron_interval'];
	$sbi_cache_cron_time     = $options['sbi_cache_cron_time'];
	$sbi_cache_cron_am_pm    = $options['sbi_cache_cron_am_pm'];

	$sb_instagram_width           = $options['sb_instagram_width'];
	$sb_instagram_width_unit      = $options['sb_instagram_width_unit'];
	$sb_instagram_feed_width_resp = $options['sb_instagram_feed_width_resp'];
	$sb_instagram_height          = $options['sb_instagram_height'];
	$sb_instagram_height_unit     = $options['sb_instagram_height_unit'];
	$sb_instagram_num             = $options['sb_instagram_num'];
	$sb_instagram_nummobile       = $options['sb_instagram_nummobile'];
	$sb_instagram_cols            = $options['sb_instagram_cols'];
	$sb_instagram_colsmobile      = $options['sb_instagram_colsmobile'];

	$sb_instagram_disable_mobile     = isset( $options['sb_instagram_disable_mobile'] ) && ( $options['sb_instagram_disable_mobile'] === 'on' || $options['sb_instagram_disable_mobile'] === true ) ? true : false;
	$sb_instagram_image_padding      = $options['sb_instagram_image_padding'];
	$sb_instagram_image_padding_unit = $options['sb_instagram_image_padding_unit'];

	//Layout Type
	$sb_instagram_layout_type       = $options['sb_instagram_layout_type'];
	$sb_instagram_highlight_type    = $options['sb_instagram_highlight_type'];
	$sb_instagram_highlight_offset  = $options['sb_instagram_highlight_offset'];
	$sb_instagram_highlight_factor  = $options['sb_instagram_highlight_factor'];
	$sb_instagram_highlight_ids     = $options['sb_instagram_highlight_ids'];
	$sb_instagram_highlight_hashtag = $options['sb_instagram_highlight_hashtag'];

	//Lightbox Comments
	$sb_instagram_lightbox_comments = $options['sb_instagram_lightbox_comments'];
	$sb_instagram_num_comments      = $options['sb_instagram_num_comments'];

	//Photo hover style
	$sb_hover_background     = $options['sb_hover_background'];
	$sb_hover_text           = $options['sb_hover_text'];
	$sbi_hover_inc_username  = $options['sbi_hover_inc_username'];
	$sbi_hover_inc_icon      = $options['sbi_hover_inc_icon'];
	$sbi_hover_inc_date      = $options['sbi_hover_inc_date'];
	$sbi_hover_inc_instagram = $options['sbi_hover_inc_instagram'];
	$sbi_hover_inc_location  = $options['sbi_hover_inc_location'];
	$sbi_hover_inc_caption   = $options['sbi_hover_inc_caption'];
	$sbi_hover_inc_likes     = $options['sbi_hover_inc_likes'];

	$sb_instagram_sort             = $options['sb_instagram_sort'];
	$sb_instagram_disable_lightbox = $options['sb_instagram_disable_lightbox'];
	$sb_instagram_captionlinks     = $options['sb_instagram_captionlinks'];
	$sb_instagram_offset           = $options['sb_instagram_offset'];

	$sb_instagram_background     = $options['sb_instagram_background'];
	$sb_instagram_show_btn       = $options['sb_instagram_show_btn'];
	$sb_instagram_btn_background = $options['sb_instagram_btn_background'];
	$sb_instagram_btn_text_color = $options['sb_instagram_btn_text_color'];
	$sb_instagram_btn_text       = $options['sb_instagram_btn_text'];
	$sb_instagram_image_res      = $options['sb_instagram_image_res'];
	$sb_instagram_media_type     = $options['sb_instagram_media_type'];
	$videotypes                  = $options['videotypes'];

	$sb_instagram_moderation_mode = $options['sb_instagram_moderation_mode'];
	$sb_instagram_hide_photos     = $options['sb_instagram_hide_photos'];
	$sb_instagram_block_users     = $options['sb_instagram_block_users'];
	$sb_instagram_ex_apply_to     = $options['sb_instagram_ex_apply_to'];
	$sb_instagram_inc_apply_to    = $options['sb_instagram_inc_apply_to'];
	$sb_instagram_show_users      = $options['sb_instagram_show_users'];
	$sb_instagram_exclude_words   = $options['sb_instagram_exclude_words'];
	$sb_instagram_include_words   = $options['sb_instagram_include_words'];

	//Text
	$sb_instagram_show_caption   = $options['sb_instagram_show_caption'];
	$sb_instagram_caption_length = $options['sb_instagram_caption_length'];
	$sb_instagram_caption_color  = $options['sb_instagram_caption_color'];
	$sb_instagram_caption_size   = $options['sb_instagram_caption_size'];
	//Meta
	$sb_instagram_show_meta  = $options['sb_instagram_show_meta'];
	$sb_instagram_meta_color = $options['sb_instagram_meta_color'];
	$sb_instagram_meta_size  = $options['sb_instagram_meta_size'];
	//Header
	$sb_instagram_show_header            = $options['sb_instagram_show_header'];
	$sb_instagram_header_color           = $options['sb_instagram_header_color'];
	$sb_instagram_header_style           = $options['sb_instagram_header_style'];
	$sb_instagram_show_followers         = $options['sb_instagram_show_followers'];
	$sb_instagram_show_bio               = $options['sb_instagram_show_bio'];
	$sb_instagram_custom_bio             = $options['sb_instagram_custom_bio'];
	$sb_instagram_custom_avatar          = $options['sb_instagram_custom_avatar'];
	$sb_instagram_header_primary_color   = $options['sb_instagram_header_primary_color'];
	$sb_instagram_header_secondary_color = $options['sb_instagram_header_secondary_color'];
	$sb_instagram_header_size            = $options['sb_instagram_header_size'];
	$sb_instagram_outside_scrollable     = $options['sb_instagram_outside_scrollable'];
	$sb_instagram_stories                = $options['sb_instagram_stories'];
	$sb_instagram_stories_time           = $options['sb_instagram_stories_time'];

	//Follow button
	$sb_instagram_show_follow_btn       = $options['sb_instagram_show_follow_btn'];
	$sb_instagram_folow_btn_background  = $options['sb_instagram_folow_btn_background'];
	$sb_instagram_follow_btn_text_color = $options['sb_instagram_follow_btn_text_color'];
	$sb_instagram_follow_btn_text       = $options['sb_instagram_follow_btn_text'];

	//Autoscroll
	$sb_instagram_autoscroll         = $options['sb_instagram_autoscroll'];
	$sb_instagram_autoscrolldistance = $options['sb_instagram_autoscrolldistance'];

	//Misc
	$sb_instagram_custom_css   = $options['sb_instagram_custom_css'];
	$sb_instagram_custom_js    = $options['sb_instagram_custom_js'];
	$sb_instagram_requests_max = $options['sb_instagram_requests_max'];
	$sb_instagram_minnum       = $options['sb_instagram_minnum'];
	$sb_instagram_cron         = $options['sb_instagram_cron'];
	$sb_instagram_disable_font = $options['sb_instagram_disable_font'];
	$sb_instagram_backup       = $options['sb_instagram_backup'];
	$sb_ajax_initial           = $options['sb_ajax_initial'];

	$enqueue_css_in_shortcode                  = $options['enqueue_css_in_shortcode'];
	$sb_instagram_disable_mob_swipe            = $options['sb_instagram_disable_mob_swipe'];
	$sbi_br_adjust                             = $options['sbi_br_adjust'];
	$sb_instagram_media_vine                   = $options['sb_instagram_media_vine'];
	$sb_instagram_custom_template              = $options['custom_template'];
	$sb_instagram_disable_admin_notice         = $options['disable_admin_notice'];
	$sb_instagram_enable_email_report          = $options['enable_email_report'];
	$sb_instagram_email_notification           = $options['email_notification'];
	$sb_instagram_email_notification_addresses = $options['email_notification_addresses'];

	if ( is_multisite() ) {
		$sbi_super_admin_only = $options['sbi_super_admin_only'];
	}
	//Carousel
	$sb_instagram_carousel          = $options['sb_instagram_carousel'];
	$sb_instagram_carousel_rows     = $options['sb_instagram_carousel_rows'];
	$sb_instagram_carousel_loop     = $options['sb_instagram_carousel_loop'];
	$sb_instagram_carousel_arrows   = $options['sb_instagram_carousel_arrows'];
	$sb_instagram_carousel_pag      = $options['sb_instagram_carousel_pag'];
	$sb_instagram_carousel_autoplay = $options['sb_instagram_carousel_autoplay'];
	$sb_instagram_carousel_interval = $options['sb_instagram_carousel_interval'];

	//Check nonce before saving data
	if ( ! isset( $_POST['sb_instagram_pro_settings_nonce'] ) || ! wp_verify_nonce( $_POST['sb_instagram_pro_settings_nonce'], 'sb_instagram_pro_saving_settings' ) ) {
		//Nonce did not verify
	} else {

		// See if the user has posted us some information. If they did, this hidden field will be set to 'Y'.
		if ( isset( $_POST[ $sb_instagram_settings_hidden_field ] ) && $_POST[ $sb_instagram_settings_hidden_field ] === 'Y' ) {

			if ( isset( $_POST[ $sb_instagram_configure_hidden_field ] ) && $_POST[ $sb_instagram_configure_hidden_field ] === 'Y' ) {
				if ( isset( $_POST['sb_instagram_type'] ) ) {
					$sb_instagram_type = SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_type'], 'enum', array( 'user', 'hashtag', 'tagged' ) );
				}

				$sb_instagram_user_id = array();
				if ( isset( $_POST['sb_instagram_user_id'] ) ) {
					if ( is_array( $_POST['sb_instagram_user_id'] ) ) {
						foreach ( $_POST['sb_instagram_user_id'] as $user_id ) {
							$sb_instagram_user_id[] = sbi_sanitize_instagram_ids( $user_id );
						}
					} else {
						$sb_instagram_user_id[] = sbi_sanitize_instagram_ids( $_POST['sb_instagram_user_id'] );
					}
				}

				$sb_instagram_tagged_ids = array();
				if ( isset( $_POST['sb_instagram_tagged_id'] ) ) {
					if ( is_array( $_POST['sb_instagram_tagged_id'] ) ) {
						foreach ( $_POST['sb_instagram_tagged_id'] as $user_id ) {
							$sb_instagram_tagged_ids[] = sbi_sanitize_instagram_ids( $user_id );
						}
					} else {
						$sb_instagram_tagged_ids[] = sbi_sanitize_instagram_ids( $_POST['sb_instagram_tagged_id'] );
					}
				}

				if ( isset( $_POST['sb_instagram_order'] ) ) {
					$sb_instagram_order = SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_order'], 'enum', array( 'recent', 'top' ) );
				}

				if ( isset( $_POST['sb_instagram_hashtag'] ) ) {
					$sb_instagram_hashtag = sanitize_text_field( wp_unslash( $_POST['sb_instagram_hashtag'] ) );
				}
				$sb_instagram_type_self_likes = '';
				$sb_instagram_location        = '';
				$sb_instagram_coordinates     = '';

				$sb_instagram_preserve_settings                                       = isset( $_POST['sb_instagram_preserve_settings'] );
				isset( $_POST['sbi_caching_type'] ) ? $sbi_caching_type               = SB_Instagram_Settings::sanitize_setting( $_POST['sbi_caching_type'], 'enum', array( 'page', 'background' ) ) : $sbi_caching_type = 'page';
				isset( $_POST['sbi_cache_cron_interval'] ) ? $sbi_cache_cron_interval = SB_Instagram_Settings::sanitize_setting( $_POST['sbi_cache_cron_interval'], 'enum', array( '30mins', '1hour', '12hours', '24hours' ) ) : $sbi_cache_cron_interval = '1hour';
				isset( $_POST['sbi_cache_cron_time'] ) ? $sbi_cache_cron_time         = SB_Instagram_Settings::sanitize_setting( (string) $_POST['sbi_cache_cron_time'], 'enum', array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11' ) ) : $sbi_cache_cron_time = '1';
				isset( $_POST['sbi_cache_cron_am_pm'] ) ? $sbi_cache_cron_am_pm       = SB_Instagram_Settings::sanitize_setting( $_POST['sbi_cache_cron_am_pm'], 'enum', array( 'am', 'pm' ) ) : $sbi_cache_cron_am_pm = 'am';

				$options['sb_instagram_type']            = $sb_instagram_type;
				$options['sb_instagram_order']           = $sb_instagram_order;
				$options['sb_instagram_user_id']         = $sb_instagram_user_id;
				$options['sb_instagram_hashtag']         = $sb_instagram_hashtag;
				$options['sb_instagram_tagged_ids']      = $sb_instagram_tagged_ids;
				$options['sb_instagram_type_self_likes'] = $sb_instagram_type_self_likes;
				$options['sb_instagram_location']        = $sb_instagram_location;
				$options['sb_instagram_coordinates']     = $sb_instagram_coordinates;

				$options['sb_instagram_preserve_settings'] = $sb_instagram_preserve_settings;
				$options['sb_instagram_cache_time']        = $sb_instagram_cache_time;
				$options['sb_instagram_cache_time_unit']   = $sb_instagram_cache_time_unit;
				if ( $sb_instagram_cache_time_unit === 'days' ) {
					$sb_instagram_cache_time_unit = 'hours';
					$sb_instagram_cache_time      = 24;
				}
				$options['sbi_caching_type']        = $sbi_caching_type;
				$options['sbi_cache_cron_interval'] = $sbi_cache_cron_interval;
				$options['sbi_cache_cron_time']     = $sbi_cache_cron_time;
				$options['sbi_cache_cron_am_pm']    = $sbi_cache_cron_am_pm;

				//Delete all SBI transients
				global $wpdb;
				$table_name = $wpdb->prefix . 'options';
				$wpdb->query(
					"
                    DELETE
                    FROM $table_name
                    WHERE `option_name` LIKE ('%\_transient\_sbi\_%')
                    "
				);
				$wpdb->query(
					"
                    DELETE
                    FROM $table_name
                    WHERE `option_name` LIKE ('%\_transient\_timeout\_sbi\_%')
                    "
				);
				$wpdb->query(
					"
			        DELETE
			        FROM $table_name
			        WHERE `option_name` LIKE ('%\_transient\_&sbi\_%')
			        "
				);
				$wpdb->query(
					"
			        DELETE
			        FROM $table_name
			        WHERE `option_name` LIKE ('%\_transient\_timeout\_&sbi\_%')
			        "
				);
				$wpdb->query(
					"
                    DELETE
                    FROM $table_name
                    WHERE `option_name` LIKE ('%\_transient\_\$sbi\_%')
                    "
				);
				$wpdb->query(
					"
                    DELETE
                    FROM $table_name
                    WHERE `option_name` LIKE ('%\_transient\_timeout\_\$sbi\_%')
                    "
				);

				if ( $sbi_caching_type === 'background' ) {
					delete_option( 'sbi_cron_report' );
					SB_Instagram_Cron_Updater::start_cron_job( $sbi_cache_cron_interval, $sbi_cache_cron_time, $sbi_cache_cron_am_pm );
				}

				global $sb_instagram_posts_manager;
				$sb_instagram_posts_manager->add_action_log( 'Saved settings on the configure tab.' );
				$sb_instagram_posts_manager->clear_api_request_delays();
			} //End config tab post

			if ( isset( $_POST[ $sb_instagram_customize_hidden_field ] ) && $_POST[ $sb_instagram_customize_hidden_field ] === 'Y' ) {
				//CUSTOMIZE - GENERAL
				//General
				//Validate and sanitize width field
				$safe_width = intval( $_POST['sb_instagram_width'] );
				if ( ! $safe_width ) {
					$safe_width = '';
				}
				if ( strlen( $safe_width ) > 4 ) {
					$safe_width = substr( $safe_width, 0, 4 );
				}
				$sb_instagram_width = $safe_width;

				$sb_instagram_width_unit      = isset( $_POST['sb_instagram_width_unit'] ) ? SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_width_unit'], 'enum', array( 'px', '%' ) ) : 'px';
				$sb_instagram_feed_width_resp = isset( $_POST['sb_instagram_feed_width_resp'] );

				//Validate and sanitize height field
				$safe_height = intval( $_POST['sb_instagram_height'] );
				if ( ! $safe_height ) {
					$safe_height = '';
				}
				if ( strlen( $safe_height ) > 4 ) {
					$safe_height = substr( $safe_height, 0, 4 );
				}
				$sb_instagram_height = $safe_height;

				$sb_instagram_height_unit = isset( $_POST['sb_instagram_height_unit'] ) ? SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_height_unit'], 'enum', array( 'px', '%' ) ) : 'px';
				$sb_instagram_background         = isset( $_POST['sb_instagram_background'] ) && $_POST['sb_instagram_background'] !== '#' ? SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_background'], 'color', 'any' ) : '';

				//Layout Type
				$sb_instagram_layout_type = isset( $_POST['sb_instagram_layout_type'] ) ? SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_layout_type'], 'enum', array( 'grid', 'carousel', 'masonry', 'highlight' ) ) : 'grid';
				$sb_instagram_highlight_type = isset( $_POST['sb_instagram_highlight_type'] ) ? SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_highlight_type'], 'enum', array( 'pattern', 'id', 'hashtag' ) ) : 'pattern';

				if ( isset( $_POST['sb_instagram_highlight_offset'] ) ) {
					$sb_instagram_highlight_offset = (int) $_POST['sb_instagram_highlight_offset'];
				} else {
					$sb_instagram_highlight_offset = 0;
				}

				if ( isset( $_POST['sb_instagram_highlight_factor'] ) ) {
					$sb_instagram_highlight_factor = (int) $_POST['sb_instagram_highlight_factor'];
				} else {
					$sb_instagram_highlight_factor = 0;
				}

				if ( isset( $_POST['sb_instagram_highlight_ids'] ) ) {
					$sb_instagram_highlight_ids = SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_highlight_ids'], 'alpha_numeric_and_comma', 'any' );
				} else {
					$sb_instagram_highlight_ids = '';
				}
				if ( isset( $_POST['sb_instagram_highlight_hashtag'] ) ) {
					$sb_instagram_highlight_hashtag = sanitize_text_field( wp_unslash( $_POST['sb_instagram_highlight_hashtag'] ) );
				} else {
					$sb_instagram_highlight_hashtag = '';
				}

				//Carousel
				$sb_instagram_carousel = ''; // deprecated
				$sb_instagram_carousel_rows = isset( $_POST['sb_instagram_carousel_rows'] ) ? SB_Instagram_Settings::sanitize_setting( (string) $_POST['sb_instagram_carousel_rows'], 'enum', array( '1', '2' ) ) : '1';
				$sb_instagram_carousel_loop = isset( $_POST['sb_instagram_carousel_loop'] ) ? SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_carousel_loop'], 'enum', array( 'loop', 'infinity' ) ) : 'loop';
				$sb_instagram_carousel_arrows = isset( $_POST['sb_instagram_carousel_arrows'] );
				$sb_instagram_carousel_pag = isset( $_POST['sb_instagram_carousel_pag'] );
				$sb_instagram_carousel_autoplay = isset( $_POST['sb_instagram_carousel_autoplay'] );
				if ( isset( $_POST['sb_instagram_carousel_interval'] ) ) {
					$sb_instagram_carousel_interval = (int) $_POST['sb_instagram_carousel_interval'];
				}

				//Num/cols
				//Validate and sanitize number of photos field
				$safe_num = intval( sanitize_text_field( $_POST['sb_instagram_num'] ) );
				if ( ! $safe_num ) {
					$safe_num = '';
				}
				if ( strlen( $safe_num ) > 4 ) {
					$safe_num = substr( $safe_num, 0, 4 );
				}
				$sb_instagram_num = $safe_num;
				if ( isset( $_POST['sb_instagram_nummobile'] ) && (int) $_POST['sb_instagram_nummobile'] !== 0 ) {
					$sb_instagram_nummobile = (int) $_POST['sb_instagram_nummobile'];
				} else {
					$sb_instagram_nummobile = '';
				}

				$sb_instagram_cols           = isset( $_POST['sb_instagram_cols'] ) ? SB_Instagram_Settings::sanitize_setting( (string) $_POST['sb_instagram_cols'], 'enum', array( '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11' ) ) : '4';
				$sb_instagram_colsmobile     = isset( $_POST['sb_instagram_colsmobile'] ) ? SB_Instagram_Settings::sanitize_setting( (string) $_POST['sb_instagram_colsmobile'], 'enum', array( '1', '2', '3', '4', '5', '6', '7', 'same', 'auto' ) ) : 'auto';
				//Validate and sanitize padding field
				$safe_padding = intval( $_POST['sb_instagram_image_padding'] );
				if ( ! $safe_padding ) {
					$safe_padding = '';
				}
				if ( strlen( $safe_padding ) > 4 ) {
					$safe_padding = substr( $safe_padding, 0, 4 );
				}
				$sb_instagram_image_padding = $safe_padding;
				$sb_instagram_image_padding_unit = isset( $_POST['sb_instagram_image_padding_unit'] ) ? SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_image_padding_unit'], 'enum', array( 'px', '%' ) ) : 'px';

				//Header
				$sb_instagram_show_header = isset( $_POST['sb_instagram_show_header'] );
				$sb_instagram_show_bio    = isset( $_POST['sb_instagram_show_bio'] );
				if ( function_exists( 'sanitize_textarea_field' ) ) {
					isset( $_POST['sb_instagram_custom_bio'] ) ? $sb_instagram_custom_bio = sanitize_textarea_field( wp_unslash( $_POST['sb_instagram_custom_bio'] ) ) : $sb_instagram_custom_bio = '';
				} else {
					isset( $_POST['sb_instagram_custom_bio'] ) ? $sb_instagram_custom_bio = sanitize_text_field( wp_unslash( $_POST['sb_instagram_custom_bio'] ) ) : $sb_instagram_custom_bio = '';
				}
				isset( $_POST['sb_instagram_custom_avatar'] ) && sbi_is_url( $_POST['sb_instagram_custom_avatar'] ) ? $sb_instagram_custom_avatar = esc_url( $_POST['sb_instagram_custom_avatar'] ) : $sb_instagram_custom_avatar = '';
				$sb_instagram_header_size = 'small';
				if ( isset( $_POST['sb_instagram_header_size'] ) ) {
					$sb_instagram_header_size = SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_header_size'], 'enum', array( 'small', 'medium', 'large' ) );
				}
				$sb_instagram_header_color = isset( $_POST['sb_instagram_header_color'] ) && $_POST['sb_instagram_header_color'] !== '#' ? SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_header_color'], 'color', 'any' ) : '';

				if ( isset( $_POST['sb_instagram_header_style'] ) ) {
					$sb_instagram_header_style = SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_header_style'], 'enum', array( 'standard', 'boxed', 'centered' ) );
				} else {
					$sb_instagram_header_style = 'standard';
				}
				$sb_instagram_show_followers = isset( $_POST['sb_instagram_show_followers'] );
				$sb_instagram_show_bio = isset( $_POST['sb_instagram_show_bio'] );

				$sb_instagram_header_primary_color = isset( $_POST['sb_instagram_header_primary_color'] ) && $_POST['sb_instagram_header_primary_color'] !== '#' ? SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_header_primary_color'], 'color', 'any' ) : '';
				$sb_instagram_header_secondary_color = isset( $_POST['sb_instagram_header_secondary_color'] ) && $_POST['sb_instagram_header_primary_color'] !== '#' ? SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_header_secondary_color'], 'color', 'any' ) : '';

				$sb_instagram_outside_scrollable = isset( $_POST['sb_instagram_outside_scrollable'] );

				$sb_instagram_stories = isset( $_POST['sb_instagram_stories'] );
				$sb_instagram_stories_time = (int) $_POST['sb_instagram_stories_time'];

				//Load More button
				$sb_instagram_show_btn           = isset( $_POST['sb_instagram_show_btn'] );
				$sb_instagram_btn_background     = isset( $_POST['sb_instagram_btn_background'] ) && $_POST['sb_instagram_btn_background'] !== '#' ? SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_btn_background'], 'color', 'any' ) : '';
				$sb_instagram_btn_text_color     = isset( $_POST['sb_instagram_btn_text_color'] ) && $_POST['sb_instagram_btn_text_color'] !== '#' ? SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_btn_text_color'], 'color', 'any' ) : '';
				$sb_instagram_btn_text           = isset( $_POST['sb_instagram_btn_text'] ) ? sanitize_text_field( wp_unslash( $_POST['sb_instagram_btn_text'] ) ) : __( 'Load More...', 'instagram-feed' );

				//AutoScroll
				$sb_instagram_autoscroll = isset( $_POST['sb_instagram_autoscroll'] );
				if ( isset( $_POST['sb_instagram_autoscrolldistance'] ) ) {
					$sb_instagram_autoscrolldistance = (int) $_POST['sb_instagram_autoscrolldistance'];
				}

				//Follow button
				$sb_instagram_show_follow_btn       = isset( $_POST['sb_instagram_show_follow_btn'] );
				$sb_instagram_folow_btn_background  = isset( $_POST['sb_instagram_folow_btn_background'] ) && $_POST['sb_instagram_folow_btn_background'] !== '#' ? SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_folow_btn_background'], 'color', 'any' ) : '';
				$sb_instagram_follow_btn_text_color = isset( $_POST['sb_instagram_follow_btn_text_color'] ) && $_POST['sb_instagram_follow_btn_text_color'] !== '#' ? SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_follow_btn_text_color'], 'color', 'any' ) : '';
				$sb_instagram_follow_btn_text       = isset( $_POST['sb_instagram_follow_btn_text'] ) ? sanitize_text_field( wp_unslash( $_POST['sb_instagram_follow_btn_text'] ) ) : __( 'Follow on Instagram', 'instagram-feed' );

				//General
				$options['sb_instagram_width']           = $sb_instagram_width;
				$options['sb_instagram_width_unit']      = $sb_instagram_width_unit;
				$options['sb_instagram_feed_width_resp'] = $sb_instagram_feed_width_resp;
				$options['sb_instagram_height']          = $sb_instagram_height;
				$options['sb_instagram_height_unit']     = $sb_instagram_height_unit;
				$options['sb_instagram_background']      = $sb_instagram_background;
				//Layout
				$options['sb_instagram_layout_type']       = $sb_instagram_layout_type;
				$options['sb_instagram_highlight_type']    = $sb_instagram_highlight_type;
				$options['sb_instagram_highlight_offset']  = $sb_instagram_highlight_offset;
				$options['sb_instagram_highlight_factor']  = $sb_instagram_highlight_factor;
				$options['sb_instagram_highlight_ids']     = $sb_instagram_highlight_ids;
				$options['sb_instagram_highlight_hashtag'] = $sb_instagram_highlight_hashtag;
				//Carousel
				$options['sb_instagram_carousel']          = $sb_instagram_carousel;
				$options['sb_instagram_carousel_arrows']   = $sb_instagram_carousel_arrows;
				$options['sb_instagram_carousel_pag']      = $sb_instagram_carousel_pag;
				$options['sb_instagram_carousel_autoplay'] = $sb_instagram_carousel_autoplay;
				$options['sb_instagram_carousel_interval'] = $sb_instagram_carousel_interval;
				$options['sb_instagram_carousel_rows']     = $sb_instagram_carousel_rows;
				$options['sb_instagram_carousel_loop']     = $sb_instagram_carousel_loop;
				//Num/cols
				$options['sb_instagram_num']                = $sb_instagram_num;
				$options['sb_instagram_nummobile']          = $sb_instagram_nummobile;
				$options['sb_instagram_cols']               = $sb_instagram_cols;
				$options['sb_instagram_colsmobile']         = $sb_instagram_colsmobile;
				$options['sb_instagram_image_padding']      = $sb_instagram_image_padding;
				$options['sb_instagram_image_padding_unit'] = $sb_instagram_image_padding_unit;
				//Header
				$options['sb_instagram_show_header']            = $sb_instagram_show_header;
				$options['sb_instagram_header_color']           = $sb_instagram_header_color;
				$options['sb_instagram_header_style']           = $sb_instagram_header_style;
				$options['sb_instagram_show_followers']         = $sb_instagram_show_followers;
				$options['sb_instagram_show_bio']               = $sb_instagram_show_bio;
				$options['sb_instagram_custom_bio']             = $sb_instagram_custom_bio;
				$options['sb_instagram_custom_avatar']          = $sb_instagram_custom_avatar;
				$options['sb_instagram_header_primary_color']   = $sb_instagram_header_primary_color;
				$options['sb_instagram_header_secondary_color'] = $sb_instagram_header_secondary_color;
				$options['sb_instagram_header_size']            = $sb_instagram_header_size;
				$options['sb_instagram_outside_scrollable']     = $sb_instagram_outside_scrollable;
				$options['sb_instagram_stories']                = $sb_instagram_stories;
				$options['sb_instagram_stories_time']           = $sb_instagram_stories_time;

				//Load More button
				$options['sb_instagram_show_btn']       = $sb_instagram_show_btn;
				$options['sb_instagram_btn_background'] = $sb_instagram_btn_background;
				$options['sb_instagram_btn_text_color'] = $sb_instagram_btn_text_color;
				$options['sb_instagram_btn_text']       = $sb_instagram_btn_text;
				//AutoScroll
				$options['sb_instagram_autoscroll']         = $sb_instagram_autoscroll;
				$options['sb_instagram_autoscrolldistance'] = $sb_instagram_autoscrolldistance;
				//Follow button
				$options['sb_instagram_show_follow_btn']       = $sb_instagram_show_follow_btn;
				$options['sb_instagram_moderation_mode']       = $sb_instagram_moderation_mode;
				$options['sb_instagram_folow_btn_background']  = $sb_instagram_folow_btn_background;
				$options['sb_instagram_follow_btn_text_color'] = $sb_instagram_follow_btn_text_color;
				$options['sb_instagram_follow_btn_text']       = $sb_instagram_follow_btn_text;

			}

			if ( isset( $_POST[ $sb_instagram_customize_posts_hidden_field ] ) && $_POST[ $sb_instagram_customize_posts_hidden_field ] === 'Y' ) {

				//CUSTOMIZE - POSTS
				//Photos
				$sb_instagram_sort               = isset( $_POST['sb_instagram_sort'] ) ? SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_sort'], 'enum', array( 'none', 'random', 'likes' ) ) : 'none';
				$sb_instagram_image_res          = isset( $_POST['sb_instagram_image_res'] ) ? SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_image_res'], 'enum', array( 'auto', 'thumb', 'medium', 'full' ) ) : 'auto';
				$sb_instagram_media_type          = isset( $_POST['sb_instagram_media_type'] ) ? SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_media_type'], 'enum', array( 'all', 'videos', 'photos' ) ) : 'all';

				$videotypes = array();
				if ( is_array( $_POST['videotypes'] ) && count( $_POST['videotypes'] ) < 3 ) {
					$videotypes = array();
					foreach ( $_POST['videotypes'] as $video_type ) {
						if ( $video_type === 'regular' ) {
							$videotypes[] = 'regular';
						} else {
							$videotypes[] = 'igtv';
						}
					}
				}
				$videotypes = implode( ',', $videotypes );

				$sb_instagram_disable_lightbox = isset( $_POST['sb_instagram_disable_lightbox'] );
				$sb_instagram_captionlinks = isset( $_POST['sb_instagram_captionlinks'] );
				$sb_instagram_offset = isset( $_POST['sb_instagram_offset'] ) ? (int) $_POST['sb_instagram_offset'] : 0;

				//Photo hover style
				$sb_hover_background         = isset( $_POST['sb_hover_background'] ) && $_POST['sb_hover_background'] !== '#' ? SB_Instagram_Settings::sanitize_setting( $_POST['sb_hover_background'], 'color', 'any' ) : '';
				$sb_hover_text         = isset( $_POST['sb_hover_text'] ) && $_POST['sb_hover_text'] !== '#' ? SB_Instagram_Settings::sanitize_setting( $_POST['sb_hover_text'], 'color', 'any' ) : '';
				$sbi_hover_inc_username = isset( $_POST['sbi_hover_inc_username'] );
				$sbi_hover_inc_icon = isset( $_POST['sbi_hover_inc_icon'] );
				$sbi_hover_inc_date = isset( $_POST['sbi_hover_inc_date'] );
				$sbi_hover_inc_instagram = isset( $_POST['sbi_hover_inc_instagram'] );
				$sbi_hover_inc_caption = isset( $_POST['sbi_hover_inc_caption'] );
				$sbi_hover_inc_likes = isset( $_POST['sbi_hover_inc_likes'] );

				//Text
				$sb_instagram_show_caption = isset( $_POST['sb_instagram_show_caption'] );
				if ( isset( $_POST['sb_instagram_caption_length'] ) && (int) $_POST['sb_instagram_caption_length'] > 0 ) {
					$sb_instagram_caption_length = (int) $_POST['sb_instagram_caption_length'];
				} else {
					$sb_instagram_caption_length = '';
				}
				if ( isset( $_POST['sb_instagram_caption_color'] ) ) {
					$sb_instagram_caption_color         = isset( $_POST['sb_instagram_caption_color'] ) && $_POST['sb_instagram_caption_color'] !== '#' ? SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_caption_color'], 'color', 'any' ) : '';
				}
				if ( isset( $_POST['sb_instagram_caption_size'] ) ) {
					$sb_instagram_caption_size = $_POST['sb_instagram_caption_size'] === 'inherit' ? 'inherit' : SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_caption_size'], 'pxsize', 'any' );
				}

				//Likes & Comments Icons (meta)
				$sb_instagram_show_meta  = isset( $_POST['sb_instagram_show_meta'] );
				$sb_instagram_meta_color = isset( $_POST['sb_instagram_meta_color'] ) && $_POST['sb_instagram_meta_color'] !== '#' ? SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_meta_color'], 'color', 'any' ) : '';

				if ( isset( $_POST['sb_instagram_meta_size'] ) ) {
					$sb_instagram_meta_size = $_POST['sb_instagram_meta_size'] === 'inherit' ? 'inherit' : SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_meta_size'], 'pxsize', 'any' );
				}

				//Lightbox comments
				$sb_instagram_lightbox_comments = isset( $_POST['sb_instagram_lightbox_comments'] );
				if ( isset( $_POST['sb_instagram_num_comments'] ) ) {
					$sb_instagram_num_comments = (int) $_POST['sb_instagram_num_comments'];
				}

				//Photos
				$options['sb_instagram_sort']       = $sb_instagram_sort;
				$options['sb_instagram_image_res']  = $sb_instagram_image_res;
				$options['sb_instagram_media_type'] = $sb_instagram_media_type;
				$options['videotypes']              = $videotypes;

				$options['sb_instagram_disable_lightbox'] = $sb_instagram_disable_lightbox;
				$options['sb_instagram_captionlinks']     = $sb_instagram_captionlinks;
				$options['sb_instagram_offset']           = $sb_instagram_offset;
				//Photo hover style
				$options['sb_hover_background']     = $sb_hover_background;
				$options['sb_hover_text']           = $sb_hover_text;
				$options['sbi_hover_inc_username']  = $sbi_hover_inc_username;
				$options['sbi_hover_inc_icon']      = $sbi_hover_inc_icon;
				$options['sbi_hover_inc_date']      = $sbi_hover_inc_date;
				$options['sbi_hover_inc_instagram'] = $sbi_hover_inc_instagram;
				$options['sbi_hover_inc_location']  = $sbi_hover_inc_location;
				$options['sbi_hover_inc_caption']   = $sbi_hover_inc_caption;
				$options['sbi_hover_inc_likes']     = $sbi_hover_inc_likes;
				//Text
				$options['sb_instagram_show_caption']   = $sb_instagram_show_caption;
				$options['sb_instagram_caption_length'] = $sb_instagram_caption_length;
				$options['sb_instagram_caption_color']  = $sb_instagram_caption_color;
				$options['sb_instagram_caption_size']   = $sb_instagram_caption_size;
				//Meta
				$options['sb_instagram_show_meta']  = $sb_instagram_show_meta;
				$options['sb_instagram_meta_color'] = $sb_instagram_meta_color;
				$options['sb_instagram_meta_size']  = $sb_instagram_meta_size;
				//Lightbox Comments
				$options['sb_instagram_lightbox_comments'] = $sb_instagram_lightbox_comments;
				$options['sb_instagram_num_comments']      = $sb_instagram_num_comments;

			}

			$sb_instagram_ex_apply_to  = isset( $_POST['sb_instagram_ex_apply_to'] ) ? SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_ex_apply_to'], 'enum', array( 'all', 'one' ) ) : 'all';
			$sb_instagram_inc_apply_to = isset( $_POST['sb_instagram_inc_apply_to'] ) ? SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_inc_apply_to'], 'enum', array( 'all', 'one' ) ) : 'all';

			if ( $sb_instagram_ex_apply_to === 'all' ) {
				if ( isset( $_POST['sb_instagram_exclude_words'] ) ) {
					$sb_instagram_exclude_words = sanitize_text_field( wp_unslash( $_POST['sb_instagram_exclude_words'] ) );
				}
			} else {
				$sb_instagram_exclude_words = '';
			}
			if ( $sb_instagram_inc_apply_to === 'all' ) {
				if ( isset( $_POST['sb_instagram_include_words'] ) ) {
					$sb_instagram_include_words = sanitize_text_field( wp_unslash( $_POST['sb_instagram_include_words'] ) );
				}
			} else {
				$sb_instagram_include_words = '';
			}

			//Moderation
			isset( $_POST['sb_instagram_moderation_mode'] ) ? $sb_instagram_moderation_mode = sanitize_text_field( wp_unslash( $_POST['sb_instagram_moderation_mode'] ) ) : $sb_instagram_moderation_mode = 'visual';
			if ( isset( $_POST['sb_instagram_hide_photos'] ) ) {
				$sb_instagram_hide_photos = sanitize_text_field( wp_unslash( $_POST['sb_instagram_hide_photos'] ) );
			}
			$sb_instagram_block_users = '';
			$sb_instagram_show_users  = '';

			if ( isset( $_POST[ $sb_instagram_customize_moderation_hidden_field ] ) && $_POST[ $sb_instagram_customize_moderation_hidden_field ] === 'Y' ) {

				$options['sb_instagram_exclude_words'] = $sb_instagram_exclude_words;
				$options['sb_instagram_include_words'] = $sb_instagram_include_words;
				$options['sb_instagram_ex_apply_to']   = $sb_instagram_ex_apply_to;
				$options['sb_instagram_inc_apply_to']  = $sb_instagram_inc_apply_to;
				//Moderation
				$options['sb_instagram_moderation_mode'] = $sb_instagram_moderation_mode;
				$options['sb_instagram_hide_photos']     = $sb_instagram_hide_photos;
				$options['sb_instagram_block_users']     = $sb_instagram_block_users;
				$options['sb_instagram_show_users']      = $sb_instagram_show_users;

			}

			if ( isset( $_POST[ $sb_instagram_customize_advanced_hidden_field ] ) && $_POST[ $sb_instagram_customize_advanced_hidden_field ] === 'Y' ) {

				//CUSTOMIZE - ADVANCED
				if ( preg_match( '#</?\w+#', $_POST['sb_instagram_custom_css'] ) ) {
					$sb_instagram_custom_css = '';
				} else {
					$sb_instagram_custom_css = $_POST['sb_instagram_custom_css'];
				}
				if ( isset( $_POST['sb_instagram_custom_js'] ) ) {
					$sb_instagram_custom_js = $_POST['sb_instagram_custom_js'];
				} else {
					$sb_instagram_custom_js = '';
				}
				$sb_instagram_ajax_theme        = isset( $_POST['sb_instagram_ajax_theme'] );
				isset( $_POST['gdpr'] ) ? $gdpr = SB_Instagram_Settings::sanitize_setting( $_POST['gdpr'], 'enum', array( 'auto', 'yes', 'no' ) ) : $gdpr = 'auto';
				$enqueue_js_in_head             = isset( $_POST['enqueue_js_in_head'] );
				$disable_js_image_loading       = isset( $_POST['disable_js_image_loading'] );
				$sb_instagram_disable_resize    = isset( $_POST['sb_instagram_disable_resize'] );
				$sb_instagram_favor_local    = isset( $_POST['sb_instagram_favor_local'] );

				//Misc
				if ( isset( $_POST['sb_instagram_requests_max'] ) ) {
					$sb_instagram_requests_max = (int) $_POST['sb_instagram_requests_max'];
				}
				! empty( $_POST['sb_instagram_minnum'] ) ? $sb_instagram_minnum = min( 100, (int) $_POST['sb_instagram_minnum'] ) : $sb_instagram_minnum = '';

				if ( isset( $_POST['sb_instagram_cron'] ) ) {
					$sb_instagram_cron = SB_Instagram_Settings::sanitize_setting( $_POST['sb_instagram_cron'], 'enum', array( 'unset', 'yes', 'no' ) );
				}
				$sb_instagram_backup            = isset( $_POST['sb_instagram_backup'] );
				$sb_ajax_initial                = isset( $_POST['sb_ajax_initial'] );
				$enqueue_css_in_shortcode       = isset( $_POST['enqueue_css_in_shortcode'] );
				$sb_instagram_disable_mob_swipe = isset( $_POST['sb_instagram_disable_mob_swipe'] );
				$sbi_br_adjust                  = isset( $_POST['sbi_br_adjust'] );
				if ( is_multisite() ) {
					isset( $_POST['sbi_super_admin_only'] ) ? $sbi_super_admin_only = sanitize_text_field( wp_unslash( $_POST['sbi_super_admin_only'] ) ) : $sbi_super_admin_only = '';
				}
				//Advanced
				$options['sb_instagram_custom_css'] = $sb_instagram_custom_css;
				$options['sb_instagram_custom_js']  = $sb_instagram_custom_js;
				//Misc
				$options['sb_instagram_ajax_theme']     = $sb_instagram_ajax_theme;
				$options['gdpr']                        = $gdpr;
				$options['enqueue_js_in_head']          = $enqueue_js_in_head;
				$options['disable_js_image_loading']    = $disable_js_image_loading;
				$options['sb_instagram_disable_resize'] = $sb_instagram_disable_resize;
				$options['sb_instagram_favor_local']    = $sb_instagram_favor_local;
				$options['sb_instagram_requests_max']   = $sb_instagram_requests_max;
				$options['sb_instagram_minnum']         = $sb_instagram_minnum;

				$options['sb_instagram_cron']         = $sb_instagram_cron;
				$options['sb_instagram_disable_font'] = $sb_instagram_disable_font;
				$options['sb_ajax_initial']           = $sb_ajax_initial;

				$options['sb_instagram_backup']            = $sb_instagram_backup;
				$options['enqueue_css_in_shortcode']       = $enqueue_css_in_shortcode;
				$options['sb_instagram_disable_mob_swipe'] = $sb_instagram_disable_mob_swipe;
				$options['sbi_br_adjust']                  = $sbi_br_adjust;

				if ( is_multisite() ) {
					$options['sbi_super_admin_only'] = $sbi_super_admin_only;
					update_site_option( 'sbi_super_admin_only', $sbi_super_admin_only );
				}
				$sb_instagram_media_vine            = isset( $_POST['sb_instagram_media_vine'] );
				$options['sb_instagram_media_vine'] = $sb_instagram_media_vine;
				$sb_instagram_custom_template       = isset( $_POST['sb_instagram_custom_template'] );
				$options['custom_template']         = $sb_instagram_custom_template;
				$sb_instagram_disable_admin_notice  = isset( $_POST['sb_instagram_disable_admin_notice'] );
				$options['disable_admin_notice']    = $sb_instagram_disable_admin_notice;
				$sb_instagram_enable_email_report   = isset( $_POST['sb_instagram_enable_email_report'] );
				$options['enable_email_report']     = $sb_instagram_enable_email_report;

				isset( $_POST['sb_instagram_email_notification'] ) ? $sb_instagram_email_notification = sanitize_text_field( wp_unslash( $_POST['sb_instagram_email_notification'] ) ) : $sb_instagram_email_notification = '';
				$original                      = $options['email_notification'];
				$options['email_notification'] = $sb_instagram_email_notification;
				isset( $_POST['sb_instagram_email_notification_addresses'] ) ? $sb_instagram_email_notification_addresses = sanitize_text_field( wp_unslash( $_POST['sb_instagram_email_notification_addresses'] ) ) : $sb_instagram_email_notification_addresses = get_option( 'admin_email' );
				$options['email_notification_addresses'] = $sb_instagram_email_notification_addresses;

				if ( $original !== $sb_instagram_email_notification && ( $sb_instagram_enable_email_report === 'on' || $sb_instagram_enable_email_report === true ) ) {
					//Clear the existing cron event
					wp_clear_scheduled_hook( 'sb_instagram_feed_issue_email' );

					$input     = sanitize_text_field( wp_unslash( $_POST['sb_instagram_email_notification'] ) );
					$timestamp = strtotime( 'next ' . $input );

					if ( $timestamp - ( 3600 * 1 ) < time() ) {
						$timestamp = $timestamp + ( 3600 * 24 * 7 );
					}
					$six_am_local = $timestamp + sbi_get_utc_offset() + ( 6 * 60 * 60 );

					wp_schedule_event( $six_am_local, 'sbiweekly', 'sb_instagram_feed_issue_email' );
				}

				//Delete all SBI transients
				global $wpdb;
				$table_name = $wpdb->prefix . 'options';
				$wpdb->query(
					"
                    DELETE
                    FROM $table_name
                    WHERE `option_name` LIKE ('%\_transient\_sbi\_%')
                    "
				);
				$wpdb->query(
					"
                    DELETE
                    FROM $table_name
                    WHERE `option_name` LIKE ('%\_transient\_timeout\_sbi\_%')
                    "
				);
				$wpdb->query(
					"
			        DELETE
			        FROM $table_name
			        WHERE `option_name` LIKE ('%\_transient\_&sbi\_%')
			        "
				);
				$wpdb->query(
					"
			        DELETE
			        FROM $table_name
			        WHERE `option_name` LIKE ('%\_transient\_timeout\_&sbi\_%')
			        "
				);
				$wpdb->query(
					"
                    DELETE
                    FROM $table_name
                    WHERE `option_name` LIKE ('%\_transient\_\$sbi\_%')
                    "
				);
				$wpdb->query(
					"
                    DELETE
                    FROM $table_name
                    WHERE `option_name` LIKE ('%\_transient\_timeout\_\$sbi\_%')
                    "
				);
				if ( $sb_instagram_cron === 'no' ) {
					wp_clear_scheduled_hook( 'sb_instagram_cron_job' );
				}

				//Run cron when Misc settings are saved
				if ( $sb_instagram_cron === 'yes' ) {
					//Clear the existing cron event
					wp_clear_scheduled_hook( 'sb_instagram_cron_job' );

					$sb_instagram_cache_time      = $options['sb_instagram_cache_time'];
					$sb_instagram_cache_time_unit = $options['sb_instagram_cache_time_unit'];

					//Set the event schedule based on what the caching time is set to
					$sb_instagram_cron_schedule = 'hourly';
					if ( $sb_instagram_cache_time_unit === 'hours' && $sb_instagram_cache_time > 5 ) {
						$sb_instagram_cron_schedule = 'twicedaily';
					}
					if ( $sb_instagram_cache_time_unit === 'days' ) {
						$sb_instagram_cron_schedule = 'daily';
					}

					wp_schedule_event( time(), $sb_instagram_cron_schedule, 'sb_instagram_cron_job' );

					sb_instagram_clear_page_caches();
				}
			}

			//End customize tab post requests

			//Save the settings to the settings array
			update_option( 'sb_instagram_settings', $options );
			$sb_instagram_using_custom_sizes = get_option( 'sb_instagram_using_custom_sizes' );
			if ( isset( $_POST['sb_instagram_using_custom_sizes'] ) ) {
				$sb_instagram_using_custom_sizes = (int) $_POST['sb_instagram_using_custom_sizes'];
			} elseif ( isset( $_GET['tab'] ) && $_GET['tab'] === 'customize' ) {
				$sb_instagram_using_custom_sizes = false;
			}
			update_option( 'sb_instagram_using_custom_sizes', $sb_instagram_using_custom_sizes );
			?>
			<div class="updated"><p><strong><?php esc_html_e( 'Settings saved.', 'instagram-feed' ); ?></strong></p></div>
		<?php } ?>

	<?php } //End nonce check ?>


	<div id="sbi_admin" class="wrap">
		<?php do_action( 'sbi_admin_overview_before_table' ); ?>

		<div id="header">
			<h1><?php esc_html_e( 'Instagram Feed Pro', 'instagram-feed' ); ?></h1>
		</div>

		<?php sbi_expiration_notice(); ?>

		<?php
		$connected_accounts  = SBI_Account_Connector::stored_connected_accounts();
		$user_feeds_returned = false;
		if ( $user_feeds_returned ) {
			$user_feed_ids = $user_feeds_returned;
		} else {
			$user_feed_ids = ! is_array( $sb_instagram_user_id ) ? explode( ',', $sb_instagram_user_id ) : $sb_instagram_user_id;
		}

		$tagged_feed_ids = ! is_array( $sb_instagram_tagged_ids ) ? explode( ',', $sb_instagram_tagged_ids ) : $sb_instagram_tagged_ids;
		$new_user_name   = false;

		SBI_Account_Connector::maybe_launch_modals( $sb_instagram_user_id );
		if ( isset( $_POST['sbi_connect_username'] ) ) {
			$new_user_name       = sanitize_text_field( wp_unslash( $_POST['sbi_connect_username'] ) );
			$new_account_details = json_decode( stripslashes( $_POST['sbi_account_json'] ), true );
			array_map( 'sanitize_text_field', $new_account_details );

			$updated_options    = sbi_connect_basic_account( $new_account_details );
			$connected_accounts = $updated_options['connected_accounts'];
			$user_feed_ids      = $updated_options['sb_instagram_user_id'];
		}

		?>

		<?php
		//Display connected page
		if ( isset( $sbi_connected_page ) && strpos( $sbi_connected_page, ':' ) !== false ) {

			$sbi_connected_page_pieces = explode( ':', $sbi_connected_page );
			$sbi_connected_page_id     = $sbi_connected_page_pieces[0];
			$sbi_connected_page_name   = $sbi_connected_page_pieces[1];
			$sbi_connected_page_image  = $sbi_connected_page_pieces[2];

			echo '&nbsp;';
			echo '<p style="font-weight: bold; margin-bottom: 5px;">Connected Business Profile:</p>';
			echo '<div class="sbi-managed-page sbi-no-select">';
			echo '<p><img class="sbi-page-avatar" border="0"  width="50" src="' . esc_url( $sbi_connected_page_image ) . '"><strong>' . esc_html( $sbi_connected_page_name ) . '</strong> &nbsp; (' . esc_html( $sbi_connected_page_id ) . ')</p>';
			echo '</div>';
		}

		?>

		<form name="form1" method="post" action="">
			<input type="hidden" name="<?php echo $sb_instagram_settings_hidden_field; ?>" value="Y">
			<?php wp_nonce_field( 'sb_instagram_pro_saving_settings', 'sb_instagram_pro_settings_nonce' ); ?>

			<?php $sbi_active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'configure'; ?>
			<h2 class="nav-tab-wrapper">
				<a href="?page=sb-instagram-feed&amp;tab=configure" class="nav-tab <?php echo $sbi_active_tab === 'configure' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( '1. Configure', 'instagram-feed' ); ?></a>
				<a href="?page=sb-instagram-feed&amp;tab=customize" class="nav-tab <?php echo strpos( $sbi_active_tab, 'customize' ) !== false ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( '2. Customize', 'instagram-feed' ); ?></a>
				<a href="?page=sb-instagram-feed&amp;tab=display" class="nav-tab <?php echo $sbi_active_tab === 'display' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( '3. Display Your Feed', 'instagram-feed' ); ?></a>
				<a href="?page=sb-instagram-feed&amp;tab=support" class="nav-tab <?php echo $sbi_active_tab === 'support' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Support', 'instagram-feed' ); ?></a>
				<?php
				$multisite_super_admin_only = get_site_option( 'sbi_super_admin_only', false );

				if ( ! is_multisite() || $multisite_super_admin_only !== 'on' || current_user_can( 'manage_network' ) ) :
					?>
					<a href="?page=sb-instagram-license" class="nav-tab"><?php esc_html_e( 'License', 'instagram-feed' ); ?></a>
				<?php endif; ?>
				<?php if ( ! is_plugin_active( 'social-wall/social-wall.php' ) ) { ?>
					<a href="?page=sbi-sw" class="nav-tab"><?php esc_html_e( 'Create a Social Wall' ); ?><span class="sbi-alert-bubble">New</span></a>
				<?php } ?>
			</h2>
			<?php if ( $sbi_active_tab === 'configure' ) { //Start Configure tab ?>
			<input type="hidden" name="<?php echo $sb_instagram_configure_hidden_field; ?>" value="Y">

			<table class="form-table" aria-describedby="Configure settings">
				<tbody>
				<h3><?php esc_html_e( 'Configure', 'instagram-feed' ); ?></h3>

				<div id="sbi_config">
					<?php sbi_get_connect_account_button(); ?>
					<a href="https://smashballoon.com/instagram-feed/token/" target="_blank" style="position: relative; top: 14px; left: 15px;"><?php esc_html_e( 'Button not working?', 'instagram-feed' ); ?></a>
				</div>

				<tr>
					<th scope="row"><label><?php esc_html_e( 'Instagram Accounts', 'instagram-feed' ); ?></label><span style="font-weight:normal; font-style:italic; font-size: 12px; display: block;"><?php esc_html_e( 'Use the button above to connect an Instagram account', 'instagram-feed' ); ?></span></th>
					<td class="sbi_connected_accounts_wrap">
						<?php if ( empty( $connected_accounts ) ) : ?>
							<p class="sbi_no_accounts"><?php esc_html_e( 'No Instagram accounts connected. Click the button above to connect an account.', 'instagram-feed' ); ?></p><br />
						<?php
						else :
							$doing_account_error_messages = count( $connected_accounts ) > 1;
							global $sb_instagram_posts_manager;

							?>
							<?php
							foreach ( $connected_accounts as $account ) :
								$username = $account['username'];
								if ( isset( $account['local_avatar'] ) && $account['local_avatar'] && isset( $options['sb_instagram_favor_local'] ) && $options['sb_instagram_favor_local'] ) {
									$upload          = wp_upload_dir();
									$resized_url     = trailingslashit( $upload['baseurl'] ) . trailingslashit( SBI_UPLOADS_NAME );
									$profile_picture = '<img class="sbi_ca_avatar" src="' . $resized_url . $account['username'] . '.jpg" />'; //Could add placeholder avatar image
								} else {
									$profile_picture = $account['profile_picture'] ? '<img class="sbi_ca_avatar" src="' . $account['profile_picture'] . '" />' : ''; //Could add placeholder avatar image
								}

								$is_invalid_class = ! $account['is_valid'] ? ' sbi_account_invalid' : '';
								$in_user_feed     = in_array( $account['user_id'], $user_feed_ids, true );
								$account_type     = isset( $account['type'] ) ? $account['type'] : 'personal';
								$use_tagged       = isset( $account['use_tagged'] ) && $account['use_tagged'] === '1';
								$is_private       = isset( $account['private'] ) && $account['private'] !== false;

								if ( empty( $profile_picture ) && $account_type === 'personal' ) {
									$account_update = sbi_account_data_for_token( $account['access_token'] );
									if ( isset( $account['is_valid'] ) ) {
										$split                           = explode( '.', $account['access_token'] );
										$connected_accounts[ $split[0] ] = array(
											'access_token' => $account['access_token'],
											'user_id'      => $split[0],
											'username'     => $account_update['username'],
											'is_valid'     => true,
											'last_checked' => time(),
											'profile_picture' => $account_update['profile_picture'],
										);

										$sbi_options                       = get_option( 'sb_instagram_settings', array() );
										$sbi_options['connected_accounts'] = $connected_accounts;
										update_option( 'sb_instagram_settings', $sbi_options );
									}
								}
								$updated_or_new_account_class = $new_user_name === $username && $account_type !== 'business' ? ' sbi_ca_new_or_updated' : '';

								?>
								<div class="sbi_connected_account<?php echo esc_attr( $is_invalid_class . $updated_or_new_account_class ); ?>
																		<?php
								if ( $in_user_feed ) {
									echo ' sbi_account_active';}
								?>
							 sbi_account_type_<?php echo esc_attr( $account_type ); ?>" id="sbi_connected_account_<?php echo esc_attr( $account['user_id'] ); ?>" data-accesstoken="" data-userid="<?php echo esc_attr( $account['user_id'] ); ?>" data-username="<?php echo esc_attr( $account['username'] ); ?>" data-type="<?php echo esc_attr( $account_type ); ?>" data-permissions="<?php if ( $use_tagged ) {echo 'tagged'; } ?>">
									<?php if ( $doing_account_error_messages && $sb_instagram_posts_manager->connected_account_has_error( $account ) ) : ?>
										<div class="sbi_deprecated">
											<span><i class="fa fa-exclamation-circle" aria-hidden="true"></i><?php esc_html_e( 'Feeds using this account might not be updating due to an error. Try viewing these feeds after reconnecting the account and saving your settings below.', 'instagram-feed' ); ?></span>
										</div>
									<?php endif; ?>
									<div class="sbi_ca_alert">
										<span><?php esc_html_e( 'The Access Token for this account is expired or invalid. Click the button above to attempt to renew it.', 'instagram-feed' ); ?></span>
									</div>
									<div class="sbi_ca_info">

										<div class="sbi_ca_delete">
											<a href="<?php echo esc_url( add_query_arg( 'disconnect', $account['user_id'], admin_url( 'admin.php?page=sb-instagram-feed' ) ) ); ?>" class="sbi_delete_account"><i class="fa fa-times"></i><span class="sbi_remove_text"><?php esc_html_e( 'Remove', 'instagram-feed' ); ?></span></a>
										</div>

										<div class="sbi_ca_username">
											<?php echo wp_kses_post( $profile_picture ); ?>
											<strong><?php echo esc_html( $username ); ?><span><?php echo esc_html( sbi_account_type_display( $account_type, isset( $account['private'] ) ) ); ?></span></strong>
										</div>

										<div class="sbi_ca_actions">
											<?php if ( ! $in_user_feed ) : ?>
												<a href="JavaScript:void(0);" class="sbi_use_in_user_feed button-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i><?php esc_html_e( 'Add to Primary Feed', 'instagram-feed' ); ?></a>
											<?php else : ?>
												<a href="JavaScript:void(0);" class="sbi_remove_from_user_feed button-primary"><i class="fa fa-minus-circle" aria-hidden="true"></i><?php esc_html_e( 'Remove from Primary Feed', 'instagram-feed' ); ?></a>
											<?php endif; ?>
											<a class="sbi_ca_token_shortcode button-secondary" href="JavaScript:void(0);"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i><?php esc_html_e( 'Add to another Feed', 'instagram-feed' ); ?></a>
											<a class="sbi_ca_show_token button-secondary" href="JavaScript:void(0);" title="<?php esc_attr_e( 'Show access token and account info', 'instagram-feed' ); ?>"><i class="fa fa-cog"></i></a>
											<?php
											if ( $is_private ) :
												$expires_in  = max( 0, floor( ( $account['expires_timestamp'] - time() ) / DAY_IN_SECONDS ) );
												$message     = $expires_in > 0 ? sprintf( __( 'Expires in %s days', 'instagram-feed' ), $expires_in ) : __( 'Access Token Expired', 'instagram-feed' );
												$alert_class = $expires_in < 10 ? ' sbi_alert' : '';
												?>
												<div class="sbi_is_private<?php echo esc_attr( $alert_class ); ?>">
													<span><?php echo esc_html( $message ); ?></span>
													<a class="sbi_tooltip_link sbi_tooltip_outside" href="JavaScript:void(0);" style="position: relative; top: 2px;"><i class="fa fa-question-circle" aria-hidden="true"></i></a>

													<a href="<?php echo esc_url( 'https://api.instagram.com/oauth/authorize?client_id=423965861585747&redirect_uri=https://api.smashballoon.com/v2/instagram-basic-display-redirect.php&response_type=code&scope=user_profile,user_media&state=' . admin_url( 'admin.php?page=sb-instagram-feed' ) ); ?>" class="button button-secondary"><?php esc_html_e( 'Refresh now', 'instagram-feed' ); ?></a>
												</div>
												<p class="sbi_tooltip sbi-more-info" style="display: none; width: 100%; box-sizing: border-box;"><?php echo wp_kses_post( sprintf( __( 'This account is a "private" account on Instagram. It needs to be manually reconnected every 60 days. %1$sChange this account to be "public"%2$s to have access tokens that are automatically refreshed.', 'instagram-feed' ), '<a href="https://help.instagram.com/116024195217477/In" target="_blank">', '</a>' ) ); ?></p>
											<?php endif; ?>

										</div>

										<div class="sbi_ca_shortcode">

											<p><?php esc_html_e( 'Copy and paste this shortcode into your page or widget area', 'instagram-feed' ); ?>:<br>
												<?php if ( ! empty( $account['username'] ) ) : ?>
													<code>[instagram-feed user="<?php echo esc_html( $account['username'] ); ?>"]</code>
												<?php endif; ?>
											</p>

											<p><?php esc_html_e( 'To add multiple users in the same feed, simply separate them using commas', 'instagram-feed' ); ?>:<br>
												<?php if ( ! empty( $account['username'] ) ) : ?>
													<code>[instagram-feed user="<?php echo esc_html( $account['username'] ); ?>, a_second_user, a_third_user"]</code>
												<?php endif; ?>

											<p><?php echo wp_kses_post( sprintf( __( 'Click on the %s tab to learn more about shortcodes', 'instagram-feed' ), '<a href="?page=sb-instagram-feed&tab=display" target="_blank">' . __( 'Display Your Feed', 'instagram-feed' ) . '</a>' ) ); ?></p>
										</div>

										<div class="sbi_ca_accesstoken">
											<span class="sbi_ca_token_label"><?php esc_html_e( 'User ID', 'instagram-feed' ); ?>:</span><input type="text" class="sbi_ca_user_id" value="<?php echo esc_attr( $account['user_id'] ); ?>" readonly="readonly" onclick="this.focus();this.select()" title="<?php esc_attr_e( 'To copy, click the field then press Ctrl + C (PC) or Cmd + C (Mac).', 'instagram-feed' ); ?>"><br>
										</div>

									</div>

								</div>

							<?php endforeach; ?>
						<?php endif; ?>
						<a href="JavaScript:void(0);" class="sbi_manually_connect button-secondary"><?php esc_html_e( 'Manually Connect an Account', 'instagram-feed' ); ?></a>
						<div class="sbi_manually_connect_wrap">
							<input name="sb_manual_at" id="sb_manual_at" type="text" value="" style="margin-top: 4px; padding: 5px 9px; margin-left: 0;" size="64" minlength="15" maxlength="400" placeholder="<?php esc_attr_e( 'Enter a valid Instagram Access Token', 'instagram-feed' ); ?>" /><span class='sbi_business_profile_tag'><?php esc_html_e( 'Business or Basic Display', 'instagram-feed' ); ?></span>
							<div class="sbi_manual_account_id_toggle">
								<label><?php esc_html_e( 'Please enter the User ID for this Profile:', 'instagram-feed' ); ?></label>
								<input name="sb_manual_account_id" id="sb_manual_account_id" type="text" value="" style="margin-top: 4px; padding: 5px 9px; margin-left: 0;" size="40" minlength="5" maxlength="100" placeholder="Eg: 15641403491391489" />
							</div>
							<p id="sbi_no_js_warning" class="sbi_nojs_notice"><?php echo wp_kses_post( sprintf( __( 'It looks like JavaScript is not working on this page. Some features may not work fully. Visit %1$sthis page%2$s for help resolving this issue.', 'instagram-feed' ), '<a href="https://smashballoon.com/i-cant-connect-or-manage-accounts-on-the-instagram-feed-settings-page/" target="_blank" rel="noopener">', '</a>' ) ); ?></p>
							<p class="sbi_submit" style="display: inline-block;"><input type="submit" name="sbi_submit" id="sbi_manual_submit" class="button button-primary" value="<?php esc_html_e( 'Connect This Account', 'instagram-feed' ); ?>"></p>
						</div>
					</td>
				</tr>

				<tr >
					<th scope="row"><label><?php esc_html_e( 'Select a Feed Type', 'instagram-feed' ); ?>:</label><code class="sbi_shortcode"> type
							Eg: type=user user=smashballoon
							Eg: type=hashtag hashtag="dogs"
							Eg: type=tagged tagged=smashballoon
							Eg: type=mixed user=x hashtag=x</code>
						<?php if ( SB_Instagram_Feed_Locator::count_unique() > 1 ) : ?>
							<div class="sbi_locations_link">
								<a href="?page=sb-instagram-feed&amp;tab=allfeeds"><svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-search fa-w-16 fa-2x"><path fill="currentColor" d="M508.5 468.9L387.1 347.5c-2.3-2.3-5.3-3.5-8.5-3.5h-13.2c31.5-36.5 50.6-84 50.6-136C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c52 0 99.5-19.1 136-50.6v13.2c0 3.2 1.3 6.2 3.5 8.5l121.4 121.4c4.7 4.7 12.3 4.7 17 0l22.6-22.6c4.7-4.7 4.7-12.3 0-17zM208 368c-88.4 0-160-71.6-160-160S119.6 48 208 48s160 71.6 160 160-71.6 160-160 160z" class=""></path></svg> <?php esc_html_e( 'Feed Finder', 'instagram-feed' ); ?></a>
							</div>
						<?php endif; ?>

					</th>
					<td>
						<div class="sbi_row" style="min-height: 29px;">
							<div class="sbi_col sbi_one">
								<input type="radio" name="sb_instagram_type" id="sb_instagram_type_user" value="user"
									<?php
									if ( $sb_instagram_type === 'user' ) {
										echo 'checked'; }
									?>
								/>
								<label class="sbi_radio_label" for="sb_instagram_type_user"><?php esc_html_e( 'User Account', 'instagram-feed' ); ?>: <a class="sbi_type_tooltip_link" href="JavaScript:void(0);"><i class="fa fa-question-circle" aria-hidden="true" style="margin-left: 2px;"></i></a></label>
							</div>
							<div class="sbi_col sbi_two">
								<div class="sbi_user_feed_ids_wrap">
									<?php
									foreach ( $user_feed_ids as $feed_id ) :
										if ( $feed_id !== '' ) :
											?>
											<?php
											if ( ! empty( $connected_accounts ) ) {
												?>
												<div id="sbi_user_feed_id_<?php echo esc_attr( $feed_id ); ?>" class="sbi_user_feed_account_wrap"><?php } ?>

											<?php if ( isset( $connected_accounts[ $feed_id ] ) && ! empty( $connected_accounts[ $feed_id ]['username'] ) ) : ?>
											<strong><?php echo esc_html( $connected_accounts[ $feed_id ]['username'] ); ?></strong> <span>(<?php echo esc_html( $feed_id ); ?>)</span>
											<input name="sb_instagram_user_id[]" id="sb_instagram_user_id" type="hidden" value="<?php echo esc_attr( $feed_id ); ?>" />
										<?php elseif ( isset( $connected_accounts[ $feed_id ] ) && ! empty( $connected_accounts[ $feed_id ]['access_token'] ) ) : ?>
											<strong><?php echo esc_html( $feed_id ); ?></strong>
											<input name="sb_instagram_user_id[]" id="sb_instagram_user_id" type="hidden" value="<?php echo esc_attr( $feed_id ); ?>" />
										<?php endif; ?>

											<?php
											if ( ! empty( $connected_accounts ) ) {
												?>
												</div><?php } ?>
										<?php
										endif;
									endforeach;
									?>
								</div>

								<?php if ( empty( $user_feed_ids ) ) : ?>
									<p class="sbi_no_accounts" style="margin-top: -3px; margin-right: 10px;"><?php esc_html_e( 'Connect a user account above', 'instagram-feed' ); ?></p>
								<?php endif; ?>
							</div>

							<div class="sbi_tooltip sbi_type_tooltip">
								<p><?php esc_html_e( 'In order to display posts from a User account, first connect an account using the button above.', 'instagram-feed' ); ?></p>
								<p style="padding-top:8px;"><strong><?php esc_html_e( 'Multiple Acounts', 'instagram-feed' ); ?></strong><br />
									<?php esc_html_e( 'It is only possible to display feeds from Instagram accounts which you own. In order to display feeds from multiple accounts, first connect them above and then use the buttons to add the account either to your primary feed or to another feed on your site.', 'instagram-feed' ); ?>
								</p>
								<p style="padding:10px 0 6px 0;">
									<strong><?php esc_html_e( 'Displaying Posts from Other Instagram Accounts', 'instagram-feed' ); ?></strong><br />
									<?php esc_html_e( "Due to Instagram restrictions it is not possible to display photos from other Instagram accounts which you do not have access to. You can only display the user feed of an account which you connect above. You can connect as many account as you like by logging in using the button above, or manually copy/pasting an Access Token by selecting the 'Manually Connect an Account' option.", 'instagram-feed' ); ?>
								</p>
							</div>
						</div>

						<?php
						//Check whether a business account is connected and hashtag feed is selected so we can display an error
						$sbi_business_account_connected = false;
						$sbi_hashtag_feed_issue         = false;
						foreach ( $connected_accounts as $connected_account ) {
							if ( isset( $connected_account['type'] ) && $connected_account['type'] === 'business' ) {
								$sbi_business_account_connected = true;
							}
						}
						if ( $sb_instagram_type === 'hashtag' && $sbi_business_account_connected === false && empty( $connected_accounts ) ) {
							$sbi_hashtag_feed_issue = true;
						}
						?>

						<div class="sbi_row
						<?php
						if ( $sbi_hashtag_feed_issue ) {
							echo 'sbi_hashtag_feed_issue'; }
						?>
							">
							<div class="sbi_col sbi_one">
								<input type="radio" name="sb_instagram_type" id="sb_instagram_type_hashtag" value="hashtag"
									<?php
									if ( $sb_instagram_type === 'hashtag' ) {
										echo 'checked'; }
									?>
								/>
								<label class="sbi_radio_label" for="sb_instagram_type_hashtag">Public Hashtag: <a class="sbi_type_tooltip_link" href="JavaScript:void(0);"><i class="fa fa-question-circle" aria-hidden="true" style="margin-left: 2px;"></i></a></label>
							</div>
							<div class="sbi_col sbi_two">
								<input name="sb_instagram_hashtag" id="sb_instagram_hashtag" type="text" value="<?php echo esc_attr( $sb_instagram_hashtag ); ?>" size="45" placeholder="Eg: #one, #two, #three" />
								<?php $order_val = isset( $sb_instagram_order ) ? $sb_instagram_order : 'top'; ?>
								<div class="sbi_radio_reveal">
									<label class="sbi_shortcode_label"><?php esc_html_e( 'Order of Posts', 'instagram-feed' ); ?>:<code class="sbi_shortcode">order=recent | order=top</code></label>
									<div class="sbi_row">
										<input name="sb_instagram_order" id="sb_instagram_order_top" type="radio" value="top"
											<?php
											if ( $order_val === 'top' ) {
												echo 'checked'; }
											?>
										/><label for="sb_instagram_order_top"><?php esc_html_e( 'Top', 'instagram-feed' ); ?><span>(<?php esc_html_e( 'Most popular first', 'instagram-feed' ); ?>)</span></label>
									</div>
									<div class="sbi_row">
										<input name="sb_instagram_order" id="sb_instagram_order_recent" type="radio" value="recent"
											<?php
											if ( $order_val === 'recent' ) {
												echo 'checked'; }
											?>
										/><label for="sb_instagram_order_recent"><?php esc_html_e( 'Recent', 'instagram-feed' ); ?><span>(<?php esc_html_e( 'Within 24 hours or "Top posts" initially', 'instagram-feed' ); ?> <a class="sbi_type_tooltip_link" href="JavaScript:void(0);" style="margin-left: 0;"><i class="fa fa-question-circle" aria-hidden="true" style="padding: 2px;"></i></a>)</span></label>

										<p class="sbi_tooltip"><?php esc_html_e( 'Instagram only returns the most recent hashtag posts from the past 24 hour period. The first time you display a hashtag feed, posts from the "Top posts" section will be retrieved, sorted by date, and saved. The plugin then stores these posts so that they can continue to be displayed indefinitely, creating a permanent feed of your posts.', 'instagram-feed' ); ?></p>
									</div>
								</div>
							</div>
							<p class="sbi_tooltip sbi_type_tooltip"><?php esc_html_e( 'Display all public posts from a hashtag instead of from a user. Separate multiple hashtags using commas. To show posts from a user feed that also have a certain hashtag, use the "includewords" shortcode setting.', 'instagram-feed' ); ?><br /><strong>Note:</strong> <?php esc_html_e( 'To display a hashtag feed, it is required that you first connect an Instagram Business Profile using the', 'instagram-feed' ); ?> <strong>"Connect an Instagram Account"</strong> <?php esc_html_e( 'button above', 'instagram-feed' ); ?>. &nbsp;<a href="https://smashballoon.com/instagram-business-profiles/" target="_blank"><?php esc_html_e( 'Why is this required?', 'instagram-feed' ); ?></a></p>

							<?php if ( $sbi_hashtag_feed_issue ) { ?>
								<p class="sbi_hashtag_feed_issue_note"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <?php esc_html_e( 'Hashtag Feeds now require a Business Profile to be connected.', 'instagram-feed' ); ?> <a href="<?php echo esc_url( admin_url( 'admin.php?page=sbi-welcome-new' ) ); ?>" target="_blank" rel="noopener noreferrer">See here</a> for more info.</p>
							<?php } ?>
						</div>

						<div class="sbi_row" style="min-height: 29px;">
							<div class="sbi_col sbi_one">
								<input type="radio" name="sb_instagram_type" id="sb_instagram_type_tagged" value="tagged"
									<?php
									if ( $sb_instagram_type === 'tagged' ) {
										echo 'checked'; }
									?>
								/>
								<label class="sbi_radio_label" for="sb_instagram_type_tagged">Tagged: <a class="sbi_type_tooltip_link" href="JavaScript:void(0);"><i class="fa fa-question-circle" aria-hidden="true" style="margin-left: 2px;"></i></a></label>
							</div>
							<div class="sbi_col sbi_two">
								<div class="sbi_tagged_feed_ids_wrap">
									<?php
									foreach ( $tagged_feed_ids as $feed_id ) :
										if ( $feed_id !== '' ) :
											?>
											<div id="sbi_tagged_feed_id_<?php echo esc_attr( $feed_id ); ?>" class="sbi_tagged_feed_account_wrap">

												<?php if ( isset( $connected_accounts[ $feed_id ] ) && ! empty( $connected_accounts[ $feed_id ]['username'] ) ) : ?>
													<strong><?php echo esc_html( $connected_accounts[ $feed_id ]['username'] ); ?></strong> <span>(<?php echo esc_html( $feed_id ); ?>)</span>
													<input name="sb_instagram_tagged_id[]" id="sb_instagram_tagged_id" type="hidden" value="<?php echo esc_attr( $feed_id ); ?>" />
												<?php elseif ( isset( $connected_accounts[ $feed_id ] ) && ! empty( $connected_accounts[ $feed_id ]['access_token'] ) ) : ?>
													<strong><?php echo esc_html( $feed_id ); ?></strong>
													<input name="sb_instagram_tagged_id[]" id="sb_instagram_tagged_id" type="hidden" value="<?php echo esc_attr( $feed_id ); ?>" />
												<?php endif; ?>

											</div>
										<?php
										endif;
									endforeach;
									?>
								</div>

								<?php if ( empty( $tagged_feed_ids ) ) : ?>
									<p class="sbi_no_accounts" style="margin-right: 10px;"><?php esc_html_e( 'Connect a user account above', 'instagram-feed' ); ?></p>
								<?php endif; ?>
							</div>
							<p class="sbi_tooltip sbi_type_tooltip"><?php esc_html_e( 'Display photos that an account has been tagged in. Separate multiple user names using commas.', 'instagram-feed' ); ?><br /><strong>Note:</strong> <?php esc_html_e( 'To display a tagged feed, it is required that you first connect an Instagram Business Profile using the', 'instagram-feed' ); ?> <strong>"Connect an Instagram Account"</strong> <?php esc_html_e( 'button above. This account must have been connected or reconnected after version 5.2 of the Pro version or 2.1 of the Free version', 'instagram-feed' ); ?>. &nbsp;<a href="https://smashballoon.com/instagram-business-profiles/" target="_blank"><?php esc_html_e( 'Why is this required?', 'instagram-feed' ); ?></a></p>
						</div>

						<div class="sbi_row sbi_mixed_directions">
							<div class="sbi_col sbi_one">
								<input type="radio" name="sb_instagram_type" disabled />
								<label class="sbi_radio_label" for="sb_instagram_type_mixed">Mixed: <a href="JavaScript:void(0);"><i class="fa fa-question-circle" aria-hidden="true" style="margin-left: 2px;"></i></a></label>
							</div>
							<div class="sbi_col sbi_two" style="max-width: 350px;">
								<input name="sb_instagram_hashtag" id="sb_instagram_mixed" type="text" size="45" disabled />
							</div>
							<div class="sbi_tooltip sbi_type_tooltip">
								<p>
									<?php echo wp_kses_post( sprintf( __( 'To display multiple feed types in a single feed, use %1$s in your shortcode and then add the user name or hashtag for each feed into the shortcode, like so: %2$s. This will combine a user feed and a hashtag feed into the same feed.', 'instagram-feed' ), 'type="mixed"', '<code>[instagram-feed type="mixed" user="smashballoon" hashtag="#awesomeplugins"]</code>' ) ); ?>
								</p>
								<p style="padding-top: 8px;"><strong>Note:</strong> To display a hashtag feed, it is required that you first connect an Instagram Business Profile using the <strong>"Connect an Instagram Account"</strong> button above. &nbsp;<a href="https://smashballoon.com/instagram-business-profiles/" target="_blank">Why is this required?</a>
								</p>
							</div>
						</div>

					</td>
				</tr>

				<tr>
					<th scope="row" class="bump-left"><label class="bump-left"><?php esc_html_e( 'Preserve settings when plugin is removed', 'instagram-feed' ); ?></label></th>
					<td>
						<input name="sb_instagram_preserve_settings" type="checkbox" id="sb_instagram_preserve_settings"
							<?php
							if ( $sb_instagram_preserve_settings ) {
								echo 'checked'; }
							?>
						/>
						<label for="sb_instagram_preserve_settings"><?php esc_html_e( 'Yes' ); ?></label>
						<a class="sbi_tooltip_link" href="JavaScript:void(0);"><i class="fa fa-question-circle" aria-hidden="true" style="margin-right: 6px;"></i></a>
						<p class="sbi_tooltip"><?php esc_html_e( 'When removing the plugin your settings are automatically erased. Checking this box will prevent any settings from being deleted. This means that you can uninstall and reinstall the plugin without losing your settings.', 'instagram-feed' ); ?></p>
					</td>
				</tr>


				<tr  class="sbi_cron_cache_opts">
					<th scope="row"><?php esc_html_e( 'Check for new posts', 'instagram-feed' ); ?></th>
					<td>
						<div class="sbi_row">
							<input type="radio" name="sbi_caching_type" id="sbi_caching_type_cron" value="background"
								<?php
								if ( $sbi_caching_type === 'background' ) {
									echo 'checked'; }
								?>
							>
							<label for="sbi_caching_type_cron"><?php esc_html_e( 'In the background', 'instagram-feed' ); ?></label>
							<a class="sbi_tooltip_link" href="JavaScript:void(0);" style="position: relative; top: 2px;"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
							<p class="sbi_tooltip sbi-more-info"><?php esc_html_e( 'Your Instagram post data is temporarily cached by the plugin in your WordPress database. There are two ways that you can set the plugin to check for new data', 'instagram-feed' ); ?>:<br><br>
								<?php esc_html_e( '<strong>1. In the background</strong><br>Selecting this option means that the plugin will check for new data in the background so that the feed is updated behind the scenes. You can select at what time and how often the plugin should check for new data using the settings below. <strong>Please note</strong> that the plugin will initially check for data from Instagram when the page first loads, but then after that will check in the background on the schedule selected - unless the cache is cleared.', 'instagram-feed' ); ?>
								<br><br>
								<?php esc_html_e( '<strong>2. When the page loads</strong><br>Selecting this option means that when the cache expires then the plugin will check Instagram for new posts the next time that the feed is loaded. You can choose how long this data should be cached for. If you set the time to 60 minutes then the plugin will clear the cached data after that length of time, and the next time the page is viewed it will check for new data. <strong>Tip:</strong> If you\'re experiencing an issue with the plugin not updating automatically then try enabling the setting labeled <strong>\'Force cache to clear on interval\'</strong> which is located on the \'Customize\' tab.', 'instagram-feed' ) . '</p>'; ?>

						</div>
						<div class="sbi_row sbi-caching-cron-options" style="display: block;">

							<select name="sbi_cache_cron_interval" id="sbi_cache_cron_interval">
								<option value="30mins"
									<?php
									if ( $sbi_cache_cron_interval === '30mins' ) {
										echo 'selected'; }
									?>
								><?php esc_html_e( 'Every 30 minutes', 'instagram-feed' ); ?></option>
								<option value="1hour"
									<?php
									if ( $sbi_cache_cron_interval === '1hour' ) {
										echo 'selected'; }
									?>
								><?php esc_html_e( 'Every hour', 'instagram-feed' ); ?></option>
								<option value="12hours"
									<?php
									if ( $sbi_cache_cron_interval === '12hours' ) {
										echo 'selected'; }
									?>
								><?php esc_html_e( 'Every 12 hours', 'instagram-feed' ); ?></option>
								<option value="24hours"
									<?php
									if ( $sbi_cache_cron_interval === '24hours' ) {
										echo 'selected'; }
									?>
								><?php esc_html_e( 'Every 24 hours', 'instagram-feed' ); ?></option>
							</select>

							<div id="sbi-caching-time-settings" style="display: none;">
								<?php esc_html_e( 'at' ); ?>

								<select name="sbi_cache_cron_time" style="width: 80px">
									<option value="1"
										<?php
										if ( $sbi_cache_cron_time === '1' ) {
											echo 'selected'; }
										?>
									>1:00</option>
									<option value="2"
										<?php
										if ( $sbi_cache_cron_time === '2' ) {
											echo 'selected'; }
										?>
									>2:00</option>
									<option value="3"
										<?php
										if ( $sbi_cache_cron_time === '3' ) {
											echo 'selected'; }
										?>
									>3:00</option>
									<option value="4"
										<?php
										if ( $sbi_cache_cron_time === '4' ) {
											echo 'selected'; }
										?>
									>4:00</option>
									<option value="5"
										<?php
										if ( $sbi_cache_cron_time === '5' ) {
											echo 'selected'; }
										?>
									>5:00</option>
									<option value="6"
										<?php
										if ( $sbi_cache_cron_time === '6' ) {
											echo 'selected'; }
										?>
									>6:00</option>
									<option value="7"
										<?php
										if ( $sbi_cache_cron_time === '7' ) {
											echo 'selected'; }
										?>
									>7:00</option>
									<option value="8"
										<?php
										if ( $sbi_cache_cron_time === '8' ) {
											echo 'selected'; }
										?>
									>8:00</option>
									<option value="9"
										<?php
										if ( $sbi_cache_cron_time === '9' ) {
											echo 'selected'; }
										?>
									>9:00</option>
									<option value="10"
										<?php
										if ( $sbi_cache_cron_time === '10' ) {
											echo 'selected'; }
										?>
									>10:00</option>
									<option value="11"
										<?php
										if ( $sbi_cache_cron_time === '11' ) {
											echo 'selected'; }
										?>
									>11:00</option>
									<option value="0"
										<?php
										if ( $sbi_cache_cron_time === '0' ) {
											echo 'selected'; }
										?>
									>12:00</option>
								</select>

								<select name="sbi_cache_cron_am_pm" style="width: 50px">
									<option value="am"
										<?php
										if ( $sbi_cache_cron_am_pm === 'am' ) {
											echo 'selected'; }
										?>
									>AM</option>
									<option value="pm"
										<?php
										if ( $sbi_cache_cron_am_pm === 'pm' ) {
											echo 'selected'; }
										?>
									>PM</option>
								</select>
							</div>

							<?php
							if ( wp_next_scheduled( 'sbi_feed_update' ) ) {
								$time_format = get_option( 'time_format' );
								if ( ! $time_format ) {
									$time_format = 'g:i a';
								}
								//
								$schedule = wp_get_schedule( 'sbi_feed_update' );
								if ( $schedule === '30mins' ) {
									$schedule = __( 'every 30 minutes', 'instagram-feed' );
								}
								if ( $schedule === 'twicedaily' ) {
									$schedule = __( 'every 12 hours', 'instagram-feed' );
								}
								$sbi_next_cron_event = wp_next_scheduled( 'sbi_feed_update' );
								echo '<p class="sbi-caching-sched-notice"><span><strong>' . esc_html__( 'Next check', 'instagram-feed' ) . ': ' . esc_html( date( $time_format, $sbi_next_cron_event + sbi_get_utc_offset() ) ) . ' (' . esc_html( $schedule ) . ')</strong> - ' . esc_html__( 'Note: Saving the settings on this page will clear the cache and reset this schedule', 'instagram-feed' ) . '</span></p>';
							} else {
								echo '<p style="font-size: 11px; color: #666;">' . esc_html__( 'Nothing currently scheduled', 'instagram-feed' ) . '</p>';
							}
							?>

						</div>
						<div class="sbi_row">
							<input type="radio" name="sbi_caching_type" id="sbi_caching_type_page" value="page"
								<?php
								if ( $sbi_caching_type === 'page' ) {
									echo 'checked'; }
								?>
							>
							<label for="sbi_caching_type_page"><?php esc_html_e( 'When the page loads', 'instagram-feed' ); ?></label>
						</div>
						<div class="sbi_row sbi-caching-page-options" style="display: none;">
							<?php esc_html_e( 'Every', 'instagram-feed' ); ?>:
							<?php
							if ( $sb_instagram_cache_time_unit === 'minutes' ) {
								$max = 1440;
							} else {
								$max = 24;
							}
							?>
							<input name="sb_instagram_cache_time" type="number" value="<?php echo esc_attr( $sb_instagram_cache_time ); ?>" size="4" min="1" max="<?php echo esc_attr( $max ); ?>"/>
							<select name="sb_instagram_cache_time_unit">
								<option value="minutes"
									<?php
									if ( $sb_instagram_cache_time_unit === 'minutes' ) {
										echo 'selected="selected"'; }
									?>
								><?php esc_html_e( 'Minutes', 'instagram-feed' ); ?></option>
								<option value="hours"
									<?php
									if ( $sb_instagram_cache_time_unit === 'hours' ) {
										echo 'selected="selected"'; }
									?>
								><?php esc_html_e( 'Hours', 'instagram-feed' ); ?></option>
							</select>
							<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What does this mean?', 'instagram-feed' ); ?></a>
							<p class="sbi_tooltip"><?php esc_html_e( 'Your Instagram posts are temporarily cached by the plugin in your WordPress database. You can choose how long the posts should be cached for. If you set the time to 1 hour then the plugin will clear the cache after that length of time and check Instagram for posts again. The maximum caching time is 24 hours.', 'instagram-feed' ); ?></p>
						</div>

					</td>
				</tr>

				</tbody>
			</table>

			<?php submit_button(); ?>
		</form>


		<p><i class="fa fa-chevron-circle-right" aria-hidden="true"></i>&nbsp; <?php echo wp_kses_post( sprintf( __( 'Next Step: %s', 'instagram-feed' ), '<a href="?page=sb-instagram-feed&tab=customize">' . __( 'Customize your Feed', 'instagram-feed' ) . '</a>' ) ); ?></p>
		<p><i class="fa fa-life-ring" aria-hidden="true"></i>&nbsp; <?php echo wp_kses_post( sprintf( __( 'Need help setting up the plugin? Check out our %s', 'instagram-feed' ), '<a href="https://smashballoon.com/instagram-feed/docs/" target="_blank">' . __( 'setup directions', 'instagram-feed' ) . '</a>' ) ); ?></p>


	<?php
	} // End Configure tab

	if ( $sbi_active_tab === 'allfeeds' ) {
		$locator_summary = SB_Instagram_Feed_Locator::summary();
		include_once trailingslashit( SBI_PLUGIN_DIR ) . 'inc/admin/templates/locator-summary.php';
	}

	if ( strpos( $sbi_active_tab, 'customize' ) !== false ) { //Show Customize sub tabs
		?>

		<h2 class="nav-tab-wrapper sbi-subtabs">
			<a href="?page=sb-instagram-feed&amp;tab=customize" class="nav-tab <?php echo $sbi_active_tab === 'customize' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'General' ); ?></a>
			<a href="?page=sb-instagram-feed&amp;tab=customize-posts" class="nav-tab <?php echo $sbi_active_tab === 'customize-posts' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Posts' ); ?></a>
			<a href="?page=sb-instagram-feed&amp;tab=customize-moderation" class="nav-tab <?php echo $sbi_active_tab === 'customize-moderation' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Moderation' ); ?></a>
			<a href="?page=sb-instagram-feed&amp;tab=customize-advanced" class="nav-tab <?php echo $sbi_active_tab === 'customize-advanced' ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Advanced' ); ?></a>
		</h2>

	<?php } ?>

		<?php if ( $sbi_active_tab === 'customize' ) { //Start General tab ?>

			<p class="sb_instagram_contents_links" id="general">
				<span><?php esc_html_e( 'Jump to:', 'instagram-feed' ); ?> </span>
				<a href="#general"><?php esc_html_e( 'General', 'instagram-feed' ); ?></a>
				<a href="#layout"><?php esc_html_e( 'Layout', 'instagram-feed' ); ?></a>
				<a href="#headeroptions"><?php esc_html_e( 'Header', 'instagram-feed' ); ?></a>
				<a href="#loadmore"><?php esc_html_e( "'Load More' Button", 'instagram-feed' ); ?></a>
				<a href="#follow"><?php esc_html_e( "'Follow' Button", 'instagram-feed' ); ?></a>
			</p>

			<input type="hidden" name="<?php echo $sb_instagram_customize_hidden_field; ?>" value="Y">

			<h3><?php esc_html_e( 'General', 'instagram-feed' ); ?></h3>

			<table class="form-table" aria-describedby="Feed width">
				<tbody>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Width of Feed', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> width  widthunit
							Eg: width=50 widthunit=%</code></th>
					<td>
						<input name="sb_instagram_width" type="text" value="<?php echo esc_attr( $sb_instagram_width ); ?>" id="sb_instagram_width" size="4" />
						<select name="sb_instagram_width_unit" id="sb_instagram_width_unit">
							<option value="px"
								<?php
								if ( $sb_instagram_width_unit === 'px' ) {
									echo 'selected="selected"'; }
								?>
							>px</option>
							<option value="%"
								<?php
								if ( $sb_instagram_width_unit === '%' ) {
									echo 'selected="selected"'; }
								?>
							>%</option>
						</select>
						<div id="sb_instagram_width_options">
							<input name="sb_instagram_feed_width_resp" type="checkbox" id="sb_instagram_feed_width_resp"
								<?php
								if ( $sb_instagram_feed_width_resp ) {
									echo 'checked'; }
								?>
							/><label for="sb_instagram_feed_width_resp"><?php esc_html_e( 'Set to be 100% width on mobile?' ); ?></label>
							<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What does this mean?', 'instagram-feed' ); ?></a>
							<p class="sbi_tooltip"><?php esc_html_e( 'If you set a width on the feed then this will be used on mobile as well as desktop. Check this setting to set the feed width to be 100% on mobile so that it is responsive.', 'instagram-feed' ); ?></p>
						</div>
					</td>
				</tr>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Height of Feed', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> height  heightunit
							Eg: height=500 heightunit=px</code></th>
					<td>
						<input name="sb_instagram_height" type="text" value="<?php echo esc_attr( $sb_instagram_height ); ?>" size="4" />
						<select name="sb_instagram_height_unit">
							<option value="px"
								<?php
								if ( $sb_instagram_height_unit === 'px' ) {
									echo 'selected="selected"'; }
								?>
							>px</option>
							<option value="%"
								<?php
								if ( $sb_instagram_height_unit === '%' ) {
									echo 'selected="selected"'; }
								?>
							>%</option>
						</select>
					</td>
				</tr>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Background Color', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> background
							Eg: background=d89531</code></th>
					<td>
						<input name="sb_instagram_background" type="text" value="<?php echo esc_attr( $sb_instagram_background ); ?>" class="sbi_colorpick" />
					</td>
				</tr>
				</tbody>
			</table>

			<hr id="layout" />
			<h3><?php esc_html_e( 'Layout', 'instagram-feed' ); ?></h3>

			<table class="form-table" aria-describedby="Layout">
				<tbody>
				<?php
				$selected_type = isset( $sb_instagram_layout_type ) ? $sb_instagram_layout_type : 'grid';
				$layout_types  = array(
					'grid'      => __( 'Grid', 'instagram-feed' ),
					'carousel'  => __( 'Carousel', 'instagram-feed' ),
					'masonry'   => __( 'Masonry', 'instagram-feed' ),
					'highlight' => __( 'Highlight', 'instagram-feed' ),
				);
				$layout_images = array(
					'grid'      => SBI_PLUGIN_URL . 'img/grid.png',
					'carousel'  => SBI_PLUGIN_URL . 'img/carousel.png',
					'masonry'   => SBI_PLUGIN_URL . 'img/masonry.png',
					'highlight' => SBI_PLUGIN_URL . 'img/highlight.png',
				);
				?>

				<tr >
					<th scope="row"><label><?php esc_html_e( 'Layout Type', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> layout
							Eg: layout=grid
							Eg: layout=carousel
							Eg: layout=masonry
							Eg: layout=highlight</code></th>
					<td>
						<?php foreach ( $layout_types as $layout_type => $label ) : ?>
							<div class="sbi_layout_cell
							<?php
							if ( $selected_type === $layout_type ) {
								echo 'sbi_layout_selected'; }
							?>
								">
								<input class="sb_layout_type" id="sb_layout_type_<?php echo esc_attr( $layout_type ); ?>" name="sb_instagram_layout_type" type="radio" value="<?php echo esc_attr( $layout_type ); ?>"
									<?php
									if ( $selected_type === $layout_type ) {
										echo 'checked'; }
									?>
								/><label for="sb_layout_type_<?php echo esc_attr( $layout_type ); ?>"><span class="sbi_label"><?php echo esc_html( $label ); ?></span><img src="<?php echo esc_url( $layout_images[ $layout_type ] ); ?>" alt="<?php echo esc_attr( $layout_type ); ?>" /></label>
							</div>
						<?php endforeach; ?>
						<div class="sb_layout_options_wrap">
							<div class="sb_instagram_layout_settings sbi_layout_type_grid">
								<i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 8px;"></i><span class="sbi_note" style="margin-left: 0;"><?php esc_html_e( 'A uniform grid of square-cropped images.' ); ?></span>
							</div>
							<div class="sb_instagram_layout_settings sbi_layout_type_masonry">
								<i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 8px;"></i><span class="sbi_note" style="margin-left: 0;"><?php esc_html_e( 'Images in their original aspect ratios with no vertical space between posts.' ); ?></span>
							</div>
							<div class="sb_instagram_layout_settings sbi_layout_type_carousel">
								<div class="sb_instagram_layout_setting">
									<i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 8px;"></i><span class="sbi_note" style="margin-left: 0;"><?php esc_html_e( 'Posts are displayed in a slideshow carousel.', 'instagram-feed' ); ?></span>
								</div>
								<div class="sb_instagram_layout_setting">

									<label><?php esc_html_e( 'Number of Rows', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> carouselrows
										Eg: carouselrows=2</code>
									<br />
									<span class="sbi_note" style="margin: -5px 0 -10px 0; display: block;">Use the "Number of Columns" setting below this section to set how many posts are visible in the carousel at a given time.</span>
									<br />
									<select name="sb_instagram_carousel_rows" id="sb_instagram_carousel_rows">
										<option value="1"
											<?php
											if ( $sb_instagram_carousel_rows === '1' ) {
												echo 'selected="selected"'; }
											?>
										>1</option>
										<option value="2"
											<?php
											if ( $sb_instagram_carousel_rows === '2' ) {
												echo 'selected="selected"'; }
											?>
										>2</option>
									</select>
								</div>
								<div class="sb_instagram_layout_setting">
									<label><?php esc_html_e( 'Loop Type', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> carouselloop
										Eg: carouselloop=rewind
										carouselloop=infinity</code>
									<br />
									<select name="sb_instagram_carousel_loop" id="sb_instagram_carousel_loop">
										<option value="rewind"
											<?php
											if ( $sb_instagram_carousel_loop === 'rewind' ) {
												echo 'selected="selected"'; }
											?>
										><?php esc_html_e( 'Rewind', 'instagram-feed' ); ?></option>
										<option value="infinity"
											<?php
											if ( $sb_instagram_carousel_loop === 'infinity' ) {
												echo 'selected="selected"'; }
											?>
										><?php esc_html_e( 'Infinity', 'instagram-feed' ); ?></option>
									</select>
								</div>
								<div class="sb_instagram_layout_setting">
									<input type="checkbox" name="sb_instagram_carousel_arrows" id="sb_instagram_carousel_arrows"
										<?php
										if ( $sb_instagram_carousel_arrows ) {
											echo 'checked="checked"'; }
										?>
									/>
									<label><?php esc_html_e( 'Show Navigation Arrows', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> carouselarrows
										Eg: carouselarrows=true</code>
								</div>
								<div class="sb_instagram_layout_setting">
									<input type="checkbox" name="sb_instagram_carousel_pag" id="sb_instagram_carousel_pag"
										<?php
										if ( $sb_instagram_carousel_pag ) {
											echo 'checked="checked"'; }
										?>
									/>
									<label><?php esc_html_e( 'Show Pagination', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> carouselpag
										Eg: carouselpag=true</code>
								</div>
								<div class="sb_instagram_layout_setting">
									<input type="checkbox" name="sb_instagram_carousel_autoplay" id="sb_instagram_carousel_autoplay"
										<?php
										if ( $sb_instagram_carousel_autoplay ) {
											echo 'checked="checked"'; }
										?>
									/>
									<label><?php esc_html_e( 'Enable Autoplay', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> carouselautoplay
										Eg: carouselautoplay=true</code>
								</div>
								<div class="sb_instagram_layout_setting">
									<label><?php esc_html_e( 'Interval Time', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> carouseltime
										Eg: carouseltime=8000</code>
									<br />
									<input name="sb_instagram_carousel_interval" type="text" value="<?php echo esc_attr( $sb_instagram_carousel_interval ); ?>" size="6" /><?php esc_html_e( 'miliseconds', 'instagram-feed' ); ?>
								</div>
							</div>

							<div class="sb_instagram_layout_settings sbi_layout_type_highlight">
								<div class="sb_instagram_layout_setting">
									<i class="fa fa-info-circle" aria-hidden="true" style="margin-right: 8px;"></i><span class="sbi_note" style="margin-left: 0;"><?php esc_html_e( 'Masonry style, square-cropped, image only (no captions or likes/comments below image). "Highlighted" posts are twice as large.', 'instagram-feed' ); ?></span>
								</div>
								<div class="sb_instagram_layout_setting">
									<label><?php esc_html_e( 'Highlighting Type', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> highlighttype
										Eg: highlighttype=pattern</code>
									<br />
									<select name="sb_instagram_highlight_type" id="sb_instagram_highlight_type">
										<option value="pattern"
											<?php
											if ( $sb_instagram_highlight_type === 'pattern' ) {
												echo 'selected="selected"'; }
											?>
										><?php esc_html_e( 'Pattern', 'instagram-feed' ); ?></option>
										<option value="id"
											<?php
											if ( $sb_instagram_highlight_type === 'id' ) {
												echo 'selected="selected"'; }
											?>
										><?php esc_html_e( 'Post ID', 'instagram-feed' ); ?></option>
										<option value="hashtag"
											<?php
											if ( $sb_instagram_highlight_type === 'hashtag' ) {
												echo 'selected="selected"'; }
											?>
										><?php esc_html_e( 'Hashtag', 'instagram-feed' ); ?></option>
									</select>
								</div>
								<div class="sb_instagram_highlight_sub_options sb_instagram_highlight_pattern sb_instagram_layout_setting">
									<label><?php esc_html_e( 'Offset', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> highlightoffset
										Eg: highlightoffset=2</code>
									<br />
									<input name="sb_instagram_highlight_offset" type="number" min="0" value="<?php echo esc_attr( $sb_instagram_highlight_offset ); ?>" style="width: 50px;" />
								</div>
								<div class="sb_instagram_highlight_sub_options sb_instagram_highlight_pattern sb_instagram_layout_setting">
									<label><?php esc_html_e( 'Pattern', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> highlightpattern
										Eg: highlightpattern=3</code>
									<br />
									<span><?php esc_html_e( 'Highlight every', 'instagram-feed' ); ?></span><input name="sb_instagram_highlight_factor" type="number" min="2" value="<?php echo esc_attr( $sb_instagram_highlight_factor ); ?>" style="width: 50px;" /><span><?php esc_html_e( 'posts', 'instagram-feed' ); ?></span>
								</div>
								<div class="sb_instagram_highlight_sub_options sb_instagram_highlight_hashtag sb_instagram_layout_setting">
									<label><?php esc_html_e( 'Highlight Posts with these Hashtags', 'instagram-feed' ); ?></label>
									<input name="sb_instagram_highlight_hashtag" id="sb_instagram_highlight_hashtag" type="text" size="40" value="<?php echo esc_attr( $sb_instagram_highlight_hashtag ); ?>" />&nbsp;<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What is this?', 'instagram-feed' ); ?></a>
									<br />
									<span class="sbi_note" style="margin-left: 0;"><?php esc_html_e( 'Separate multiple hashtags using commas', 'instagram-feed' ); ?></span>


									<p class="sbi_tooltip"><?php esc_html_e( 'You can use this setting to highlight posts by a hashtag. Use a specified hashtag in your posts and they will be automatically highlighted in your feed.', 'instagram-feed' ); ?></p>
								</div>
								<div class="sb_instagram_highlight_sub_options sb_instagram_highlight_ids sb_instagram_layout_setting">
									<label><?php esc_html_e( 'Highlight Posts by ID', 'instagram-feed' ); ?></label>
									<textarea name="sb_instagram_highlight_ids" id="sb_instagram_highlight_ids" style="width: 100%;" rows="3"><?php echo esc_textarea( $sb_instagram_highlight_ids ); ?></textarea>
									<br />
									<span class="sbi_note" style="margin-left: 0;"><?php esc_html_e( 'Separate IDs using commas', 'instagram-feed' ); ?></span>

									&nbsp;<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What is this?', 'instagram-feed' ); ?></a>
									<p class="sbi_tooltip"><?php esc_html_e( 'You can use this setting to highlight posts by their ID. Enable and use "moderation mode", check the box to show post IDs underneath posts, then copy and paste IDs into this text box.', 'instagram-feed' ); ?></p>
								</div>
							</div>

						</div>
					</td>
				</tr>


				<tr >
					<th scope="row"><label><?php esc_html_e( 'Number of Photos', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> num
							Eg: num=6</code></th>
					<td>
						<input name="sb_instagram_num" type="text" value="<?php echo esc_attr( $sb_instagram_num ); ?>" size="4" />
						<span class="sbi_note"><?php esc_html_e( 'Number of photos to show initially', 'instagram-feed' ); ?></span>
						&nbsp;<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What is this?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( "This is the number of photos which will be displayed initially and also the number which will be loaded in when you click on the 'Load More' button in your feed. For optimal performance it is recommended not to set this higher than 50.", 'instagram-feed' ); ?></p>
						<br>
						<a href="javascript:void(0);" class="sb_instagram_mobile_layout_reveal button-secondary"><?php esc_html_e( 'Show Mobile Options', 'instagram-feed' ); ?></a>
						<br>
						<div class="sb_instagram_mobile_layout_setting">
							<p style="font-weight: bold; padding-bottom: 5px;"><?php esc_html_e( 'Number of Photos on Mobile', 'instagram-feed' ); ?></p>
							<input name="sb_instagram_nummobile" type="number" value="<?php echo esc_attr( $sb_instagram_nummobile ); ?>" min="0" max="100" style="width: 50px;" />
							<span class="sbi_note"><?php esc_html_e( 'Leave blank to use the same as above', 'instagram-feed' ); ?></span>
						</div>
					</td>
				</tr>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Number of Columns', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> cols
							Eg: cols=3</code></th>
					<td>
						<select name="sb_instagram_cols">
							<option value="1"
								<?php
								if ( $sb_instagram_cols === '1' ) {
									echo 'selected="selected"'; }
								?>
							>1</option>
							<option value="2"
								<?php
								if ( $sb_instagram_cols === '2' ) {
									echo 'selected="selected"'; }
								?>
							>2</option>
							<option value="3"
								<?php
								if ( $sb_instagram_cols === '3' ) {
									echo 'selected="selected"'; }
								?>
							>3</option>
							<option value="4"
								<?php
								if ( $sb_instagram_cols === '4' ) {
									echo 'selected="selected"'; }
								?>
							>4</option>
							<option value="5"
								<?php
								if ( $sb_instagram_cols === '5' ) {
									echo 'selected="selected"'; }
								?>
							>5</option>
							<option value="6"
								<?php
								if ( $sb_instagram_cols === '6' ) {
									echo 'selected="selected"'; }
								?>
							>6</option>
							<option value="7"
								<?php
								if ( $sb_instagram_cols === '7' ) {
									echo 'selected="selected"'; }
								?>
							>7</option>
							<option value="8"
								<?php
								if ( $sb_instagram_cols === '8' ) {
									echo 'selected="selected"'; }
								?>
							>8</option>
							<option value="9"
								<?php
								if ( $sb_instagram_cols === '9' ) {
									echo 'selected="selected"'; }
								?>
							>9</option>
							<option value="10"
								<?php
								if ( $sb_instagram_cols === '10' ) {
									echo 'selected="selected"'; }
								?>
							>10</option>
						</select>
						<br>
						<a href="javascript:void(0);" class="sb_instagram_mobile_layout_reveal button-secondary"><?php esc_html_e( 'Show Mobile Options', 'instagram-feed' ); ?></a>
						<br>
						<div class="sb_instagram_mobile_layout_setting">

							<p style="font-weight: bold; padding-bottom: 5px;"><?php esc_html_e( 'Number of Columns on Mobile', 'instagram-feed' ); ?></p>
							<select name="sb_instagram_colsmobile">
								<option value="auto"
									<?php
									if ( $sb_instagram_colsmobile === 'auto' ) {
										echo 'selected="selected"'; }
									?>
								><?php esc_html_e( 'Auto', 'instagram-feed' ); ?></option>
								<option value="same"
									<?php
									if ( $sb_instagram_colsmobile === 'same' ) {
										echo 'selected="selected"'; }
									?>
								><?php esc_html_e( 'Same as desktop', 'instagram-feed' ); ?></option>
								<option value="1"
									<?php
									if ( $sb_instagram_colsmobile === '1' ) {
										echo 'selected="selected"'; }
									?>
								>1</option>
								<option value="2"
									<?php
									if ( $sb_instagram_colsmobile === '2' ) {
										echo 'selected="selected"'; }
									?>
								>2</option>
								<option value="3"
									<?php
									if ( $sb_instagram_colsmobile === '3' ) {
										echo 'selected="selected"'; }
									?>
								>3</option>
								<option value="4"
									<?php
									if ( $sb_instagram_colsmobile === '4' ) {
										echo 'selected="selected"'; }
									?>
								>4</option>
								<option value="5"
									<?php
									if ( $sb_instagram_colsmobile === '5' ) {
										echo 'selected="selected"'; }
									?>
								>5</option>
								<option value="6"
									<?php
									if ( $sb_instagram_colsmobile === '6' ) {
										echo 'selected="selected"'; }
									?>
								>6</option>
								<option value="7"
									<?php
									if ( $sb_instagram_colsmobile === '7' ) {
										echo 'selected="selected"'; }
									?>
								>7</option>
							</select>
							&nbsp;<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What does "Auto" mean?', 'instagram-feed' ); ?></a>
							<p class="sbi_tooltip" style="padding: 10px 0 0 0;"><?php esc_html_e( 'This means that the plugin will automatically calculate how many columns to use for mobile based on the screen size and number of columns selected above. For example, a feed which is set to use 4 columns will show 2 columns for screen sizes less than 640 pixels and 1 column for screen sizes less than 480 pixels.', 'instagram-feed' ); ?></p>
						</div>
					</td>
				</tr>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Padding around Images', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> imagepadding  imagepaddingunit</code></th>
					<td>
						<input name="sb_instagram_image_padding" type="text" value="<?php echo esc_attr( $sb_instagram_image_padding ); ?>" size="4" />
						<select name="sb_instagram_image_padding_unit">
							<option value="px"
								<?php
								if ( $sb_instagram_image_padding_unit === 'px' ) {
									echo 'selected="selected"'; }
								?>
							>px</option>
							<option value="%"
								<?php
								if ( $sb_instagram_image_padding_unit === '%' ) {
									echo 'selected="selected"'; }
								?>
							>%</option>
						</select>
					</td>
				</tr>

				</tbody>
			</table>
			<?php submit_button(); ?>

			<hr id="headeroptions" />
			<h3><?php esc_html_e( 'Header', 'instagram-feed' ); ?></h3>
			<table class="form-table" aria-describedby="Feed header">
				<tbody>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Show Feed Header', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> showheader
							Eg: showheader=false</code></th>
					<td>
						<input type="checkbox" name="sb_instagram_show_header" id="sb_instagram_show_header"
							<?php
							if ( $sb_instagram_show_header ) {
								echo 'checked="checked"'; }
							?>
						/>
					</td>
				</tr>
				</tbody>
			</table>

			<p class="submit sbi-expand-button">
				<a href="javascript:void(0);" class="button">Show Customization Options</a>
			</p>

			<table class="form-table sbi-expandable-options" aria-describedby="Header settings">
				<tbody>

				<tr >
					<th scope="row"><label><?php esc_html_e( 'Header Style', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> headerstyle
							Eg: headerstyle=boxed</code></th>
					<td>
						<select name="sb_instagram_header_style" id="sb_instagram_header_style" style="float: left;">
							<option value="standard"
								<?php
								if ( $sb_instagram_header_style === 'standard' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( 'Standard', 'instagram-feed' ); ?></option>
							<option value="boxed"
								<?php
								if ( $sb_instagram_header_style === 'boxed' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( 'Boxed', 'instagram-feed' ); ?></option>
							<option value="centered"
								<?php
								if ( $sb_instagram_header_style === 'centered' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( 'Centered', 'instagram-feed' ); ?></option>
						</select>
						<div id="sb_instagram_header_style_boxed_options">
							<p><?php esc_html_e( 'Please select 2 background colors for your Boxed header:', 'instagram-feed' ); ?></p>
							<div class="sbi_row">
								<div class="sbi_col sbi_one">
									<label><?php esc_html_e( 'Primary Color', 'instagram-feed' ); ?></label>
								</div>
								<div class="sbi_col sbi_two">
									<input name="sb_instagram_header_primary_color" type="text" value="<?php echo esc_attr( $sb_instagram_header_primary_color ); ?>" class="sbi_colorpick" />
								</div>
							</div>

							<div class="sbi_row">
								<div class="sbi_col sbi_one">
									<label><?php esc_html_e( 'Secondary Color', 'instagram-feed' ); ?></label>
								</div>
								<div class="sbi_col sbi_two">
									<input name="sb_instagram_header_secondary_color" type="text" value="<?php echo esc_attr( $sb_instagram_header_secondary_color ); ?>" class="sbi_colorpick" />
								</div>
							</div>
							<p style="margin-top: 10px;"><?php esc_html_e( "Don't forget to set your text color below.", 'instagram-feed' ); ?></p>
						</div>
					</td>
				</tr>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Header Size', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> headersize
							Eg: headersize=medium</code></th>
					<td>
						<select name="sb_instagram_header_size" id="sb_instagram_header_size" style="float: left;">
							<option value="small"
								<?php
								if ( $sb_instagram_header_size === 'small' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( 'Small', 'instagram-feed' ); ?></option>
							<option value="medium"
								<?php
								if ( $sb_instagram_header_size === 'medium' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( 'Medium', 'instagram-feed' ); ?></option>
							<option value="large"
								<?php
								if ( $sb_instagram_header_size === 'large' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( 'Large', 'instagram-feed' ); ?></option>
						</select>
					</td>
				</tr>

				</tr>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Show Number of Followers', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> showfollowers
							Eg: showfollowers=false</code></th>
					<td>
						<input type="checkbox" name="sb_instagram_show_followers" id="sb_instagram_show_followers"
							<?php
							if ( $sb_instagram_show_followers ) {
								echo 'checked="checked"'; }
							?>
						/>
						<span class="sbi_note"><?php esc_html_e( 'User feeds from a Business account only.', 'instagram-feed' ); ?></span><a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'Why?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php echo wp_kses_post( sprintf( __( 'Instagram is deprecating their old API for Personal accounts on June 1, 2020. The plugin supports their new API, however, some features (such as this one) are not yet available in their new API. If you require this feature then it is available if you convert your Instagram account from a Personal to a Business account by following %s.', 'instagram-feed' ), '<a href="https://smashballoon.com/instagram-business-profiles/" target="_blank">these directions</a>' ) ); ?></p>
					</td>
				</tr>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Show Bio Text', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> showbio
							Eg: showbio=false</code></th>
					<td>
						<?php $sb_instagram_show_bio = isset( $sb_instagram_show_bio ) ? $sb_instagram_show_bio : true; ?>
						<input type="checkbox" name="sb_instagram_show_bio" id="sb_instagram_show_bio"
							<?php
							if ( $sb_instagram_show_bio ) {
								echo 'checked="checked"'; }
							?>
						/>
						<span class="sbi_note"><?php esc_html_e( 'Only applies for Instagram accounts with bios', 'instagram-feed' ); ?></span><br />
						<div class="sb_instagram_box" style="display: block;">
							<div class="sb_instagram_box_setting" style="display: block;">
								<label style="padding-bottom: 0;"><?php esc_html_e( 'Add Custom Bio Text', 'instagram-feed' ); ?></label><code class="sbi_shortcode" style="margin-top: 5px;"> custombio
									Eg: custombio="My custom bio."</code>
								<br>
								<span class="sbi_aside" style="padding-bottom: 5px; display: block;"><?php esc_html_e( 'Use your own custom bio text in the feed header. Bio text is automatically retrieved from Instagram for Business accounts.', 'instagram-feed' ); ?></span>

								<textarea type="text" name="sb_instagram_custom_bio" id="sb_instagram_custom_bio" ><?php echo esc_textarea( stripslashes( $sb_instagram_custom_bio ) ); ?></textarea>
								&nbsp;<a class="sbi_tooltip_link sbi_tooltip_under" href="JavaScript:void(0);"><?php esc_html_e( 'Why is my bio not displaying automatically?', 'instagram-feed' ); ?></a>
								<p class="sbi_tooltip" style="padding: 10px 0 0 0; width: 99%;"><?php echo wp_kses_post( sprintf( __( 'Instagram is deprecating their old API for Personal accounts on June 1, 2020. The plugin supports their new API, however, their new API does not yet include the bio text for Personal accounts. If you require this feature then it is available if you convert your Instagram account from a Personal to a Business account by following %s. Note: If you previously had a Personal account connected then the plugin has saved the avatar for that feed and will continue to use it automatically.', 'instagram-feed' ), '<a href="https://smashballoon.com/instagram-business-profiles/" target="_blank">these directions</a>' ) ); ?></p>
							</div>
						</div>

					</td>
				</tr>

				<tr >
					<th scope="row"><label><?php esc_html_e( 'Use Custom Avatar', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> customavatar
							Eg: customavatar="https://my-website.com/avatar.jpg"</code></th>
					<td>
						<input type="text" name="sb_instagram_custom_avatar" class="large-text" id="sb_instagram_custom_avatar" value="<?php echo esc_attr( stripslashes( $sb_instagram_custom_avatar ) ); ?>" placeholder="https://example.com/avatar.jpg" />
						<span class="sbi_aside"><?php esc_html_e( 'Avatar is automatically retrieved from Instagram for Business accounts', 'instagram-feed' ); ?></span>
						<br>
						<a class="sbi_tooltip_link sbi_tooltip_under" href="JavaScript:void(0);"><?php esc_html_e( 'Why is my avatar not displaying automatically?', 'instagram-feed' ); ?></a>

						<p class="sbi_tooltip sbi_tooltip_under_text" style="padding: 10px 0 0 0;"><?php echo wp_kses_post( sprintf( __( 'Instagram is deprecating their old API for Personal accounts on June 1, 2020. The plugin supports their new API, however, their new API does not yet include the avatar image for Personal accounts. If you require this feature then it is available if you convert your Instagram account from a Personal to a Business account by following %s. Note: If you previously had a Personal account connected then the plugin has saved the bio text for that feed and will continue to use it automatically.', 'instagram-feed' ), '<a href="https://smashballoon.com/instagram-business-profiles/" target="_blank">these directions</a>' ) ); ?></p>

					</td>
				</tr>

				<tr >
					<th scope="row"><label><?php esc_html_e( 'Header Text Color', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> headercolor
							Eg: headercolor=fff</code></th>
					<td>
						<input name="sb_instagram_header_color" type="text" value="<?php echo esc_attr( $sb_instagram_header_color ); ?>" class="sbi_colorpick" />
					</td>
				</tr>

				<tr >
					<th scope="row"><label><?php esc_html_e( 'Display Outside the Scrollable Area', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> headeroutside
							Eg: headeroutside=true</code></th>
					<td>
						<input name="sb_instagram_outside_scrollable" type="checkbox"
							<?php
							if ( $sb_instagram_outside_scrollable ) {
								echo 'checked="checked"'; }
							?>
						/>
						<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What is this?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( 'This positions the Header outside of the feed container. It is useful if your feed has a vertical scrollbar as it places it outside of the scrollable area and fixes it at the top.', 'instagram-feed' ); ?></p>
					</td>
				</tr>

				<tr >
					<th scope="row"><label><?php esc_html_e( 'Include Stories', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> stories
							Eg: stories=true</code></th>
					<td>
						<input name="sb_instagram_stories" type="checkbox"
							<?php
							if ( $sb_instagram_stories ) {
								echo 'checked="checked"'; }
							?>
						/>

						<span class="sbi_note"><?php esc_html_e( 'Business accounts only', 'instagram-feed' ); ?></span><br />

						<div class="sb_instagram_box" style="display: block;">
							<div class="sb_instagram_box_setting" style="display: block;">
								<label><?php esc_html_e( 'Slide Change Interval', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> storiestime
									Eg: storiestime=5000</code>
								<br>
								<input name="sb_instagram_stories_time" type="number" min="1000" step="500" value="<?php echo esc_attr( $sb_instagram_stories_time ); ?>" style="width: 80px;">milliseconds
								<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What is this?', 'instagram-feed' ); ?></a>
								<p class="sbi_tooltip"><?php esc_html_e( 'This is the number of milliseconds that an image story slide will display before displaying the next slide. Videos always change when the video is finished.', 'instagram-feed' ); ?></p>
							</div>
						</div>
					</td>
				</tr>
				</tbody>
			</table>


			<hr id="loadmore" />
			<h3><?php esc_html_e( "'Load More' Button", 'instagram-feed' ); ?></h3>
			<table class="form-table" aria-describedby="Load more settings">
				<tbody>
				<tr >
					<th scope="row"><label><?php esc_html_e( "Show the 'Load More' button", 'instagram-feed' ); ?></label><code class="sbi_shortcode"> showbutton
							Eg: showbutton=false</code></th>
					<td>
						<input type="checkbox" name="sb_instagram_show_btn" id="sb_instagram_show_btn"
							<?php
							if ( $sb_instagram_show_btn ) {
								echo 'checked="checked"'; }
							?>
						/>
					</td>
				</tr>
				</tbody>
			</table>

			<p class="submit sbi-expand-button">
				<a href="javascript:void(0);" class="button">Show Customization Options</a>
			</p>

			<table class="form-table sbi-expandable-options" aria-describedby="Button settings">
				<tbody>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Button Background Color', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> buttoncolor
							Eg: buttoncolor=8224e3</code></th>
					<td>
						<input name="sb_instagram_btn_background" type="text" value="<?php echo esc_attr( $sb_instagram_btn_background ); ?>" class="sbi_colorpick" />
					</td>
				</tr>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Button Text Color', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> buttontextcolor
							Eg: buttontextcolor=eeee22</code></th>
					<td>
						<input name="sb_instagram_btn_text_color" type="text" value="<?php echo esc_attr( $sb_instagram_btn_text_color ); ?>" class="sbi_colorpick" />
					</td>
				</tr>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Button Text', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> buttontext
							Eg: buttontext="Show more.."</code></th>
					<td>
						<input name="sb_instagram_btn_text" type="text" value="<?php echo esc_attr( $sb_instagram_btn_text ); ?>" size="30" />
					</td>
				</tr>
				<tr >
					<th scope="row" class="bump-left"><label class="bump-left"><?php esc_html_e( 'Autoload more posts on scroll', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> autoscroll
							Eg: autoscroll=true</code></th>
					<td>
						<input name="sb_instagram_autoscroll" type="checkbox" id="sb_instagram_autoscroll"
							<?php
							if ( $sb_instagram_autoscroll ) {
								echo 'checked'; }
							?>
						/>
						<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What is this?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( 'This will make every Instagram feed load more posts as the user gets to the bottom of the feed. To enable this on only a specific feed use the autoscroll=true shortcode option.', 'instagram-feed' ); ?></p>
					</td>
				</tr>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Scroll Trigger Distance', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> autoscrolldistance
							Eg: autoscrolldistance=200</code></th>
					<td>
						<input name="sb_instagram_autoscrolldistance" type="text" value="<?php echo esc_attr( $sb_instagram_autoscrolldistance ); ?>" size="30" />
						<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What is this?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( 'This is the distance in pixels from the bottom of the page the user must scroll to to trigger the loading of more posts.', 'instagram-feed' ); ?></p>
					</td>
				</tr>


				</tbody>
			</table>

			<hr id="follow" />
			<h3><?php esc_html_e( "'Follow' Button", 'instagram-feed' ); ?></h3>
			<table class="form-table" aria-describedby="Follow button settings">
				<tbody>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Show the Follow button', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> showfollow
							Eg: showfollow=true</code></th>
					<td>
						<input type="checkbox" name="sb_instagram_show_follow_btn" id="sb_instagram_show_follow_btn"
							<?php
							if ( $sb_instagram_show_follow_btn ) {
								echo 'checked="checked"'; }
							?>
						/>
					</td>
				</tr>
				</tbody>
			</table>

			<p class="submit sbi-expand-button">
				<a href="javascript:void(0);" class="button">Show Customization Options</a>
			</p>

			<table class="form-table sbi-expandable-options" aria-describedby="Follow button settings">
				<tbody>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Button Background Color', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> followcolor
							Eg: followcolor=28a1bf</code></th>
					<td>
						<input name="sb_instagram_folow_btn_background" type="text" value="<?php echo esc_attr( $sb_instagram_folow_btn_background ); ?>" class="sbi_colorpick" />
					</td>
				</tr>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Button Text Color', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> followtextcolor
							Eg: followtextcolor=000</code></th>
					<td>
						<input name="sb_instagram_follow_btn_text_color" type="text" value="<?php echo esc_attr( $sb_instagram_follow_btn_text_color ); ?>" class="sbi_colorpick" />
					</td>
				</tr>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Button Text', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> followtext
							Eg: followtext="Follow me"</code></th>
					<td>
						<input name="sb_instagram_follow_btn_text" type="text" value="<?php echo esc_attr( $sb_instagram_follow_btn_text ); ?>" size="30" />
					</td>
				</tr>
				</tbody>
			</table>

			<?php submit_button(); ?>


		<?php } //End Customize General tab ?>

		<?php if ( $sbi_active_tab === 'customize-posts' ) { //Start Customize Posts tab ?>

			<p class="sb_instagram_contents_links" id="general">
				<span><?php esc_html_e( 'Jump to:', 'instagram-feed' ); ?> </span>
				<a href="#photos"><?php esc_html_e( 'Photos', 'instagram-feed' ); ?></a>
				<a href="#hover"><?php esc_html_e( 'Photo Hover Style', 'instagram-feed' ); ?></a>
				<a href="#caption"><?php esc_html_e( 'Caption', 'instagram-feed' ); ?></a>
				<a href="#likes"><?php esc_html_e( 'Likes &amp; Comments Icons', 'instagram-feed' ); ?></a>
				<a href="#comments"><?php esc_html_e( 'Lightbox Comments', 'instagram-feed' ); ?></a>
			</p>

			<input type="hidden" name="<?php echo $sb_instagram_customize_posts_hidden_field; ?>" value="Y">

			<hr id="photos" />
			<h3><?php esc_html_e( 'Photos', 'instagram-feed' ); ?></h3>

			<table class="form-table" aria-describedby="Sorting settings">
				<tbody>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Sort Photos By', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> sortby
							Eg: sortby=random</code></th>
					<td>
						<select name="sb_instagram_sort" class="sb_instagram_sort">
							<option value="none"
								<?php
								if ( $sb_instagram_sort === 'none' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( 'Newest to oldest', 'instagram-feed' ); ?></option>
							<option value="random"
								<?php
								if ( $sb_instagram_sort === 'random' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( 'Random', 'instagram-feed' ); ?></option>
							<option value="likes"
								<?php
								if ( $sb_instagram_sort === 'likes' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( 'Likes*', 'instagram-feed' ); ?></option>
						</select>
						<div class="sbi_likes_explain sb_instagram_box">
							<p>
								*<?php esc_html_e( 'Sorting by likes is available for business accounts only. Feed will use up to the most recent 200 posts. It\'s recommended that you use background caching (setting found on the Configure tab) to prevent slow feed load times.', 'instagram-feed' ); ?>
							</p>
						</div>
					</td>
				</tr>

				<tr >
					<th scope="row"><label><?php esc_html_e( 'Image Resolution', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> imageres
							Eg: imageres=medium</code></th>
					<td>
						<select id="sb_standard_res_settings" name="sb_instagram_image_res">
							<option value="auto"
								<?php
								if ( $sb_instagram_image_res === 'auto' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( 'Auto-detect (recommended)', 'instagram-feed' ); ?></option>
							<option value="thumb"
								<?php
								if ( $sb_instagram_image_res === 'thumb' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( 'Thumbnail (150x150)', 'instagram-feed' ); ?></option>
							<option value="medium"
								<?php
								if ( $sb_instagram_image_res === 'medium' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( 'Medium (320x320)', 'instagram-feed' ); ?></option>
							<option value="full"
								<?php
								if ( $sb_instagram_image_res === 'full' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( 'Full size (640x640)', 'instagram-feed' ); ?></option>
						</select>

						&nbsp<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What does Auto-detect mean?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( 'Auto-detect means that the plugin automatically sets the image resolution based on the size of your feed.', 'instagram-feed' ); ?></p>
					</td>
				</tr>

				<tr >
					<th scope="row"><label><?php esc_html_e( 'Media Type to Display', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> media
							Eg: media=photos
							media=videos
							media=all</code></th>
					<td>
						<select name="sb_instagram_media_type">
							<option value="all"
								<?php
								if ( $sb_instagram_media_type === 'all' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( 'All', 'instagram-feed' ); ?></option>
							<option value="photos"
								<?php
								if ( $sb_instagram_media_type === 'photos' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( 'Photos only', 'instagram-feed' ); ?></option>
							<option value="videos"
								<?php
								if ( $sb_instagram_media_type === 'videos' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( 'Videos only', 'instagram-feed' ); ?></option>
						</select>
						<div class="sbi_video_type_checkboxes">
							<?php
							$regular_checked = strpos( $videotypes, 'regular' ) !== false ? ' checked' : '';
							$igtv_checked    = strpos( $videotypes, 'igtv' ) !== false ? ' checked' : '';
							?>
							<div class="sbi_video_type_wrap">
								<input type="checkbox" name="videotypes[]" id="sb_instagram_videotypes_regular" value="regular"<?php echo esc_attr( $regular_checked ); ?>/><label for="sb_instagram_videotypes_regular"><?php esc_html_e( 'Regular videos', 'instagram-feed' ); ?></label>
							</div>
							<div class="sbi_video_type_wrap">
								<input type="checkbox" name="videotypes[]" id="sb_instagram_videotypes_igtv" value="igtv"<?php echo esc_attr( $igtv_checked ); ?>/><label for="sb_instagram_videotypes_igtv"><?php esc_html_e( 'IGTV videos', 'instagram-feed' ); ?></label>
							</div>
						</div>
					</td>
				</tr>

				<tr >
					<th scope="row"><label><?php esc_html_e( 'Disable Pop-up Lightbox', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> disablelightbox
							Eg: disablelightbox=true</code></th>
					<td>
						<input type="checkbox" name="sb_instagram_disable_lightbox" id="sb_instagram_disable_lightbox"
							<?php
							if ( $sb_instagram_disable_lightbox ) {
								echo 'checked="checked"'; }
							?>
						/>
					</td>
				</tr>

				<tr >
					<th scope="row"><label><?php esc_html_e( 'Link Posts to URL in Caption (Shoppable feed)', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> captionlinks
							Eg: captionlinks=true</code></th>
					<td>
						<input type="checkbox" name="sb_instagram_captionlinks" id="sb_instagram_captionlinks"
							<?php
							if ( $sb_instagram_captionlinks ) {
								echo 'checked="checked"'; }
							?>
						/>
						&nbsp;<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What will this do?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php echo wp_kses_post( sprintf( __( 'Checking this box will change the link for each post to any url included in the caption for that Instagram post. The lightbox will be disabled. Visit %s to learn how this works.', 'instagram-feed' ), '<a href="https://smashballoon.com/make-a-shoppable-feed">' . __( 'this link', 'instagram-feed' ) . '</a>' ) ); ?></p>
					</td>
				</tr>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Post Offset', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> offset
							Eg: offset=1</code></th>
					<td>
						<input name="sb_instagram_offset" id="sb_instagram_offset" type="text" value="<?php echo esc_attr( $sb_instagram_offset ); ?>" size="4" />Posts
						&nbsp;<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What is this?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( 'Feed will "skip" this many posts or offset the start of your posts by this number.', 'instagram-feed' ); ?></p>
					</td>
				</tr>
				</tbody>
			</table>

			<hr id="hover" />
			<h3><?php esc_html_e( 'Photo Hover Style', 'instagram-feed' ); ?></h3>

			<table class="form-table" aria-describedby="Photo hover settings">
				<tbody>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Hover Background Color', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> hovercolor
							Eg: hovercolor=1e73be</code></th>
					<td>
						<input name="sb_hover_background" type="text" value="<?php echo esc_attr( $sb_hover_background ); ?>" class="sbi_colorpick" />
					</td>
				</tr>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Hover Text Color', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> hovertextcolor
							Eg: hovertextcolor=fff</code></th>
					<td>
						<input name="sb_hover_text" type="text" value="<?php echo esc_attr( $sb_hover_text ); ?>" class="sbi_colorpick" />
					</td>
				</tr>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Information to display', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> hoverdisplay
							Eg: hoverdisplay='username,date'

							Options: username, date, instagra, caption, likes</code></th>
					<td>
						<div>
							<input name="sbi_hover_inc_username" type="checkbox" id="sbi_hover_inc_username"
								<?php
								if ( $sbi_hover_inc_username ) {
									echo 'checked'; }
								?>
							/>
							<label for="sbi_hover_inc_username"><?php esc_html_e( 'Username', 'instagram-feed' ); ?>&nbsp;<span style="font-size: 12px;">(<?php esc_html_e( 'User feeds only', 'instagram-feed' ); ?>)</span></label>
						</div>
						<div>
							<input name="sbi_hover_inc_date" type="checkbox" id="sbi_hover_inc_date"
								<?php
								if ( $sbi_hover_inc_date ) {
									echo 'checked'; }
								?>
							/>
							<label for="sbi_hover_inc_date"><?php esc_html_e( 'Date', 'instagram-feed' ); ?>&nbsp;<span style="font-size: 12px;">(<?php esc_html_e( 'User feeds only', 'instagram-feed' ); ?>)</span></label>
						</div>
						<div>
							<input name="sbi_hover_inc_instagram" type="checkbox" id="sbi_hover_inc_instagram"
								<?php
								if ( $sbi_hover_inc_instagram ) {
									echo 'checked'; }
								?>
							/>
							<label for="sbi_hover_inc_instagram"><?php esc_html_e( 'Instagram Icon/Link', 'instagram-feed' ); ?></label>
						</div>
						<div>
							<input name="sbi_hover_inc_caption" type="checkbox" id="sbi_hover_inc_caption"
								<?php
								if ( $sbi_hover_inc_caption ) {
									echo 'checked'; }
								?>
							/>
							<label for="sbi_hover_inc_caption"><?php esc_html_e( 'Caption', 'instagram-feed' ); ?></label>
						</div>
						<div>
							<input name="sbi_hover_inc_likes" type="checkbox" id="sbi_hover_inc_likes"
								<?php
								if ( $sbi_hover_inc_likes ) {
									echo 'checked'; }
								?>
							/>
							<label for="sbi_hover_inc_likes"><?php esc_html_e( 'Like/Comment Icons', 'instagram-feed' ); ?>&nbsp;<span style="font-size: 12px;">(<?php esc_html_e( 'Business accounts only.', 'instagram-feed' ); ?> <a class="sbi_tooltip_link" href="JavaScript:void(0);" style="font-size: 12px; margin: 0;"><?php esc_html_e( 'Why?', 'instagram-feed' ); ?></a>)<span class="sbi_tooltip"><?php echo wp_kses_post( sprintf( __( 'Instagram is deprecating their old API for Personal accounts on June 1, 2020. The plugin supports their new API, however, some features (such as this one) are not yet available in their new API. If you require this feature then it is available if you convert your Instagram account from a Personal to a Business account by following %s.', 'instagram-feed' ), '<a href="https://smashballoon.com/instagram-business-profiles/" target="_blank">these directions</a>' ) ); ?></span></span></label>
						</div>
					</td>
				</tr>

				</tbody>
			</table>

			<?php submit_button(); ?>

			<hr id="caption" />
			<h3><?php esc_html_e( 'Caption', 'instagram-feed' ); ?></h3>

			<table class="form-table" aria-describedby="Caption settings">
				<tbody>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Show Caption', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> showcaption
							Eg: showcaption=false</code></th>
					<td>
						<input type="checkbox" name="sb_instagram_show_caption" id="sb_instagram_show_caption"
							<?php
							if ( $sb_instagram_show_caption ) {
								echo 'checked="checked"'; }
							?>
						/>
					</td>
				</tr>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Maximum Text Length', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> captionlength
							Eg: captionlength=20</code></th>
					<td>
						<input name="sb_instagram_caption_length" id="sb_instagram_caption_length" type="text" value="<?php echo esc_attr( $sb_instagram_caption_length ); ?>" size="4" />Characters
						&nbsp;<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What is this?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( 'The number of characters of text to display in the caption. An elipsis link will be added to allow the user to reveal more text if desired.', 'instagram-feed' ); ?></p>
					</td>
				</tr>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Text Color', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> captioncolor
							Eg: captioncolor=dd3333</code></th>
					<td>
						<input name="sb_instagram_caption_color" type="text" value="<?php echo esc_attr( $sb_instagram_caption_color ); ?>" class="sbi_colorpick" />
					</td>
				</tr>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Text Size', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> captionsize
							Eg: captionsize=12</code></th>
					<td>
						<select name="sb_instagram_caption_size" style="width: 180px;">
							<option value="inherit"
								<?php
								if ( $sb_instagram_caption_size === 'inherit' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( 'Inherit from theme', 'instagram-feed' ); ?></option>
							<option value="10"
								<?php
								if ( $sb_instagram_caption_size === '10' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '10px' ); ?></option>
							<option value="11"
								<?php
								if ( $sb_instagram_caption_size === '11' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '11px' ); ?></option>
							<option value="12"
								<?php
								if ( $sb_instagram_caption_size === '12' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '12px' ); ?></option>
							<option value="13"
								<?php
								if ( $sb_instagram_caption_size === '13' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '13px' ); ?></option>
							<option value="14"
								<?php
								if ( $sb_instagram_caption_size === '14' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '14px' ); ?></option>
							<option value="16"
								<?php
								if ( $sb_instagram_caption_size === '16' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '16px' ); ?></option>
							<option value="18"
								<?php
								if ( $sb_instagram_caption_size === '18' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '18px' ); ?></option>
							<option value="20"
								<?php
								if ( $sb_instagram_caption_size === '20' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '20px' ); ?></option>
							<option value="24"
								<?php
								if ( $sb_instagram_caption_size === '24' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '24px' ); ?></option>
							<option value="28"
								<?php
								if ( $sb_instagram_caption_size === '28' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '28px' ); ?></option>
							<option value="32"
								<?php
								if ( $sb_instagram_caption_size === '32' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '32px' ); ?></option>
							<option value="36"
								<?php
								if ( $sb_instagram_caption_size === '36' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '36px' ); ?></option>
							<option value="40"
								<?php
								if ( $sb_instagram_caption_size === '40' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '40px' ); ?></option>
						</select>
					</td>
				</tr>
				</tbody>
			</table>

			<hr id="likes" />
			<h3><?php esc_html_e( 'Likes &amp; Comments Icons', 'instagram-feed' ); ?></h3>

			<table class="form-table" aria-describedby="Comments settings">
				<tbody>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Show Icons', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> showlikes
							Eg: showlikes=false</code></th>
					<td>
						<input type="checkbox" name="sb_instagram_show_meta" id="sb_instagram_show_meta"
							<?php
							if ( $sb_instagram_show_meta ) {
								echo 'checked="checked"'; }
							?>
						/>
						<span class="sbi_note"><?php esc_html_e( 'Only available for Business accounts', 'instagram-feed' ); ?></span>
						<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'Why?', 'instagram-feed' ); ?></a>

						<p class="sbi_tooltip"><?php echo wp_kses_post( sprintf( __( 'Instagram is deprecating their old API for Personal accounts on June 1, 2020. The plugin supports their new API, however, some features (such as this one) are not yet available in their new API. If you require this feature then it is available if you convert your Instagram account from a Personal to a Business account by following %s.', 'instagram-feed' ), '<a href="https://smashballoon.com/instagram-business-profiles/" target="_blank">these directions</a>' ) ); ?></p>
					</td>
				</tr>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Icon Color', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> likescolor
							Eg: likescolor=fff</code></th>
					<td>
						<input name="sb_instagram_meta_color" type="text" value="<?php echo esc_attr( $sb_instagram_meta_color ); ?>" class="sbi_colorpick" />
					</td>
				</tr>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Icon Size', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> likessize
							Eg: likessize=14</code></th>
					<td>
						<select name="sb_instagram_meta_size" style="width: 180px;">
							<option value="inherit"
								<?php
								if ( $sb_instagram_meta_size === 'inherit' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( 'Inherit from theme', 'instagram-feed' ); ?></option>
							<option value="10"
								<?php
								if ( $sb_instagram_meta_size === '10' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '10px' ); ?></option>
							<option value="11"
								<?php
								if ( $sb_instagram_meta_size === '11' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '11px' ); ?></option>
							<option value="12"
								<?php
								if ( $sb_instagram_meta_size === '12' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '12px' ); ?></option>
							<option value="13"
								<?php
								if ( $sb_instagram_meta_size === '13' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '13px' ); ?></option>
							<option value="14"
								<?php
								if ( $sb_instagram_meta_size === '14' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '14px' ); ?></option>
							<option value="16"
								<?php
								if ( $sb_instagram_meta_size === '16' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '16px' ); ?></option>
							<option value="18"
								<?php
								if ( $sb_instagram_meta_size === '18' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '18px' ); ?></option>
							<option value="20"
								<?php
								if ( $sb_instagram_meta_size === '20' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '20px' ); ?></option>
							<option value="24"
								<?php
								if ( $sb_instagram_meta_size === '24' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '24px' ); ?></option>
							<option value="28"
								<?php
								if ( $sb_instagram_meta_size === '28' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '28px' ); ?></option>
							<option value="32"
								<?php
								if ( $sb_instagram_meta_size === '32' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '32px' ); ?></option>
							<option value="36"
								<?php
								if ( $sb_instagram_meta_size === '36' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '36px' ); ?></option>
							<option value="40"
								<?php
								if ( $sb_instagram_meta_size === '40' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( '40px' ); ?></option>
						</select>
					</td>
				</tr>
				</tbody>
			</table>

			<hr id="comments" />
			<h3><?php esc_html_e( 'Lightbox Comments', 'instagram-feed' ); ?></h3>
			<div>
				<p><span style="margin: -10px 0 0 0; font-style: italic; font-size: 12px;"><?php esc_html_e( 'Comments available for User feeds from Business accounts only', 'instagram-feed' ); ?></span>
					<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'Why?', 'instagram-feed' ); ?></a>

					<span class="sbi_tooltip"><?php echo wp_kses_post( sprintf( __( 'Instagram is deprecating their old API for Personal accounts on June 1, 2020. The plugin supports their new API, however, some features (such as this one) are not yet available in their new API. If you require this feature then it is available if you convert your Instagram account from a Personal to a Business account by following %s.', 'instagram-feed' ), '<a href="https://smashballoon.com/instagram-business-profiles/" target="_blank">these directions</a>' ) ); ?></span>
				</p>
			</div>

			<table class="form-table" aria-describedby="Lightbox settings">
				<tbody>

				<tr >
					<th scope="row"><label><?php esc_html_e( 'Show Comments in Lightbox', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> lightboxcomments
							Eg: lightboxcomments="true"</code></th>
					<td style="padding: 5px 10px 0 10px;">
						<input type="checkbox" name="sb_instagram_lightbox_comments" id="sb_instagram_lightbox_comments"
							<?php
							if ( $sb_instagram_lightbox_comments ) {
								echo 'checked="checked"'; }
							?>
							   style="margin-right: 15px;" />
						<input id="sbi_clear_comment_cache" class="button-secondary" style="margin-top: -5px;" type="submit" value="<?php esc_attr_e( 'Clear Comment Cache' ); ?>" />
						&nbsp<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What is this?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( 'This will remove the cached comments saved in the database', 'instagram-feed' ); ?></p>
					</td>
				</tr>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Number of Comments', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> numcomments
							Eg: numcomments="10"</code></th>
					<td>
						<input name="sb_instagram_num_comments" type="text" value="<?php echo esc_attr( $sb_instagram_num_comments ); ?>" size="4" />
						<span class="sbi_note"><?php esc_html_e( 'Max number of latest comments.', 'instagram-feed' ); ?></span>
						&nbsp<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What is this?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( 'This is the maximum number of comments that will be shown in the lightbox. If there are more comments available than the number set, only the latest comments will be shown', 'instagram-feed' ); ?></p>
					</td>
				</tr>

				</tbody>
			</table>

			<?php submit_button(); ?>

		<?php } //End Customize Posts tab ?>

		<?php if ( $sbi_active_tab === 'customize-moderation' ) { //Start Customize Moderation tab ?>

			<p class="sb_instagram_contents_links" id="general">
				<span><?php esc_html_e( 'Jump to:', 'instagram-feed' ); ?> </span>
				<a href="#filtering"><?php esc_html_e( 'Post Filtering', 'instagram-feed' ); ?></a>
				<a href="#moderation"><?php esc_html_e( 'Moderation', 'instagram-feed' ); ?></a>
			</p>

			<input type="hidden" name="<?php echo $sb_instagram_customize_moderation_hidden_field; ?>" value="Y">

			<hr id="filtering" />
			<h3><?php esc_html_e( 'Post Filtering', 'instagram-feed' ); ?></h3>
			<table class="form-table" aria-describedby="Filtering settings">
				<tbody>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Remove photos containing these words or hashtags', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> excludewords
							Eg: excludewords="naughty, words"</code></th>
					<td>
						<div class="sb_instagram_apply_labels">
							<p>Apply to:</p>
							<input name="sb_instagram_ex_apply_to" id="sb_instagram_ex_all" class="sb_instagram_incex_one_all" type="radio" value="all"
								<?php
								if ( $sb_instagram_ex_apply_to === 'all' ) {
									echo 'checked'; }
								?>
							/><label for="sb_instagram_ex_all">All feeds</label>
							<input name="sb_instagram_ex_apply_to" id="sb_instagram_ex_one" class="sb_instagram_incex_one_all" type="radio" value="one"
								<?php
								if ( $sb_instagram_ex_apply_to === 'one' ) {
									echo 'checked'; }
								?>
							/><label for="sb_instagram_ex_one">One feed</label>
						</div>

						<input name="sb_instagram_exclude_words" id="sb_instagram_exclude_words" type="text" style="width: 70%;" value="<?php echo esc_attr( $sb_instagram_exclude_words ); ?>" />
						<p class="sbi_extra_info sbi_incex_shortcode"
							<?php
							if ( $sb_instagram_ex_apply_to === 'one' ) {
								echo 'style="display:block;"';}
							?>
						><?php esc_html_e( 'Add this to the shortcode for your feed', 'instagram-feed' ); ?> <code></code></p>

						<br />
						<span class="sbi_note" style="margin-left: 0;"><?php esc_html_e( 'Separate words/hashtags using commas', 'instagram-feed' ); ?></span>

						&nbsp;<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What is this?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( 'You can use this setting to remove photos which contain certain words or hashtags in the caption. Separate multiple words or hashtags using commas.', 'instagram-feed' ); ?></p>

					</td>
				</tr>

				<tr >
					<th scope="row"><label><?php esc_html_e( 'Show photos containing these words or hashtags', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> includewords
							Eg: includewords="sunshine"</code></th>
					<td>
						<div class="sb_instagram_apply_labels">
							<p>Apply to:</p>
							<input name="sb_instagram_inc_apply_to" id="sb_instagram_inc_all" class="sb_instagram_incex_one_all" type="radio" value="all"
								<?php
								if ( $sb_instagram_inc_apply_to === 'all' ) {
									echo 'checked'; }
								?>
							/><label for="sb_instagram_inc_all">All feeds</label>
							<input name="sb_instagram_inc_apply_to" id="sb_instagram_inc_one" class="sb_instagram_incex_one_all" type="radio" value="one"
								<?php
								if ( $sb_instagram_inc_apply_to === 'one' ) {
									echo 'checked'; }
								?>
							/><label for="sb_instagram_inc_one">One feed</label>
						</div>

						<input name="sb_instagram_include_words" id="sb_instagram_include_words" type="text" style="width: 70%;" value="<?php echo esc_attr( $sb_instagram_include_words ); ?>" />
						<p class="sbi_extra_info sbi_incex_shortcode"
							<?php
							if ( $sb_instagram_ex_apply_to === 'one' ) {
								echo 'style="display:block;"'; }
							?>
						><?php esc_html_e( 'Add this to the shortcode for your feed', 'instagram-feed' ); ?> <code></code></p>

						<br />
						<span class="sbi_note" style="margin-left: 0;"><?php esc_html_e( 'Separate words/hashtags using commas', 'instagram-feed' ); ?></span>

						<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What is this?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php echo wp_kses_post( sprintf( __( 'You can use this setting to only show photos which contain certain words or hashtags in the caption. For example, adding %s will show any photos which contain either the word sheep, cow, or dog. Separate multiple words or hashtags using commas.', 'instagram-feed' ), '<code>' . __( 'sheep, cow, dog', 'instagram-feed' ) . '</code>' ) ); ?></p>

					</td>
				</tr>
				</tbody>
			</table>

			<div>
				<a class="sbi_tooltip_link" href="JavaScript:void(0);" style="margin-left: 0;"><?php esc_html_e( 'Can I filter words or hashtags from comments?', 'instagram-feed' ); ?></a>
				<div class="sbi_tooltip" style="width: 100%;"><?php esc_html_e( 'Instagram is deprecating their old API for Personal accounts on June 1, 2020. The plugin supports their new API, however, some features (such as this one) are not yet available in their new API. For this reason, it is now only possible to filter posts by the caption and not the comments.', 'instagram-feed' ); ?></div>
			</div>

			<hr id="moderation" />
			<h3><?php esc_html_e( 'Moderation', 'instagram-feed' ); ?></h3>
			<table class="form-table" aria-describedby="Moderation settings">
				<tbody>
				<tr >
					<th scope="row"><label><?php esc_html_e( 'Moderation Type', 'instagram-feed' ); ?></label></th>
					<td>
						<input name="sb_instagram_moderation_mode" id="sb_instagram_moderation_mode_visual" class="sb_instagram_moderation_mode" type="radio" value="visual"
							<?php
							if ( $sb_instagram_moderation_mode === 'visual' ) {
								echo 'checked'; }
							?>
							   style="margin-top: 0;" /><label for="sb_instagram_moderation_mode_visual">Visual</label>
						<input name="sb_instagram_moderation_mode" id="sb_instagram_moderation_mode_manual" class="sb_instagram_moderation_mode" type="radio" value="manual"
							<?php
							if ( $sb_instagram_moderation_mode === 'manual' ) {
								echo 'checked'; }
							?>
							   style="margin-top: 0; margin-left: 10px;"/><label for="sb_instagram_moderation_mode_manual">Manual</label>

						<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What is this?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><strong><?php esc_html_e( 'Visual Moderation Mode', 'instagram-feed' ); ?></strong><br /><?php echo wp_kses_post( sprintf( __( 'This adds a button to each feed that will allow you to hide posts, and create white lists from the front end using a visual interface. Visit %s for details', 'instagram-feed' ), '<a href="https://smashballoon.com/guide-to-moderation-mode/" target="_blank">' . __( 'this page', 'instagram-feed' ) . '</a>' ) ); ?></p>

						<br />
						<div class="sbi_mod_manual_settings">

							<div class="sbi_row">
								<label><?php esc_html_e( 'Hide specific photos', 'instagram-feed' ); ?></label>
								<textarea name="sb_instagram_hide_photos" id="sb_instagram_hide_photos" style="width: 100%;" rows="3"><?php echo esc_attr( $sb_instagram_hide_photos ); ?></textarea>
								<br />
								<span class="sbi_note" style="margin-left: 0;"><?php esc_html_e( 'Separate IDs using commas', 'instagram-feed' ); ?></span>

								&nbsp;<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What is this?', 'instagram-feed' ); ?></a>
								<p class="sbi_tooltip"><?php esc_html_e( "You can use this setting to hide specific photos in your feed. Just click the 'Hide Photo' link in the photo pop-up in your feed to get the ID of the photo, then copy and paste it into this text box.", 'instagram-feed' ); ?></p>
							</div>
							<?php if ( ! empty( $sb_instagram_block_users ) ) : ?>
								<div class="sbi_row">
									<label><?php esc_html_e( 'Block users', 'instagram-feed' ); ?></label>
									<input name="sb_instagram_block_users" id="sb_instagram_block_users" type="text" style="width: 100%;" value="<?php echo esc_attr( $sb_instagram_block_users ); ?>" />

									<br />
									<span class="sbi_note" style="margin-left: 0;"><?php esc_html_e( 'Separate usernames using commas', 'instagram-feed' ); ?></span>

									&nbsp;<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What is this?', 'instagram-feed' ); ?></a>
									<p class="sbi_tooltip"><?php esc_html_e( 'You can use this setting to block photos from certain users in your feed. Just enter the usernames here which you want to block. Separate multiple usernames using commas.', 'instagram-feed' ); ?></p>
								</div>
							<?php endif; ?>

						</div>

					</td>
				</tr>
				<?php if ( ! empty( $sb_instagram_show_users ) ) : ?>

					<tr >
						<th scope="row"><label><?php esc_html_e( 'Only show posts by these users', 'instagram-feed' ); ?></label></th>
						<td>

							<input name="sb_instagram_show_users" id="sb_instagram_show_users" type="text" style="width: 70%;" value="<?php echo esc_attr( $sb_instagram_show_users ); ?>" />

							<br />
							<span class="sbi_note" style="margin-left: 0;"><?php esc_html_e( 'Separate usernames using commas', 'instagram-feed' ); ?></span>

							&nbsp<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What is this?', 'instagram-feed' ); ?></a>
							<p class="sbi_tooltip"><?php esc_html_e( 'You can use this setting to show photos only from certain users in your feed. Just enter the usernames here which you want to show. Separate multiple usernames using commas.', 'instagram-feed' ); ?></p>

						</td>
					</tr>
				<?php endif; ?>

				<tr >
					<th scope="row"><label><?php esc_html_e( 'White lists', 'instagram-feed' ); ?></label></th>
					<td>
						<div class="sbi_white_list_names_wrapper">
							<?php
							$sbi_current_white_names = get_option( 'sb_instagram_white_list_names', array() );

							if ( empty( $sbi_current_white_names ) ) {
								esc_html_e( 'No white lists currently created', 'instagram-feed' );
							} else {
								$sbi_white_size = count( $sbi_current_white_names );
								$sbi_i          = 1;
								echo 'IDs: ';
								foreach ( $sbi_current_white_names as $white ) {
									if ( $sbi_i !== $sbi_white_size ) {
										echo '<span>' . esc_html( $white ) . ', </span>';
									} else {
										echo '<span>' . esc_html( $white ) . '</span>';
									}
									$sbi_i++;
								}
								echo '<br />';
							}
							?>
						</div>

						<input id="sbi_clear_white_lists" class="button-secondary" type="submit" value="<?php esc_attr_e( 'Clear White Lists' ); ?>" />
						&nbsp;<a class="sbi_tooltip_link" href="JavaScript:void(0);" style="display: inline-block; margin-top: 5px;"><?php esc_html_e( 'What is this?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( 'This will remove all of the white lists from the database', 'instagram-feed' ); ?></p>

						<?php
						$permanent_white_lists = get_option( 'sb_permanent_white_lists', array() );
						if ( ! empty( $permanent_white_lists ) && ! empty( $sbi_current_white_names ) ) {
							$sbi_white_size = count( $permanent_white_lists );
							$sbi_i          = 1;
							echo '<div class="sbi_white_list_names_wrapper sbi_white_list_perm">';
							echo 'Permanent: ';
							foreach ( $permanent_white_lists as $white ) {
								if ( $sbi_i !== $sbi_white_size ) {
									echo '<span>' . esc_html( $white ) . ', </span>';
								} else {
									echo '<span style="margin-right: 10px;">' . esc_html( $white ) . '</span>';
								}
								$sbi_i++;
							}
							echo '<input id="sbi_clear_permanent_white_lists" class="button-secondary" type="submit" value="' . esc_attr__( 'Disable Permanent White Lists' ) . '" style="vertical-align: middle;"/>';
							echo '</div>';
						}
						?>
					</td>
				</tr>

				</tbody>
			</table>

			<?php submit_button(); ?>

		<?php } //End Customize Moderation tab ?>

		<?php if ( $sbi_active_tab === 'customize-advanced' ) { //Start Customize Advanced tab ?>

			<p class="sb_instagram_contents_links" id="general">
				<span><?php esc_html_e( 'Jump to:', 'instagram-feed' ); ?> </span>
				<a href="#snippets"><?php esc_html_e( 'Custom Code', 'instagram-feed' ); ?></a>
				<a href="#misc"><?php esc_html_e( 'Misc', 'instagram-feed' ); ?></a>
			</p>

			<input type="hidden" name="<?php echo $sb_instagram_customize_advanced_hidden_field; ?>" value="Y">

			<hr id="snippets" />
			<h3><?php esc_html_e( 'Custom Code Snippets', 'instagram-feed' ); ?></h3>

			<table class="form-table" aria-describedby="Custom code settings">
				<tbody>
				<tr>
					<th scope="row"></th>
				</tr>
				<tr >
					<td style="padding-bottom: 0;">
						<strong style="font-size: 15px;"><?php esc_html_e( 'Custom CSS', 'instagram-feed' ); ?></strong><br />
						<?php esc_html_e( 'Enter your own custom CSS in the box below', 'instagram-feed' ); ?>
					</td>
				</tr>
				<tr >
					<td>
						<textarea name="sb_instagram_custom_css" id="sb_instagram_custom_css" style="width: 70%;" rows="7"><?php echo esc_textarea( wp_unslash( $sb_instagram_custom_css ) ); ?></textarea>
					</td>
				</tr>
				<tr >
					<td style="padding-bottom: 0;">
						<strong style="font-size: 15px;"><?php esc_html_e( 'Custom JavaScript', 'instagram-feed' ); ?></strong><br />
						<?php esc_html_e( 'Enter your own custom JavaScript/jQuery in the box below', 'instagram-feed' ); ?>
					</td>
				</tr>
				<tr >
					<td>
						<textarea name="sb_instagram_custom_js" id="sb_instagram_custom_js" style="width: 70%;" rows="7"><?php echo esc_textarea( wp_unslash( $sb_instagram_custom_js ) ); ?></textarea>
						<br /><span class="sbi_note" style="margin: 5px 0 0 2px; display: block;"><strong><?php esc_html_e( 'Note:', 'instagram-feed' ); ?></strong> <?php esc_html_e( 'Custom JavaScript reruns every time more posts are loaded into the feed', 'instagram-feed' ); ?></span>
					</td>
				</tr>
				</tbody>
			</table>
			<?php submit_button(); ?>

			<hr id="gdpr" />
			<h3><?php esc_html_e( 'GDPR', 'instagram-feed' ); ?></h3>

			<table class="form-table" aria-describedby="GDPR settings">
				<tbody>
				<tr>
					<th scope="row" class="bump-left"><label class="bump-left"><?php esc_html_e( 'Enable GDPR settings', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> gdpr
							Eg: gdpr=yes</code></th>
					<td>

						<?php
						$select_options = array(
							array(
								'label' => __( 'Automatic', 'instagram-feed' ),
								'value' => 'auto',
							),
							array(
								'label' => __( 'Yes', 'instagram-feed' ),
								'value' => 'yes',
							),
							array(
								'label' => __( 'No', 'instagram-feed' ),
								'value' => 'no',
							),
						)
						?>
						<?php
						$gdpr_list = "<ul class='sbi-list'>
                            	<li>" . __( 'Only local images (not from Instagram\'s CDN) will be displayed in the feed.', 'instagram-feed' ) . '</li>
                            	<li>' . __( 'Placeholder blank images will be displayed until images are available.', 'instagram-feed' ) . '</li>
                            	<li>' . __( 'To view videos, visitors will click a link to view the video on Instagram.', 'instagram-feed' ) . '</li>
                                <li>' . __( 'Carousel posts will only show the first image in the lightbox.', 'instagram-feed' ) . '</li>
                                <li>' . __( 'The maximum image resolution will be 640 pixels wide in the lightbox.', 'instagram-feed' ) . '</li>
                            </ul>';
						?>
						<div>
							<select name="gdpr" id="sbi_gdpr_setting">
								<?php
								foreach ( $select_options as $select_option ) :
									$selected = $select_option['value'] === $gdpr ? ' selected' : '';
									?>
									<option value="<?php echo esc_attr( $select_option['value'] ); ?>"<?php echo esc_attr( $selected ); ?> ><?php echo esc_html( $select_option['label'] ); ?></option>
								<?php endforeach; ?>
							</select>
							<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What does this mean?', 'instagram-feed' ); ?></a>
							<div class="sbi_tooltip gdpr_tooltip">

								<p><span><?php esc_html_e( 'Yes', 'instagram-feed' ); ?>:</span> <?php esc_html_e( "Enabling this setting prevents all images and videos from being loaded directly from Instagram's servers (CDN) to prevent any requests to external websites in your browser. To accommodate this, some features of the plugin will be disabled or limited.", 'instagram-feed' ); ?> <a href="JavaScript:void(0);" class="sbi_show_gdpr_list"><?php esc_html_e( 'What will be limited?', 'instagram-feed' ); ?></a></p>

								<?php echo "<div class='sbi_gdpr_list'>" . wp_kses_post( $gdpr_list ) . '</div>'; ?>


								<p><span><?php esc_html_e( 'No', 'instagram-feed' ); ?>:</span> <?php esc_html_e( 'The plugin will still make some requests to load and display images and videos directly from Instagram.', 'instagram-feed' ); ?></p>


								<p><span><?php esc_html_e( 'Automatic', 'instagram-feed' ); ?>:</span> <?php echo wp_kses_post( sprintf( __( 'The plugin will only load images and videos directly from Instagram if consent has been given by one of these integrated %s', 'instagram-feed' ), '<a href="https://smashballoon.com/doc/gdpr-plugin-list/?instagram" target="_blank" rel="noopener">' . esc_html__( 'GDPR cookie plugins', 'instagram-feed' ) . '</a>' ) ); ?></p>

								<p><?php echo wp_kses_post( sprintf( __( '%s to learn more about GDPR compliance in the Instagram Feed plugin.', 'instagram-feed' ), '<a href="https://smashballoon.com/doc/instagram-feed-gdpr-compliance/?instagram" target="_blank" rel="noopener">' . __( 'Click here', 'instagram-feed' ) . '</a>' ) ); ?></p>
							</div>
						</div>

						<?php
						if ( ! SB_Instagram_GDPR_Integrations::gdpr_tests_successful( isset( $_GET['retest'] ) ) ) :
							$errors = SB_Instagram_GDPR_Integrations::gdpr_tests_error_message();
							?>
							<div class="sb_instagram_box sbi_gdpr_error">
								<div class="sb_instagram_box_setting">
									<p>
										<strong><?php esc_html_e( 'Error:', 'instagram-feed' ); ?></strong> <?php esc_html_e( 'Due to a configuration issue on your web server, the GDPR setting is unable to be enabled.', 'instagram-feed' ); ?></p>
									<p>
										<?php echo wp_kses_post( $errors ); ?>
									</p>
								</div>
							</div>
						<?php else : ?>

							<div class="sbi_gdpr_auto">
								<?php
								if ( SB_Instagram_GDPR_Integrations::gdpr_plugins_active() ) :
									$active_plugin = SB_Instagram_GDPR_Integrations::gdpr_plugins_active();
									?>
									<div class="sbi_gdpr_plugin_active">
										<div class="sbi_active">
											<p>
												<svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-check-circle fa-w-16 fa-2x"><path fill="currentColor" d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z" class=""></path></svg>
												<strong><?php echo wp_kses_post( sprintf( __( '%s detected', 'instagram-feed' ), $active_plugin ) ); ?></strong>
												<br />
												<?php esc_html_e( 'Some Instagram Feed features will be limited for visitors to ensure GDPR compliance until they give consent.', 'instagram-feed' ); ?>
												<a href="JavaScript:void(0);" class="sbi_show_gdpr_list"><?php esc_html_e( 'What will be limited?', 'instagram-feed' ); ?></a>
											</p>
											<?php echo "<div class='sbi_gdpr_list'>" . wp_kses_post( $gdpr_list ) . '</div>'; ?>
										</div>

									</div>
								<?php else : ?>
									<div class="sb_instagram_box">
										<div class="sb_instagram_box_setting">
											<p><?php echo wp_kses_post( 'No GDPR consent plugin detected. Install a compatible <a href="https://smashballoon.com/doc/gdpr-plugin-list/?instagram" target="_blank">GDPR consent plugin</a>, or manually enable the setting above to display a GDPR compliant version of the feed to all visitors.', 'instagram-feed' ); ?></p>
										</div>
									</div>
								<?php endif; ?>
							</div>

							<div class="sb_instagram_box sbi_gdpr_yes">
								<div class="sb_instagram_box_setting">
									<p><?php esc_html_e( 'No requests will be made to third-party websites. To accommodate this, some features of the plugin will be limited:', 'instagram-feed' ); ?></p>
									<?php echo wp_kses_post( $gdpr_list ); ?>
								</div>
							</div>

							<div class="sb_instagram_box sbi_gdpr_no">
								<div class="sb_instagram_box_setting">
									<p><?php esc_html_e( 'The plugin will function as normal and load images and videos directly from Instagram.', 'instagram-feed' ); ?></p>
								</div>
							</div>

						<?php endif; ?>
					</td>
				</tr>

				</tbody>
			</table>

			<?php submit_button(); ?>

			<hr id="misc" />
			<h3><?php esc_html_e( 'Misc', 'instagram-feed' ); ?></h3>

			<table class="form-table" aria-describedby="Misc settings">
				<tbody>
				<tr>
					<th scope="row" class="bump-left"><label class="bump-left"><?php esc_html_e( 'Are you using an Ajax powered theme?', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> ajaxtheme
							Eg: ajaxtheme=true</code></th>
					<td>
						<input name="sb_instagram_ajax_theme" type="checkbox" id="sb_instagram_ajax_theme"
							<?php
							if ( $sb_instagram_ajax_theme ) {
								echo 'checked'; }
							?>
						/>
						<label for="sb_instagram_ajax_theme"><?php esc_html_e( 'Yes', 'instagram-feed' ); ?></label>
						<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What does this mean?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( "When navigating your site, if your theme uses Ajax to load content into your pages (meaning your page doesn't refresh) then check this setting. If you're not sure then it's best to leave this setting unchecked while checking with your theme author, otherwise checking it may cause a problem.", 'instagram-feed' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row" class="bump-left"><label class="bump-left"><?php esc_html_e( 'Image Resizing', 'instagram-feed' ); ?></label></th>
					<td>
						<input name="sb_instagram_disable_resize" type="checkbox" id="sb_instagram_disable_resize"
							<?php
							if ( $sb_instagram_disable_resize ) {
								echo 'checked'; }
							?>
						/>
						<label for="sb_instagram_disable_resize"><?php esc_html_e( 'Disable Image Resizing', 'instagram-feed' ); ?></label>                        <span>                      <a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What does this mean?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( 'The disables the local storing of image files to use for images in the plugin.', 'instagram-feed' ); ?></p><br><br>
						</span>
						<input name="sb_instagram_favor_local" type="checkbox" id="sb_instagram_favor_local"
							<?php
							if ( $sb_instagram_favor_local ) {
								echo 'checked'; }
							?>
						/>
						<span><label for="sb_instagram_favor_local"><?php esc_html_e( 'Favor Local Images', 'instagram-feed' ); ?></label>                        <a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What does this mean?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( 'The plugin creates and stores resized versions of images in order to serve a more optimized resolution size in the feed. Enable this setting to always use these images if one is available.', 'instagram-feed' ); ?></p><br><br>
						</span>
						<input id="sbi_reset_resized" class="button-secondary" type="submit" value="<?php esc_attr_e( 'Reset Resized Images' ); ?>" style="vertical-align: middle;"/>
						<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What does this mean?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( "The plugin creates and stores resized versions of images in order to serve a more optimized resolution size in the feed. The local images and records in custom database tables are also used to store posts from recent hashtag feeds that are no longer available from Instagram's API. Click this button to clear all data related to resized images. Enable the setting to favor local images to always use a local, resized image if one is available.", 'instagram-feed' ); ?></p>
					</td>
				</tr>

				<tr >
					<th scope="row"><label><?php esc_html_e( 'Max concurrent API requests', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> maxrequests
							Eg: maxrequests=2</code></th>
					<td>
						<input name="sb_instagram_requests_max" type="number" min="1" max="10" value="<?php echo esc_attr( $sb_instagram_requests_max ); ?>" />
						<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What does this mean?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( 'Change the number of maximum concurrent API requests. This is not recommended unless directed by a member of the support team.', 'instagram-feed' ); ?></p>
					</td>
				</tr>

				<tr >
					<th scope="row"><label><?php esc_html_e( 'API request size', 'instagram-feed' ); ?></label><code class="sbi_shortcode"> minnum
							Eg: minnum=25</code></th>
					<td>
						<input name="sb_instagram_minnum" type="number" min="0" max="100" value="<?php echo esc_attr( $sb_instagram_minnum ); ?>" />
						<span class="sbi_note"><?php esc_html_e( 'Leave at "0" for default', 'instagram-feed' ); ?></span>
						<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What does this mean?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( 'If your feed contains a lot of IG TV posts or your feed is not displaying any posts despite there being posts available on Instagram.com, try increasing this number to 25 or more.', 'instagram-feed' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row" class="bump-left">
						<label class="bump-left"><?php esc_html_e( 'Load initial posts with AJAX', 'instagram-feed' ); ?></label>
					</th>
					<td>
						<input name="sb_ajax_initial" type="checkbox" id="sb_ajax_initial"
							<?php
							if ( $sb_ajax_initial ) {
								echo 'checked'; }
							?>
						/>
						<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What does this mean?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( 'Initial posts will be loaded using AJAX instead of added to the page directly. If you use page caching, this will allow the feed to update according to the "Check for new posts every" setting on the "Configure" tab.', 'instagram-feed' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row" class="bump-left">
						<label for="sb_instagram_cron" class="bump-left"><?php esc_html_e( 'Force cache to clear on interval', 'instagram-feed' ); ?></label>
					</th>
					<td>
						<select name="sb_instagram_cron">
							<option value="unset"
								<?php
								if ( $sb_instagram_cron === 'unset' ) {
									echo 'selected="selected"'; }
								?>
							>-</option>
							<option value="yes"
								<?php
								if ( $sb_instagram_cron === 'yes' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( 'Yes', 'instagram-feed' ); ?></option>
							<option value="no"
								<?php
								if ( $sb_instagram_cron === 'no' ) {
									echo 'selected="selected"'; }
								?>
							><?php esc_html_e( 'No', 'instagram-feed' ); ?></option>
						</select>

						<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What does this mean?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( "If you're experiencing an issue with the plugin not auto-updating then you can set this to 'Yes' to run a scheduled event behind the scenes which forces the plugin cache to clear on a regular basis and retrieve new data from Instagram.", 'instagram-feed' ); ?></p>
					</td>
				</tr>

				<tr >
					<th scope="row"><label><?php esc_html_e( 'Enable Backup/Permanent caching', 'instagram-feed' ); ?></label></th>
					<td class="sbi-customize-tab-opt">
						<input name="sb_instagram_backup" type="checkbox" id="sb_instagram_backup"
							<?php
							if ( $sb_instagram_backup ) {
								echo 'checked'; }
							?>
						/>
						<input id="sbi_clear_backups" class="button-secondary" type="submit" value="<?php esc_attr_e( 'Clear Backup/Permanent Caches' ); ?>" />
						<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What does this mean?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( 'Every feed will save a duplicate version of itself in the database to be used if the normal cache is not available.', 'instagram-feed' ); ?></p>
					</td>
				</tr>

				<tr >
					<th scope="row"><label><?php esc_html_e( 'Enqueue JS file in head', 'instagram-feed' ); ?></label></th>
					<td>
						<input type="checkbox" name="enqueue_js_in_head" id="sb_instagram_enqueue_js_in_head"
							<?php
							if ( $enqueue_js_in_head ) {
								echo 'checked="checked"'; }
							?>
						/>
						<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What does this mean?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( "Check this box if you'd like to enqueue the JavaScript file for the plugin in the head instead of the footer.", 'instagram-feed' ); ?></p>
					</td>
				</tr>

				<tr >
					<th scope="row"><label><?php esc_html_e( 'Enqueue CSS file with shortcode', 'instagram-feed' ); ?></label></th>
					<td>
						<input type="checkbox" name="enqueue_css_in_shortcode" id="sb_instagram_enqueue_css_in_shortcode"
							<?php
							if ( $enqueue_css_in_shortcode ) {
								echo 'checked="checked"'; }
							?>
						/>
						<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What does this mean?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( "Check this box if you'd like to only include the CSS file for the plugin when the feed is on the page.", 'instagram-feed' ); ?></p>
					</td>
				</tr>

				<tr >
					<th scope="row"><label><?php esc_html_e( 'Disable JS Image Loading', 'instagram-feed' ); ?></label></th>
					<td>
						<input type="checkbox" name="disable_js_image_loading" id="sb_instagram_disable_js_image_loading"
							<?php
							if ( $disable_js_image_loading ) {
								echo 'checked="checked"'; }
							?>
						/>
						<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What does this mean?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( 'Check this box to have images loaded server side instead of with JS.', 'instagram-feed' ); ?></p>
					</td>
				</tr>

				<tr >
					<th scope="row"><label for="sb_instagram_disable_mob_swipe"><?php esc_html_e( 'Disable Mobile Swipe Code', 'instagram-feed' ); ?></label></th>
					<td>
						<input type="checkbox" name="sb_instagram_disable_mob_swipe" id="sb_instagram_disable_mob_swipe"
							<?php
							if ( $sb_instagram_disable_mob_swipe ) {
								echo 'checked="checked"'; }
							?>
						/>
						<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What does this mean?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( "Check this box if you'd like to disable jQuery mobile in the JavaScript file. This will fix issues with jQuery versions 2.x and later.", 'instagram-feed' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="sbi_br_adjust"><?php esc_html_e( 'Caption Line-Break Limit', 'instagram-feed' ); ?></label></th>
					<td>
						<input type="checkbox" name="sbi_br_adjust" id="sbi_br_adjust"
							<?php
							if ( $sbi_br_adjust ) {
								echo 'checked="checked"'; }
							?>
						/>
						<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What does this mean?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( 'Character Limits for captions are adjusted for use of new line or %s html. Disable this setting to always use the true character limit.', 'instagram-feed' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row" class="bump-left"><label class="bump-left"><?php esc_html_e( 'Enable Mediavine integration', 'instagram-feed' ); ?></label></th>
					<td>
						<input name="sb_instagram_media_vine" type="checkbox" id="sb_instagram_media_vine"
							<?php
							if ( $sb_instagram_media_vine ) {
								echo 'checked'; }
							?>
						/>
						<label for="ssb_instagram_media_vine"><?php esc_html_e( 'Yes', 'instagram-feed' ); ?></label>
						<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What does this mean?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( 'Enable this setting to automatically place ads if you are using Mediavine ad management', 'instagram-feed' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row" class="bump-left"><label class="bump-left"><?php esc_html_e( 'Enable Custom Templates', 'instagram-feed' ); ?></label></th>
					<td>
						<input name="sb_instagram_custom_template" type="checkbox" id="sb_instagram_custom_template"
							<?php
							if ( $sb_instagram_custom_template ) {
								echo 'checked'; }
							?>
						/>
						<label for="ssb_instagram_custom_template"><?php esc_html_e( 'Yes', 'instagram-feed' ); ?></label>
						<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What does this mean?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php echo wp_kses_post( __( "The default HTML for the feed can be replaced with custom templates added to your theme's folder. Enable this setting to use these templates. See <a href=\"https://smashballoon.com/guide-to-creating-custom-templates/\" target=\"_blank\">this guide</a>", 'instagram-feed' ) ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row" class="bump-left"><label class="bump-left"><?php esc_html_e( 'Disable Admin Error Notice', 'instagram-feed' ); ?></label></th>
					<td>
						<input name="sb_instagram_disable_admin_notice" type="checkbox" id="sb_instagram_disable_admin_notice"
							<?php
							if ( $sb_instagram_disable_admin_notice ) {
								echo 'checked'; }
							?>
						/>
						<label for="sb_instagram_disable_admin_notice"><?php esc_html_e( 'Yes', 'instagram-feed' ); ?></label>
						<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What does this mean?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( 'This will permanently disable the feed error notice that displays in the bottom right corner for admins on the front end of your site.', 'instagram-feed' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row" class="bump-left"><label class="bump-left"><?php esc_html_e( 'Feed Issue Email Report', 'instagram-feed' ); ?></label></th>
					<td>
						<input name="sb_instagram_enable_email_report" type="checkbox" id="sb_instagram_enable_email_report"
							<?php
							if ( $sb_instagram_enable_email_report === 'on' || $sb_instagram_enable_email_report === true ) {
								echo 'checked'; }
							?>
						/>
						<label for="sb_instagram_enable_email_report"><?php esc_html_e( 'Yes', 'instagram-feed' ); ?></label>
						<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What does this mean?', 'instagram-feed' ); ?></a><br />
						<p class="sbi_tooltip"><?php esc_html_e( "Instagram Feed will send a weekly notification email using your site's wp_mail() function if one or more of your feeds is not updating or is not displaying. If you're not receiving the emails in your inbox, you may need to configure an SMTP service using another plugin like WP Mail SMTP.", 'instagram-feed' ); ?></p>

						<div class="sb_instagram_box" style="display: block;">
							<div class="sb_instagram_box_setting">
								<label><?php esc_html_e( 'Schedule Weekly on', 'instagram-feed' ); ?></label><br>
								<?php
								$schedule_options = array(
									array(
										'val'   => 'monday',
										'label' => __( 'Monday', 'instagram-feed' ),
									),
									array(
										'val'   => 'tuesday',
										'label' => __( 'Tuesday', 'instagram-feed' ),
									),
									array(
										'val'   => 'wednesday',
										'label' => __( 'Wednesday', 'instagram-feed' ),
									),
									array(
										'val'   => 'thursday',
										'label' => __( 'Thursday', 'instagram-feed' ),
									),
									array(
										'val'   => 'friday',
										'label' => __( 'Friday', 'instagram-feed' ),
									),
									array(
										'val'   => 'saturday',
										'label' => __( 'Saturday', 'instagram-feed' ),
									),
									array(
										'val'   => 'sunday',
										'label' => __( 'Sunday', 'instagram-feed' ),
									),
								);

								if ( isset( $_GET['flag'] ) ) {
									echo '<span id="sbi-goto"></span>';
								}
								?>
								<select name="sb_instagram_email_notification" id="sb_instagram_email_notification">
									<?php foreach ( $schedule_options as $schedule_option ) : ?>
										<option value="<?php echo esc_attr( $schedule_option['val'] ); ?>"
											<?php
											if ( $schedule_option['val'] === $sb_instagram_email_notification ) {
												echo 'selected';}
											?>
										><?php echo esc_html( $schedule_option['label'] ); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="sb_instagram_box_setting">
								<label><?php esc_html_e( 'Email Recipients', 'instagram-feed' ); ?></label><br><input class="regular-text" type="text" name="sb_instagram_email_notification_addresses" value="<?php echo esc_attr( $sb_instagram_email_notification_addresses ); ?>"><span class="sbi_note"><?php esc_html_e( 'separate multiple emails with commas', 'instagram-feed' ); ?></span>
								<br><br><?php esc_html_e( 'Emails not working?', 'instagram-feed' ); ?> <a href="https://smashballoon.com/email-report-is-not-in-my-inbox/" target="_blank"><?php esc_html_e( 'See our related FAQ', 'instagram-feed' ); ?></a>
							</div>
						</div>

					</td>
				</tr>

				<tr>
					<?php
					$usage_tracking = get_option(
						'sbi_usage_tracking',
						array(
							'last_send' => 0,
							'enabled'   => sbi_is_pro_version(),
						)
					);

					if ( isset( $_POST['sb_instagram_requests_max'] ) ) {
						$usage_tracking['enabled'] = false;
						if ( isset( $_POST['sbi_usage_tracking_enable'] ) ) {
							$usage_tracking['enabled'] = true;
						}
						update_option( 'sbi_usage_tracking', $usage_tracking, false );
					}
					$sbi_usage_tracking_enable = isset( $usage_tracking['enabled'] ) ? $usage_tracking['enabled'] : true;
					?>
					<th scope="row" class="bump-left"><label class="bump-left"><?php esc_html_e( 'Enable Usage Tracking', 'instagram-feed' ); ?></label></th>
					<td>
						<input name="sbi_usage_tracking_enable" type="checkbox" id="sbi_usage_tracking_enable"
							<?php
							if ( $sbi_usage_tracking_enable ) {
								echo 'checked'; }
							?>
						/>
						<label for="sbi_usage_tracking_enable"><?php esc_html_e( 'Yes', 'instagram-feed' ); ?></label>
						<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What is usage tracking?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( "Understanding how you are using the plugin allows us to further improve it. The plugin will send a report in the background once per week which includes information about your plugin settings and statistics about your site, which we can use to help improve the features which matter most to you and improve your experience using the plugin. The plugin will never collect any sensitive information like access tokens, email addresses, or user information, and sending this data won't slow down your site at all. For more information,", 'instagram-feed' ); ?> <a href="https://smashballoon.com/instagram-feed/usage-tracking/" target="_blank"><?php esc_html_e( 'see here', 'instagram-feed' ); ?></a>.</p>
					</td>
				</tr>

				<?php if ( is_multisite() && current_user_can( 'manage_network' ) ) : ?>
					<tr>
						<th scope="row"><label for="sbi_super_admin_only"><?php esc_html_e( 'License Page Super Admin Only', 'instagram-feed' ); ?></label></th>
						<td>
							<input type="checkbox" name="sbi_super_admin_only" id="sbi_super_admin_only"
								<?php
								if ( ! empty( $sbi_super_admin_only ) ) {
									echo 'checked="checked"'; }
								?>
							/>
							<a class="sbi_tooltip_link" href="JavaScript:void(0);"><?php esc_html_e( 'What does this mean?', 'instagram-feed' ); ?></a>
							<p class="sbi_tooltip"><?php esc_html_e( "If you are using multisite, you can restrict access to the license page to super admin's only by enabling this setting.", 'instagram-feed' ); ?></p>
						</td>
					</tr>
				<?php endif; ?>

				<tr >
					<th scope="row"><label><?php esc_html_e( 'Manage Data', 'instagram-feed' ); ?></label></th>
					<td class="sbi-customize-tab-opt">
						<input id="sbi_clear_platform_data" class="button-secondary" type="submit" value="<?php esc_attr_e( 'Delete all Platform Data' ); ?>" />
						<a class="sbi_tooltip_link" href="JavaScript:void(0);" style="position: relative; top: 5px;"><?php esc_html_e( 'What does this mean?', 'instagram-feed' ); ?></a>
						<p class="sbi_tooltip"><?php esc_html_e( 'Warning: Clicking this button will permanently delete all Instagram data, including all connected accounts, cached posts, and stored images.', 'instagram-feed' ); ?></p>
					</td>
				</tr>

				</tbody>
			</table>

			<?php submit_button(); ?>

		<?php } //End Customize Advanced tab ?>

		</form>


		<?php
		//Show Customize page footer
		if ( strpos( $sbi_active_tab, 'customize' ) !== false ) {
			?>
			<p><i class="fa fa-life-ring" aria-hidden="true"></i>&nbsp; <?php echo wp_kses_post( sprintf( __( 'Need help setting up the plugin? Check out our %s', 'instagram-feed' ), '<a href="https://smashballoon.com/instagram-feed/docs/" target="_blank">' . __( 'setup directions', 'instagram-feed' ) . '</a>' ) ); ?></p>
		<?php } ?>


		<?php if ( $sbi_active_tab === 'display' ) { //Start Configure tab ?>

			<h3><?php esc_html_e( 'Display your Feed', 'instagram-feed' ); ?></h3>
			<p><?php esc_html_e( "Copy and paste the following shortcode directly into the page, post or widget where you'd like the feed to show up:", 'instagram-feed' ); ?></p>
			<input type="text" value="[instagram-feed]" size="16" readonly="readonly" style="text-align: center;" onclick="this.focus();this.select()" title="<?php esc_html_e( 'To copy, click the field then press Ctrl + C (PC) or Cmd + C (Mac).', 'instagram-feed' ); ?>" />

			<h3 style="padding-top: 10px;"><?php esc_html_e( 'Multiple Feeds', 'custom-twitter-feed' ); ?></h3>
			<p><?php esc_html_e( "If you'd like to display multiple feeds then you can set different settings directly in the shortcode like so:", 'instagram-feed' ); ?>
				<code>[instagram-feed num=9 cols=3]</code></p>
			<p>You can display as many different feeds as you like, on either the same page or on different pages, by just using the shortcode options below. For example:<br />
				<code>[instagram-feed]</code><br />
				<code>[instagram-feed num=4 cols=4 showfollow=false]</code><br />
				<code>[instagram-feed user=smashballoon]</code><br />
			</p>
			<p><?php esc_html_e( 'See the table below for a full list of available shortcode options:', 'instagram-feed' ); ?></p>

			<table class="sbi_shortcode_table" aria-describedby="Shortcode options">
				<tbody>
				<tr >
					<th scope="row"><?php esc_html_e( 'Shortcode option', 'instagram-feed' ); ?></th>
					<th scope="row"><?php esc_html_e( 'Description', 'instagram-feed' ); ?></th>
					<th scope="row"><?php esc_html_e( 'Example', 'instagram-feed' ); ?></th>
				</tr>

				<tr class="sbi_table_header"><td colspan=3><?php esc_html_e( 'Configure Options', 'instagram-feed' ); ?></td></tr>
				<tr>
					<td>type</td>
					<td><?php esc_html_e( 'Display photos from a connected User Account', 'instagram-feed' ); ?> (user)<br /><?php esc_html_e( 'Display posts from a Hashtag', 'instagram-feed' ); ?> (hashtag)<br />
						<?php esc_html_e( 'Display posts that have tagged a connected User Account', 'instagram-feed' ); ?> (tagged)<br />
						<?php esc_html_e( 'Display a mix of feed types', 'instagram-feed' ); ?> (mixed)</td>
					<td><code>[instagram-feed type=user]</code><br /><code>[instagram-feed type=hashtag]</code><br /><code>[instagram-feed type=tagged]</code><br /><code>[instagram-feed type=mixed]</code></td>
				</tr>
				<tr>
					<td>user</td>
					<td><?php esc_html_e( 'Your Instagram user name for the account. This must be a user name from one of your connected accounts.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed type="user" user="smashballoon"]</code></td>
				</tr>
				<tr>
					<td>hashtag</td>
					<td><?php esc_html_e( 'Any hashtag. Separate multiple hashtags with commas.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed type="hashtag" hashtag="#awesome"]</code></td>
				</tr>
				<tr>
					<td>tagged</td>
					<td><?php esc_html_e( 'Your business Instagram user name for the account. This must be a user name from one of your connected business accounts.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed type="tagged" tagged="smashballoon"]</code></td>
				</tr>
				<tr>
					<td>order</td>
					<td><?php esc_html_e( 'The order to display the Hashtag feed posts', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed order="top"]</code><br /><code>[instagram-feed order="recent"]</code></td>
				</tr>
				<tr>
					<td>tagged</td>
					<td><?php esc_html_e( 'Your Instagram user name for the account. This must be a user name from one of your connected accounts.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed tagged="smashballoon"]</code></td>
				</tr>


				<tr class="sbi_table_header"><td colspan=3><?php esc_html_e( 'Customize Options', 'instagram-feed' ); ?></td></tr>
				<tr>
					<td>width</td>
					<td><?php esc_html_e( 'The width of your feed. Any number.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed width=50]</code></td>
				</tr>
				<tr>
					<td>widthunit</td>
					<td><?php esc_html_e( "The unit of the width. 'px' or '%'", 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed widthunit=%]</code></td>
				</tr>
				<tr>
					<td>height</td>
					<td><?php esc_html_e( 'The height of your feed. Any number.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed height=250]</code></td>
				</tr>
				<tr>
					<td>heightunit</td>
					<td><?php esc_html_e( "The unit of the height. 'px' or '%'", 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed heightunit=px]</code></td>
				</tr>
				<tr>
					<td>background</td>
					<td><?php esc_html_e( 'The background color of the feed. Any hex color code.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed background=#ffff00]</code></td>
				</tr>


				<tr class="sbi_table_header"><td colspan=3><?php esc_html_e( 'Layout Options', 'instagram-feed' ); ?></td></tr>
				<tr>
					<td>layout</td>
					<td><?php esc_html_e( 'How posts are arranged visually in the feed.', 'instagram-feed' ); ?> 'grid', 'carousel', 'masonry', or 'highlight'</td>
					<td><code>[instagram-feed layout=grid]</code></td>
				</tr>
				<tr>
					<td>num</td>
					<td><?php esc_html_e( 'The number of photos to display initially. Maximum is 33.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed num=10]</code></td>
				</tr>
				<tr>
					<td>nummobile</td>
					<td><?php esc_html_e( 'The number of photos to display initially for mobile screens (smaller than 480 pixels).', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed nummobile=6]</code></td>
				</tr>
				<tr>
					<td>cols</td>
					<td><?php esc_html_e( 'The number of columns in your feed. 1 - 10.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed cols=5]</code></td>
				</tr>
				<tr>
					<td>colsmobile</td>
					<td><?php esc_html_e( 'The number of columns in your feed for mobile screens (smaller than 480 pixels).', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed colsmobile=2]</code></td>
				</tr>
				<tr>
					<td>imagepadding</td>
					<td><?php esc_html_e( 'The spacing around your photos', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed imagepadding=10]</code></td>
				</tr>
				<tr>
					<td>imagepaddingunit</td>
					<td><?php esc_html_e( "The unit of the padding. 'px' or '%'", 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed imagepaddingunit=px]</code></td>
				</tr>

				<tr class="sbi_table_header"><td colspan=3><?php esc_html_e( 'Carousel Options', 'instagram-feed' ); ?></td></tr>
				<tr>
					<td>carouselrows</td>
					<td><?php esc_html_e( 'Choose 1 or 2 rows of posts in the carousel', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed carouselrows=1]</code></td>
				</tr>
				<tr>
					<td>carouselloop</td>
					<td><?php esc_html_e( 'Infinitely loop through posts or rewind', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed carouselloop=rewind]</code></td>
				</tr>
				<tr>
					<td>carouselarrows</td>
					<td><?php esc_html_e( 'Display directional arrows on the carousel', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed carouselarrows=true]</code></td>
				</tr>
				<tr>
					<td>carouselpag</td>
					<td><?php esc_html_e( 'Display pagination links below the carousel', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed carouselpag=true]</code></td>
				</tr>
				<tr>
					<td>carouselautoplay</td>
					<td><?php esc_html_e( 'Make the carousel autoplay', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed carouselautoplay=true]</code></td>
				</tr>
				<tr>
					<td>carouseltime</td>
					<td><?php esc_html_e( 'The interval time between slides for autoplay. Time in miliseconds.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed carouseltime=8000]</code></td>
				</tr>

				<tr class="sbi_table_header"><td colspan=3><?php esc_html_e( 'Highlight Options', 'instagram-feed' ); ?></td></tr>
				<tr>
					<td>highlighttype</td>
					<td><?php esc_html_e( 'Choose from 3 different ways of highlighting posts.', 'instagram-feed' ); ?> 'pattern', 'hashtag', 'id'.</td>
					<td><code>[instagram-feed highlighttype=hashtag]</code></td>
				</tr>
				<tr>
					<td>highlightpattern</td>
					<td><?php esc_html_e( 'How often a post is highlighted.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed highlightpattern=7]</code></td>
				</tr>
				<tr>
					<td>highlightoffset</td>
					<td><?php esc_html_e( 'When to start the highlight pattern.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed highlightoffset=3]</code></td>
				</tr>
				<tr>
					<td>highlighthashtag</td>
					<td><?php esc_html_e( 'Highlight posts with these hashtags.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed highlighthashtag=best]</code></td>
				</tr>

				<tr class="sbi_table_header"><td colspan=3><?php esc_html_e( 'Photos Options', 'instagram-feed' ); ?></td></tr>
				<tr>
					<td>sortby</td>
					<td><?php esc_html_e( 'Sort the posts by Newest to Oldest (none) Random (random) or Likes (likes)', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed sortby=random]</code></td>
				</tr>
				<tr>
					<td>imageres</td>
					<td><?php esc_html_e( 'The resolution/size of the photos.', 'instagram-feed' ); ?> 'auto', full', or 'medium'.</td>
					<td><code>[instagram-feed imageres=full]</code></td>
				</tr>
				<tr>
					<td>media</td>
					<td><?php esc_html_e( 'Display all media, only photos, or only videos', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed media=photos]</code></td>
				</tr>
				<tr>
					<td>videotypes</td>
					<td><?php esc_html_e( 'When showing only videos, what types to include', 'instagram-feed' ); ?> 'igtv', 'regular' or 'igtv,regular'.</td>
					<td><code>[instagram-feed videotypes=regular,igtv]</code></td>
				</tr>
				<tr>
					<td>disablelightbox</td>
					<td><?php esc_html_e( 'Whether to disable the photo Lightbox. It is enabled by default.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed disablelightbox=true]</code></td>
				</tr>
				<tr>
					<td>captionlinks</td>
					<td><?php esc_html_e( "Whether to use urls in captions for the photo's link instead of linking to instagram.com.", 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed captionlinks=true]</code></td>
				</tr>
				<tr>
					<td>offset</td>
					<td><?php esc_html_e( 'Offset which post is displayed first in the feed.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed offset=1]</code></td>
				</tr>

				<tr class="sbi_table_header"><td colspan=3><?php esc_html_e( 'Lightbox Comments Options', 'instagram-feed' ); ?></td></tr>
				<tr>
					<td>lightboxcomments</td>
					<td><?php esc_html_e( 'Whether to show comments in the lightbox for this feed.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed lightboxcomments=true]</code></td>
				</tr>
				<tr>
					<td>numcomments</td>
					<td><?php esc_html_e( 'Number of comments to show starting from the most recent.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed numcomments=10]</code></td>
				</tr>

				<tr class="sbi_table_header"><td colspan=3><?php esc_html_e( 'Photos Hover Style Options', 'instagram-feed' ); ?></td></tr>
				<tr>
					<td>hovercolor</td>
					<td><?php esc_html_e( 'The background color when hovering over a photo. Any hex color code.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed hovercolor=#ff0000]</code></td>
				</tr>
				<tr>
					<td>hovertextcolor</td>
					<td><?php esc_html_e( 'The text/icon color when hovering over a photo. Any hex color code.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed hovertextcolor=#fff]</code></td>
				</tr>
				<tr>
					<td>hoverdisplay</td>
					<td><?php esc_html_e( 'The info to display when hovering over the photo. Available options:', 'instagram-feed' ); ?><br />username, date, instagram, caption, likes</td>
					<td><code>[instagram-feed hoverdisplay="date, likes"]</code></td>
				</tr>

				<tr class="sbi_table_header"><td colspan=3><?php esc_html_e( 'Header Options', 'instagram-feed' ); ?></td></tr>
				<tr>
					<td>showheader</td>
					<td><?php esc_html_e( 'Whether to show the feed Header.', 'instagram-feed' ); ?> 'true' or 'false'.</td>
					<td><code>[instagram-feed showheader=false]</code></td>
				</tr>
				<tr>
					<td>headerstyle</td>
					<td><?php esc_html_e( 'Which header style to use. Choose from standard, boxed, or centered.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed headerstyle=boxed]</code></td>
				</tr>
				<tr>
					<td>headersize</td>
					<td><?php esc_html_e( 'Size of the header. Choose from small, medium, or large.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed headersize=medium]</code></td>
				</tr>
				<tr>
					<td>headerprimarycolor</td>
					<td><?php esc_html_e( 'The primary color to use for the boxed header. Any hex color code.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed headerprimarycolor=#333]</code></td>
				</tr>
				<tr>
					<td>headersecondarycolor</td>
					<td><?php esc_html_e( 'The secondary color to use for the boxed header. Any hex color code.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed headersecondarycolor=#ccc]</code></td>
				</tr>
				<tr>
					<td>showfollowers</td>
					<td><?php esc_html_e( 'Display the number of followers in the header', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed showfollowers=true]</code></td>
				</tr>
				<tr>
					<td>showbio</td>
					<td><?php esc_html_e( 'Display the bio in the header', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed showbio=true]</code></td>
				</tr>
				<tr>
					<td>custombio</td>
					<td><?php esc_html_e( 'Display a custom bio in the header', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed custombio="My custom bio."]</code></td>
				</tr>
				<tr>
					<td>customavatar</td>
					<td><?php esc_html_e( 'Display a custom avatar in the header. Enter the full URL of an image file.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed customavatar="https://example.com/avatar.jpg"]</code></td>
				</tr>
				<tr>
					<td>headercolor</td>
					<td><?php esc_html_e( 'The color of the Header text. Any hex color code.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed headercolor=#333]</code></td>
				</tr>
				<tr>
					<td>stories</td>
					<td><?php esc_html_e( "Include the user's Instagram story in the header if available.", 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed stories=true]</code></td>
				</tr>
				<tr>
					<td>storiestime</td>
					<td><?php esc_html_e( 'Length of time an image slide will display when viewing stories.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed storiestime=5000]</code></td>
				</tr>

				<tr class="sbi_table_header"><td colspan=3><?php esc_html_e( 'Caption Options', 'instagram-feed' ); ?></td></tr>
				<tr>
					<td>showcaption</td>
					<td><?php esc_html_e( "Whether to show the photo caption. 'true' or 'false'.", 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed showcaption=false]</code></td>
				</tr>
				<tr>
					<td>captionlength</td>
					<td><?php esc_html_e( 'The number of characters of the caption to display', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed captionlength=50]</code></td>
				</tr>
				<tr>
					<td>captioncolor</td>
					<td><?php esc_html_e( 'The text color of the caption. Any hex color code.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed captioncolor=#000]</code></td>
				</tr>
				<tr>
					<td>captionsize</td>
					<td><?php esc_html_e( 'The size of the caption text. Any number.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed captionsize=24]</code></td>
				</tr>

				<tr class="sbi_table_header"><td colspan=3><?php esc_html_e( 'Likes &amp; Comments Options', 'instagram-feed' ); ?></td></tr>
				<tr>
					<td>showlikes</td>
					<td><?php esc_html_e( "Whether to show the Likes &amp; Comments. 'true' or 'false'.", 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed showlikes=false]</code></td>
				</tr>
				<tr>
					<td>likescolor</td>
					<td><?php esc_html_e( 'The color of the Likes &amp; Comments. Any hex color code.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed likescolor=#FF0000]</code></td>
				</tr>
				<tr>
					<td>likessize</td>
					<td><?php esc_html_e( 'The size of the Likes &amp; Comments. Any number.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed likessize=14]</code></td>
				</tr>

				<tr class="sbi_table_header"><td colspan=3><?php esc_html_e( "'Load More' Button Options", 'instagram-feed' ); ?></td></tr>
				<tr>
					<td>showbutton</td>
					<td><?php esc_html_e( "Whether to show the 'Load More' button.", 'instagram-feed' ); ?> 'true' or 'false'.</td>
					<td><code>[instagram-feed showbutton=false]</code></td>
				</tr>
				<tr>
					<td>buttoncolor</td>
					<td><?php esc_html_e( 'The background color of the button. Any hex color code.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed buttoncolor=#000]</code></td>
				</tr>
				<tr>
					<td>buttontextcolor</td>
					<td><?php esc_html_e( 'The text color of the button. Any hex color code.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed buttontextcolor=#fff]</code></td>
				</tr>
				<tr>
					<td>buttontext</td>
					<td><?php esc_html_e( 'The text used for the button.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed buttontext="Load More Photos"]</code></td>
				</tr>

				<tr class="sbi_table_header"><td colspan=3><?php esc_html_e( "'Follow' Button Options", 'instagram-feed' ); ?></td></tr>
				<tr>
					<td>showfollow</td>
					<td><?php esc_html_e( "Whether to show the Instagram 'Follow' button.", 'instagram-feed' ); ?> 'true' or 'false'.</td>
					<td><code>[instagram-feed showfollow=true]</code></td>
				</tr>
				<tr>
					<td>followcolor</td>
					<td><?php esc_html_e( 'The background color of the button. Any hex color code.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed followcolor=#ff0000]</code></td>
				</tr>
				<tr>
					<td>followtextcolor</td>
					<td><?php esc_html_e( 'The text color of the button. Any hex color code.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed followtextcolor=#fff]</code></td>
				</tr>
				<tr>
					<td>followtext</td>
					<td><?php esc_html_e( 'The text used for the button.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed followtext="Follow me"]</code></td>
				</tr>
				<tr class="sbi_table_header"><td colspan=3><?php esc_html_e( 'Auto Load More on Scroll', 'instagram-feed' ); ?></td></tr>
				<tr>
					<td>autoscroll</td>
					<td><?php esc_html_e( 'Load more posts automatically as the user scrolls down the page.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed autoscroll=true]</code></td>
				</tr>
				<tr>
					<td>autoscrolldistance</td>
					<td><?php esc_html_e( 'Distance before the end of feed or page that triggers the loading of more posts.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed autoscrolldistance=200]</code></td>
				</tr>
				<tr class="sbi_table_header"><td colspan=3><?php esc_html_e( 'Post Filtering Options', 'instagram-feed' ); ?></td></tr>
				<tr>
					<td>excludewords</td>
					<td><?php esc_html_e( 'Remove posts which contain certain words or hashtags in the caption.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed excludewords="bad, words"]</code></td>
				</tr>
				<tr>
					<td>includewords</td>
					<td><?php esc_html_e( 'Only display posts which contain certain words or hashtags in the caption.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed includewords="sunshine"]</code></td>
				</tr>
				<!--<tr>
					<td>showusers</td>
					<td><?php esc_html_e( 'Only display posts from this user. Separate multiple users with a comma', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed showusers="smashballoon,taylorswift"]</code></td>
				</tr>-->
				<tr>
					<td>whitelist</td>
					<td><?php esc_html_e( 'Only display posts that match one of the post ids in this "whitelist"', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed whitelist="2"]</code></td>
				</tr>

				<tr class="sbi_table_header"><td colspan=3><?php esc_html_e( 'Misc Options', 'instagram-feed' ); ?></td></tr>
				<tr>
					<td>permanent</td>
					<td><?php esc_html_e( 'Feed will never look for new posts from Instagram.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed permanent="true"]</code></td>
				</tr>
				<tr>
					<td>maxrequests</td>
					<td><?php esc_html_e( 'Change the number of maximum concurrent API requests.', 'instagram-feed' ); ?><br /><?php esc_html_e( 'This is not recommended unless directed by a member of the support team.', 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed maxrequests="2"]</code></td>
				</tr>
				<tr>
					<td>customtemplate</td>
					<td><?php esc_html_e( 'Whether or not the plugin should look in your theme for a custom template.', 'instagram-feed' ); ?><br /><?php esc_html_e( "Do not enable unless there are templates added to your theme's folder.", 'instagram-feed' ); ?></td>
					<td><code>[instagram-feed customtemplate="true"]</code></td>
				</tr>

				</tbody>
			</table>

			<p><i class="fa fa-life-ring" aria-hidden="true"></i>&nbsp; <?php echo wp_kses_post( sprintf( __( 'Need help setting up the plugin? Check out our %s', 'instagram-feed' ), '<a href="https://smashballoon.com/instagram-feed/docs/" target="_blank">' . __( 'setup directions', 'instagram-feed' ) . '</a>' ) ); ?></p>

		<?php } //End Display tab ?>


		<?php if ( $sbi_active_tab === 'support' ) { //Start Support tab ?>
			<div class="sbi_support">

				<br />
				<h3 style="padding-bottom: 10px;">Need help?</h3>

				<p>
					<span class="sbi-support-title"><i class="fa fa-life-ring" aria-hidden="true"></i>&nbsp; <a href="https://smashballoon.com/instagram-feed/docs/" target="_blank"><?php esc_html_e( 'Setup Directions', 'instagram-feed' ); ?></a></span>
					<?php esc_html_e( 'A step-by-step guide on how to setup and use the plugin.', 'instagram-feed' ); ?>
				</p>

				<p>
					<span class="sbi-support-title"><i class="fa fa-youtube-play" aria-hidden="true"></i>&nbsp; <a href="https://www.youtube.com/embed/q6ZXVU4g970" target="_blank" id="sbi-play-support-video"><?php esc_html_e( 'Watch a Video', 'instagram-feed' ); ?></a></span>
					<?php esc_html_e( 'How to setup, use, and customize the plugin.', 'instagram-feed' ); ?>

					<iframe title="How to setup the Instagram Feed plugin" id="sbi-support-video" src="//www.youtube.com/embed/q6ZXVU4g970?theme=light&amp;showinfo=0&amp;controls=2&amp;rel=0" width="960" height="540" allowfullscreen="allowfullscreen" allow="autoplay; encrypted-media"></iframe>
				</p>

				<p>
					<span class="sbi-support-title"><i class="fa fa-question-circle" aria-hidden="true"></i>&nbsp; <a href="https://smashballoon.com/instagram-feed/support/faq/" target="_blank"><?php esc_html_e( 'FAQs and Docs', 'instagram-feed' ); ?></a></span>
					<?php esc_html_e( 'View our expansive library of FAQs and documentation to help solve your problem as quickly as possible.', 'instagram-feed' ); ?>
				</p>

				<div class="sbi-support-faqs">

					<ul>
						<li><strong>FAQs</strong></li>
						<li>&bull;&nbsp; <a href="https://smashballoon.com/my-instagram-access-token-keep-expiring/" target="_blank"><?php esc_html_e( 'My Access Token Keeps Expiring', 'instagram-feed' ); ?></a></li>
						<li>&bull;&nbsp; <a href="https://smashballoon.com/my-photos-wont-load/" target="_blank"><?php esc_html_e( 'My Instagram Feed Won\'t Load', 'instagram-feed' ); ?></a></li>
						<li>&bull;&nbsp; <a href="https://smashballoon.com/can-display-photos-specific-hashtag-specific-user-id/" target="_blank"><?php esc_html_e( 'Filter a User Feed by Hashtag', 'instagram-feed' ); ?></a></li>
						<li style="margin-top: 8px; font-size: 12px;"><a href="https://smashballoon.com/instagram-feed/support/faq/" target="_blank"><?php esc_html_e( 'See All', 'instagram-feed' ); ?><i class="fa fa-chevron-right" aria-hidden="true"></i></a></li>
					</ul>

					<ul>
						<li><strong>Documentation</strong></li>
						<li>&bull;&nbsp; <a href="https://smashballoon.com/instagram-feed/docs/" target="_blank"><?php esc_html_e( 'Installation and Configuration', 'instagram-feed' ); ?></a></li>
						<li>&bull;&nbsp; <a href="https://smashballoon.com/display-multiple-instagram-feeds/" target="_blank"><?php esc_html_e( 'Displaying multiple feeds', 'instagram-feed' ); ?></a></li>
						<li>&bull;&nbsp; <a href="https://smashballoon.com/instagram-feed-faq/customization/" target="_blank"><?php esc_html_e( 'Customizing your Feed', 'instagram-feed' ); ?></a></li>
					</ul>
				</div>

				<p>
					<span class="sbi-support-title"><i class="fa fa-rocket" aria-hidden="true"></i>&nbsp; <a href="admin.php?page=sbi-welcome-new"><?php esc_html_e( 'Welcome Page', 'instagram-feed' ); ?></a></span>
					<?php esc_html_e( "View the plugin welcome page to see what's new in the latest update.", 'instagram-feed' ); ?>
				</p>

				<p>
					<span class="sbi-support-title"><i class="fa fa-envelope" aria-hidden="true"></i>&nbsp; <a href="https://smashballoon.com/instagram-feed/support/" target="_blank"><?php esc_html_e( 'Request Support', 'instagram-feed' ); ?></a></span>
					<?php esc_html_e( 'Still need help? Submit a ticket and one of our support experts will get back to you as soon as possible.', 'instagram-feed' ); ?><br /><strong><?php esc_html_e( 'Important:', 'instagram-feed' ); ?></strong> <?php echo wp_kses_post( sprintf( __( 'Please include your %s below with all support requests.', 'instagram-feed' ), '<strong>' . __( 'System Info', 'instagram-feed' ) . '</strong>' ) ); ?>
				</p>
			</div>

			<hr />

			<h3><?php esc_html_e( 'System Info', 'instagram-feed' ); ?> &nbsp; <em style="color: #666; font-size: 11px; font-weight: normal;"><?php esc_html_e( 'Click the text below to select all', 'instagram-feed' ); ?></em></h3>


			<?php $sbi_options = get_option( 'sb_instagram_settings' ); ?>
			<textarea readonly="readonly" onclick="this.focus();this.select()" title="To copy, click the field then press Ctrl + C (PC) or Cmd + C (Mac)." style="width: 100%; max-width: 960px; height: 500px; white-space: pre; font-family: Menlo,Monaco,monospace;">
## SITE/SERVER INFO: ##
Site URL:                 <?php echo esc_html( site_url() ) . "\n"; ?>
Home URL:                 <?php echo esc_html( home_url() ) . "\n"; ?>
WordPress Version:        <?php echo esc_html( get_bloginfo( 'version' ) ) . "\n"; ?>
PHP Version:              <?php echo esc_html( PHP_VERSION ) . "\n"; ?>
Web Server Info:          <?php echo esc_html( $_SERVER['SERVER_SOFTWARE'] ) . "\n"; ?>

## ACTIVE PLUGINS: ##
<?php
$plugins        = get_plugins();
$active_plugins = get_option( 'active_plugins', array() );

foreach ( $plugins as $plugin_path => $plugin ) {
	// If the plugin isn't active, don't show it.
	if ( ! in_array( $plugin_path, $active_plugins, true ) ) {
		continue;
	}

	echo esc_html( $plugin['Name'] ) . ': ' . esc_html( $plugin['Version'] ) . "\n";
}
?>

## PLUGIN SETTINGS: ##
sb_instagram_license => <?php echo esc_html( get_option( 'sbi_license_key' ) ) . "\n"; ?>
sb_instagram_license_type => <?php echo esc_html( SBI_PLUGIN_NAME ) . "\n"; ?>
				<?php
				global $wpdb;
				foreach ( $sbi_options as $key => $val ) {
					if ( $key !== 'connected_accounts' ) {
						if ( is_array( $val ) ) {
							foreach ( $val as $item ) {
								if ( is_array( $item ) ) {
									foreach ( $item as $key2 => $val2 ) {
										echo esc_html( $key2 ) . ' => ' . esc_html( $val2 ) . "\n";
									}
								} else {
									echo esc_html( $key ) . ' => ' . esc_html( $item ) . "\n";
								}
							}
						} else {
							echo esc_html( $key ) . ' => ' . esc_html( $val ) . "\n";
						}
					}
				}
				?>

## CONNECTED ACCOUNTS: ##
<?php
echo "\n";
$con_accounts      = isset( $sbi_options['connected_accounts'] ) ? $sbi_options['connected_accounts'] : array();
$business_accounts = array();
$basic_accounts    = array();
$manager           = new SB_Instagram_Data_Manager();
if ( ! empty( $con_accounts ) ) {
	foreach ( $con_accounts as $account ) {
		$type                    = isset( $account['type'] ) ? $account['type'] : 'personal';
		$original_access_token   = ! empty( $account['access_token'] ) ? $account['access_token'] : '';
		$account['access_token'] = ! empty( $original_access_token ) ? $manager->remote_encrypt( $original_access_token ) : '';
		if ( isset( $account['page_access_token'] ) ) {
			unset( $account['page_access_token'] );
		}
		echo '*' . esc_html( $account['user_id'] ) . '*' . "\n";
		var_export( $account );
		echo "\n";
		$account['access_token'] = $original_access_token;
		if ( $type === 'business' ) {
			$business_accounts[] = $account;
		} elseif ( $type === 'basic' ) {
			$basic_accounts[] = $account;
		}
	}
}
?>

## API RESPONSE: ##
<?php
$first_con_basic_account    = isset( $basic_accounts[0] ) ? $basic_accounts[0] : array();
$first_con_business_account = isset( $business_accounts[0] ) ? $business_accounts[0] : array();

if ( ! empty( $first_con_basic_account ) ) {
	echo '*BASIC ACCOUNT*';
	echo "\n";
	$connection = new SB_Instagram_API_Connect( $first_con_basic_account, 'header' );
	$connection->connect();
	if ( ! $connection->is_wp_error() && ! $connection->is_instagram_error() ) {
		foreach ( $connection->get_data() as $key => $item ) {
			if ( is_array( $item ) ) {
				foreach ( $item as $key2 => $item2 ) {
					echo esc_html( $key2 ) . ' => ' . esc_html( $item2 ) . "\n";
				}
			} else {
				echo esc_html( $key ) . ' => ' . esc_html( $item ) . "\n";
			}
		}
	} else {
		if ( $connection->is_wp_error() ) {
			$response = $connection->get_wp_error();
			if ( isset( $response ) && isset( $response->errors ) ) {
				foreach ( $response->errors as $key => $item ) {
					echo esc_html( $key ) . ' => ' . esc_html( $item[0] ) . "\n";
				}
			}
		} else {
			$error = $connection->get_data();
			var_export( $error );
		}
	}
	echo "\n";
} else {
	echo 'no connected basic accounts';
	echo "\n";
}
if ( ! empty( $first_con_business_account ) ) {
	echo '*BUSINESS ACCOUNT*';
	echo "\n";
	$connection = new SB_Instagram_API_Connect( $first_con_business_account, 'header' );
	$connection->connect();
	if ( ! $connection->is_wp_error() && ! $connection->is_instagram_error() ) {
		foreach ( $connection->get_data() as $key => $item ) {
			if ( is_array( $item ) ) {
				foreach ( $item as $key2 => $item2 ) {
					echo esc_html( $key2 ) . ' => ' . esc_html( $item2 ) . "\n";
				}
			} else {
				echo esc_html( $key ) . ' => ' . esc_html( $item ) . "\n";
			}
		}
	} else {
		if ( $connection->is_wp_error() ) {
			$response = $connection->get_wp_error();
			if ( isset( $response ) && isset( $response->errors ) ) {
				foreach ( $response->errors as $key => $item ) {
					echo esc_html( $key ) . ' => ' . esc_html( $item[0] ) . "\n";
				}
			}
		} else {
			$error = $connection->get_data();
			var_export( $error );
		}
	}
} else {
	echo 'no connected business accounts';
}
?>

				<?php
				$cron = _get_cron_array();
				foreach ( $cron as $key => $data ) {
					$is_target = false;
					foreach ( $data as $key2 => $val ) {
						if ( strpos( $key2, 'sbi' ) !== false || strpos( $key2, 'sb_instagram' ) !== false ) {
							$is_target = true;
							echo esc_html( $key2 );
							echo "\n";
						}
					}
					if ( $is_target ) {
						echo esc_html( date( 'Y-m-d H:i:s', $key ) );
						echo "\n";
						echo esc_html( 'Next Scheduled: ' . ( (int) $key - time() ) / 60 . ' minutes' );
						echo "\n\n";
					}
				}
				?>
## Cron Cache Report: ##
<?php
$cron_report = get_option( 'sbi_cron_report', array() );
if ( ! empty( $cron_report ) ) {
	var_export( $cron_report );
}
echo "\n";
?>

## Access Token Refresh: ##
<?php
$cron_report = get_option( 'sbi_refresh_report', array() );
if ( ! empty( $cron_report ) ) {
	var_export( $cron_report );
}
echo "\n";
?>

## Resizing: ##
<?php
$upload     = wp_upload_dir();
$upload_dir = $upload['basedir'];
$upload_dir = trailingslashit( $upload_dir ) . SBI_UPLOADS_NAME;
if ( file_exists( $upload_dir ) ) {
	echo 'upload directory exists';
} else {
	$created = wp_mkdir_p( $upload_dir );

	if ( ! $created ) {
		echo 'cannot create upload directory';
	}
}
echo "\n";
echo "\n";

$table_name             = esc_sql( $wpdb->prefix . SBI_INSTAGRAM_POSTS_TYPE );
$feeds_posts_table_name = esc_sql( $wpdb->prefix . SBI_INSTAGRAM_FEEDS_POSTS );

if ( $wpdb->get_var( "show tables like '$feeds_posts_table_name'" ) !== $feeds_posts_table_name ) {
	echo 'no feeds posts table';
	echo "\n";

} else {
	$last_result = $wpdb->get_results( "SELECT * FROM $feeds_posts_table_name ORDER BY id DESC LIMIT 1;" );
	if ( is_array( $last_result ) && isset( $last_result[0] ) ) {
		echo '*FEEDS POSTS TABLE*';
		echo "\n";

		foreach ( $last_result as $column ) {

			foreach ( $column as $key => $value ) {
				echo esc_html( $key ) . ': ' . esc_html( $value ) . "\n";

			}
		}
	} else {
		echo 'feeds posts has no rows';
		echo "\n";
	}
}
echo "\n";

if ( $wpdb->get_var( "show tables like '$table_name'" ) !== $table_name ) {
	echo 'no posts table';
	echo "\n";

} else {
	$last_result = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY id DESC LIMIT 1;", ARRAY_A );
	if ( is_array( $last_result ) && isset( $last_result[0] ) ) {
		echo '*POSTS TABLE*';
		echo "\n";
		foreach ( $last_result as $column ) {
			if ( isset( $column['json_data'] ) ) {
				unset( $column['json_data'] );
			}

			foreach ( $column as $key => $value ) {
				echo esc_html( $key ) . ': ' . esc_html( $value ) . "\n";

			}
		}
	} else {
		echo 'feeds posts has no rows';
		echo "\n";
	}
}
?>

## LISTS AND CACHES: ##
<?php
$sbi_current_white_names = get_option( 'sb_instagram_white_list_names', array() );

if ( empty( $sbi_current_white_names ) ) {
	esc_html_e( 'No white lists currently created', 'instagram-feed' );
} else {
	$sbi_white_size = count( $sbi_current_white_names );
	$sbi_i          = 1;
	echo 'IDs: ';
	foreach ( $sbi_current_white_names as $white ) {
		if ( $sbi_i !== $sbi_white_size ) {
			echo esc_html( $white ) . ', ';
		} else {
			echo esc_html( $white );
		}
		$sbi_i++;
	}
}
echo "\n";

if ( isset( $sbi_current_white_names[0] ) ) {
	$sb_instagram_white_lists    = get_option( 'sb_instagram_white_lists_' . $sbi_current_white_names[0], '' );
	$sb_instagram_white_list_ids = ! empty( $sb_instagram_white_lists ) ? implode( ', ', $sb_instagram_white_lists ) : '';
	echo 'White list ' . esc_html( $sbi_current_white_names[0] ) . ': ' . esc_html( $sb_instagram_white_list_ids ) . "\n";
}

?>

## Errors: ##
<?php
global $sb_instagram_posts_manager;
$errors = $sb_instagram_posts_manager->get_errors();
if ( ! empty( $errors['resizing'] ) ) :
	echo '* Resizing *' . "\n";
	echo esc_html( $errors['resizing'] ) . "\n";
endif;
if ( ! empty( $errors['database_create'] ) ) :
	echo '* Database Create *' . "\n";
	echo esc_html( $errors['database_create'] ) . "\n";
endif;
if ( ! empty( $errors['upload_dir'] ) ) :
	echo '* Uploads Directory *' . "\n";
	echo esc_html( $errors['upload_dir'] ) . "\n";
endif;
if ( ! empty( $errors['connection'] ) ) :
	echo '* API/WP_HTTP Request *' . "\n";
	var_export( $errors['connection'] );
endif;
?>

## Error Log: ##
<?php
$error_log = $sb_instagram_posts_manager->get_error_log();
if ( ! empty( $error_log ) ) :
	foreach ( $error_log as $error ) :
		echo esc_html( $error ) . "\n";
	endforeach;
endif;
?>

## Action Log: ##
<?php
$actions = $sb_instagram_posts_manager->get_action_log();
if ( ! empty( $actions ) ) :
	foreach ( $actions as $action ) :
		echo esc_html( $action ) . "\n";
	endforeach;
endif;
?>

## Location Summary: ##
<?php
$locator_summary          = SB_Instagram_Feed_Locator::summary();
$condensed_shortcode_atts = array( 'type', 'user', 'hashtag', 'tagged', 'num', 'cols', 'layout', 'whitelist', 'includewords' );

if ( ! empty( $locator_summary ) ) {

	foreach ( $locator_summary as $locator_section ) {
		if ( ! empty( $locator_section['results'] ) ) {
			$first_five = array_slice( $locator_section['results'], 0, 5 );
			foreach ( $first_five as $result ) {
				$condensed_shortcode_string = '[instagram-feed';
				$shortcode_atts             = json_decode( $result['shortcode_atts'], true );
				$shortcode_atts             = is_array( $shortcode_atts ) ? $shortcode_atts : array();
				foreach ( $shortcode_atts as $key => $value ) {
					if ( in_array( $key, $condensed_shortcode_atts, true ) ) {
						$condensed_shortcode_string .= ' ' . esc_html( $key ) . '="' . esc_html( $value ) . '"';
					}
				}
				$condensed_shortcode_string .= ']';
				echo esc_url( get_the_permalink( $result['post_id'] ) ) . ' ' . esc_html( $condensed_shortcode_string ) . "\n";
			}
		}
	}
}
?>

## GDPR: ##
<?php
if ( ! SB_Instagram_GDPR_Integrations::gdpr_tests_successful() ) :
	$errors = SB_Instagram_GDPR_Integrations::gdpr_tests_error_message();
	?>
	<?php echo wp_kses_post( $errors ); ?>
<?php endif; ?>

## oEmbed: ##
<?php
$oembed_token_settings = get_option( 'sbi_oembed_token', array() );
foreach ( $oembed_token_settings as $key => $value ) {
	echo esc_html( $key ) . ': ' . esc_html( $value ) . "\n";
}

$single  = new SB_Instagram_Single( 'https://www.instagram.com/p/CCq1D_cMYMF/' );
$post    = $single->fetch();
$message = '';
if ( isset( $post['thumbnail_url'] ) ) {
	$message = 'success';
} else {
	$error = $single->get_error();

	if ( ! empty( $error ) ) {
		$message = $error;
	}
}
echo 'oEmbed request test: ' . esc_html( $message );
?>
</textarea>
			<div style="margin-bottom: 20px;"><input id="sbi_reset_log" class="button-secondary" type="submit" value="Reset Error Log" style="vertical-align: middle;"/></div>
			<?php
			$nonce_verified = isset( $_GET['sbi_nonce'] ) && wp_verify_nonce( $_GET['sbi_nonce'], 'sbi-custom-debug' );
			if ( $nonce_verified && isset( $_GET['debug'] ) && $_GET['debug'] === 'true' ) :
				?>
				<div><a href="<?php echo esc_url( wp_nonce_url( get_admin_url( null, 'admin.php?page=sb-instagram-feed&tab=support' ), 'sbi-custom-debug', 'sbi_nonce' ) ); ?>"><?php esc_html_e( 'Hide Custom Table Data' ); ?></a></div>

				<?php
				$offset      = isset( $_GET['offset'] ) ? (int) $_GET['offset'] : 0;
				$show_images = isset( $_GET['images'] ) ? (int) $_GET['images'] === 1 : false;

				$posts_table_name       = $wpdb->prefix . SBI_INSTAGRAM_POSTS_TYPE;
				$feeds_posts_table_name = $wpdb->prefix . SBI_INSTAGRAM_FEEDS_POSTS;

				$results = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT
            p.id, p.created_on, p.instagram_id, p.images_done, p.media_id, f.feed_id, p.json_data, p.time_stamp, p.top_time_stamp, p.sizes
            FROM $posts_table_name AS p
            LEFT JOIN $feeds_posts_table_name AS f ON p.id = f.id
			LIMIT %d, %d",
						$offset,
						100
					)
				);
				if ( isset( $results[0] ) ) :
					?>
					<div class="sbi_debug_table_wrap">
						<table class="sbi_debug_table widefat striped" aria-describedby="Embeds">
							<tr>
								<?php foreach ( $results[0] as $col => $val ) : ?>
									<th scope="row"><?php echo esc_html( $col ); ?></th>
								<?php endforeach; ?>
							</tr>
							<?php foreach ( $results as $result ) : ?>
								<tr>
									<?php foreach ( $result as $key => $val ) : ?>
										<?php if ( $show_images && $key === 'media_id' ) : ?>
											<td ><img width="100" src="<?php echo esc_url( sbi_get_resized_uploads_url() . $val . 'low.jpg' ); ?>" alt="Image <?php echo esc_attr( $val ); ?>"/><br><span><?php echo esc_html( $val ); ?></span></td>

										<?php else : ?>
											<td ><span><?php echo esc_html( $val ); ?></span></td>
										<?php endif; ?>
									<?php endforeach; ?>

								</tr>
							<?php endforeach; ?>
						</table>
					</div>
				<?php
				endif;

			else :
				?>
				<div><a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'debug', 'true', get_admin_url( null, 'admin.php?page=sb-instagram-feed&tab=support' ) ), 'sbi-custom-debug', 'sbi_nonce' ) ); ?>">Show Custom Table Data</a></div>

			<?php
			endif;
		} //End Support tab
		?>

		<div class="sbi_quickstart">
			<h3><i class="fa fa-rocket" aria-hidden="true"></i>&nbsp; Display your feed</h3>
			<p>Copy and paste this shortcode directly into the page, post or widget where you'd like to display the feed:        <input type="text" value="[instagram-feed]" size="15" readonly="readonly" style="text-align: center;" onclick="this.focus();this.select()" title="To copy, click the field then press Ctrl + C (PC) or Cmd + C (Mac)."></p>
			<p>Find out how to display <a href="?page=sb-instagram-feed&amp;tab=display">multiple feeds</a>.</p>
		</div>

		<hr />

		<p><i class="fa fa-facebook-square" aria-hidden="true" style="color: #3B5998; font-size: 23px; margin-right: 1px;"></i>&nbsp; <span style="display: inline-block; top: -3px; position: relative;">Want to display Facebook posts? Check out our <a href="https://wordpress.org/plugins/custom-facebook-feed/" target="_blank">Facebook Feed</a> plugin</span></p>

		<p><i class="fa fa-twitter-square" aria-hidden="true" style="color: #00aced; font-size: 23px; margin-right: 1px;"></i>&nbsp; <span style="display: inline-block; top: -3px; position: relative;">Got Tweets? Check out our <a href="https://wordpress.org/plugins/custom-twitter-feeds/" target="_blank">Twitter Feed</a> plugin</span></p>

		<p><i class="fa fa-youtube-play" aria-hidden="true" style="color: #FF0000; font-size: 23px; margin-right: 1px;"></i>&nbsp; <span style="display: inline-block; top: -3px; position: relative;">Like to YouTube? Check out our <a href="https://wordpress.org/plugins/feeds-for-youtube/" target="_blank">YouTube Feed</a> plugin</span></p>

		<p class="sbi_disclaimer">Please note, use of this plugin is subject to <a href="https://developers.facebook.com/terms/" target="blank" rel="noopener noreferrer">Facebook's Platform Terms</a></p>

	</div> <!-- end #sbi_admin -->

	<?php
} //End Settings page
