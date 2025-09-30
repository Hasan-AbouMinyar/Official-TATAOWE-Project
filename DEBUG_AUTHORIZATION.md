# Debugging Event Authorization Issue

## المشكلة الحالية

المستخدم المسجل دخوله لا يستطيع رؤية زر Edit حتى لو كان صاحب المنظمة.

## خطوات التشخيص

### 1. التحقق من المستخدم الحالي

افتح المتصفح → اذهب لأي صفحة → افتح Console (F12) → اكتب:

```javascript
// التحقق من المستخدم الحالي
const user = JSON.parse(localStorage.getItem('user'))
console.log('Current User:', user)

// التحقق من التوكن
const token = localStorage.getItem('auth_token')
console.log('Token exists:', !!token)
```

### 2. التحقق من بيانات الحدث

عندما تفتح صفحة حدث، ستظهر في Console:

```javascript
Event loaded: {
  eventId: 1,
  eventName: "...",
  organization: {...},
  organizationUserId: 8,  // ← ID صاحب المنظمة
  currentUser: {...},
  currentUserId: X        // ← ID المستخدم الحالي
}
```

```javascript
canEdit check: {
  organizationUserId: 8,
  currentUserId: X,
  match: false  // ← يجب أن يكون true لإظهار زر Edit
}
```

### 3. الحل

#### إذا كان `currentUserId` ≠ `organizationUserId`:

**السبب**: أنت مسجل دخول بمستخدم مختلف عن صاحب المنظمة.

**الحل**: سجل دخول بالمستخدم الصحيح:

```sql
-- في Backend
cd /Users/abouminyar/Projects/Tatawoe/Backend
php artisan tinker

// أظهر جميع المنظمات وأصحابها
App\Models\Organization::with('user')->get()->map(function($org) {
    return [
        'org_id' => $org->id,
        'org_name' => $org->name,
        'user_id' => $org->user_id,
        'user_name' => $org->user->name,
        'user_email' => $org->user->email,
    ];
});
```

ثم سجل دخول بالـ email الصحيح.

#### إذا كان المستخدم صحيح لكن ما زال لا يعمل:

**المشكلة**: البيانات لا تُحمّل بشكل صحيح من API.

**الحل**:

1. تأكد من أن Backend يرجع `user_id` في organization:

```bash
curl http://localhost:8000/api/events/1 \
  -H "Authorization: Bearer YOUR_TOKEN" | jq '.organization'
```

يجب أن ترى:
```json
{
  "id": 1,
  "user_id": 8,  // ← يجب أن يكون موجود
  "name": "...",
  ...
}
```

2. إذا لم يكن `user_id` موجود، المشكلة في EventController:

```php
// في Backend/app/Http/Controllers/Api/EventController.php
public function show(Event $event)
{
    // يجب أن يكون هكذا:
    $event->load(['organization.user:id', 'reviews.user:id,name,photo']);
    // ...
}
```

### 4. تسجيل دخول سريع للاختبار

#### A) إنشاء مستخدم جديد مع منظمة:

```php
cd /Users/abouminyar/Projects/Tatawoe/Backend
php artisan tinker

// إنشاء مستخدم جديد
$user = App\Models\User::create([
    'name' => 'Test Owner',
    'username' => 'testowner',
    'email' => 'owner@test.com',
    'password' => bcrypt('password'),
    'phoneNumber' => '1234567890',
    'email_verified_at' => now(),
]);

// إنشاء منظمة له
$org = App\Models\Organization::create([
    'user_id' => $user->id,
    'name' => 'Test Organization',
    'email' => 'org@test.com',
    'field' => 'Technology',
    'description' => 'Test organization for debugging',
]);

// إنشاء حدث للمنظمة
$event = App\Models\Event::create([
    'organization_id' => $org->id,
    'name' => 'Test Event',
    'description' => 'Test event for debugging',
    'start_time' => now()->addDays(1),
    'end_time' => now()->addDays(2),
    'location' => 'Test Location',
]);

echo "✅ Created:\n";
echo "User: owner@test.com (password: password)\n";
echo "Organization: {$org->name} (ID: {$org->id})\n";
echo "Event: {$event->name} (ID: {$event->id})\n";
```

ثم:
1. سجل خروج من الموقع
2. سجل دخول بـ: `owner@test.com` / `password`
3. اذهب للحدث: `/event/{event_id}`
4. يجب أن ترى زر Edit ✅

#### B) استخدام مستخدم موجود:

```php
cd /Users/abouminyar/Projects/Tatawoe/Backend
php artisan tinker

// أظهر مستخدم مع منظماته وأحداثه
$userId = 8; // أو أي user_id
$user = App\Models\User::with(['organizations.events'])->find($userId);

echo "User: {$user->name} ({$user->email})\n\n";

foreach ($user->organizations as $org) {
    echo "Organization: {$org->name} (ID: {$org->id})\n";
    foreach ($org->events as $event) {
        echo "  - Event: {$event->name} (ID: {$event->id})\n";
    }
}
```

### 5. الكود الحالي للتحقق

```javascript
// في Frontend/src/views/Event/EventDetails.vue
const canEdit = computed(() => {
  if (!event.value || !authStore.user) return false
  const organizationUserId = event.value.organization?.user_id
  const currentUserId = authStore.user.id
  console.log('canEdit check:', { organizationUserId, currentUserId, match: organizationUserId === currentUserId })
  return organizationUserId === currentUserId
})
```

**يجب أن يكون**:
- `organizationUserId` = user_id من قاعدة البيانات للمنظمة
- `currentUserId` = user_id للمستخدم المسجل دخوله
- `match: true` = لإظهار زر Edit

---

## تلخيص المشكلة المحتملة

### السيناريو 1: أنت مسجل دخول بمستخدم خطأ ✅ (الأرجح)
- **الحل**: سجل دخول بالمستخدم الصحيح (صاحب المنظمة)
- **كيف تعرف**: `currentUserId` ≠ `organizationUserId` في Console

### السيناريو 2: البيانات لا تُحمّل من API
- **الحل**: تأكد من أن `organization.user_id` موجود في response
- **كيف تعرف**: `organizationUserId` = `undefined` في Console

### السيناريو 3: authStore.user فارغ
- **الحل**: تأكد من أن `localStorage.getItem('user')` موجود
- **كيف تعرف**: `currentUserId` = `undefined` في Console

---

## الخطوة التالية

1. افتح المتصفح
2. افتح Console (F12)
3. اذهب لأي صفحة event
4. انظر للـ logs في Console
5. أرسل لي screenshot أو انسخ الـ logs
6. سأساعدك في إيجاد المشكلة بالضبط

---

**ملاحظة مهمة**: 
الكود الحالي **صحيح**، المشكلة على الأرجح هي أنك مسجل دخول بمستخدم مختلف عن صاحب المنظمة. التحقق من Console سيوضح المشكلة بالضبط.
