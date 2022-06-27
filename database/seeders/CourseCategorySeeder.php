<?php

namespace Database\Seeders;

use App\Models\CourseCategory;
use Illuminate\Database\Seeder;

class CourseCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Web Development',
            'Android App Development',
            'IOS App Development',
            'Artificial Intelligence',
            'Data Structure',
            'Algorithm',
            'Digital Marketing',
            'Devops',
            'Project Management',
            'MySQL Database',
            'Oracle Database',
        ];

        foreach ($categories as $category) {
            CourseCategory::create(['name' => $category]);
        }

    }
}
