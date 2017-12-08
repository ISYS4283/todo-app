<?php

namespace App;

use razorbacks\blackboard\rest\Api;

class Blackboard
{
    public function postGradeForStudent(User $user)
    {
        $server = config('blackboard.server');
        $applicationId = config('blackboard.applicationId');
        $secret = config('blackboard.secret');
        $blackboard = new Api($server, $applicationId, $secret);

        $course = config('blackboard.course');
        $column = config('blackboard.column');
        $points = config('blackboard.points');
        $username = $user->getUsername();

        $endpoint = "/courses/$course/gradebook/columns/$column/users/userName:$username";
        $blackboard->patch($endpoint, [
            'score' => $points,
        ]);
    }
}
