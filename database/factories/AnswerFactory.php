<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\User;

class AnswerFactory extends Factory
{
    
    public function definition()
    {
      //  $factory->define(App\Answer::class, function (Faker $faker) {
            return [
                'body' => $this->faker->paragraphs(rand(3, 7), true),
                'user_id' =>  User::pluck('id')->random(),
                // 'votes_count' => rand(0, 5),
            ];
      //  });
    }
}
