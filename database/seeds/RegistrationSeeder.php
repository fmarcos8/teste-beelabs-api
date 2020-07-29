<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = DB::table('students')->get();
        $courses = DB::table('courses')->get();

        foreach ($students as $student) {
            $limit = rand(1, 5);
            $count = 1;
            foreach ($courses as $course) {
                $data = [
                    'student_id' => $student->id,
                    'course_id' => $course->id
                ];

                DB::table('registrations')->insert($data);

                if ($count === $limit) {
                    break;
                }

                $count++;
            }
        }
    }
}
