<?php

namespace LogFavoritePosts\Includes;

class Log_Favorite_Posts_Widget extends \WP_Widget {

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {
        $widget_ops = array( 
            'classname' => 'log_favorite_posts_widget',
            'description' => __( 'Your site&#8217;s favorite Posts.' ),
        );

        parent::__construct( 'log_favorite_posts_widget', 'Log Favorite Posts', $widget_ops );
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Favorite Posts' );
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;

        if ( ! $number ) {
            $number = 5;
        }

        $query = $this->get_favorite_posts( $instance, $number );

        if ( ! $query->have_posts() ) {
            return;
        }
        ?>
        <?php echo $args['before_widget']; ?>
        <?php
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
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
        echo $args['after_widget'];
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form( $instance ) {
        $title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;

        require LOG_FAVORITE_POSTS_PLUGIN_DIR . '/views/widget-fields.php';
    }

    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     *
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['number'] = (int) $new_instance['number'];

        return $instance;
    }

    private function get_favorite_posts( $instance, $number ) {
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

        return new \WP_Query(
            apply_filters(
                'widget_posts_args',
                array(
                    'posts_per_page'      => $number,
                    'post__in'            => $post_ids,
                    'no_found_rows'       => true,
                    'post_status'         => 'publish',
                    'ignore_sticky_posts' => true,
                ),
                $instance
            )
        );
    }
}