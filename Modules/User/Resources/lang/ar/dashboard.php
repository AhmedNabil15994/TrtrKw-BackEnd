<?php

return [
    'admins' => [
        'create' => [
            'form' => [
                'confirm_password' => 'تاكيد كلمة المرور',
                'email' => 'البريد الالكتروني',
                'general' => 'بيانات عامة',
                'image' => 'الصورة الشخصية',
                'info' => 'البيانات',
                'mobile' => 'الهاتف',
                'name' => 'الاسم',
                'password' => 'كلمة المرور',
                'roles' => 'الادوار',
            ],
            'title' => 'اضافة الموظفين',
        ],
        'datatable' => [
            'created_at' => 'تاريخ الآنشاء',
            'date_range' => 'البحث بالتواريخ',
            'email' => 'البريد الالكتروني',
            'image' => 'الصورة الشخصية',
            'mobile' => 'الهاتف',
            'name' => 'الاسم',
            'roles' => 'الأدوار',
            'options' => 'الخيارات',
        ],
        'index' => [
            'title' => 'الموظفين',
        ],
        'update' => [
            'form' => [
                'confirm_password' => 'تآكيد كلمة المرور',
                'email' => 'البريد الالكتروني',
                'general' => 'بيانات عامة',
                'image' => 'الصورة الشخصية',
                'mobile' => 'الهاتف',
                'name' => 'الاسم',
                'password' => 'تغير كلمة المرور',
                'roles' => 'الادوار',
            ],
            'title' => 'تعديل الموظفين',
        ],
        'validation' => [
            'email' => [
                'required' => 'من فضلك ادخل البريد الالكتروني',
                'unique' => 'هذا البريد تم ادخالة من قبل',
            ],
            'mobile' => [
                'digits_between' => 'من فضلك ادخل ٨ ارقام فقط داخل رقم الهاتف',
                'numeric' => 'يجب ان يتكون رقم الهاتف من ارقام فقط بالانجليزية',
                'required' => 'من فضلك ادخل رقم الهاتف',
                'unique' => 'رقم الهاتف تم ادخالة من قبل',
            ],
            'name' => [
                'required' => 'من فضلك ادخل الاسم الشخصي',
            ],
            'password' => [
                'min' => 'يجب ان يتكون كلمة المرور من كلمة اكبر من ٦ مدخلات : ارقام او احرف',
                'required' => 'من فضلك ادخل كلمة المرور',
                'same' => 'كلمة المرور غير متطابقة مع التآكيد',
            ],
            'roles' => [
                'required' => 'من فضلك اختر ادوار المدير',
            ],
        ],
    ],
    'drivers' => [
        'create' => [
            'form' => [
                'confirm_password' => 'تاكيد كلمة المرور',
                'email' => 'البريد الالكتروني',
                'general' => 'بيانات عامة',
                'image' => 'الصورة الشخصية',
                'info' => 'البيانات',
                'mobile' => 'الهاتف',
                'name' => 'الاسم',
                'password' => 'كلمة المرور',
                'roles' => 'الادوار',
                'company' => 'شركة الشحن',
            ],
            'title' => 'اضافة السائقين',
        ],
        'datatable' => [
            'created_at' => 'تاريخ الآنشاء',
            'date_range' => 'البحث بالتواريخ',
            'email' => 'البريد الالكتروني',
            'image' => 'الصورة الشخصية',
            'mobile' => 'الهاتف',
            'name' => 'الاسم',
            'options' => 'الخيارات',
            'company' => 'شركة الشحن',
        ],
        'index' => [
            'title' => 'السائقين',
        ],
        'update' => [
            'form' => [
                'confirm_password' => 'تآكيد كلمة المرور',
                'email' => 'البريد الالكتروني',
                'general' => 'بيانات عامة',
                'image' => 'الصورة الشخصية',
                'mobile' => 'الهاتف',
                'name' => 'الاسم',
                'password' => 'تغير كلمة المرور',
                'roles' => 'الادوار',
            ],
            'title' => 'تعديل السائقين',
        ],
        'validation' => [
            'email' => [
                'required' => 'من فضلك ادخل البريد الالكتروني',
                'unique' => 'هذا البريد تم ادخالة من قبل',
            ],
            'mobile' => [
                'digits_between' => 'من فضلك ادخل ٨ ارقام فقط داخل رقم الهاتف',
                'numeric' => 'يجب ان يتكون رقم الهاتف من ارقام فقط بالانجليزية',
                'required' => 'من فضلك ادخل رقم الهاتف',
                'unique' => 'رقم الهاتف تم ادخالة من قبل',
            ],
            'name' => [
                'required' => 'من فضلك ادخل الاسم الشخصي',
            ],
            'password' => [
                'min' => 'يجب ان يتكون كلمة المرور من كلمة اكبر من ٦ مدخلات : ارقام او احرف',
                'required' => 'من فضلك ادخل كلمة المرور',
                'same' => 'كلمة المرور غير متطابقة مع التآكيد',
            ],
            'roles' => [
                'required' => 'من فضلك اختر ادوار السائقين',
            ],
            'company_id' => [
                'required' => 'من فضلك اختر شركة الشحن',
            ],
        ],
    ],
    'sellers' => [
        'create' => [
            'form' => [
                'confirm_password' => 'تاكيد كلمة المرور',
                'email' => 'البريد الالكتروني',
                'general' => 'بيانات عامة',
                'image' => 'الصورة الشخصية',
                'info' => 'البيانات',
                'mobile' => 'الهاتف',
                'name' => 'الاسم',
                'password' => 'كلمة المرور',
                'roles' => 'الادوار',
            ],
            'title' => 'اضافة البائعين',
        ],
        'datatable' => [
            'created_at' => 'تاريخ الآنشاء',
            'date_range' => 'البحث بالتواريخ',
            'email' => 'البريد الالكتروني',
            'image' => 'الصورة الشخصية',
            'mobile' => 'الهاتف',
            'name' => 'الاسم',
            'options' => 'الخيارات',
        ],
        'index' => [
            'title' => 'البائعين',
        ],
        'update' => [
            'form' => [
                'confirm_password' => 'تآكيد كلمة المرور',
                'email' => 'البريد الالكتروني',
                'general' => 'بيانات عامة',
                'image' => 'الصورة الشخصية',
                'mobile' => 'الهاتف',
                'name' => 'الاسم',
                'password' => 'تغير كلمة المرور',
                'roles' => 'الادوار',
            ],
            'title' => 'تعديل البائع',
        ],
        'validation' => [
            'email' => [
                'required' => 'من فضلك ادخل البريد الالكتروني',
                'unique' => 'هذا البريد تم ادخالة من قبل',
            ],
            'mobile' => [
                'digits_between' => 'من فضلك ادخل ٨ ارقام فقط داخل رقم الهاتف',
                'numeric' => 'يجب ان يتكون رقم الهاتف من ارقام فقط بالانجليزية',
                'required' => 'من فضلك ادخل رقم الهاتف',
                'unique' => 'رقم الهاتف تم ادخالة من قبل',
            ],
            'name' => [
                'required' => 'من فضلك ادخل الاسم الشخصي',
            ],
            'password' => [
                'min' => 'يجب ان يتكون كلمة المرور من كلمة اكبر من ٦ مدخلات : ارقام او احرف',
                'required' => 'من فضلك ادخل كلمة المرور',
                'same' => 'كلمة المرور غير متطابقة مع التآكيد',
            ],
            'roles' => [
                'required' => 'من فضلك اختر ادوار البائعين',
            ],
        ],
    ],
    'users' => [
        'create' => [
            'form' => [
                'confirm_password' => 'تاكيد كلمة المرور',
                'email' => 'البريد الالكتروني',
                'general' => 'بيانات عامة',
                'image' => 'الصورة الشخصية',
                'info' => 'البيانات',
                'mobile' => 'الهاتف',
                'name' => 'الاسم',
                'password' => 'كلمة المرور',
            ],
            'title' => 'اضافة العملاء',
        ],
        'datatable' => [
            'created_at' => 'تاريخ الآنشاء',
            'date_range' => 'البحث بالتواريخ',
            'email' => 'البريد الالكتروني',
            'image' => 'الصورة الشخصية',
            'mobile' => 'الهاتف',
            'name' => 'الاسم',
            'options' => 'الخيارات',
        ],
        'index' => [
            'title' => 'العملاء',
        ],
        'update' => [
            'form' => [
                'confirm_password' => 'تآكيد كلمة المرور',
                'email' => 'البريد الالكتروني',
                'general' => 'بيانات عامة',
                'image' => 'الصورة الشخصية',
                'mobile' => 'الهاتف',
                'name' => 'الاسم',
                'password' => 'تغير كلمة المرور',
            ],
            'title' => 'تعديل العضو',
        ],
        'validation' => [
            'email' => [
                'required' => 'من فضلك ادخل البريد الالكتروني',
                'unique' => 'هذا البريد تم ادخالة من قبل',
            ],
            'mobile' => [
                'digits_between' => 'من فضلك ادخل ٨ ارقام فقط داخل رقم الهاتف',
                'numeric' => 'يجب ان يتكون رقم الهاتف من ارقام فقط بالانجليزية',
                'required' => 'من فضلك ادخل رقم الهاتف',
                'unique' => 'رقم الهاتف تم ادخالة من قبل',
            ],
            'name' => [
                'required' => 'من فضلك ادخل الاسم الشخصي',
            ],
            'password' => [
                'min' => 'يجب ان يتكون كلمة المرور من كلمة اكبر من ٦ مدخلات : ارقام او احرف',
                'required' => 'من فضلك ادخل كلمة المرور',
                'same' => 'كلمة المرور غير متطابقة مع التآكيد',
            ],
        ],
    ],
];
