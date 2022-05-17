<?php 
$return_v ='';
$return_v .='
<div class="" style=" padding-left:15px; padding-right:15px;">
	<div class="row">
		<div class="col-sm-12" style="padding:0;">
			<div id="" class="owl-carousel owl-theme">'; ?>
			<?php
			$dbTable = [
				'rvcomment' => $wpdb->prefix.'rvcomment', //table_name1
				'services' => $wpdb->prefix.'services', //table_name3
				'client' => $wpdb->prefix.'client', //table_client
			];

			$table_name1 = $wpdb->prefix.'rvcomment';
			$rowClt = $wpdb->get_results("select * from $table_name1 where review_status=1 AND act=1", ARRAY_A);
			$total_c = $wpdb->num_rows;
			$SrvName = 'Multiple Services';
			if (array_key_exists("category",$rv_view_data)){
				if( $rv_view_data['category'] == 0 ){
					$rowClt23 = $wpdb->get_results("select * from $table_name1 where review_status=1 AND act=1", ARRAY_A);	
					$total_c = $wpdb->num_rows;	
					$result = $wpdb->get_results("SELECT sum(review_rating) as total_rv FROM ".$table_name1." where review_status=1 AND act=1");
					$sum = $result[0]->total_rv;
					$average = 0;
					if($sum != 0 AND $total_c != 0) {
						$average = $sum/$total_c;
					}
					
					$rowClt = $wpdb->get_results("select * from $table_name1 where review_status=1 AND act=1 order by id DESC LIMIT 20", ARRAY_A);		
					// $table_name3 = $wpdb->prefix.'services';		
					// $rowSrv = $wpdb->get_row("select * from $table_name3 ", ARRAY_A);	
				} else{
					$rowClt23 = $wpdb->get_results("select * from $table_name1 where review_status=1 AND act=1 AND service_id='".$rv_view_data['category']."'", ARRAY_A);
					$total_c = $wpdb->num_rows;  
					echo "<pre>";
					print_r($total_c);die;
					$result = $wpdb->get_results("SELECT sum(review_rating) as total_rv FROM ".$table_name1." where review_status=1 AND act=1 AND service_id='".$rv_view_data['category']."'");
					$sum = $result[0]->total_rv;
					$average = 0;
					if($sum != 0 AND $total_c != 0) {
						$average = $sum/$total_c;
					}
					$rowClt = $wpdb->get_results("select * from $table_name1 where review_status=1 AND act=1 AND service_id='".$rv_view_data['category']."' order by id DESC LIMIT 20", ARRAY_A);
					$total_c = $wpdb->num_rows;
					$table_name3 = $wpdb->prefix.'services';
					$rowSrv = $wpdb->get_row("select * from $table_name3 where id='".$rv_view_data['category']."' ", ARRAY_A);
					$SrvName = $rowSrv['name'];
				}
			}

			if (array_key_exists("clientid",$rv_view_data)) {
				$rowClt = $wpdb->get_results("select * from $table_name1 where review_status=1 AND act=1 AND client_id='".$rv_view_data['clientid']."' order by id DESC ", ARRAY_A);
				$total_c = $wpdb->num_rows;
				$table_client = $wpdb->prefix.'client';
				$rowClient = $wpdb->get_row("select * from $table_client where id='".$rv_view_data['clientid']."' ", ARRAY_A);
				$result = $wpdb->get_results("SELECT sum(review_rating) as total_rv FROM ".$table_name1." where review_status='1' AND client_id='".$rv_view_data['clientid']."' AND act ='1' ");
				$GetRow = $wpdb->get_row("select * from $table_name1 where review_status=1 AND client_id='".$rv_view_data['clientid']."' AND act=1 order by id DESC LIMIT 1", ARRAY_A);
				$service_id1 = $GetRow['service_id'];
				$table_name3 = $wpdb->prefix.'services';
				$rowSrv = $wpdb->get_row("select * from $table_name3 where id='".$service_id1."' ", ARRAY_A);
				$SrvName = $rowSrv['name'];
				$sum = $result[0]->total_rv;
				$average = 0;
				if($sum !='0' AND $total_c !='0') {
					$average = $sum/$total_c;
				}
				$date = date_parse_from_format("Y-m-d h:i:s", $rowClient['datetime']);
				$time = mktime($date['hour'], $date['minute'], $date['second'], $date['month'], $date['day'], $date['year']);
				$add_date = date('M d, Y', $time);
				
				$return_v .='';
			}  

			$rr=0;
			foreach($rowClt as $client) {
				$rr++;
				$table_client = $wpdb->prefix.'client';
				$rowClient = $wpdb->get_row("select * from $table_client where id='".$client['client_id']."' ", ARRAY_A);
		
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
									<div class="user-profile"></div><p class="overview"> <span  >
										<b class="u_name" itemprop="name">'.$client['reviewer_name'].'</b> <span> <br>';
											
										if($client['reply'] !='' AND $client['reply'] !=null){ 
											$return_v .='
												<span data-toggle="popover" data-placement="top" title="Reply from expert" data-content="'.$client['reply'].'" 
													style="cursor:pointer;font-size:13px;" >1 
													<i class="fa fa-fw fa-comments"></i> 
													<a>Click to Read Experts Reply</a>
												<span>';
										}
										$return_v .='</p>
									</div>
								</div>
							</div>
						</div>';
			}
		$return_v .="</div>
	</div>";
	$return_v .='
	<script type="application/ld+json">[{"@context":"http:\/\/schema.org","@type":"Review","itemReviewed":{"@type":"LocalBusiness","name":"'.$rowClient['clientName'].'","url": "'.get_permalink(get_the_ID()).'",
	"image": "'.site_url().'/wp-content/plugins/theme-options/img/guarantee-shield-big.png", "priceRange": "££" },"reviewRating":{"@type":"aggregateRating","ratingValue":'.round($average,2).',"bestRating":5,"reviewCount":'.$total_c.'},"author":"Users"}]</script>
	';
$return_v .="<script>
   jQuery(document).ready(function($) {
	   var owl = $('.owl-carousel');
		owl.owlCarousel({
			items:3,
			loop:"; ?><?php if($rr <3){ $return_v .='false,'; } else{ $return_v .='true,'; }  ?>
			<?php $return_v .="margin:20,
			dots:true,
			autoplay:true,
			autoplayTimeout:5000,
			autoplayHoverPause:true,
			responsive : {
				// breakpoint from 0 up
				0 : {
					items:1
				},
				568 : {
					items:2,
				}
				,
				768 : {
					items:3,
				}
			}
		});
		  $('.user-profile').each( function(){
			   $(this).css('background-color', getRandomColor());
			   var fn = $(this).parent().find('.u_name').text();
			   res = $(this).text( fn.charAt(0) );
		  });
	});
	function getRandomColor() {
	  var letters = '0123456789ABCDEF';
	  var color = '#';
	  for (var i = 0; i < 6; i++) {
		color += letters[Math.floor(Math.random() * 16)];
	  }
	  return color;
	}
</script>
<script>
jQuery(document).ready(function($){
    $('[data-toggle=";?> <?php $return_v .='"popover"'; ?><?php $return_v .="]').popover();   
	$('.Stars').each(function(){
		var wd = $(this).data('width');
		//$(this+':after').css('width', wd+'%');
	});
});
</script>
"; ?>                                		                                                    		                            