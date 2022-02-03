<?php


function divichild_enqueue_scripts() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_script( 'custom-js', get_stylesheet_directory_uri() . '/js/scripts.js', array( 'jquery' ));
}
add_action( 'wp_enqueue_scripts', 'divichild_enqueue_scripts' );


//you can add custom functions below this line:

/* Calculate the estimated reading time for a given piece of $content.
*
* @param string $content Content to calculate read time for.
* @param int $wpm Estimated words per minute of reader.
*
* @returns	int $time Estimated reading time.
*/
function reading_time( $content = '', $wpm = 250 ) {
    $clean_content = strip_shortcodes( $content );
    $clean_content = strip_tags( $clean_content );
    $word_count = str_word_count( $clean_content );
    $time = ceil( $word_count / $wpm );
    return $time;
}

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
                $item_permalink = get_the_permalink();

                // We gotta move the tags into an array, clean them of duplicates and construct a template item.
                if( $item_tags ) {
                    if( !isset( $item_tags_constructed ) ) {
                        $item_tags_constructed = [];
                    }
                    foreach( $item_tags as $item_tag ) {
                        $item_tag_link = get_tag_link( $item_tag->term_id );
                        $item_tag_id = $item_tag->term_id;
                        $item_tags_constructed[] = " <a class='blog-item__tag blog-item__tag--$item_tag_id' href='$item_tag_link'>$item_tag->name</a> ";
                        // var_dump( $item_tags_constructed);
                    }
                    $final_item_tags_constructed = array_unique( $item_tags_constructed );
                    // var_dump( $final_item_tags_constructed);
                    $final_item_tags_constructed = implode( $final_item_tags_constructed );
                }

                //We have to use the function above to construct the time to read.
                $item_time_to_read = reading_time( get_the_content() );
                $item_categories = get_the_category();
                // var_dump( $item_categories );
                if( $item_categories ) {
                    // var_dump( $item_categories );
                        if( !isset( $item_categories_constructed ) ) {
                            $item_categories_constructed = [];
                        }
                        foreach( $item_categories as $item_tag ) {
                            $item_tag_link = get_category_link( $item_tag->term_id );
                            $item_tag_id = $item_tag->term_id;
                            $item_categories_constructed[] = " <a href='$item_tag_link' class='blog-item__category blog-item__category--$item_tag_id'>$item_tag->name</a> ";
                            // var_dump( $item_tags_constructed);
                        }
                        $final_item_categories_constructed = array_unique( $item_categories_constructed );
                        // var_dump( $final_item_tags_constructed);
                        $final_item_categories_constructed = implode( $final_item_categories_constructed );
                        // var_dump( $item_categories_constructed );
                }
                $item_time = get_the_date();
                $item_class = implode( ' ' ,get_post_class( "blog-item blog-item--$item_id" ));
                $item_featured_image = get_the_post_thumbnail( $item_id ,'full', [ 'class' => "blog-item__image blog-item__image--$item_id" ]  );
                // var_dump( $item_tags );

                //Template tags to construct
                $item_tags_template = "<div class='blog-item__tags blog-item__tags--$item_id'>$final_item_tags_constructed</div>";

                $item_featured_template = "<a href='$item_permalink'>$item_featured_image</a>";

                $item_date_template = "<div class='blog-item__time blog-item__time--$item_id'>$item_time</div>";

                // $item_info_template = "";

                $item_time_read_template = "<div class='blog-item__read blog-item__read--$item_id'>Time to read: $item_time_to_read min</div>";

                $item_category_template = "<div class='blog-item__categories blog-item__categories--$item_id'>$final_item_categories_constructed</div>";

                $item_title_template = "<a href='$item_permalink'><h3 class='blog-item__title blog-item__title--$item_id'>$item_title</h3></a>";



                $item_template = "<div class='$item_class'>
                    <div class='blog-item__header blog-item__header--$item_id'>

                        $item_tags_template
                        $item_featured_template
                    </div>
                    <div class='blog-item__body blog-item__body--$item_id'>
                        $item_date_template
                        $item_title_template
                        $item_category_template
                        $item_time_read_template
                    </div>
                </div>";
                $items .= $item_template;
            }


            $template_pagination = the_posts_pagination();

            $templates = "<div class='blog-items'>$items</div>$template_pagination";
            return $templates;
        }
    }
}

add_shortcode( 'blog_shortcode', 'blog_shortcode' );
