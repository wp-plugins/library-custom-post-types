<?php
/*
		
------------------------------------------------------------------------------------------------------------------------------------------------

Index
	1 = Content Type
	2 = Taxonomies
 		2.1 = Journal Subject
	3 = Metaboxes
 		3.1 = URL
 		3.2 = Description
 		3.3 = Time Span
 		3.4 = Publisher
 		3.5 = ISSN
 		3.6 = Full Text Note(s)
 	4 = Columns
 	5 = CSS
*/


/**********************************
1 = Content Type
**********************************/
add_action('init', 'course_guide_init');
	function course_guide_init(){
		$labels=array(
			'name' => _x('Course Guide', 'content type general name'),
			'singular_name' => _x('Course Guide', 'content type singular name'),
			'add_new'  =>  __('Add a Course Guide', 'content'),
			'add_new_item' => __('Add a Course Guide'),
			'edit_item' => __('Edit Course Guide'),
			'new_item' => __('New Course Guide'),
			'all_items' => __('All Course Guides'),
			'view_item' => __('View Course Guides'),
			'search_items' => __('Search Course Guides'),
			'not_found' => __('No Course Guides Found'),
			'not_found_in_trash' => __('No Course Guides Found in Trash'),
			'parent_item_colon' => __('Parent Course Guide:'),
			'menu_name' => 'Course Guides'
		);
		$options=array(
			'labels' => $labels,
			'description' => __('Create a List of Course Guides'),
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 101,								//null - below comments, 5 - below Posts, 10 - below Media, 20 - below Pages
			'menu_icon' =>  plugins_url().'/library-custom-post-types/icons/course_guides_16.png', //defaults to null, the posts icon
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
						'custom-fields',					
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
		register_post_type('course_guides',$options);
	}

//Flush the permalinks on activation
function library_cpt_course_guides_rewrite_flush() {
	course_guide_init();
	flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'library_cpt_course_guides_rewrite_flush');

/**********************************
2 = Taxonomies
**********************************/
/****************
2.1 = Course Guide Subject
****************/
add_action( 'init', 'library_cpt_course_guides_subject', 0 );
	function library_cpt_course_guides_subject(){	
		$labels = array(										//where "categories" is equal to the kind of taxonomy object, change "categories" accordingly
			'name' => _x( 'Course Guide Subjects', 'taxonomy general name' ),
			'singular_name' => _x( 'Course Guide Subject', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Course Guide Subjects' ),
			'popular_items' => __( 'Popular Course Guide Subjects' ),
			'all_items' => __( 'See All' ),
			'parent_item' => __( 'Parent Course Guide Subject' ),
			'parent_item_colon' => __( 'Parent Course Guide Subject:' ),
			'edit_item' => __( 'Edit Course Guide Subject' ), 
			'update_item' => __( 'Update Course Guide Subject' ),
			'add_new_item' => __( 'Add New Course Guide Subject' ),
			'new_item_name' => __( 'New Course Guide Subject' ),
			'separate_items_with_commas' => __( 'Separate tags with commas' ),
			'add_or_remove_items' => __( 'Add or remove tags' ),
			'choose_from_most_used' => __( 'Choose from the most used tags' ),
			'menu_name' => __( 'Subjects' ),
		); 	
		register_taxonomy('course_guides_subject',array('course_guides'), 
			array(
			    'labels' => $labels,								
				'public' => true,									//defaults to false, but it needs to be shown in the UI
			    'show_in_nav_menus' =>true,							//defaults if not set, defaults to value of public argument
			    'show_ui' => true,									//defaults to value of public argument
			    'show_tagcloud' => true,							//defaults to value of show_ui argument
			    'hierarchical' => true,								//defaults to false; false = tags, true = categories
			    //'update_count_callback' =>,						//A function name that will be called to update the count of an associated $object_type
			    'rewrite' => array( 'slug' => 'course_guide_subject', 'with_front' => true, 'hierarchical' => true ),			//defaults to true; an array of options using a slug is available
			    'query_var' => true,								//defaults to false to prevent queries, or string to customize query var
			    //'capabilities' => array(values),					//can be used to lock down permissions for the particular taxonomy; see codex
			    '_builtin' => false,								//always use false
		  	)
		);	
	}

/**********************************
3 = Metaboxes
**********************************/
add_action("admin_init", "library_cpt_course_guides_mb_create");
add_action('save_post', 'library_cpt_course_guides_mb_save');

function library_cpt_course_guides_mb_create(){
	add_meta_box( 'library_cpt_course_guides_mb_about_the_guide', 'About the Guide', 'course_guides_about_the_guide', 'course_guides', 'advanced', 'high' );
	add_meta_box( 'library_cpt_course_guides_mb_primary_databases', 'Primary Databases', 'course_guides_primary_databases', 'course_guides', 'advanced', 'high' );
	add_meta_box( 'library_cpt_course_guides_mb_alternative_databases', 'Alternative Databases', 'course_guides_alternative_databases', 'course_guides', 'advanced', 'high' );
	add_meta_box( 'library_cpt_course_guides_mb_primary_journals', 'Primary Journals', 'course_guides_primary_journals', 'course_guides', 'advanced', 'high' );
	add_meta_box( 'library_cpt_course_guides_mb_alternative_journals', 'Alternative Journals', 'course_guides_alternative_journals', 'course_guides', 'advanced', 'high' );
	add_meta_box( 'library_cpt_course_guides_mb_text_sources', 'Text Sources', 'course_guides_text_sources', 'course_guides', 'advanced', 'high' );
	add_meta_box( 'library_cpt_course_guides_mb_web_sources', 'Web Sources', 'course_guides_web_sources', 'course_guides', 'advanced', 'high' );
	add_meta_box( 'library_cpt_course_guides_mb_author', 'Author', 'course_guides_author', 'course_guides', 'side', 'core' );
}

function library_cpt_course_guides_mb_save(){
	global $post;
	update_post_meta($post->ID, 'course_guides_about_the_guide', $_POST['course_guides_about_the_guide']);	
	update_post_meta($post->ID, 'course_guides_primary_databases_description', $_POST['course_guides_primary_databases_description']);
	update_post_meta($post->ID, 'course_guides_primary_databases_select', $_POST['course_guides_primary_databases_select']);
	update_post_meta($post->ID, 'course_guides_alternative_databases', $_POST['course_guides_alternative_databases']);
	update_post_meta($post->ID, 'course_guides_primary_journals', $_POST['course_guides_primary_journals']);
	update_post_meta($post->ID, 'course_guides_alternative_journals', $_POST['course_guides_alternative_journals']);
	update_post_meta($post->ID, 'course_guides_text_sources', $_POST['course_guides_text_sources']);
	update_post_meta($post->ID, 'course_guides_web_sources', $_POST['course_guides_web_sources']);
	update_post_meta($post->ID, 'course_guides_web_author', $_POST['course_guides_author']);
}

/****************
3.1 = About the Guide
****************/
function course_guides_about_the_guide(){
	global $post;
	$custom = get_post_custom($post->ID);
	$course_guides_about_the_guide = $custom["course_guides_about_the_guide"][0];
	?>
	<textarea name="course_guides_about_the_guide" id="course_guides_about_the_guide" class="textarea" ><?php echo $course_guides_about_the_guide; ?></textarea>
	<p>Describe the course guide.  You can use this area to talk about the purpose, goals, objects, etc. of the guide an how it can help in the research process.</p>
<?php
}

/****************
3.2 = Primary Databases
****************/
function course_guides_primary_databases(){
	global $post;
	$custom1 = get_post_custom($post->ID);
	$course_guides_primary_databases_description = $custom1["course_guides_primary_databases_description"][0];
	$custom2 = get_post_custom($post->ID);
	$course_guides_primary_databases_select = $custom2["course_guides_primary_databases_select"][0];
	?>
	<textarea name="course_guides_primary_databases_description" id="course_guides_about_the_guide_description" class="textarea" ><?php echo $course_guides_primary_databases_description; ?></textarea>
	<p>Describe the databases you'll choose.</p>

	<div id="bla">
		<input type="checkbox" name="course_guides_primary_databases_select" value="<?php echo $course_guides_primary_databases_select; ?>" /> I have a bike<br />
	</div>
	<p>Select the primary databases for this course guide.</p>
<?php
}

/****************
3.3 = Alternative Databases
****************/
function course_guides_alternative_databases(){
	global $post;
	$custom = get_post_custom($post->ID);
	$course_guides_alternative_databases = $custom["course_guides_alternative_databases"][0];
	?>
<?php
}

/****************
3.4 = Primary Journals
****************/
function course_guides_primary_journals(){
	global $post;
	$custom = get_post_custom($post->ID);
	$course_guides_primary_journals = $custom["course_guides_primary_journals"][0];
	?>
<?php
}

/****************
3.5 = Alternative Journals
****************/
function course_guides_alternative_journals(){
	global $post;
	$custom = get_post_custom($post->ID);
	$course_guides_alternative_journals = $custom["course_guides_alternative_journals"][0];
	?>
<?php
}

/****************
3.6 = Text Sources
****************/
function course_guides_text_sources(){
	global $post;
	$custom = get_post_custom($post->ID);
	$course_guides_text_sources = $custom["course_guides_text_sources"][0];
	?>
<?php
}

/****************
3.7 = Web Sources
****************/
function course_guides_web_sources(){
	global $post;
	$custom = get_post_custom($post->ID);
	$course_guides_web_sources = $custom["course_guides_web_sources"][0];
	?>
<?php
}

/****************
3.8 = Author
****************/
function course_guides_author(){
	global $post;
	$custom = get_post_custom($post->ID);
	$course_guides_author = $custom["course_guides_author"][0];
	?>
<?php
}

/**********************************
4 = Columns
**********************************/
add_filter("manage_edit-course_guides_columns", "set_course_guides_columns");
add_action("manage_posts_custom_column",  "course_guides_custom_columns");

function set_course_guides_columns($columns){
	$columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => "Journal Title",
		"test" => "Test",
	);

	return $columns;
}

function course_guides_custom_columns($column){
	global $post;
	switch ($column)
	{
		case "test":?>
			<a href="<?php echo get_post_meta($post->ID, 'test', true); ?>"><?php echo get_post_meta($post->ID, 'test', true);
			break;
	}
}

/**********************************
5 = CSS
**********************************/
add_action("admin_head", "course_guides_css");
	function course_guides_css(){
	?>
		<style type="text/css">
			#icon-edit.icon32-posts-course_guides{
				background: transparent url(<?php echo plugins_url()?>/library-custom-post-types/icons/course_guides_32.png) no-repeat;
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
			div#bla{
				border: 1px solid red !important;
			}		
		</style>
	<?php
	}
	?>