<?php include('header.php');?>
<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb"> 
        <li class="breadcrumb-item active">[ Insert Daily Records here ]</li>
      </ol> 
	  <div class="row">
        <div class="col-lg-6">
          <!-- Example Bar Chart Card-->
          <div class="card mb-3">
            <div class="card-header">
              <i class="fa fa-bar-chart"></i> Enter Transaction Details</div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-12 my-auto">
                  <form method="post" action="request.php?type=add_transaction" id="add_product" enctype="multipart/form-data">
				  <div id="add_product_response" class="col-md-12 col-xs-12 col-sm-12 col-lg-12"></div>
				  <div class="form-group">
					<label class="control-label col-md-12 col-sm-12 col-xs-12" for="bank"><span class="required">Complete Product details*</span> </label>
					<div class="col-md-12 col-sm-12 col-xs-12">
					  <textarea id="description" name="description" class="form-control col-md-12 col-xs-12" placeholder="Example: 4 Breads @ 25 Each & 2 Jams @ 45 each..."></textarea>
					</div>
				  </div> 
				  <div class="form-group">
				  <label class="control-label col-md-12 col-sm-12 col-xs-12" for="total_price">Total Price<span class="required">*</span> </label>
					<div class="col-md-12 col-sm-12 col-xs-12">
					  <input id="total_price" name="total_price" type="number" class="form-control col-md-12 col-xs-12" required>
					</div>
				  </div>
				  <div class="row">
					  <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
						  <div class="form-group">
							<label class="control-label col-md-12 col-sm-12 col-xs-12" for="consumed">Date<span class="required">*</span> </label>
							<div class="col-md-12 col-sm-12 col-xs-12">
							  <input type="date" id="date" name="date" class="form-control col-md-12 col-xs-12" value="<?php echo date('Y-m-d',time());?>" required>
							</div>
						  </div> 
					  </div>
					  <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
						  <div class="form-group">
						  <label class="control-label col-md-12 col-sm-12 col-xs-12" for="paid_by">Paid By<span class="required">*</span> </label>
							<div class="col-md-12 col-sm-12 col-xs-12">
							  <select id="paid_by" name="paid_by" class="form-control col-md-12 col-xs-12" required>
								  <option value="">Select anyone</option>
							  <?php $lists = UsersList();foreach($lists as $list){?>
								<option value="<?php echo $list['id'];?>"><?php echo $list['name'];?></option>
							  <?php } ?> 
							  </select>
							</div>
						  </div>
					</div>
				</div>
				<div class="col-md-12">
				<b>Applied for:</b><br>
				<?php foreach($lists as $list){?>
					<input type="checkbox" name="applied_for[]" class="applied_for" value="<?php echo $list['id'];?>" id="applied_for_<?php echo $list['id'];?>">&nbsp;<label for="applied_for_<?php echo $list['id'];?>"><?php echo $list['name'];?></label>&nbsp;&nbsp;
				<?php } ?> 
				</div>
				  <div class="form-group"> 
					  <button type="submit" class="form-control col-md-12 col-xs-12 btn-primary"> Add</button>
				  </div>
				  </form>
                </div> 
              </div>
            </div> 
          </div> 
        </div> 
        <div class="col-lg-6">
          <!-- Example Bar Chart Card-->
          <div class="card mb-3">
            <div class="card-header">
              <i class="fa fa-inr"></i> Summarized Expense</div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-12 my-auto">   
				  <Div class="row">
					  <div class="col-md-6 col-sm-12 col-xs-12">
						<b>Money Paid by You. (Current month)</b>
						<div class="col-md-12 col-sm-12 col-xs-12">
						   <?php echo TotalPaidByUser($_SESSION['user_id'],'monthly');?> Rs
						</div>
					  </div>
					  <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
						<b>Overall Expense.</b>
						<div class="col-md-6 col-sm-12 col-xs-12">
						   <?php echo TotalPaidByUser($_SESSION['user_id'],'all');?> Rs
						</div>
					  </div>
				  </div>
                </div> 
              </div>
			  
            </div> 
          </div>   
          <div class="card mb-3">
            <div class="card-header">
               <i class="fa fa-inr"></i> Debit Balance (Pay them)</div>
             <div class="card-body">
              <div class="row">
                <div class="col-sm-12 my-auto">   
				  <Div class="row">  
						 <?php 
						   foreach($user_list as $user){ 
							if($user['id'] != $_SESSION['user_id']){?>
								<div class="col-md-6"><b><?php echo $user['name'];?></b>  <?php echo ShowCreditBalance($user['id'],$_SESSION['user_id']);?> Rs</div>
						 <?php }} ?> 
				  </div>
                </div> 
              </div> 
            </div> 
          </div> 

          <div class="card mb-3">
            <div class="card-header">
               <i class="fa fa-inr"></i> Credit Balance (Take from them) | Your Balance : <?php echo TotalPaidByUser($_SESSION['user_id'],'monthly') -  CaluclateBalance($_SESSION['user_id'],'credit');?> Rs</div>
             <div class="card-body">
              <div class="row">
                <div class="col-sm-12 my-auto">   
				  <Div class="row">  
						 <?php 
						   foreach($user_list as $user){ 
							if($user['id'] != $_SESSION['user_id']){?>
								<div class="col-md-6"><b><?php echo $user['name'];?></b>  <?php echo ShowDebitBalance($user['id'],$_SESSION['user_id']);?> Rs</div>
						 <?php }} ?> 
				  </div>
                </div> 
              </div> 
            </div> 
          </div> 		  
        </div>   
    </div>
	</div>
	<div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
		<table id="table_id" class="table table-condensed table-striped table-hover">
    <thead>
        <tr>
			<th>Sl No.</th><th>Description</th><th>Total Price</th><th>Applied to</th><th>PPP</th><th>Date</th><th>Paid by</th>
        </tr>
    </thead>
	<tbody> 
	<?php 
	$i=0;
    $transactions = getAllTransactions();
	foreach($transactions as $transaction){ 
	$i++;?>
            <tr>
				<td><?php echo $i;?></td><td><?php echo $transaction['description'];?></td><td><?php echo $transaction['total_price'];?> Rs</td><td><?php $ids= explode(',',$transaction['applied_for']);foreach($ids as $id){ echo getuser($id)['name'].'&nbsp;|&nbsp;'; } ?></td><td><?php echo $transaction['per_person_price'];?> Rs</td><td><?php echo date('d M Y,h:i A',$transaction['timestamp']);?></td><td><?php echo getuser($transaction['paid_by'])['name'];?></td>
            </tr> 
	<?php } ?>
    </tbody>
</table>
	</div>
<script> 
$(document).ready(function(){
$("#add_blood_consumption").submit(function(e)
{   
    $('#add_blood_consumption_response').html("<div class='alert alert-success'>Please wait....</div>");
	var postData = $(this).serializeArray();
	$("button[type='submit']").prop('disabled',true);
	var formURL = "request.php?type=add_blood_consumption";
	$.ajax(
	{
        url: formURL,
		type: "POST",
		data : postData,
        dataType: 'json',
		success:function(data, textStatus, jqXHR) 
		{
				$("button[type='submit']").prop('disabled',false);
		        if(data.status==200){
				$('#add_blood_consumption_response').html("<div class='alert alert-success'>"+data.msg+"</div>");
				}else{
				$('#add_blood_consumption_response').html("<div class='alert alert-danger'>"+data.msg+"</div>");
				}
		},
		error: function(jqXHR, textStatus, errorThrown) 
		{
				$("button[type='submit']").prop('disabled',false);
                $('#add_blood_consumption_response').html("<div class='alert alert-danger'>Please Check Connection....</div>");
		}
	});
    e.preventDefault();	//STOP default action
});
});
	</script>
	<?php include('footer.php');?>
