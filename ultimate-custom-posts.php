<?php
/* Plugin Name:Ultimate Custom Posts
Plugin URI: https://trustycoders.com/
Description: You can show custom post type posts with this plugin
Version: 3.0
Author: Jatinder Singh
Author URI: jatinder199217@gmail.com
License:
*/ 
 
/**
 * List of JavaScript / CSS files for admin
 */
 
add_action('admin_init', 'ucp_scripts');
add_action('admin_menu', 'ucp_menu');

	
/**
 * List of JavaScript / CSS files for admin
 */

if (!function_exists('ucp_scripts')) {
    function ucp_scripts() {
        if (is_admin()) {
			wp_register_style('admin.display.css', plugin_dir_url( __FILE__ ) . '/css/admin.display.css');
			wp_enqueue_style('admin.display.css');
		 
			wp_enqueue_script('jquery');
			
			wp_register_script('admin.script.js', plugin_dir_url( __FILE__ ) . '/js/admin.script.js');
			wp_enqueue_script('admin.script.js');
        }
    }
}


//Adding stylesheet for front-end display
add_action('wp_enqueue_scripts', 'ucp_show');

/**
 * Activation Hook for Ultimte Custom Posts
 */

function ucp_activation() {
}
register_activation_hook(__FILE__, 'ucp_activation');


/**
 * Deactivation Hook for Ultimate Custom Posts
 */
 
function ucp_deactivation() {
}
register_deactivation_hook(__FILE__, 'ucp_deactivation');

/**
 * Uninstall Hook for Ultimate Custom Posts
 */
function ucp_uninstall() {
}
register_uninstall_hook(__FILE__, 'ucp_uninstall');


/**
 * Plugin Menu for Ultimate Custom Posts
 */
 
function ucp_menu() {
	add_menu_page('Ultimate Custom Posts', 'Ultimate Custom Posts', 'administrator', 'Ultimate Custom Posts', 'ucp_core');
}
function pgafu_register_design_page() {
 	add_sub_menu_page( __('Post Grid And Filter', 'post-grid-and-filter-ultimate'), __('Post Grid And Filter', 'post-grid-and-filter-ultimate'), 'manage_options', 'pgafu-about',  'pgafu_designs_page', 'dashicons-sticky', 6 );
}

/**
 * Function for Admin Menu Page Option 
 */
 
function ucp_core(){
	$cposts = get_post_types();
	$builtin = array(
		'post',
		'page',
		'attachment'
	);
	$cposts = get_post_types( array(
		'public'   => true,
		'_builtin' => false
	) );
	
	$html .= '';
	$html .= '<div class="dash-area">';
	$html .= '<h1>Choose Post Type From Drop Down List and Create Shortcode: </h1><br/>';
	$html .= '<select id="dropdown_selector">'; 
	foreach($cposts as $cpost){ 
	$html .= '<option value="'. strtolower($cpost) .'">' .$cpost . '</option>'; 
	}
	$html .='</select><br/>'; 
	$html .= '<input type="text" name="name" id="showoption" placeholder="Your Shortcode Appear Here" readonly="readonly" />';
	
	
	$html .= '<div class="infobox">';
	$html .= '<span class="highlighted">Here is the list of other atributes that you can use with the shortcode:</span><br/>
	<strong>style</strong> => You can choose grid style by <b>1</b>,<b>2</b>,<b>3</b> .Just use "style"<br/>
	<strong>post_limit</strong> => Number of posts display on per page <br/>
	<strong>except_length</strong> => Set Except Length by using "except_length"<br/>
	<strong>order</strong> => In what order posts will be displayed, Possible Values: ASC, DESC <br/>';
		$html .= '</div>';
		//	$html .= '<h1 class="post-ajex-filter">Post Filter Shortcode</h1>';
		//$html .= '<div class="infobox ucp-filter">';
	//$html .= '<p>You can use <b>[upf_post_filter]</b> for ultimate post ajex filter .Ajax Filter shortcode that helps you filter your post by category terms with Ajax. Ajax post grid will help you Load posts with grid layout and you can also filter by post category</p><br/>';
	//$html .= '</div>';
	//$html .= '</div><div class="offers"><a href="#" target="_blank"><img src="' . plugins_url( 'images/ultimate-posts-plugin.png', __FILE__ ) . '"></a></div></div>';
	echo $html;
}

/**
 * Function for the excerpt length 
 */
 function excerpt($limit) {
      $excerpt = explode(' ', get_the_excerpt(), $limit);

      if (count($excerpt) >= $limit) {
          array_pop($excerpt);
          $excerpt = implode(" ", $excerpt) . '...';
      } else {
          $excerpt = implode(" ", $excerpt);
      }

      $excerpt = preg_replace('`\[[^\]]*\]`', '', $excerpt);

      return $excerpt;
}

function content($limit) {
    $content = explode(' ', get_the_content(), $limit);

    if (count($content) >= $limit) {
        array_pop($content);
        $content = implode(" ", $content) . '...';
    } else {
        $content = implode(" ", $content);
    }

    $content = preg_replace('/\[.+\]/','', $content);
    $content = apply_filters('the_content', $content); 
    $content = str_replace(']]>', ']]&gt;', $content);

    return $content;
}

/**
 * Function for the Shortcode in order to Ultimate Custom Posts  
 */
function ucp_show( $atts ) {

$p=	extract( shortcode_atts(
		array(
			'post_limit' => '-1',
			'post_type' => '',
			'orderby' => '',
			'order' => '',
			'type' => '',
			'paged' => $paged,
			'style' => '',
			'category' =>'',
			'except_length' =>'',
		), $atts ) 
	);
	//	var_dump($atts);
$limit=$atts ['except_length'];
$style=$atts['style'];

if ($style=='1'){
wp_register_style('ultimate-custom-post1.css', plugin_dir_url( __FILE__ ) . 'css/ultimate-custom-post1.css');
	wp_enqueue_style('ultimate-custom-post1.css');
	
} elseif ($style=='2'){
wp_register_style('ultimate-custom-post2.css', plugin_dir_url( __FILE__ ) . 'css/ultimate-custom-post2.css');
	wp_enqueue_style('ultimate-custom-post2.css');

} elseif ($style=='3'){
wp_register_style('ultimate-custom-post3.css', plugin_dir_url( __FILE__ ) . 'css/ultimate-custom-post3.css');
	wp_enqueue_style('ultimate-custom-post3.css');
	
}else {
  wp_register_style('ultimate-custom-post.css', plugin_dir_url( __FILE__ ) . 'css/ultimate-custom-post.css');
	wp_enqueue_style('ultimate-custom-post.css');  
}
	 global $post;

      $html = "";
	  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; 
      $my_query = new WP_Query( 
						array(
								'post_type' => $type,
								'posts_per_page' => $post_limit, 
								'orderby' => $orderby, 
								'order' => $order, 
								'paged' => $paged
							  ));

      if( $my_query->have_posts() ) : while( $my_query->have_posts() ) : $my_query->the_post();
   
        $html .= "<div id='ultimate-post-main'>";
		$html .= "<div class='ultimate-post-block'><div class='innerwrapper'>";
		$html .= "<div class='ultimate-post-thumb'>" . "<a href=". esc_url( get_permalink() ) . ">" . get_the_post_thumbnail() . "</a>" . "</div>";
		$html .= "<h2><a href=". esc_url( get_permalink() ) . ">" . get_the_title() . "</a>";
		$html .= "</h2>";
		$html .= "<div class='ultimate-post-content'>";
		$html .= "<p>" . excerpt($limit) . "</p><a href=". esc_url( get_permalink() ) . " class='read-more button'>Read More</a>";
		$html .= "</div></div></div></div>";
     
		endwhile; endif;
			$big = 9999999999;
			$html .="<div class='ultimate-post-nav-links'>" . paginate_links( 
				array(
					'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
					'format' => '?paged=%#%',
					'current' => max( 1, get_query_var('paged') ),
					'total' => $my_query->max_num_pages 
				) ) . "</div>";
		return $html;
}
add_shortcode( 'ucp_show', 'ucp_show' ); 
?>