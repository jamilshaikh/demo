<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class maruti_suzuki extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this -> load -> library(array('table', 'form_validation', 'session'));
		$this -> load -> helper(array('form', 'url', 'number'));
		$this -> load -> model('new_car_model', '', TRUE);
		$this -> load -> model('blog_model', '', TRUE);
	}
	public function index()
	{
		redirect('maruti-suzuki/new-cars');
	}
	public function new_cars($type=null,$model=null) {
		
		
		$data['contact_detail']=$this->contact_detail_model->contact_detail();
		$query= $this -> new_car_model -> select_new_car($model);
		$data['model']=$model;
		$data['select_new_car'] =$query;
		$data['title'] = "Buy Maruti Suzuki Car Online in Mumbai & Pune - Autovista";
		$data['keyword'] = "buy new car, buy new car in mumbai, buy new car in pune, buy new car online,  book new car, new car price, buy maruti car, new car pics, car variants, car reviews";
		$data['description'] = "Buy Maruti Suzuki cars online in Mumbai, Navi Mumbai & Pune at Autovista. Check Maruti Suzuki price, features, reviews, Specifications & images of these cars.";
		$data["url"]= site_url()."maruti-suzuki/new-cars";
		$this -> load -> view("include/header.php",$data);		
		$this -> load -> view('new_car_main_view.php', $data);
		$this -> load -> view('include/footer.php');
	}
	public function change_session() {
		echo $location = $this -> input -> post('location');
		$this->contact_detail_model->get_location_detail($location);
		echo $this->session->userdata('location_url');
	}
	public function maruti_new_car($model_name=null,$variant_name=null,$location=NULL) {
		$data['contact_detail']=$q=$this->contact_detail_model->contact_detail();	
		$data['select_new_car_similar']=$this->new_car_model->select_new_car_similar();			
		if(is_null($location) && is_null($variant_name) && is_null($model_name))
		{
			$redirect_to_default=1;
		}		
		else if((is_null($location) || isset($location)) && (isset($variant_name)|| is_null($variant_name)) && isset($model_name))
		{
			//echo  $model_name; 
			$data['model_url']=$model_name;
			if(is_null($location))
			{
				$this->contact_detail_model->get_location_detail();	
				if(is_null($variant_name))
				{
					$variant_name=$this->session->userdata('location_url');
				}else
				{
					$location=$this->session->userdata('location_url');
				}
			}
			else {
				$this->contact_detail_model->get_location_detail($location);
				//echo $this->session->userdata('location_url');					
			}
			$model_query=$this -> new_car_model -> check_model($model_name);
			//print_r($model_query);
			if(count($model_query)>0)
			{
					$model_id=$model_query[0]->model_id;
				if(isset($variant_name))
				{
					$variant_id=$model_query[0]->variant_id;
					$data['select_info']= $this -> new_car_model -> select_info1($variant_id);	
					//print_r($data['select_info']);
					$data['select_price']=$this->new_car_model->check_price($model_id);	
					$data['select_color'] = $this -> new_car_model -> select_color($model_id);	
					if($variant_name=='specification')
					{
						$data['title'] = "";
						$data['keyword']='';					
						$data['description']="";
						$data['sub_header_url']=$variant_name;										
						$data['select_variant'] = $this -> new_car_model -> select_variant($model_id);
						//$data['select_color'] = $this -> new_car_model -> select_color($model_id);			
						$this -> load -> view("include/header.php",$data);
						//$this -> load -> view("new_car_tab_view.php",$data);
						$this -> load -> view('new_car_specification.php', $data);
						$this -> load -> view('new_car_similar.php', $data);
					}
					elseif($variant_name=='on-road-price')
					{
						$data['title'] = "";
						$data['keyword']='';					
						$data['description']="";	
						$data['sub_header_url']=$variant_name;
						$variant_id=$model_query[0]->variant_id;							
						//$data['select_price']=$this->new_car_model->check_price($model_id);	
						$data['showroom_location']=$this->new_car_model->showroom_location();	
						
						$data['select_variant'] = $this -> new_car_model -> select_onroad_variant($model_id);
						//$data['select_color'] = $this -> new_car_model -> select_color($model_id);			
						$this -> load -> view("include/header.php",$data);
						$this -> load -> view('new_car_onroad_price.php', $data);
						$this -> load -> view('new_car_similar.php', $data);
					}
					elseif($variant_name=='images')
					{
						//show images page
						//echo "images";
						$data['title'] = "";
						$data['keyword']='';					
						$data['description']="";
						$data['sub_header_url']=$variant_name;
						//$data['select_color'] = $this -> new_car_model -> select_color($model_id);
						$this -> load -> view("include/header.php",$data);
						//$this -> load -> view("new_car_tab_view.php",$data);
						$this -> load -> view('new_car_images.php', $data);
						$this -> load -> view('new_car_similar.php', $data);
					}
					elseif($variant_name=='colors')
					{
						//show images page
						//echo "images";
						$data['title'] = "";
						$data['keyword']='';					
						$data['description']="";
						$data['sub_header_url']=$variant_name;
						//$data['select_color'] = $this -> new_car_model -> select_color($model_id);
						$this -> load -> view("include/header.php",$data);
						//$this -> load -> view("new_car_tab_view.php",$data);
						$this -> load -> view('new_car_images.php', $data);
						$this -> load -> view('new_car_similar.php', $data);
					}
					elseif($variant_name=='faqs')
					{
						//show images page
						//echo "images";
						$data['title'] = "";
						$data['keyword']='';					
						$data['description']="";
						$data['sub_header_url']=$variant_name;
						$data['faq']=$this->new_car_model->all_faq($model_id);						
						$this -> load -> view("include/header.php",$data);
						$this -> load -> view('new_car_faq.php', $data);
						$this -> load -> view('new_car_similar.php', $data);
					}
					elseif($variant_name=='review')
					{
						$data['title'] = "";
						$data['keyword']='';					
						$data['description']="";
						$data['sub_header_url']=$variant_name;						
						$data['select_review']=$this->new_car_model->all_review($model_id);	
						$data['review_count']=$this->new_car_model->review_count($model_id);	
						$this -> load -> view("include/header.php",$data);						
						$this -> load -> view('new_car_review.php', $data);
						$this -> load -> view('new_car_similar.php', $data);
					}
					else
					{
						
							$data['select_review']=$this->new_car_model->review($model_id);	
							$data['review_count']=$this->new_car_model->review_count($model_id);	
						$q1=$this->db->query("select showroom_url,showroom_name from tbl_showroom_location where (showroom_url='$variant_name' || showroom_name='$variant_name') and status=1 ")->result();
						if(count($q1)>0)
						{
							
							$this->contact_detail_model->get_location_detail($q1[0]->showroom_url);
							
							$data['sub_header_url']='model';
						$data['faq']=$this->new_car_model->faq($model_id);	
					
					$query = $this -> new_car_model -> select_variant($model_id);
					$data['model_id'] = $model_id;
					$data['select_variant'] = $query;
					
					$query5= $this -> new_car_model -> select_info1($variant_id);
					$data['select_info'] =$query5;
					$data['select_var'] = $this -> new_car_model -> select_var_id($variant_id);
			
					$data['select_overview'] = $model_query;
					$data['blog_query'] = $this -> blog_model -> select_blog_2();
					
					$data['title'] = "Maruti Suzuki  Reviews, Specs & Images";
					$data['keyword']='';
					
					$data['description']="Get Maruti Suzuki showroom price in  with EMI details for all variants. Checkout its Features, Specification, Reviews, Images & Mileage.";
			
					$this -> load -> view("include/header.php",$data);
					$this -> load -> view('new_car_overview.php', $data);
					$this -> load -> view('new_car_similar.php', $data);
						}
						else {
							/*--echo "show variantwise data"- */
						
							$data['sub_header_url']='variant';
							$data['select_variant']=$query = $this -> new_car_model -> select_variant($model_id);
							$data['select_info']=$query5= $this -> new_car_model -> select_info_variant_page($model_id,$variant_name);
							$data['select_color'] = $this -> new_car_model -> select_color($model_id);
						
							$data['showroom_location']=$this->new_car_model->showroom_location();							
							$data['title'] = "Maruti Suzuki ". ucwords(str_replace('-', ' ',$model_name))." Price in ".$location." - Reviews, Specs & Images";
							$data['keyword']=$query5[0]->keyword;
							
							$data['description']="Get Maruti Suzuki ".ucwords(str_replace('-', ' ',$model_name))." showroom price in ".$location." with EMI details for all variants. Checkout its Features, Specification, Reviews, Images & Mileage.";
						
							$data['url']= site_url()."maruti-suzuki/maruti-new-car/".$model_name."/".$variant_name."/".str_replace(' ', '-', $_SESSION['location_url']);
							$this -> load -> view("include/header.php",$data);	
									
							$this -> load -> view('new_car_view.php', $data);
							$this -> load -> view('new_car_similar.php', $data);
						}	
					}
				}
				else {
					$redirect_to_default=1;
					
				}
			}
			else
			{
				$redirect_to_default=1;
			}
		}
		else {
			$redirect_to_default=1;
		}
		if(isset($redirect_to_default))
		{
		
			redirect('maruti-suzuki/new-cars');
		}		
		$this -> load -> view('include/footer.php');
		
	}
	
	public function new_car_detail($model_name,$location=null, $variant_name) {
		
			
		if($model_name=='' || $location=='' || $variant_name=='')
		{
			redirect('');
		}	
		elseif($model_name=='ciaz')
		{
			redirect("https://autovista.in/nexa-car/detail/the-new-ciaz/Mumbai/667");
		}
		$location=str_replace('%20', ' ', $location);
		$_SESSION['location'] = $location;
		if (isset($_SESSION['location'])) {
	

		} else {

			$location = 'Mumbai';
			$_SESSION['location'] = $location;
		}
		if (isset($_SESSION['location_web'])) {
        } else {
        $location = 'Mumbai';
            $_SESSION['location_web'] = $location;
        }
		
		
		if(is_numeric($variant_name))
		{
			$variant_id=$variant_name;
			$select_id1 = $this -> new_car_model -> select_model_variant_name($variant_id);
			if(count($select_id1)>0)
			{
			$model_name=$select_id1[0] -> model_name;
			$model_name1 = str_replace('-', '_', $model_name);
			$m_name = str_replace(' ', '-', $model_name1);
			$variant_name=$select_id1[0] -> variant_name;
			$variant_name1 = str_replace('-', '_', $variant_name);
			$v_name = str_replace(' ', '-', $variant_name1);
			redirect('maruti-suzuki/maruti-new-car/'.$m_name.'/'.$v_name.'/'.$_SESSION['location_web']);			
			}
			else {
				$select_id1 = $this -> new_car_model -> select_model_variant_default();
					$model_name=$select_id1[0] -> model_name;
					$model_name1 = str_replace('-', '_', $model_name);
					$m_name = str_replace(' ', '-', $model_name1);
					$variant_name=$select_id1[0] -> variant_name;
					$variant_name1 = str_replace('-', '_', $variant_name);
					$v_name = str_replace(' ', '-', $variant_name1);
			redirect('maruti-suzuki/maruti-new-car/'.$m_name.'/'.$v_name.'/'.$_SESSION['location_web']);	
			}
		}
	else
	{
		$select_id1 = $this -> new_car_model -> select_model_variant_default();
		$model_name=$select_id1[0] -> model_name;
		$model_name1 = str_replace('-', '_', $model_name);
		$m_name = str_replace(' ', '-', $model_name1);
		$variant_name=$select_id1[0] -> variant_name;
		$variant_name1 = str_replace('-', '_', $variant_name);
		$v_name = str_replace(' ', '-', $variant_name1);
		redirect('maruti-suzuki/maruti-new-car/'.$m_name.'/'.$v_name.'/'.$_SESSION['location_web']);	
	}
		$data['contact_detail']=$this->contact_detail_model->contact_detail();
		 
		$data['select_new_car_similar']=$this->new_car_model->select_new_car_similar();
		
		$query1 = $this -> new_car_model -> select_model_id($variant_id);
		$model_id = $query1[0] -> model_id;
		$query = $this -> new_car_model -> select_variant($model_id);
		$data['model_id'] = $model_id;
		$data['select_variant'] = $query;
		
		$query5= $this -> new_car_model -> select_info1($variant_id);
		$data['select_info'] =$query5;
		$data['select_var'] = $this -> new_car_model -> select_var_id($variant_id);

		$data['select_overview'] = $this -> new_car_model -> select_overview($model_id);
		$data['blog_query'] = $this -> blog_model -> select_blog_2();
		//$variant_id = $query[0] -> variant_id;
		$data['select_features'] = $this -> new_car_model -> select_features($variant_id);
		$data['select_color'] = $this -> new_car_model -> select_color($model_id);
		$data['select_new_car'] = $this -> new_car_model -> select_query();
			/*select meta data */
		//$data['title'] = $query5[0]->title;
		$data['title'] = "Maruti Suzuki ". ucwords(str_replace('-', ' ',$model_name))." Price in ".$location." - Reviews, Specs & Images";
		$data['keyword']=$query5[0]->keyword;
		//$data['description']=$query5[0]->description;
		$data['description']="Get Maruti Suzuki ".ucwords(str_replace('-', ' ',$model_name))." showroom price in ".$location." with EMI details for all variants. Checkout its Features, Specification, Reviews, Images & Mileage.";
	
			$data['url']= site_url()."maruti-suzuki/new-car-detail/".$model_name."/".$location."/".$variant_name;
		$this -> load -> view("include/header.php",$data);
		$this -> load -> view("include/header1.php");
		$this -> load -> view('new_car_view.php', $data);
		$this -> load -> view('include/footer_other.php');

	}

	public function download_brochure($model_name) {
		

		$query1 = $this -> db -> query("select * from make_models where model_id='$model_name'");

		foreach ($query1->result() as $fetch) {
			$name = $fetch -> brochure;
		}
		$file = file_get_contents(base_url().'assets/Brochure/'.$name);

		$new_name="brochure.pdf";
		 $this->load->helper('file');

	//load the download helper
		$this->load->helper('download');
   			
		//use this function to force the session/browser to download the created file
		force_download($name, $file);

	}

	



public function arena()
{
	$data['contact_detail']=$this->contact_detail_model->contact_detail();		
	$data['title'] = "Maruti Suzuki to launched new ARENA showrooms in India";
	$data['keyword'] = "Maruti Suzuki car dealerships, maruti suzuki arena, arena maruti suzuki showroom ,maruti rebranding, launch, Maruti Arena showrooms in Mumbai, Maruti Arena showrooms in Navi Mumbai , Maruti Arena showrooms in Pune, Maruti  Arena showroom Kharghar, Autovista, Maruti Suzuki car dealership In Mumbai";
	$data['description'] = "Maruti Suzuki to launched new ARENA showrooms in India – Check Here area unit all the features, pictures and different details of the new premium dealerships from Maruti.";
	$data['url']= site_url()."maruti-suzuki/arena";
	$this -> load -> view("include/header.php",$data);
	$this -> load -> view('maruti_suzuki_areana_view.php');
	$this -> load -> view('include/footer_other.php');
}
public function change_specification_variant()
{
	 $variant_url=$this -> input -> post('variant_url');
  $model_id=$this -> input -> post('model_id');
	  $model_url=$this -> input -> post('model_url');
	$data['select_info']= $q=$this -> new_car_model -> select_info_variant_page($model_id,$variant_url);	
	$data['select_variant'] = $this -> new_car_model -> select_variant($model_id);
	
	$this -> load -> view('new_car_specification_filter.php', $data);
}
public function change_on_road_price()
{
	 $location=$this -> input -> post('location');
	  $this->contact_detail_model->get_location_detail($location);
	  $model_id=$this -> input -> post('model_id');
	  $model_url=$this -> input -> post('model_url');
	$select_variant = $this -> new_car_model -> select_onroad_variant($model_id);
	?>
	<table class="table" style="">
							            			<tr >
							                				<th>Variants</th>
							                			<th> Ex Showroom Price     </th>
							                			<th class="text-center"> Book	 </th>
							                		</tr>
							                		<?php 
							                $i=0;	
							                	foreach($select_variant as $fetch3){
							                		 ?>
											<tr>
												<th><a href='<?php echo site_url()?>maruti-suzuki/maruti-new-car/<?php echo $model_url.'/'.$fetch3 -> variant_url.'/'.$this->session->userdata('location_url')?>'><?php echo $fetch3 -> variant_name; ?></a></th>
												<td><i class="fa fa-inr" aria-hidden="true"></i>  <?php
												if ($this->session->userdata('location_web') == 'Pune' || $this->session->userdata('location_web') == 'Pune Magarpatta' || $this->session->userdata('location_web') == 'Pune Hadapsar' || $this->session->userdata('location_web') == 'Pune Baner') {
													$price = $fetch3 -> ex_showroom_pune_price;
												} elseif ($this->session->userdata('location_web') == 'Navi Mumbai') {
													$price = $fetch3 -> ex_showroom_navi_mumbai_price;
												} else {
													$price = $fetch3 -> ex_showroom_price;
												}
												setlocale(LC_MONETARY, 'en_IN');
												$amount = money_format('%!i', $price);
												$amount = explode('.', $amount);
												//Comment this if you want amount value to be 1,00,000.00
												echo $amount[0];
													?><?php if($fetch3->quotation_id!='')
													{ $i++;
														?>&nbsp;
													<span id='fap<?php echo $i;?>' class='fa fa-plus bgchange' style='padding: 7px;color: #fff;' onclick="showonroad('<?php echo $i;?>')"></span>
													<span id='fam<?php echo $i;?>' class='fa fa-minus bgchange' style='padding: 7px;color: #fff;display:none' onclick="hideonroad('<?php echo $i;?>')"></span>
													<?php
													}?></td>
													<td>
							
												<button type="button" class="btn-primary site-button bgchange btn-block" title="READ MORE" rel="bookmark" data-toggle="modal" data-target="#book_now_form">Book Now</button>
					
							
												</td>   
						</tr>
							<tr>
								<td colspan='3' style='border: none'>
									<div style='display: none' id='on<?php echo $i;?>' class='bg-gray'>
									<table  >
										<tr>
											<th >Ex Showroom </th>
											<th >Registration </th>
											<th >Other </th>
											<th >On Road </th>
										</tr>
										<tr>
											<td><i class="fa fa-inr" aria-hidden="true"></i><?php 	
													echo $price ;
												?>
											</td>
											<td><i class="fa fa-inr" aria-hidden="true"></i><?php 	
													echo $fetch3-> individual_registration_with_hp ;
												?>
											</td>
											<td><i class="fa fa-inr" aria-hidden="true"></i>
												<?php echo $other=$fetch3-> individual_on_road_price-($price + $fetch3 -> individual_registration_with_hp)	; ?>
											</td>
											<td><i class="fa fa-inr" aria-hidden="true"></i><?php 	
													echo $fetch3 -> individual_on_road_price ;
												?>
											</td>
										</tr>
									</table>	
									</div>	
								</td>					
							</tr>
						<?php 
							
							} ?>
                		</table>
	<?php
}
		/*<script type="application/ld+json">{
        "@context":"http://schema.org/",
        "@type":"Car",
        "name": <?php echo $model_url;?>,
        "description": "Maruti Alto 800 is an upgraded version of its first small hatchback car in India. Browse Alto 800 features, specifications, mileage, colours, price & review online.",
        "model":"Alto 800",
        "brand": "Maruti Suzuki", "image":"http://marutisuzuki.com/-/media/images/maruti/marutisuzuki/car/car-profile-shots/alto-800/alto800_mojito_green.ashx?h=398&la=en&w=680",
        "vehicleEngine":
        {"@type":"EngineSpecification","name":"796 CC"},
        "fuelEfficiency":
        {"@type":"QuantitativeValue","name":"22.05 kmpl"},
        "manufacturer":{"@type":"organization","name":"Maruti Suzuki India Limited"},
        "offers": { "@type": "AggregateOffer","priceCurrency": "INR","lowPrice": "245803.00", "highPrice":"376164.00" }
        }
</script>*/
}
	 
?>
			