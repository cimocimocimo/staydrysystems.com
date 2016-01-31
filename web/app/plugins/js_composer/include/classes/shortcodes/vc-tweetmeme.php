<?php
class WPBakeryShortCode_VC_TweetMeMe extends WPBakeryShortCode {
    protected function contentInline( $atts, $content = null ) {
        extract(shortcode_atts(array(
            'type' => 'horizontal'//horizontal, vertical, none
        ), $atts));

        $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc-social-placeholder twitter-share-button vc-socialtype-' . $type, $this->settings['base'], $atts );
        return '<div class="'.$css_class.'"></div>';
    }
}