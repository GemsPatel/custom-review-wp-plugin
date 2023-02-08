<?php
if (isset($_GET['delete']))
{
    $del = $_GET['delete'];
    $wp_rvcomment = $wpdb->prefix . 'rvcomment';
    $wpdb->query("DELETE FROM $wp_rvcomment WHERE id='$del'");
    echo '<div class="alert alert-danger fade in alert-dismissable" style="margin-top:18px;">
   			<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>
   			 Review deleted successfully.</div>';
}
if (isset($_GET['unapprove']))
{
    $del = $_GET['unapprove'];
    $wp_rvcomment = $wpdb->prefix . 'rvcomment';
    $wpdb->query("UPDATE $wp_rvcomment SET review_status='0' WHERE id='$del'");
    echo '<div class="alert alert-danger fade in alert-dismissable" style="margin-top:18px;">
   			<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>
   			 Review status changed to pending successfully.</div>';
}
if (isset($_GET['approve']))
{
    $del = $_GET['approve'];
    $wp_rvcomment = $wpdb->prefix . 'rvcomment';
    $wpdb->query("UPDATE $wp_rvcomment SET review_status='1' WHERE id='$del'");
    echo '<div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
   		<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>
   		Review approved successfully.</div>';
}
if (isset($_POST['reply_submit']))
{
    $rv_reply = $_POST['rv_reply'];
    $rv_comment_id = $_POST['rv_comment_id'];
    $wp_rvcomment = $wpdb->prefix . 'rvcomment';
    $wpdb->query("UPDATE $wp_rvcomment SET reply='$rv_reply' WHERE id='$rv_comment_id'");
    echo '<div class="alert alert-success fade in alert-dismissable" style="margin-top:18px;">
   		<a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>
   		Reply added successfully.</div>';
}
?>
<style>
   .width_160{
      width:160px;
   }
</style>
<div class="col-md-12">
   <div class="row m-r-0 m-t-20">
      <div class="col-md-12">
         <div class="row">
            <h3>Manage Reviews</h3>
         </div>
         <div class="row">
            <form action="<?php echo site_url(); ?>/wp-admin/admin.php?page=Manage-Review" method="GET">
               <div class="col-md-4">
                  <div class="form-group row">
                     <input type="hidden" name="page" value="Manage-Review">
                     <label for="example-text-input" class="col-sm-5 col-form-label">Select Category * </label>
                     <div class="col-sm-7">
                        <select required class="form-control" name="categoryf">
                           <option value="All" >All</option>
                           <?php
                           $table_name = $wpdb->prefix . 'services';
                           $rowCat = $wpdb->get_results("select * from $table_name where act='1' ", ARRAY_A);
                           foreach ($rowCat as $cat)
                           {
                              if (isset($_GET['categoryf']) and $_GET['categoryf'] == $cat['id']) { ?>
                                 <option value="<?php echo $cat['id']; ?>" selected><?php echo $cat['name']; ?></option>
                              <?php } else { ?> 
                                 <option value="<?php echo $cat['id']; ?>" ><?php echo $cat['name']; ?></option>
                              <?php }
                           } ?>
                        </select>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="form-group row">
                     <label for="example-text-input" class="col-sm-5 col-form-label">Select Client </label>
                     <div class="col-sm-7">
                        <select required class="form-control" name="clientf">
                           <option value="All" >All</option>
                           <?php
                           $table_name = $wpdb->prefix . 'client';
                           $rowCat = $wpdb->get_results("select * from $table_name where act='1' ", ARRAY_A);
                           foreach ($rowCat as $clnt)
                           {
                              if (isset($_GET['clientf']) and $_GET['clientf'] == $clnt['id']) { ?>
                                 <option value="<?php echo $clnt['id']; ?>" selected><?php echo $clnt['clientName']; ?></option>
                              <?php } else { ?> 
                                 <option value="<?php echo $clnt['id']; ?>" ><?php echo $clnt['clientName']; ?></option>
                              <?php }
                           } ?>
                        </select>
                     </div>
                  </div>
               </div>
               <div class="col-md-2">
                  <button type="submit" name="filter" class="btn btn-success">Search</button>
               </div>
            </form>
         </div>
         <div class="row ">
            <div class="col-md-12 p-t-20 p-b-20">
               <div class="row table-responsive">
                  <form method="get">
                     <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                     <?php
                     include ('datatable.php');
                     ?>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="modal-default">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Reply of review</h4>
         </div>
         <form method="POST" action="">
            <div class="modal-body">
               <div class="form-group">
                  <input type="hidden" id="rv_comment_id" name="rv_comment_id" />
                  <textarea class="form-control" id="rv_reply" rows="4" name="rv_reply" required placeholder="Leave reply here...*"></textarea>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
               <button type="submit" name="reply_submit" class="btn btn-primary">Reply</button>
            </div>
         </form>
      </div>
      <!-- /.modal-content -->
   </div>
   <!-- /.modal-dialog -->
</div>
<script>
   function rv_fun(id,reply) {
    document.getElementById("rv_comment_id").value = id;
    document.getElementById("rv_reply").value = reply;
   }
</script>
