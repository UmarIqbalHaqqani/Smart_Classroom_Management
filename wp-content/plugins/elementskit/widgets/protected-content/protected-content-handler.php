<?php
namespace Elementor;

defined('ABSPATH') || exit;

class ElementsKit_Widget_Protected_Content_Handler extends \ElementsKit_Lite\Core\Handler_Widget {

	static function get_name() {
		return 'elementskit-protected-content';
	}

	static function get_title() {
		return esc_html__( 'Protected Content', 'elementskit' );
	}

	static function get_icon() {
		return 'ekit ekit-protected-content-v3 ekit-widget-icon';
	}

	static function get_categories() {
		return [ 'elementskit' ];
	}

	static function get_keywords() {
		return ['ekit', 'protected content', 'protected', 'content'];
	}

	static function get_dir() {
		return \ElementsKit::widget_dir() . 'protected-content/';
	}

	static function get_url() {
		return \ElementsKit::widget_url() . 'protected-content/';
	}
}