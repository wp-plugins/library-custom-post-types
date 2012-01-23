<?php
/*
Plugin Name: Library Custom Post Types - Directory
Plugin URI: http://thecorkboard.org/
Description: A content type for a staff directory.
Author: Kyle Jones
Version: 1.2
Author URI: http://thecorkboard.org
*/

/*
		
------------------------------------------------------------------------------------------------------------------------------------------------

Index
	1 = Content Type
	2 = Taxonomies
 		2.1 = Journal Subject
	3 = Metaboxes
 		3.1 = Position Title
 		3.2 = E-Mail
 		3.3 = Phone Number
 		3.4 = URL
 		3.5 = Address
 		3.6 = Biography
 		3.7 = Photo
 	4 = Columns
 	5 = CSS
*/


/**********************************
1 = Content Type
**********************************/
add_action('init', 'staff_directory_init');
	function staff_directory_init(){
		$labels=array(
			'name' => _x('Staff Directory', 'content type general name'),
			'singular_name' => _x('Staff Member', 'content type singular name'),
			'add_new'  =>  __('Add a Staff Member', 'content'),
			'add_new_item' => __('Add a Staff Member'),
			'edit_item' => __('Edit Staff Member'),
			'new_item' => __('New Staff Member'),
			'all_items' => __('All Staff Members'),
			'view_item' => __('View Staff Members'),
			'search_items' => __('Search the Staff Directory'),
			'not_found' => __('No Staff Members Found'),
			'not_found_in_trash' => __('No Staff Members Found in Trash'),
			'parent_item_colon' => __('Parent Staff Member:'),
			'menu_name' => 'Staff Directory'
		);
		$options=array(
			'labels' => $labels,
			'description' => __('Create a Staff Directory'),
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 101,								//null - below comments, 5 - below Posts, 10 - below Media, 20 - below Pages
			'menu_icon' =>  plugins_url().'/library-custom-post-types/icons/staff_directory_16.png', //defaults to null, the posts icon
			'capability_type' => 'post',
			//'capabilities' => array(values),					//can be used to lock down permissions for the particular content type; see codex
			//'map_meta_cap' => ,
			'hierarchical' => false,							//defaults to false; allows parent to be specified
			'supports' => array(								//defaults to title and editor
						'title',							
						//'editor',							
						//'author',							
						//'thumbnail',						
						//'excerpt',							
						//'trackbacks',						
						//'custom-fields',					
						//'comments',							
						//'revisions',						
						//'page-attributes'
						),					
			'register_meta_box_cb' => '',						//provide a callback function that will be called when setting up the meta boxes for the edit form.
			//'taxonomies' => array(values),					//an array of registered taxonomies that will be used with this post type.						
			//'permalink_epmask' => '',							//defaults to EP_PERMALINK
			'has_archive' => true,								//enables post type archives. Will use string as archive slug
			'rewrite' => array(									//defaults to true; an array of options using a slug is available
						'slug' => 'staff_directory', 
						'with_front' => false 
						),
			'query_var' => true,								//defaults to true
			'can_export' => true,								//defaults to true, can be exported
			'show_in_nav_menus' => true,						//defaults to value of public argument
			'_builtin' => false,								//always use false
			'_edit_link' => 'post.php?post=%d',					//this is the default, use it
		);
		register_post_type('staff_directory',$options);
	}

//Flush the permalinks on activation
function library_cpt_staff_directory_rewrite_flush() {
	staff_directory_init();
	flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'library_cpt_staff_directory_rewrite_flush');

/**********************************
2 = Taxonomies
**********************************/
/****************
2.1 = Journal Subject
****************/
add_action( 'init', 'library_cpt_staff_directory_department', 0 );
	function library_cpt_staff_directory_department(){	
		$labels = array(										//where "categories" is equal to the kind of taxonomy object, change "categories" accordingly
			'name' => _x( 'Departments', 'taxonomy general name' ),
			'singular_name' => _x( 'Department', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Departments' ),
			'popular_items' => __( 'Popular Departments' ),
			'all_items' => __( 'See All' ),
			'parent_item' => __( 'Parent Department' ),
			'parent_item_colon' => __( 'Parent Department:' ),
			'edit_item' => __( 'Edit Department' ), 
			'update_item' => __( 'Update Department' ),
			'add_new_item' => __( 'Add New Department' ),
			'new_item_name' => __( 'New Department' ),
			'separate_items_with_commas' => __( 'Separate tags with commas' ),
			'add_or_remove_items' => __( 'Add or remove tags' ),
			'choose_from_most_used' => __( 'Choose from the most used tags' ),
			'menu_name' => __( 'Departments' ),
		); 	
		register_taxonomy('staff_directory_department',array('staff_directory'), 
			array(
			    'labels' => $labels,								
				'public' => true,									//defaults to false, but it needs to be shown in the UI
			    'show_in_nav_menus' =>true,							//defaults if not set, defaults to value of public argument
			    'show_ui' => true,									//defaults to value of public argument
			    'show_tagcloud' => true,							//defaults to value of show_ui argument
			    'hierarchical' => true,								//defaults to false; false = tags, true = categories
			    //'update_count_callback' =>,						//A function name that will be called to update the count of an associated $object_type
			    'rewrite' => array( 'slug' => 'department', 'with_front' => true, 'hierarchical' => true ),			//defaults to true; an array of options using a slug is available
			    'query_var' => true,								//defaults to false to prevent queries, or string to customize query var
			    //'capabilities' => array(values),					//can be used to lock down permissions for the particular taxonomy; see codex
			    '_builtin' => false,								//always use false
		  	)
		);	
	}

/**********************************
3 = Metaboxes
**********************************/
add_action("admin_init", "library_cpt_staff_directory_mb_create");
add_action('save_post', 'library_cpt_staff_directory_mb_save');

function library_cpt_staff_directory_mb_create(){
	add_meta_box( 'library_cpt_staff_directory_mb_position_title', 'Position Title', 'directory_position_title', 'staff_directory', 'advanced', 'high' );
	add_meta_box( 'library_cpt_staff_directory_mb_email', 'E-Mail', 'directory_email', 'staff_directory', 'advanced', 'high' );
	add_meta_box( 'library_cpt_staff_directory_mb_phone', 'Phone Number', 'directory_phone', 'staff_directory', 'advanced', 'high' );
	add_meta_box( 'library_cpt_staff_directory_mb_url', 'URL', 'directory_url', 'staff_directory', 'advanced', 'high' );
	add_meta_box( 'library_cpt_staff_directory_mb_address', 'Address', 'directory_address', 'staff_directory', 'advanced', 'high' );
	add_meta_box( 'library_cpt_staff_directory_mb_bio', 'Biography', 'directory_bio', 'staff_directory', 'advanced', 'high' );
	add_meta_box( 'library_cpt_staff_directory_mb_photo', 'Photo', 'directory_photo', 'staff_directory', 'advanced', 'high' );
}

function library_cpt_staff_directory_mb_save(){
	global $post;
	update_post_meta($post->ID, 'directory_position_title', $_POST['directory_position_title']);
	update_post_meta($post->ID, 'directory_email', $_POST['directory_email']);
	update_post_meta($post->ID, 'directory_phone', $_POST['directory_phone']);
	update_post_meta($post->ID, 'directory_url', $_POST['directory_url']);
	update_post_meta($post->ID, 'directory_address', $_POST['directory_address']);
	update_post_meta($post->ID, 'directory_bio', $_POST['directory_bio']);
	update_post_meta($post->ID, 'directory_photo', $_POST['directory_photo']);	
}

/****************
3.1 = Position Title
****************/
function directory_position_title(){
	global $post;
	$custom = get_post_custom($post->ID);
	$directory_position_title = $custom["directory_position_title"][0];
	?>
	<input type="text" name="directory_position_title" id="directory_position_title" class="text" tabindex="1" value="<?php echo $directory_position_title; ?>" />
	<p>Provide the staff member's position title (use the Departments taxonomy to associate a specific department).</p>
<?php
}

/****************
3.2 = E-Mail
****************/
function directory_email(){
	global $post;
	$custom = get_post_custom($post->ID);
	$directory_email = $custom["directory_email"][0];
	?>
	<input type="text" name="directory_email" id="directory_email" class="text" value="<?php echo $directory_email; ?>" />
	<p>Insert the staff member's e-mail address.</p>
<?php
}

/****************
3.3 = Phone Number
****************/
function directory_phone(){
	global $post;
	$custom = get_post_custom($post->ID);
	$directory_phone = $custom["directory_phone"][0];
	?>
	<input type="text" name="directory_phone" id="directory_phone" class="text" value="<?php echo $directory_phone; ?>" />
	<p>Input the staff member's full phone number including area code.</p>
<?php
}

/****************
3.4 = URL
****************/
function directory_url(){
	global $post;
	$custom = get_post_custom($post->ID);
	$directory_url = $custom["directory_url"][0];
	?>
	<input type="text" name="directory_url" id="directory_url" class="text" value="<?php echo $directory_url; ?>" />
	<p>If the staff member has a professional website, share it here.</p>
<?php
}

/****************
3.5 = Address
****************/
function directory_address(){
	global $post;
	$custom = get_post_custom($post->ID);
	$directory_address = $custom["directory_address"][0];
	?>
	<textarea name="directory_address" id="directory_address" class="textarea"><?php echo $directory_address; ?></textarea>
	<p>Including the library's address, include the staff member's office or room number.  For example:<br />
		<i>Room #34<br />
		1200 S. Library Street<br />
		Library, WI 53302</i>
	</p>
<?php
}

/****************
3.6 = Biography
****************/
function directory_bio(){
	global $post;
	$custom = get_post_custom($post->ID);
	$directory_bio = $custom["directory_bio"][0];
	?>
	<textarea name="directory_bio" id="directory_bio" class="textarea"><?php echo $directory_bio; ?></textarea>
	<p>Add a brief bio about the staff member.</p>
<?php
}

/****************
3.7 = Photo
****************/
function directory_photo(){
	global $post;
	$custom = get_post_custom($post->ID);
	$directory_photo = $custom["directory_photo"][0];
	?>
	<input type="text" name="directory_photo" id="directory_photo" class="text" value="<?php echo $directory_photo; ?>" />
	<p>Using the media library, upload the staff member's photo and then copy and paste the url to the photo here.</p>
<?php
}

/**********************************
4 = Columns
**********************************/
add_filter("manage_edit-staff_directory_columns", "set_staff_directory_columns");
add_action("manage_posts_custom_column",  "staff_directory_custom_columns");

function set_staff_directory_columns($columns){
	$columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => "Staff Member",
		"directory_photo" => "Photo",
		"directory_email" => "E-mail",
		"directory_phone" => "Phone Number",
	);

	return $columns;
}

function staff_directory_custom_columns($column){
	global $post;
	switch ($column)
	{
		case "directory_photo":?>
			<img src="<?php echo get_post_meta($post->ID, 'directory_photo', true)?>" height="50" width="50" /><?php ;
			break;
		case "directory_email":?>
			<a href="mailto:<?php echo get_post_meta($post->ID, 'directory_email', true); ?>"><?php echo get_post_meta($post->ID, 'directory_email', true);
			break;
		case "directory_phone":
			echo get_post_meta($post->ID, 'directory_phone', true);
			break;
	}
}
/**********************************
5 = CSS
**********************************/
add_action("admin_head", "staff_directory_css");
	function staff_directory_css(){
	?>
		<style type="text/css">
			#icon-edit.icon32-posts-staff_directory{
				background: transparent url(<?php echo plugins_url()?>/library-custom-post-types/icons/staff_directory_32.png) no-repeat;
			}
			/*Metabox CSS*/
			.text{
				margin: 0;
				height: 2em; /*allows 1 visible line*/
				width: 99%;
			}
			.textarea{
				margin: 0;
				height: 6.2em; /*allows 4 visible lines*/
				width: 99%;
			}	
			/*Columns CSS*/
			.column-directory_photo{
				width: 20%;
			}		
			.column-directory_email, .column-directory_phone{
				width: 15%;
			}
		</style>
	<?php
	}
	?>