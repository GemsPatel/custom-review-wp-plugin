<?php


$message = '';
if(isset($_POST["submit"])){


	$mailgun_api_key = $_POST["mailgun_api_key"];  
	$mailgun_domain = $_POST["mailgun_domain"];  
	$from_email = $_POST["from_email"]; 
	
	add_option('_mytrustedexpert_mailgun_api_key',$mailgun_api_key);
	add_option('_mytrustedexpert_mailgun_domain',$mailgun_domain);
	add_option('_mytrustedexpert_from_email',$from_email);
	
	update_option('_mytrustedexpert_mailgun_api_key',$mailgun_api_key);
	update_option('_mytrustedexpert_mailgun_domain',$mailgun_domain);
	update_option('_mytrustedexpert_from_email',$from_email);
	
	
	if($mailgun_api_key != '' && $mailgun_domain != ''){
	$message = '<div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
								<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>
								Setting details added successfully.</div>';
	}
	
	
}   
		
		
		
		

?>

<!-------<link rel="stylesheet" href="//fontawesome.io/assets/font-awesome/css/font-awesome.css">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">


<script src="../wp-content/plugins/theme-options/js/custom.js"></script>

<link rel="stylesheet" href="../wp-content/plugins/theme-options/css/style.css">

<script src="../wp-content/plugins/theme-options/js/bootstrap.min.js"></script>
<script src="../wp-content/plugins/theme-options/js/jquery.min.js"></script>

<link rel="stylesheet" href="../wp-content/plugins/theme-options/css/style.css">------->

<?php
if(isset($message)){
	echo $message;
}
?>
<div class="col-md-12">
	<div class="row m-r-0 m-t-20">
         <div class="col-md-12">
         	<div class="row">

         	 <h3>Settings</h3>
         	</div>
         	<div class="row bg-light-gray">


         		<div class="col-md-12 p-t-20 p-b-20">
				   <div class="row">
				   
				    <div class="col-md-12 col-sm-10">
		         	<form action="#" method="POST">

						<div class="form-group row">
						  <label for="example-text-input" class="col-sm-2 col-form-label">Mailgun API KEY *</label>
						  <div class="col-sm-10">
						    <input class="form-control" required type="text"  name="mailgun_api_key" value="<?php echo get_option('_mytrustedexpert_mailgun_api_key'); ?>"  placeholder="Mailgun API KEY" >
						  </div>
						</div>
						<div class="form-group row">
						  <label for="example-text-input" class="col-sm-2 col-form-label">Mailgun Domain *</label>
						  <div class="col-sm-10">
						    <input class="form-control"  required type="text" value="<?php echo get_option('_mytrustedexpert_mailgun_domain'); ?>"  name="mailgun_domain" id="example-text-input" placeholder="Mailgun Domain" >
						  </div>
						</div>  
						<div class="form-group row">
						  <label for="example-text-input" class="col-sm-2 col-form-label">From Email</label>
						  <div class="col-sm-10">
						    <input class="form-control" type="email" value="<?php echo get_option('_mytrustedexpert_from_email'); ?>"  name="from_email" id="example-text-input" placeholder="From Email"  >
						  </div>
						</div>
						
						

						<div class="form-group row">
						  <label for="example-text-input" class="col-sm-2 col-form-label"></label>
						  <div class="col-sm-10">
						    <button type="submit" name="submit" class="btn btn-success">Save</button>
						    
						  </div>
						</div>

		                
		            </form>
                  </div>
                </div>



		      </div>
		    </div>
			
			
		 </div>
	</div>    
</div>    
