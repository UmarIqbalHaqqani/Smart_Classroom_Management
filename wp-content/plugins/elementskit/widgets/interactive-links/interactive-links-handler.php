<?php
namespace Elementor;

class ElementsKit_Widget_Interactive_Links_Handler extends \ElementsKit_Lite\Core\Handler_Widget {

    static function get_name() {
        return 'elementskit-interactive-links';
    }

    static function get_title() {
        return esc_html__( 'Interactive Links', 'elementskit' );
    }

    static function get_icon() {
        return 'ekit ekit-widget-icon ekit-link';
    }

    static function get_categories() {
        return [ 'elementskit' ];
    }

    static public function get_keywords() {
		return [ 'ekit', 'links', 'interacive', 'dynamic', 'list' ];
	}

    static function get_dir() {
        return \ElementsKit::widget_dir() . 'interactive-links/';
    }

    static function get_url() {
        return \ElementsKit::widget_url() . 'interactive-links/';
    }

}