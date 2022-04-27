<style type="text/css">
    label 
    { 
        font-size: 10px;
    }
    .h5, h5 
    { 
        font-size: 1.25rem; 
    }
    .h4, h4 
    { 
        font-size: 1.50rem; 
    }
    .dottedUnderline, label
    {
        line-height: 2.25em;
    }
    u {    
        border-bottom: 1px dotted #000;
        text-decoration: none;
    }
    .doubleUnderline { border-bottom: 3px solid; }
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
    .underline {
        border-bottom: 1px solid currentColor;
    }

    .underline--dotted {
        border-bottom: 1px black dotted;
    }
    .dottedUnderline { border-bottom: 1px dotted; }
    .centered{
        margin: 0 auto;
    }

    .rightaligned{
        margin-right: 0;
        margin-left: auto;
    }

    .leftaligned{
        margin-left: 0;
        margin-right: 0;
    }
    /*.gst_table thead th, .gst_table thead td 
    {
        border-bottom-width: 2px;
        border: 1px solid #222;
    }*/
</style>  
<table style="width: 100%;border: none;" >
  <tr>
    <td width="20%"> 
      <?if($institution["data"]["INSTITUTION_LOGO"]) { ?>
        <img  src="<?=$institution["logo_path"].$institution["data"]["INSTITUTION_LOGO"]?>" height="100px" width="auto"  >
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
<h4 align="center"  style="font-weight: bold;"><span class="doubleUnderline">WORK FITNESS</span></h4>
<div class="clearfix">&nbsp;</div>
<table style="width: 100%;border: none;" >
    <tr>
        <td width="50%" align="left"> 
            No &nbsp; :&nbsp;&nbsp;<?=$sickdata["data"]["CERTIFICATE_NUMBER"]?>
        </td>
        <td width="50%" align="right">
            Date &nbsp; :&nbsp;&nbsp;<?=date("d/m/Y",strtotime($sickdata["data"]["ISSUED_DATE"]))?>
        </td>
    </tr>
</table>
<div class="clearfix">&nbsp;</div>
<table style="width: 100%;border: none;" class="leftaligned">
    <tr>
        <td style=" line-height: 170%;" align="left">
            Please be advised that <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$sickdata["data"]["GENDER"] == 0 ? 'Ms' : 'Mr'?>&nbsp;<?=$sickdata["data"]["PATIENT_NAME"]?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> has attended the clinic and the following was observed <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$sickdata["data"]["SICK_REASON"]?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> Fit for/was given sick leave for <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$sickdata["data"]["DURATION"]?>&nbsp;Days&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> &nbsp;From&nbsp; <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=date("d/m/Y",strtotime($sickdata["data"]["FROM_DATE"]))?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> &nbsp;To&nbsp; <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=date("d/m/Y",strtotime($sickdata["data"]["TO_DATE"]))?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
        </td>
    </tr>
    <tr>
        <td align="center">
            <div class="clearfix">&nbsp;</div>
            <div class="clearfix">&nbsp;</div>
            <div class="clearfix">&nbsp;</div>
            <span style="font-weight: bold;font-size: 16px;">Signature & Stamp of Doctor</span>
            <div class="clearfix">&nbsp;</div>
            <div class="clearfix">&nbsp;</div>
            <div class="clearfix">&nbsp;</div>
            <div class="clearfix">&nbsp;</div>
            <div class="clearfix">&nbsp;</div>
            <div class="clearfix">&nbsp;</div>
        </td>
    </tr>
</table>

<table  style="border: none;">
    <tr> 
        <td align="left"  style="text-justify: auto;">
            <span>
                I hereby declare that I have been well explained
                regarding the procedure of sick leave as per the rules
                and regulations set by MOH and DOHMS.
                I confirm that this certificate will be used for internal
                administrative pupose of our office/company and I
                undertake all liability and legal responsibility for
                same.
            </span>
        </td>
    </tr>
</table>
<div class="clearfix">&nbsp;</div><div class="clearfix">&nbsp;</div>
<table width="100%" style="border: none;">
    <tr> 
        <td align="left" width="70%">
            <span>
                Date  :
            </span>
        </td>
         <td align="left" width="30%">
            <span>
                 Patient Sign  :
            </span>
        </td>
    </tr>
</table>
<div class="clearfix">&nbsp;</div>
                   