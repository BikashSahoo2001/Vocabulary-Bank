<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://magnigeeks.com
 * @since      1.0.0
 *
 * @package    Vocabulary_Bank
 * @subpackage Vocabulary_Bank/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Vocabulary_Bank
 * @subpackage Vocabulary_Bank/public
 * @author     Bikash sahoo <bikashsahoobiki1999@gmail.com>
 */
class Vocabulary_Bank_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_shortcode('vocabulary', array($this,'vocabulary_shortcode'));
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/vocabulary-bank-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/vocabulary-bank-public.js', array( 'jquery' ), $this->version, false );

	}

	public function vocabulary_shortcode() {

		ob_start();

		$letters = [];

		if ( ! is_user_logged_in() ) {

		    echo '<p class="error">Already have an account? <a href="' . site_url() . '/portal-login/">Click here to login</a></p>';

		    return;
			
		}
		if ( is_user_logged_in() ) {

			$current_user = wp_get_current_user();

			// if (in_array('student', $current_user->roles)) {
				$user_id = get_current_user_id();

				if ( $user_id ) {
					global $wpdb;
					$student_id = $user_id;
					$status = array('pass', 'fail');

					$query = $wpdb->prepare(
						"SELECT quiz_id
						FROM {$wpdb->prefix}lifterlms_quiz_attempts
						WHERE student_id = %d
						AND (status = %s OR status = %s)",
						$student_id,
						'fail',
						'pass'
					);

					$quiz_ids = $wpdb->get_col( $query );

					$quiz_ids = array_unique( $quiz_ids );

                    wp_reset_postdata();

					if ( !empty ( $quiz_ids ) ) {

						foreach ( $quiz_ids as $quiz_id ) {

							$meta_value = get_post_meta( $quiz_id, 'vocab', true );

							if ( is_array( $meta_value ) ) {

								foreach ( $meta_value as $vocab_post_id ) {

									$vocabs = [];

									$custom_posts = new WP_Query( array(
										'p' => $vocab_post_id,
										'post_type' => 'vocabulary',
										'posts_per_page' => -1, 
									));
									
									if ( $custom_posts->have_posts() ) {

										while ( $custom_posts->have_posts() ) {

											$custom_posts->the_post();

											$title = get_the_title();

											$firstLetter = $title[0];

											$firstLetterCapitalized = strtoupper ( $firstLetter );

											if ( !in_array( $firstLetterCapitalized, $letters) ) {

												array_push( $letters, $firstLetterCapitalized );

											}
										}
									}
								}
							}
						}
					}
				}
			// }
			echo "<div id='vocabulary-letters'>";

    		echo '<a id="vocabulary_0" class="active" > All </a>';

    		foreach ( $letters as $letter ) {

    			echo '<a id="vocabulary_' . $letter . '">' . $letter . '</a> ';

    		}
    		echo "</div>";
    
    		echo "<div id='vocabulary-contents'>";
		}

		?>
			<input type="text" id="search-bar" placeholder="Search..."> 
		<?php

		
		if ( is_user_logged_in() ) {

			$current_user = wp_get_current_user();

			// if (in_array('student', $current_user->roles)) {
				$user_id = get_current_user_id();

				if ( $user_id ) {

					global $wpdb;
					$student_id = $user_id;
					$status = array( 'pass', 'fail' );

					$query = $wpdb->prepare(
						"SELECT quiz_id
						FROM {$wpdb->prefix}lifterlms_quiz_attempts
						WHERE student_id = %d
						AND (status = %s OR status = %s)",
						$student_id,
						'fail',
						'pass'
					);

					$quiz_ids = $wpdb->get_col( $query );

					$quiz_ids = array_unique( $quiz_ids );

                    wp_reset_postdata();

					if ( !empty( $quiz_ids ) ) {

						foreach ( $quiz_ids as $quiz_id ) {

							$meta_value = get_post_meta( $quiz_id, 'vocab', true );

							if ( is_array( $meta_value ) ) {

								$meta_value = array_unique ( $meta_value );

								foreach( $meta_value as $vocab_post_id ) {

									$post = get_post( $vocab_post_id );

									if ( $post && $post->post_type == 'vocabulary' ) {

										$post_title = $post->post_title;

										$post_content = get_post_field( 'post_content', $vocab_post_id );

										echo "<div id='".$post_title."'>";

										if ( isset( $post_title ) ) {

											echo "<h3 class='vocabulary-title'>".$post_title."</h3><br>";
										}

										if ( isset( $post_content ) ) {

											echo "<h6>".$post_content."</h6><br>";
										}
										echo "</div>";
									}
								}
							}
						}
					}
					else {

					    echo '<p class="error">You have not complited any quiz.</p>';
					}
				}
			// }
		}
		echo "</div>";
		$return = ob_get_clean();
		
		return $return;
	}
	
}
