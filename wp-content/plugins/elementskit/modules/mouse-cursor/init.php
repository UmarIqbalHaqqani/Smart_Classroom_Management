<?php
namespace ElementsKit\Modules\Mouse_Cursor;

defined( 'ABSPATH' ) || exit;

class Init {
	private $dir;
	private $url;

	public function __construct() {

		// get current directory path
		$this->dir = dirname(__FILE__) . '/';

		// get current module's url
		$this->url = \ElementsKit::plugin_url() . 'modules/mouse-cursor/';

		// enqueue scripts
		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_cursor_scripts' ] );
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_cursor_style' ] );
		add_action('elementor/preview/enqueue_scripts', [$this, 'enqueue_cursor_scripts']);
		add_action('elementor/preview/enqueue_styles', [$this, 'enqueue_cursor_style']);
		add_action('elementor/editor/after_enqueue_scripts', [$this, 'enqueue_cursor_scripts']);
		add_action('elementor/editor/after_enqueue_styles', [$this, 'enqueue_cursor_style']);

		// include all necessary files
		$this->include_files();

		// calling the wrapper controls
		new \Elementor\ElementsKit_Mouse_Cursor();

	}

	public function include_files() {
		include $this->dir . 'mouse-cursor.php';
	}

	public function enqueue_cursor_style(){
		wp_enqueue_style( 'cotton', $this->url . 'assets/css/style.css' , [], \ElementsKit::version());
	}
	public function enqueue_cursor_scripts() {
		wp_enqueue_script( 'cotton', $this->url . 'assets/js/cotton.min.js' , ['jquery'], \ElementsKit::version(), true );
		wp_enqueue_script( 'mouse-cursor', $this->url . 'assets/js/mouse-cursor-scripts.js' , ['jquery', 'elementor-frontend'], \ElementsKit::version(), true );
	}
}