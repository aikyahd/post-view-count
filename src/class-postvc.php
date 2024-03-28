<?php
/**
 * Post view count plugin main class
 *
 * @package    WordPress
 * @subpackage Post View Count
 * @since      1.0
 */

namespace Aikya\PostViewCount;

/**
 * Post view count main class
 */
class PostVC {

	/**
	 * Plugin initialization
	 */
	public function init() {
		add_action( 'init', array( $this, 'init_plugin' ) );
		register_activation_hook( POSTVC, array( $this, 'activate' ) );
		register_deactivation_hook( POSTVC, array( $this, 'deactivate' ) );
	}

	/**
	 * Plugin activation
	 */
	public function activate() {
		$this->init_plugin();
		flush_rewrite_rules();
	}

	/**
	 * Plugin deactivation
	 */
	public function deactivate() {
		flush_rewrite_rules();
	}

	/**
	 * Plugin activation process
	 */
	public function init_plugin() {
		// add post view column.
		add_filter( 'manage_post_posts_columns', array( $this, 'add_view_col' ) );

		// add views to column.
		add_action( 'manage_post_posts_custom_column', array( $this, 'view_col_data' ), 10, 2 );

		// add sortable column.
		add_filter( 'manage_edit-post_sortable_columns', array( $this, 'add_col_sorting' ) );

		// sort posts by the orderby value.
		add_action( 'pre_get_posts', array( $this, 'col_orderby_views' ) );

		// update post view count.
		add_action( 'wp_head', array( $this, 'update_count' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_script' ) );

		// add shortcode.
		add_shortcode( 'post_view_count', array( $this, 'shortcode' ) );
	}

	/**
	 * Handle frontend enqueuing
	 */
	public function frontend_script() {
		// enqueue style.
		wp_enqueue_style( 'postvc', plugin_dir_url( POSTVC ) . 'assets/frontend.css', array(), '1.0.0', 'all' );
	}

	/**
	 * Handle updating post views automatically
	 */
	public function update_count() {
		global $post;

		if ( ! isset( $post->ID ) ) {
			return;
		}

		if ( ! isset( $post->post_type ) || 'post' !== $post->post_type ) {
			return;
		}

		$view = get_post_meta( $post->ID, 'postvc_meta', true );
		if ( empty( $view ) ) {
			$view = 1;
		} else {
			$view = (int) $view + 1;
		}

		update_post_meta( $post->ID, 'postvc_meta', $view );
	}

	/**
	 * Add post view shortcode
	 *
	 * @param array $atts shortcode attributes.
	 */
	public function shortcode( $atts ) {
		ob_start();

		$this->display_count( $atts );

		$content = ob_get_contents();
		ob_get_clean();

		return do_shortcode( $content );
	}

	/**
	 * Display shortcode content
	 *
	 * @param array $atts shortcode attributes.
	 */
	public function display_count( $atts ) {

		$id = isset( $atts['id'] ) ? (int) $atts['id'] : '';

		if ( empty( $id ) ) {
			return '';
		}

		$view = get_post_meta( $id, 'postvc_meta', true );

		if ( empty( $view ) ) {
			return '';
		}

		// if views are too large, format it a bit.
		$view = number_format( (int) $view );

		?>
		<div class="postvc-wrapper">
			<h2><?php echo esc_html__( 'Page Views', 'post-view-count' ); ?></h2>
			<h4><?php echo esc_html__( 'Total Views', 'post-view-count' ); ?></h4>
			<h2><?php echo esc_attr( $view ); ?></h2>
		</div>
		<?php
	}

	/**
	 * Add custom column to admin posts list
	 *
	 * @param array $columns all posts list columns.
	 */
	public function add_view_col( $columns ) {

		$columns['post_views'] = esc_html__( 'Views', 'post-view-count' );
		return $columns;
	}

	/**
	 * Display column content or post views
	 *
	 * @param string $column  current column being displayed.
	 * @param int    $post_id post id of the row.
	 */
	public function view_col_data( $column, $post_id ) {
		if ( 'post_views' !== $column ) {
			return '';
		}

		$view = get_post_meta( $post_id, 'postvc_meta', true );

		echo esc_html( $view );
	}

	/**
	 * Add sortable column support for post views
	 *
	 * @param array $columns all columns of posts list.
	 */
	public function add_col_sorting( $columns ) {
		$columns['post_views'] = 'post_views';
		return $columns;
	}

	/**
	 * Update query to add sorting by posts views
	 *
	 * @param object $query WP_Query object.
	 */
	public function col_orderby_views( $query ) {
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( 'post_views' === $query->get( 'orderby' ) ) {
			$query->set( 'meta_key', 'postvc_meta' );
			$query->set( 'orderby', 'meta_value_num' );
		}
	}
}

$postvc = new PostVC();
$postvc->init();
