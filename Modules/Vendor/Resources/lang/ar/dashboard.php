<?php

return [
    'payments' => [
        'create' => [
            'form' => [
                'code' => 'كود الدفع',
                'general' => 'بيانات عامة',
                'image' => 'الصورة',
                'info' => 'البيانات',
            ],
            'title' => 'اضافة طرق الدفع',
        ],
        'datatable' => [
            'code' => 'كود الدفع',
            'created_at' => 'تاريخ الآنشاء',
            'date_range' => 'البحث بالتواريخ',
            'image' => 'الصورة',
            'options' => 'الخيارات',
        ],
        'index' => [
            'title' => 'طرق الدفع',
        ],
        'update' => [
            'form' => [
                'code' => 'كود الدفع',
                'general' => 'بيانات عامة',
                'image' => 'الصورة',
            ],
            'title' => 'تعديل طريقة الدفع',
        ],
        'validation' => [
            'code' => [
                'required' => 'من فضلك ادخل كود الدفع',
                'unique' => 'هذا الكود تم ادخالة من قبل',
            ],
            'image' => [
                'required' => 'من فضلك اختر الصورة',
            ],
        ],
    ],
    'sections' => [
        'create' => [
            'form' => [
                'description' => 'الوصف',
                'general' => 'بيانات عامة',
                'info' => 'البيانات',
                'meta_description' => 'Meta Description',
                'meta_keywords' => 'Meta Keywords',
                'seo' => 'SEO',
                'status' => 'الحالة',
                'title' => 'عنوان القسم',
                'image' => 'صورة القسم',
            ],
            'title' => 'اضافة اقسام المتاجر',
        ],
        'datatable' => [
            'created_at' => 'تاريخ الآنشاء',
            'date_range' => 'البحث بالتواريخ',
            'options' => 'الخيارات',
            'status' => 'الحالة',
            'title' => 'العنوان',
        ],
        'index' => [
            'title' => 'اقسام المتاجر',
        ],
        'update' => [
            'form' => [
                'description' => 'الوصف',
                'general' => 'بيانات عامة',
                'meta_description' => 'Meta Description',
                'meta_keywords' => 'Meta Keywords',
                'seo' => 'SEO',
                'status' => 'الحالة',
                'title' => 'عنوان القسم',
                'image' => 'صورة القسم',
            ],
            'title' => 'تعديل اقسام المتاجر',
        ],
        'validation' => [
            'description' => [
                'required' => 'من فضلك ادخل وصف القسم',
            ],
            'title' => [
                'required' => 'من فضلك ادخل عنوان القسم',
                'unique' => 'هذا العنوان تم ادخالة من قبل',
            ],
        ],
    ],
    'vendor_statuses' => [
        'create' => [
            'form' => [
                'accepted_orders' => 'حالة استقبال الطلبات',
                'info' => 'البيانات',
                'label_color' => 'لون العلامة',
                'title' => 'العنوان',
            ],
            'title' => 'اضافة حالات المتجر',
        ],
        'datatable' => [
            'accepted_orders' => 'حالة استقبال الطلبات',
            'created_at' => 'تاريخ الآنشاء',
            'date_range' => 'البحث بالتواريخ',
            'label_color' => 'لون العلامة',
            'options' => 'الخيارات',
            'title' => 'العنوان',
        ],
        'index' => [
            'title' => 'حالات المتجر',
        ],
        'update' => [
            'form' => [
                'accepted_orders' => 'حالة استقبال الطلبات',
                'general' => 'بيانات عامة',
                'label_color' => 'لون العلامة',
                'title' => 'العنوان',
            ],
            'title' => 'تعديل حالات المتجر',
        ],
        'validation' => [
            'accepted_orders' => [
                'unique' => 'لا يمكن اكثر من حالة لستقبال الطلبات',
            ],
            'label_color' => [
                'required' => 'من فضلك اختر لون العلامة',
            ],
        ],
    ],
    'vendors' => [
        'create' => [
            'form' => [
                'commission' => 'نسبة الربح من المتجر',
                'description' => 'الوصف',
                'fixed_commission' => 'نسبة ربح ثابتة',
                'fixed_delivery' => 'سعر التوصيل الثابت',
                'general' => 'بيانات عامة',
                'image' => 'الصورة',
                'info' => 'البيانات',
                'is_trusted' => 'صلاحيات الاضافة',
                'meta_description' => 'Meta Description',
                'meta_keywords' => 'Meta Keywords',
                'order_limit' => 'الحد الادنى للطلب',
                'other' => 'بيانات اخرى',
                'payments' => 'طرق الدفع المدعومة',
                'products' => 'تصدير المنتجات',
                'receive_prescription' => 'استقبال الوصفات الطبية',
                'receive_question' => 'استقبال الأسئلة',
                'sections' => 'قسم المتجر',
                'sellers' => 'بائعين المتجر',
                'seo' => 'SEO',
                'status' => 'الحالة',
                'title' => 'عنوان',
                'vendor_email' => 'البريد الالكتروني للمتجر',
                'vendor_statuses' => 'حالة المتجر',
                'companies' => 'شركات التوصيل',
                'companies_and_states' => 'التوصيل',
                'states' => 'يرجى تحديد المناطق التي يتم التوصيل إليها',
            ],
            'title' => 'اضافة المتاجر',
        ],
        'datatable' => [
            'created_at' => 'تاريخ الآنشاء',
            'date_range' => 'البحث بالتواريخ',
            'image' => 'الصورة',
            'options' => 'الخيارات',
            'status' => 'الحالة',
            'title' => 'العنوان',
            'products' => 'المنتجات',
            'no_products_data' => 'لا يوجد منتجات حالياً',
            'total' => 'الإجمالى',
            'per_page' => 'اجمالى الصفحة',
        ],
        'index' => [
            'sorting' => 'ترتيب المتاجر',
            'title' => 'المتاجر',
        ],
        'sorting' => [
            'title' => 'ترتيب المتاجر',
        ],
        'update' => [
            'form' => [
                'commission' => 'نسبة الربح من المتجر',
                'description' => 'الوصف',
                'general' => 'بيانات عامة',
                'image' => 'الصورة',
                'info' => 'البيانات',
                'is_trusted' => 'صلاحيات الاضافة',
                'meta_description' => 'Meta Description',
                'meta_keywords' => 'Meta Keywords',
                'order_limit' => 'الحد الادنى للطلب',
                'other' => 'بيانات اخرى',
                'payments' => 'طرق الدفع المدعومة',
                'products' => 'تصدير المنتجات',
                'receive_prescription' => 'استقبال الوصفات الطبية',
                'receive_question' => 'استقبال الاسالة',
                'sections' => 'قسم المتجر',
                'sellers' => 'بائعين المتجر',
                'seo' => 'SEO',
                'status' => 'الحالة',
                'title' => 'عنوان',
                'vendor_email' => 'البريد الالكتروني للمتجر',
            ],
            'title' => 'تعديل المتجر',
        ],
        'validation' => [
            'commission' => [
                'numeric' => 'من فضلك ادخل نسبه الربح ارقام انجليزية فقط',
                'required' => 'من فضلك ادخل نسبه الربح',
            ],
            'fixed_commission' => [
                'numeric' => 'من فضلك ادخل نسبة الربح الثابتة ارقام انجليزية فقط',
                'required' => 'من فضلك ادخل نسبة ربح ثابتة',
            ],
            'description' => [
                'required' => 'من فضلك ادخل الوصف',
            ],
            'fixed_delivery' => [
                'numeric' => 'من فضلك ادخل سعر التوصيل الثابت ارقام انجليزية فقط',
                'required' => 'من فضلك ادخل سعر التوصيل الثابت',
            ],
            'image' => [
                'required' => 'من فضلك ادخل الصورة',
                'image' => 'من فضلك ادخل الصورة من نوع صورة',
                'mimes' => 'الصورة يجب ان تكون ضمن',
                'max' => 'حجم الصورة يجب الا يزيد عن',
            ],
            'months' => [
                'numeric' => 'من فضلك ادخل عدد شهور الباقة ارقام فقط',
                'required' => 'من فضلك ادخل عدد شهور الباقة',
            ],
            'order_limit' => [
                'numeric' => 'من فضلك ادخل الاحد الادنى كا ارقام انجليزية فقط : 5.000',
                'required' => 'من فضلك ادخل الحد الادنى للمتجر : 5.000',
            ],
            'payments' => [
                'required' => 'من فضلك اختر طرق الدفع المدعومة من قبل هذا المتجر',
            ],
            'price' => [
                'numeric' => 'من فضلك ادخل سعر الباقة ارقام فقط',
                'required' => 'من فضلك ادخل سعر الباقة',
            ],
            'sections' => [
                'required' => 'من فضلك اختر قسم المتجر',
            ],
            'sellers' => [
                'required' => 'من فضلك اختر البائعين لهذا المتجر',
            ],
            'special_price' => [
                'numeric' => 'من فضلك ادخل السعر الخاص ارقام فقط',
            ],
            'title' => [
                'required' => 'من فضلك ادخل العنوان',
                'unique' => 'هذا العنوان تم ادخالة من قبل',
            ],
            'products' => [
                'ids' => [
                    'required' => 'من فضلك اختر مصفوفة من الاختيارات او على الاقل اختيار واحد',
                ],
            ],
        ],
        'products' => [
            'title' => 'منتجات المتجر',
            'table' => [
                'title' => 'عنوان المنتج',
                'quantity' => 'الكمية',
                'price' => 'السعر',
                'status' => 'الحالة',
            ],
        ],
    ],
];
