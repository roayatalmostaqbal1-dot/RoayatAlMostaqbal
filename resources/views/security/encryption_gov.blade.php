@extends('layouts.app')

@section('title', __('messages.security.encryption.title') . ' - ' . __('messages.header.title'))
@section('description', __('messages.security.encryption.meta_description'))

@section('content')
    <!-- القسم 1: المقدمة (Hero) -->
    <section class="py-16 md:py-24 bg-white">
        <div class="max-w-5xl mx-auto px-5">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-8 text-center" style="font-family: 'Almarai', sans-serif;">
                حماية الرحلة الرقمية للأمة
            </h1>
            <div class="prose prose-lg max-w-none text-gray-700 text-lg leading-relaxed" style="font-family: 'Almarai', sans-serif;">
                <p class="mb-4">
                    في عصر التحول الرقمي الشامل الذي تشهده دولة الإمارات العربية المتحدة، تبرز الحاجة الماسة إلى حلول أمنية متقدمة تحمي البيانات الحساسة للمواطنين والمقيمين والمؤسسات الحكومية والخاصة. تقدم شركة رؤية المستقبل نموذجاً ابتكارياً في التشفير وحماية البيانات مصمماً خصيصاً للبيئة الحكومية الإماراتية، يجمع بين أحدث تقنيات التشفير من جانب العميل (Client-Side Encryption) وإدارة المفاتيح الهجينة لضمان أقصى درجات الأمان مع الحفاظ على قابلية الاستخدام والامتثال للمتطلبات التنظيمية.
                </p>
                <p class="mb-4">
                    هذا النموذج لا يقتصر على حماية البيانات التقليدية فحسب، بل يمتد ليشمل حماية الهوية الرقمية والتحقق الآمن من المستخدمين دون الكشف عن المعلومات الحساسة، مما يجعله حلاً مثالياً للخدمات الحكومية الإلكترونية والمنصات الرقمية التي تتطلب مستويات عالية من الأمان والخصوصية.
                </p>
                <p>
                    تم تصميم هذا النموذج ليكون متوافقاً مع قانون حماية البيانات الشخصية لدولة الإمارات العربية المتحدة (القانون الاتحادي رقم 45 لسنة 2021) ومتطلبات الأمن السيبراني الوطني، مع ضمان إمكانية التدقيق والامتثال للمعايير الحكومية دون المساس بخصوصية المستخدمين.
                </p>
            </div>
        </div>
    </section>

    <!-- القسم 2: الابتكار الأساسي -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-5xl mx-auto px-5">
            <h2 class="text-3xl md:text-4xl font-bold mb-8" style="color: #00732F; font-family: 'Almarai', sans-serif;">
                النموذج الابتكاري: التشفير الهجين للبيئة الحكومية
            </h2>
            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed" style="font-family: 'Almarai', sans-serif;">
                <p class="mb-4">
                    يمثل نموذج التشفير الهجين السيادي الذي طورته شركة رؤية المستقبل حلاً تقنياً متكاملاً يجمع بين مزايا التشفير من جانب العميل (Client-Side Encryption) والتشفير من جانب الخادم (Server-Side Encryption) في إطار موحد يوفر أقصى درجات الأمان مع الحفاظ على المرونة التشغيلية.
                </p>
                <p class="mb-4">
                    يتميز هذا النموذج بإدارة هجينة للمفاتيح التشفيرية، حيث يتم تشفير البيانات الحساسة للمستخدمين على أجهزتهم قبل الإرسال إلى الخوادم، بينما يتم الاحتفاظ بمفاتيح التشفير الرئيسية في بيئة آمنة على الخوادم لضمان إمكانية الاسترجاع في حالات الطوارئ والامتثال لمتطلبات التدقيق الحكومي.
                </p>
                <p class="mb-4">
                    يعتمد النموذج على مبادئ إثبات المعرفة الصفرية (Zero-Knowledge Proof) في عملية المصادقة، مما يسمح بالتحقق من هوية المستخدمين دون الحاجة إلى نقل أو تخزين كلمات المرور أو البيانات الحساسة الأخرى بشكل نصي. هذا النهج يضمن أن الخوادم لا يمكنها الوصول إلى البيانات المشفرة حتى في حالة اختراق النظام.
                </p>
                <p class="mb-4 font-semibold" style="color: #00732F;">
                    هذا النموذج محمي بطلب براءة اختراع رقم <strong>AEPD21656</strong> لدى مكتب براءات الاختراع في دولة الإمارات العربية المتحدة.
                </p>
                <p>
                    تم تطوير هذا النموذج ليكون متوافقاً مع البيئة الحكومية الإماراتية ومتطلباتها الخاصة بالأمن السيبراني وحماية البيانات، مع ضمان إمكانية التكامل مع الأنظمة الحكومية القائمة والامتثال الكامل للمعايير الوطنية والدولية.
                </p>
            </div>
        </div>
    </section>

    <!-- القسم 3: مكونات النموذج (جدول) -->
    <section class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-5">
            <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8" style="font-family: 'Almarai', sans-serif;">
                مكونات نموذج التشفير الهجين السيادي
            </h3>
            <!-- جدول للشاشات الكبيرة -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300 bg-white" style="font-family: 'Almarai', sans-serif;">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-6 py-4 text-right font-bold text-gray-900" style="color: #00732F;">
                                المكون
                            </th>
                            <th class="border border-gray-300 px-6 py-4 text-right font-bold text-gray-900" style="color: #00732F;">
                                الوظيفة
                            </th>
                            <th class="border border-gray-300 px-6 py-4 text-right font-bold text-gray-900" style="color: #00732F;">
                                الفائدة
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 px-6 py-4 text-gray-700 font-semibold">
                                التشفير من جانب العميل (CSE)
                            </td>
                            <td class="border border-gray-300 px-6 py-4 text-gray-700">
                                تشفير البيانات الحساسة على جهاز المستخدم قبل الإرسال إلى الخوادم
                            </td>
                            <td class="border border-gray-300 px-6 py-4 text-gray-700">
                                ضمان عدم إمكانية وصول الخوادم إلى البيانات النصية حتى في حالة الاختراق
                            </td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="border border-gray-300 px-6 py-4 text-gray-700 font-semibold">
                                إدارة المفاتيح الهجينة
                            </td>
                            <td class="border border-gray-300 px-6 py-4 text-gray-700">
                                توزيع إدارة المفاتيح بين المستخدم والخادم بشكل متوازن
                            </td>
                            <td class="border border-gray-300 px-6 py-4 text-gray-700">
                                تحقيق التوازن بين الأمان القصوى وإمكانية الاسترجاع والامتثال
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-6 py-4 text-gray-700 font-semibold">
                                إثبات المعرفة الصفرية (ZKP)
                            </td>
                            <td class="border border-gray-300 px-6 py-4 text-gray-700">
                                التحقق من هوية المستخدمين دون الكشف عن بيانات الاعتماد
                            </td>
                            <td class="border border-gray-300 px-6 py-4 text-gray-700">
                                حماية خصوصية المستخدمين مع ضمان أمان المصادقة
                            </td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td class="border border-gray-300 px-6 py-4 text-gray-700 font-semibold">
                                التشفير من جانب الخادم (SSE)
                            </td>
                            <td class="border border-gray-300 px-6 py-4 text-gray-700">
                                تشفير إضافي للبيانات الحساسة على مستوى الخوادم
                            </td>
                            <td class="border border-gray-300 px-6 py-4 text-gray-700">
                                طبقة حماية إضافية للبيانات المخزنة وضمان الامتثال للمتطلبات التنظيمية
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- بطاقات للشاشات الصغيرة -->
            <div class="md:hidden space-y-4" style="font-family: 'Almarai', sans-serif;">
                <div class="bg-white border border-gray-300 p-4">
                    <h4 class="font-bold text-gray-900 mb-2" style="color: #00732F;">التشفير من جانب العميل (CSE)</h4>
                    <p class="text-sm text-gray-600 mb-2"><strong>الوظيفة:</strong> تشفير البيانات الحساسة على جهاز المستخدم قبل الإرسال إلى الخوادم</p>
                    <p class="text-sm text-gray-600"><strong>الفائدة:</strong> ضمان عدم إمكانية وصول الخوادم إلى البيانات النصية حتى في حالة الاختراق</p>
                </div>
                <div class="bg-gray-50 border border-gray-300 p-4">
                    <h4 class="font-bold text-gray-900 mb-2" style="color: #00732F;">إدارة المفاتيح الهجينة</h4>
                    <p class="text-sm text-gray-600 mb-2"><strong>الوظيفة:</strong> توزيع إدارة المفاتيح بين المستخدم والخادم بشكل متوازن</p>
                    <p class="text-sm text-gray-600"><strong>الفائدة:</strong> تحقيق التوازن بين الأمان القصوى وإمكانية الاسترجاع والامتثال</p>
                </div>
                <div class="bg-white border border-gray-300 p-4">
                    <h4 class="font-bold text-gray-900 mb-2" style="color: #00732F;">إثبات المعرفة الصفرية (ZKP)</h4>
                    <p class="text-sm text-gray-600 mb-2"><strong>الوظيفة:</strong> التحقق من هوية المستخدمين دون الكشف عن بيانات الاعتماد</p>
                    <p class="text-sm text-gray-600"><strong>الفائدة:</strong> حماية خصوصية المستخدمين مع ضمان أمان المصادقة</p>
                </div>
                <div class="bg-gray-50 border border-gray-300 p-4">
                    <h4 class="font-bold text-gray-900 mb-2" style="color: #00732F;">التشفير من جانب الخادم (SSE)</h4>
                    <p class="text-sm text-gray-600 mb-2"><strong>الوظيفة:</strong> تشفير إضافي للبيانات الحساسة على مستوى الخوادم</p>
                    <p class="text-sm text-gray-600"><strong>الفائدة:</strong> طبقة حماية إضافية للبيانات المخزنة وضمان الامتثال للمتطلبات التنظيمية</p>
                </div>
            </div>
        </div>
    </section>

    <!-- القسم 4: خدمة المنظومة الوطنية -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-5xl mx-auto px-5">
            <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8" style="font-family: 'Almarai', sans-serif;">
                كيف يخدم هذا النموذج منظومتنا الثلاثية؟
            </h3>
            <div class="space-y-6" style="font-family: 'Almarai', sans-serif;">
                <div class="bg-white p-6 border-r-4" style="border-color: #00732F;">
                    <h4 class="text-xl font-bold text-gray-900 mb-3" style="color: #00732F;">
                        حماية المواطنين والمقيمين
                    </h4>
                    <p class="text-gray-700 leading-relaxed">
                        يضمن نموذج التشفير الهجين حماية البيانات الشخصية والحساسة للمواطنين والمقيمين في جميع تفاعلاتهم الرقمية مع الخدمات الحكومية والخاصة. من خلال تطبيق التشفير من جانب العميل، يتم ضمان عدم إمكانية وصول أي طرف ثالث، بما في ذلك مقدمي الخدمة أنفسهم، إلى البيانات النصية للمستخدمين. هذا النهج يتوافق مع مبادئ الخصوصية وحماية البيانات المنصوص عليها في قانون حماية البيانات الشخصية لدولة الإمارات العربية المتحدة، ويوفر للمواطنين والمقيمين الثقة الكاملة في أمان معلوماتهم الشخصية.
                    </p>
                </div>
                <div class="bg-white p-6 border-r-4" style="border-color: #00732F;">
                    <h4 class="text-xl font-bold text-gray-900 mb-3" style="color: #00732F;">
                        دعم المؤسسات الحكومية
                    </h4>
                    <p class="text-gray-700 leading-relaxed">
                        يوفر النموذج للمؤسسات الحكومية القدرة على تقديم خدمات رقمية آمنة وموثوقة مع ضمان الامتثال الكامل للمتطلبات التنظيمية والأمنية. من خلال إدارة المفاتيح الهجينة، يمكن للمؤسسات الحكومية الحفاظ على قدرتها على التدقيق والامتثال للمعايير الوطنية دون المساس بخصوصية المستخدمين. هذا النهج يسمح للمؤسسات الحكومية بتطبيق مبادئ "الخصوصية بالتصميم" (Privacy by Design) و"الأمان بالتصميم" (Security by Design) في جميع خدماتها الرقمية، مما يعزز ثقة المواطنين في التحول الرقمي الحكومي.
                    </p>
                </div>
                <div class="bg-white p-6 border-r-4" style="border-color: #00732F;">
                    <h4 class="text-xl font-bold text-gray-900 mb-3" style="color: #00732F;">
                        تعزيز الأمن السيبراني الوطني
                    </h4>
                    <p class="text-gray-700 leading-relaxed">
                        يساهم نموذج التشفير الهجين في تعزيز الأمن السيبراني الوطني من خلال توفير طبقات متعددة من الحماية ضد التهديدات السيبرانية المختلفة. يعتمد النموذج على معايير التشفير الدولية المعترف بها مثل AES-256 وTLS 1.3، مما يضمن مقاومة عالية ضد محاولات الاختراق والوصول غير المصرح به. بالإضافة إلى ذلك، يوفر النموذج إمكانية التكامل مع أنظمة الأمن السيبراني الوطنية والامتثال لمتطلبات الاستجابة للحوادث السيبرانية، مما يساهم في بناء منظومة أمنية وطنية متكاملة وقادرة على مواجهة التحديات السيبرانية المعاصرة.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- القسم 5: التقنيات الداعمة -->
    <section class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-5">
            <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8" style="font-family: 'Almarai', sans-serif;">
                أسس تقنية راسخة
            </h3>
            <div class="space-y-6" style="font-family: 'Almarai', sans-serif;">
                <div class="border-b border-gray-200 pb-6">
                    <h4 class="text-xl font-bold text-gray-900 mb-3" style="color: #00732F;">
                        TLS 1.3 (Transport Layer Security)
                    </h4>
                    <p class="text-gray-700 leading-relaxed">
                        يتم استخدام بروتوكول TLS 1.3، أحدث إصدار من بروتوكولات أمان النقل، لتشفير جميع الاتصالات بين أجهزة المستخدمين والخوادم. يوفر TLS 1.3 حماية قوية ضد التنصت والتلاعب بالبيانات أثناء النقل، مع دعم Perfect Forward Secrecy الذي يضمن عدم إمكانية فك تشفير الاتصالات السابقة حتى في حالة اختراق المفاتيح. يتم استخدام خوارزمية AES-256-GCM للتشفير المتماثل، مما يوفر مستوى عالياً من الأمان مع الأداء الأمثل.
                    </p>
                </div>
                <div class="border-b border-gray-200 pb-6">
                    <h4 class="text-xl font-bold text-gray-900 mb-3" style="color: #00732F;">
                        التشفير من جانب العميل (Client-Side Encryption)
                    </h4>
                    <p class="text-gray-700 leading-relaxed">
                        يتم تطبيق التشفير من جانب العميل باستخدام معايير التشفير المتماثل القوية مثل AES-256. يتم إنشاء مفاتيح التشفير على أجهزة المستخدمين ولا يتم إرسالها إلى الخوادم أبداً. يتم تشفير البيانات الحساسة مثل المعلومات الشخصية والملفات الخاصة على جهاز المستخدم قبل الإرسال، مما يضمن أن الخوادم تستقبل البيانات في شكل مشفر فقط ولا يمكنها الوصول إلى المحتوى النصي حتى في حالة اختراق النظام.
                    </p>
                </div>
                <div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3" style="color: #00732F;">
                        إثبات المعرفة الصفرية (Zero-Knowledge Proof)
                    </h4>
                    <p class="text-gray-700 leading-relaxed">
                        يتم تطبيق مبادئ إثبات المعرفة الصفرية في عملية المصادقة والتحقق من الهوية. يعتمد النظام على بروتوكولات TOTP (Time-based One-Time Password) المستندة إلى RFC 6238 للتحقق من هوية المستخدمين دون الحاجة إلى نقل أو تخزين كلمات المرور أو البيانات الحساسة الأخرى. يسمح هذا النهج للمستخدمين بإثبات معرفتهم بالمعلومات السرية (مثل كلمة المرور أو رمز المصادقة) دون الكشف عن هذه المعلومات للخادم، مما يوفر مستوى عالياً من الخصوصية والأمان.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- القسم 6: الامتثال والمصداقية -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-5xl mx-auto px-5">
            <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8" style="font-family: 'Almarai', sans-serif;">
                الامتثال والمصداقية
            </h3>
            <div class="space-y-4" style="font-family: 'Almarai', sans-serif;">
                <div class="flex items-start">
                    <span class="text-gray-700 mr-3 mt-1">•</span>
                    <p class="text-gray-700 leading-relaxed flex-1">
                        <strong class="text-gray-900">الامتثال لقانون حماية البيانات الشخصية:</strong> يتوافق نموذج التشفير الهجين بشكل كامل مع القانون الاتحادي رقم 45 لسنة 2021 بشأن حماية البيانات الشخصية لدولة الإمارات العربية المتحدة، ويضمن حماية البيانات الشخصية وفقاً للمعايير والضوابط المنصوص عليها في القانون.
                    </p>
                </div>
                <div class="flex items-start">
                    <span class="text-gray-700 mr-3 mt-1">•</span>
                    <p class="text-gray-700 leading-relaxed flex-1">
                        <strong class="text-gray-900">طلب براءة الاختراع:</strong> النموذج محمي بطلب براءة اختراع رقم AEPD21656 لدى مكتب براءات الاختراع في دولة الإمارات العربية المتحدة، مما يؤكد الابتكار التقني والأصالة في التصميم.
                    </p>
                </div>
                <div class="flex items-start">
                    <span class="text-gray-700 mr-3 mt-1">•</span>
                    <p class="text-gray-700 leading-relaxed flex-1">
                        <strong class="text-gray-900">دعم Hub71:</strong> تم تطوير هذا النموذج بدعم من Hub71، منصة التكنولوجيا في أبوظبي، مما يعكس التزام الشركة بالابتكار والتطوير التقني في بيئة داعمة للشركات الناشئة والتقنية.
                    </p>
                </div>
                <div class="flex items-start">
                    <span class="text-gray-700 mr-3 mt-1">•</span>
                    <p class="text-gray-700 leading-relaxed flex-1">
                        <strong class="text-gray-900">دعم صندوق خليفة:</strong> حصلت الشركة على دعم من صندوق خليفة لتطوير المشاريع، مما يؤكد الجودة التقنية والجدوى التجارية للنموذج ومساهمته في تعزيز الابتكار التكنولوجي في دولة الإمارات العربية المتحدة.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- القسم 7: دعوة العمل (CTA) -->
    <section class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-5 text-center">
            <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8" style="font-family: 'Almarai', sans-serif;">
                لنبدأ حوارًا أمنيًا
            </h3>
            <div class="flex justify-center">
                <a href="{{ route('contact', app()->getLocale()) }}"
                   class="inline-block px-8 py-4 bg-white border-2 font-bold rounded text-lg transition-colors hover:bg-gray-50"
                   style="border-color: #00732F; color: #00732F; font-family: 'Almarai', sans-serif;">
                    تواصل مع فريق الأمن والسيبرانية
                </a>
            </div>
        </div>
    </section>
@endsection
