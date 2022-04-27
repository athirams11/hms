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
	.gst_table th 
	{ 
		background-color:#fff; 
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
	/*.gst_table thead th, .gst_table thead td 
	{
	    border-bottom-width: 2px;
	    border: 1px solid #222;
	}*/
</style>  
<?
$child= array(
	'id' => '1','label' => 'A',
	'id' => '2','label' => 'B',
	'id' => '3','label' => 'C',
	'id' => '4','label' => 'D',
	'id' => '5','label' => 'E',
	'id' => '6','label' => 'F',
	'id' => '7','label' => 'G',
	'id' => '8','label' => 'H',
	'id' => '9','label' => 'I',
	'id' => '10','label' => 'J',
	'id' => '11','label' => 'K',
	'id' => '12','label' => 'L',
	'id' => '13','label' => 'M',
	'id' => '14','label' => 'N',
	'id' => '15','label' => 'O',
	'id' => '16','label' => 'P',
	'id' => '17','label' => 'Q',
	'id' => '18','label' => 'R',
	'id' => '19','label' => 'S',
	'id' => '20','label' => 'T')
?>

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
	<!-- <div class="footer col-lg-12 " align="center" ><hr>
	    <p style="text-align: center;font-size: 14px" >
	       This is a computer-generated document. No signature is required.
	    </p>
	</div> -->
<h5 align="center"><u>Dental History</u></h5>

<table style="width: 100%;border: none;" >
  <tr>
    <td width="50%"> 
      <?if($dentaldata["data"]["FIRST_NAME"]) { ?>
        <label>Patient name&nbsp;&nbsp;</label>
        <label>:&nbsp;&nbsp;<?=$dentaldata["data"]['FIRST_NAME']?></label>
      <? } ?>
    </td>
    <td width="50%" align="right"> 
      <?if($dentaldata["data"]["OP_REGISTRATION_NUMBER"]) { ?>
        <label>Patient number&nbsp;&nbsp; :&nbsp; &nbsp;&nbsp;<?=$dentaldata["data"]["OP_REGISTRATION_NUMBER"]?></label>
      <? } ?>
    </td>
  </tr>
  <tr>
  	<td width="50%"> 
      <?if($dentaldata["data"]["GENDER"]) { ?>
      	<label>Gender&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <label>:&nbsp;&nbsp;<?=$dentaldata["data"]["GENDER"] == 1 ? 'Male' : 'Female'?></label>
      <? } ?>
    </td>
    <td width="50%" align="right"> 
      <?if($dentaldata["data"]["MOBILE_NO"]) { ?>
      	<label>Phone number&nbsp;&nbsp; :&nbsp; &nbsp;&nbsp;<?=$dentaldata["data"]["MOBILE_NO"]?></label>
      <? } ?>
    </td>
  </tr>
  <tr>
  	<td width="50%"> 
      <?if($dentaldata["data"]["DOB"]) { ?>
      	<label>Date of birth&nbsp;&nbsp;</label>
        <label>:&nbsp;&nbsp;<?=$dentaldata["data"]["DOB"]?></label>
      <? } ?>
    </td>
    <td width="50%" align="right"> 
      <?if($dentaldata["data"]["AGE"]) { ?>
      	<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Age&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$dentaldata["data"]["AGE"]?>year(s)</label>
      <? } ?>
    </td>
  </tr>
</table>
 <div class="col-lg-12" >
    <div class="clearfix">&nbsp;</div>
    <div class="clearfix">&nbsp;</div>
	<table  class="printtable gst_table container col-md-12" style="width: 100%;" >
	  <thead>
	    <tr>
	      <th align="center" width="5%">#</th>
	      <th align="center" width="10%">Date</th>
	      <th align="center" width="13%">Tooth Number</th>
	      <th align="left" width="25%">Procedure</th>
	      <th align="left">Notes</th>
	    </tr>
	  </thead>
	  <tbody>
	  	 	<? $i = 0;
	  	 	foreach ($dentaldata["data"]["DENTAL_COMPLAINT"] as $key => $value) 
	 		{ $i++;?>
				<tr>
					<td align="center"><?=$i?></td>
					<td align="center"><?=$value["CREATEDATE"]?></td>
					<!-- <td align="center"><?//=$dentaldata["data"]["PATIENT_TYPE"] == 1 ? $value["TOOTH_NUMBER"] : ?></td> -->
					<td align="center"><?=$value["TOOTH_NUMBER"]?></td>
					<td><?=$value["PROCEDURE"]?></td>
					<td><?=nl2br($value["DESCRIPTION"])?></td>
				</tr>
			<? } ?>
	  </tbody>
	</table>
</div>
