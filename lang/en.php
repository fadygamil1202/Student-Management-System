<?php
return [
    'app.name' => 'School Student Profiles',
    'save' => 'Save', 'cancel' => 'Cancel', 'add' => 'Add', 'remove' => 'Remove', 'delete' => 'Delete',
    'filter' => 'Filter', 'saved' => 'Saved.',

    'nav.dashboard' => 'Dashboard', 'nav.students' => 'Students', 'nav.classes' => 'Classes & subjects',
    'nav.users' => 'Users', 'nav.notifications' => 'Notifications', 'nav.account' => 'My account', 'nav.logout' => 'Log out',

    'role.admin' => 'School admin', 'role.teacher' => 'Teacher', 'role.parent' => 'Parent',
    'role.teacher_pl' => 'Teachers', 'role.parent_pl' => 'Parents',

    'login.title' => 'Sign in', 'login.subtitle' => 'For school staff and parents.',
    'login.submit' => 'Sign in', 'login.invalid' => 'Wrong email or password.',
    'login.too_many' => 'Too many attempts. Please wait 15 minutes and try again.',

    'error.403' => 'You don\'t have access to this page.', 'error.404' => 'Page not found.',
    'error.429' => 'Too many requests. Please wait a minute.', 'error.400' => 'Bad request.',
    'error.csrf' => 'Your session expired. Go back, refresh the page and try again.',
    'error.home' => 'Go to the home page', 'error.required' => ':field is required.',
    'error.invalid' => 'Please check the form and try again.', 'error.invalid_date' => 'Invalid date.',

    'field.name' => 'Name', 'field.first_name' => 'First name', 'field.last_name' => 'Last name',
    'field.email' => 'Email', 'field.password' => 'Password', 'field.phone' => 'Phone', 'field.role' => 'Role',
    'field.dob' => 'Date of birth', 'field.class' => 'Class', 'field.age' => 'Age', 'field.age_n' => 'Age :n',
    'field.student' => 'Student', 'field.subject' => 'Subject', 'field.term' => 'Term',
    'field.assessment' => 'Assessment', 'field.score' => 'Score', 'field.max_score' => 'Out of',
    'field.blood_type' => 'Blood type', 'field.allergies' => 'Allergies', 'field.conditions' => 'Medical conditions',
    'field.medications' => 'Medications', 'field.care_notes' => 'Emergency care notes',
    'field.private_notes' => 'Private staff notes',

    'severity.mild' => 'Mild', 'severity.moderate' => 'Moderate', 'severity.severe' => 'Severe',

    'dash.welcome' => 'Welcome, :name', 'dash.scans_week' => 'Card scans (7 days)',
    'dash.recent_scans' => 'Recent card scans', 'dash.no_scans' => 'No scans yet.',
    'dash.my_classes' => 'My classes', 'dash.no_classes' => 'You have no classes yet. Ask a school admin to assign you.',
    'dash.n_students' => ':n students', 'dash.pending_insights' => ':n AI insights are waiting for your review.',
    'dash.my_children' => 'My children', 'dash.no_children' => 'No children are linked to your account yet. Contact the school.',
    'dash.view_profile' => 'View profile', 'dash.unread' => 'You have :n new notifications.',

    'students.new' => 'Add student', 'students.edit' => 'Edit student', 'students.search' => 'Search by name',
    'students.all_classes' => 'All classes', 'students.none' => 'No students found.',
    'students.created' => 'Student added. Now fill in the emergency information.',
    'students.deleted' => 'Student deleted.', 'students.delete' => 'Delete student',
    'students.delete_confirm' => 'Delete this student and all their data? This cannot be undone.',
    'students.notes_hint' => 'Only school staff can see these notes.', 'students.no_class' => 'No class',

    'layer1.title' => 'Emergency info (public card)', 'layer1.public_on' => 'Card active', 'layer1.public_off' => 'Card disabled',
    'layer1.preview' => 'Preview public page', 'layer1.regen' => 'Lost card? New link',
    'layer1.regen_confirm' => 'This disables the old card and link immediately. You will need to print or write a new card. Continue?',
    'layer1.no_consent' => 'A parent has not yet given consent for the public emergency page.',

    'emergency.edit_title' => 'Emergency info', 'emergency.token_regenerated' => 'New link created. The old card no longer works.',
    'emergency.intro' => 'This is what anyone who taps the card will see. Keep it to what a first responder needs.',
    'emergency.allergies_ph' => "Peanuts|severe\nPenicillin|moderate",
    'emergency.allergies_hint' => 'One per line: name|severity (mild, moderate or severe).',
    'emergency.conditions_ph' => 'e.g. Asthma — inhaler in school bag', 'emergency.medications_ph' => 'e.g. Salbutamol inhaler as needed',
    'emergency.care_ph' => 'e.g. EpiPen in the front pocket of the school bag',
    'emergency.visible_title' => 'Show on the public page', 'emergency.visible_hint' => 'Unticked items stay private. The full name, class, school and phone numbers are never shown.',
    'emergency.enabled' => 'Card is active (untick to disable the public page)',
    'emergency.consent' => 'I am the student\'s guardian and I agree to show the ticked information on the public emergency page.',
    'emergency.consented_at' => 'Guardian consent given on :date.',

    'card.title' => 'Print card', 'card.print' => 'Print card / QR', 'card.print_now' => 'Print',
    'card.help_nfc' => 'Print this card, and write the link below to the card\'s NFC chip (NTAG213/215) using any NFC writer app.',
    'card.heading' => 'Emergency medical info', 'card.scan_text' => 'Tap with a phone or scan the code',
    'card.qr_label' => 'QR code linking to the emergency page',

    'grades.title' => 'Grades', 'grades.none' => 'No grades yet.', 'grades.add' => 'Add grade',
    'grades.added' => 'Grade added.', 'grades.deleted' => 'Grade deleted.', 'grades.delete_confirm' => 'Delete this grade?',
    'grades.invalid' => 'Check the grade: the score must be between 0 and the maximum, and all fields are required.',
    'grades.term_ph' => 'e.g. Term 1 2026', 'grades.assessment_ph' => 'e.g. Midterm exam',

    'ai.title' => 'AI insights', 'ai.generate' => 'Generate insight', 'ai.none' => 'No insights yet.',
    'ai.disabled' => 'AI insights are turned off. Add a Claude API key in config.php to enable them.',
    'ai.privacy_note' => 'Only anonymous grades and age are sent to the AI — no name, health or contact data. Insights stay drafts until a teacher approves them; parents see approved ones only.',
    'ai.no_grades' => 'Add some grades first.', 'ai.failed' => 'The AI request failed.',
    'ai.too_many' => 'Too many AI requests this hour. Try again later.',
    'ai.generated' => 'Draft insight created. Review it before parents can see it.',
    'ai.reviewed' => 'Insight reviewed.', 'ai.approve' => 'Approve & share with parents', 'ai.reject' => 'Reject',
    'ai.edit_before' => 'You can edit the text before approving.',
    'ai.status_draft' => 'Draft — needs review', 'ai.status_approved' => 'Approved', 'ai.status_rejected' => 'Rejected',
    'ai.reviewed_by' => 'reviewed by :name',

    'guardians.title' => 'Guardians', 'guardians.none' => 'No guardians linked.', 'guardians.primary' => 'Primary contact',
    'guardians.link_parent' => 'Link a parent account', 'guardians.relation' => 'Relation',
    'guardians.relation_ph' => 'e.g. Mother', 'guardians.create_first' => 'Create a parent account in Users first.',

    'scan.title' => 'Card scan log', 'scan.action' => 'Action', 'scan.time' => 'Time',
    'scan.view' => 'Page opened', 'scan.call_parent' => 'Called parent',

    'notify.scan' => ':name\'s emergency card was opened at :time.',
    'notify.call' => 'Someone tapped "Call parent" on :name\'s emergency page at :time.',
    'notify.insight' => 'A new teacher-approved insight is available for :name.',
    'notify.none' => 'No notifications.',

    'classes.classes' => 'Classes', 'classes.subjects' => 'Subjects', 'classes.assignments' => 'Teacher assignments',
    'classes.assign_hint' => 'A teacher can see and grade only the students in classes assigned to them.',
    'classes.assign' => 'Assign', 'classes.class_ph' => 'e.g. Grade 4-A', 'classes.subject_ph' => 'e.g. Mathematics',
    'classes.new_class' => 'New class name', 'classes.new_subject' => 'New subject name',

    'users.new' => 'Add user', 'users.create' => 'Create account', 'users.created' => 'Account created.',
    'users.email_taken' => 'This email is already used.', 'users.temp_password' => 'Temporary password',
    'users.temp_hint' => 'At least 8 characters. Ask the person to change it in "My account".',
    'users.activate' => 'Activate', 'users.deactivate' => 'Deactivate', 'users.cannot_self' => 'You cannot deactivate yourself.',

    'account.change_password' => 'Change password', 'account.current_password' => 'Current password',
    'account.new_password' => 'New password (leave empty to keep)', 'account.wrong_password' => 'Current password is wrong.',
    'account.password_short' => 'Password must be at least 8 characters.',
    'account.phone_hint' => 'Used by the "Call parent" button on your child\'s emergency page. It is never shown on the page.',

    'public.title' => 'Emergency info', 'public.heading' => 'Emergency medical info',
    'public.call_parent' => 'Call parent', 'public.call_ambulance' => 'Call ambulance · 123',
    'public.privacy' => 'The parent\'s number is not shown on this page.',
    'public.logged' => 'This visit is logged and the parent is notified.',
    'public.not_found' => 'This card is not active. If this is an emergency, call 123.',
    'public.no_contact' => 'No parent phone number is available. If this is an emergency, call 123.',
];
