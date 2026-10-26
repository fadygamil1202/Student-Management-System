<?php
declare(strict_types=1);

require dirname(__DIR__) . '/src/bootstrap.php';
foreach (glob(BASE_PATH . '/src/controllers/*.php') as $file) {
    require $file;
}

$method = $_SERVER['REQUEST_METHOD'];
$path = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/');
// Strip the subfolder the app lives in (and /public or /index.php if typed directly)
if (BASE_URL !== '' && str_starts_with($path, BASE_URL)) {
    $path = substr($path, strlen(BASE_URL));
}
$path = preg_replace('#^/public(?=/|$)#', '', $path);
$path = preg_replace('#^/index\.php(?=/|$)#', '', $path);
$path = rtrim($path, '/') ?: '/';

// [method, pattern, handler]
$routes = [
    ['GET',  '#^/$#',                                 'home'],
    ['GET',  '#^/login$#',                            'login_form'],
    ['POST', '#^/login$#',                            'login_submit'],
    ['POST', '#^/logout$#',                           'logout_submit'],
    ['GET',  '#^/lang/(en|ar)$#',                     'switch_lang'],
    ['GET',  '#^/dashboard$#',                        'dashboard'],
    ['GET',  '#^/notifications$#',                    'notifications'],
    ['GET',  '#^/account$#',                          'account_form'],
    ['POST', '#^/account$#',                          'account_submit'],

    // Public NFC / link page (Layer 1)
    ['GET',  '#^/e/([a-f0-9]{32})$#',                 'public_emergency'],
    ['POST', '#^/e/([a-f0-9]{32})/call$#',            'public_call_parent'],

    // Students (Layer 2)
    ['GET',  '#^/students$#',                         'students_index'],
    ['GET',  '#^/students/new$#',                     'student_new'],
    ['POST', '#^/students$#',                         'student_create'],
    ['GET',  '#^/students/(\d+)$#',                   'student_show'],
    ['GET',  '#^/students/(\d+)/edit$#',              'student_edit'],
    ['POST', '#^/students/(\d+)$#',                   'student_update'],
    ['POST', '#^/students/(\d+)/delete$#',            'student_delete'],
    ['GET',  '#^/students/(\d+)/emergency$#',         'emergency_edit'],
    ['POST', '#^/students/(\d+)/emergency$#',         'emergency_update'],
    ['POST', '#^/students/(\d+)/token$#',             'emergency_regenerate_token'],
    ['GET',  '#^/students/(\d+)/card$#',              'student_card'],
    ['POST', '#^/students/(\d+)/guardians$#',         'guardian_add'],
    ['POST', '#^/students/(\d+)/guardians/(\d+)/delete$#', 'guardian_remove'],
    ['POST', '#^/students/(\d+)/grades$#',            'grade_create'],
    ['POST', '#^/grades/(\d+)/delete$#',              'grade_delete'],
    ['POST', '#^/students/(\d+)/insights$#',          'insight_generate'],
    ['POST', '#^/insights/(\d+)/review$#',            'insight_review'],

    // Administration
    ['GET',  '#^/admin/classes$#',                    'admin_classes'],
    ['POST', '#^/admin/classes$#',                    'admin_class_create'],
    ['POST', '#^/admin/subjects$#',                   'admin_subject_create'],
    ['POST', '#^/admin/assignments$#',                'admin_assignment_create'],
    ['POST', '#^/admin/assignments/(\d+)/delete$#',   'admin_assignment_delete'],
    ['GET',  '#^/admin/users$#',                      'admin_users'],
    ['POST', '#^/admin/users$#',                      'admin_user_create'],
    ['POST', '#^/admin/users/(\d+)/toggle$#',         'admin_user_toggle'],
];

foreach ($routes as [$m, $pattern, $handler]) {
    if (preg_match($pattern, $path, $matches)) {
        if ($m !== $method) continue;
        array_shift($matches);
        if ($method === 'POST') verify_csrf();
        // Cast each captured value to the handler's declared parameter type (int ids, string tokens)
        $params = (new ReflectionFunction($handler))->getParameters();
        $args = [];
        foreach ($matches as $i => $v) {
            $type = isset($params[$i]) ? (string)$params[$i]->getType() : 'string';
            $args[] = $type === 'int' ? (int)$v : $v;
        }
        $handler(...$args);
        exit;
    }
}

abort(404);
