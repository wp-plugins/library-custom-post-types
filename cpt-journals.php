<?php
/*
Plugin Name: Library Custom Post Types - Journals
Plugin URI: http://thecorkboard.org/
Description: A content type for listing journals.
Author: Kyle Jones
Version: 1.3
Author URI: http://thecorkboard.org
*/

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
add_action('init', 'journal_init');
	function journal_init(){
		$labels=array(
			'name' => _x('Journals', 'content type general name'),
			'singular_name' => _x('Journal', 'content type singular name'),
			'add_new'  =>  __('Add a Journal', 'content'),
			'add_new_item' => __('Add a Journal'),
			'edit_item' => __('Edit Journal'),
			'new_item' => __('New Journal'),
			'all_items' => __('All Journals'),
			'view_item' => __('View Journals'),
			'search_items' => __('Search Journals'),
			'not_found' => __('No Journals Found'),
			'not_found_in_trash' => __('No Journals Found in Trash'),
			'parent_item_colon' => __('Parent Journal:'),
			'menu_name' => 'Journals'
		);
		$options=array(
			'labels' => $labels,
			'description' => __('Create a List of Journals'),
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 101,								//null - below comments, 5 - below Posts, 10 - below Media, 20 - below Pages
			'menu_icon' =>  plugins_url().'/library-custom-post-types/icons/journals_16.png', //defaults to null, the posts icon
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
						'custom-fields',					
						//'comments',							
						//'revisions',						
						//'page-attributes'
						),					
			'register_meta_box_cb' => '',						//provide a callback function that will be called when setting up the meta boxes for the edit form.
			//'taxonomies' => array(values),					//an array of registered taxonomies that will be used with this post type.						
			//'permalink_epmask' => '',							//defaults to EP_PERMALINK
			'rewrite' => array(									//defaults to true; an array of options using a slug is available
						'slug' => 'journals', 
						'with_front' => false 
						),
			'rewrite' => true,									//defaults to true; an array of options using a slug is available
			'query_var' => true,								//defaults to true
			'can_export' => true,								//defaults to true, can be exported
			'show_in_nav_menus' => true,						//defaults to value of public argument
			'_builtin' => false,								//always use false
			'_edit_link' => 'post.php?post=%d',					//this is the default, use it
		);
		register_post_type('journals',$options);
	}

//Flush the permalinks on activation
function library_cpt_journals_rewrite_flush() {
	journal_init();
	flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'library_cpt_journals_rewrite_flush');

/**********************************
2 = Taxonomies
**********************************/
/****************
2.1 = Journal Subject
****************/
add_action( 'init', 'library_cpt_journal_subject', 0 );
	function library_cpt_journal_subject(){	
		$labels = array(										//where "categories" is equal to the kind of taxonomy object, change "categories" accordingly
			'name' => _x( 'Journal Subjects', 'taxonomy general name' ),
			'singular_name' => _x( 'Journal Subject', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Journal Subjects' ),
			'popular_items' => __( 'Popular Journal Subjects' ),
			'all_items' => __( 'See All' ),
			'parent_item' => __( 'Parent Journal Subject' ),
			'parent_item_colon' => __( 'Parent Journal Subject:' ),
			'edit_item' => __( 'Edit Journal Subject' ), 
			'update_item' => __( 'Update Journal Subject' ),
			'add_new_item' => __( 'Add New Journal Subject' ),
			'new_item_name' => __( 'New Journal Subject' ),
			'separate_items_with_commas' => __( 'Separate tags with commas' ),
			'add_or_remove_items' => __( 'Add or remove tags' ),
			'choose_from_most_used' => __( 'Choose from the most used tags' ),
			'menu_name' => __( 'Subjects' ),
		); 	
		register_taxonomy('journal_subject',array('journals'), 
			array(
			    'labels' => $labels,								
				'public' => true,									//defaults to false, but it needs to be shown in the UI
			    'show_in_nav_menus' =>true,							//defaults if not set, defaults to value of public argument
			    'show_ui' => true,									//defaults to value of public argument
			    'show_tagcloud' => true,							//defaults to value of show_ui argument
			    'hierarchical' => true,								//defaults to false; false = tags, true = categories
			    //'update_count_callback' =>,						//A function name that will be called to update the count of an associated $object_type
			    'rewrite' => array( 'slug' => 'journal_subject', 'with_front' => true, 'hierarchical' => true ),			//defaults to true; an array of options using a slug is available
			    'query_var' => true,								//defaults to false to prevent queries, or string to customize query var
			    //'capabilities' => array(values),					//can be used to lock down permissions for the particular taxonomy; see codex
			    '_builtin' => false,								//always use false
		  	)
		);	
	}

/**********************************
3 = Metaboxes
**********************************/
add_action("admin_init", "library_cpt_journals_mb_create");
add_action('save_post', 'library_cpt_journals_mb_save');

function library_cpt_journals_mb_create(){
	add_meta_box( 'library_cpt_journals_mb_url', 'URL', 'journals_url', 'journals', 'advanced', 'high' );
	add_meta_box( 'library_cpt_journals_mb_description', 'Description', 'journals_description', 'journals', 'advanced', 'high' );
	add_meta_box( 'library_cpt_journals_mb_time_span', 'Time Span', 'journals_time_span', 'journals', 'advanced', 'high' );	
	add_meta_box( 'library_cpt_journals_mb_publisher', 'Publisher', 'journals_publisher', 'journals', 'advanced', 'high' );
	add_meta_box( 'library_cpt_journals_mb_issn', 'ISSN', 'journals_issn', 'journals', 'advanced', 'high' );
	add_meta_box( 'library_cpt_journals_mb_full_text_notes', 'Full Text Note(s)', 'journals_full_text_notes', 'journals', 'advanced', 'high' );
}

function library_cpt_journals_mb_save(){
	global $post;
	update_post_meta($post->ID, 'journals_url', $_POST['journals_url']);
	update_post_meta($post->ID, 'journals_description', $_POST['journals_description']);
	update_post_meta($post->ID, 'journals_time_span', $_POST['journals_time_span']);	
	update_post_meta($post->ID, 'journals_publisher', $_POST['journals_publisher']);
	update_post_meta($post->ID, 'journals_issn', $_POST['journals_issn']);
	update_post_meta($post->ID, 'journals_full_text_notes', $_POST['journals_full_text_notes']);	
}

/****************
3.1 = URL
****************/
function journals_url(){
	global $post;
	$custom = get_post_custom($post->ID);
	$journals_url = $custom["journals_url"][0];
	?>
	<input type="text" name="journals_url" id="journals_url" class="text" tabindex="1" value="<?php echo $journals_url; ?>" />
	<p>Provide the stable link (the permalink) to the journal.</p>
<?php
}

/****************
3.2 = Description
****************/
function journals_description(){
	global $post;
	$custom = get_post_custom($post->ID);
	$journals_description = $custom["journals_description"][0];
	?>
	<textarea name="journals_description" id="journals_description" class="textarea"><?php echo $journals_description; ?></textarea>
	<p>Describe the journal and its topical coverage.</p>
<?php
}

/****************
3.3 = Time Span
****************/
function journals_time_span(){
	global $post;
	$custom = get_post_custom($post->ID);
	$journals_time_span = $custom["journals_time_span"][0];
	?>
	<input type="text" name="journals_time_span" id="journals_time_span" class="text" value="<?php echo $journals_time_span; ?>" />
	<p>List from what year to what year does this journal cover.  For example: <br /><i>1871-2011</i></p>
<?php
}

/****************
3.4 = Publisher
****************/
function journals_publisher(){
	global $post;
	$custom = get_post_custom($post->ID);
	$journals_publisher = $custom["journals_publisher"][0];
	?>
	<input type="text" name="journals_publisher" id="journals_publisher" class="text" value="<?php echo $journals_publisher; ?>" />
	<p>List the name of the publisher of this journal.</p>
<?php
}

/****************
3.5 = ISSN
****************/
function journals_issn(){
	global $post;
	$custom = get_post_custom($post->ID);
	$journals_issn = $custom["journals_issn"][0];
	?>
	<input type="text" name="journals_issn" id="journals_issn" class="text" value="<?php echo $journals_issn; ?>" />
	<p>Insert the ISSN of the journal.</p>
<?php
}

/****************
3.6 = Full Text Note(s)
****************/
function journals_full_text_notes(){
	global $post;
	$custom = get_post_custom($post->ID);
	$journals_full_text_notes = $custom["journals_full_text_notes"][0];
	?>
	<textarea name="journals_full_text_notes" id="journals_full_text_notes" class="textarea"><?php echo $journals_full_text_notes; ?></textarea>
	<p>If there are any limitations to full text access of the articles within the journal, explain them here.</p>
<?php
}

/**********************************
4 = Columns
**********************************/
add_filter("manage_edit-journals_columns", "set_journals_columns");
add_action("manage_posts_custom_column",  "journals_custom_columns");

function set_journals_columns($columns){
	$columns = array(
		"cb" => "<input type=\"checkbox\" />",
		"title" => "Journal Title",
		"journal_url" => "URL",
		"journal_publisher" => "Publisher",
		"journal_subjects" => "Subjects",
	);

	return $columns;
}

function journals_custom_columns($column){
	global $post;
	switch ($column)
	{
		case "journal_url":?>
			<a href="<?php echo get_post_meta($post->ID, 'journals_url', true); ?>"><?php echo get_post_meta($post->ID, 'journals_url', true);
			break;
		case "journal_publisher":
			echo get_post_meta($post->ID, 'journals_publisher', true);
			break;
		case "journal_subjects":
			echo get_the_term_list($post->ID,'journal_subject','',', ','');
			break;
	}
}

/**********************************
5 = CSS
**********************************/
add_action("admin_head", "journals_css");
	function journals_css(){
	?>
		<style type="text/css">
			#icon-edit.icon32-posts-journals{
				background: transparent url(<?php echo plugins_url()?>/library-custom-post-types/icons/journals_32.png) no-repeat;
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
		</style>
	<?php
	}
	?>