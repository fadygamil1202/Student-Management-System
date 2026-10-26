<?php
declare(strict_types=1);

/*
 * Access rules (Layer 2):
 *  - admin:   any student in their own school
 *  - teacher: students in a class they are assigned to
 *  - parent:  only students they are a guardian of
 * Layer 1 (public page) is handled separately via the random token.
 */

function find_student(int $id): ?array
{
    return q_one('SELECT s.*, c.name AS class_name FROM students s
                  LEFT JOIN classes c ON c.id = s.class_id WHERE s.id = ?', [$id]);
}

function is_guardian(array $user, int $studentId): bool
{
    return (bool)q_one('SELECT 1 FROM guardians WHERE user_id = ? AND student_id = ?', [$user['id'], $studentId]);
}

function teaches_class(array $user, ?int $classId): bool
{
    if (!$classId) return false;
    return (bool)q_one('SELECT 1 FROM teacher_assignments WHERE teacher_id = ? AND class_id = ?', [$user['id'], $classId]);
}

function can_view_student(array $user, array $student): bool
{
    if ((int)$user['school_id'] !== (int)$student['school_id']) return false;
    return match ($user['role']) {
        'admin'   => true,
        'teacher' => teaches_class($user, $student['class_id'] ? (int)$student['class_id'] : null),
        'parent'  => is_guardian($user, (int)$student['id']),
        default   => false,
    };
}

/** Emergency (Layer 1) data and visibility: admins and the student's guardians. */
function can_edit_emergency(array $user, array $student): bool
{
    if ((int)$user['school_id'] !== (int)$student['school_id']) return false;
    return $user['role'] === 'admin' || ($user['role'] === 'parent' && is_guardian($user, (int)$student['id']));
}

/** Subjects this user may grade for the student (admin: all school subjects). */
function gradable_subjects(array $user, array $student): array
{
    if ((int)$user['school_id'] !== (int)$student['school_id']) return [];
    if ($user['role'] === 'admin') {
        return q_all('SELECT id, name FROM subjects WHERE school_id = ? ORDER BY name', [$student['school_id']]);
    }
    if ($user['role'] === 'teacher' && $student['class_id']) {
        return q_all('SELECT sub.id, sub.name FROM teacher_assignments ta
                      JOIN subjects sub ON sub.id = ta.subject_id
                      WHERE ta.teacher_id = ? AND ta.class_id = ? ORDER BY sub.name',
                     [$user['id'], $student['class_id']]);
    }
    return [];
}

/** Teachers of the class and admins can generate and review AI insights. */
function can_manage_insights(array $user, array $student): bool
{
    return in_array($user['role'], ['admin', 'teacher'], true) && can_view_student($user, $student);
}

function load_student_or_404(int $id): array
{
    $student = find_student($id);
    if (!$student) abort(404);
    return $student;
}

function authorize_student(array $user, int $id): array
{
    $student = load_student_or_404($id);
    if (!can_view_student($user, $student)) abort(403);
    return $student;
}
