<?php 
    /*
    Plugin Name: yoel bootstrap easy product catalog 
    Plugin URI: 
    Description: create a responsive easily 
    Author: yoel rosfisher
    Version: 1.0
    Author URI:
    */
	
	include( 'cuztom/cuztom.php' );
	///500 error becuse its not on init sanitize_categoryaction add_action
	//add_action('init', array( $this, 'register_post_type' ) ); to
	//post type class constructor
	
	
/*
usge


*/



////////products///////////
//postype
$products = new Cuztom_Post_Type( 'yoel_products', array(
    'has_archive' => true,
    'supports' => array( 'title' )
	) );
	//product category s
$products->add_taxonomy( "products_category" );

	//_description_textarea
	$products->add_meta_box(
    'products_info',
    'products info',
	array(
    

        array(
        'id'            => '_main_img',
        'type'          => 'image',
        'label'         => 'main image',
		),
        array(
            'id'            => '_description_textarea',
            'type'          => 'textarea',
            'label'         => 'description',
			'description'   => ' will appear inside the product lightbox'
        )


	)
	);
	
	//images
	$products->add_meta_box(
        'product_imgs',
    'products content',
    array( 
        
		//add imges
		'bundle', 
        array(
            array(
                'name'          => 'img_title',
                'label'         => 'img title',
                'description'   => 'the imgs title',
                'type'          => 'text'
            ),
            array(
						'name'          => 'product_img',
						'label'         => 'inner Image',
						'description'   => 'select an img that will appear inside the product lightbox',
						'type'          => 'image',
					)
        )
    )
	);//end bundle



//shortcodes
//enqu scripts

///products output and shortcodes

///shortcodes
//[yproducts name="productstst"]
function yproducts_shortcode($atts) 
{
   extract(shortcode_atts(array(

	  'class' => "productstst",
	  'cat'=>'none',
	  'size'=>4
   ), $atts));

	return yget_products($class,$cat,$size);
}//end yproducts_shortcode

add_shortcode('yproducts', 'yproducts_shortcode');

function yget_products($class,$cat = "none",$size = 4)
{
	
	$html = "";

	//get products post id by name
	$posts =  yget_products_by_cat($cat);
	if ( !$posts->have_posts() ) {return;}//if no posts exit
	$html .="<div class='yproducts_container ".$class."'>";
	while ( $posts->have_posts() ) 
	{
		$posts->the_post();
		$id = get_the_ID(); 
		$imgs =  get_post_meta($id, '_product_imgs', true);
		$desc =  get_post_meta($id, '_description_textarea', true);
		$main_img =  get_post_meta($id, '_main_img', true);
		//$all_meta = get_post_meta($id, '', true);
		//var_dump($all_meta );
		
		//container
		
		$html .="<div class='y_single_products_container col-md-".$size."'>";
		
		$html .= "<h3>".get_the_title()."</h3>";
		
		$html .= "<div class='yproduct_main_img'>".wp_get_attachment_image( $main_img )."</div>";
		
		//lightbox imges
		$html .="<div class='ylightbox' id='".get_the_title()."'>";
		$html .="<div class='inner' >";
		$html .="<div class='inner_scroll' >";
		$html .= "<a href='#/' class='yclose'>close</a>";
		$html .= "<div class='main_lihtbox_img'>".wp_get_attachment_image( $imgs[0]['_product_img'],"full" )."</div>";
		$html .= "<h3>".get_the_title()."</h3>";
		$html .= "<p>".$desc."</p>";
		foreach($imgs as $yimg)
		{
			//var_dump($yimg);
			$img_title = $yimg['_img_title'];	
			$img_id = $yimg['_product_img'];
			
			//get img by id
			$html .="<div class='yinner_img col-md-2'>";
		
			$html .= wp_get_attachment_image( $img_id,"full");
			$html .="<h6>".$img_title."</h6>";
			$html .="</div><!--end yinner_img-->";
		}
		$html .="<div class='yclear'></div><!--end yclear-->";
		//end container
		$html .="</div><!--end inner_scroll-->";
		$html .="</div><!--end inner-->";
		$html .="</div><!--end ylightbox-->";
		$html .="</div><!--end y_single_products_container-->";
		
	}
	$html .="</div><!--end yproducts_container-->";
	/*
	//the products post id
	$id =$post->ID;

	//get imgs ids as array
	$imgs =  get_post_meta($post->ID, '_product_imgs', true);
	$info =  get_post_meta($post->ID, '_products_info', true);

	//container
	$html .="<div class='yproducts_container'>";
	//the ul tag <ul clas=blabla>
	$html .= yul_products_open_tag($name,$width,$type);
	
	foreach( $imgs as $img)
	{
		//single slide
		$html .= single_product_img($img,$height);
	}
	$html .= "</ul>";
	//display type
	if($type == "img_tabs")
	{
		
	}
	//end container
	$html .="</div>";
//	yproducts_fotter_js($name,$type) ;//echo js
*/
	wp_reset_query();
	yproducts_fotter_js($name,$type) ;
	return $html;//echo products
	
	
	
}//end yget_products


//products js

//js and php logic fun
function yproducts_fotter_js($id,$type) 
{
	$html = '<script>

    $( document ).ready(function () {
		
		//change viewport for bootrtrap

		$( "meta[name=\'viewport\']" ).attr("content","width=device-width, initial-scale=1.0");
		
		//lightbox
		y_lightbox(".ylightbox",".yproduct_main_img",".yclose")
	});';
	//if($type == "norm")	{}//end norm
	

	  

	//end script
	$html .= ' </script>';
	echo $html;
}//end yproducts_fotter_js


////////on activate plugin/////////
 
function yoel_bootstrap_product_catalog_activation_function()
{
	//create all cat
		
	wp_insert_term(
    'all',   // the term 
    'products_category', // the taxonomy
    array(
        'slug' => 'all'
    )
	);

}
register_activation_hook( __FILE__, 'yoel_bootstrap_product_catalog_activation_function' );


add_action( 'init', 'yproduct_init' );

function yproduct_init()
 {
	wp_insert_term(
    'all',   // the term 
    'products_category', // the taxonomy
    array(
        'slug' => 'all'
    )
	);
}

////////render helper funcs (genrate html or js)//////////






function single_product_img($img,$height)
{
		$html = "";
		//get the img id
		$img_id = $img["_product_img"];
		$html .= "<li>";
		//get the img url
		$img_url = wp_get_attachment_image_src($img_id);
		//$img_id .=wp_get_attachment_image($img_id);
		$html .= '<img src="'.$img_url[0].'" style="height: '.$height.'px;" />';
		if(isset($img["_img_title"]))
		{
				$html .='<p class="caption">'.$img["_img_title"].'</p>';
		}
		$html .= "</li>";
		return $html;
}//end single_slide



///end products output and shortcodes



//jqury (also makes sure no conflicts)

 
function my_jquery_enqueue_yoel_products() {
   wp_deregister_script('jquery');
   wp_register_script('jquery', "http" . ($_SERVER['SERVER_PORT'] == 443 ? "s" : "") . "://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js", false, null);
   wp_enqueue_script('jquery');
   
	//ylightbox
   wp_register_script('ylightbox',  plugins_url('js/ylightbox.js', __FILE__));
    wp_enqueue_script('ylightbox');
}



add_action( 'wp_enqueue_scripts', 'my_jquery_enqueue_yoel_products' );  

////encue style
function ywp_adding_styles_yoel_products() 
{

	//responsive products css
	//wp_enqueue_style('responsiveslides_css', plugins_url('css/responsiveslides.css', __FILE__));
	//wp_enqueue_script('responsiveslides_css');
	
	wp_enqueue_style('yproducts_css', plugins_url('css/yproducts_css.css', __FILE__));
	wp_enqueue_script('yproducts_css');
	
	//add bootstrap
	wp_enqueue_style( 'bootstrap-css', "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" );
	wp_enqueue_script( 'bootstrap-js', "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js");

}

add_action( 'wp_enqueue_scripts', 'ywp_adding_styles_yoel_products' ); 

//change viewport for bootstrap
add_action( 'wp_head', function() {
	//echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
} );

////permissions
add_action( 'in_admin_header', function()
{
	//only admin can edit
	if (!current_user_can('activate_plugins') && $_GET['post_type']=="yoel_products"){
        /*do something*/
			//return;
			
			exit("<h1>you dont have permission to use this page</h1>");
	}
} ); 


////add help page
function yadd_submenus_pages_yoel_products()
{
	add_submenu_page(
		'edit.php?post_type=yoel_products',
		'how to use yoel products', /*page title*/
		'how to use yoel products', /*menu title*/
		'manage_options', /*roles and capabiliyt needed*/
		'wnm_fund_set',
		'yhelp_page_yoel_products' /*replace with your own function*/
	);
}
add_action( 'admin_menu', 'yadd_submenus_pages_yoel_products' );

function yhelp_page_yoel_products()
{
	//chack user level_10
	if (current_user_can('level_10')){
        /*do something*/
		//	return;
	}
	echo '<div class="wrap"><h2>how to use</h2></div>';
	//$src = plugin_dir_path( __FILE__ ."help.swf");
	//$src =  plugins_url( 'help.swf' , __FILE__ );
	//vidio link
	//echo '<a href="'.$src.'">video tutorial</a>';
	//example shortodes
	echo "<h2>example shortcodes</h2>";
	echo "<h3>get all products</h3>";
	echo '<input type="text" value=\'[yproducts ]\' size="33" style="
    direction: ltr;
"/></br></br>';

	echo "<h3>get products by products_category</h3>";
	echo '<input type="text" value=\'[yproducts cat="some cat name" ]\' size="33" style="
    direction: ltr;
"/></br></br>';


	echo "<h3>change product bootstrap grid width (col-md-1-12 . 4 by default)</h3>";
	echo '<input type="text" value=\'[yproducts size="6" ]\' size="33" style="
    direction: ltr;
"/></br></br>';

	echo "<h3>custom container css class</h3>";
	echo '<input type="text" value=\'[yproducts class="my_class" ]\' size="33" style="
    direction: ltr;
"/></br></br>';

	echo "<h3>all options together</h3>";
	echo '<input type="text" value=\'[yproducts cat="some cat name" size="6"  class="my_class" ]\' size="63" style="
    direction: ltr;
"/></br></br>';


	
}//end yhelp_page_yoel_products

////////end products///////////


////////castum colloums/////////////////////
$postype = "yoel_products";

///////add colums////////
add_filter( 'manage_edit-'.$postype.'_columns',  

function ( $columns ) {
	//cullom names
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'title' ),
		//"shortcode" =>__( 'shortcode' )
		
		//
		//'title' => __( 'Movie' ),
		//'duration' => __( 'Duration' ),
		//'genre' => __( 'Genre' ),
		//'date' => __( 'Date' )
	);

	return $columns;
});

///add collum data///

////////end castum colloums/////////////////////


////helper funcs/////
if(!function_exists('yget_post_by_title')) {
	
function yget_post_by_title($page_title,$postype, $output = OBJECT) {
    global $wpdb;
        $post = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='".$postype."'", $page_title ));
        if ( $post )
            return get_post($post, $output);

    return null;
}

}//end function_exists

function yget_products_by_cat($cat)
{
	if($cat == "none")
	{
		$quary = 'post_type=yoel_products';
	}
	else
	{
		$cat = get_term_by('name', $cat, 'products_category');//get cat id
	
		if(!$cat){return;}//if not exist exit
		//$quary = 'post_type=yoel_products&products_category='.$cat->term_id;
		
		$quary = array("post_type"=>'yoel_products','tax_query' => array(
        array(
            'taxonomy' => 'products_category',
            'field' => 'id',
            'terms' =>  $cat->term_id,
            'operator' => 'AND' )
		));

	}

	return new WP_Query($quary);
}
?>