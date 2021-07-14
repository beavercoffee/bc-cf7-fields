<?php

if(!class_exists('BC_CF7_Fields')){
    final class BC_CF7_Fields {

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
            add_action('plugins_loaded', [$this, 'plugins_loaded']);
        }

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    	//
    	// public
    	//
    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function plugins_loaded(){
            if(!defined('BC_FUNCTIONS')){
        		return;
        	}
            if(!defined('WPCF7_VERSION')){
        		return;
        	}
            add_action('wpcf7_init', [$this, 'wpcf7_init']);
            add_filter('wpcf7_validate_password', [$this, 'wpcf7_password_validation_filter'], 10, 2);
            add_filter('wpcf7_validate_password*', [$this, 'wpcf7_password_validation_filter'], 10, 2);
            add_filter('wpcf7_validate_radio*', 'wpcf7_checkbox_validation_filter', 10, 2);
            remove_action('wpcf7_init', 'wpcf7_add_form_tag_acceptance');
    		remove_action('wpcf7_init', 'wpcf7_add_form_tag_checkbox');
    		remove_action('wpcf7_init', 'wpcf7_add_form_tag_date');
    		remove_action('wpcf7_init', 'wpcf7_add_form_tag_file');
    		remove_action('wpcf7_init', 'wpcf7_add_form_tag_number');
    		remove_action('wpcf7_init', 'wpcf7_add_form_tag_select');
            remove_action('wpcf7_init', 'wpcf7_add_form_tag_submit');
    		remove_action('wpcf7_init', 'wpcf7_add_form_tag_text');
    		remove_action('wpcf7_init', 'wpcf7_add_form_tag_textarea');
            bc_build_update_checker('https://github.com/beavercoffee/bc-cf7-fields', $this->file, 'bc-cf7-fields');
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function wpcf7_init(){
            wpcf7_add_form_tag('acceptance', function($tag){
                $html = wpcf7_acceptance_form_tag_handler($tag);
                $html = apply_filters('bc_cf7_field', $html, $tag, 'acceptance', 'acceptance', $html);
                return $html;
            }, [
        		'name-attr' => true,
			]);
			wpcf7_add_form_tag(['checkbox', 'checkbox*'], function($tag){
                $html = wpcf7_checkbox_form_tag_handler($tag);
                $html = apply_filters('bc_cf7_field', $html, $tag, 'checkbox', 'checkbox', $html);
                return $html;
            }, [
				'multiple-controls-container' => true,
        		'name-attr' => true,
                'selectable-values' => true,
        	]);
			wpcf7_add_form_tag(['date', 'date*'], function($tag){
                $html = wpcf7_date_form_tag_handler($tag);
                $html = apply_filters('bc_cf7_field', $html, $tag, 'date', 'date', $html);
                return $html;
            }, [
        		'name-attr' => true,
        	]);
            wpcf7_add_form_tag(['email', 'email*'], function($tag){
                $html = wpcf7_text_form_tag_handler($tag);
                $html = apply_filters('bc_cf7_field', $html, $tag, 'email', 'text', $html);
                return $html;
            }, [
        		'name-attr' => true,
        	]);
			wpcf7_add_form_tag(['file', 'file*'], function($tag){
                $html = wpcf7_file_form_tag_handler($tag);
                $html = apply_filters('bc_cf7_field', $html, $tag, 'file', 'file', $html);
                return $html;
            }, [
				'file-uploading' => true,
        		'name-attr' => true,
        	]);
			wpcf7_add_form_tag(['number', 'number*'], function($tag){
                $html = wpcf7_number_form_tag_handler($tag);
                $html = apply_filters('bc_cf7_field', $html, $tag, 'number', 'number', $html);
                return $html;
            }, [
        		'name-attr' => true,
        	]);
            wpcf7_add_form_tag(['radio', 'radio*'], function($tag){
                $html = wpcf7_checkbox_form_tag_handler($tag);
                $html = apply_filters('bc_cf7_field', $html, $tag, 'radio', 'checkbox', $html);
                return $html;
            }, [
				'multiple-controls-container' => true,
        		'name-attr' => true,
                'selectable-values' => true,
        	]);
			wpcf7_add_form_tag(['range', 'range*'], function($tag){
				$html = wpcf7_number_form_tag_handler($tag);
                $html = apply_filters('bc_cf7_field', $html, $tag, 'range', 'number', $html);
                return $html;
            }, [
        		'name-attr' => true,
        	]);
			wpcf7_add_form_tag(['select', 'select*'], function($tag){
                $html = wpcf7_select_form_tag_handler($tag);
                $html = apply_filters('bc_cf7_field', $html, $tag, 'select', 'select', $html);
                return $html;
            }, [
        		'name-attr' => true,
                'selectable-values' => true,
        	]);
            wpcf7_add_form_tag(['submit'], function($tag){
                $html = wpcf7_submit_form_tag_handler($tag);
                $html = apply_filters('bc_cf7_field', $html, $tag, 'submit', 'submit', $html);
                return $html;
            });
            wpcf7_add_form_tag(['password', 'password*'], function($tag){
                $html = wpcf7_text_form_tag_handler($tag);
                $html = apply_filters('bc_cf7_field', $html, $tag, 'password', 'text', $html);
                return $html;
            }, [
        		'name-attr' => true,
        	]);
            wpcf7_add_form_tag(['tel', 'tel*'], function($tag){
                $html = wpcf7_text_form_tag_handler($tag);
                $html = apply_filters('bc_cf7_field', $html, $tag, 'tel', 'text', $html);
                return $html;
            }, [
        		'name-attr' => true,
        	]);
            wpcf7_add_form_tag(['text', 'text*'], function($tag){
                $html = wpcf7_text_form_tag_handler($tag);
                $html = apply_filters('bc_cf7_field', $html, $tag, 'text', 'text', $html);
                return $html;
            }, [
        		'name-attr' => true,
        	]);
            wpcf7_add_form_tag(['textarea', 'textarea*'], function($tag){
                $html = wpcf7_textarea_form_tag_handler($tag);
                $html = apply_filters('bc_cf7_field', $html, $tag, 'textarea', 'textarea', $html);
                return $html;
            }, [
        		'name-attr' => true,
        	]);
            wpcf7_add_form_tag(['url', 'url*'], function($tag){
                $html = wpcf7_text_form_tag_handler($tag);
                $html = apply_filters('bc_cf7_field', $html, $tag, 'url', 'text', $html);
                return $html;
            }, [
        		'name-attr' => true,
        	]);
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function wpcf7_password_validation_filter($result, $tag){
            $name = $tag->name;
			$value = isset($_POST[$name]) ? trim(wp_unslash(strtr((string) $_POST[$name], "\n", " "))) : '';
			if('password' === $tag->basetype){
				if($tag->is_required() and '' === $value){
					$result->invalidate($tag, wpcf7_get_message('invalid_required'));
				}
			}
			return wpcf7_text_validation_filter($result, $tag);
        }

    	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
}
