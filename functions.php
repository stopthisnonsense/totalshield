<?php


function divichild_enqueue_scripts() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_script( 'custom-js', get_stylesheet_directory_uri() . '/js/scripts.js', array( 'jquery' ));
}
add_action( 'wp_enqueue_scripts', 'divichild_enqueue_scripts' );


//you can add custom functions below this line:


function blog_shortcode() {
    if( is_home() ) {
        if( have_posts() ) {
            while( have_posts() ) {
                if( !isset($items) ) {
                    $items = '';
                }
                the_post();
                $item_title = get_the_title();
                $item_id = get_the_ID();
                $item_tags = get_the_tags( '', );

                if( $item_tags ) {
                    if( !isset( $item_tags_constructed ) ) {
                        $item_tags_constructed = '';
                    }
                    foreach( $item_tags as $item_tag ) {
                        $item_tag_link = get_tag_link( $item_tag->term_id );
                        $item_tags_constructed .= " <a href='$item_tag_link'>$item_tag->name</a> ";
                        // var_dump( $item_tags_constructed);
                    }
                }

                $item_categories = get_the_category();
                // var_dump( $item_categories );
                if( $item_categories ) {
                    // var_dump( $item_categories );
                        if( !isset( $item_categories_constructed ) ) {
                            $item_categories_constructed = '';
                        }
                        foreach( $item_categories as $item_tag ) {
                            $item_tag_link = get_category_link( $item_tag->term_id );
                            $item_categories_constructed .= " <a href='$item_tag_link'>$item_tag->name</a> ";
                            // var_dump( $item_tags_constructed);
                        }
                        // var_dump( $item_categories_constructed );
                }
                $item_time = get_the_time();
                $item_class = implode( ' ' ,get_post_class( "blog-item blog-item--$item_id" ));
                $item_featured_image = get_the_post_thumbnail( $item_id ,'full' );
                // var_dump( $item_tags );

                //Template tags to construct
                $item_tags_template = "<div class='blog-item__tags blog-item__tags--$item_id'>$item_tags_constructed</div>";

                $item_featured_template = "$item_featured_image";

                $item_date_template = "<div class='blog-item__time blog-item__time--$item_id'>$item_time</div>";

                // $item_info_template = "";

                $item_time_read_template = "<div class='blog-item__read blog-item__read--$item_id'>Time to read: 1 min</div>";

                $item_category_template = "<div class='blog-item__categories blog-item__categories--$item_id'>$item_categories_constructed</div>";

                $item_title_template = "<h3 class='blog-item__title blog-item__title--$item_id'>$item_title</h3>";



                $item_template = "<div class='$item_class'>
                    <div class='blog-item__header blog-item__header--$item_id'>

                        $item_tags_constructed
                        $item_featured_template
                    </div>
                    <div>
                        $item_date_template
                        $item_title_template
                        $item_time_read_template
                        $item_category_template
                    </div>
                </div>";
                $items .= $item_template;
            }

            $templates = "<div class='blog-items'>$items</div>";
            return $templates;
        }
    }
}

add_shortcode( 'blog_shortcode', 'blog_shortcode' );
