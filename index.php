<?php
  /*
Plugin Name: Reviews
Plugin URI: http://constacloud.com
Description:  Reviews rating snippet customize Plugin. 
Author: Vijendra 
Version: 1.0.0
*/
error_reporting(0);	
$short_code_check = 0;
if( is_admin() && !class_exists( 'WP_List_Table' ) )
require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
add_action('admin_enqueue_scripts', 'review_admin_page_script');

function review_admin_page_script()
{    
	if ( $_GET['page'] == 'Manage-Client' || $_GET['page'] == 'Manage-Category' || $_GET['page'] == 'Pending-Review' || $_GET['page'] == 'Manage-Review'  || $_GET['page'] == 'Review-Setting' || $_GET['page'] == 'edit-Review') {
		wp_enqueue_style( 'review_admin_awesome_css', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
		wp_enqueue_style( 'review_admin_bootstrap_css', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
		wp_enqueue_style( 'review_admin_style_css', plugin_dir_url( __FILE__ ).'assets/css/style.css');
		wp_enqueue_style( 'review_admin_dataTables.bootstrap_css', plugin_dir_url( __FILE__ ).'assets/datatable/dataTables.bootstrap.min.css');
		wp_enqueue_script( array( 'jquery') );
		wp_enqueue_script( 'review_admin_bootstrap_js', plugin_dir_url( __FILE__ ).'assets/js/bootstrap.min.js');
		wp_enqueue_script( 'review_admin_custom_js', plugin_dir_url( __FILE__ ).'assets/js/custom.js');
		wp_enqueue_script( 'review_admin_jquery.dataTables_js', plugin_dir_url( __FILE__ ).'assets/datatable/jquery.dataTables.min.js');
		wp_enqueue_script( 'review_admin_dataTables.bootstrap_js', plugin_dir_url( __FILE__ ).'assets/datatable/dataTables.bootstrap.min.js');
    }
}

function rev_mail($app_url,$api_key,$curl_post_data){
	$mailgunKey = "api:" . $api_key;
	$service_url = $app_url.'/messages';
    $curl = curl_init($service_url);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, $mailgunKey);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $curl_response = curl_exec($curl);
    $response = json_decode($curl_response,true);
	return $response; 
}

function rv_get_status($id,$status)
{
	global $wpdb;
	if($status=='1') {
		$page = 'Manage-Review';
	} else {
		$page = 'Pending-Review';
	}
	$table_name = $wpdb->prefix.'rvcomment';
	$rowCat = $wpdb->get_row("select * from $table_name where id='$id' ", ARRAY_A);
	if($rowCat['reply'] !='' AND $rowCat['reply'] !=null) {
		$icon = 'class="btn btn-default" title="Replied"><i class="fa fa-fw fa-reply-all"></i>';
	} else {
		$icon = 'class="btn btn-info" title="Not Replied"><i class="fa fa-fw fa-reply"></i>';
	}

	if($rowCat['review_status'] == 1) {
		$return = '<a  onclick="return confirm(';
		$return .="'Are you sure, You want to change this review status?'";
		$return .=');" href="'.site_url().'/wp-admin/admin.php?page='.$page.'&unapprove='.$id.'"  title="Click here to put pending again." class="btn btn-success">Approved</a> <a onclick="return confirm(';
		$return .="'Are you sure, You want to delete this review?'";
		$return .=');" href="'.site_url().'/wp-admin/admin.php?page='.$page.'&delete='.$id.'"  class="btn btn-danger"><i class="fa fa-fw fa-trash"></i></a><br><button type="button"  style="margin-top:4px;" data-toggle="modal" data-target="#modal-default" onclick="rv_fun(';
		$return .="'".$id."','".$rowCat['reply']."'";
		$return .=')" '.$icon.'</button> <a href="'.site_url().'/wp-admin/admin.php?page=edit-Review&edit='.$id.'" class="btn btn-info"><i class="fa fa-fw fa-edit"></i></a>';
	}else{
		$return = '<a onclick="return confirm(';
		$return .="'Are you sure, You want to change this review status?'";
		$return .=');"  href="'.site_url().'/wp-admin/admin.php?page='.$page.'&approve='.$id.'" title="Click here to approve." class="btn btn-warning">Pending</a> <a onclick="return confirm(';
		$return .="'Are you sure, You want to delete this review?'";
		$return .=');" href="'.site_url().'/wp-admin/admin.php?page='.$page.'&delete='.$id.'"  class="btn btn-danger"><i class="fa fa-fw fa-trash"></i></a><br><button type="button"  data-toggle="modal" data-target="#modal-default" style="margin-top:4px;" onclick="rv_fun(';
		$return .="'".$id."','".$rowCat['reply']."'";
		$return .=')" '.$icon.' </button> <a href="'.site_url().'/wp-admin/admin.php?page=edit-Review&edit='.$id.'" class="btn btn-info"><i class="fa fa-fw fa-edit"></i></a>';
	}
	return $return;
}

function option_list_fn() {
	global $wpdb;
    include'general-options.php';
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }
    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function rv_star($star_no,$class)
{
	$star_no = $star_no ? $star_no : 0;
	if($star_no == 0){
		$review = 0;	
	}else{
		//$review = round($star_no * 100/5,2);
		$review = 0;
		if($star_no >= 0.01 && $star_no <= 0.25 ){ $review = 8; }
		if($star_no >= 0.26 && $star_no <= 0.50 ){ $review = 10; }
		if($star_no >= 0.51 && $star_no <= 0.75 ){ $review = 12; }
		if($star_no >= 0.76 && $star_no <= 1 ){ $review = 20; }
		if($star_no >= 1.01 && $star_no <= 1.25 ){ $review = 28; }
		if($star_no >= 1.26 && $star_no <= 1.50 ){ $review = 30; }
		if($star_no >= 1.51 && $star_no <= 1.75 ){ $review = 32; }
		if($star_no >= 1.76 && $star_no <= 2 ){ $review = 40; }
		if($star_no >= 2.01 && $star_no <= 2.25 ){ $review = 48; }
		if($star_no >= 2.26 && $star_no <= 2.50 ){ $review = 50; }
		if($star_no >= 2.51 && $star_no <= 2.75 ){ $review = 52; }
		if($star_no >= 2.76 && $star_no <= 3 ){ $review = 60; }
		if($star_no >= 3.01 && $star_no <= 3.25 ){ $review = 68; }
		if($star_no >= 3.26 && $star_no <= 3.50 ){ $review = 70; }
		if($star_no >= 3.51 && $star_no <= 3.75 ){ $review = 72; }
		if($star_no >= 3.76 && $star_no <= 4 ){ $review = 80; }
		if($star_no >= 4.01 && $star_no <= 4.25 ){ $review = 88; }
		if($star_no >= 4.26 && $star_no <= 4.50 ){ $review = 90; }
		if($star_no >= 4.51 && $star_no <= 4.75 ){ $review = 92; }
		if($star_no >= 4.76 && $star_no <= 5 ){  $review = 100; }
	}  
	return $return = '
	<style>
		.Stars.'.$class.'::after {
				width:  '.$review.'%;
			}
	</style>
	<span class="Stars '.$class.'" style="--rating: '.$star_no.'" data-width="'.$review.'" aria-label="Rating of this product."></span>';
}

function pending_count()
{
   global $wpdb;
	$table_name1 = $wpdb->prefix.'rvcomment';
	$rowClt = $wpdb->get_results("select * from $table_name1 where review_status='0' AND act ='1'", ARRAY_A);
	$total = $wpdb->num_rows;
	if($total >0) {
		$return ='('.$total.')';
	}else{
		$return='';
	}
	return $return;
}

function general_option_fun()
{
   global $wpdb;
	include'general-options.php';
}  

function category_fun()
{
	global $wpdb;
	include'category.php';   
}  

function rev_custom($rv_view_data)
{
	global $wpdb;
	if( isset( $GLOBALS['_GET']['view'] ) && $GLOBALS['_GET']['view'] == "onLoad" ){
		//
	} else {
		if($GLOBALS['short_code_check'] == 0){
			include'header.php';
			$GLOBALS['short_code_check'] = 1;
		}
	}

	if( isset( $GLOBALS['_GET']['view'] ) && $GLOBALS['_GET']['view'] == "all" ){
		include'view_all.php';
	} else if( isset( $GLOBALS['_GET']['view'] ) && $GLOBALS['_GET']['view'] == "onLoad" ){
		include 'front_view.php';
	} else {
		include 'on_load_review.php';
	}

	return $return_v; 
}

function rev_badge($rv_badge_data)
{	  
	global $wpdb;
	if($GLOBALS['short_code_check'] == 0){
	   include'header.php';
		$GLOBALS['short_code_check'] = 1;
	}

	include'badge.php';
	return $return_bedge;
}

function rev_text($rv_badge_data)
{	  
	global $wpdb;
	if($GLOBALS['short_code_check'] == 0){
	   include'header.php';
	   $GLOBALS['short_code_check'] = 1;
	}
	include'review_text.php';
	return $return;
}

function rev_customform($rv_form_data)
{
	global $wpdb;
	if($GLOBALS['short_code_check'] == 0){
	   include'header.php';
	   $GLOBALS['short_code_check'] = 1;
	}
	include'review_form.php';
} 

function review_fun()
{
   global $wpdb;
		include'review.php';
}  

function edit_review_fun()
{
   global $wpdb;
		include'edit_review.php';
} 

function review_fun_pending()
{
   global $wpdb;
		include'review_pending.php';
}  

function review_setting_fun(){
	global $wpdb;
    include'review_setting.php';
}

/*****************end add pricing list*****************/  
/*****************edit pricing list*****************/  
/*****************end edit pricing list*****************/ 

add_action( 'admin_menu', 'theme_options' ); 

function theme_options()  
{
	add_menu_page('Manage Client', 'Review Options '.pending_count().'', 'edit_pages', 'Manage-Client', 'general_option_fun', 'dashicons-admin-generic' ); 
	add_submenu_page( 'Manage-Client', 'Manage Client', 'Manage Client', 'edit_pages', 'Manage-Client');
	add_submenu_page( 'Manage-Client', 'Manage Category', 'Manage Category', 'edit_pages', 'Manage-Category', 'category_fun');
	add_submenu_page( 'Manage-Client', 'Pending Review', 'Pending Review '.pending_count().'', 'edit_pages', 'Pending-Review', 'review_fun_pending');
	add_submenu_page( 'Manage-Client', 'Approved Reviews', 'Approved Reviews', 'edit_pages', 'Manage-Review', 'review_fun');
	add_submenu_page( 'Manage-Client', 'Settings', 'Settings', 'manage_options', 'Review-Setting', 'review_setting_fun');
	add_submenu_page( 'Manage-Client', '', '', 'edit_pages', 'edit-Review', 'edit_review_fun');
}

/*****************create table*****************/ 
function create_theme_option_table() {
	global $wpdb;
    $table_name2 = $wpdb->prefix.'services';
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name2'") != $table_name2) {
		 //table not in database. Create new table
		 $charset_collate = $wpdb->get_charset_collate();
		 $sql = "CREATE TABLE $table_name2 (
			id mediumint(11) NOT NULL AUTO_INCREMENT,
			slug varchar(100) NOT NULL,
			name varchar(100) NOT NULL,
			act int(1) NOT NULL DEFAULT 1,
			UNIQUE KEY id (id)
		 ) $charset_collate;";
		 require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		 dbDelta( $sql );
		 /********inser row*********/
	}
	
    $table_name = $wpdb->prefix.'client';
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		 //table not in database. Create new table
		 $charset_collate = $wpdb->get_charset_collate();
		 $sql = "CREATE TABLE $table_name (
			  id mediumint(11) NOT NULL AUTO_INCREMENT,
			  clientName varchar(255) NOT NULL,
			  serviceType varchar(500) NOT NULL,
			  email varchar(55) NULL,
			  phone varchar(15) NULL,
			  datetime DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  act int(1) NOT NULL DEFAULT 1,
			  UNIQUE KEY id (id)
		 ) $charset_collate;";
		 require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		 dbDelta( $sql );
	}
	
    $table_name = $wpdb->prefix.'rvcomment';
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		 //table not in database. Create new table
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $table_name (
				id int(11) NOT NULL AUTO_INCREMENT,
				date_time datetime NOT NULL,
				reviewer_name varchar(100) DEFAULT NULL,
				reviewer_email varchar(150) DEFAULT NULL,
				review_title varchar(100) DEFAULT NULL,
				review_rating varchar(11) DEFAULT '0',
				review_text text,
				review_status tinyint(1) DEFAULT '0',
				reviewer_ip varchar(39) DEFAULT NULL,
				post_id int(11) DEFAULT '0',
				review_category varchar(100) DEFAULT 'none',
				reviewer_image varchar(100) DEFAULT NULL,
				reviewer_id varchar(11) DEFAULT NULL,
				service_id varchar(11) DEFAULT NULL,
				client_id varchar(11) DEFAULT NULL,
				review_phone varchar(20) DEFAULT NULL,
				act tinyint(2) DEFAULT '1',
				reply varchar(1000) DEFAULT NULL,
				PRIMARY KEY  (id)
			)
		CHARACTER SET utf8
		COLLATE utf8_general_ci;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
}

register_activation_hook(__FILE__, 'create_theme_option_table');
add_shortcode('REVCUSTOM','rev_custom');
add_shortcode('REVCUSTOMFORM','rev_customform');
add_shortcode('REVCUSTOMBADGE','rev_badge');
add_shortcode('REVCUSTOMTEXT','rev_text');
//add_action('admin_init', 'pricing_options');	
/*****************end create table*****************/ 


add_action('wp_ajax_load_more_review', 'load_more_review');  // for logged in users only
add_action('wp_ajax_nopriv_load_more_review', 'load_more_review'); // for ALL users

add_action('wp_ajax_load_slider_review', 'load_slider_review');  // for logged in users only
add_action('wp_ajax_nopriv_load_slider_review', 'load_slider_review'); // for ALL users

function reviewDataQuery(){
	global $wpdb;
	$data = $_POST;

	$dbTable = [
		'rvcomment' => $wpdb->prefix.'rvcomment', //table_name1
		'services' => $wpdb->prefix.'services', //table_name3
		'client' => $wpdb->prefix.'client', //table_client
	];
	
	$limit = "LIMIT " . $data['offset'] . "," . $data['perpage'];
	if (array_key_exists("category",$data['rv_view_data'])){
		if( $data['rv_view_data']['category'] == 0 ){
			$rowClt = $wpdb->get_results("select * from ".$dbTable['rvcomment']." where review_status=1 AND act=1 order by id DESC ".$limit, ARRAY_A);
		}else{
			$rowClt = $wpdb->get_results("select * from ".$dbTable['rvcomment']." where review_status=1 AND act=1 AND service_id='".$data['rv_view_data']['category']."' order by id DESC ".$limit, ARRAY_A);
		}
	} else if (array_key_exists("clientid",$data['rv_view_data'])) {
		$rowClt = $wpdb->get_results("select * from ".$dbTable['rvcomment']." where review_status=1 AND act=1 AND client_id='".$data['rv_view_data']['clientid']."' order by id DESC ".$limit, ARRAY_A);
	} else {
		$rowClt = $wpdb->get_results("select * from ".$dbTable['rvcomment']." where review_status=1 AND act=1 ".$limit, ARRAY_A);
	}
	
	// echo $wpdb->last_query;
	return $rowClt;
}

function load_slider_review(){
	$rowClt = reviewDataQuery();
	$return_v ='<div class="owl-carousel owl-theme">'; 
	foreach($rowClt as $client) {
		$return_v .='
		<div class="item">
			<div class="media">
				<div class="media-body">
					<div class="testimonial" >
						<p>
							<span class="star-box">
								'.rv_star($client['review_rating'],'slider'.$client['id'].'_'.rand()).' 
							</span>
							<span itemprop="reviewBody">
								'.$client['review_text'].'
							</span>
						</p>
					</div>
					<div class="user">
						<div class="user-profile" style="background: #'.rand( 000000,999999 ).';">'.$client['reviewer_name'][0].'</div>
							<p class="overview"> 
								<span>
									<b class="u_name" itemprop="name">'.$client['reviewer_name'].'</b> 
								</span> 
								<br>';
								if( $client['reply'] !='' && $client['reply'] !=null){ 
									$return_v .='
									<span data-toggle="popover" data-placement="top" title="'.$client['reply'].'" data-content="'.$client['reply'].'" 
										style="cursor:pointer;font-size:13px;">1 <i class="fa fa-fw fa-comments"></i> 
									<span>';
								}
							$return_v .='</p>
						</div>
					</div>
				</div>
			</div>
		</div>';
	}
	$return_v.="<div>
	<script>
		$(function () {
			$('[data-toggle=\"popover\"]').popover();
		});
	</script>";

	echo $return_v;die;
}

/**
 * 
 * @return void
 */
function load_more_review(){

	$rowClt = reviewDataQuery();
	$return_v = '';
	if( COUNT( $rowClt ) >0 ){
		$return_v = '
		<div class="row">
			<div class="col-md-12">';
				foreach($rowClt as $client) {
					$return_v .='
					<div class="col-md-4">
						<div class="testimonial" >
							<p>
								<span class="star-box">
									'.rv_star($client['review_rating'],'slider'.$client['id'].'_'.rand()).' 
								</span>
								<span itemprop="reviewBody">
									'.$client['review_text'].'
								</span>
							</p>
						</div>
						<div class="user">
							<div class="user-profile" style="background: #'.rand( 000000,999999 ).';">'.$client['reviewer_name'][0].'</div>
								<p class="overview"> 
									<span>
										<b class="u_name" itemprop="name">'.$client['reviewer_name'].'</b> 
									</span> 
									<br>';
									if( $client['reply'] != '' && $client['reply'] != null){ 
										$return_v .='
										<span data-toggle="popover" data-placement="top" title="'.$client['reviewer_name'].'" data-content="'.$client['reply'].'" 
											style="cursor:pointer;font-size:13px;">1 <i class="fa fa-fw fa-comments"></i> 
										<span>';
									}
								$return_v .='</p>
							</div>
						</div>';
				}
			$return_v .="</div>
		</div>
		<script>
			$(function () {
				$('[data-toggle=\"popover\"]').popover();
			});
		</script>";
	}

	echo $return_v;die;
}
?>