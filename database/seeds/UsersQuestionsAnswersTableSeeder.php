<?php

namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use App\User;
use App\Question;
use App\Answer;
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
      //  $users = User::factory()->count(3)->make();
        User::factory(3)->create()->each(function($u) {
            $u->questions()
              ->saveMany(
                Question::factory(rand(1, 5))->make()
              )
              ->each(function ($q) {
                $q->answers()->saveMany(Answer::factory(rand(1, 5))->make());
              });
        });  
    }
}
