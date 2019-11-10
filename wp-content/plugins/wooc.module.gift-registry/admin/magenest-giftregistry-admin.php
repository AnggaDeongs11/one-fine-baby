<?php
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly
if (!defined('ABSPATH')) {
    exit ();
} // Exit if accessed directly
class Magenest_Giftregistry_Admin {
/**
 * Constructor.
 */
public function __construct()
{
    add_action('admin_enqueue_scripts', array(__CLASS__, 'wpdocs_enqueue_custom_admin_style'));
//        add_action( 'admin_notices', array(__CLASS__,'delete_notice' ));
}

public static function wpdocs_enqueue_custom_admin_style()
{
    wp_register_script('bootstrap', GIFTREGISTRY_URL . '/assets/js/bootstrap.min.js');
    wp_register_style('my-bootstrap', GIFTREGISTRY_URL . '/assets/css/my-bootstrap.css');
    wp_enqueue_style('my-bootstrap');
    wp_enqueue_script('bootstrap');
}

public function giftregistry_manage()
{
    if (isset ($_REQUEST ['delete'])) {
        if (isset ($_REQUEST ['id'])) {
            $this->delete($_REQUEST ['id']);
        }
    } elseif (isset ($_REQUEST ['edit'])) {
        if (isset ($_REQUEST ['id'])) {
            $this->edit($_REQUEST ['id']);
        }
    } elseif (isset ($_REQUEST ['delete'])) {
    } else {
        $this->index();
    }
}

public function delete($id)
{
    Magenest_Giftregistry_Model::delete_giftregistry($id);
    wp_safe_redirect(admin_url('admin.php?page=gift_registry&settings-updated=true'));
//        exit;
}

public function edit($id)
{
    ?>
    <br>
    <button onclick="window.location.href='<?php echo get_admin_url(null, 'admin.php?page=gift_registry') ?>'"
            name="back" type="button" class="button button-primary button   -large" id="back" accesskey="p"
            value="Back"><?php echo __('Gift registry manage',GIFTREGISTRY_TEXT_DOMAIN) ?> </button>
    <?php
    echo '<h2>'.__('Gift registry',GIFTREGISTRY_TEXT_DOMAIN).'</h2>';
    ob_start();
    $template_path = GIFTREGISTRY_PATH . 'template/account/';
    $default_path = GIFTREGISTRY_PATH . 'template/account/';

    wc_get_template('add-giftregistry.php', array(
        'wid' => $id,

    ), $template_path, $default_path
    );
    echo ob_get_clean();

    /////////////////////////////////////////////////////////////////////
    /////////////////////////////GIFT REGISTRY ITEMS/////////////////////
    ////////////////////////////////////////////////////////////////////
    $items = Magenest_Giftregistry_Model::get_items_in_giftregistry($id);
    ob_start();

    $template_path = GIFTREGISTRY_PATH . 'template/account/';
    $default_path = GIFTREGISTRY_PATH . 'template/account/';


    wc_get_template('my-giftregistry.php', array(
        'items' => $items,
        'wid' => $id
    ), $template_path, $default_path
    );
    echo ob_get_clean();
}

public static function index()
{
// Test the use of paginate_links

$rows_per_page = get_option('posts_per_page');

$current = (isset($_REQUEST['paged']) && intval($_REQUEST['paged'])) ? intval($_REQUEST['paged']) : 1;

// $rows is the array that we are going to paginate.
$rows = Magenest_Giftregistry_Model::get_all_giftregistry();


global $wp_rewrite;

$pagination_args = array(
    'base' => esc_url_raw(@add_query_arg('paged', '%#%')),
    'format' => '&paged=%#%',
    'total' => ceil(sizeof($rows) / $rows_per_page),
    'current' => $current,
    'show_all' => false,
    'type' => 'plain',
);

if ($wp_rewrite->using_permalinks()) {
    $pagination_args['base'] = user_trailingslashit(trailingslashit(remove_query_arg('s', get_pagenum_link(1))) . '&paged=%#%', 'paged');
}

if (!empty($wp_query->query_vars['s'])) {
    $pagination_args['add_args'] = array('s' => get_query_var('s'));
}

echo paginate_links($pagination_args);

$start = ($current - 1) * $rows_per_page;
$end = $start + $rows_per_page;
$end = (sizeof($rows) < $end) ? sizeof($rows) : $end;

if (isset($_REQUEST['settings-updated']) && $_REQUEST['settings-updated'] == true) {
    ?>
    <div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">
        <p><strong><?=__('Deleted gift registry',GIFTREGISTRY_TEXT_DOMAIN)?></strong></p>
        <button type="button" class="notice-dismiss"><span class="screen-reader-text"><?=__('Dismiss this notice.',GIFTREGISTRY_TEXT_DOMAIN)?></span>
        </button>
    </div>
    <?php
}
?>
<h2><?=__('Gift Registry Management',GIFTREGISTRY_TEXT_DOMAIN)?></h2>
<table id="wishlit-tbl" class="wp-list-table widefat fixed">
    <thead>
    <tr>
        <th>
            <?php echo __('User id', GIFTREGISTRY_TEXT_DOMAIN) ?>
        </th>
        <th>
            <?php echo __('Owner\'s registry name', GIFTREGISTRY_TEXT_DOMAIN) ?>
        </th>
        <th>
            <?php echo __('Owner\'s registry email', GIFTREGISTRY_TEXT_DOMAIN) ?>
        </th>
        <th>
            <?php echo __('Date Time', GIFTREGISTRY_TEXT_DOMAIN) ?>
        </th>
        <th>
            <?php echo __('Delete', GIFTREGISTRY_TEXT_DOMAIN) ?>
        </th>
        <th>
            <?php echo __('Edit', GIFTREGISTRY_TEXT_DOMAIN) ?>
        </th>
    </tr>
    </thead>
    <?php

    for ($i = $start; $i < $end; ++$i) {
        $row = $rows[$i];
        $phpdate = strtotime($row['event_date_time']);
        $order_date = date('d M, Y h:i A', $phpdate);
        $http_schema = 'http://';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) {
            $http_schema = 'https://';
        }
        $delete_link = $http_schema . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . '&delete=1';
        $edit_link = $http_schema . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . '&edit=1';
        echo '<tr>';
        ?>
        <td><?php echo $row['user_id'] ?> </td>
        <td><?php echo $row['registrant_firstname'] ?> </td>
        <td><?php echo $row['registrant_email'] ?> </td>
        <td><?php echo $order_date ?> </td>
        <td>
            <a href="<?php echo $delete_link . '&id=' . $row['id'] ?>"><?php echo __('Delete', GIFTREGISTRY_TEXT_DOMAIN) ?></a>
        </td>
        <td>
            <a href="<?php echo $edit_link . '&id=' . $row['id'] ?>"><?php echo __('Edit', GIFTREGISTRY_TEXT_DOMAIN) ?></a>
        </td>
        <td></td>


        <?php
        echo '</tr>';

    }
    echo '</table>';
    }

    public function giftregistry_manages()
    {
        $rows_per_page = 10;
        $current = (intval(get_query_var('paged'))) ? intval(get_query_var('paged')) : 1;

        //$rows = $wpdb->get_results('SELECT * FROM subscriber ORDER BY sub_lname ASC');

        $rows = Magenest_Giftregistry_Model::get_all_giftregistry();
        $start = ($current - 1) * $rows_per_page;
        $end = $start + $rows_per_page;
        $end = (sizeof($rows) < $end) ? sizeof($rows) : $end;

        $pagination_args = array(
            'base' => esc_url_raw(@add_query_arg('paged', '%#%')),
            'format' => '&paged=%#%',
            'total' => ceil(sizeof($rows) / $rows_per_page) + 1,
            'current' => $current,
            'show_all' => false,
            'prev_next' => true,
            'prev_text' => __(' Previous'),
            'next_text' => __('Next '),
            'type' => 'plain',
            'add_args' => false
        );

        echo paginate_links($pagination_args);
    }
    }
    ?>
