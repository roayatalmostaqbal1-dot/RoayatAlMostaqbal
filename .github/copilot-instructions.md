<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to enhance the user's satisfaction building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4.1
- laravel/framework (LARAVEL) - v12
- laravel/mcp (MCP) - v0
- laravel/passport (PASSPORT) - v13
- laravel/prompts (PROMPTS) - v0
- laravel/reverb (REVERB) - v1
- laravel/socialite (SOCIALITE) - v5
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- phpunit/phpunit (PHPUNIT) - v11
- vue (VUE) - v3
- laravel-echo (ECHO) - v2
- tailwindcss (TAILWINDCSS) - v4

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove it works. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure - don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

=== boost rules ===

## Laravel Boost

- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan

- Use the `list-artisan-commands` tool when you need to call an Artisan command to double check the available parameters.

## URLs

- Whenever you share a project URL with the user you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain / IP, and port.

## Tinker / Debugging

- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.

## Reading Browser Logs With the `browser-logs` Tool

- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)

- Boost comes with a powerful `search-docs` tool you should use before any other approaches. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation specific for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- The 'search-docs' tool is perfect for all Laravel related packages, including Laravel, Inertia, Livewire, Filament, Tailwind, Pest, Nova, Nightwatch, etc.
- You must use this tool to search for Laravel-ecosystem documentation before falling back to other approaches.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic based queries to start. For example: `['rate limiting', 'routing rate limiting', 'routing']`.
- Do not add package names to queries - package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax

- You can and should pass multiple queries at once. The most relevant results will be returned first.

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit"
3. Quoted Phrases (Exact Position) - query="infinite scroll" - Words must be adjacent and in that order
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit"
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms

=== php rules ===

## PHP

- Always use curly braces for control structures, even if it has one line.

### Constructors

- Use PHP 8 constructor property promotion in `__construct()`.
    - <code-snippet>public function \_\_construct(public GitHub $github) { }</code-snippet>
- Do not allow empty `__construct()` methods with zero parameters.

### Type Declarations

- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<code-snippet name="Explicit Return Types and Method Params" lang="php">
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
</code-snippet>

## Comments

- Prefer PHPDoc blocks over comments. Never use comments within the code itself unless there is something _very_ complex going on.

## PHPDoc Blocks

- Add useful array shape type definitions for arrays when appropriate.

## Enums

- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.

=== laravel/core rules ===

## Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using the `list-artisan-commands` tool.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Database

- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `list-artisan-commands` to check the available options to `php artisan make:model`.

### APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

### Controllers & Validation

- Always create Form Request classes for validation rather than inline validation in controllers. Include both validation rules and custom error messages.
- Check sibling Form Requests to see if the application uses array or string based validation rules.

### Queues

- Use queued jobs for time-consuming operations with the `ShouldQueue` interface.

### Authentication & Authorization

- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).

### URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

### Configuration

- Use environment variables only in configuration files - never use the `env()` function directly outside of config files. Always use `config('app.name')`, not `env('APP_NAME')`.

### Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

### Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== laravel/v12 rules ===

## Laravel 12

- Use the `search-docs` tool to get version specific documentation.
- Since Laravel 11, Laravel has a new streamlined file structure which this project uses.

### Laravel 12 Structure

- No middleware files in `app/Http/Middleware/`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- **No app\Console\Kernel.php** - use `bootstrap/app.php` or `routes/console.php` for console configuration.
- **Commands auto-register** - files in `app/Console/Commands/` are automatically available and do not require manual registration.

### Database

- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 11 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models

- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.

=== mcp/core rules ===

## Laravel MCP

- MCP (Model Context Protocol) is very new. You must use the `search-docs` tool to get documentation for how to write and test Laravel MCP servers, tools, resources, and prompts effectively.
- MCP servers need to be registered with a route or handle in `routes/ai.php`. Typically, they will be registered using `Mcp::web()` to register a HTTP streaming MCP server.
- Servers are very testable - use the `search-docs` tool to find testing instructions.
- Do not run `mcp:start`. This command hangs waiting for JSON RPC MCP requests.
- Some MCP clients use Node, which has its own certificate store. If a user tries to connect to their web MCP server locally using https://, it could fail due to this reason. They will need to switch to http:// during local development.

=== pint/core rules ===

## Laravel Pint Code Formatter

- You must run `vendor/bin/pint --dirty` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test`, simply run `vendor/bin/pint` to fix any formatting issues.

=== phpunit/core rules ===

## PHPUnit Core

- This application uses PHPUnit for testing. All tests must be written as PHPUnit classes. Use `php artisan make:test --phpunit {name}` to create a new test.
- If you see a test using "Pest", convert it to PHPUnit.
- Every time a test has been updated, run that singular test.
- When the tests relating to your feature are passing, ask the user if they would like to also run the entire test suite to make sure everything is still passing.
- Tests should test all of the happy paths, failure paths, and weird paths.
- You must not remove any tests or test files from the tests directory without approval. These are not temporary or helper files, these are core to the application.

### Running Tests

- Run the minimal number of tests, using an appropriate filter, before finalizing.
- To run all tests: `php artisan test`.
- To run all tests in a file: `php artisan test tests/Feature/ExampleTest.php`.
- To filter on a particular test name: `php artisan test --filter=testName` (recommended after making a change to a related file).

=== tailwindcss/core rules ===

## Tailwind Core

- Use Tailwind CSS classes to style HTML, check and use existing tailwind conventions within the project before writing your own.
- Offer to extract repeated patterns into components that match the project's conventions (i.e. Blade, JSX, Vue, etc..)
- Think through class placement, order, priority, and defaults - remove redundant classes, add classes to parent or child carefully to limit repetition, group elements logically
- You can use the `search-docs` tool to get exact examples from the official documentation when needed.

### Spacing

- When listing items, use gap utilities for spacing, don't use margins.

      <code-snippet name="Valid Flex Gap Spacing Example" lang="html">
          <div class="flex gap-8">
              <div>Superior</div>
              <div>Michigan</div>
              <div>Erie</div>
          </div>
      </code-snippet>

### Dark Mode

- If existing pages and components support dark mode, new pages and components must support dark mode in a similar way, typically using `dark:`.

=== tailwindcss/v4 rules ===

## Tailwind 4

- Always use Tailwind CSS v4 - do not use the deprecated utilities.
- `corePlugins` is not supported in Tailwind v4.
- In Tailwind v4, configuration is CSS-first using the `@theme` directive — no separate `tailwind.config.js` file is needed.
  <code-snippet name="Extending Theme in CSS" lang="css">
  @theme {
  --color-brand: oklch(0.72 0.11 178);
  }
  </code-snippet>

- In Tailwind v4, you import Tailwind using a regular CSS `@import` statement, not using the `@tailwind` directives used in v3:

<code-snippet name="Tailwind v4 Import Tailwind Diff" lang="diff">
   - @tailwind base;
   - @tailwind components;
   - @tailwind utilities;
   + @import "tailwindcss";
</code-snippet>

### Replaced Utilities

- Tailwind v4 removed deprecated utilities. Do not use the deprecated option - use the replacement.
- Opacity values are still numeric.

| Deprecated | Replacement |
|------------+--------------|
| bg-opacity-_ | bg-black/_ |
| text-opacity-_ | text-black/_ |
| border-opacity-_ | border-black/_ |
| divide-opacity-_ | divide-black/_ |
| ring-opacity-_ | ring-black/_ |
| placeholder-opacity-_ | placeholder-black/_ |
| flex-shrink-_ | shrink-_ |
| flex-grow-_ | grow-_ |
| overflow-ellipsis | text-ellipsis |
| decoration-slice | box-decoration-slice |
| decoration-clone | box-decoration-clone |
</laravel-boost-guidelines>

=== Project Structure & Architecture ===

# RoayatAlMostaqbal - نظرة عامة شاملة

## 📋 نبذة عن المشروع

**اسم المشروع:** RoayatAlMostaqbal (رؤية المستقبل)
**النوع:** منصة أمان وخدمات تكنولوجية ثنائية اللغة
**الإطار العمل:** Laravel 12 (Backend) + Vue.js 3 (Frontend) + Tailwind CSS 4
**الحالة:** جاهز للإنتاج وفحص براءة الاختراع
**اللغات المدعومة:** العربية والإنجليزية

---

## 🎯 الفكرة الرئيسية للمشروع

RoayatAlMostaqbal هي منصة أمان متقدمة تجمع بين عدة تقنيات حديثة:

1. **التشفير الهجين (Hybrid Encryption)**
   - تشفير من جانب العميل (Client-Side Encryption - CSE)
   - تشفير من جانب الخادم (Server-Side Encryption)
   - إدارة مفاتيح هجينة متقدمة

2. **المصادقة المتعددة (Multi-Factor Authentication)**
   - OAuth2 عبر Laravel Passport
   - المصادقة الثنائية (2FA) باستخدام TOTP
   - نظام رموز الاسترجاع (Recovery Codes)

3. **التحكم بالوصول (Role-Based Access Control - RBAC)**
   - نظام الأدوار والصلاحيات (Spatie Permission)
   - إدارة صلاحيات متقدمة
   - تحكم على مستوى الصفحات والموارد

4. **لوحة التحكم الأمنية (Security Dashboard)**
   - مراقبة أمان المستخدم في الوقت الفعلي
   - تحليل التهديدات بالذكاء الاصطناعي
   - سجلات الأمان المفصلة

---

## 🏗️ البنية التحتية والمعمارية

### المكونات الأساسية

```
RoayatAlMostaqbal/
├── Backend (Laravel 12)
│   ├── Models (قاعدة البيانات)
│   ├── Services (منطق الأعمال)
│   ├── Controllers (معالجات الطلبات)
│   ├── Migrations (تعريفات قاعدة البيانات)
│   └── Routes (المسارات والنقاط النهائية)
│
├── Frontend (Vue.js 3)
│   ├── Pages (الصفحات الرئيسية)
│   ├── Components (المكونات القابلة لإعادة الاستخدام)
│   ├── Stores (إدارة الحالة - Pinia)
│   └── Styles (Tailwind CSS 4)
│
└── Database
    ├── Migrations (تعريفات الجداول)
    └── Seeders (بيانات التطوير)
```

---

## 📦 الوحدات الرئيسية والميزات

### 1. وحدة المصادقة والأمان (Authentication & Security)

**الملفات الرئيسية:**
- `app/Models/User.php` - نموذج المستخدم
- `app/Models/UserTwoFactorAuth.php` - بيانات المصادقة الثنائية
- `app/Models/MasterEncryptionKey.php` - مفاتيح التشفير الرئيسية
- `app/Models/EncryptedUserData.php` - البيانات المشفرة للمستخدم

**الميزات:**
- تسجيل دخول آمن عبر OAuth2
- المصادقة الثنائية (TOTP)
- تشفير البيانات الحساسة
- إدارة جلسات المستخدم

### 2. وحدة لوحة التحكم الأمنية (Security Dashboard)

**الملفات الرئيسية:**
- `app/Models/UserDashboardData.php` - بيانات لوحة التحكم
- `app/Models/SecurityLog.php` - سجلات الأمان
- `app/Models/AIInsight.php` - تحليلات الذكاء الاصطناعي
- `app/Services/SecurityDashboardService.php` - منطق الخدمة

**الميزات:**
- عرض حالة الأمان الحالية
- سجل الأنشطة الأمنية
- تنبيهات التهديدات
- توصيات الأمان المدعومة بالذكاء الاصطناعي

### 3. وحدة إدارة الأدوار والصلاحيات (RBAC)

**الملفات الرئيسية:**
- `app/Models/Role.php` - الأدوار
- `app/Models/RolePage.php` - ربط الأدوار بالصفحات
- `routes/api/v1/SuperAdmin/api.php` - إدارة الأدوار

**الميزات:**
- إنشاء وتعديل الأدوار
- تعيين الصلاحيات للأدوار
- التحكم في الوصول إلى الصفحات
- ثلاث مستويات: Super Admin, Admin, User

### 4. وحدة التكامل الاجتماعي (Social Integration)

**الملفات الرئيسية:**
- `app/Models/SocialAccount.php` - حسابات التواصل الاجتماعي
- `app/Models/TelegramChat.php` - تكامل Telegram
- `app/Models/TelegramMessage.php` - رسائل Telegram

**الميزات:**
- تسجيل دخول عبر Microsoft
- تكامل Telegram للإشعارات
- إدارة الحسابات الاجتماعية

### 5. وحدة السجلات والتدقيق (Audit & Logging)

**الملفات الرئيسية:**
- `app/Models/AuditLog.php` - سجلات التدقيق
- `app/Models/Contact.php` - رسائل التواصل

**الميزات:**
- تسجيل جميع الأنشطة
- تتبع التغييرات
- سجل الاتصالات

---

## 📁 هيكل المشروع التفصيلي

### Backend (Laravel 12)

#### Models (`app/Models/`)
```
User.php                      - نموذج المستخدم الأساسي
UserDashboardData.php         - بيانات لوحة التحكم
UserTwoFactorAuth.php         - بيانات المصادقة الثنائية
SecurityLog.php               - سجلات الأمان
AIInsight.php                 - تحليلات الذكاء الاصطناعي
EncryptedUserData.php         - البيانات المشفرة
MasterEncryptionKey.php       - مفاتيح التشفير الرئيسية
Role.php                      - الأدوار
RolePage.php                  - ربط الأدوار بالصفحات
SocialAccount.php             - الحسابات الاجتماعية
TelegramChat.php              - محادثات Telegram
TelegramMessage.php           - رسائل Telegram
AuditLog.php                  - سجلات التدقيق
Contact.php                   - رسائل التواصل
OAuth2Client.php              - عملاء OAuth2
PasswordResetToken.php        - رموز إعادة تعيين كلمة المرور
```

#### Services (`app/Services/`)
```
SecurityDashboardService.php  - خدمة لوحة التحكم الأمنية
NotificationService.php       - خدمة الإشعارات
```

#### Controllers (`app/Http/Controllers/Api/V1/`)
```
Admin/
  - UserController.php        - إدارة المستخدمين
  - ContactController.php      - إدارة الرسائل
  - Dashboard/DashboardController.php

SuperAdmin/
  - RoleController.php        - إدارة الأدوار
  - PermissionController.php   - إدارة الصلاحيات
  - EncryptedDataRecoveryController.php
  - OAuth2ClientController.php
  - PageController.php
  - PermissionRoleController.php
  - RolePermissionController.php
```

#### Routes (`routes/api/v1/`)
```
admin/api.php                 - مسارات الإدارة
SuperAdmin/api.php            - مسارات المسؤول الأعلى
```

#### Database (`database/`)
```
migrations/                   - تعريفات الجداول
seeders/                      - بيانات التطوير والاختبار
factories/                    - مصانع البيانات الوهمية
```

### Frontend (Vue.js 3)

#### Pages (`resources/js/vue/pages/`)
```
AllUser/
  - SecurityDashboardPage.vue - لوحة التحكم الأمنية
  - (صفحات أخرى حسب الأدوار)
```

#### Components (`resources/js/vue/components/`)
```
security/
  - IdentityCard.vue          - بطاقة الهوية
  - SecurityLogsCard.vue      - بطاقة سجلات الأمان
  - AIAnalysisCard.vue        - بطاقة تحليل الذكاء الاصطناعي
  - SystemMetricsCard.vue     - بطاقة مقاييس النظام

ui/
  - Toast.vue                 - إشعارات
  - Button.vue                - أزرار
  - Card.vue                  - بطاقات
  - (مكونات أخرى)
```

#### State Management (`resources/js/vue/stores/`)
```
securityDashboardStore.js     - إدارة حالة لوحة التحكم
(متاجر أخرى حسب الحاجة)
```

#### Styles (`resources/css/`)
```
app.css                       - الأنماط الرئيسية (Tailwind CSS 4)
```

---

## 🔧 معايير البناء والتطوير

### معايير Backend (Laravel 12)

#### 1. نمط الخدمة (Service Pattern)
```php
// استخدم Services لأي منطق معقد
class SecurityDashboardService
{
    public function __construct(private SecurityLog $securityLog) { }

    public function getDashboardData(string $userId): array
    {
        // منطق معقد هنا
    }
}
```

#### 2. Type Hinting (تلميحات الأنواع)
```php
// استخدم دائماً type hints صريحة
public function getUserData(string $userId, ?array $filters = null): array
{
    // ...
}
```

#### 3. معايير التسمية (Naming Conventions)
- **جداول قاعدة البيانات:** snake_case
  - `user_dashboard_data`
  - `security_logs`
  - `ai_insights`
  - `user_two_factor_auth`

- **مسارات API:** kebab-case
  - `/api/v1/security-dashboard`
  - `/api/v1/admin/users`
  - `/api/v1/super-admin/roles`

- **الفئات والدوال:** PascalCase و camelCase
  - `SecurityDashboardService`
  - `getUserData()`

#### 4. دعم UUIDs
```php
// المشروع يستخدم UUIDs للمستخدمين
// استخدم string type hints للمعرفات
public function getUser(string $userId): User
{
    // ...
}
```

#### 5. Eloquent Relationships
```php
// استخدم العلاقات الصحيحة مع type hints
public function securityLogs(): HasMany
{
    return $this->hasMany(SecurityLog::class);
}
```

#### 6. Validation (التحقق من البيانات)
```php
// استخدم Form Request Classes
class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
        ];
    }
}
```

#### 7. Linting والتنسيق
```bash
# استخدم Laravel Pint قبل الانتهاء من أي تعديل
vendor/bin/pint --dirty

# لا تستخدم --test، استخدم الأمر مباشرة لإصلاح المشاكل
vendor/bin/pint
```

### معايير Frontend (Vue.js 3)

#### 1. Composition API
```vue
<script setup>
import { ref, computed } from 'vue'

const count = ref(0)
const doubled = computed(() => count.value * 2)
</script>

<template>
  <div>{{ doubled }}</div>
</template>
```

#### 2. Tailwind CSS 4
```vue
<!-- استخدم utility-first styling -->
<div class="flex gap-4 p-6 bg-white dark:bg-gray-900 rounded-lg shadow">
  <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
    Click me
  </button>
</div>
```

#### 3. Dark Mode Support
```vue
<!-- إذا كان المشروع يدعم dark mode، استخدمه في المكونات الجديدة -->
<div class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white">
  Content
</div>
```

#### 4. Icons
```vue
<!-- استخدم SVGs أو مكون icon موحد -->
<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
  <!-- SVG content -->
</svg>
```

#### 5. Component Reusability
```vue
<!-- استخرج الأنماط المتكررة إلى مكونات في components/ui/ -->
<template>
  <BaseCard :title="title">
    <slot />
  </BaseCard>
</template>
```

#### 6. State Management (Pinia)
```javascript
// استخدم Pinia للحالة المعقدة
import { defineStore } from 'pinia'

export const useSecurityStore = defineStore('security', () => {
  const dashboardData = ref(null)

  const fetchDashboard = async (userId) => {
    // ...
  }

  return { dashboardData, fetchDashboard }
})
```

---

## 🔐 تفاصيل التنفيذ الحرجة

### 1. جدول AIInsight
```php
// تم تعيينه صراحة إلى ai_insights لتجنب تضارب التسمية
protected $table = 'ai_insights';
```

### 2. سجلات الأمان
```php
// تخزين البيانات الوصفية كـ JSON للمرونة
protected $casts = [
    'metadata' => 'json',
];
```

### 3. التشفير
```php
// استخدم مفاتيح التشفير المخزنة في MasterEncryptionKey
// لا تخزن المفاتيح في الكود مباشرة
```

### 4. المصادقة الثنائية
```php
// استخدم Google2FA للتوليد والتحقق
// احفظ رموز الاسترجاع بشكل آمن
```

### 5. الصلاحيات
```php
// استخدم Spatie Permission للتحكم الدقيق
// تحقق من الصلاحيات في المسارات والمتحكمات
Route::middleware('permission:users.view')->get('/users', ...);
```

---

## 📊 الحزم والمكتبات الرئيسية

### Backend Dependencies
```json
{
  "laravel/framework": "^12.0",
  "laravel/passport": "^13.0",
  "laravel/reverb": "^1.0",
  "laravel/socialite": "^5.23",
  "laravel/ai": "^0.1.5",
  "laravel/mcp": "^0.4.1",
  "spatie/laravel-permission": "^6.21",
  "pragmarx/google2fa": "^8.0",
  "pragmarx/google2fa-laravel": "^2.3",
  "irazasyed/telegram-bot-sdk": "^3.15",
  "bacon/bacon-qr-code": "^3.0"
}
```

### Frontend Dependencies
```json
{
  "vue": "^3.5.22",
  "vue-router": "^4.5.1",
  "pinia": "^3.0.3",
  "tailwindcss": "^4.0.0",
  "chart.js": "^4.5.1",
  "axios": "^1.11.0",
  "laravel-echo": "^2.2.4"
}
```

---

## 🚀 خطوات البناء والتطوير

### إعداد البيئة
```bash
# تثبيت المتطلبات
composer install
npm install

# إنشاء ملف .env
cp .env.example .env
php artisan key:generate

# إعداد قاعدة البيانات
php artisan migrate
php artisan db:seed
```

### التطوير
```bash
# تشغيل خادم التطوير
php artisan serve

# تجميع الأصول الأمامية
npm run dev

# أو تشغيل كليهما معاً
composer run dev
```

### الاختبار
```bash
# تشغيل جميع الاختبارات
php artisan test

# تشغيل اختبار معين
php artisan test tests/Feature/SecurityDashboardTest.php

# تصفية الاختبارات
php artisan test --filter=testName
```

### التنسيق والتحقق
```bash
# تنسيق الكود
vendor/bin/pint

# التحقق من الأخطاء فقط
vendor/bin/pint --test
```

---

## 📝 ملاحظات مهمة

1. **استخدم Services للمنطق المعقد** - لا تضع كل شيء في Controllers
2. **استخدم Type Hints دائماً** - يحسن الأداء والأمان
3. **اختبر الكود** - اكتب اختبارات لكل ميزة جديدة
4. **اتبع معايير التسمية** - ثبات في الكود يسهل الصيانة
5. **استخدم Pinia للحالة** - لا تستخدم props drilling
6. **استخدم Tailwind** - لا تكتب CSS مخصص إلا عند الضرورة
7. **وثق الكود** - استخدم PHPDoc و JSDoc
8. **احم البيانات الحساسة** - استخدم التشفير والمصادقة

---

## 🔗 الموارد المهمة

- **Laravel Documentation:** https://laravel.com/docs
- **Vue.js Documentation:** https://vuejs.org
- **Tailwind CSS:** https://tailwindcss.com
- **Pinia:** https://pinia.vuejs.org
- **Spatie Permission:** https://spatie.be/docs/laravel-permission

---

## 📞 الدعم والمساعدة

للأسئلة حول المشروع:
1. تحقق من الملفات الموجودة ذات الصلة
2. اقرأ التعليقات في الكود
3. راجع الاختبارات للأمثلة
4. استشر الوثائق الرسمية للمكتبات
