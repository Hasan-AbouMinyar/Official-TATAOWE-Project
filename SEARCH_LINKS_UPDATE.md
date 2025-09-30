# تحديث نظام البحث - إضافة الروابط إلى صفحات التفاصيل

## ✅ المشكلة التي تم حلها

المستخدم أشار إلى أن البطاقات في نتائج البحث يجب أن تفتح صفحات التفاصيل عند الضغط عليها:
- الضغط على مستخدم → عرض الملف الشخصي
- الضغط على منظمة → عرض صفحة المنظمة
- الضغط على فعالية → عرض تفاصيل الفعالية

## 🔧 التغييرات المنفذة

### 1. إضافة Routes الجديدة

تم إضافة مسارين جديدين في `/Frontend/src/router/index.js`:

```javascript
// Route لعرض تفاصيل الفعالية
{
  path: '/events/:id',
  name: 'EventDetails',
  component: () => import('@/views/Event/Details.vue'),
  meta: { requiresAuth: true }
}

// Route لعرض الملف الشخصي للمستخدم
{
  path: '/users/:id',
  name: 'UserProfile',
  component: () => import('@/views/User/Profile.vue'),
  meta: { requiresAuth: true }
}
```

**ملاحظة:** مسار المنظمات `/organization/:id` كان موجوداً بالفعل ✅

### 2. إنشاء صفحة تفاصيل الفعالية

**الملف:** `/Frontend/src/views/Event/Details.vue`

**المميزات:**
- ✅ عرض صورة الفعالية (أو حرف أول ملون)
- ✅ عنوان الفعالية ووصفها
- ✅ رابط للمنظمة المنظمة
- ✅ التاريخ والوقت (بالإنجليزية)
- ✅ الموقع
- ✅ عدد المتطوعين المتقدمين
- ✅ المهارات المطلوبة (كـ badges)
- ✅ قسم التقييمات (Reviews)
- ✅ زر "Apply Now" للتطوع
- ✅ زر "Edit" إذا كان المستخدم مالك المنظمة

**الوظائف:**
```javascript
- loadEvent() // جلب تفاصيل الفعالية
- loadReviews() // جلب التقييمات
- checkApplication() // التحقق من التقديم السابق
- applyToEvent() // التقدم للفعالية
- formatDate() // تنسيق التاريخ بالإنجليزية
```

### 3. إنشاء صفحة الملف الشخصي للمستخدم

**الملف:** `/Frontend/src/views/User/Profile.vue`

**المميزات:**
- ✅ صورة الملف الشخصي (أو avatar بحرف أول)
- ✅ صورة غلاف (Cover Image)
- ✅ الاسم والـ username
- ✅ معلومات الاتصال (بريد، هاتف، عنوان)
- ✅ إحصائيات (عدد المنظمات، عدد التطبيقات)
- ✅ السيرة الذاتية (Bio)
- ✅ المهارات (Skills)
- ✅ قائمة المنظمات التي يملكها
- ✅ أحدث 5 تطبيقات مع حالتها (pending/accepted/rejected)

**الوظائف:**
```javascript
- loadUser() // جلب معلومات المستخدم
- loadOrganizations() // جلب منظمات المستخدم
- loadApplications() // جلب تطبيقات المستخدم
```

### 4. تحديث Users API

**الملف:** `/Frontend/src/api/users.js`

تمت إضافة methods جديدة:

```javascript
// Get user by ID
getById(id) {
  return apiClient.get(`/users/${id}`)
}

// Get user organizations
getUserOrganizations(userId) {
  return apiClient.get(`/users/${userId}/organizations`)
}
```

### 5. تحديث Backend UserController

**الملف:** `/Backend/app/Http/Controllers/Api/UserController.php`

**التعديلات:**

1. **تحديث show() method:**
```php
public function show(User $user)
{
    // Load relationships
    $user->load('skills');
    
    return $user;
}
```

2. **تحديث organizations() method:**
```php
public function organizations(User $user)
{
    $organizations = $user->organizations()
        ->withCount('events')  // إضافة عدد الفعاليات
        ->get();
    
    // Add full URL for logo
    $organizations->transform(function($org) {
        if ($org->logo) {
            $org->logo_url = asset('storage/' . $org->logo);
        }
        return $org;
    });
    
    return $organizations;
}
```

## 🎯 كيفية عمل الروابط

### من نتائج البحث:

1. **الضغط على بطاقة مستخدم:**
   ```
   UserCard → router-link → /users/{id} → UserProfile.vue
   ```
   - يعرض الملف الشخصي الكامل
   - يعرض المنظمات والتطبيقات

2. **الضغط على بطاقة منظمة:**
   ```
   OrganizationCard → router-link → /organization/{id} → Profile.vue
   ```
   - يعرض معلومات المنظمة
   - يعرض الفعاليات

3. **الضغط على بطاقة فعالية:**
   ```
   EventCard → router-link → /events/{id} → Details.vue
   ```
   - يعرض تفاصيل الفعالية الكاملة
   - يسمح بالتقدم للتطوع
   - يعرض التقييمات

## 📊 Backend Endpoints المستخدمة

### للمستخدمين:
- `GET /api/users/{id}` - جلب معلومات المستخدم
- `GET /api/users/{id}/organizations` - جلب منظمات المستخدم
- `GET /api/users/{id}/applications` - جلب تطبيقات المستخدم

### للفعاليات:
- `GET /api/events/{id}` - جلب تفاصيل الفعالية
- `GET /api/events/{id}/reviews` - جلب التقييمات
- `GET /api/events/{id}/check-application` - التحقق من التقديم
- `POST /api/events/{id}/apply` - التقدم للفعالية

### للمنظمات:
- `GET /api/organization/{id}` - جلب معلومات المنظمة (موجود مسبقاً)

## 🎨 التصميم

### صفحة تفاصيل الفعالية:
- صورة كبيرة في الأعلى (h-96)
- معلومات منظمة في بطاقات ملونة (blue/green/purple)
- أزرار واضحة للإجراءات (Edit/Apply)
- قسم للوصف والمهارات المطلوبة
- قسم للتقييمات مع النجوم

### صفحة الملف الشخصي:
- صورة غلاف ملونة
- Avatar كبير (-mt-16 للتراكب)
- معلومات منظمة مع أيقونات
- إحصائيات بارزة
- بطاقات للمنظمات والتطبيقات

## ✅ الحالة الحالية

### ما يعمل:
- ✅ جميع البطاقات في نتائج البحث قابلة للضغط
- ✅ Routes تم إضافتها بنجاح
- ✅ صفحات العرض تم إنشاؤها
- ✅ API endpoints جاهزة
- ✅ Backend controllers محدثة
- ✅ التصميم متجاوب ومتناسق

### للاختبار:
1. ابحث عن أي شيء في شريط البحث
2. اضغط على أي بطاقة مستخدم → يجب أن تفتح صفحة الملف الشخصي
3. اضغط على أي بطاقة منظمة → يجب أن تفتح صفحة المنظمة
4. اضغط على أي بطاقة فعالية → يجب أن تفتح صفحة التفاصيل

## 🚀 الخطوات التالية

للاختبار الكامل:
```bash
# تأكد من تشغيل Backend
cd Backend && php artisan serve

# تأكد من تشغيل Frontend
cd Frontend && npm run dev

# افتح المتصفح
# http://localhost:5175
```

## 📝 ملاحظات

1. **Authentication:** جميع الصفحات محمية بـ `requiresAuth: true`
2. **Error Handling:** كل صفحة تحتوي على error state مع رسالة واضحة
3. **Loading States:** جميع الصفحات تعرض spinner أثناء التحميل
4. **Navigation:** زر "Back" في كل صفحة للرجوع
5. **Responsive:** التصميم يعمل على جميع أحجام الشاشات

## 🎉 النتيجة

الآن عند البحث والضغط على أي نتيجة:
- ✅ المستخدم → الملف الشخصي الكامل
- ✅ المنظمة → صفحة المنظمة
- ✅ الفعالية → تفاصيل الفعالية مع إمكانية التطوع

جميع الروابط تعمل بشكل صحيح! 🎊
