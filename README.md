# WordPress-Custom-URL-with-template-part
```php

<?php
/**
 * Plugin Name: Ade WP Custom Page Builder
 * Plugin URI: https://www.adeleyeayodeji.com/
 * Author: Adeleye Ayodeji
 * Author URI: https://www.adeleyeayodeji.com
 * Description: This plugin allows you to create custom pages with a custom page builder.
 * Version: 0.1.0
 * License: 0.1.0
 * License URL: http://www.gnu.org/licenses/gpl-2.0.txt
 * text-domain: ade-wp-custom-page-builder
*/

//add security
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( ! defined( 'ADE_WP_CUSTOM_PAGE_BUILDER' ) ) {
	define( 'ADE_WP_CUSTOM_PAGE_BUILDER', __FILE__ );
}

 class AdeWPCustomPageBuilder
 {
	 function init(){
		add_action('generate_rewrite_rules', array($this, 'myRewrite'));
        add_action( 'init', function () {
            flush_rewrite_rules( true );
        }, 9999 );
		add_filter('query_vars', array($this, 'myRegisterQueryVars'));
		add_action('template_redirect', array($this, 'myThemeRedirect'));
	 }

	 /**
	 * URL Rewrites
	 */
	public function myRewrite()
	{
		/** @global WP_Rewrite $wp_rewrite */
		global $wp_rewrite;
		
		$newRules = array(
			'posts/?$' => 'index.php?ade_page=posts',
			'post/(\d+)/([a-zA-Z0-9-]+)/?$' => sprintf('index.php?ade_page=post&post_id=%s&post_name=%s', $wp_rewrite->preg_index(1), $wp_rewrite->preg_index(2),
			),
		);
	
		$wp_rewrite->rules = $newRules + (array) $wp_rewrite->rules;
	}

	/**	
	 * Set templates for custom pages
	 *
	 * @see http://stackoverflow.com/questions/4647604/wp-use-file-in-plugin-directory-as-custom-page-template
	 */
	public function myThemeRedirect()
	{
		$page = get_query_var('ade_page');
		$post_id = (int)get_query_var('post_id', 0);
        $post_name = get_query_var('post_name');
		if ($page == 'posts' && empty($post_id)) {
	
			// Pet Archive
			$data = array( // Data you can pass to the template
				'posts' => get_posts(array(
                    'post_type' => 'post',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                )),
				'action' => 'my_action'
			);
			$fullTemplatePath = plugin_dir_path(ADE_WP_CUSTOM_PAGE_BUILDER) . 'templates/archive-posts.php';
			$this->doMyThemeRedirect($fullTemplatePath, true, $data);
			return;
	
		} elseif ($page == 'post' && !empty($post_id)) {
	
			// Single Pet
			$data = array( // Data you can pass to the template
				'post' => get_post($post_id),
                'title' => $post_name,
                'action' => 'my_action'
            );
            $fullTemplatePath = plugin_dir_path(ADE_WP_CUSTOM_PAGE_BUILDER) . 'templates/single-post.php';
			$this->doMyThemeRedirect($fullTemplatePath, true, $data);
			return;
	
		}
	}
	
	/**
	 * Process theme redirect
	 *
	 * @param mixed $path
	 * @param bool $force force redirect regardless of have_posts()
	 * @param array $data vars to set for theme
	 */
	function doMyThemeRedirect($path, $force=false, $data=array())
	{
		global $post, $wp_query;
	
		if (have_posts() || $force) {
			if (!empty($data)) extract($data);
			include($path);
			die();
		} else {
			$wp_query->is_404 = true;
		}
	}

	/**
	 * Register custom query vars
	 *
	 * @param array $vars The array of available query variables
	 *
	 * @return array
	 *
	 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/query_vars
	 */
	public function myRegisterQueryVars($vars)
	{
		$vars[] = 'ade_page';
		$vars[] = 'post_id';
		$vars[] = 'post_name';
		return $vars;
	}
	
 }

 //init
 $customPageData = new AdeWPCustomPageBuilder();
 $customPageData->init();

```
