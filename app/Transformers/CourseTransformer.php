<?php

namespace App\Transformers;

class CourseTransformer
{
    protected string $imageUrl = 'https://r4.wallpaperflare.com/wallpaper/801/330/425/laravel-php-code-simple-wallpaper-32119260dd86feebda789235a0c819b2.jpg';

    public function transformCourses($courses)
    {
        $courses->getCollection()->transform(function ($course) {
            return $this->transformCourse($course);
        });

        return $courses;
    }


    public function transformCourse($course)
    {
        $data                      = $course->only('id', 'title', 'slug', 'price', 'description');
        $data['category']          = $course->category->only('id', 'name');
        $data['url']               = $this->imageUrl;
        $data['short_description'] = $course->short_description;
        return $data;

    }
}