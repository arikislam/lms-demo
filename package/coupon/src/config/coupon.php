<?php
return [
    'product_model'           => '\App\Models\Course', //Product class
    'product_primary_key'     => 'id', //primary key
    'product_display_column'  => 'name', //Display the name
    'product_category_column' => 'course_category_id', //Display the name
    'product_price_column'    => 'price', //Display the name
    'category_model'          => '\App\Models\CourseCategory', // Product Category model
    'category_primary_key'    => 'id', // Product category primary key
    'category_display_column' => 'name',// Product category name or display column
    'api_route_base_prefix'   => 'api', // Set route prefixes
    'api_middlewares'         => ['auth:sanctum'], // Set middlewares
    'per_page_data'           => 10, //optional
];