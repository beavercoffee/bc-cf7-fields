<?php

if(!class_exists('BC_CF7_Floating_Labels')){
    final class BC_CF7_Floating_Labels {

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    	//
    	// private static
    	//
    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        private static $instance = null;

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    	//
    	// public static
    	//
    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public static function get_instance($file = ''){
            if(null !== self::$instance){
                return self::$instance;
            }
            if('' === $file){
                wp_die(__('File doesn&#8217;t exist?'));
            }
            if(!is_file($file)){
                wp_die(sprintf(__('File &#8220;%s&#8221; doesn&#8217;t exist?'), $file));
            }
            self::$instance = new self($file);
            return self::$instance;
    	}

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    	//
    	// private
    	//
    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        private $file = '';

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    	private function __clone(){}

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    	private function __construct($file = ''){
            $this->file = $file;
            add_action('bc_cf7_fields_loaded', [$this, 'bc_cf7_fields_loaded']);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        private static function placeholder($tag = null, $fallback = ''){
            $values = $tag->values;
            if(in_array($tag->basetype, ['date', 'email', 'number', 'password', 'tel', 'text', 'textarea', 'url'])){
                if($tag->has_option('placeholder') or $tag->has_option('watermark')){
                    if($values){
                        return reset($values);
                    }
                }
            } elseif('select' === $tag->basetype){
                if($tag->has_option('first_as_label')){
                    if($values){
                        return reset($values);
                    }
                }
            }
            return $fallback;
        }

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    	private function select($html = '', $placeholder = '', $tag = null){
           	$html = bc_str_get_html($html);
			$wrapper = $html->find('.wpcf7-form-control-wrap', 0);
			$wrapper->addClass('bc-floating-labels');
			$select = $wrapper->find('select', 0);
			$select->addClass('custom-select');
			$option = $select->find('option', 0);
			$option->innertext = '';
			$select->outertext = $select->outertext . '<label>' . $placeholder . '</label>';
            return $html;
        }

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    	private function text($html = '', $placeholder = '', $tag = null){
            $html = bc_str_get_html($html);
			$wrapper = $html->find('.wpcf7-form-control-wrap', 0);
			$wrapper->addClass('bc-floating-labels');
			$input = $wrapper->find('input', 0);
			$input->addClass('form-control');
			$input->outertext = $input->outertext . '<label>' . $placeholder . '</label>';
            return $html;
        }

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    	private function textarea($html = '', $placeholder = '', $tag = null){
            $html = bc_str_get_html($html);
			$wrapper = $html->find('.wpcf7-form-control-wrap', 0);
			$wrapper->addClass('bc-floating-labels');
			$textarea = $wrapper->find('textarea', 0);
			$textarea->addClass('form-control');
			$textarea->cols = null;
			$textarea->rows = null;
			$textarea->outertext = $textarea->outertext . '<label>' . $placeholder . '</label>';
            return $html;
        }

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    	//
    	// public
    	//
    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function bc_cf7_fields_loaded(){
            add_action('wpcf7_enqueue_scripts', [$this, 'wpcf7_enqueue_scripts']);
            add_action('wpcf7_enqueue_styles', [$this, 'wpcf7_enqueue_styles']);
            add_filter('bc_cf7_field', [$this, 'bc_cf7_field'], 15, 5);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function bc_cf7_field($html, $tag, $type, $basetype, $original_html){
			$placeholder = $this->placeholder($tag);
            if('' === $placeholder){
				return $html;
			}
            switch($type){
                case 'date':
                case 'email':
                case 'number':
                case 'password':
                case 'tel':
                case 'text':
                case 'url':
                    $html = $this->text($original_html, $placeholder, $tag);
                    break;
                case 'select':
                    $html = $this->select($original_html, $placeholder, $tag);
                    break;
                case 'textarea':
                    $html = $this->textarea($original_html, $placeholder, $tag);
                    break;
            }
            return $html;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function wpcf7_enqueue_scripts(){
            $src = plugin_dir_url($this->file) . 'assets/bc-cf7-floating-labels.js';
            $ver = filemtime(plugin_dir_path($this->file) . 'assets/bc-cf7-floating-labels.js');
            wp_enqueue_script('bc-cf7-floating-labels', $src, ['contact-form-7'], $ver, true);
            wp_add_inline_script('bc-cf7-floating-labels', 'bc_cf7_floating_labels.init();');

        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function wpcf7_enqueue_styles(){
            $src = plugin_dir_url($this->file) . 'assets/bc-cf7-floating-labels.css';
            $ver = filemtime(plugin_dir_path($this->file) . 'assets/bc-cf7-floating-labels.css');
            wp_enqueue_style('bc-cf7-floating-labels', $src, ['contact-form-7'], $ver);
        }

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
}
