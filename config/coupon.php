<?php

use App\Models\Course;
use App\Models\CourseCategory;

return [
    'product_model' => Course::class, //Product class
    'product_primary_key' => 'id', //primary key
    'category_model' => CourseCategory::class, // Product Category model
    'category_primary_key' => 'id' //
];