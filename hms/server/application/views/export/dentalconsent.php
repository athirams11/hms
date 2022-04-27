
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
   .doubleUnderline { padding:0px;border-bottom: 1px solid; }
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
<?if(!empty($consent["data"]["LANGUAGE_READ"]) && $consent["data"]["LANGUAGE_READ"]==1){ ?>
   <div class="clearfix">&nbsp;</div>

<h5 align="center"  style="font-weight: bold;"><span class="doubleUnderline">Dental Consent</span></h5>

<div class="clearfix">&nbsp;</div>
<table style="width: 100%;border: none;" >
   <tr>
      <td width="100%">
         <label><b>1. Treatment Plan</b></label><br>
         I understand the recommended treatment and my financial responsibility as explained to me.<br>
         I understand that by signing this consent I am in no way obligated to any treatment. I also acknowledge that during treatment it may be necessary to change or add procedures because of conditions found while working on the teeth that were not discovered during examination. For example, root canal therapy following routine restorative procedures.
      </td>
   <tr>
   <tr>
      <td width="100%">
         <label><b>2. Drug and Medications</b></label><br>
         I understand that antibiotics, analgesics and other medications can cause allergic reactions such as redness and swelling tissue, pain, itching, vomiting and/or anaphylactic shock.
      </td>
   <tr>
   <tr>
      <td width="100%">
         <label><b>3. Extractions</b></label><br>
         Alternatives to removal of teeth have been explained to me (root canal therapy, crown and bridge procedures, periodontal therapy, etc.)<br>
I understand removing teeth does not always remove the infection, if present, and may be necessary to have further treatment.<br>
I understand the risks involved in having teeth removed, some of which are pain, swelling, spread of infection, dry socket, loss of feeling in my teeth, lips, tongue and surrounding tissue (paresthesia) that can last for an indefinite period of time, or fractured jaw.<br>
I understand I may need further treatment by a specialist if complications arise during or following treatment, the cost of which is my responsibility.
      </td>
   <tr>
   <tr>
      <td width="100%">
         <label><b>4. Crown's, Bridges, Veneers</b></label><br>
         I understand that sometimes it is not possible to match the color of natural teeth exactly with artificial teeth.<br>
I further understand that I may be wearing temporary crowns, which come off easily and that I must be careful to ensure that they are kept on until the permanent crown is delivered.<br>
I realize the final opportunity to make changes (shape of, fit, size and color) will be before cementation. It is also my responsibility to return for permanent cementation within 20 days from tooth preparation. Excessive delays may allow for tooth movement. This may necessitate a remake of the crown or bridge.<br>
I understand there will be additional charges for remakes due to my delaying permanent cementation.
      </td>
   <tr>
   <tr>
      <td width="100%">
         <label><b>5. Endodontic Therapy</b></label><br>
         I realize there is no guarantee that root canal treatment will save my tooth, and that complications can occur from the treatment, and that occasionally root canal filling material may extend through the tooth which does not necessarily affect the success of the treatment.<br>
I understand that endodontic files and reamers are very fine instruments and stresses and defects in their manufacture can cause them to separate during use.<br>
I understand that occasionally additional surgical procedures may be necessary following root canal treatment (apicoectomy). I understand that the tooth may be lost in spite of all efforts to restore it.      </td>
   <tr>
   <tr>
      <td width="100%">
         <label><b>6. Periodontal Disease</b></label><br>
         I understand that I have been diagnosed with a serious condition, causing gum and bone inflammation and/or loss and that the result could lead to the loss of teeth. Alternative treatments have been explained to me, including gum surgery, tooth extraction and/or replacement.
      </td>
   <tr>
   <tr>
      <td width="100%">
         <label><b>7. Fillings</b></label><br>
         I understand that care must be exercised in chewing on filling teeth, especially during the first 24 hours to avoid breakage.<br>
I understand that a more extensive restorative procedure than originally diagnosed may be required due to additional or extensive decays<br>
I understand that significant sensitivity is a common after effect of newly placed fillings.
</td>
   <tr>
   <tr>
      <td width="100%">
         <label><b>8. Partials and Dentures</b></label><br>
         I understand the wearing of partials/dentures is difficult. Sore spots, altered speech, and difficulty in eating are common problems. Immediate dentures (placement of dentures immediately after extractions) may be painful. Immediate dentures may require considerable adjusting and several relines. A permanent reline will be needed at a later date. This is not included in the denture fee.<br>
I understand that it is my responsibility to return for delivery of my partial/denture<br>
I understand that failure to keep my delivery appointment may result in poorly fitted dentures. If a remake is I understand that dentistry is not an exact science and that, therefore, reputable practitioners cannot property guarantee results. I acknowledge that no guarantee or assurance has been made by anyone regarding the dental treatment, which I have requested and authorized.
</td>
   <tr>
</table>
<?}else{?>

   <div class="clearfix">&nbsp;</div>

<h5 align="center"  style="font-weight: bold;"><span class="doubleUnderline">الموافقة على الأسنان
</span></h5>

<div class="clearfix">&nbsp;</div>
<table style="width: 100%;border: none;"  dir="rtl">
   <tr>
      <td width="100%" >أفهم العلاج الموصى به ومسؤوليتي المالية كما هو موضح لي. أفهم أنه من خلال التوقيع على هذه الموافقة ، لست ملزماً بأي شكل من الأشكال بأي علاج. وأقر أيضًا أنه قد يكون من الضروري أثناء العلاج تغيير أو إضافة إجراءات بسبب
      <br>
      الحالات التي وجدت أثناء العمل على الأسنان التي لم يتم اكتشافها أثناء الفحص. على سبيل المثال ،علاج قناة الجذر بعد إجراء حشوة محافظة روتينية</td>
   </tr><hr>
   <tr>
      <td width="100%" >
      الأدوية والاستطبابات:<br>
      أفهم أن المضادات الحيوية والمسكنات والأدوية الأخرى يمكن أن تسبب تفاعلات حساسية مثل الاحمرار وتورم الأنسجة والألم والحكة والقيء و / أو صدمة الحساسية
      </td>
      </tr><hr>
   <tr>
      <td width="100%"  >
      تم شرح بدائل إزالة الأسنان (علاج قناة الجذر ، وإجراءات التاج والجسور ، علاج اللثة ، إلخ.) أفهم أن إزالة الأسنان لا تزيل دائمًا الالتهاب او الانتان ، إذا كانت موجودة ، وقد تكون هناك ضرورة لمزيد من العلاجات . أتفهم المخاطر التي<br>
      تنطوي عليها إزالة الأسنان ، وبعضها الألم ، والتورم ، وانتشار الالتهاب ، والجيب الجاف ، وفقدان الإحساس في أسناني ، والشفاه ، واللسان والأنسجة المحيطة (تشوش الحس) التي يمكن أن تستمر لفترة غير محددة من الوقت ، أو كسر في<br>
      الفك. أفهم أنني قد أحتاج إلى مزيد من العلاج من قبل أخصائي إذا ظهرت مضاعفات أثناء العلاج أو بعده ، وتكون تكلفتها هي مسؤوليتي</td>
      </tr><hr>
   <tr>
      <td width="100%"  >
      أتفهم أنه في بعض الأحيان لا يمكن مطابقة لون الأسنان الطبيعية بالأسنان الاصطناعية تمامًا.<br>
      أتفهم أيضًا أنني قد أضع تيجانًا مؤقتة ، والتي تنزع بسهولة وأنني يجب أن أكون حريصًا على ضمان الاحتفاظ بها حتى يتم تسليم التاج الدائم.<br>
      أدرك أن الفرصة الأخيرة لإجراء تغييرات (الشكل والملاءمة والحجم واللون) ستكون قبل الإلصاق النهائي.<br>
      من مسؤوليتي أيضًا أن أعود للتثبيت الدائم في غضون 20 يومًا من إعداد الأسنان. قد يسمح التأخير المفرط بحركة الأسنان. قد يتطلب هذا إعادة تصنيع التاج أو الجسر. أفهم أنه ستكون هناك رسوم إضافية على عمليات إعادة التصنيع بسبب<br>
      تأخري في الإسمنت الدائم.
      </td>
      </tr><hr>
   <tr>
      <td width="100%"  >
         أدرك أنه لا يوجد ضمان بأن علاج قناة الجذر سينقذ أسناني ، وأن المضاعفات يمكن أن تحدث من العلاج ، وأن مادة حشو قناة الجذر قد تمتد أحيانًا من خلال السن مما لا يؤثر بالضرورة على نجاح العلاج .<br>
         أتفهم أن أدوات علاج الأقنية اللبية هي أدوات دقيقة جدًا ، ويمكن للضغوط والعيوب في تصنيعها أن تتسبب في انفصالها أثناء الاستخدام. أتفهم أنه قد يلزم أحيانًا إجراء عمليات جراحية إضافية بعد علاج قناة الجذر (عملية قطع ذروة الجذر). أفهم<br>
         أن السن قد يفقد بالرغم من كل الجهود المبذولة لاستعادته.<br>
         </tr><hr>
   <tr>
      <td width="100%"  >
         أتفهم أنه قد تم تشخيص حالتي بحالة جدية حرجة ، مما تسبب في التهاب و / أو فقدان اللثة والعظام وأنها بالنتيجة قد تؤدي إلى فقدان الأسنان.<br>
         شرحت لي العلاجات البديلة ، بما في ذلك جراحة اللثة ، وقلع الأسنان و / أو استبدالها.
      </td>
      </tr><hr>
   <tr>
      <td width="100%"  >
      أتفهم أنه يجب توخي الحذر عند المضغ على حشو الأسنان ، خاصة خلال الـ 24 ساعة الأولى لتجنب الكسر.<br>
      أتفهم أن إجراء علاجات ترميمية أكثر شمولاً مما تم تشخيصه في الأصل قد تكون مطلوبًة تبعا لاصابات او نخور إضافية<br>
      اتفهم أن الحساسية الشديدة هي أمر شائع بعد عمل الحشوات الموضوعة حديثًا.
</td>
</tr><hr>
   <tr>
      <td width="100%"  >
      أتفهم أن ارتداء الأجزاء الجزئية / أطقم الأسنان أمر صعب. تعتبر البقع المؤلمة ، وتغيير الكلام ، وصعوبة تناول الطعام من المشاكل الشائعة. قد تكون أطقم الأسنان الفورية (وضع أطقم الأسنان بعد الاستخراج مباشرة) مؤلمة. قد تتطلب أطقم<br>
      الأسنان الفورية تعديلًا كبيرًا وعدة علاجات. سوف تكون هناك حاجة إلى مقوم دائم في وقت لاحق. لم يتم تضمين هذا في رسوم طقم الأسنان. أنا أتفهم أنه من مسؤوليتي العودة لتسليم بلدي<br>
      جزئي / أسنان. أفهم أن الفشل في الاحتفاظ بموعد التسليم قد يؤدي إلى أطقم أسنان سيئة التركيب. إذا كان طبعة جديدة<br>
      مطلوب بسبب تأخري لأكثر من 30 يومًا ، يمكن تكبد رسوم إضافية
<br>أتفهم أن طب الأسنان ليس علمًا دقيقًا ، وبالتالي ، فإن الممارسين ذوي السمعة الطيبة لا يمكنهم ضمان النتائج. أقر بأنه لم يتم تقديم أي ضمان أو تأكيد من قِبل أي شخص فيما يتعلق بعلاج الأسنان ، الذي طلبته وسمح به</td>
</tr><hr>
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