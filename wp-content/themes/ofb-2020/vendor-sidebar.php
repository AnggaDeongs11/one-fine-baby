<?php
global $wp;

$arrLinks = [
    ['link' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard.svg'],
    ['link' => 'dashboard/product', 'label' => 'Products', 'icon' => 'products.svg'],
    ['link' => 'dashboard/order', 'label' => 'Orders', 'icon' => 'order.svg'],
    ['link' => 'dashboard/settings', 'label' => 'Settings', 'icon' => 'settings.svg'],
    ['link' => '#', 'label' => 'More', 'icon' => 'more.svg'],
];
?>
<nav class="vendor-nav">
    <ul>
        <?php foreach ($arrLinks as $arrLink) :?>
        <li class="vendor-nav__list <?php echo $wp->request === $arrLink['link'] ? 'active' : ''; ?>">
            <a href="<?php echo get_site_url(null, $arrLink['link']); ?>" class="vendor-nav__link">
                <img src="/wp-content/themes/ofb-2020/images/<?php echo $arrLink['icon']; ?>"/>
                <span><?php echo $arrLink['label']; ?></span>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
    <a href="/dashboard/product/edit/" class="btn btn--primary">
        <img src="/wp-content/themes/ofb-2020/images/add.svg" style="margin-right: 9px;margin-top: -4px;"/>
        <span>Add Product</span></a>
</nav>
