<?php
header('Content-Type: application/json');

$notifications = [
  [
    "title" => "Registrar's Office",
    "description" => "Grade Reports for Semester 1 are now available.",
    "time" => "2 minutes ago",
    "bgColor" => "bg-indigo-600",
    "iconLetter" => "R"
  ],
  [
    "title" => "Library",
    "description" => "Reminder: Return borrowed books before May 20 to avoid late fees.",
    "time" => "10 minutes ago",
    "bgColor" => "bg-green-600",
    "iconLetter" => "L"
  ],
  [
    "title" => "Academic Affairs",
    "description" => "Final exam schedule has been posted.",
    "time" => "30 minutes ago",
    "bgColor" => "bg-yellow-500",
    "iconLetter" => "A"
  ],
  [
    "title" => "Class Coordinator",
    "description" => "IT Elective class moved to Room 304 starting next week.",
    "time" => "1 hour ago",
    "bgColor" => "bg-red-500",
    "iconLetter" => "C"
  ],
  [
    "title" => "Guidance Office",
    "description" => "Online counseling sessions open this week. Book your slot.",
    "time" => "Today",
    "bgColor" => "bg-blue-600",
    "iconLetter" => "G"
  ]
];

echo json_encode($notifications);
