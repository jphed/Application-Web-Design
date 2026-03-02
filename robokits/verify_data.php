<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VERIFICACIÓN DE DATOS ===\n\n";

// Verificar Users
echo "USERS:\n";
$users = App\Models\User::all();
foreach ($users as $user) {
    echo "- {$user->name} | {$user->email} | {$user->role}\n";
}
echo "Total users: " . $users->count() . "\n\n";

// Verificar Robotics Kits
echo "ROBOTICS KITS:\n";
$kits = App\Models\RoboticsKit::all();
foreach ($kits as $kit) {
    echo "- {$kit->name} | \${$kit->price} | Stock: {$kit->stock}\n";
}
echo "Total kits: " . $kits->count() . "\n\n";

// Verificar Groups
echo "GROUPS:\n";
$groups = App\Models\Group::all();
foreach ($groups as $group) {
    echo "- {$group->name}\n";
}
echo "Total groups: " . $groups->count() . "\n\n";

// Verificar Courses
echo "COURSES:\n";
$courses = App\Models\Course::with('roboticsKit')->get();
foreach ($courses as $course) {
    echo "- {$course->title} | Kit: {$course->roboticsKit->name}\n";
}
echo "Total courses: " . $courses->count() . "\n\n";

// Verificar relaciones Course-Group
echo "COURSE-GROUP RELATIONSHIPS:\n";
foreach ($courses as $course) {
    $groupNames = $course->groups->pluck('name')->implode(', ');
    echo "- {$course->title} -> Groups: {$groupNames}\n";
}

echo "\n=== VERIFICACIÓN COMPLETADA ===\n";
