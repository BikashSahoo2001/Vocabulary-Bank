<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://magnigeeks.com
 * @since      1.0.0
 *
 * @package    Vocabulary_Bank
 * @subpackage Vocabulary_Bank/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Vocabulary_Bank
 * @subpackage Vocabulary_Bank/admin
 * @author     Bikash sahoo <bikashsahoobiki1999@gmail.com>
 */
class Vocabulary_Bank_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action( 'init', array( $this, 'create_vocabulary_post_type' ) );
// 		add_action('wp_ajax_add_question', array($this, 'add_question_callback') );
// 		add_action('wp_ajax_nopriv_add_question', array($this, 'add_question_callback') );

		add_filter( 'llms_builder_register_custom_fields', array( $this, 'vocabulary_bank_fields' ), 100 );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Vocabulary_Bank_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Vocabulary_Bank_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/vocabulary-bank-admin.css', array(), $this->version, 'all' );	
		
		



	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Vocabulary_Bank_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Vocabulary_Bank_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/vocabulary-bank-admin.js', array( 'jquery' ), $this->version, false );

		$localized_data = array(
			
			'ajax_url' => admin_url('admin-ajax.php'), // The URL to admin-ajax.php for AJAX requests.
 		);
	
 		wp_localize_script( $this->plugin_name, 'myLocalizedData', $localized_data );

	}


	// Register Custom Post Type
	public	function create_vocabulary_post_type() {

		$labels = array(
			'name'               => _x( 'Vocabulary Bank', 'Post Type General Name', 'vocabulary-bank' ),
			'singular_name'      => _x( 'Vocabulary Bank', 'Post Type Singular Name', 'vocabulary-bank' ),
			'menu_name'          => _x( 'Vocabulary Bank', 'Admin Menu text', 'vocabulary-bank' ),
			'name_admin_bar'     => _x( 'Vocabulary Bank', 'Add New on Toolbar', 'vocabulary-bank' ),
			'add_new'            => __( 'Add New', 'vocabulary-bank' ),
			'add_new_item'       => __( 'Add New Vocabulary Bank', 'vocabulary-bank' ),
			'new_item'           => __( 'New Vocabulary Bank', 'vocabulary-bank' ),
			'edit_item'          => __( 'Edit Vocabulary Bank', 'vocabulary-bank' ),
			'view_item'          => __( 'View Vocabulary Bank', 'vocabulary-bank' ),
			'all_items'          => __( 'All Vocabulary Bank', 'vocabulary-bank' ),
			'search_items'       => __( 'Search Vocabulary Bank', 'vocabulary-bank' ),
			'parent_item_colon'  => __( 'Parent Vocabulary Bank:', 'vocabulary-bank' ),
			'not_found'          => __( 'No Vocabulary Bank found.', 'vocabulary-bank' ),
			'not_found_in_trash' => __( 'No Vocabulary Bank found in Trash.', 'vocabulary-bank' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'menu_icon'          => 'dashicons-awards', // Customize the menu icon
			'query_var'          => true,
			'rewrite'            => false,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 5,
			'supports'           => array( 'title', 'editor' ), // Customize the supported features
		);

		register_post_type( 'vocabulary', $args );
	}

	// callback for get all questions
	public function add_question_callback() {

		$data_to_send =[];

		$questions = [];
		
		$args = array(
			'post_type' => 'vocabulary',
			'posts_per_page' => -1, // To retrieve all posts of the custom post type
		);
		
		$custom_posts = new WP_Query( $args );
		
		if ( $custom_posts->have_posts() ) {

			while ($custom_posts->have_posts() ) {

				$custom_posts->the_post();

				$title = get_the_title();

				array_push($questions,  $title );


			}
			wp_reset_postdata();

			$data_to_send = array(
				'message' => 'This is the success response from the server.',
				'status' => 'success',
				'question_data' => $questions,

			);
 			
 		} else {
			// Handle errors if no questions were found.
			$data_to_send = array(
				'message' => 'This is the error response from the server.',
				'status' => 'error',
			);
		}
		$data = json_encode( $data_to_send );

		wp_send_json_error( $data );
		wp_die();
		
	}

	public function vocabulary_bank_fields( $default_fields ) {
		$vocab_fields = array();

		$custom_posts = new WP_Query( array(
			'post_type' => 'vocabulary',
			'posts_per_page' => -1,
			'order' => 'ASC',
    		'orderby' => 'title',

		));

		$vocabulary = [];

		if ( $custom_posts->have_posts() ) {

			while ( $custom_posts->have_posts() ) {

				$custom_posts->the_post();

				$title = get_the_title();

				$vocabulary[ get_the_ID() ] = $title;  
			}
		}
		$vocab_fields[]   = array(
			'attribute' => 'vocab',
			'id'        => 'vocab',
			'label'     => esc_html__( 'Vocabulary Bank', 'vocabulary-bank' ),
			'type'      => 'select',
			'multiple'	=> true,
			'options'	=> $vocabulary
		);

		$fields['vocabulary_bank_settings'] = array(
			'title'      => __( 'Vocabulary Settings', 'vocabulary-bank' ),
			'toggleable' => true,
			'fields'     => apply_filters(
				'vocabulary_lifterlms_settings',
				array(
					$vocab_fields,
				)
			),
		);

		$default_fields['quiz']  = $fields;

		return $default_fields;
	}
	
}
