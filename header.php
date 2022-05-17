<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) ?>assets/slider/owl.carousel.min.css" type="text/css"/>
<link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) ?>assets/star/css/star-rating.css" media="all" type="text/css"/>
<link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) ?>assets/star/css/themes/krajee-fa/theme.css" media="all" type="text/css"/>
<link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) ?>assets/star/css/themes/krajee-svg/theme.css" media="all" type="text/css"/>
<link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) ?>assets/star/css/themes/krajee-uni/theme.css" media="all" type="text/css"/>

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->

<?php
function wpse45377_enqueue_scripts() {
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery');
}
add_action( 'wp_enqueue_scripts', 'wpse45377_enqueue_scripts' );
?>

<script src="<?php echo plugin_dir_url( __FILE__ ) ?>assets/js/bootstrap.min.js"></script>

<script src="<?php echo plugin_dir_url( __FILE__ ) ?>assets/slider/owl.carousel.min.js"></script>

<script src="<?php echo plugin_dir_url( __FILE__ ) ?>assets/star/js/star-rating.js" type="text/javascript"></script>
<script src="<?php echo plugin_dir_url( __FILE__ ) ?>assets/star/js/themes/krajee-fa/theme.js" type="text/javascript"></script>
<script src="<?php echo plugin_dir_url( __FILE__ ) ?>assets/star/js/themes/krajee-svg/theme.js" type="text/javascript"></script>
<script src="<?php echo plugin_dir_url( __FILE__ ) ?>assets/star/js/themes/krajee-uni/theme.js" type="text/javascript"></script>
	
<style type="text/css">
		.box {
			position: relative;
			border-radius: 3px;
			background: #FFFFFF;
			border-top: 3px solid #D2D6DE;
			margin-bottom: 20px;
			width: 100%;
			box-shadow: 0 1px 1px rgb(0 0 0 / 10%);
		}
		.box-footer {
			border-top-left-radius: 0;
			border-top-right-radius: 0;
			border-bottom-right-radius: 3px;
			border-bottom-left-radius: 3px;
			border-top: 1px solid #f4f4f4;
			padding: 10px;
			background-color: #fff;
		}
		.rv_score {
			position: relative;
			overflow: hidden;
		}
		.rv_score-wrap {
		  display: inline-grid;
		  position: relative;
		  *height: 19px;
		}
		.rv_score .rv_stars-active {
			color: #e88b02;
			position: absolute;
			z-index: 10;
			overflow: hidden;
			white-space: nowrap;
			top: -2px;
		}
		.testimonial .rv_score .rv_stars-active {
			top:auto;
		}
		.rv_score .rv_stars-inactive {
			color: grey;
			position: relative;
		}
		.testimonial p:before{
			content: "\f10d";
			font: normal normal normal 14px/1 FontAwesome;
			font-size: 25px;
			left: 16px;
			position: absolute;
			color: #dadada;
		}
		.testimonial p{
			padding-left: 30px;
			line-height: 1.8;
			min-height: 150px;
			max-height: 150px;
			overflow-y: auto;
			margin-right: 7px;
			padding-right:5px;
		}
		.testimonial{
			padding: 15px;
			background: #f9f9f9;
			border: 1px solid #f1f1f1;
			border-radius: 4px;
		}
		.user .user-profile{
			position: absolute;
			width: 50px;
			height: 50px;
			text-align: center;
			background: #ccc;
			margin: 10px;
			border-radius: 50%;
			font-size: 23px;
			line-height: 2;
			color:#fff;
		}
		.c-gold {
			color: #e88b02;
			*font-size: 17px;
		}

		.user .overview{
			margin-left: 60px;
			min-height: 60px;
			padding-top: 13px;
			padding-left: 15px;
			margin-top: 10px;
		}
		
		.testimonial p::-webkit-scrollbar-track
		{
			-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.15);
			border-radius: 10px;
			background-color: #F5F5F5;
		}

		.testimonial p::-webkit-scrollbar
		{
			width: 6px;
			background-color: #F5F5F5;
		}

		.testimonial p::-webkit-scrollbar-thumb
		{
			border-radius: 10px;
			-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
			background-color: #f3f3f3;
		}
		
		.info-box{
			border-top: 3px solid #f46d24;
			box-shadow: 1px 2px 1px 1px rgba(0,0,0,0.1);
		}
		.info-box {
			display: block;
			min-height: 72px;
			background: #fff;
			width: 100%;
			box-shadow: 1px 3px 7px 1px rgba(0, 0, 0, 0.21);
			border-radius: 2px;
			margin-bottom: 13px;
		}
		.info-box-icon {
			border-top-left-radius: 2px;
			border-top-right-radius: 0;
			border-bottom-right-radius: 0;
			border-bottom-left-radius: 2px;
			display: block;
			float: left;
			height: 74px;
			width: 70px;
			text-align: center;
			font-size: 45px;
			line-height: 64px;
			background: rgb(255, 255, 255);
		}
		.info-box-content {
			padding: 5px 10px;
			margin-left: 68px;
			text-align:left;
		}
		
		.owl-theme .owl-dots,.owl-theme .owl-nav{text-align:center;-webkit-tap-highlight-color:transparent}.owl-theme .owl-nav{margin-top:10px}.owl-theme .owl-nav [class*=owl-]{color:#FFF;font-size:14px;margin:5px;padding:4px 7px;background:#D6D6D6;display:inline-block;cursor:pointer;border-radius:3px}.owl-theme .owl-nav [class*=owl-]:hover{background:#869791;color:#FFF;text-decoration:none}.owl-theme .owl-nav .disabled{opacity:.5;cursor:default}.owl-theme .owl-nav.disabled+.owl-dots{margin-top:10px}.owl-theme .owl-dots .owl-dot{display:inline-block;zoom:1}.owl-theme .owl-dots .owl-dot span{width:10px;height:10px;margin:5px 7px;background:#D6D6D6;display:block;-webkit-backface-visibility:visible;transition:opacity .2s ease;border-radius:30px}.owl-theme .owl-dots .owl-dot.active span,.owl-theme .owl-dots .owl-dot:hover span{background:#869791}
		
		.popover-content {
			padding: 9px 14px;
			overflow-y: scroll;
			height: 200px;
		}
		.popover.top {
			margin-top: 3px;
		}
		.popover{
			width:100%;
			left:0px !important;
		}
		.arrow{
			left:34% !important;
		}
		.testimonial .star-box {
			display:block;
		}
		
		.box_rating_wrapper{
			display:inline-block;
			width: 100%;
		}
		.box_rating_wrapper b{ float:left;margin-right:10px;}
		.box_rating_wrapper .Stars{ float:left; margin-top: 4px;}
	</style>