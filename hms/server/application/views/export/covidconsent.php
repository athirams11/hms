<style type="text/css">
   label 
   { 
      font-size: 15px;
   }
   .h5, h5 
   { 
      font-size: 1.25rem; 
   }
   .gst_table 
   { 
      background-color:#fff;
      border-collapse:collapse;
      color:#000;
      font-size:15px; 
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
   padding:6px
   }
   .gst_table td 
   {
   border:1px solid #222; 
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
   .doubleUnderline { padding:0px;border-bottom: 1px solid; }
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
      <td colspan="2" width="100%">
      <?if($pateint_details["data"]->OP_REGISTRATION_NUMBER) { ?>
         <label>Patient number&nbsp;&nbsp; :&nbsp; &nbsp;&nbsp;<?=$pateint_details["data"]->OP_REGISTRATION_NUMBER?></label>
         <? } ?>
      </td>
   </tr>
   <tr>
      <td  colspan="2" width="100%"> 
         <?if($pateint_details["data"]->FIRST_NAME) { ?>
         <label>Patient name&nbsp;&nbsp;</label>
         <label>:&nbsp;&nbsp;<?=$pateint_details["data"]->FIRST_NAME?></label>
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


<div class="clearfix">&nbsp;</div>
<h5 align="center"  style="font-weight: bold;"><span class="doubleUnderline">Patient Screening Form - Covid 19</span></h5>
<div class="clearfix">&nbsp;</div>

<?$cons=json_decode($consent["data"]["DATA"]) ?>
<?if(!empty($cons->questions)){ ?>
<table style="width: 100%;" class="gst_table">
   <?  $i = 0; foreach ($cons->questions as $value) {$i++;?>
   <tr>
   <td align="center"><?=$i?></td>
      <td width="60%">
         <label><?=$value->ques?></label>
      </td>
      <td width="30%" align="center">
     
                     <input type="radio" name = "yes" value="yes" checked=<?=$value->ans==1?>> Yes </br>
                     <input type="radio" name = "no" value="no" checked=<?=$value->ans==0?>> No </br>
                     </td>
     
   </tr>
  
<?}?>
<tr>
   <td colspan="3" align="center">Note: <span style="color:red;">Positive responses to any of these would likely indicate a deeper discussion with the Dentist before proceeding emergency or urgent treatment.</span></td>
   </tr>
</table>
<?}?>

<table style="width: 100%;border: none;" >
   <tr>
      <td width="100%"  align="right" > 
         <br>Signature <br>
         <?if($consent["data"]["SIGNATURE"]) { ?>
         <img  src="<?=$consent["sign_path"].$consent["data"]["SIGNATURE"]?>" height="auto" width="100px"  >
         <? } ?><br>

         <?if($consent["data"]["SIGNED_ON"]) { ?>
         Signed On: <?=date("d-m-Y h:i a",strtotime($consent["data"]["SIGNED_ON"]));?>
         <? } ?>
      </td>
   <tr>
</table>