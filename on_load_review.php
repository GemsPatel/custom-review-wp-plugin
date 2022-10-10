<?php 
global $wp_query;
$pageLimit = 9;
?>
<style>
	:root {
		--star-color: #e88b02;
		--star-background: #e88b02;
	}
	.Stars {
		--percent: calc(var(--rating) / 5 * 100%);
		display: inline-block;
		font-family: Times;  
		line-height: 1;
		position: relative;
		font-weight: 800;
		font-size: 115%;
		*transform: scale(1.3);
		transform-origin: center;
	}
	.Stars::before {
		content: "☆☆☆☆☆";
		letter-spacing: 0px;
		background: linear-gradient(90deg, var(--star-background) var(--percent), var(--star-color) var(--percent));
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
		font-weight:100;
		white-space: nowrap;
	}
	.Stars::after {
		content: "★★★★★";
		letter-spacing: 0px;
		background: linear-gradient(90deg, var(--star-background) var(--percent), var(--star-color) var(--percent));
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
		position: absolute;
		left: 0;
		font-weight:100;
		white-space: nowrap;
	}
	.owl-nav button {
	position: absolute;
	top: 50%;
	background-color: #000;
	color: #fff;
	margin: 0;
	transition: all 0.3s ease-in-out;
	}
	.owl-nav button.owl-prev {
	left: 0;
	}
	.owl-nav button.owl-next {
	right: 0;
	}
	.owl-dots {
	text-align: center;
	padding-top: 15px;
	}
	.owl-dots button.owl-dot {
	width: 10px;
	height: 10px;
	border-radius: 50%;
	display: inline-block;
	background: #ccc;
	margin: 0 3px;
	}
	.owl-dots button.owl-dot.active {
	background-color: #000;
	}
	.owl-dots button.owl-dot:focus {
	outline: none;
	}
	.owl-nav button {
		position: absolute;
		top: 50%;
		transform: translateY(-50%);
		background: rgba(255, 255, 255, 0.38) !important;
	}
	.owl-nav button:focus {
		outline: none;
	}
	.owl-prev, .owl-next {
		display: none;
	}

	@media only screen and (min-width: 1168px) {
		.entry .entry-content > *,
		.entry .entry-summary > * {
			max-width: 100vw !important;
		}
	}
</style>
<script type="application/json" src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.27.2/axios.min.js"></script>
<script>
	var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';
</script>
<input type="hidden" value="<?php echo $pageLimit;?>" id="per_page">
<input type="hidden" value="<?php echo $pageLimit;?>" id="offset">
<?php
$dbTable = [
	'rvcomment' => $wpdb->prefix.'rvcomment', //table_name1
	'services' => $wpdb->prefix.'services', //table_name3
	'client' => $wpdb->prefix.'client', //table_client
];
$rowClient['clientName'] = '';
$where = "";
if (array_key_exists("category",$rv_view_data)){
	if( $rv_view_data['category'] == 0 ){
		$rowClt23 = $wpdb->get_results("select * from ".$dbTable['rvcomment']." where review_status=1 AND act=1", ARRAY_A);	
		$total_c = $wpdb->num_rows;	
		$rowClt = $wpdb->get_results("select * from ".$dbTable['rvcomment']." where review_status=1 AND act=1 order by id DESC LIMIT ".$pageLimit, ARRAY_A);
	} else{
		$rowClt23 = $wpdb->get_results("select * from ".$dbTable['rvcomment']." where review_status=1 AND act=1 AND service_id='".$rv_view_data['category']."'", ARRAY_A);
		$total_c = $wpdb->num_rows;
		$where = "AND service_id='".$rv_view_data['category']."'";
		$rowClt = $wpdb->get_results("select * from ".$dbTable['rvcomment']." where review_status=1 AND act=1 AND service_id='".$rv_view_data['category']."' order by id DESC LIMIT ".$pageLimit, ARRAY_A);
		// $total_c = $wpdb->num_rows;
		$rowSrv = $wpdb->get_row("select * from ".$dbTable['services']." where id='".$rv_view_data['category']."' ", ARRAY_A);
		$SrvName = $rowSrv['name'];
	}
} else if (array_key_exists("clientid",$rv_view_data)) {
	$rowClt = $wpdb->get_results("select * from ".$dbTable['rvcomment']." where review_status=1 AND act=1 AND client_id='".$rv_view_data['clientid']."' order by id DESC ", ARRAY_A);
	$total_c = $wpdb->num_rows;
	$rowClient = $wpdb->get_row("select * from ".$dbTable['client']." where id='".$rv_view_data['clientid']."' ", ARRAY_A);
	$where = "AND client_id='".$rv_view_data['clientid']."'";
	$GetRow = $wpdb->get_row("select * from ".$dbTable['rvcomment']." where review_status=1 AND client_id='".$rv_view_data['clientid']."' AND act=1 order by id DESC LIMIT 1", ARRAY_A);
	$rowSrv = $wpdb->get_row("select * from ".$dbTable['services']." where id='".$GetRow['service_id']."' ", ARRAY_A);
	$SrvName = $rowSrv['name'];
	$date = date_parse_from_format("Y-m-d h:i:s", $rowClient['datetime']);
	$time = mktime($date['hour'], $date['minute'], $date['second'], $date['month'], $date['day'], $date['year']);
	$add_date = date('M d, Y', $time);
} else {
	$rowClt = $wpdb->get_results("select * from ".$dbTable['rvcomment']." where review_status=1 AND act=1", ARRAY_A);
	$total_c = $wpdb->num_rows;
	$SrvName = 'Multiple Services';
}

$result = $wpdb->get_results("SELECT sum(review_rating) as total_rv FROM ".$dbTable['rvcomment']." where review_status=1 AND act=1 ".$where );
$sum = $result[0]->total_rv;
$average = 0;
if($sum && $total_c ) {
	$average = ceil( $sum/$total_c );
}

$return_v .='<div class="row">
				<div class="col-sm-12">
					<div class="owl-slider">
						<div id="load_all_slider_review" class="owl-carousel">';
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
														if( $client['reply'] != '' && $client['reply'] != null){ 
															$return_v .='
															<span  data-toggle="popover" data-placement="top" title="'.$client['reviewer_name'].'" data-content="'.$client['reply'].'"
																style="cursor:pointer;font-size:13px;">1 <i class="fa fa-fw fa-comments"></i> 
															<span>';
														} 
													$return_v .='</p>
												</div>
											</div>
										</div>
									</div>';
							}
						$return_v .='</div>
					</div>
				</div>
			</div>';
$return_v .='<div class="load-all-review" style="margin-top: 25px;"></div>';

$return_v .='<div class="row display-load-more-btn">
				<div class="col-md-12 text-center" style="margin-top: 15px;">
					<button type="button" class="btn btn-primary" id="load-more">Load More</button>
				</div>
			</div>';

$return_v .='
<script type="application/ld+json">[{"@context":"http:\/\/schema.org","@type":"Review","itemReviewed":{"@type":"LocalBusiness","name":"'.$rowClient['clientName'].'","url": "'.get_permalink(get_the_ID()).'",
"image": "'.site_url().'/wp-content/plugins/theme-options/img/guarantee-shield-big.png", "priceRange": "££" },
"reviewRating":{"@type":"aggregateRating","ratingValue":'.round($average,2).',"bestRating":5,"reviewCount":'.$total_c.'},"author":"Users"}]</script>
';

$return_v .="<script>
	
	jQuery('#load-more').click(function() {
		loadMoreReview();		
	});

	function loadSliderReview(){
		var perpage = $('#per_page').val();
		var offset = $('#offset').val();
		var data = {
			'action' : 'load_slider_review', // the name of your PHP function!
			'perpage' : perpage,
			'offset' : offset,
			'rv_view_data' : ".json_encode($rv_view_data)."
		};
		
		jQuery.post(ajaxurl, data, function(response) {
			if( response == '' ){
				$('.display-load-more-btn').addClass('hide');
			}else{
				offset = ( parseInt( offset ) + parseInt( perpage ) );
				$('#offset').val( offset );
				$('#load_all_slider_review').html(response);
			}
		});
	}

	function loadMoreReview(){
		var perpage = $('#per_page').val();
		var offset = $('#offset').val();
		var data = {
			'action' : 'load_more_review', // the name of your PHP function!
			'perpage' : perpage,
			'offset' : offset,
			'rv_view_data' : ".json_encode($rv_view_data)."
		};
		
		jQuery.post(ajaxurl, data, function(response) {
			if( response == '' ){
				$('.display-load-more-btn').addClass('hide');
			}else{
				offset = ( parseInt( offset ) + parseInt( perpage ) );
				$('#offset').val( offset );
				$('.load-all-review').append(response);
			}
		});
	}
	
	jQuery(document).ready(function($) {
		
		// $('[data-toggle=\"popover\"]').popover({
		// 	html: true,
		// 	trigger: 'hover',
		// 	// title: $(this).attr('data-title')+' <a class=\"close\" href=\"#\");\">&times;</a>',
		// });

		// loadSliderReview();

		$('.user-profile').each( function(){
			$(this).css('background-color', getRandomColor());
			var fn = $(this).parent().find('.u_name').text();
			res = $(this).text( fn.charAt(0) );
		});

		// $('.Stars').each(function(){
		// 	var wd = $(this).data('width');
		// 	$(this+':after').css('width', wd+'%');
		// });
	});

	jQuery('#load_all_slider_review').owlCarousel({
		autoplayHoverPause:true,
		autoplay: true,
		rewind: true, /* use rewind if you don't want loop */
		margin: 20,
		 /*
		animateOut: 'fadeOut',
		animateIn: 'fadeIn',
		*/
		responsiveClass: true,
		autoHeight: true,
		autoplayTimeout: 7000,
		smartSpeed: 800,
		nav: true,
		responsive: {
		  0: {
			items: 1
		  },
	  
		  600: {
			items: 3
		  },
	  
		  1024: {
			items: 4
		  },
	  
		  1366: {
			items: 4
		  }
		}
	});

	function getRandomColor() {
		var letters = '0123456789ABCDEF';
		var color = '#';
		for (var i = 0; i < 6; i++) {
			color += letters[Math.floor(Math.random() * 16)];
		}
		return color;
	}

	$(function () {
		// $('[data-toggle=\"popover\"]').popover()
		$('.overview').on(\"click\", function(){
			$('[data-toggle=\"popover\"]').popover('show');
		});
	});
</script>
"; ?>