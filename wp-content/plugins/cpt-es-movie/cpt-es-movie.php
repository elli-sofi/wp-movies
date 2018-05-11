<?php
   /**
    * Plugin Name:   Movie Custom Post Type
    * Description:   Register a WordPress CPT `es_movie` with a custom Taxonomy `es_movie_tag` and add ability to shop created content with WooCommerce
    * Version:       1.0
    * Author:        Elli-Sofi
    * License:       GPLv2 or later
    * License URI:   http://www.gnu.org/licenses/gpl-2.0.html
    * Text Domain:   es-movie
    * Domain Path:   /languages
    */


/**
 * Register a public Movie CPT and Taxonomy
 */
add_action( 'init', 'es_movie_cpt' );

function es_movie_cpt() {

    // Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Movies Collection', 'Post Type General Name', 'es-movie' ),
        'singular_name'       => _x( 'Movie', 'Post Type Singular Name', 'es-movie' ),
        'menu_name'           => __( 'Movies', 'es-movie' ),
        'parent_item_colon'   => __( 'Parent Movie', 'es-movie' ),
        'all_items'           => __( 'All Movies', 'es-movie' ),
        'view_item'           => __( 'View Movie', 'es-movie' ),
        'add_new_item'        => __( 'Add New Movie', 'es-movie' ),
        'add_new'             => __( 'Add New', 'es-movie' ),
        'edit_item'           => __( 'Edit Movie', 'es-movie' ),
        'update_item'         => __( 'Update Movie', 'es-movie' ),
        'search_items'        => __( 'Search Movie', 'es-movie' ),
        'not_found'           => __( 'Not Found', 'es-movie' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'es-movie' ),
    );

    $supports = array(
        'title',
        'editor',
        'thumbnail',
        'excerpt',
        'comments',
        'revisions',
    );
    
    // Set other options for Custom Post Type
    $args = array(
        'label'               => __( 'Movies', 'es-movie' ),
        'description'         => __( 'Movie reviews', 'es-movie' ),
        'labels'              => $labels,
        'supports'            => $supports,
        'hierarchical'        => false,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'menu_icon'             => 'dashicons-format-video',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'capability_type'     => 'page',
        'rewrite'             => array(
            'slug'        => _x( 'movies', 'CPT permalink slug', 'es-movie' ),
            'with_front'  => false,
        ),
        'register_meta_box_cb' => 'es_movie_add_price_metabox',
    );
     
    // Registering Custom Post Type
    register_post_type( 'es_movie', $args );

    // Registering Taxonomy
    register_taxonomy(
        'es_movie_tag',
        'es_movie',
        array(
            'label'             => __( 'Movie Tags', 'es_movie' ),
            'show_admin_column' => true,
            'rewrite'           => array(
                'slug' => _x( 'movie-tag', 'Custom Taxonomy slug', 'es_movie' ),
            ),
        )
    );
}

/**
 * Adds a metabox with Price and Favorite to the right side of the screen under the “Publish” box
 */
function es_movie_add_price_metabox() {
    add_meta_box(
        'es_movie_price',
        'Price',
        'es_movie_price',
        'es_movie',
        'normal',
        'high'
    );

    add_meta_box(
        'es_movie_favorite',
        'Favorite',
        'es_movie_favorite',
        'es_movie',
        'normal',
        'high'
    );
}

/**
 * Output the HTML for Price and Favorite metaboxes.
 */
function es_movie_price() {
    global $post;
    wp_nonce_field( basename( __FILE__ ), 'es_movie_fields' );
    $price = get_post_meta( $post->ID, 'price', true );
    echo '<input type="text" name="price" value="' . esc_textarea( $price )  * 1.0 . '">';
}

function es_movie_favorite() {
    global $post;
    wp_nonce_field( basename( __FILE__ ), 'es_movie_fields' );
    $favorite = get_post_meta( $post->ID, 'favorite', true );
    echo '<label for="favorite"><input name="favorite" id="favorite" type="checkbox"' . ($favorite ? 'checked' : '') . '> In favorite collection</label>';
}

/**
 * Save price and favorite
 */
add_action( 'save_post', 'es_movie_save_additional_meta', 1, 2 );

function es_movie_save_additional_meta( $post_id, $post ) {

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
    }
    if ( ! isset( $_POST['price'] ) || ! wp_verify_nonce( $_POST['es_movie_fields'], basename( __FILE__ ) ) ) {
        return $post_id;
    }
    $meta_additional['price'] = esc_textarea( $_POST['price'] ) * 1.0;
    $meta_additional['favorite'] = $_POST['favorite'];
    
    foreach ( $meta_additional as $key => $value ) :
        // Don't store custom data twice
        if ( 'revision' === $post->post_type ) {
            return;
        }
        if ( get_post_meta( $post_id, $key, false ) ) {
            // If the custom field already has a value, update it.
            update_post_meta( $post_id, $key, $value );
        } else {
            // If the custom field doesn't have a value, add it.
            add_post_meta( $post_id, $key, $value);
        }
        if ( ! $value ) {
            // Delete the meta key if there's no value
            delete_post_meta( $post_id, $key );
        }
    endforeach;
}

/**
 * Filter the single_template with our custom function
 */
add_filter('single_template', 'es_movie_custom_template');

function es_movie_custom_template($template) {
    global $post;

    // Is this a "my-custom-post-type" post?
    if ($post->post_type == "es_movie"){

        $plugin_path = wp_normalize_path( plugin_dir_path ( __FILE__ ) );
        $template_name = 'single-es_movie.php';

        if($template === get_stylesheet_directory() . '/' . $template_name
            || ! file_exists( $plugin_path . 'templates/' . $template_name ) ) {
            return $template;
        }

        return $plugin_path . 'templates/' . $template_name;
    }

    return $template;
}

/**
 * Posts to Homepage
 */
add_action( 'pre_get_posts', 'es_movie_add_post_type_to_home' );

function es_movie_add_post_type_to_home( $query ) {
    if( $query->is_main_query() && $query->is_home() ) {
        $query->set( 'post_type', array( 'post', 'es_movie') );
    }
}

/**
 * Add custom Favorite Movies Stylesheet
 */
add_action( 'wp_enqueue_scripts', 'es_movie_favorites_style' );

function es_movie_favorites_style() {
  global $post;
  if ( is_page( 'Best Movies' ) || $post->post_type == "es_movie" ) {
    wp_enqueue_style(
         "es-movie",
         plugins_url( 'styles/es-movie.css', __FILE__),
         array()
    );
 }
}

/**
 * Generate content for Favorite Movies Page
 */
add_action( 'the_content', 'es_movie_favorites_page');

function es_movie_favorites_page( $content ) {
  if ( is_page( 'Best Movies' ) ) { ?> 
        <ul class='es-movie-favorites-list'>
            <?php
            global $post;
            $args = array(
                'post_type' => 'es_movie',
                'meta_key' => 'favorite',
                'meta_value' => 'on',
                'posts_per_page' => 9,
                'orderby' => 'rand'
            );
            $rand_posts = get_posts( $args );

            foreach ( $rand_posts as $post ) : 
              setup_postdata( $post );
              $price = get_post_meta( $post->ID, 'price', true );
               ?>

                <li>
                    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail');?></a>
                    <h5>$<?php echo $price;?></h5>
                    <h2><?php the_title(); ?></h2>
                </li>
            
            <?php endforeach; 
            wp_reset_postdata(); ?>
        </ul>
  <?php 
    return '';
    }
    return $content;
}

/**
 * WooCommerce additions for Movies
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    add_action('woocommerce_loaded', function () {
        // Prepare Movies woocommerce class
        class WCCPT_Product_Data_Store_CPT extends WC_Product_Data_Store_CPT {
            /**
             * Method to read a product from the database.
             * @param WC_Product
             */

            public function read( &$product ) {
                $product->set_defaults();

                if ( ! $product->get_id() || ! ( $post_object = get_post( $product->get_id()) ) || ! in_array( $post_object->post_type, array( 'es_movie', 'product' ) ) ) {
                    throw new Exception( __( 'Invalid product.', 'woocommerce' ) );
                }

                $id = $product->get_id();

                $product->set_props( array(
                    'name'              => $post_object->post_title,
                    'slug'              => $post_object->post_name,
                    'date_created'      => 0 < $post_object->post_date_gmt ? wc_string_to_timestamp( $post_object->post_date_gmt ) : null,
                    'date_modified'     => 0 < $post_object->post_modified_gmt ? wc_string_to_timestamp( $post_object->post_modified_gmt ) : null,
                    'status'            => $post_object->post_status,
                    'description'       => $post_object->post_content,
                    'short_description' => $post_object->post_excerpt,
                    'parent_id'         => $post_object->post_parent,
                    'menu_order'        => $post_object->menu_order,
                    'reviews_allowed'   => 'open' === $post_object->comment_status,
                ));

                $this->read_attributes( $product );
                $this->read_downloads( $product );
                $this->read_visibility( $product );
                $this->read_product_data( $product );
                $this->read_extra_data( $product );
                $product->set_object_read( true );
            }

            /**
             * Get the product type based on product ID.
             *
             * @since 3.0.0
             * @param int $product_id
             * @return bool|string
             */
            public function get_product_type( $product_id ) {
                $post_type = get_post_type( $product_id );
                if ( 'product_variation' === $post_type ) {
                    return 'variation';
                } elseif ( in_array( $post_type, array( 'es_movie', 'product' ) ) ) {
                    $terms = get_the_terms( $product_id, 'product_type' );
                    return ! empty( $terms ) ? sanitize_title( current( $terms )->name ) : 'simple';
                } else {
                    return false;
                }
            }
        }
    });

}

add_filter( 'woocommerce_data_stores', 'woocommerce_data_stores' );

function woocommerce_data_stores ( $stores ) {
    $stores['product'] = 'WCCPT_Product_Data_Store_CPT';
    return $stores;
}

/**
 * Price function
 */
add_filter('woocommerce_get_price', 'woocommerce_get_price', 10, 2 );
add_filter('woocommerce_get_regular_price','woocommerce_get_price', 10, 2);
add_filter('woocommerce_get_sale_price','woocommerce_get_price', 10, 2);
add_filter('woocommerce_order_amount_item_subtotal','woocommerce_get_price', 10, 2);

function woocommerce_get_price( $price, $product ) {
    if ( $product->post->post_type === 'es_movie' ) {
        $price = get_post_meta( $product->post->ID, "price", true );
    }
    return $price;
}

/**
 * Redirect to PayPal for this CPT after click on "Add to Cart" button
 */
add_action( 'woocommerce_add_to_cart_validation', 'es_movie_check_wpse', 10, 3 );

function es_movie_check_wpse( $true, $product_id, $quantity ) {
    $post_type = get_post_type( $product_id );

    if ( $post_type != "es_movie" )
        return $true;

    add_filter( 'add_to_cart_redirect', 'es_movie_redirect_wpse' );
    add_filter( 'allowed_redirect_hosts', 'es_movie_whitelist_wpse' );
    return $true;
}

function es_movie_redirect_wpse( $url ) {
    return '/cart/?startcheckout=true';
}

function es_movie_whitelist_wpse( $hosts ) {
    $hosts[] = 'paypal.com';
    return $hosts;
}