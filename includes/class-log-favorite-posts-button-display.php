<?php

namespace LogFavoritePosts\Includes;

class Log_Favorite_Posts_Button_Display {

    /**
     * Initializes properties, actions and filters of the class.
     *
     * @return    void
     */
    public function initialize() {
        add_filter( 'the_content', array( $this, 'display_button' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
    }

    /**
     * Shows the bookmark button after the post content.
     * 
     * @param  string $content content of the current post
     * 
     */
    public function display_button( $content ) {
        // Run code only for Single post page
        if ( is_single() && get_post_type() == 'post' ) {
            $post_id = get_the_ID();
            $button_text = ( $this->confirm_favorite( $post_id ) ) ? __( 'UNMARK THIS POST AS FAVORITE', 'log-favorite-posts' ) : __( 'BOOKMARK THIS POST', 'log-favorite-posts' );

            $button_html = '<div>';
                $button_html .= '<button class="bookmark-button" id="' . $post_id . '">' . $button_text . '</button>';
            $button_html .= '</div>';

            $content = $content . $button_html;

            return $content;
        }
    }

    /**
     * Enqueues the scripts.
     *
     * @return    void
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            'log-favorite-posts',
            LOG_FAVORITE_POSTS_PLUGIN_URL . '/assets/js/public.js',
            array( 'jquery', 'wp-i18n' ),
            LOG_FAVORITE_POSTS_VERSION
        );

        wp_set_script_translations(
            'log-favorite-posts',
            'log-favorite-posts',
            LOG_FAVORITE_POSTS_PLUGIN_URL . '/languages'
        );
    }

    private function confirm_favorite($cid) {
        if ( isset( $_COOKIE['wp-favorite-posts'] ) ){
            foreach ($_COOKIE['wp-favorite-posts'] as $fpost_id => $val) {
                if ($fpost_id == $cid && $val == 'added') {
                    return true;
                }
            }

            return false;
        }

        return false;
    }
}