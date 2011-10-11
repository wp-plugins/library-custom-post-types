<?php
/*
Plugin Name: Library Custom Post Types - Databases
Plugin URI: http://thecorkboard.org/
Description: A content type for listing databases.
Author: Kyle Jones
Version: 1.2
Author URI: http://thecorkboard.org
*/

/*
Changelog
	v1.0
		-created content type framework for the CPT, taxonomies, metaboxes, columns, and CSS edits
		
------------------------------------------------------------------------------------------------------------------------------------------------

Index
	1 = Content Type
	2 = Taxonomies
 		3.1 =
	3 = Metaboxes
 		4.1 =
 	4 = Columns
 		5.1 =
 	5 = CSS
*/


/**********************************
1 = Content Type
**********************************/

add_action('init', 'databases_init');
	function databases_init(){
		$labels=array(
			'name' => _x('Databases', 'content type general name'),
			'singular_name' => _x('Database', 'content type singular name'),
			'add_new'  =>  __('Add a Database', 'content'),
			'add_new_item' => __('Add a Database'),
			'edit_item' => __('Edit Database'),
			'new_item' => __('New Database'),
			'all_items' => __('All Databases'),
			'view_item' => __('View Databases'),
			'search_items' => __('Search Databases'),
			'not_found' => __('No Databases Found'),
			'not_found_in_trash' => __('No Databases Found in Trash'),
			'parent_item_colon' => __('Parent Database:'),
			'menu_name' => 'Databases'
		);
		$options=array(
			'labels' => $labels,
			'description' => __('Create a List of Databases'),
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 101,								//null - below comments, 5 - below Posts, 10 - below Media, 20 - below Pages
			'menu_icon' =>  plugins_url().'/library-cpt/icons/databases_16.png', //defaults to null, the posts icon
			'capability_type' => 'post',
			//'capabilities' => array(values),					//can be used to lock down permissions for the particular content type; see codex
			//'map_meta_cap' => ,
			'hierarchical' => false,							//defaults to false; allows parent to be specified
			'supports' => array(								//defaults to title and editor
						'title',							
						//'editor',							
						'author',							
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
			'rewrite' => true,									//defaults to true; an array of options using a slug is available
			'query_var' => true,								//defaults to true
			'can_export' => true,								//defaults to true, can be exported
			'show_in_nav_menus' => true,						//defaults to value of public argument
			'_builtin' => false,								//always use false
			'_edit_link' => 'post.php?post=%d',					//this is the default, use it
		);
		register_post_type('databases',$options);
	}

//Flush the permalinks on activation
function library_cpt_databases_rewrite_flush() {
	databases_init();
	flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'library_cpt_databases_rewrite_flush');

/**********************************
2 = Taxonomies
**********************************/
/****************
2.1 = Database Type
****************/
add_action( 'init', 'library_cpt_database_type', 0 );
	function library_cpt_database_type(){	
		$labels = array(										//where "categories" is equal to the kind of taxonomy object, change "categories" accordingly
			'name' => _x( 'Database Types', 'taxonomy general name' ),
			'singular_name' => _x( 'Database Type', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Database Types' ),
			'popular_items' => __( 'Popular Database Types' ),
			'all_items' => __( 'See All' ),
			'parent_item' => __( 'Parent Database Type' ),
			'parent_item_colon' => __( 'Parent Database Type:' ),
			'edit_item' => __( 'Edit Database Type' ), 
			'update_item' => __( 'Update Database Type' ),
			'add_new_item' => __( 'Add New Database Type' ),
			'new_item_name' => __( 'New Database Type' ),
			'separate_items_with_commas' => __( 'Separate tags with commas' ),
			'add_or_remove_items' => __( 'Add or remove tags' ),
			'choose_from_most_used' => __( 'Choose from the most used tags' ),
			'menu_name' => __( 'Types' ),
		); 	
		register_taxonomy('database_type', array('databases'), 
			array(
			    'labels' => $labels,								
				'public' => true,									//defaults to false, but it needs to be shown in the UI
			    'show_in_nav_menus' =>true,							//defaults if not set, defaults to value of public argument
			    'show_ui' => true,									//defaults to value of public argument
			    'show_tagcloud' => true,							//defaults to value of show_ui argument
			    'hierarchical' => true,								//defaults to false; false = tags, true = categories
			    //'update_count_callback' =>,						//A function name that will be called to update the count of an associated $object_type
			    'rewrite' => array( 'slug' => 'database_type', 'with_front' => true, 'hierarchical' => true ),			//defaults to true; an array of options using a slug is available
			    'query_var' => true,								//defaults to false to prevent queries, or string to customize query var
			    //'capabilities' => array(values),					//can be used to lock down permissions for the particular taxonomy; see codex
			    '_builtin' => false,								//always use false
		  	)
		);	
	}
/****************
2.2 = Database Subject
****************/
add_action( 'init', 'library_cpt_database_subject', 0 );
	function library_cpt_database_subject(){	
		$labels = array(										//where "categories" is equal to the kind of taxonomy object, change "categories" accordingly
			'name' => _x( 'Database Subjects', 'taxonomy general name' ),
			'singular_name' => _x( 'Database Subject', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Database Subjects' ),
			'popular_items' => __( 'Popular Database Subjects' ),
			'all_items' => __( 'See All' ),
			'parent_item' => __( 'Parent Database Subject' ),
			'parent_item_colon' => __( 'Parent Database Subject:' ),
			'edit_item' => __( 'Edit Database Subject' ), 
			'update_item' => __( 'Update Database Subject' ),
			'add_new_item' => __( 'Add New Database Subject' ),
			'new_item_name' => __( 'New Database Subject' ),
			'separate_items_with_commas' => __( 'Separate tags with commas' ),
			'add_or_remove_items' => __( 'Add or remove tags' ),
			'choose_from_most_used' => __( 'Choose from the most used tags' ),
			'menu_name' => __( 'Subjects' ),
		); 	
		register_taxonomy('database_subject',array('databases'), 
			array(
			    'labels' => $labels,								
				'public' => true,									//defaults to false, but it needs to be shown in the UI
			    'show_in_nav_menus' =>true,							//defaults if not set, defaults to value of public argument
			    'show_ui' => true,									//defaults to value of public argument
			    'show_tagcloud' => true,							//defaults to value of show_ui argument
			    'hierarchical' => true,								//defaults to false; false = tags, true = categories
			    //'update_count_callback' =>,						//A function name that will be called to update the count of an associated $object_type
			    'rewrite' => array( 'slug' => 'database_subject', 'with_front' => true, 'hierarchical' => true ),			//defaults to true; an array of options using a slug is available
			    'query_var' => true,								//defaults to false to prevent queries, or string to customize query var
			    //'capabilities' => array(values),					//can be used to lock down permissions for the particular taxonomy; see codex
			    '_builtin' => false,								//always use false
		  	)
		);	
	}

/**********************************
3 = Metaboxes
**********************************/
add_action('admin_init', 'library_cpt_databases_mb_create');
add_action('save_post', 'library_cpt_databases_mb_save');
 
function library_cpt_databases_mb_create(){
	add_meta_box( 'library_cpt_databases_mb_url', 'URL', 'database_url', 'databases', 'normal', 'high' );
	add_meta_box( 'library_cpt_databases_mb_description', 'Description', 'database_description', 'databases', 'normal', 'high' );
	add_meta_box( 'library_cpt_databases_mb_time_span', 'Time Span', 'database_time_span', 'databases', 'normal', 'core' );
	add_meta_box( 'library_cpt_databases_mb_publisher', 'Publisher', 'database_publisher', 'databases', 'normal', 'core' );
	add_meta_box( 'library_cpt_databases_mb_quick_search_notes', 'Quick Search Note(s)', 'database_quick_search_notes', 'databases', 'normal', 'low' );
	add_meta_box( 'library_cpt_databases_mb_full_text_notes', 'Full Text Note(s)', 'database_full_text_notes', 'databases', 'normal', 'low' );
}

function library_cpt_databases_mb_save(){
  global $post;
	update_post_meta($post->ID, 'database_url', $_POST['database_url']);
	update_post_meta($post->ID, 'database_description', $_POST['database_description']);
	update_post_meta($post->ID, 'database_time_span', $_POST['database_time_span']);
	update_post_meta($post->ID, 'database_publisher', $_POST['database_publisher']);
	update_post_meta($post->ID, 'database_quick_search_notes', $_POST['database_quick_search_notes']);
	update_post_meta($post->ID, 'database_full_text_notes', $_POST['database_full_text_notes']);
}

/****************
3.1 = URL
****************/
function database_url(){
	global $post;
	$custom = get_post_custom($post->ID);
	$database_url = $custom["database_url"][0];
	?>
	<input type="text" name="database_url" id="database_url" class="text" value="<?php echo $database_url; ?>" />
	<p>Provide the stable link (the permalink) to the database.</p>
<?php
}

/****************
3.2 = Description
****************/
function database_description(){
	global $post;
	$custom = get_post_custom($post->ID);
	$database_description = $custom["database_description"][0];
	?>
	<textarea name="database_description" id="database_description" class="textarea" ><?php echo $database_description; ?></textarea>
	<p>Describe the database and its topical coverage.</p>
<?php
}

/****************
3.3 = Time Span
****************/
function database_time_span(){
	global $post;
	$custom = get_post_custom($post->ID);
	$database_time_span = $custom["database_time_span"][0];
	?>
	<input type="text" name="database_time_span" id="database_time_span" class="text" value="<?php echo $database_time_span; ?>" />
	<p>List from what year to what year does this database cover.  For example: <br /><i>1871-2011</i></p>
<?php
}

/****************
3.4 = Publisher
****************/
function database_publisher(){
	global $post;
	$custom = get_post_custom($post->ID);
	$database_publisher = $custom["database_publisher"][0];
	?>
	<input type="text" name="database_publisher" id="database_publisher" class="text" value="<?php echo $database_publisher; ?>" />
	<p>List the name of the publisher of this database.</p>
<?php
}

/****************
3.5 = Quick Search Note(s)
****************/
function database_quick_search_notes(){
	global $post;
	$custom = get_post_custom($post->ID);
	$database_quick_search_notes = $custom["database_quick_search_notes"][0];
	?>
	<textarea name="database_quick_search_notes" id="database_quick_search_notes" class="textarea" ><?php echo $database_quick_search_notes; ?></textarea>
	<p>Provide any sort of notes that may help the user quickly search the database efficiently.<br />
		Notes should also be provided on searching restrictions (i.e., what it can't do) that need to be understood.</p>
<?php
}


/****************
3.6 = Full Text Note(s)
****************/
function database_full_text_notes(){
	global $post;
	$custom = get_post_custom($post->ID);
	$database_full_text_notes = $custom["database_full_text_notes"][0];
	?>
	<textarea name="database_full_text_notes" id="database_full_text_notes" class="textarea" ><?php echo $database_full_text_notes; ?></textarea>
	<p>If there are any limitations to full text access of the artifacts within the database, explain them here.</p>
<?php
}


/**********************************
4 = Columns
**********************************/
/****************
4.1 = 
****************/
add_filter("manage_edit-databases_columns", "set_databases_columns");
add_action("manage_posts_custom_column",  "databases_custom_columns");

function set_databases_columns($columns){
	$columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => "Database Title",
		"database_url" => "URL",
		"database_publisher" => "Publisher",
		"date" => "Date",
	);

	return $columns;
}

function databases_custom_columns($column){
	global $post;
	switch ($column)
	{
		case "database_url": ?>
			<a href="<?php echo get_post_meta($post->ID, 'database_url', true); ?>" target="_blank"><?php echo get_post_meta($post->ID, 'database_url', true);
			break;
		case "database_publisher":
			echo get_post_meta($post->ID, 'database_publisher', true);
			break;
	}
}


/**********************************
5 = CSS
**********************************/
add_action("admin_head", "databases_css");
	function databases_css(){
	?>
		<style type="text/css">
			#icon-edit.icon32-posts-databases{  /*Add the large icon to the main page area*/
				background: transparent url(<?php echo plugins_url()?>/library-custom-post-types/icons/databases_32.png) no-repeat;
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
			.column-database_url, .column-database_publisher{
				width: 20%;
			}		
		</style>
	<?php
	}
	?>