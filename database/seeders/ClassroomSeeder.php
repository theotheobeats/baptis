<?php

namespace Database\Seeders;

use App\Models\Classroom;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tk_a_classes = [1, 2];
        foreach ($tk_a_classes as $class) {
            Classroom::create([
                'school_group' => 'TK',
                'grade' => "A",
                'name' => "A" . $class,
                'note' => '-'
            ]);
        }

        $tk_b_classes = [1, 2, 3, 4];
        foreach ($tk_b_classes as $class) {
            Classroom::create([
                'school_group' => 'TK',
                'grade' => "B",
                'name' => "B" . $class,
                'note' => '-'
            ]);
        }

        $sd_classes = [1, 2, 3, 4, 5, 6];
        $sd_class_codes = ['A', 'B', 'C', 'D', 'E'];
        foreach ($sd_classes as $class) {
            foreach ($sd_class_codes as $code) {
                Classroom::create([
                    'school_group' => 'SD',
                    'grade' => $class,
                    'name' => $class . $code,
                    'note' => '-'
                ]);
            }
        }

        // Untuk kelas 6F
        Classroom::create([
            'school_group' => 'SD',
            'grade' => 6,
            'name' => "" . "6F",
            'note' => '-'
        ]);

        $smp_classes = [7, 8, 9];
        foreach ($smp_classes as $class) {
            Classroom::create([
                'school_group' => 'SMP',
                'grade' => $class,
                'name' => "" . $class,
                'note' => '-'
            ]);
        }


        Classroom::create([
            'school_group' => 'TK',
            'grade' => "A",
            'name' => "A",
            'note' => '-'
        ]);

        Classroom::create([
            'school_group' => 'TK',
            'grade' => "B",
            'name' => "B",
            'note' => '-'
        ]);

        foreach ($sd_classes as $class) {
            Classroom::create([
                'school_group' => 'SD',
                'grade' => $class,
                'name' => $class,
                'note' => '-'
            ]);
        }
        

    }
}
