
<?php
if(isset($_POST['update_review']))
{
	$client = $_POST['client'];
	
	    $wp_client = $wpdb->prefix.'client';
		$crow = $wpdb->get_row("Select * FROM $wp_client where id='$client'", ARRAY_A);
		$cate = $crow['serviceType'];
				   
	$ClientName = $_POST['ClientName'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$rate = $_POST['rate'];
	$rv_comment = $_POST['rv_comment'];
	$rid = $_POST['rid'];
	
	 $wp_rvcomment = $wpdb->prefix.'rvcomment';
	
	
		$wpdb->query("UPDATE $wp_rvcomment SET client_id='$client', reviewer_name='$ClientName' ,review_title= '$ClientName', reviewer_email='$email' , review_phone='$phone', review_rating='$rate', review_text ='$rv_comment' , 	service_id ='$cate'  WHERE id='$rid'");
		
		$info = '<div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
									<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>
									Review Updated Successfully. </div>';
									echo $info;
}

 ?>


<div class="col-md-12">
	<div class="row m-r-0 m-t-20">
         <div class="col-md-12">
         	<div class="row">

         	 <h3>Edit Review</h3>
         	</div>
         	<div class="row bg-light-gray">


         		<div class="col-md-12 p-t-20 p-b-20">
				   <div class="row">
				   <?php if(isset($_GET['edit'])){ 
				   
				   $id = $_GET['edit'];
				   
				   $wp_rvcomment = $wpdb->prefix.'rvcomment';
				   $crow = $wpdb->get_row("Select * FROM $wp_rvcomment where id='$id'", ARRAY_A);
				   ?>
				   
				   <script>
				   window.onload = function() {
  var input = document.getElementById("myinputbox").focus();
}

				   </script>
				    <div class="col-md-12 col-sm-10">
		         	<form action="" method="POST">

						<div class="form-group row">
						  <label for="example-text-input" class="col-sm-2 col-form-label">Select Client * </label>
						  <div class="col-sm-10">
						  <select required class="form-control" name="client">
						  
						  <?php
                         $table_name = $wpdb->prefix.'client';
						  $rowCat = $wpdb->get_results("select * from $table_name where act='1' ", ARRAY_A);
						foreach($rowCat as $cat)
						{
							if($crow['client_id'] == $cat['id'])
							{
						  ?>
						   <option value="<?php echo $cat['id']; ?>" selected><?php echo $cat['clientName']; ?></option>
							
							<?php } else{ ?> 
							
							   <option value="<?php echo $cat['id']; ?>" ><?php echo $cat['clientName']; ?></option>
							<?php } } ?>
						</select>
						  </div>
						</div>
						<div class="form-group row">
						  <label for="example-text-input" class="col-sm-2 col-form-label">Name *</label>
						  <div class="col-sm-10">
						    <input class="form-control" value="<?php echo  $crow['reviewer_name']; ?>" required type="text"  name="ClientName" id="myinputbox"  placeholder="Name" >		
							<input  value="<?php echo $_GET['edit']; ?>"  type="hidden"  name="rid"  >
						  </div>
						</div>
						<div class="form-group row">
						  <label for="example-text-input" class="col-sm-2 col-form-label">Email *</label>
						  <div class="col-sm-10">
						    <input class="form-control"  value="<?php echo  $crow['reviewer_email']; ?>" required type="email"  name="email" id="example-text-input" placeholder="Email" >
						  </div>
						</div> 
						<div class="form-group row">
						  <label for="example-text-input" class="col-sm-2 col-form-label">Phone *</label>
						  <div class="col-sm-10">
						    <input class="form-control"   value="<?php echo  $crow['review_phone']; ?>" required type="text"  name="phone" id="example-text-input" placeholder="Email" >
						  </div>
						</div> 
						
						<div class="form-group row">
						  <label for="example-text-input" class="col-sm-2 col-form-label">Rate *</label>
						  <div class="col-sm-10">
						    <input class="form-control" step=".50" min="1" max="5" value="<?php echo  $crow['review_rating']; ?>" required type="number"  name="rate" >
						  </div>
						</div>  
						
						<div class="form-group row">
						  <label for="example-text-input" class="col-sm-2 col-form-label">Comment *</label>
						  <div class="col-sm-10">
						     <textarea class="form-control" rows="4" name="rv_comment" required placeholder="Leave review here...*"><?php echo  $crow['review_text']; ?></textarea>
						  </div>
						</div>  
						
						
						

						<div class="form-group row">
						  <label for="example-text-input" class="col-sm-2 col-form-label"></label>
						  <div class="col-sm-10">
						  <a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=Manage-Review" class="btn btn-info">Cancel</a>
						    <button type="submit" name="update_review" class="btn btn-success">Update</button>
						  
						  </div>
						  </div>

		                
		            </form>
                  </div>
				  
				   <?php  } ?>
                </div>



		      </div>
		    </div>
			<br>
			
			
			
		 </div>
	</div>    
</div>    
