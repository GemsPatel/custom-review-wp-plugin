<div style="padding-left:15px; padding-right:15px;">
	<div class="row">
		<div class="col-sm-12" style="padding:0;">
			<?php
			$sc = null;
			if(isset($_REQUEST['rv_name']))
			{
				global $wpdb;
				file_put_contents('sheet.txt', $_REQUEST['rv_name']);
				$rv_name = $_REQUEST['rv_name'];
				$client_id = $_REQUEST['rv_clientid'];
				$rv_category = $_REQUEST['rv_category'];
				$rv_phone = $_REQUEST['rv_phone'];
				$rv_email = $_REQUEST['rv_email'];
				$rv_gender = $_REQUEST['rv_gender'];
				$rv_comment = $_REQUEST['rv_comment'];
				$review_rating = $_REQUEST['review_rating'];
				$rv_post_id = $_REQUEST['rv_post_id'];
				$date_time = date('Y-m-d h:i:s');
				$wp_client = $wpdb->prefix.'client';
				$num = $wpdb->query("select * from $wp_client where id='$client_id' ");
				if($wpdb->num_rows > 0)
				{
					$row = $wpdb->get_row($wpdb->prepare( "SELECT * FROM $wp_client WHERE id='$client_id' " ), ARRAY_A );
					$rv_category =  $row['serviceType'];
					$to_email =  $row['email'];
					$wp_rvcomment = $wpdb->prefix.'rvcomment';
					$wpdb->query("select * from $wp_rvcomment where reviewer_email='$rv_email' AND client_id='$client_id'AND act='1' "); 
					if( $wpdb->num_rows == 0 )
					{
						$wpdb->query("INSERT INTO $wp_rvcomment SET date_time='$date_time' , reviewer_name='$rv_name' , reviewer_email='$rv_email' , review_title='$rv_name', review_rating='$review_rating', review_text='$rv_comment', client_id='$client_id', review_phone='$rv_phone', service_id='$rv_category', review_status='0', post_id='$rv_post_id'");
						$html_content = 'Hi,<br>';
						$html_content .= '<br>You just received a new review on My Trusted Expert. Pleases see below:';
						$html_content .= '<br><br><b>Rating:</b> '.$review_rating;
						$html_content .= '<br><b>Name:</b> '.$rv_name;
						$html_content .= '<br><b>Review:</b> '.$rv_comment;
						$html_content .= '<br><br>Regards,';
						$html_content .= '<br><b>My Trusted Expert Team</b>';
						$api_key='f5dd543999420f1e4816d50234f94826-e566273b-94e1f876';//paste the api key here
						$app_url='https://api.mailgun.net/v3/sandbox64b28936992444bdadafd1c648aff050.mailgun.org';//paste here the sub domain as test.testing.com
						$_mytrustedexpert_from_email = get_option('_mytrustedexpert_from_email');
						if($_mytrustedexpert_from_email == ''){
							$from_mail = get_option('admin_email');
						}else{
							$from_mail = $_mytrustedexpert_from_email;
						}
						$curl_post_data = [
							'from' =>'Mytrustedexpert.com <'.$from_mail.'>',
							'to' =>'User <'.$to_email.'>',
							'subject' =>'New Review',
							'replyTo' =>$from_mail,
							'Message-Id' =>"mytrustedexpert_email",
							'html' =>$html_content,
							'o:tracking-clicks' =>False
						];
						$api_key = get_option('_mytrustedexpert_mailgun_api_key');
						$app_url = get_option('_mytrustedexpert_mailgun_domain');
						if($api_key != '' && $app_url != ''){
							//$rev_mail = rev_mail($app_url,$api_key,$curl_post_data);
						}
						echo' <div id="rv_success" class="alert alert-success fade in alert-dismissable" style="margin-top:10px;">
						<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>
						<center>
						<strong>
						Your review has been recorded successfully and submitted for approval. Thanks!</strong>
						</center>
						</div>';
						$sc = 1;
					}
					else{
						echo'<div class="alert alert-danger fade in alert-dismissable" style="margin-top:18px;">
						<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>
						<center>
						<strong>
						Duplicate review not allowed.</strong>
						</center></div>';
					}
				}
   			}

		 	if( $sc == null ) {
				if($_GET['clientid'] != ''){
					$clientid = $_GET['clientid'];
					$wp_client = $wpdb->prefix.'client';
					$num = $wpdb->query("select * from $wp_client where id='$clientid' ");
					if($wpdb->num_rows > 0)
					{
						$row = $wpdb->get_row($wpdb->prepare( "SELECT * FROM $wp_client WHERE id='$clientid' " ), ARRAY_A );
						$category =  $row['serviceType'];
					}else{
						echo'<div class="alert alert-danger fade in alert-dismissable" style="margin-top:18px;">
						<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>
						<center>
							<strong>Client not found.</strong>
						</center></div>';
					}
				} ?>
			 	<div class="box box-primary" id="rv_form_div">
                	<div class="box-header with-border">
                  		<h3 class="box-title">Leave your review</h3>  
                	</div><!-- /.box-header -->
                
					<!-- form start -->
                	<form role="form" method="POST" action="" id="rv_review_form" >
                  		<div class="box-body">
				  			<?php
				  			if($_GET['clientid'] != ''){ 
								if($clientid != '' && $category != ''){
									echo "<input type='hidden' name='rv_clientid' value='".$clientid."'>";
									echo "<input type='hidden' name='rv_category' value='".$category."'>";
								}
				  			}else{
								if (array_key_exists("category",$rv_form_data)) {
					  				echo "<input type='hidden' name='rv_category' value='".$rv_form_data['category']."'>";
					  			}
					  			
								if (array_key_exists("clientid",$rv_form_data)){
									echo "<input type='hidden' name='rv_clientid' value='".$rv_form_data['clientid']."'>";
					  			}else{ ?>
									<div class="form-group">
										<select  name='rv_clientid' required class="form-control" >
											<option value="" >Select Person</option>
											<?php  $table_name1 = $wpdb->prefix.'client';
											$rowClt = $wpdb->get_results("select * from $table_name1 where act='1' ", ARRAY_A);
											foreach($rowClt as $client) {
												echo'<option value="'. $client['id'].'" >'. $client['clientName'].'</option>';
											}
											?>
										</select>
									</div>
		            			<?php } 
							}?>
                    		
							<div class="form-group">
								<input type="text" name='rv_name' required class="form-control"  placeholder="Enter name *"> 
								<input type="hidden" name='rv_post_id' required value="<?php echo get_the_ID(); ?>" >
                    		</div>  
							<div class="form-group">
                       			<input type="text" name='rv_phone' class="form-control" required  placeholder="Enter telephone number (not displayed) *">
                    		</div>
							<div class="form-group">
                       			<input type="email" name='rv_email' class="form-control" required  placeholder="Enter e-mail (not displayed) *">
                    		</div>
                   			<div class="form-group"  style="display:none">
                      			<div class="radio">
                        			<label>
                          				<input type="radio"  name="rv_gender" id="optionsRadios1" value="Male" checked>
                          				Male
                        			</label>
                      			</div>
                      			<div class="radio">
                        			<label>
                          				<input type="radio" name="rv_gender" id="optionsRadios2" value="Female">
                          				Female
									</label>
                      			</div>
                    		</div>
							<div class="form-group">
                  				<input type="text" name="review_rating" required class="kv-uni-star rating-loading" value="" data-size="lg" title="">
        						<br>
                    		</div>
							<div class="form-group">
                      			<textarea class="form-control" rows="4" name="rv_comment" required placeholder="Leave your review...*"></textarea>
                    		</div>
                  		</div><!-- /.box-body -->
                  		<div class="box-footer">
                    		<button type="submit" name="rv_submit" class="btn btn-primary">Submit Review</button>
                  		</div>
                	</form>
				</div><!-- /.box -->
			<?php } ?>
		</div>
	</div>
</div>

<script>
    jQuery( document ).ready( function( $ ) {
        $('.kv-gly-star').rating({
            containerClass: 'is-star'
        });
        $('.kv-gly-heart').rating({
            containerClass: 'is-heart',
            defaultCaption: '{rating} hearts',
            starCaptions: function (rating) {
                return rating == 1 ? 'One heart' : rating + ' hearts';
            },
            filledStar: '<i class="glyphicon glyphicon-heart"></i>',
            emptyStar: '<i class="glyphicon glyphicon-heart-empty"></i>'
        });
        $('.kv-fa').rating({
            theme: 'krajee-fa',
            filledStar: '<i class="fa fa-star"></i>',
            emptyStar: '<i class="fa fa-star-o"></i>'
        });
        $('.kv-fa-heart').rating({
            defaultCaption: '{rating} hearts',
            starCaptions: function (rating) {
                return rating == 1 ? 'One heart' : rating + ' hearts';
            },
            theme: 'krajee-fa',
            filledStar: '<i class="fa fa-heart"></i>',
            emptyStar: '<i class="fa fa-heart-o"></i>'
        });
        $('.kv-uni-star').rating({
            theme: 'krajee-uni',
            filledStar: '&#x2605;',
            emptyStar: '&#x2606;'
        });
        $('.kv-uni-rook').rating({
            theme: 'krajee-uni',
            defaultCaption: '{rating} rooks',
            starCaptions: function (rating) {
                return rating == 1 ? 'One rook' : rating + ' rooks';
            },
            filledStar: '&#9820;',
            emptyStar: '&#9814;'
        });
        $('.kv-svg').rating({
            theme: 'krajee-svg',
            filledStar: '<span class="krajee-icon krajee-icon-star"></span>',
            emptyStar: '<span class="krajee-icon krajee-icon-star"></span>'
        });
        $('.kv-svg-heart').rating({
            theme: 'krajee-svg',
            filledStar: '<span class="krajee-icon krajee-icon-heart"></span>',
            emptyStar: '<span class="krajee-icon krajee-icon-heart"></span>',
            defaultCaption: '{rating} hearts',
            starCaptions: function (rating) {
                return rating == 1 ? 'One heart' : rating + ' hearts';
            },
            containerClass: 'is-heart'
        });
        $('.rating,.kv-gly-star,.kv-gly-heart,.kv-uni-star,.kv-uni-rook,.kv-fa,.kv-fa-heart,.kv-svg,.kv-svg-heart').on(
			'change', function () {
				console.log('Rating selected: ' + $(this).val());
		});
    });
</script>
