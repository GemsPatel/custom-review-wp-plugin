<?php
$return = ''; 
$table_name1 = $wpdb->prefix.'rvcomment';
$rowClt = $wpdb->get_results("select * from $table_name1 where review_status=1 AND act=1 ", ARRAY_A);
$total = $wpdb->num_rows;
$result = $wpdb->get_results("SELECT sum(review_rating) as total_rv FROM ".$table_name1." where review_status=1 AND act=1");
$sum = $result[0]->total_rv;
$average = 0;
if($sum && $total ) 
	$average = round( ( $sum/$total ), 2 );

$return .= '<div class="col-md-12"><div class="row">
	<div class="rv_text over_all_rating_text" style="font-weight:600;">
		Overall Rating: '.rv_star($average,'overall-rating-text-'.rand(111, 999)).' ('.$average.') Based on '.$total.' reviews 
		</div>
	</div>
</div>';
?>
