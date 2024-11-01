<?php
/*
Plugin Name: XML-ify Wordpress Multiple Posts
Plugin URI: http://lauragentry.com/2011/04/15/wordpress-plugin-xml-ify-wordpress-multiple-posts/
Description: This plugin generates an XML file for multiple posts from your Wordpress blog. You can use the generated XML files to populate photo gallery software, iPhone applications, etc.
Author: Laura Gentry
Version: 1.0
Author URI: http://www.lauragentry.com/
*/
function exportMPpostxml() {
if (is_front_page()) {
//global $post;
//$thePostID = $post->ID;

//MAKE xmlfiles DIRECTORY IF IT DOESN'T EXIST
if (!is_dir(WP_CONTENT_DIR . "/xmlfiles/")) {
   mkdir(WP_CONTENT_DIR . "/xmlfiles/");
} 
//START BUFFER
ob_start();

// removes formatting from the excerpt field 
remove_filter('the_excerpt', 'wpautop');

// only pull the latest XX # of posts, set in options page and/or only pull posts from a specific category
$numberposts = get_option('numberposts');
$categoryname = get_option('categoryname'); 

echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
echo '<multipostxml>';

// start wordpress loop
query_posts('posts_per_page=' . $numberposts . '&category_name=' . $categoryname);
if ( have_posts() ): while ( have_posts() ): the_post();
  echo '<singlepost>';
	
    
	// display the title if checked in options page
        $checkbox1 = get_option('checkbox1');
		$tagname6 = get_option('tagname6');
         if($checkbox1 == 1) : 
		 echo "<" . $tagname6 . ">";
		 {
	the_title(); 
		echo "</" . $tagname6 . ">"; } else : {        
    } endif;
    
	// display the excerpt if checked in options page
		$checkbox2 = get_option('checkbox2');
		$tagname7 = get_option('tagname7');
         if($checkbox2 == 1) : 
		 echo "<" . $tagname7 . ">";
		 {
		the_excerpt(); 
		echo "</" . $tagname7 . ">"; } else : {
    } endif;     
       
		// display the excerpt if checked in options page
		$checkbox3 = get_option('checkbox3');
		$tagname8 = get_option('tagname8');
         if($checkbox3 == 1) : 
		 echo "<" . $tagname8 . ">";
		 {
		the_permalink();  
		echo "</" . $tagname8 . ">"; } else : { 
    } endif;
    	
		// display post attachment data if checked
		$checkbox4 = get_option('checkbox4');
    	if($checkbox4 == 1) : {
		global $post;
		$thePostID = $post->ID;
		$MPxmlargs = array(
       'post_type' => 'attachment',
       'numberposts' => -1,
       'post_status' => null,
       'post_parent' => $post->ID,
	   'post_mime_type' => 'image',
       'order' => 'ASC',
       'orderby' => 'menu_order'
		);
    	$postattachments = get_posts($MPxmlargs);
		if ($postattachments) {
     		foreach ($postattachments as $attachment) {
                $imgURL = wp_get_attachment_url( $attachment->ID, false );
                $thumb = wp_get_attachment_thumb_url( $attachment->ID, false );
                $id = $attachment->ID;
                $imageTitle = $attachment->post_title;
                $imageCaption = $attachment->post_excerpt;

		echo '<attachment imageURL="' . $imgURL . '" thumbURL="' . $thumb . '">';
		echo '<caption><![CDATA[' . $imageCaption . ']]></caption>';
		echo '</attachment>';
		}
		}
		} endif;
    
	
	// grabs the custom fields and tag names set on the options page, assigns them as variables, then prints them
	$elementname1 = get_option('tagname1');
	$elementname2 = get_option('tagname2');
	$elementname3 = get_option('tagname3');
	$elementname4 = get_option('tagname4');
	$elementname5 = get_option('tagname5');
	$customelement1 = get_option('customfield1');
	$customelement2 = get_option('customfield2');
	$customelement3 = get_option('customfield3');
	$customelement4 = get_option('customfield4');
	$customelement4 = get_option('customfield4');
	if (get_post_custom_values($customelement1)) :
	foreach (get_post_custom_values($customelement1) as $customfield1) {
		echo "<" . $elementname1 . ">" . $customfield1 . "</" . $elementname1 . ">"; }
	else :
		echo '';
	endif;
	if (get_post_custom_values($customelement2)) :
	foreach (get_post_custom_values($customelement2) as $customfield2) {
		echo "<" . $elementname2 .">" . $customfield2 . "</" . $elementname2 . ">"; }
	else :
		echo '';
	endif;
	if (get_post_custom_values($customelement3)) :
	foreach (get_post_custom_values($customelement3) as $customfield3) {
		echo "<" . $elementname3 .">" . $customfield3 . "</" . $elementname3 . ">"; }
	else :
		echo '';
	endif;
	if (get_post_custom_values($customelement4)) :
	foreach (get_post_custom_values($customelement4) as $customfield4) {
		echo "<" . $elementname4 .">" . $customfield4 . "</" . $elementname4 . ">"; }
	else :
		echo '';
	endif;
	if (get_post_custom_values($customelement5)) :
	foreach (get_post_custom_values($customelement5) as $customfield5) {
		echo "<" . $elementname5 .">" . $customfield5 . "</" . $elementname5 . ">"; }
	else :
		echo '';
	endif;
	
    echo '</singlepost>';
	endwhile; endif;
echo '</multipostxml>';

$page = ob_get_contents();
// EXPORT THE BUFFER AS A FILE WITH AN XML EXTENSION
$fp = fopen(WP_CONTENT_DIR . "/xmlfiles/multipost.xml","w");
fwrite($fp,$page);
// CLEAN BUFFER SO XML IT WON'T PRINT ON POST PAGE
ob_end_clean();
} else {
// IF NOT A POST PAGE, DO NOTHING
}
}
// Now we set that function up to execute when the admin_footer action is called
add_action('get_footer', 'exportMPpostxml');


// *************** OPTIONS PAGE SET UP ****************
// Add options to database
add_option("customfield1", '', '', 'yes'); 
add_option("customfield2", '', '', 'yes'); 
add_option("customfield3", '', '', 'yes'); 
add_option("customfield4", 'off', '', 'yes'); 
add_option("customfield5", 'off', '', 'yes'); 
add_option("tagname1", '', '', 'yes'); 
add_option("tagname2", '', '', 'yes'); 
add_option("tagname3", '', '', 'yes'); 
add_option("tagname4", '', '', 'yes'); 
add_option("tagname5", '', '', 'yes');
add_option("tagname6", '', '', 'yes');
add_option("tagname7", '', '', 'yes');
add_option("tagname8", '', '', 'yes');
add_option("numberposts", '', '', 'yes'); 
add_option("categoryname", '', '', 'yes'); 
add_option("checkbox1", '', '', 'yes');
add_option("checkbox2", '', '', 'yes');
add_option("checkbox3", '', '', 'yes'); 
add_option("checkbox4", '', '', 'yes'); 

// Add Single Post XML to the admin menu
function multi_post_xml_menu() {
  add_options_page('XML ify Wordpress Multiple Posts', 'XML ify Wordpress Multiple Posts', 'manage_options', __FILE__, 'get_multi_post_xml_options_page');
}

// The form used to build the options page
function get_multi_post_xml_options_page() {
  include("multi-post-xml-feed-options.php");
}

function get_multi_post_xml_options(){
 $options = array();
 $options['customfield1'] = get_option(customfield1);
 $options['customfield2'] = get_option(customfield2); 
 $options['customfield3'] = get_option(customfield3); 
 $options['customfield4'] = get_option(customfield4); 
 $options['customfield5'] = get_option(customfield5); 
 $options['tagname1'] = get_option(tagname1); 
 $options['tagname2'] = get_option(tagname2); 
 $options['tagname3'] = get_option(tagname3); 
 $options['tagname4'] = get_option(tagname4); 
 $options['tagname5'] = get_option(tagname5); 
 $options['tagname6'] = get_option(tagname6); 
 $options['tagname7'] = get_option(tagname7); 
 $options['tagname8'] = get_option(tagname8); 
 $options['numberposts'] = get_option(numberposts); 
 $options['categoryname'] = get_option(categoryname); 
 $options['checkbox1'] = get_option(checkbox1); 
 $options['checkbox2'] = get_option(checkbox2); 
 $options['checkbox3'] = get_option(checkbox3); 
 $options['checkbox4'] = get_option(checkbox4); 
 
return $options;
}

$options = get_multi_post_xml_options();
extract($options);

function register_multi_post_xml_settings() { // whitelist options
register_setting('multi_post_xml_group','customfield1');
register_setting('multi_post_xml_group','customfield2');
register_setting('multi_post_xml_group','customfield3');
register_setting('multi_post_xml_group','customfield4');
register_setting('multi_post_xml_group','customfield5');
register_setting('multi_post_xml_group','tagname1');
register_setting('multi_post_xml_group','tagname2');
register_setting('multi_post_xml_group','tagname3');
register_setting('multi_post_xml_group','tagname4');
register_setting('multi_post_xml_group','tagname5');
register_setting('multi_post_xml_group','tagname6');
register_setting('multi_post_xml_group','tagname7');
register_setting('multi_post_xml_group','tagname8');
register_setting('multi_post_xml_group','numberposts');
register_setting('multi_post_xml_group','categoryname');
register_setting('multi_post_xml_group','checkbox1');
register_setting('multi_post_xml_group','checkbox2');
register_setting('multi_post_xml_group','checkbox3');
register_setting('multi_post_xml_group','checkbox4');
}

//add the admin page
add_action('admin_menu', 'multi_post_xml_menu');
add_action( 'admin_init', 'register_multi_post_xml_settings' );
?>