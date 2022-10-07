<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Factory;

class UsersQuestionsAnswersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('answers')->delete();
        \DB::table('questions')->delete();
        \DB::table('users')->delete();
        //App\Models\Blog::factory()->count(3)->create()
        App\User::factory()->count(3)->create()->each(function($u) {
            $u->questions()
              ->saveMany(
                  factory(App\Question::class, rand(1, 5))->make()
              )
              ->each(function ($q) {
                $q->answers()->saveMany(factory(App\Answer::class, rand(1, 5))->make());
              });
        });  
    }
}
