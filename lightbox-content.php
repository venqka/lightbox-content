<?php
/*
Plugin Name: Lightbox Content
Plugin URI: http://shtrak.eu
Description: This plugin adds the feature to open posts and pages in lightbox without leave the page
Author: venqka@shtrak.eu
Version: 1.1
Author URI: shtrak.eu
Textdomain: lc
*/

// function sh_sc_admin_enqueue() {

// 	// Add the color picker css file       
// 	wp_enqueue_style( 'wp-color-picker' ); 

// 	wp_register_script( 'cc-backend', plugin_dir_url( __FILE__ ) . 'scripts/cc-backend.js', array( 'jquery', 'wp-color-picker' ), '1.8.0', false );

// 	wp_enqueue_script( 'cc-backend', plugin_dir_url( __FILE__ ) . 'scripts/cc-backend.js', array( 'jquery', 'wp-color-picker' ), '1.8.0', false );

// }
// add_action( 'admin_enqueue_scripts', 'sh_sc_admin_enqueue' );

/***********************************************
	Enqueue frontend scripts and styles
***********************************************/
function lc_enqueue() {

	//enqueue Lightbox Content styles
	wp_enqueue_style( 'lc-frontend', plugin_dir_url( __FILE__ ) . 'styles/lc-frontend.css', array(), '1.0', false );

	//enqueue Lightbox Content scripts
	wp_register_script( 'lc-frontend', plugin_dir_url( __FILE__ ) . 'scripts/lc-frontend.js', array( 'jquery' ), '1.0', false );

	wp_enqueue_script( 'lc-frontend', plugin_dir_url( __FILE__ ) . 'scripts/lc-frontend.js', array( 'jquery' ), '1.0', false );

	//set ajax url and create nonce
	$ajax_args = array(
		'ajax_url'	 	=> admin_url( 'admin-ajax.php' ), 
		'ajax_nonce'	=> wp_create_nonce( 'ajax-nonce' )			
	);
	wp_localize_script( 'lc-frontend', 'lc_ajax', $ajax_args );

}
add_action( 'wp_enqueue_scripts', 'lc_enqueue' );

/***********************************************
	Create the lightbox container
***********************************************/
function lc_container() {

?>
	<div class="lc-container"></div>
<?php
}
add_action( 'wp_footer', 'lc_container' );

function lc_load_content() {

	//check nonce
	// $ls_ajax_nonce = $_POST['nonce'];
	 
	// check_ajax_referer( 'ajax-nonce', 'nonce' );
	
	//get the post url
	$lc_post_url = $_POST["post_url"];

	//get the post id by url
	$lc_post_id = url_to_postid( $lc_post_url );

	ob_start();

?>
	<div class="lc">
		
		<article id="post-<?php echo $lc_post_id; ?>" <?php post_class(); ?>>
			
			<header class="entry-header">
				<h1 class="entry-title"><?php echo get_the_title( $lc_post_id ); ?></h1>
			</header>

<?php 
			//if( has_post_thumbnail( $lc_post_id ) ) {
?>
				<div class="post-thumbnail">
					<a href="<?php the_permalink( $lc_post_id ); ?>">
						<?php echo get_the_post_thumbnail( $lc_post_id ); ?>
					</a>
				</div><!-- .post-thumbnail -->
<?php
			//	}
?>
			<div class="entry-content">

				<?php echo( do_shortcode( get_post_field( 'post_content', $lc_post_id ) ) ); ?>

			</div><!-- .entry-content -->

		</article>
			
	</div>
<?php

	$lc = ob_get_clean();

	wp_send_json( $lc );

	wp_die();

}
add_action( 'wp_ajax_lc_get_post', 'lc_load_content' );
add_action( 'wp_ajax_nopriv_lc_get_post', 'lc_load_content' );