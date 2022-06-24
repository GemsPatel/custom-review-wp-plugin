<?php 
global $wp_query;
?>
<script type="application/json" src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.27.2/axios.min.js"></script>
<script>
	var ajaxurl = '<?php echo admin_url('admin-ajax.php');?>';
</script>
<input type="hidden" value="12" id="per_page">
<input type="hidden" value="0" id="offset">
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
	}else{
		$rowClt23 = $wpdb->get_results("select * from ".$dbTable['rvcomment']." where review_status=1 AND act=1 AND service_id='".$rv_view_data['category']."'", ARRAY_A);
		$total_c = $wpdb->num_rows;
		$where = "AND service_id='".$rv_view_data['category']."'";
	}
} else if (array_key_exists("clientid",$rv_view_data)) {
	$rowClt = $wpdb->get_results("select * from ".$dbTable['rvcomment']." where review_status=1 AND act=1 AND client_id='".$rv_view_data['clientid']."' order by id DESC ", ARRAY_A);
	$total_c = $wpdb->num_rows;
	$rowClient = $wpdb->get_row("select * from ".$dbTable['client']." where id='".$rv_view_data['clientid']."' ", ARRAY_A);
	$where = "AND client_id='".$rv_view_data['clientid']."'";
	
} else {
	$rowClt = $wpdb->get_results("select * from ".$dbTable['rvcomment']." where review_status=1 AND act=1", ARRAY_A);
	$total_c = $wpdb->num_rows;
	$SrvName = 'Multiple Services';
}

$result = $wpdb->get_results("SELECT sum(review_rating) as total_rv FROM ".$dbTable['rvcomment']." where review_status=1 AND act=1 ".$where );
$sum = $result[0]->total_rv;
$average = 0;
if($sum !='0' AND $total_c !='0') {
	$average = $sum/$total_c;
}

$return_v .='<div class="load-all-review"></div>';

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
		loadMoreReview();
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

	$('.Stars').each(function(){
		var wd = $(this).data('width');
		//$(this+':after').css('width', wd+'%');
	});
	
	$(function () {
		$('[data-toggle=\"popover\"]').popover()
	});
</script>
"; ?>