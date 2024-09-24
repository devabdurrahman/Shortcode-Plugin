<?php
/**
* Plugin Name: Shortcode Plugin
* Description: This is our Second plugin, which gives idea about shortcode basics
* Author: Abdur Rahman
* Version: 1.0
* Author URI: https://github.com/devabdurrahman
* 
**/

// basic shortcode

add_shortcode( "message", "sp_show_static_message" );

function sp_show_static_message(){
	return "hello i am a simple shortoce message";
}

// paramitarized shortcode

add_shortcode( "student", "sp_student_data" );

function sp_student_data($attributes){
	$attributes = shortcode_atts( array(
		"name" => "default name",
		"email" => "default email"

	), $attributes, "student" );

	return "<h3>Student Data: Name - {$attributes['name']}, Email - {$attributes['email']}</h3>";
}

// shortcode with DB operations

add_shortcode("list-posts", "sp_hanlde_list_posts");

function sp_hanlde_list_posts(){
	global $wpdb;

	$table_prefix = $wpdb->prefix; // wp_
	$table_name = $table_prefix . "posts"; // wp_posts

	// Get post whose post_type = post_status and status = publish

	$posts = $wpdb->get_results(
		"SELECT post_title from {$table_name} WHERE post_type = 'post' AND post_status = 'publish' "
	);

	if(count($posts) > 0){
		$outputHtml = "<ul>";

		foreach($posts as $post){
			$outputHtml .= '<li>'.$post->post_title.'</li>';
		}

		$outputHtml .= "</ul>";

		return $outputHtml;
	}

	return "no post found";

}

// shortcode with WP_Query operations

add_shortcode("list-posts-query", "sp_hanlde_list_posts_wp_query");

function sp_hanlde_list_posts_wp_query($attributes){

	$attributes = shortcode_atts( array(
		"number" => 5
	), $attributes, "list-posts-query" );

	$query = new WP_Query(array(
		"posts_per_page" => $attributes['number'],
		"post_status" => "publish"
	));

	if($query->have_posts()){

		$outputHtml = "<ul>";

		while($query->have_posts()){
			$query->the_post();
			$outputHtml .= "<li><a href='".get_the_permalink()."'>".get_the_title()."</a></li>";
		}

		$outputHtml .= "</ul>";

		return $outputHtml;
	}

	return "no posts have found";
}
