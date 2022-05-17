<?php				 				   
class Pending_Review_List_Table extends WP_List_Table 
{
	private $order;
	private $orderby;
	private $posts_per_page = 10;
	/**
	 * Undocumented function
	 */
	public function __construct()
	{
		parent :: __construct( array(
			'singular' => 'table example',
			'plural'   => 'table examples',
			'ajax'     => true
		) );
		$this->set_order();
		$this->set_orderby();
		$this->prepare_items();
		$this->search_box('Search', 'search');
		$this->display();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	private function get_sql_results()
	{
		global $wpdb;
		$sw = "";
		if($_REQUEST['clientf'] != 'All' && $_REQUEST['clientf'] != ''){
			$sw .=' AND client_id = '.$_REQUEST['clientf'].'';
		}
		if($_REQUEST['categoryf'] != 'All' && $_REQUEST['categoryf'] != ''){
			$sw .=' AND service_id = '.$_REQUEST['categoryf'].'';
		} 
		if($_REQUEST['page'] == 'Manage-Review'){
			$review_status = 1;
		}else{
			$review_status = 0;
		}
		$table_name = $wpdb->prefix.'rvcomment';
		$table_client = $wpdb->prefix.'client';
		$table_services = $wpdb->prefix.'services';
		$args = array( $table_name.'.id',$table_name.'.date_time',$table_name.'.reviewer_name',$table_name.'.reviewer_email',$table_name.'.review_phone',$table_name.'.review_rating',$table_name.'.review_text',$table_name.'.client_id',$table_name.'.service_id',$table_client.'.clientName',$table_services.'.name');
		$search = '';
		if(isset($_REQUEST['s']) && $_REQUEST['s'] != ''){
			$s = trim($_REQUEST['s']);
			$search = ' AND (';
			foreach($args as $arg){
				$search .= "$arg LIKE '$s%' OR ";
			}
			$search = rtrim($search, ' OR ');
			$search .= ') ';
		}
		$sql_select = implode( ', ', $args );
		$sql_results = $wpdb->get_results("
				SELECT $sql_select
				FROM $table_name
				LEFT JOIN $table_client ON ($table_name.client_id=$table_client.id)
				LEFT JOIN $table_services ON ($table_name.service_id=$table_services.id)
				WHERE $table_name.act ='1' AND $table_name.review_status='$review_status' $search $sw ORDER BY $table_name.$this->orderby $this->order"
		);
		return $sql_results;
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function set_order()
	{
		$order = 'DESC';
		if ( isset( $_GET['order'] ) AND $_GET['order'] )
			$order = $_GET['order'];
		$this->order = esc_sql( $order );
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function set_orderby()
	{
		$orderby = 'id';
		if ( isset( $_GET['orderby'] ) AND $_GET['orderby'] )
			$orderby = $_GET['orderby'];
		$this->orderby = esc_sql( $orderby );
	}
	/**
	 * @see WP_List_Table::ajax_user_can()
	 */
	public function ajax_user_can() 
	{
		return current_user_can( 'edit_posts' );
	}

	/**
	 * @see WP_List_Table::no_items()
	 */
	public function no_items() 
	{
		_e( 'No posts found.' );
	}

	/**
	 * @see WP_List_Table::get_views()
	 */
	public function get_views()
	{
		return array();
	} 

	/**
	 * @see WP_List_Table::get_columns()
	 */
	public function get_columns()
	{
		$columns = array(
		'date_time' => __( 'Date' ),
		'reviewer_name' => __( 'Name' ),
		'reviewer_email' => __( 'Email' ),
		'review_phone' => __( 'Phone' ),
		'review_rating' => __( 'Rating' ),
		'review_text' => __( 'Comments' ),
		'clientName' => __( 'Client Name' ),
		'name' => __( 'Category' ),
		'status' => __( 'Status' )
		);
		return $columns;        
	}

	/**
	 * @see WP_List_Table::get_sortable_columns()
	 */
	public function get_sortable_columns()
	{
		$sortable = array(
			'date_time' => array( 'date_time', true ),
			'reviewer_name' => array( 'reviewer_name', true ),
			'reviewer_email' => array( 'reviewer_email', true ),
			'review_phone' => array( 'review_phone', true ),
			'review_rating' => array( 'review_rating', true ),
			'review_text' => array( 'review_text', true ),
			'clientName' => array( 'clientName', true ),
			'name' => array( 'name', true )
		);
		return $sortable;
	}

	/**
	 * Prepare data for display
	 * @see WP_List_Table::prepare_items()
	 */
	public function prepare_items()
	{
		$columns  = $this->get_columns();
		$hidden   = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array( 
			$columns,
			$hidden,
			$sortable 
		);
		// SQL results
		$posts = $this->get_sql_results();
		empty( $posts ) AND $posts = array();
		# >>>> Pagination
		$per_page     = $this->posts_per_page;
		$current_page = $this->get_pagenum();
		$total_items  = count( $posts );
		$this->set_pagination_args( array (
			'total_items' => $total_items,
			'per_page'    => $per_page,
			'total_pages' => ceil( $total_items / $per_page )
		) );
		$last_post = $current_page * $per_page;
		$first_post = $last_post - $per_page + 1;
		$last_post > $total_items AND $last_post = $total_items;
		// Setup the range of keys/indizes that contain 
		// the posts on the currently displayed page(d).
		// Flip keys with values as the range outputs the range in the values.
		$range = array_flip( range( $first_post - 1, $last_post - 1, 1 ) );
		// Filter out the posts we're not displaying on the current page.
		$posts_array = array_intersect_key( $posts, $range );
		# <<<< Pagination
		// Prepare the data
		$permalink = __( 'Edit:' );
		foreach ( $posts_array as $key => $post )
		{
			//$link     = get_edit_post_link( $post->id );
			//$no_title = __( 'No name set' );
			//$title    = ! $post->id ? "<em>{$no_title}</em>" : $post->id;
			//$posts[ $key ]->id = "<a title='{$permalink} {$title}' href='{$link}'>{$title}</a>";
			//$posts[ $key ]->client_id = rv_get_client($post->client_id);
			//$posts[ $key ]->service_id = rv_get_category($post->service_id);
			$posts[ $key ]->status = rv_get_status($post->id,0);
		}
		$this->items = $posts_array;
	}

	/**
	 * A single column
	 */
	public function column_default( $item, $column_name )
	{
		return $item->$column_name;
	}

	/**
	 * Override of table nav to avoid breaking with bulk actions & according nonce field
	 */
	public function display_tablenav( $which ) {
		?>
		<div class="tablenav <?php echo esc_attr( $which ); ?>">
			<!-- 
			<div class="alignleft actions">
				<?php # $this->bulk_actions( $which ); ?>
			</div>
			 -->
			<?php
			$this->extra_tablenav( $which );
			$this->pagination( $which );
			?>
			<br class="clear" />
		</div>
		<?php
	}
	
	/**
	 * Disables the views for 'side' context as there's not enough free space in the UI
	 * Only displays them on screen/browser refresh. Else we'd have to do this via an AJAX DB update.
	 * 
	 * @see WP_List_Table::extra_tablenav()
	 */
	public function extra_tablenav( $which )
	{
		global $wp_meta_boxes;
		$views = $this->get_views();
		if ( empty( $views ) )
			return;
		$this->views();
	}
} 
new Pending_Review_List_Table();
?>