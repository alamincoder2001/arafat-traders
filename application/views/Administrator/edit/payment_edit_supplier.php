<div class="col-xs-12">
	<form name="PaymentForm" id="PaymentForm" method="POST">
		<!-- PAGE CONTENT BEGINS -->
		<span id="saveResult">
		<div class="form-horizontal">
		<div class="col-sm-6">
			
			<div class="form-group">
				<label class="col-sm-4 control-label" for="tr_type">Transaction Type</label>
				<label class="col-sm-1 control-label">:</label>
				<div class="col-sm-6">

					<input type="hidden" name="tr_id" value="<?php echo $edit->SPayment_invoice; ?>">

					<select name="tr_type" id="tr_type" class="chosen-select" style="width: 100%;">
						<option value="CP" <?php ($edit->SPayment_TransactionType == 'CP')?'Selected':''; ?>>Cash Receive</option>
						<option value="CR" <?php ($edit->SPayment_TransactionType == 'CR')?'Selected':''; ?>>Cash Payment</option>
					</select>
					<span id="msgTR"></span>
				</div>
			</div>	
			
			
			<div class="form-group">
				<label class="col-sm-4 control-label" for="SuppID">Account ID</label>
				<label class="col-sm-1 control-label">:</label>
				<div class="col-sm-6">
					<select class="chosen-select form-control" name="SuppID" id="SuppID" data-placeholder="Choose customer code..." onchange="FatchUser()">
						<option value="<?php echo $edit->SPayment_customerID; ?>"> 
							<?php 
								$AllUser = $this->db->get('tbl_supplier')->result();
								foreach ($AllUser as $value) {
									if($value->Supplier_SlNo == $edit->SPayment_customerID ):

								 		echo $value->Supplier_Name." - ".$value->Supplier_Code;
									endif;
							} ?>
						</option>

						<?php
							foreach($AllUser as $userCode){ ?>
						<option value="<?php  echo $userCode->Supplier_SlNo; ?>">
							<?php echo $userCode->Supplier_Name." - ".$userCode->Supplier_Code; ?></option>
						<?php } ?>
					</select>
					<span id="msgID"></span>
				</div>
			</div>	


			<div class="form-group">
				<label class="col-sm-4 control-label" for="DaTe">Supplier Name</label>
				<label class="col-sm-1 control-label">:</label>	
				<div class="col-sm-6">
					<?php 
						foreach ($AllUser as $value) {
						if($value->Supplier_SlNo == $edit->SPayment_customerID ):
					?>
						<input class="form-control notranslate" name="catname" id="catname" type="text" placeholder="Supplier Name" value="<?php echo $value->Supplier_Name; ?>" />

					<?php endif; } ?>
					
					<span id="msgName"></span>
				</div>
			</div>
		
		</div>
			
		<div class="col-sm-6">
			<div class="form-group">
			<label class="col-sm-4 control-label" for="paymentDate">Date</label>
			<label class="col-sm-1 control-label">:</label>	
				<div class="col-sm-6">
				<input class="form-control notranslate date-picker" name="paymentDate" id="paymentDate" type="text" data-date-format="yyyy-mm-dd" style="border-radius: 5px !important;" value="<?php echo  $edit->SPayment_date; ?>" />
				<span id="msgDate"></span>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-4 control-label" for="Note"> Description </label>
				<label class="col-sm-1 control-label">:</label>
				<div class="col-sm-6">
					<input type="text" id="Note" name="Note" value="<?php echo $edit->SPayment_notes; ?>" value="" class="form-control notranslate" />
					<span id="msgNote"></span>
				</div>
			</div>


			<div class="form-group">
				<label class="col-sm-4 control-label" for="paidAmount"> Amount </label>
				<label class="col-sm-1 control-label">:</label>
				<div class="col-sm-6">
					<input type="text" id="paidAmount" value="<?php echo $edit->SPayment_amount; ?>" name="paidAmount" value="0" class="form-control notranslate" />
					<input type="hidden" name="Paymentby" id="Paymentby" value="By Cash">
					<span id="msgPaid"></span>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-4 control-label" for=""> </label>
				<label class="col-sm-1 control-label"></label>
				<div class="col-sm-6">
					<button type="button" onclick="Updatedata()" name="btnSubmit" title="Update" class="btn btn-sm btn-success pull-left">
							Update
							<i class="ace-icon fa fa-arrow-right icon-on-right bigger-110"></i>
					</button>
				</div>
			</div>
		</div>
		<input type="hidden" name="idd" id="idd" value="<?php echo $edit->SPayment_id; ?>">
	</form>
</div>

<script type="text/javascript">

	function FatchUser(){
        var userid = $( "#SuppID" ).val();

         $('#catname').val("");
        $.ajax({
             url : "<?php echo base_url(); ?>Administrator/Supplier/fatch_supplier_name/"+userid,
             type: "POST",
             // data: {userid : userid},
             dataType: "JSON",
             success: function(data)
             {
                 $('#catname').val(data.Supplier_Name);
             }
         });
     }


	 function Updatedata(){
		var id = $('#idd').val();
		var tr_type = $('#tr_type').val();
		var paymentDate = $('#paymentDate').val();
		var catname = $('#catname').val();
		var SuppID = $('#SuppID').val();
		var paidAmount = $('#paidAmount').val();
        var Note = $("#Note").val();
        var Paymentby = $('#Paymentby').val();

		if(tr_type==""){
			$("#msgTR").html('Required this field').css("color","red");
            return false;
        }else{
            $('#msgTR').html('');
        }
		if(SuppID==""){
			$("#msgID").html('Required this field').css("color","red");
            return false;
        }else{
            $('#msgID').html('');
        }
        if(Note==""){
            $("#msgNote").html('Required this field').css("color","red");
            return false;
        }else{
            $('#msgNote').html('');
        }
		if(paidAmount=="0"){
			$("#msgPaid").html('Required this field').css("color","red");
            return false;
        }else{
            $('#msgPaid').html('');
        }
        if(paymentDate==""){
            $("#msgDate").html('Required this field').css("color","red");
            return false;
        }else{
            $('#msgDate').html('');
        }
		var succes = "";
		if(succes == ""){;
			var urldata = "<?php echo base_url();?>Administrator/Supplier/supplierPaymentUpdate/"+id;
			$.ajax({
				type: "POST",
				url: urldata,
				data: $('#PaymentForm').serialize(),
				success:function(data){
					if(data){
							$('#PaymentForm')[0].reset();
							location.reload();
						
					}else{
						alert("Payment failed!");
					}
					
				}
			});
		}
	}

</script>