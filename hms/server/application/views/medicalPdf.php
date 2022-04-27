
<style type="text/css">
   label 
   { 
   font-size: 10px;
   }
   .h5, h5 
   { 
   font-size: 1.25rem; 
   }
   .gst_table 
   { 
   background-color:#fff;border-collapse:collapse;color:#000;font-size:14px; 
   }
   .gst_table thead td
   { 
   		background-color: #6fa4d9; 
   }
   .gst_table tbody td
   { 
   		background-color: #fff; 
   }
   .gst_table td, .gst_table th 
   { 
   padding:2px
   }
   .gst_table td 
   {
   border:1px solid #222; 
   }
   .printtable tr 
   { 
   border-top:1px solid black;border-bottom:1px solid black; 
   }
   .clearfix 
   {
   width: 100%;min-height: 10px;
   }
   .double { 
   width: 100%;
   border-bottom: 1px double #000 ;
   border-top: 1px double #000 ;
   text-align:right;
   font-weight: bolder;
   font-size:18px; 
   }
   .footer, .footer-space 
   {
   height: 30px; 
   }
   .header 
   {
   position: fixed; top: 0;
   }
   .footer 
   { 
   position: fixed; bottom: 0; 
   }
   .gst_table 
   {
   border: 1px solid #222;
   }
   .gst_table th, .gst_table td 
   {
   border: 1px solid #222;
   border-bottom-width: 1px;
   vertical-align: middle;
   }
</style>
<table style="width: 100%;border: none;" >
   <tr>
      <td width="20%"> 
         <?if($institution["data"]["INSTITUTION_LOGO"]) { ?>
         <img  src="<?=$institution["logo_path"].$institution["data"]["INSTITUTION_LOGO"]?>" height="auto" width="100px"  >
         <? } ?>
      </td>
      <td width="80%" align="right" >
         <?if($institution["data"]) { ?>
         <h5 class="name"><b><?=$institution["data"]['INSTITUTION_NAME']?></b></h5>
         <div><label ><?=$institution["data"]['INSTITUTION_ADDRESS']?></label></div>
         <div><label ><?=$institution["data"]['INSTITUTION_CITY']?>,&nbsp;<?=$institution["data"]['INSTITUTION_COUNTRY_NAME']?></label></div>
         <div><label >Ph :&nbsp;<?=$institution["data"]['INSTITUTION_PHONE_NO']?></label></div>
         <div><label >Email :&nbsp;<?=$institution["data"]['INSTITUTION_EMAIL']?></label></div>
         <? } ?>
      </td>
   <tr>
</table>
<div class="clearfix">&nbsp;</div>
<table style="width: 100%;border: none;" >
   <tr>
      <td width="50%"> 
         <?if($pateint_details["data"]->FIRST_NAME) { ?>
         <label>Patient name&nbsp;&nbsp;</label>
         <label>:&nbsp;&nbsp;<?=$pateint_details["data"]->FIRST_NAME?></label>
         <? } ?>
      </td>
      <td width="50%" align="right"> 
         <?if($pateint_details["data"]->OP_REGISTRATION_NUMBER) { ?>
         <label>Patient number&nbsp;&nbsp; :&nbsp; &nbsp;&nbsp;<?=$pateint_details["data"]->OP_REGISTRATION_NUMBER?></label>
         <? } ?>
      </td>
   </tr>
   <tr>
      <td width="50%"> 
         <?if($pateint_details["data"]->GENDER) { ?>
         <label>Gender&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
         <label>:&nbsp;&nbsp;<?=$pateint_details["data"]->GENDER == 1 ? 'Male' : 'Female'?></label>
         <? } ?>
      </td>
      <td width="50%" align="right"> 
         <?if($pateint_details["data"]->MOBILE_NO) { ?>
         <label>Phone number&nbsp;&nbsp; :&nbsp; &nbsp;&nbsp;<?=$pateint_details["data"]->MOBILE_NO?></label>
         <? } ?>
      </td>
   </tr>
   <tr>
      <td width="50%"> 
         <?if($pateint_details["data"]->DOB) { ?>
         <label>Date of birth&nbsp;&nbsp;</label>
         <label>:&nbsp;&nbsp;<?=date("d-m-Y",strtotime($pateint_details["data"]->DOB));?></label>
         <? } ?>
      </td>
      <td width="50%" align="right"> 
         <?if($pateint_details["data"]->AGE) { ?>
         <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Age&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$pateint_details["data"]->AGE?>year(s)</label>
         <? } ?>
      </td>
   </tr>
</table>
<?if(!empty($visit_details["data"]))
{
   foreach ($visit_details["data"] as $value) 
   {?>
		<h5 align="center"><u>Date : <?=date("d-m-Y h:i a",strtotime($value["date"]));?></u></h5>
		<?if(!empty($value["vitals"]["data"]))
		 { ?>
			<h5 align="left"><u>Vitals Information</u></h5>
			<div class="col-lg-12" >
		       <table  class="printtable gst_table container col-md-12" style="width: 100%;" >
			        <thead >
			          <tr>
			              <td style="width:10%;" >Date & Time</td>
			              <td style="width:10%;">Temp<br>(<sup>o</sup>F)</td>
			              <td style="width:10%;" >Pulse<br>(/Min)</td>
			              <td style="width:10%;" >BPs<br>(MmHg)</td>
			              <td style="width:10%;" >BPd<br>(MmHg)</td>
			              <td style="width:10%;" >Weight<br>(Kg)</td>
			              <td style="width:10%;" >Height<br>(Cm)</td>
			              <td style="width:10%;" >BMI<br>(Kg/M<sup>2</sup>)</td>
			              <td style="width:10%;" >BSA<br>(M<sup>2</sup>)</td>
			              <!-- <?foreach ($value["vitals_params"]["data"] as $value2) {?>
			              <td class="text-center" style="width:8%;">
			              	<?=$value2->SHORT_FORM?>(<?=$value2->SYMBOL?>)
			              </td>
			             <? }?> -->
			          </tr>
			        </thead>        
			        <tbody>
			        	<?foreach ($value["vitals"]["data"] as $value1) { ?>
				          <tr>
				              <td ><?=date("d-m-Y h:i a",strtotime($value1["DATE_TIME"]));?></td>
				              <?foreach ($value1["param_values"] as $value2) {?>
				              <td ><?=$value2->PARAMETER_VALUE ?></td>
				              <? } ?>
				          </tr>
			      <? } ?>
			        </tbody>
		      </table>
		  	
			</div>
		<?}?>

		<?if(!empty($value["nursingnotes"]["data"]))
		{?>
			<h5 align="left"><u>Notes</u></h5>
			<div class="col-lg-12" >
			   <table  class="printtable gst_table container col-md-12" style="width: 100%;" >
				   <thead style="background: #6fa4d9;">
			         <tr>
			            <!--  <td class="text-center">#</td> -->
			            <td class="text-left">Description</td>
			            <td class="text-center">Details</td>
			         </tr>
			      </thead>
			      <tbody>
			         <tr>
			         	<td width="50%">Chief Complaints</td>
			            <td class="text-center" ><?=$value["nursingnotes"]["data"]["CHIEF_COMPLAINTS"]?></td>
			        </tr>
			        <tr>
			        	<td class="text-left">Lab Result</td>
			            <td class="text-center" ><?=$value["nursingnotes"]["data"]["PAST_HISTORY"]?></td>
			        </tr>
			        <tr>
			        	 <td class="text-center">Nursing Notes</td>
			            <td class="text-center" ><?=$value["nursingnotes"]["data"]["NURSING_NOTES"]?></td>
			        </tr>
			        <tr>
			        	<td class="text-center">Treatment Given</td>
			            <td class="text-center" ><?=$value["nursingnotes"]["data"]["FAMILY_HISTORY"]?></td>
			           
			         </tr>
			      </tbody>
			      <tbody>
			      </tbody>
			   </table>
			  </div>
	 	<?}?>


	 	<?if(!empty($value["pain"]["data"]))
		{
			?>
			<h5 align="left"><u>Pain Assesment</u></h5>
			<div class="col-lg-12" >
				<table  class="container col-md-12" style="width: 100%;" >
				 <tr>
			        <?foreach ($value["pain"]["data"] as $value4) 
		         	{?> 
		         		<h4><u><?if($value4["USER_ACCESS_TYPE"]==1) echo "Admin";elseif($value4["USER_ACCESS_TYPE"]==3) echo "Nurse";elseif($value4["USER_ACCESS_TYPE"]==4) echo "Doctor"; else echo "";?></u></h4>
		         		<?foreach ($value["pain_array"] as $value3) 
			         	{?>
			         		<?if($value3["value"]==$value4["PAIN_SCORE"]){?>
			         			<td><img  src="<?=base_url($value3["select"])?>" height="auto" width="100px"  ></td>
			         		<?}else{?>
			         			<td><img  src="<?=base_url($value3["image"])?>" height="auto" width="100px"  ></td>
			         		<?}
			         	}?>
		         	<?}?>
		         </tr>
		     	</table>
			 </div>
	 	<?}?>



		<?if(!empty($value["diagonosis"]["data"]["PATIENT_DIAGNOSIS_DETAILS"]))
		{?>
			<h5 align="left"><u>Diagonosis Information</u></h5>
			<div class="col-lg-12" >
		   <table  class="printtable gst_table container col-md-12" style="width: 100%;" >
		      <thead style="background: #6fa4d9;">
		         <tr>
		            <td align="center" width="2%">#</td>
		            <td width="15%">Diagnosis Name</td>
		            <td width="7%">ICD</td>
		            <td width="7%">Level</td>
		            <td width="5%">Type</td>
		         </tr>
		      </thead>
		      <tbody>
		         <? 
		         	$i = 0;foreach ($value["diagonosis"]["data"]["PATIENT_DIAGNOSIS_DETAILS"] as $value3) 
		         	{ $i++;?>
			         <tr>
			            <td align="center" class="text-center" ><?=$i?></td>
			            <td class="text-center" ><?=$value3["DIAGNOSIS_NAME"]?></td>
			            <td class="text-center" ><?=$value3["DIAGNOSIS_CODE"] ?></td>
			            <td class="text-center" ><?=$value3["DIAGNOSIS_LEVEL_NAME"]?></td>
			            <td class="text-center" ><?=$value3["DIAGNOSIS_TYPE_NAME"]?></td>
			         </tr>
		         	<?}?>
			      </tbody>
			   </table>
			</div>
		<?}?>
		<?if(!empty($value["prescription"]["data"]["PRESCRIPTION_DETAILS"]))
		{?>
			<h5 align="left"><u>Prescription Information</u></h5>
			<div class="col-lg-12" >
		   <?if(!empty($value["prescription"]["data"]["[ERX_REFERENCE_NUMBER]"]))
		   {
			      ?>
				   <table style="width: 100%;border: none;" >
				      <tr>
				         <td width="50%"> 
				            <label>ERX No&nbsp;&nbsp;</label>
				            <label>:&nbsp;&nbsp;<?=$value["prescription"]["data"]["[ERX_REFERENCE_NUMBER]"]?></label>
				         </td>
				      </tr>
				   </table>
				 <?
			}?>
		   <table  class="printtable gst_table container col-md-12" style="width: 100%;" >
		      <thead style="background: #6fa4d9;">
		         <tr>
		            <td align="center" width="2%">#</td>
		            <td class="text-left">Drug</td>
		            <td class="text-center">Frequency</td>
		            <td class="text-center">Duration (Days)</td>
		            <td class="text-center">Qty</td>
		            <td class="text-left" width="10%">Route of Admin</td>
		            <td class="text-left" width="25%">Instruction</td>
		            <td class="text-center">Refills</td>
		         </tr>
		      </thead>
		      <tbody>
		         <? if(!empty($value["prescription"]["data"]["PRESCRIPTION_DETAILS"]))
		         {
		         	$i = 0;foreach ($value["prescription"]["data"]["PRESCRIPTION_DETAILS"] as $value3) { $i++;?>
			         <tr>
			            <td class="text-center" align="center"><?=$i?></td>
			            <td class="text-center" ><?=$value3["TRADE_NAMES"]?></td>
			            <td class="text-center" ><?=$value3["FREQUENCY"] ;?>
			               <?if($value3["FREQUENCY_TYPE"] == 17){?>Day(s)<?}
			                  elseif($value3["FREQUENCY_TYPE"] == 18){?> Hour(s)<?} 
			                  elseif($value3["FREQUENCY_TYPE"] = 19){?>Per week(s)<?}?>
			            </td>
			            <td class="text-center" ><?=$value3["STRENGTH"]?></td>
			            <td class="text-center" ><?=$value3["TOTAL_QUANTITY"]?></td>
			            <td class="text-center" ><?=$value3["DESCRIPTION"]?></td>
			            <td class="text-center" ><?=$value3["INSTRUCTION"]?></td>
			            <td class="text-center" ><?=$value3["REMARKS"]?></td>
			         </tr>
			         <?}
			     }?>
		      </tbody>
		   </table>
		</div>
	 <?}?>


	 <?if(!empty($value["allergy"]["data"]))
		{?>
			<h5 align="left"><u>Allergy Details</u></h5>
			
			<?if(isset($value["allergy"]["data"]["NO_KNOWN_ALLERGIES"]) && $value["allergy"]["data"]["NO_KNOWN_ALLERGIES"]==0){?>
				<div class="col-lg-12" >
			<?if(!empty($value["allergy"]["data"]["DRUG_ALLERGIES"])){?>
			<h4><u>Drug Allergies</u></h4>
			   <table  class="printtable gst_table container col-md-12" style="width: 100%;" >
			      <thead style="background: #6fa4d9;">
			         <tr>
			            <td align="center" width="2%">#</td>
			            <td class="text-left">Generic Name</td>
			            <td class="text-center">Brand Name</td>
			         </tr>
			      </thead>
			      <tbody>
		         <? 
		         	$i = 0;foreach ($value["allergy"]["data"]["DRUG_ALLERGIES"] as $value3) 
		         	{ $i++;?>
			         <tr>
			            <td class="text-center" align="center" ><?=$i?></td>
			            <td class="text-center" ><?=$value3["GENERIC_NAME"]?></td>
			            <td class="text-center" ><?=$value3["BRAND_NAME"] ?></td>
			         </tr>
		         	<?}?>
			      </tbody>
			      <tbody>
			      </tbody>
			   </table>
			  <?}?>
			 <?if(!empty($value["allergy"]["data"]["OTHER_ALLERGIES"])){?>
			 <h4><u>Other Allergies</u></h4>
			   <table  class="printtable gst_table container col-md-12" style="width: 100%;" >
			      <thead style="background: #6fa4d9;">
			         <tr>
			            <td align="center" width="2%">#</td>
			            <td class="text-left">Other Allergies</td>
			            <td class="text-center">Allergies Item	</td>
			         </tr>
			      </thead>
			      <tbody>
		         <? 
		         	$i = 0;foreach ($value["allergy"]["data"]["OTHER_ALLERGIES"] as $value3) 
		         	{ $i++;?>
			         <tr>
			            <td class="text-center" ><?=$i?></td>
			            <td class="text-center" ><?=$value3["ALLERGIES_OTHER_NAME"]?></td>
			            <td class="text-center" ><?=$value3["ALLERGIES_ITEM"] ?></td>
			         </tr>
		         	<?}?>
			      </tbody>
			   </table>
			  <?}?></div><?}else{?>
			  	<p>No known allergies</p>
			<?}?>
		
	 <?}?>



	 <? if(!empty($value["complaints"]["data"]))
	{?>
		<h5 align="left"><u>General Complaints</u></h5>
		<div class="col-lg-12" >
		   <table  class="printtable gst_table container col-md-12" style="width: 100%;" >
		      <thead style="background: #6fa4d9;">
		         <tr>
		            <!--  <td class="text-center">#</td> -->
		            <td class="text-left">Medical history</td>
		            <td class="text-center">Details</td>
		         </tr>
		      </thead>
		      <tbody>
			         <tr>
			            <td>Complaints</td>
			            <td class="text-center" ><?=$value["complaints"]["data"]["COMPLAINTS"]?></td>
			         </tr>
			         <tr>
			            <td>Summary of Case	</td>
			            <td class="text-center" ><?=$value["complaints"]["data"]["SUMMARY_OF_CASE"]?></td>
			         </tr>
			         <tr>
			            <td>Past Medical History	</td>
			            <td class="text-center" ><?=$value["complaints"]["data"]["PAST_MEDICAL_HISTORY"]?></td>
			         </tr>
			         <tr>
			            <td>Drug History	</td>
			            <td class="text-center" ><?=$value["complaints"]["data"]["DRUG_HISTORY"]?></td>
			         </tr>
			         <tr>
			            <td>Social History	</td>
			            <td class="text-center" ><?=$value["complaints"]["data"]["SOCIAL_HISTORY"]?></td>
			         </tr>
			         <tr>
			            <td>Clinical Examination	</td>
			            <td class="text-center" ><?=$value["complaints"]["data"]["CLINICAL_EXAMINATION"]?></td>
			         </tr>
			         <tr>
			            <td>Other Notes	</td>
			            <td class="text-center" ><?=$value["complaints"]["data"]["NOTES"]?></td>
			         </tr>
		      </tbody>
		   </table>
		</div>
	<?}?>

	<? if(!empty($value["dental"]["data"]["DENTAL_COMPLAINT"]))
	{?>
		<h5 align="left"><u>Dental Complaints Information</u></h5>
		<div class="col-lg-12" >
		   <table  class="printtable gst_table container col-md-12" style="width: 100%;" >
		      <thead style="background: #6fa4d9;">
		         <tr>
		            <td align="center" width="2%">#</td>
		            <td  width="10%">Date</td>
		            <td  width="13%">Tooth Number</td>
		            <td  width="25%">Procedure</td>
		            <td >Notes</td>
		         </tr>
		      </thead>
		      <tbody>
		         <? $i = 0;
		            foreach ($value["dental"]["data"]["DENTAL_COMPLAINT"] as $key => $value3) 
		            
		            {
		             $i++;?>
			         <tr>
			            <td align="center"><?=$i?></td>
			            <td align="center"><?=date("d-m-Y",strtotime($value3["CREATEDATE"]));?></td>
			            <td align="center"><?=$value3["TOOTH_NUMBER"]?></td>
			            <td><?=$value3["PROCEDURE"]?></td>
			            <td><?=nl2br($value3["DESCRIPTION"])?></td>
			         </tr>
			         <? } ?>
		      </tbody>
		   </table>
		</div>
	<?}?>
	<?if(!empty($value["billing"]["data"]))
	{?>
		<h5 align="left"><u>Billing Information</u></h5>
		<div class="col-lg-12" >
		  <?
			   	foreach ($value["billing"]["data"] as $value3) 
			   {
			      ?>
				   <table style="width: 100%;border: none;" >
				      <tr>
				         <td width="50%"> 
				            <?if($value3["BILLING_INVOICE_NUMBER"])
				            { ?>
				            	<label>Invoice No&nbsp;&nbsp;</label>
				            	<label>:&nbsp;&nbsp;<?=$value3["BILLING_INVOICE_NUMBER"]?></label>
				            <?} ?>
				         </td>
				         <!-- <td width="50%" align="right"> 
				         	<?if($value3["BILLING_TYPE"])
				            { ?>
				            <label>Payment Type&nbsp;&nbsp; :&nbsp; &nbsp;&nbsp;
				            	<?if($value3["PATIENT_TYPE"] == 1) {?> Credit <? }else { ?> Cash <? }?></label>
				            <? } ?>
				         </td> -->
				      </tr>
				   </table>
				   <table  class="printtable gst_table container col-md-12" style="width: 100%;" >
				      <thead style="background: #6fa4d9;">
				         <tr>
				            <td align="center" width="2%">#</td>
				            <td style="width:20%" class="text-left">Description</td>
				            <td style="width:7%">Code</td>
				            <td style="width:3%">Price<br>(AED)</td>
				            <td  style="width:5%">Qty</td>
				            <td  style="width:3%">Gross<br>(AED)</td>
				            <!-- <td  style="width:4%">Co-payment</td> -->
				            <!-- <td  style="width:4%">Patient Pay</td> -->
				            <!-- <th style="width:4%">Authorization No:</th> -->
				            <td  style="width:4%">Total</td>
				            <!-- <td  style="width:4%">Insurance Total</td> -->
				         </tr>
				      </thead>
				      <tbody>
				         <pre>
				         <? $i = 0;
				            foreach ($value3["bill_details"] as $key => $value4)
				            { $i++;?>
				         <tr>
				            <td align="center"><?=$i?></td>
				            <td class="text-left"><?=$value4["PROCEDURE_CODE_NAME"]?></td>
				            <td class="text-center"><?=$value4["PROCEDURE_CODE"]?> </td>
				            <td class="text-right"><?=$value4["RATE"]?></td>
				            <td class="text-center"><?=$value4["QUANTITY"]?></td>
				            <td class="text-right"><?=$value4["RATE"]* $value4["QUANTITY"]?></td>
				            <!-- <td class="text-right"> <?=$value4["COINS"]  . ' ' . $value4["COINS_TYPE"]?></td> -->
				            <!-- <td class="text-right"> <?=$value4["PATIENT_PAYABLE"]?></td> -->
				            <td class="text-right"> <?=$value4["TOTAL_PATIENT_PAYABLE"]?></td>
				            <!-- <td class="text-right"> <?=$value4["TOTAL_INSURED_AMOUNT"]?></td> -->
				         </tr>
				         <? } ?>
				      </tbody>
				   </table>
				   <table cellspacing="0" cellpadding="0" class="table table-sm table-striped " style="width: 100%;border: none;">
		              <tbody>
		                <tr> 
		                    <td  style="text-align: right">Gross Total:</td>
		                    <td style="width:10%;text-align: center;"><?=$value3["BILLED_AMOUNT"]?></td>
		                </tr>
		                <!-- <tr>
		                    <td   style="text-align: right">Net Total:</td>
		                    <td style="width:10%;text-align: center;"><?=$value3["BILLED_AMOUNT"]?></td>
		                </tr> -->
		                <tr>
		                    <td  style="text-align: right">Patient Payable:</td>     
		                    <td  style="width:10%;text-align: center;"><?=$value3["PAID_BY_PATIENT"]?></td>
		                </tr>
		                <!-- <tr>
		                    <td   style="text-align: right">Amount to be Claimed:</td>
		                    <td style="width:10%;text-align: center;"><?=$value3["INSURED_AMOUNT"]?></td>
		                </tr> -->
		              </tbody>
		            </table>
			   <?}?>
			</div>
		<?}

	}
}
?>

<!-- <pre><?// print_r($visit_details);exit();?></pre> -->