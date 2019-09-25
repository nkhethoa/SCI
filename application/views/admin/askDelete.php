<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-fluid">
<div class="starter-template">
	<div class="panel panel-success">
    			<div class="panel-heading">
    				<h3 class="panel-title"><strong>Delete User</strong></h3>
    			</div>
    			<div class="panel-body">
    				<br>
    				<h1 class="text-center">Are you sure you want to delete this User?</h1>
    				<br>
    				<p class="text-center">This operation cannot be canceled</p>
                    <!--form action using php-->
    				<?php
    				$action = "admin/users";
    				$options = array("class"=>"form-horizontal","method"=>"POST");
    				echo form_open($action,$options);
    				?>
    				<input type="hidden" name="id_user" value="<?php echo isset($id_user)? $id_user:0 ;?>" placeholder="">
                     <div class="text-center">
    				<a role="button" href="<?php echo base_url("admin/users") ?>" class="btn btn-default" title="No Delete">NO&nbsp;<i class="fa fa-times" aria-hidden="true"></i></a>

    				<!--<a href="<?php //echo base_url('project/books/'.$id_book) ?>" class="btn btn-danger btn-xs" title="delete">YES-->
    					<button type="submit" class="btn btn-default" title="Yes Delete">YES&nbsp;<i class="fa fa-check" aria-hidden="true"></i></button>
    				</div>
    				</form>
    				<br>
    				<br>
    				<br>
    				<br>
    			</div>
    			</div>
    		</div>
</div>

</div><!--div for container-->
