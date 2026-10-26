<?php
declare(strict_types=1);

function students_index(): void
{
    $user = require_role('admin', 'teacher');
    $search = trim((string)($_GET['q'] ?? ''));
    $classId = isset($_GET['class']) && ctype_digit($_GET['class']) ? (int)$_GET['class'] : null;

    $sql = 'SELECT s.*, c.name class_name FROM students s LEFT JOIN classes c ON c.id=s.class_id WHERE s.school_id=?';
    $params = [$user['school_id']];
    if ($user['role'] === 'teacher') {
        $sql .= ' AND s.class_id IN (SELECT class_id FROM teacher_assignments WHERE teacher_id=?)';
        $params[] = $user['id'];
    }
    if ($search !== '') {
        $sql .= ' AND CONCAT(s.first_name, " ", s.last_name) LIKE ?';
        $params[] = '%' . $search . '%';
    }
    if ($classId) {
        $sql .= ' AND s.class_id=?';
        $params[] = $classId;
    }
    $sql .= ' ORDER BY c.name, s.first_name, s.last_name';

    view('students/index', [
        'title'    => t('nav.students'),
        'students' => q_all($sql, $params),
        'classes'  => visible_classes($user),
        'search'   => $search,
        'classId'  => $classId,
        'user'     => $user,
    ]);
}

function visible_classes(array $user): array
{
    if ($user['role'] === 'teacher') {
        return q_all('SELECT DISTINCT c.id, c.name FROM classes c JOIN teacher_assignments ta ON ta.class_id=c.id
                      WHERE ta.teacher_id=? ORDER BY c.name', [$user['id']]);
    }
    return q_all('SELECT id, name FROM classes WHERE school_id=? ORDER BY name', [$user['school_id']]);
}

function student_new(): void
{
    $user = require_role('admin');
    view('students/form', ['title' => t('students.new'), 'student' => null, 'classes' => visible_classes($user)]);
}

function validate_student_input(array $user): array
{
    $first = input('first_name');
    $last = input('last_name');
    $dob = input('date_of_birth');
    $classId = input_int('class_id');
    if ($first === '' || $last === '') {
        flash('error', t('error.required', ['field' => t('field.name')]));
        return [];
    }
    if ($dob !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob)) {
        flash('error', t('error.invalid_date'));
        return [];
    }
    if ($classId && !q_one('SELECT 1 FROM classes WHERE id=? AND school_id=?', [$classId, $user['school_id']])) {
        $classId = null;
    }
    return [
        'first_name' => $first, 'last_name' => $last, 'date_of_birth' => $dob ?: null,
        'class_id' => $classId, 'notes' => input('notes') ?: null,
    ];
}

function student_create(): void
{
    $user = require_role('admin');
    $d = validate_student_input($user);
    if (!$d) redirect('students/new');
    q('INSERT INTO students (school_id, class_id, first_name, last_name, date_of_birth, notes) VALUES (?,?,?,?,?,?)',
      [$user['school_id'], $d['class_id'], $d['first_name'], $d['last_name'], $d['date_of_birth'], $d['notes']]);
    $id = (int)db()->lastInsertId();
    q('INSERT INTO emergency_profiles (student_id, public_token) VALUES (?, ?)', [$id, random_token()]);
    flash('success', t('students.created'));
    redirect("students/{$id}/emergency");
}

function student_show(int $id): void
{
    $user = require_login();
    $student = authorize_student($user, $id);
    $profile = q_one('SELECT * FROM emergency_profiles WHERE student_id=?', [$id]);

    $grades = q_all('SELECT g.*, sub.name subject, u.name teacher FROM grades g JOIN subjects sub ON sub.id=g.subject_id
                     LEFT JOIN users u ON u.id=g.teacher_id WHERE g.student_id=? ORDER BY g.created_at DESC', [$id]);

    // Per-subject averages (percentage)
    $averages = q_all('SELECT sub.name subject, ROUND(AVG(g.score / g.max_score * 100), 1) pct, COUNT(*) n
                       FROM grades g JOIN subjects sub ON sub.id=g.subject_id WHERE g.student_id=?
                       GROUP BY sub.id, sub.name ORDER BY sub.name', [$id]);

    $insightSql = 'SELECT i.*, u.name reviewer FROM ai_insights i LEFT JOIN users u ON u.id=i.reviewed_by WHERE i.student_id=?';
    if ($user['role'] === 'parent') $insightSql .= " AND i.status='approved'";
    else $insightSql .= " AND i.status<>'rejected'";
    $insights = q_all($insightSql . ' ORDER BY i.created_at DESC', [$id]);

    $guardians = q_all('SELECT g.id, g.relation, g.is_primary, u.name, u.email, u.phone FROM guardians g
                        JOIN users u ON u.id=g.user_id WHERE g.student_id=? ORDER BY g.is_primary DESC, u.name', [$id]);

    $scans = can_edit_emergency($user, $student)
        ? q_all('SELECT * FROM scan_logs WHERE student_id=? ORDER BY created_at DESC LIMIT 20', [$id]) : [];

    $parents = $user['role'] === 'admin'
        ? q_all("SELECT id, name, email FROM users WHERE school_id=? AND role='parent' AND is_active=1 ORDER BY name", [$user['school_id']]) : [];

    view('students/show', [
        'title'     => $student['first_name'] . ' ' . $student['last_name'],
        'user'      => $user,
        'student'   => $student,
        'profile'   => $profile,
        'grades'    => $grades,
        'averages'  => $averages,
        'insights'  => $insights,
        'guardians' => $guardians,
        'scans'     => $scans,
        'parents'   => $parents,
        'subjects'  => gradable_subjects($user, $student),
        'canEmergency' => can_edit_emergency($user, $student),
        'canInsights'  => can_manage_insights($user, $student),
    ]);
}

function student_edit(int $id): void
{
    $user = require_role('admin');
    $student = authorize_student($user, $id);
    view('students/form', ['title' => t('students.edit'), 'student' => $student, 'classes' => visible_classes($user)]);
}

function student_update(int $id): void
{
    $user = require_role('admin');
    authorize_student($user, $id);
    $d = validate_student_input($user);
    if (!$d) redirect("students/{$id}/edit");
    q('UPDATE students SET class_id=?, first_name=?, last_name=?, date_of_birth=?, notes=? WHERE id=?',
      [$d['class_id'], $d['first_name'], $d['last_name'], $d['date_of_birth'], $d['notes'], $id]);
    flash('success', t('saved'));
    redirect("students/{$id}");
}

function student_delete(int $id): void
{
    $user = require_role('admin');
    authorize_student($user, $id);
    q('DELETE FROM students WHERE id=?', [$id]);
    flash('success', t('students.deleted'));
    redirect('students');
}

// ---------- Emergency profile (Layer 1) ----------

function emergency_edit(int $id): void
{
    $user = require_login();
    $student = load_student_or_404($id);
    if (!can_edit_emergency($user, $student)) abort(403);
    $profile = q_one('SELECT * FROM emergency_profiles WHERE student_id=?', [$id]);
    view('students/emergency', ['title' => t('emergency.edit_title'), 'student' => $student, 'profile' => $profile, 'user' => $user]);
}

function emergency_update(int $id): void
{
    $user = require_login();
    $student = load_student_or_404($id);
    if (!can_edit_emergency($user, $student)) abort(403);

    $blood = input('blood_type');
    if (!in_array($blood, BLOOD_TYPES, true)) $blood = null;
    $flags = [];
    foreach (['show_age', 'show_blood_type', 'show_allergies', 'show_conditions', 'show_medications', 'show_care_notes', 'is_enabled'] as $f) {
        $flags[$f] = isset($_POST[$f]) ? 1 : 0;
    }
    $existing = q_one('SELECT consent_at FROM emergency_profiles WHERE student_id=?', [$id]);
    $consentAt = $existing['consent_at'] ?? null;
    if ($user['role'] === 'parent' && isset($_POST['consent']) && !$consentAt) {
        $consentAt = date('Y-m-d H:i:s');
    }

    q('UPDATE emergency_profiles SET blood_type=?, allergies=?, conditions=?, medications=?, care_notes=?,
       show_age=?, show_blood_type=?, show_allergies=?, show_conditions=?, show_medications=?, show_care_notes=?,
       is_enabled=?, consent_at=? WHERE student_id=?', [
        $blood,
        json_encode(parse_allergies(input('allergies')), JSON_UNESCAPED_UNICODE),
        input('conditions') ?: null,
        input('medications') ?: null,
        input('care_notes') ?: null,
        $flags['show_age'], $flags['show_blood_type'], $flags['show_allergies'], $flags['show_conditions'],
        $flags['show_medications'], $flags['show_care_notes'], $flags['is_enabled'], $consentAt, $id,
    ]);
    flash('success', t('saved'));
    redirect("students/{$id}");
}

function emergency_regenerate_token(int $id): void
{
    $user = require_login();
    $student = load_student_or_404($id);
    if (!can_edit_emergency($user, $student)) abort(403);
    q('UPDATE emergency_profiles SET public_token=? WHERE student_id=?', [random_token(), $id]);
    flash('success', t('emergency.token_regenerated'));
    redirect("students/{$id}");
}

function student_card(int $id): void
{
    $user = require_login();
    $student = load_student_or_404($id);
    if (!can_edit_emergency($user, $student)) abort(403);
    $profile = q_one('SELECT * FROM emergency_profiles WHERE student_id=?', [$id]);
    $link = public_link($profile['public_token']);
    view('students/card', ['title' => t('card.title'), 'student' => $student, 'link' => $link], 'layout_plain');
}

// ---------- Guardians ----------

function guardian_add(int $id): void
{
    $user = require_role('admin');
    authorize_student($user, $id);
    $parentId = input_int('user_id');
    $parent = $parentId ? q_one("SELECT id FROM users WHERE id=? AND school_id=? AND role='parent'", [$parentId, $user['school_id']]) : null;
    if (!$parent) {
        flash('error', t('error.invalid'));
        redirect("students/{$id}");
    }
    $primary = isset($_POST['is_primary']) ? 1 : 0;
    if ($primary) q('UPDATE guardians SET is_primary=0 WHERE student_id=?', [$id]);
    q('INSERT IGNORE INTO guardians (student_id, user_id, relation, is_primary) VALUES (?,?,?,?)',
      [$id, $parentId, input('relation') ?: null, $primary]);
    flash('success', t('saved'));
    redirect("students/{$id}");
}

function guardian_remove(int $id, int $guardianId): void
{
    $user = require_role('admin');
    authorize_student($user, $id);
    q('DELETE FROM guardians WHERE id=? AND student_id=?', [$guardianId, $id]);
    flash('success', t('saved'));
    redirect("students/{$id}");
}

// ---------- Grades ----------

function grade_create(int $id): void
{
    $user = require_role('admin', 'teacher');
    $student = authorize_student($user, $id);
    $subjectId = input_int('subject_id');
    $allowed = array_column(gradable_subjects($user, $student), 'id');
    if (!$subjectId || !in_array($subjectId, array_map('intval', $allowed), true)) abort(403);

    $score = filter_var(input('score'), FILTER_VALIDATE_FLOAT);
    $max = filter_var(input('max_score', '100'), FILTER_VALIDATE_FLOAT);
    $term = input('term');
    $assessment = input('assessment');
    if ($score === false || $max === false || $max <= 0 || $score < 0 || $score > $max || $term === '' || $assessment === '') {
        flash('error', t('grades.invalid'));
        redirect("students/{$id}#grades");
    }
    q('INSERT INTO grades (student_id, subject_id, teacher_id, term, assessment, score, max_score) VALUES (?,?,?,?,?,?,?)',
      [$id, $subjectId, $user['id'], $term, $assessment, $score, $max]);
    flash('success', t('grades.added'));
    redirect("students/{$id}#grades");
}

function grade_delete(int $gradeId): void
{
    $user = require_role('admin', 'teacher');
    $grade = q_one('SELECT * FROM grades WHERE id=?', [$gradeId]);
    if (!$grade) abort(404);
    $student = authorize_student($user, (int)$grade['student_id']);
    $allowed = array_map('intval', array_column(gradable_subjects($user, $student), 'id'));
    if (!in_array((int)$grade['subject_id'], $allowed, true)) abort(403);
    q('DELETE FROM grades WHERE id=?', [$gradeId]);
    flash('success', t('grades.deleted'));
    redirect("students/{$student['id']}#grades");
}

// ---------- AI insights ----------

function insight_generate(int $id): void
{
    $user = require_role('admin', 'teacher');
    $student = authorize_student($user, $id);
    if (!can_manage_insights($user, $student)) abort(403);
    if (!ai_enabled()) {
        flash('error', t('ai.disabled'));
        redirect("students/{$id}#insights");
    }
    if (!rate_limit('ai:' . $user['id'], 20, 3600)) {
        flash('error', t('ai.too_many'));
        redirect("students/{$id}#insights");
    }
    try {
        $text = generate_insight($student, current_lang());
        q('INSERT INTO ai_insights (student_id, requested_by, content, lang) VALUES (?,?,?,?)',
          [$id, $user['id'], $text, current_lang()]);
        flash('success', t('ai.generated'));
    } catch (RuntimeException $ex) {
        flash('error', $ex->getMessage());
    }
    redirect("students/{$id}#insights");
}

function insight_review(int $insightId): void
{
    $user = require_role('admin', 'teacher');
    $insight = q_one('SELECT * FROM ai_insights WHERE id=?', [$insightId]);
    if (!$insight) abort(404);
    $student = authorize_student($user, (int)$insight['student_id']);
    if (!can_manage_insights($user, $student)) abort(403);

    $decision = input('decision');
    if (!in_array($decision, ['approved', 'rejected'], true)) abort(400);
    $content = input('content');
    if ($content === '') $content = $insight['content'];
    q('UPDATE ai_insights SET status=?, content=?, reviewed_by=?, reviewed_at=NOW() WHERE id=?',
      [$decision, $content, $user['id'], $insightId]);

    if ($decision === 'approved') {
        foreach (q_all('SELECT user_id FROM guardians WHERE student_id=?', [$student['id']]) as $g) {
            notify((int)$g['user_id'], t('notify.insight', ['name' => $student['first_name']]));
        }
    }
    flash('success', t('ai.reviewed'));
    redirect("students/{$student['id']}#insights");
}
