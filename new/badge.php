<?php 
$return_bedge =''; 
$rvcomment = $wpdb->prefix.'rvcomment';
$rowClt = $wpdb->get_results("select * from $rvcomment where review_status=1 AND act=1 ", ARRAY_A);
$total = $wpdb->num_rows;
$result = $wpdb->get_results("SELECT sum(review_rating) as total_rv FROM ".$rvcomment." where review_status=1 AND act=1");
$sum = $result[0]->total_rv;
if (array_key_exists("category",$rv_badge_data))
{						  						   
	if($rv_badge_data['category'] == 0)	
	{																
		$rowClt = $wpdb->get_results("select * from $rvcomment where review_status=1 AND act=1", ARRAY_A);					  
		$total = $wpdb->num_rows;	
		$result = $wpdb->get_results("SELECT sum(review_rating) as total_rv FROM ".$rvcomment." where review_status=1 AND act=1");	
		$sum = $result[0]->total_rv;	
	}else{							
		$rowClt = $wpdb->get_results("select * from $rvcomment where review_status=1 AND service_id='".$rv_badge_data['category']."' AND act=1", ARRAY_A);	
		$total = $wpdb->num_rows;					  
		$result = $wpdb->get_results("SELECT sum(review_rating) as total_rv FROM ".$rvcomment." where review_status=1 AND service_id='".$rv_badge_data['category']."' AND act=1");
		$sum = $result[0]->total_rv;														
	}
}
if (array_key_exists("clientid",$rv_badge_data))
{
	$rowClt = $wpdb->get_results("select * from $rvcomment where review_status=1 AND client_id='".$rv_badge_data['clientid']."' AND act=1", ARRAY_A);
	$total = $wpdb->num_rows;
	$result = $wpdb->get_results("SELECT sum(review_rating) as total_rv FROM ".$rvcomment." where review_status=1 AND client_id='".$rv_badge_data['clientid']."' AND act=1");
	$sum = $result[0]->total_rv;
}  
$average = 0;
if($sum !='0' AND $total !='0') { $average = $sum/$total; }
$star = 0;
if($average >= 0.01 && $average <= 0.25 ){ $star = 0.25; }
if($average >= 0.26 && $average <= 0.50 ){ $star = 0.5; }
if($average >= 0.51 && $average <= 0.75 ){ $star = 0.75; }
if($average >= 0.76 && $average <= 1 ){ $star = 1; }
if($average >= 1.01 && $average <= 1.25 ){ $star = 1.25; }
if($average >= 1.26 && $average <= 1.50 ){ $star = 1.5; }
if($average >= 1.51 && $average <= 1.75 ){ $star = 1.75; }
if($average >= 1.76 && $average <= 2 ){ $star = 2; }
if($average >= 2.01 && $average <= 2.25 ){ $star = 2.25; }
if($average >= 2.26 && $average <= 2.50 ){ $star = 2.5; }
if($average >= 2.51 && $average <= 2.75 ){ $star = 2.75; }
if($average >= 2.76 && $average <= 3 ){ $star = 3; }
if($average >= 3.01 && $average <= 3.25 ){ $star = 3.25; }
if($average >= 3.26 && $average <= 3.50 ){ $star = 3.5; }
if($average >= 3.51 && $average <= 3.75 ){ $star = 3.75; }
if($average >= 3.76 && $average <= 4 ){ $star = 4; }
if($average >= 4.01 && $average <= 4.25 ){ $star = 4.25; }
if($average >= 4.26 && $average <= 4.50 ){ $star = 4.5; }
if($average >= 4.51 && $average <= 4.75 ){ $star = 4.75; }
if($average >= 4.76 && $average <= 5 ){  $star = 5; }
$return_bedge .='<div class="" style="
	 padding-left:15px;
	 padding-right:15px;
 ">
	<div class="row">
		<div class="col-sm-12" style="padding:0;">
		<div class="">
              <div class="info-box" style="max-width:280px;">
                <span class="info-box-icon bg-white"><img src="'.plugin_dir_url( __FILE__ ).'assets/star/img/sheild.png"></span>
                <div class="info-box-content">
                  <span class="info-box-text" style="color: #7a7a7a;"><b>Overall Rating</b></span>
				    <span class="box_rating_wrapper"><b style="color: #e88b02;font-size: 17px;" >'.number_format($average,2).'&nbsp;</b>
											    '.rv_star($star,'overallbadge_'.rand()).' 
												 &nbsp;
											  </span>
                  <span  style="color: #7a7a7a;" class="info-box-text">Based on '.$total.' Reviews</span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div>
			</div>
		</div>
	</div>';                               		                                                    		                            