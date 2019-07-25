<?php
if ( ! defined( 'WPINC' ) ) die ;

$_panels = array(
	'all' => array(
		'title'	=> __( 'Clean All', 'litespeed-cache' ),
		'desc'	=> '',
	),
	'revision' => array(
		'title'	=> __( 'Post Revisions', 'litespeed-cache' ),
		'desc'	=> __( 'Clean all post revisions', 'litespeed-cache' ),
	),
	'auto_draft' => array(
		'title'	=> __( 'Auto Drafts', 'litespeed-cache' ),
		'desc'	=> __( 'Clean all auto saved drafts', 'litespeed-cache' ),
	),
	'trash_post' => array(
		'title'	=> __( 'Trashed Posts', 'litespeed-cache' ),
		'desc'	=> __( 'Clean all trashed posts and pages', 'litespeed-cache' ),
	),
	'spam_comment' => array(
		'title'	=> __( 'Spam Comments', 'litespeed-cache' ),
		'desc'	=> __( 'Clean all spam comments', 'litespeed-cache' ),
	),
	'trash_comment' => array(
		'title'	=> __( 'Trashed Comments', 'litespeed-cache' ),
		'desc'	=> __( 'Clean all trashed comments', 'litespeed-cache' ),
	),
	'trackback-pingback' => array(
		'title'	=> __( 'Trackbacks/Pingbacks', 'litespeed-cache' ),
		'desc'	=> __( 'Clean all trackbacks and pingbacks', 'litespeed-cache' ),
	),
	'expired_transient' => array(
		'title'	=> __( 'Expired Transients', 'litespeed-cache' ),
		'desc'	=> __( 'Clean expired transient options', 'litespeed-cache' ),
	),
	'all_transients' => array(
		'title'	=> __( 'All Transients', 'litespeed-cache' ),
		'desc'	=> __( 'Clean all transient options', 'litespeed-cache' ),
	),
	'optimize_tables' => array(
		'title'	=> __( 'Optimize Tables', 'litespeed-cache' ),
		'desc'	=> __( 'Optimize all tables in your database', 'litespeed-cache' ),
	),
	'all_cssjs' => array(
		'title'	=> __( 'Clean CSS/JS Optimizer', 'litespeed-cache' ),
		'desc'	=> __( 'Purge all and clean all minified/combined CSS/JS data', 'litespeed-cache' ),
		'dismiss_count_icon' => true,
		'title_cls'	=> 'litespeed-warning',
	),
) ;

$total = 0 ;
foreach ( $_panels as $tag => $v ) {
	if ( $tag != 'all' ) {
		$_panels[ $tag ][ 'count' ] = LiteSpeed_Cache_DB_Optm::db_count( $tag ) ;
		if ( ! in_array( $tag, array( 'all_cssjs', 'optimize_tables' ) ) ) {
			$total += $_panels[ $tag ][ 'count' ] ;
		}
	}
	$_panels[ $tag ][ 'link' ] = LiteSpeed_Cache_Utility::build_url( LiteSpeed_Cache_Router::ACTION_DB, $tag ) ;
}

$_panels[ 'all' ][ 'count' ] = $total ;

$autoload_summary = LiteSpeed_Cache_DB_Optm::get_instance()->autoload_summary() ;

?>

<h3 class="litespeed-title"><?php echo __('Database Optimizer', 'litespeed-cache'); ?></h3>

<div class="litespeed-panel-wrapper">

<?php foreach ( $_panels as $tag => $v ): ?>

	<a href="<?php echo $v[ 'link' ] ; ?>" class="litespeed-panel">
		<section class="litespeed-panel-wrapper-icon">
			<span class="litespeed-panel-icon-<?php echo $tag ; ?>"></span>
		</section>
		<section class="litespeed-panel-content">
			<div class="litespeed-h3 <?php if ( ! empty( $v[ 'title_cls' ] ) ) echo $v[ 'title_cls' ] ; ?>">
				<?php echo $v[ 'title' ] ; ?>
				<span class="litespeed-panel-counter<?php if ( $v[ 'count' ] > 0 && empty( $v[ 'dismiss_count_icon' ] ) ) echo '-red' ; ?>">(<?php echo $v[ 'count' ] ; ?>)</span>
			</div>
			<span class="litespeed-panel-para"><?php echo $v[ 'desc' ] ; ?></span>
		</section>
		<?php if ( empty( $v[ 'dismiss_count_icon' ] ) ) : ?>
		<section class="litespeed-panel-wrapper-top-right">
			<span class="litespeed-panel-top-right-icon<?php echo $v[ 'count' ] > 0 ? '-cross' : '-tick' ; ?>"></span>
		</section>
		<?php endif; ?>
	</a>
<?php endforeach; ?>

</div>

<h3 class="litespeed-title"><?php echo __( 'Database Table Engine Converter', 'litespeed-cache' ) ; ?></h3>

<div class="litespeed-panel-wrapper">

	<table class="litespeed-table">
		<thead><tr >
			<th scope="col">#</th>
			<th scope="col"><?php echo __( 'Table', 'litespeed-cache' ) ; ?></th>
			<th scope="col"><?php echo __( 'Engine', 'litespeed-cache' ) ; ?></th>
			<th scope="col"><?php echo __( 'Tool', 'litespeed-cache' ) ; ?></th>
		</tr></thead>
		<tbody>
		<?php
			$list = LiteSpeed_Cache_DB_Optm::get_instance()->list_myisam() ;
			if ( $list ) :
				foreach ( $list as $k => $v ) :
		?>
				<tr>
					<td><?php echo $k + 1 ; ?></td>
					<td><?php echo $v->TABLE_NAME ; ?></td>
					<td><?php echo $v->ENGINE ; ?></td>
					<td>
						<a href="<?php echo LiteSpeed_Cache_Utility::build_url( LiteSpeed_Cache_Router::ACTION_DB, LiteSpeed_Cache_DB_Optm::TYPE_CONV_TB, false, false, array( 'tb' => $v->TABLE_NAME ) ) ; ?>">
							<?php echo __( 'Convert to InnoDB', 'litespeed-cache' ) ; ?>
						</a>
					</td>
				</tr>
		<?php endforeach ; ?>
		<?php else : ?>
			<tr>
				<td colspan="4" class="litespeed-success litespeed-text-center">
					<?php echo __( 'We are good. No table uses MyISAM engine.', 'litespeed-cache' ) ; ?>
				</td>
			</tr>
		<?php endif ; ?>
		</tbody>
	</table>

</div>


<h3 class="litespeed-title"><?php echo __( 'Database Summary', 'litespeed-cache' ) ; ?></h3>

<div class="litespeed-panel-wrapper">
	<h4 class="litespeed-left50">Autoload size: <?php echo LiteSpeed_Cache_Utility::real_size( $autoload_summary->autoload_size ) ; ?></h4>
	<h4 class="litespeed-left50">Autoload entries: <?php echo $autoload_summary->autload_entries ; ?></h4>
	<h4 class="litespeed-left50">Autoload top list:
		<table class="litespeed-table">
			<thead><tr >
				<th scope="col">#</th>
				<th scope="col"><?php echo __('Option Name', 'litespeed-cache') ; ?></th>
				<th scope="col"><?php echo __('Size', 'litespeed-cache') ; ?></th>
			</tr></thead>
			<tbody>
				<?php foreach ( $autoload_summary->autoload_toplist as $k => $v ) : ?>
				<tr>
					<td><?php echo $k + 1 ; ?></td>
					<td><?php echo $v->option_name ; ?></td>
					<td><?php echo $v->option_value_length ; ?></td>
				</tr>
				<?php endforeach ; ?>
			</tbody>
		</table>

</div>





