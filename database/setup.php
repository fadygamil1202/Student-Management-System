<?php
/**
 * One-time setup from the command line:
 *   php database/setup.php --school="My School" --name="Admin Name" --email=admin@school.eg --password=StrongPass123 [--demo]
 * Creates the tables, the school and its first admin. --demo adds sample
 * classes, a teacher, a parent and a student so you can try every role.
 */
declare(strict_types=1);
if (PHP_SAPI !== 'cli') exit("Run from the command line.\n");

require dirname(__DIR__) . '/src/bootstrap.php';

$opts = getopt('', ['school:', 'name:', 'email:', 'password:', 'demo', 'skip-schema']);
foreach (['school', 'name', 'email', 'password'] as $k) {
    if (empty($opts[$k])) exit("Missing --{$k}\nUsage: php database/setup.php --school=\"My School\" --name=\"Admin\" --email=a@b.c --password=secret123 [--demo]\n");
}
if (strlen($opts['password']) < 8) exit("Password must be at least 8 characters.\n");

if (!isset($opts['skip-schema'])) {
    $sql = file_get_contents(__DIR__ . '/schema.sql');
    foreach (explode(";\n", $sql) as $stmt) {
        $code = trim(preg_replace('/^\s*--.*$/m', '', $stmt));
        if ($code !== '') db()->exec($code);
    }
    echo "Tables created.\n";
}

q('INSERT INTO schools (name) VALUES (?)', [$opts['school']]);
$schoolId = (int)db()->lastInsertId();
$hash = fn(string $p) => password_hash($p, PASSWORD_DEFAULT);
q("INSERT INTO users (school_id, role, name, email, password_hash) VALUES (?, 'admin', ?, ?, ?)",
  [$schoolId, $opts['name'], strtolower($opts['email']), $hash($opts['password'])]);
echo "School and admin created. Sign in with {$opts['email']}.\n";

if (!isset($opts['demo'])) exit(0);

// ---------- Demo data ----------
q('INSERT INTO classes (school_id, name) VALUES (?, ?)', [$schoolId, 'Grade 4-A']);
$classId = (int)db()->lastInsertId();
$subjects = [];
foreach (['Mathematics', 'Science', 'Arabic', 'English'] as $s) {
    q('INSERT INTO subjects (school_id, name) VALUES (?, ?)', [$schoolId, $s]);
    $subjects[$s] = (int)db()->lastInsertId();
}
q("INSERT INTO users (school_id, role, name, email, phone, password_hash) VALUES (?, 'teacher', 'Mona Teacher', 'teacher@demo.test', NULL, ?)",
  [$schoolId, $hash('demo12345')]);
$teacherId = (int)db()->lastInsertId();
q("INSERT INTO users (school_id, role, name, email, phone, password_hash) VALUES (?, 'parent', 'Ahmed Parent', 'parent@demo.test', '+201000000000', ?)",
  [$schoolId, $hash('demo12345')]);
$parentId = (int)db()->lastInsertId();
foreach (['Mathematics', 'Science'] as $s) {
    q('INSERT INTO teacher_assignments (teacher_id, class_id, subject_id) VALUES (?,?,?)', [$teacherId, $classId, $subjects[$s]]);
}

q('INSERT INTO students (school_id, class_id, first_name, last_name, date_of_birth) VALUES (?,?,?,?,?)',
  [$schoolId, $classId, 'Omar', 'Demo', date('Y-m-d', strtotime('-9 years -3 months'))]);
$studentId = (int)db()->lastInsertId();
$token = random_token();
q('INSERT INTO emergency_profiles (student_id, public_token, blood_type, allergies, conditions, medications, care_notes, consent_at)
   VALUES (?,?,?,?,?,?,?,NOW())', [
    $studentId, $token, 'O+',
    json_encode([['name' => 'Peanuts', 'severity' => 'severe'], ['name' => 'Penicillin', 'severity' => 'moderate']]),
    'Asthma — inhaler in school bag', 'Salbutamol inhaler as needed', 'EpiPen in the front pocket of the school bag',
]);
q("INSERT INTO guardians (student_id, user_id, relation, is_primary) VALUES (?, ?, 'Father', 1)", [$studentId, $parentId]);

$grades = [
    ['Mathematics', 'Term 1', 'Quiz 1', 72], ['Mathematics', 'Term 1', 'Midterm', 78], ['Mathematics', 'Term 1', 'Quiz 2', 85],
    ['Science', 'Term 1', 'Quiz 1', 90], ['Science', 'Term 1', 'Midterm', 64], ['Science', 'Term 1', 'Lab report', 70],
];
foreach ($grades as [$sub, $term, $as, $score]) {
    q('INSERT INTO grades (student_id, subject_id, teacher_id, term, assessment, score) VALUES (?,?,?,?,?,?)',
      [$studentId, $subjects[$sub], $teacherId, $term, $as, $score]);
}

echo "Demo data added:\n  teacher@demo.test / demo12345\n  parent@demo.test  / demo12345\n";
echo "  Public emergency page: <your site address>/e/{$token}\n";
