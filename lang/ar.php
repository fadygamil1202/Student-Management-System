<?php
return [
    'app.name' => 'ملفات الطلاب المدرسية',
    'save' => 'حفظ', 'cancel' => 'إلغاء', 'add' => 'إضافة', 'remove' => 'إزالة', 'delete' => 'حذف',
    'filter' => 'تصفية', 'saved' => 'تم الحفظ.',

    'nav.dashboard' => 'الرئيسية', 'nav.students' => 'الطلاب', 'nav.classes' => 'الفصول والمواد',
    'nav.users' => 'المستخدمون', 'nav.notifications' => 'الإشعارات', 'nav.account' => 'حسابي', 'nav.logout' => 'تسجيل الخروج',

    'role.admin' => 'مسؤول المدرسة', 'role.teacher' => 'معلم', 'role.parent' => 'ولي أمر',
    'role.teacher_pl' => 'المعلمون', 'role.parent_pl' => 'أولياء الأمور',

    'login.title' => 'تسجيل الدخول', 'login.subtitle' => 'لموظفي المدرسة وأولياء الأمور.',
    'login.submit' => 'دخول', 'login.invalid' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
    'login.too_many' => 'محاولات كثيرة. يرجى الانتظار ١٥ دقيقة ثم المحاولة مرة أخرى.',

    'error.403' => 'ليس لديك صلاحية للوصول إلى هذه الصفحة.', 'error.404' => 'الصفحة غير موجودة.',
    'error.429' => 'طلبات كثيرة. يرجى الانتظار دقيقة.', 'error.400' => 'طلب غير صالح.',
    'error.csrf' => 'انتهت الجلسة. ارجع وحدّث الصفحة ثم حاول مرة أخرى.',
    'error.home' => 'الذهاب إلى الصفحة الرئيسية', 'error.required' => 'حقل :field مطلوب.',
    'error.invalid' => 'يرجى مراجعة النموذج والمحاولة مرة أخرى.', 'error.invalid_date' => 'تاريخ غير صالح.',

    'field.name' => 'الاسم', 'field.first_name' => 'الاسم الأول', 'field.last_name' => 'اسم العائلة',
    'field.email' => 'البريد الإلكتروني', 'field.password' => 'كلمة المرور', 'field.phone' => 'الهاتف', 'field.role' => 'الدور',
    'field.dob' => 'تاريخ الميلاد', 'field.class' => 'الفصل', 'field.age' => 'العمر', 'field.age_n' => ':n سنوات',
    'field.student' => 'الطالب', 'field.subject' => 'المادة', 'field.term' => 'الفصل الدراسي',
    'field.assessment' => 'التقييم', 'field.score' => 'الدرجة', 'field.max_score' => 'من',
    'field.blood_type' => 'فصيلة الدم', 'field.allergies' => 'الحساسية', 'field.conditions' => 'الحالات الصحية',
    'field.medications' => 'الأدوية', 'field.care_notes' => 'ملاحظات الرعاية الطارئة',
    'field.private_notes' => 'ملاحظات خاصة بالموظفين',

    'severity.mild' => 'خفيفة', 'severity.moderate' => 'متوسطة', 'severity.severe' => 'شديدة',

    'dash.welcome' => 'مرحبًا، :name', 'dash.scans_week' => 'مسح البطاقات (٧ أيام)',
    'dash.recent_scans' => 'آخر عمليات مسح البطاقات', 'dash.no_scans' => 'لا توجد عمليات مسح بعد.',
    'dash.my_classes' => 'فصولي', 'dash.no_classes' => 'لا توجد فصول مسندة إليك بعد. اطلب من مسؤول المدرسة إسنادك.',
    'dash.n_students' => ':n طالب', 'dash.pending_insights' => 'يوجد :n من تحليلات الذكاء الاصطناعي بانتظار مراجعتك.',
    'dash.my_children' => 'أبنائي', 'dash.no_children' => 'لا يوجد أبناء مرتبطون بحسابك بعد. تواصل مع المدرسة.',
    'dash.view_profile' => 'عرض الملف', 'dash.unread' => 'لديك :n إشعارات جديدة.',

    'students.new' => 'إضافة طالب', 'students.edit' => 'تعديل بيانات الطالب', 'students.search' => 'ابحث بالاسم',
    'students.all_classes' => 'كل الفصول', 'students.none' => 'لا يوجد طلاب.',
    'students.created' => 'تمت إضافة الطالب. أكمل الآن بيانات الطوارئ.',
    'students.deleted' => 'تم حذف الطالب.', 'students.delete' => 'حذف الطالب',
    'students.delete_confirm' => 'حذف هذا الطالب وكل بياناته؟ لا يمكن التراجع عن ذلك.',
    'students.notes_hint' => 'هذه الملاحظات يراها موظفو المدرسة فقط.', 'students.no_class' => 'بدون فصل',

    'layer1.title' => 'معلومات الطوارئ (البطاقة العامة)', 'layer1.public_on' => 'البطاقة مفعّلة', 'layer1.public_off' => 'البطاقة معطّلة',
    'layer1.preview' => 'معاينة الصفحة العامة', 'layer1.regen' => 'فُقدت البطاقة؟ رابط جديد',
    'layer1.regen_confirm' => 'سيؤدي هذا إلى إيقاف البطاقة والرابط القديمين فورًا، وستحتاج إلى طباعة بطاقة جديدة. هل تريد المتابعة؟',
    'layer1.no_consent' => 'لم يوافق ولي الأمر بعد على صفحة الطوارئ العامة.',

    'emergency.edit_title' => 'معلومات الطوارئ', 'emergency.token_regenerated' => 'تم إنشاء رابط جديد. البطاقة القديمة لم تعد تعمل.',
    'emergency.intro' => 'هذا ما سيراه أي شخص يلمس البطاقة بهاتفه. اكتفِ بما يحتاجه المسعف.',
    'emergency.allergies_ph' => "فول سوداني|شديدة\nالبنسلين|متوسطة",
    'emergency.allergies_hint' => 'سطر لكل حساسية: الاسم|الشدة (خفيفة أو متوسطة أو شديدة).',
    'emergency.conditions_ph' => 'مثال: ربو — البخاخة في الحقيبة المدرسية', 'emergency.medications_ph' => 'مثال: بخاخة سالبيوتامول عند الحاجة',
    'emergency.care_ph' => 'مثال: قلم الإبينفرين في الجيب الأمامي للحقيبة',
    'emergency.visible_title' => 'يظهر في الصفحة العامة', 'emergency.visible_hint' => 'العناصر غير المحددة تبقى خاصة. الاسم الكامل والفصل والمدرسة وأرقام الهواتف لا تظهر أبدًا.',
    'emergency.enabled' => 'البطاقة مفعّلة (أزل التحديد لإيقاف الصفحة العامة)',
    'emergency.consent' => 'أنا ولي أمر الطالب وأوافق على عرض المعلومات المحددة في صفحة الطوارئ العامة.',
    'emergency.consented_at' => 'وافق ولي الأمر بتاريخ :date.',

    'card.title' => 'طباعة البطاقة', 'card.print' => 'طباعة البطاقة / رمز QR', 'card.print_now' => 'طباعة',
    'card.help_nfc' => 'اطبع هذه البطاقة، واكتب الرابط التالي على شريحة NFC الخاصة بالبطاقة (NTAG213/215) باستخدام أي تطبيق لكتابة NFC.',
    'card.heading' => 'معلومات طبية للطوارئ', 'card.scan_text' => 'المس البطاقة بالهاتف أو امسح الرمز',
    'card.qr_label' => 'رمز QR يفتح صفحة الطوارئ',

    'grades.title' => 'الدرجات', 'grades.none' => 'لا توجد درجات بعد.', 'grades.add' => 'إضافة درجة',
    'grades.added' => 'تمت إضافة الدرجة.', 'grades.deleted' => 'تم حذف الدرجة.', 'grades.delete_confirm' => 'حذف هذه الدرجة؟',
    'grades.invalid' => 'راجع الدرجة: يجب أن تكون بين صفر والدرجة القصوى، وكل الحقول مطلوبة.',
    'grades.term_ph' => 'مثال: الفصل الأول ٢٠٢٦', 'grades.assessment_ph' => 'مثال: امتحان منتصف الفصل',

    'ai.title' => 'تحليلات الذكاء الاصطناعي', 'ai.generate' => 'إنشاء تحليل', 'ai.none' => 'لا توجد تحليلات بعد.',
    'ai.disabled' => 'تحليلات الذكاء الاصطناعي متوقفة. أضف مفتاح Claude API في ملف config.php لتفعيلها.',
    'ai.privacy_note' => 'تُرسل الدرجات والعمر فقط دون أي اسم أو بيانات صحية أو أرقام تواصل. يبقى التحليل مسودة حتى يوافق عليه المعلم، ولا يرى ولي الأمر إلا التحليلات المعتمدة.',
    'ai.no_grades' => 'أضف بعض الدرجات أولًا.', 'ai.failed' => 'فشل طلب الذكاء الاصطناعي.',
    'ai.too_many' => 'طلبات كثيرة هذه الساعة. حاول لاحقًا.',
    'ai.generated' => 'تم إنشاء مسودة تحليل. راجعها قبل أن يراها ولي الأمر.',
    'ai.reviewed' => 'تمت مراجعة التحليل.', 'ai.approve' => 'اعتماد ومشاركة مع ولي الأمر', 'ai.reject' => 'رفض',
    'ai.edit_before' => 'يمكنك تعديل النص قبل الاعتماد.',
    'ai.status_draft' => 'مسودة — تحتاج مراجعة', 'ai.status_approved' => 'معتمد', 'ai.status_rejected' => 'مرفوض',
    'ai.reviewed_by' => 'راجعه :name',

    'guardians.title' => 'أولياء الأمور', 'guardians.none' => 'لا يوجد أولياء أمور مرتبطون.', 'guardians.primary' => 'جهة الاتصال الأساسية',
    'guardians.link_parent' => 'ربط حساب ولي أمر', 'guardians.relation' => 'صلة القرابة',
    'guardians.relation_ph' => 'مثال: الأم', 'guardians.create_first' => 'أنشئ حساب ولي الأمر من صفحة المستخدمين أولًا.',

    'scan.title' => 'سجل مسح البطاقة', 'scan.action' => 'الإجراء', 'scan.time' => 'الوقت',
    'scan.view' => 'فُتحت الصفحة', 'scan.call_parent' => 'اتصال بولي الأمر',

    'notify.scan' => 'تم فتح بطاقة الطوارئ الخاصة بـ :name الساعة :time.',
    'notify.call' => 'ضغط شخص على "اتصل بولي الأمر" في صفحة طوارئ :name الساعة :time.',
    'notify.insight' => 'يوجد تحليل جديد معتمد من المعلم لـ :name.',
    'notify.none' => 'لا توجد إشعارات.',

    'classes.classes' => 'الفصول', 'classes.subjects' => 'المواد', 'classes.assignments' => 'إسناد المعلمين',
    'classes.assign_hint' => 'لا يرى المعلم ولا يرصد درجات إلا طلاب الفصول المسندة إليه.',
    'classes.assign' => 'إسناد', 'classes.class_ph' => 'مثال: الصف الرابع أ', 'classes.subject_ph' => 'مثال: الرياضيات',
    'classes.new_class' => 'اسم الفصل الجديد', 'classes.new_subject' => 'اسم المادة الجديدة',

    'users.new' => 'إضافة مستخدم', 'users.create' => 'إنشاء الحساب', 'users.created' => 'تم إنشاء الحساب.',
    'users.email_taken' => 'هذا البريد مستخدم بالفعل.', 'users.temp_password' => 'كلمة مرور مؤقتة',
    'users.temp_hint' => '٨ أحرف على الأقل. اطلب من صاحب الحساب تغييرها من "حسابي".',
    'users.activate' => 'تفعيل', 'users.deactivate' => 'إيقاف', 'users.cannot_self' => 'لا يمكنك إيقاف حسابك.',

    'account.change_password' => 'تغيير كلمة المرور', 'account.current_password' => 'كلمة المرور الحالية',
    'account.new_password' => 'كلمة المرور الجديدة (اتركها فارغة للإبقاء)', 'account.wrong_password' => 'كلمة المرور الحالية غير صحيحة.',
    'account.password_short' => 'يجب ألا تقل كلمة المرور عن ٨ أحرف.',
    'account.phone_hint' => 'يُستخدم لزر "اتصل بولي الأمر" في صفحة طوارئ ابنك، ولا يظهر في الصفحة أبدًا.',

    'public.title' => 'معلومات الطوارئ', 'public.heading' => 'معلومات طبية للطوارئ',
    'public.call_parent' => 'اتصل بولي الأمر', 'public.call_ambulance' => 'اتصل بالإسعاف (١٢٣)',
    'public.privacy' => 'رقم ولي الأمر لا يظهر في هذه الصفحة.',
    'public.logged' => 'تم تسجيل هذه الزيارة وإبلاغ ولي الأمر.',
    'public.not_found' => 'هذه البطاقة غير مفعّلة. في حالة الطوارئ اتصل بـ ١٢٣.',
    'public.no_contact' => 'لا يوجد رقم لولي الأمر. في حالة الطوارئ اتصل بـ ١٢٣.',
];
