<?php
/**
 * The template for displaying Movie single post
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

<div class="wrap">
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

            <?php
            /* Start the Loop */
            while ( have_posts() ) : the_post();?>

                <h1><?php the_title();?></h1>
                <h5><?php the_excerpt();?></h5>
            <?php
                $terms = get_the_terms( $post->ID, 'es_movie_tag' );
                if ( count( $terms ) > 0 ) {
                    echo "<ul class='movie-tags'>";
                    foreach ( $terms as $term ) {
                        echo '<li>' . $term->name . '</li>';
                    }
                    echo "</ul>";
                    echo "<div class='clearfix'></div>";
                }

                the_content();

                $price = get_post_meta( $post->ID, "price", true );

                if ( $price > 0 ):?>
                    <div class="single-product">
                        <p class="price">$<?php echo $price; ?></p>

                        <form class="cart" action="" method="post" enctype='multipart/form-data'>
                            <input name="add-to-cart" type="hidden" value="<?php echo $post->ID ?>" />
                            <div class="quantity">
                                <input name="quantity" type="number" value="1" min="1"  />
                            </div>
                            <button type="submit" value="Add to cart" class="single_add_to_cart_button button alt">Add to cart</button>
                        </form>
                    </div>
                <?php endif;
                
                twentyseventeen_entry_footer();

                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;

                the_post_navigation( array(
                    'prev_text' => '<span class="screen-reader-text">' . __( 'Previous Post', 'twentyseventeen' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Previous', 'twentyseventeen' ) . '</span> <span class="nav-title"><span class="nav-title-icon-wrapper">' . twentyseventeen_get_svg( array( 'icon' => 'arrow-left' ) ) . '</span>%title</span>',
                    'next_text' => '<span class="screen-reader-text">' . __( 'Next Post', 'twentyseventeen' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Next', 'twentyseventeen' ) . '</span> <span class="nav-title">%title<span class="nav-title-icon-wrapper">' . twentyseventeen_get_svg( array( 'icon' => 'arrow-right' ) ) . '</span></span>',
                ) );

            endwhile; // End of the loop.
            ?>

        </main><!-- #main -->
    </div><!-- #primary -->
    <?php get_sidebar(); ?>
</div><!-- .wrap -->

<?php get_footer();
