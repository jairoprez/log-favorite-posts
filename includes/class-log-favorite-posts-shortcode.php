<?php

namespace LogFavoritePosts\Includes;

class Log_Favorite_Posts_Shortcode {

    /**
     * Initializes properties, actions and filters of the class.
     *
     * @return    void
     */
    public function initialize() {
        add_shortcode( 'display_favorite_posts', array( $this, 'display_favorite_posts' ) );
    }

    /**
     * Shows favorite posts.
     * 
     * @param  array $atts the shortcode attributes, the shortcode content (if any)
     * 
     * @return string HTML info/view
     */
    public function display_favorite_posts( $atts ) {
        $atts = shortcode_atts([ 
            'posts_per_page' => 5
        ], $atts, 'display_favorite_posts' );

        $query = $this->get_favorite_posts( $atts );
        
        if ( ! $query->have_posts() ) {
            return;
        }
        ?>
        
        <ul>
            <?php foreach ( $query->posts as $recent_post ) : ?>
                <?php
                $post_title   = get_the_title( $recent_post->ID );
                $title        = ( ! empty( $post_title ) ) ? $post_title : __( '(no title)' );
                $aria_current = '';

                if ( get_queried_object_id() === $recent_post->ID ) {
                    $aria_current = ' aria-current="page"';
                }
                ?>
                <li>
                    <a href="<?php the_permalink( $recent_post->ID ); ?>"<?php echo $aria_current; ?>><?php echo $title; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php
    }

    private function get_favorite_posts( $atts ) {
        $post_ids = [];
        if ( isset( $_COOKIE['wp-favorite-posts'] ) ){
            foreach ( $_COOKIE['wp-favorite-posts'] as $fpost_id => $val ) {
                if ( $val ) {
                    array_push( $post_ids, $fpost_id );
                }
            }
        }

        if ( empty( $post_ids ) ) {
            $post_ids = [0];
        }

        return new \WP_Query([
            'posts_per_page'      => $atts['posts_per_page'],
            'post__in'            => $post_ids,
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
        ]);
    }
}