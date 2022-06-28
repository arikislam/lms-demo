<?php

use App\Models\Course;
use App\Models\CourseCategory;

return [
    'product_model'           => Course::class, //Product class
    'product_primary_key'     => 'id', //primary key
    'product_display_column'  => 'name', //Display the name
    'product_category_column' => 'course_category_id', //Display the name
    'product_price_column'    => 'course_price_id', //Display the name
    'category_model'          => CourseCategory::class, // Product Category model
    'category_primary_key'    => 'id', //
    'category_display_column' => 'name',//
    'api_route_base_prefix'   => 'api',
    'api_middlewares'         => ['auth:sanctum'],
    'per_page_data'           => 10,
];