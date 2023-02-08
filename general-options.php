<?php
if (isset($_POST["submit"]))
{
   $catID = $_POST["category"];
   $ClientName = $_POST["ClientName"];
   $email = $_POST["email"];
   $wp_client = $wpdb->prefix . 'client';
   $row1 = $wpdb->query("select * from $wp_client where ClientName='$ClientName' ");
   if ($wpdb->num_rows == '0')
   {
      $wpdb->query("INSERT INTO $wp_client SET clientName='$ClientName' , serviceType='$catID' , email='$email'  ");
      $info = '<div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>
               Client Added Successfully. </div>';
   }
   else
   {
      $info = '<div class="alert alert-danger fade in alert-dismissable" style="margin-top:18px;">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>
               Duplicate Name not allowed.</div>';
   }
   echo $info;
}

if (isset($_POST["update"]))
{
    $catID = $_POST["category"];
    $ClientName = $_POST["ClientName"];
    $ClientNameOld = $_POST["ClientNameOld"];
    $cid = $_POST["cid"];
    $email = $_POST["email"];
    if ($ClientName != $ClientNameOld)
    {
        $wp_client = $wpdb->prefix . 'client';
        $row1 = $wpdb->query("select * from $wp_client where ClientName='$ClientName' AND act='1'");
        if ($wpdb->num_rows == '0')
        {
            $wpdb->query("UPDATE $wp_client SET clientName='$ClientName' , serviceType='$catID' , email='$email'  WHERE id='$cid'");
            $info = '<div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>
                     Client Details Update Successfully. </div>';
        }
        else
        {
            $info = '<div class="alert alert-danger fade in alert-dismissable" style="margin-top:18px;">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>
                     Duplicate Name not allowed.</div>';
        }
    }
    else
    {
        $wp_client = $wpdb->prefix . 'client';
        $wpdb->query("UPDATE $wp_client SET  serviceType='$catID' , email='$email'  WHERE id='$cid'");
        $info = '<div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
                     <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>
                  Client Details Update Successfullyy. </div>';
    }
    echo $info;
}
if (isset($_GET['delete']))
{
    $del = $_GET['delete'];
    $wp_client = $wpdb->prefix . 'client';
    $wp_rvcomment = $wpdb->prefix . 'rvcomment';
    $wpdb->query("DELETE FROM $wp_client WHERE id='$del'");
    $wpdb->query("DELETE FROM $wp_rvcomment WHERE client_id='$del'");
    echo '<div class="alert alert-danger fade in alert-dismissable" style="margin-top:18px;">
               <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>
            Client deleted successfully.</div>';
}
?>
<div class="col-md-12">
   <div class="row m-r-0 m-t-20">
      <div class="col-md-12">
         <div class="row">
            <h3>Manage Clients</h3>
         </div>
         <div class="row bg-light-gray">
            <div class="col-md-12 p-t-20 p-b-20">
               <div class="row">
                  <?php if (isset($_GET['edit']))
                  {
                     $id = $_GET['edit'];
                     $wp_client = $wpdb->prefix . 'client';
                     $crow = $wpdb->get_row("Select * FROM $wp_client where id='$id'", ARRAY_A);
                     ?>
                     <script>
                        window.onload = function() {
                           var input = document.getElementById("myinputbox").focus();
                        }
                     </script>
                     <div class="col-md-12 col-sm-10">
                        <form action="<?php echo site_url(); ?>/wp-admin/admin.php?page=Manage-Client" method="POST">
                           <div class="form-group row">
                              <label for="example-text-input" class="col-sm-2 col-form-label">Select Category * </label>
                              <div class="col-sm-10">
                                 <select required class="form-control" name="category">
                                    <?php
                                    $table_name = $wpdb->prefix . 'services';
                                    $rowCat = $wpdb->get_results("select * from $table_name where act='1' ", ARRAY_A);
                                    foreach ($rowCat as $cat)
                                    {
                                       if ($crow['serviceType'] == $cat['id'])
                                       { ?>
                                          <option value="<?php echo $cat['id']; ?>" selected><?php echo $cat['name']; ?></option>
                                          <?php
                                       }
                                       else
                                       { ?> 
                                          <option value="<?php echo $cat['id']; ?>" ><?php echo $cat['name']; ?></option>
                                          <?php
                                       }
                                    } ?>
                                 </select>
                              </div>
                           </div>
                           <div class="form-group row">
                              <label for="example-text-input" class="col-sm-2 col-form-label">Client Name *</label>
                              <div class="col-sm-10">
                                 <input class="form-control" value="<?php echo $crow['clientName']; ?>" required type="text"  name="ClientName" id="myinputbox"  placeholder="Client name" >		
                                 <input  value="<?php echo $crow['clientName']; ?>"  type="hidden"  name="ClientNameOld"  >
                                 <input  value="<?php echo $crow['id']; ?>"  type="hidden"  name="cid"  >
                              </div>
                           </div>
                           <div class="form-group row">
                              <label for="example-text-input" class="col-sm-2 col-form-label">Email *</label>
                              <div class="col-sm-10">
                                 <input class="form-control"  value="<?php echo $crow['email']; ?>" required type="email"  name="email" id="example-text-input" placeholder="Email" >
                              </div>
                           </div>
                           <div class="form-group row">
                              <label for="example-text-input" class="col-sm-2 col-form-label"></label>
                              <div class="col-sm-10">
                                 <a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=Manage-Client" class="btn btn-info">Cancel</a>
                                 <button type="submit" name="update" class="btn btn-success">Update</button>
                              </div>
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
                              <label for="example-text-input" class="col-sm-2 col-form-label">Select Category *</label>
                              <div class="col-sm-10">
                                 <select required class="form-control" name="category">
                                    <option value="">Select Category</option>
                                    <?php
                                    $table_name = $wpdb->prefix . 'services';
                                    $rowCat = $wpdb->get_results("select * from $table_name where act='1' ", ARRAY_A);
                                    foreach ($rowCat as $cat)
                                    { ?>
                                       <option value="<?php echo $cat['id']; ?>" ><?php echo $cat['name']; ?></option>
                                    <?php
                                    } ?>
                                 </select>
                              </div>
                           </div>
                           <div class="form-group row">
                              <label for="example-text-input" class="col-sm-2 col-form-label">Client Name *</label>
                              <div class="col-sm-10">
                                 <input class="form-control" required type="text"  name="ClientName"  placeholder="Client name" >
                              </div>
                           </div>
                           <div class="form-group row">
                              <label for="example-text-input" class="col-sm-2 col-form-label">Email *</label>
                              <div class="col-sm-10">
                                 <input class="form-control"  required type="email"  name="email" id="example-text-input" placeholder=" Email" >
                              </div>
                           </div>
                           <div class="form-group row">
                              <label for="example-text-input" class="col-sm-2 col-form-label"></label>
                              <div class="col-sm-10">
                                 <button type="submit" name="submit" class="btn btn-success">Add Client</button>
                              </div>
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
                           <th>Client Name</th>
                           <th>Category</th>
                           <th>Email</th>
                           <th>Short Code</th>
                           <th style="width: 70px;">Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                        $i = 1;
                        $table_name1 = $wpdb->prefix . 'client';
                        $rowClt = $wpdb->get_results("select * from $table_name1 where act='1' ", ARRAY_A);
                        foreach ($rowClt as $client)
                        {
                        ?>
                        <tr>
                           <td><?php echo $i++; ?></td>
                           <td>
                              <?php echo $client['clientName']; ?>
                           </td>
                           <td><?php
                              $cateID = $client['serviceType'];
                              $table_name = $wpdb->prefix . 'services';
                              $rowCat = $wpdb->get_row("select * from $table_name where id='$cateID' ", ARRAY_A);
                              echo $rowCat['name'];
                           ?></td>
                           <td><?php echo $client['email']; ?></td>
                           <td><?php echo '[REVCUSTOMFORM category="' . $cateID . '" clientId="' . $client['id'] . '" ] <br>[REVCUSTOM category="' . $cateID . '" clientId="' . $client['id'] . '" ] <br>[REVCUSTOMBADGE category="' . $cateID . '" clientId="' . $client['id'] . '" ]  <br>[REVCUSTOMTEXT category="' . $cateID . '" clientId="' . $client['id'] . '" ]'; ?></td>
                           <td><a href="<?php echo site_url(); ?>/wp-admin/admin.php?page=Manage-Client&edit=<?php echo $client['id']; ?>" class="btn btn-info"><i class="fa fa-fw fa-edit"></i></a>
                              <a onclick="return confirm('Are you sure, you want to delete this client?');" href="<?php echo site_url(); ?>/wp-admin/admin.php?page=Manage-Client&delete=<?php echo $client['id']; ?>"  class="btn btn-danger"><i class="fa fa-fw fa-trash"></i></a>
                           </td>
                        </tr>
                        <?php
                        } ?>
                     </tfoot>
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
