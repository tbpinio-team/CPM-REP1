<?php
$_FILE_NAME = basename($_SERVER['REQUEST_URI'], '?'.$_SERVER['QUERY_STRING']); ?>
<?php if($_FILE_NAME == "contract_details.php"){ 
	$FirmID = $_SESSION['dbe_firm_id'];
	$Tab1_Q1 = 'SELECT *
	FROM prime_contractor pc
	JOIN sub_contractor sc ON pc.contract_number = sc.contract_number
	WHERE pc.contract_number = sc.contract_number AND pc.dbe_firm_id = '.$FirmID.'
	GROUP BY pc.contract_id ORDER BY pc.contract_id DESC';
	$Tab1_Q1R = mysqli_query($con_AWT,$Tab1_Q1) or die(mysqli_error());  
	$Tab1_Q1D = mysqli_fetch_array($Tab1_Q1R); 
	
   // echo "<pre>";print_r($Tab1_Q1D);exit; 
	?>
   <div class="row">
	   <div class="col-lg-6 col-xs-12">
		 <div class="box">
		   <!-- <div class="box-header">
			 <h3 class="box-title">Responsive Hover Table</h3>

			 <div class="box-tools">
			   <div class="input-group input-group-sm" style="width: 150px;">
				 <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

				 <div class="input-group-btn">
				   <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
				 </div>
			   </div>
			 </div>
		   </div> -->
		   <!-- /.box-header -->
		   <div class="box-body table-responsive no-padding">
			 <table class="table table-hover">
			   <tr>
				 <td>Company Name</td>
				 <td><strong><?php echo $Tab1_Q1D['prime_contractor_name']; ?></strong></td>
			   </tr>
			   <tr>
				 <td>Contract Name</td>
				 <td><strong><?php echo $Tab1_Q1D['description_of_work']; ?></strong></td>
			   </tr>
			   <tr>
				 <td>Contract Number</td>
				 <td><strong><?php echo $Tab1_Q1D['contract_number']; ?></strong></td>
			   </tr>
			   <tr>
				 <td>Contract Amount</td>
				 <td><strong>$<?php echo number_format($Tab1_Q1D['award_amount']); ?></strong></td>
			   </tr>
			 </table>
		   </div>
		   <!-- /.box-body -->
		 </div>
		 <!-- /.box -->
	   </div>
	 </div>
 <?php } 
if($_FILE_NAME != "contract_details.php"){
include("../includes/header.php");
include("../includes/top_nav.php");
include("../includes/side_bar.php");
include ("../functions/functions.php"); 
}
$NoResult = '<div class="alert alert-danger tab3_boxes" style="float:left;width:100%;margin-top:25px;"><div class="col-lg-4"><h3 style="margin-top:68px;color:#fff;">No Result Found!</h3></div></div>';
$UserOfferCodes = $_SESSION['user_offer_codes'];
$UserOfferScore = $_SESSION['user_offer_score']; 
$UserCertificationID = $_SESSION['certification_id'];
$UserID = $_SESSION['user_id'];
if($UserOfferCodes!='' && $UserOfferScore!=0){
$Score = explode(',',$UserOfferCodes);
$OfferCodes='';
$Index3_offer = 1;
foreach($Score as $key=>$val){
	$val = str_replace(' ','',$val);
	if($Index3_offer>1){
		$OfferCodes .=' OR po.offer_code LIKE "%'.$val.'%"';
	}else{
		$OfferCodes .=' po.offer_code LIKE "%'.$val.'%"';
	}
$Index3_offer++;	
} 	
$Tab3_Q1 = 'SELECT *
FROM partner_offer po
LEFT JOIN user_offer_status uos ON po.partner_offer_id = uos.partner_offer_id
WHERE ('.$OfferCodes.') 
AND po.offer_score <= '.$UserOfferScore.' 
AND uos.offer_status_user_id = '.$UserID.'
AND uos.user_minimum_score <= '.$UserOfferScore.'
';	 	

$Tab3_Q1R = mysqli_query($con_PRMSUB,$Tab3_Q1) or die(mysqli_error());
if(mysqli_num_rows($Tab3_Q1R)>0){
	while($OfferData = mysqli_fetch_assoc($Tab3_Q1R)){
		
$PartnerOfferID = $OfferData['partner_offer_id'];
$InsQ = "INSERT INTO `partner_offer_views`(`partner_offer_user_id`,`partner_offer_id`) VALUES('".$UserID."','".$PartnerOfferID."')";
mysqli_query($con_PRMSUB,$InsQ) or die(mysqli_error());

// Partner Data
$Tab3_Q4 = 'SELECT * FROM `partner` WHERE `partner_id`='.$OfferData['partner_id'].'';
$Tab3_Q4R = mysqli_query($con_MAIN,$Tab3_Q4) or die(mysqli_error());	
if(mysqli_num_rows($Tab3_Q4R)>0){
	$PartnerName1 = mysqli_fetch_array($Tab3_Q4R); 
	$Logo = $PartnerName1['partner_logo_url'];
	if($Logo == ''){$Logo = 'assets/img/companylogo.png';}
	}else{ 	$Logo = 'assets/img/companylogo.png';}	
	
	$PartnerName = $PartnerName1['partner_name']; 
//UserCompany Name
$Tab3_Q5 = 'SELECT * FROM `sbdvbe` WHERE `Certification ID`='.$UserCertificationID.'';
$Tab3_Q5R = mysqli_query($con_MAIN,$Tab3_Q5) or die(mysqli_error());
if(mysqli_num_rows($Tab3_Q5R)>0){
	$UserC = mysqli_fetch_array($Tab3_Q5R);
	$UserCompany = $UserC['Legal Business Name'];
}else{
	$UserCompany='';
}

// For View Log
$S1 = 'SELECT `finserv_id` FROM `finserv` WHERE `partner_id`='.$OfferData['partner_id'].' AND `offer_id`='.$PartnerOfferID.''; 
$S1R = mysqli_query($con_PRMSUB,$S1) or die(mysqli_error());

$Tab2_Q5 = 'SELECT `cfm_id`,`content_code` FROM `cfm` WHERE `type`="Best Practices" LIMIT 1';
$Tab2_Q5R = mysqli_query($con_PRMSUB,$Tab2_Q5) or die(mysqli_error());
$Tab2_Index5 = 1;
$Tab2_Q5D = mysqli_fetch_assoc($Tab2_Q5R);

if(mysqli_num_rows($S1R)>0){
	$FinSer = mysqli_fetch_assoc($S1R);
	$FinServID = $FinSer['finserv_id'];
$CFMid	 = $Tab2_Q5D['cfm_id'];
$UserID		 = $_SESSION['user_id'];
$Insert = "INSERT INTO `finserv_views`(`finserv_user_id`,`finserv_id`,`finserv_view_date`) VALUES ('".$UserID."','".$FinServID."','".date('Y-m-d H:i:s')."')";
$Tab2_Q5R = mysqli_query($con_PRMSUB,$Insert) or die(mysqli_error());
} 	
?>									
						
  
									
	<div class="tab3_boxes" style="float:left;width:100%;margin-top:25px;padding:10px;">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="text-align: center;background:#9ec7c2;height: 230px;padding:0%;">
		<h4 style="color:white;background: #2e9288fa;padding: 2%;margin-top: 0%;"><?php echo $PartnerName; ?></h4><br /><?php if(isset($Logo)){ ?>
		<img src="<?php echo base_url.$Logo; ?>" alt="" width="200" />
		<?php } else{  ?>
		<img src="<?php echo base_url; ?>assets/img/partnerLogo.PNG" alt="" style="width:92px;margin-left: 3px;" />
		<?php } ?>
		<br />
			<a href="#more_info_popup" role="button" data-toggle="modal" onclick="showpartnerdescription(<?php echo $OfferData['partner_id']; ?>)" class="btn btn-sm btn-primary btn-white btn-round tab3btn" type="button" style="background:#EAF2F8 !important;color:#10253F !important;border:none;">
				<span class="bigger-110" style="color:#10253F !important;">MORE INFO</span>
				<i class="icon-on-right ace-icon fa fa-arrow-right"></i>
			</a>
		</div>
		
		<div class=" profile-user-info-striped col-lg-4 col-md-4 col-sm-4 col-xs-4" style="background:#133e64;height: 230px;">
			<h4 style="color:#ffcc00;"><?php echo $OfferData['offer_title']; ?></h4>
			<h4 style="color:#fff;"><?php echo $OfferData['offer_type']; ?></h4>
		<hr>
			<h4 style="color:#fff;text-transform:capitalize;font-size:19px;text-align:center;"><?php echo $UserCompany; ?></h4> 
			<div class="profile-info-row">
				<div class="profile-info-name" style="color:white;"> YOU ARE </div>
				<div class="profile-info-value">
					<span id="username" class="editable"><span class="label label-success arrowed-in arrowed-in-right" style="color:white;"><?php echo $OfferData['offer_text']; ?></span></span>
				</div>
			</div>
			
			<div class="profile-info-row">
				<div class="profile-info-name" style="color:white;"> UP TO </div>
				<div class="profile-info-value">
					<span id="username" class="editable" style="color:white;">$ <?php echo number_format($OfferData['offer_up_to_amount'],0); ?></span>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pull-right" style="background:#133e64;height: 230px;">
			<a href="#modal-table" role="button" data-toggle="modal" onclick="showofferboxpopup(<?php echo $OfferData['offer_box_id']; ?>,'<?php echo $PartnerName; ?>')" class="btn btn-sm btn-primary btn-white btn-round tab3btn" type="button" style="margin-top:120px;float:right;">
				<i class="ace-icon fa fa-rss bigger-150 middle orange2"></i>
				<span class="bigger-110" style="font-size:12px !important;">CLICK HERE TO LEARN MORE</span>

				<i class="icon-on-right ace-icon fa fa-arrow-right"></i>
			</a>
		</div>
	</div>
<?php
	}// While close
}else{
// Find Special Case
include "tab3/specialcase.php";	
}
}else{echo $NoResult;}
?>							

<?php 
if($_FILE_NAME != "contract_details.php"){
include("../includes/footer.php");
}
 ?>