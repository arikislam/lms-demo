<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseCategory;
use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Database\Seeder;

class CoursesSeeder extends Seeder
{
    protected $faker;

    public function __construct()
    {
        $this->faker = $this->withFaker();
    }

    protected function withFaker()
    {
        return Container::getInstance()->make(Generator::class);
    }


    public function run()
    {
        $data = [];
        $count = 0;
        while($count<100) {
            $data[] = [
                'title'              => $this->faker->sentence,
                'description'        => $this->faker->paragraph,
                'course_category_id' => CourseCategory::inRandomOrder()->first()->id,
                'price'              => $this->faker->randomFloat(2, 100, 1000),
            ];
            $count++;
        }

        foreach ($data as $course) {
            Course::create($course);
        }

    }


}
