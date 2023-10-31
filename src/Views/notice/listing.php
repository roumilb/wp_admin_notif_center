<div class="wrap">
    <h2>
        <?php echo __('Notice listing', 'wanc'); ?>
    </h2>
    <form method="post">
        <?php
            $dataView['noticeListingService']->prepare_items();
            $dataView['noticeListingService']->search_box('search', 'search_id');
            $dataView['noticeListingService']->display();
        ?>
    </form>
</div>