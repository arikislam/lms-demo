<?php

use App\Models\Course;
use App\Models\CourseCategory;

return [
    'product_model' => Course::class, //Product class
    'product_primary_key' => 'id', //primary key
    'product_display_column' => 'name', //Display the name
    'category_model' => CourseCategory::class, // Product Category model
    'category_primary_key' => 'id', //
    'category_display_column' => 'name',//
    'api_route_base_prefix' => 'api'
];