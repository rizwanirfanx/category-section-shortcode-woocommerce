<?php
/*
Plugin Name: Categories Section Shortcode
Description: This Plugin gives you a shortcode that will display Categories Section along with the number of products in each categoryj
Version: 1.2
Author: Omar
*/

function custom_hello_world_shortcode($atts)
{
	if (!isset($atts['parent-category'])) {
		return '<p>Please Select A Parent Category of Whom Child Category You want to Show</p>';
	}
	$parent_category_slug = $atts['parent-category'];
	$number_of_columns = 3;
	if (isset($atts['columns'])) {
		$number_of_columns = $atts['columns'];
	} 
	$parent_category = get_term_by('slug', $parent_category_slug, 'product_cat');

	if ($parent_category && !is_wp_error($parent_category)) {
		$parent_category_id = $parent_category->term_id;
	} else {
		'<p>Parent Category Doesn\'t exists</p>';
	}

	$child_categories = get_terms(array(
		'taxonomy' => 'product_cat', // WooCommerce product category taxonomy
		'order' => 'ASC',
		'child_of' => $parent_category->term_id,
		'hide_empty' => false, // Include categories even if they are empty
	));



	$output = '<div style="display: grid; grid-template-columns: repeat('.  $number_of_columns .  ',1fr); grid-gap: 10px;">';
	if (!empty($child_categories)) {
		foreach ($child_categories as $category) {
			$link = '';
			$link .= '/shop/?product_cat=';
			$link .= $parent_category_slug . '+' . $category->slug;

			$category_id = $category->term_id;
			$category_name = $category->name;
			$thumbnail_id = get_term_meta($category_id, 'thumbnail_id', true);

			$image = wp_get_attachment_url($thumbnail_id);
			$output .= '<div style="min-height: 350px;"><a href="' . $link . '">';



			if ($image) {
				$output .= '<img loading="lazy" style="height: 300px; width:100%; object-fit: cover;" src="' . esc_url($image) . '" alt="' . $category_name . ' Image">';
			} else {
				$output .= '<img loading="lazy" style="height: 300px;" src="https://i0.wp.com/thinkfirstcommunication.com/wp-content/uploads/2022/05/placeholder-1-1.png?fit=1200%2C800&ssl=1"/>';
			}
			$output .=  '<p style="text-align:center;margin-top :20px">'  . $category_name . ' (' . $category->count . ') ' .  '</p>';
			$output .= '</div>';
		}
		$output .= "</a></div>";
	} else {
		echo "No Results Found";
	}
	return $output;
}
add_shortcode('product-categories-tws', 'custom_hello_world_shortcode');
