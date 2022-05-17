<?php if (isset($_POST["submit"]))
{
    $ClientName = $_POST["CatName"];
    $wp_client = $wpdb->prefix . 'services';
    $row1 = $wpdb->query("select * from $wp_client where name='$ClientName' AND act='1'");
    if ($wpdb->num_rows == '0')
    {
        $slug = str_replace(" ", "", $ClientName);
        $wpdb->query("INSERT INTO $wp_client SET name='$ClientName' , slug='$slug'");
        $info = '<div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">								<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>								Category Added Successfully. </div>';
    }
    else
    {
        $info = '<div class="alert alert-danger fade in alert-dismissable" style="margin-top:18px;">								<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>								 Duplicate Name not allowed.</div>';
    }
    echo $info;
}
if (isset($_POST["update"]))
{
    $CatName = $_POST["CatName"];
    $slug = str_replace(" ", "", $CatName);
    $cid = $_POST["cid"];
    $CatNameOld = $_POST["CatNameOld"];
    if ($CatName != $CatNameOld)
    {
        $wp_services = $wpdb->prefix . 'services';
        $row1 = $wpdb->query("select * from $wp_services where name='$CatName' ");
        if ($wpdb->num_rows == '0')
        {
            $wpdb->query("UPDATE $wp_services SET name='$CatName' , slug='$slug' WHERE id='$cid'");
            $info = '<div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">									<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>									Category Updated Successfully. </div>';
        }
        else
        {
            $info = '<div class="alert alert-danger fade in alert-dismissable" style="margin-top:18px;">									<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>									 Duplicate Name not allowed.</div>';
        }
    }
    else
    {
        $wpdb->query("UPDATE $wp_services SET name='$CatName' , slug='$slug' WHERE id='$cid'");
        $info = '<div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">									<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>									Category Updated Successfully. </div>';
    }
    echo $info;
}
if (isset($_GET['delete']))
{
    $del = $_GET['delete'];
    $wp_client = $wpdb->prefix . 'services';
    $wpdb->query("UPDATE $wp_client SET act='0' WHERE id='$del'");
    echo '<div class="alert alert-danger fade in alert-dismissable" style="margin-top:18px;">									<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>									 Category deleted successfully.</div>';
} ?>

<div class="col-md-12">
   <div class="row m-r-0 m-t-20">
      <div class="col-md-12">
         <div class="row">
            <h3>Manage Category</h3>
         </div>
         <div class="row bg-light-gray">
            <div class="col-md-12 p-t-20 p-b-20">
               <div class="row">
                  <?php if (isset($_GET['edit']))
{
    $id = $_GET['edit'];
    $wp_client = $wpdb->prefix . 'services';
    $crow = $wpdb->get_row("Select * FROM $wp_client where id='$id'", ARRAY_A); ?>				   				   <script>				   window.onload = function() {  var input = document.getElementById("myinputbox").focus();}				   </script>				    
                  <div class="col-md-12 col-sm-10">
                     <form action="<?php echo site_url(); ?>/wp-admin/admin.php?page=Manage-Category" method="POST">
                        <div class="form-group row">
                           <label for="example-text-input" class="col-sm-2 col-form-label">Category Name1 *</label>						  
                           <div class="col-sm-10">						    <input class="form-control" value="<?php echo $crow['name']; ?>" required type="text"  name="CatName" id="myinputbox"  placeholder="Category name" >									<input  value="<?php echo $crow['name']; ?>"  type="hidden"  name="CatNameOld"  >							<input  value="<?php echo $crow['id']; ?>"  type="hidden"  name="cid"  >						  </div>
                        </div>
                        <div class="form-group row">
                           <label for="example-text-input" class="col-sm-2 col-form-label"></label>						  
                           <div class="col-sm-10">						  <a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=Manage-Category" class="btn btn-info">Cancel</a>						    <button type="submit" name="update" class="btn btn-success">Update</button>						  						  </div>
                        </div>
                     </form>
                  </div>
                  <?php
}
else
{ ?>				    
                  <div class="col-md-12 col-sm-10">
                     <form action="#" method="POST">
                        <div class="form-group row">
                           <label for="example-text-input" class="col-sm-2 col-form-label">Category Name *</label>						  
                           <div class="col-sm-10">						    <input class="form-control" required type="text"  name="CatName"  placeholder="Category name" >						  </div>
                        </div>
                        <div class="form-group row">
                           <label for="example-text-input" class="col-sm-2 col-form-label"></label>						  
                           <div class="col-sm-10">						    <button type="submit" name="submit" class="btn btn-success">Save</button>						    						  </div>
                        </div>
                     </form>
                  </div>
                  <?php
} ?>                
               </div>
            </div>
         </div>
         <br>			
         <div class="row bg-light-gray">
            <div class="col-md-12 p-t-20 p-b-20">
               <div class="row table-responsive">
                  <table id="example2" class="table table-bordered table-hover">
                     <thead>
                        <tr>
                           <th>S.No.</th>
                           <th>Category Name</th>
                           <th>Short Code</th>
                           <th style="width: 70px;">Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td>0</td>
                           <td>				 All Reviews                  </td>
                           <td><?php echo '[REVCUSTOM category="0"]'; ?><br><?php echo '[REVCUSTOMBADGE category="0"]'; ?> <br><?php echo '[REVCUSTOMTEXT category="0"]'; ?></td>
                           <td></td>
                        </tr>
                        <?php $i = 1;
$table_name1 = $wpdb->prefix . 'services';
$rowClt = $wpdb->get_results("select * from $table_name1 where act='1' ", ARRAY_A);
foreach ($rowClt as $client)
{ ?>                
                        <tr>
                           <td><?php echo $i++; ?></td>
                           <td>				  <?php echo $client['name']; ?>                  </td>
                           <td><?php echo '[REVCUSTOM category="' . $client['id'] . '"]'; ?><br><?php echo '[REVCUSTOMBADGE category="' . $client['id'] . '"]'; ?> <br><?php echo '[REVCUSTOMTEXT category="' . $client['id'] . '"]'; ?></td>
                           <td><a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=Manage-Category&edit=<?php echo $client['id']; ?>" class="btn btn-info"><i class="fa fa-fw fa-edit"></i></a> 				  <a onclick="return confirm('Are you sure, you want to delete this category');" href="<?php echo site_url(); ?>/wp-admin/admin.php?page=Manage-Category&delete=<?php echo $client['id']; ?>"  class="btn btn-danger"><i class="fa fa-fw fa-trash"></i></a></td>
                        </tr>
                        <?php
} ?>                </tfoot>              
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>


<script>  
jQuery(function ($) {    
	$('#example1').DataTable()    
	$('#example2').DataTable({      
		'paging'      : true,      
		'lengthChange': false,      
		'searching'   : true,      
		'ordering'    : true,      
		'info'        : true,      
		'autoWidth'   : false    
	})  
})
</script>
