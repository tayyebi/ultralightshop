<?php
// Template part: searchform
?>
<form role="search" method="get" class="search-form" action="<?php echo home_url('/'); ?>">
    <label>
        <span class="screen-reader-text"><?php _x('Search', 'label') ?></span>
        <input type="search" class="search-field" placeholder="<?php echo esc_attr_x('Search …', 'placeholder') ?>" 
        value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x('Search for:', 'label') ?>" />
    </label>
    <div id="price-slider"></div>
    <input type="hidden" id="price_min" name="price_min" value="0">
    <input type="hidden" id="price_max" name="price_max" value="1000">
    <input type="text" id="price-amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
    <input type="submit" class="search-submit" value="<?php echo esc_attr_x('Search', 'submit button') ?>">
</form>
