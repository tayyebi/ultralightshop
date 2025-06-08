<?php
// Pre-get-posts filter for price slider
add_action('pre_get_posts', function($query) {
    if(!is_admin() && $query->is_main_query() && $query->is_search()){
        if(isset($_GET['price_min']) || isset($_GET['price_max'])){
            $meta_query = [];
            if(!empty($_GET['price_min'])){
                $meta_query[] = ['key'=>'price','value'=>$_GET['price_min'],'type'=>'NUMERIC','compare'=>'>='];
            }
            if(!empty($_GET['price_max'])){
                $meta_query[] = ['key'=>'price','value'=>$_GET['price_max'],'type'=>'NUMERIC','compare'=>'<='];
            }
            if($meta_query){
                $query->set('meta_query', $meta_query);
            }
        }
    }
});

// Order posts by product first, then post
add_filter('posts_orderby', function($orderby) {
    global $wpdb;
    if(is_home() || is_archive() || is_search()){
        $orderby = "FIELD({$wpdb->posts}.post_type, 'product', 'post'), {$wpdb->posts}.post_date DESC";
    }
    return $orderby;
});
