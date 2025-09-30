# إصلاح مشكلة ظهور زر التعديل للمتطوعين

## 🐛 المشكلة
كان زر "Edit" (التعديل) يظهر لجميع المستخدمين عند عرض تفاصيل الفعالية، حتى المتطوعين الذين لا يملكون صلاحية التعديل. هذا خطأ أمني وتجربة مستخدم سيئة.

## ✅ الحل المُطبق

### 1. إصلاح Frontend (واجهة المستخدم)

#### أ) تحميل بيانات صاحب المنظمة
في `EventController.php`، أضفنا تحميل `user_id` الخاص بالمنظمة:

```php
// قبل:
$event->load(['organization', 'reviews.user:id,name,photo']);

// بعد:
$event->load(['organization.user:id', 'reviews.user:id,name,photo']);
```

#### ب) تحسين منطق canEdit
في `EventDetails.vue`، أصبح الكود يتحقق بدقة من الصلاحيات:

```javascript
const canEdit = computed(() => {
  if (!event.value || !authStore.user) return false
  // التحقق من أن المستخدم الحالي يملك المنظمة التي أنشأت الفعالية
  const organizationUserId = event.value.organization?.user_id
  const currentUserId = authStore.user.id
  return organizationUserId === currentUserId
})
```

**المنطق**:
- ❌ إذا لم يكن مسجل دخول → إخفاء زر التعديل
- ❌ إذا لم يكن للفعالية منظمة → إخفاء زر التعديل  
- ❌ إذا كان ID المستخدم ≠ ID صاحب المنظمة → إخفاء زر التعديل
- ✅ فقط إظهار زر التعديل إذا: `event.organization.user_id === authStore.user.id`

### 2. إصلاح Backend (الأمان)

#### أ) فحص الصلاحيات في update()
أضفنا فحص أمني في بداية دالة `update()`:

```php
public function update(Request $request, Event $event)
{
    // التحقق من أن المستخدم الحالي يملك المنظمة التي أنشأت الفعالية
    $user = auth('sanctum')->user();
    if (!$user || !$event->organization || $event->organization->user_id !== $user->id) {
        return response()->json(['message' => 'Unauthorized to edit this event'], 403);
    }
    // ... باقي كود التحديث
}
```

#### ب) فحص الصلاحيات في destroy()
نفس الفحص في دالة الحذف:

```php
public function destroy(Event $event)
{
    // التحقق من أن المستخدم الحالي يملك المنظمة التي أنشأت الفعالية
    $user = auth('sanctum')->user();
    if (!$user || !$event->organization || $event->organization->user_id !== $user->id) {
        return response()->json(['message' => 'Unauthorized to delete this event'], 403);
    }
    // ... باقي كود الحذف
}
```

## 🔒 التحسينات الأمنية

### Frontend (طبقة تجربة المستخدم)
- ✅ زر التعديل مخفي عن غير الملّاك
- ✅ تجربة مستخدم واضحة (الزر لا يظهر أصلاً)
- ✅ إضافة console.log لتسهيل التشخيص

### Backend (طبقة الأمان)
- ✅ فحص الصلاحيات في `update()` - يرجع خطأ 403
- ✅ فحص الصلاحيات في `destroy()` - يرجع خطأ 403
- ✅ فحص الصلاحيات كان موجوداً بالفعل في `applications()` - نمط متسق
- ✅ منع التلاعب بالـ API من مستخدمين غير مصرح لهم

## 📋 جدول الصلاحيات

| نوع المستخدم | عرض الفعالية | التقديم للفعالية | تعديل الفعالية | حذف الفعالية | عرض الطلبات |
|--------------|---------------|-------------------|-----------------|---------------|-------------|
| **زائر** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **متطوع** | ✅ | ✅ | ❌ | ❌ | ❌ |
| **صاحب منظمة** (منظمة أخرى) | ✅ | ✅ | ❌ | ❌ | ❌ |
| **صاحب منظمة** (نفس المنظمة) | ✅ | ❌ | ✅ | ✅ | ✅ |

## 🎯 ما تم إصلاحه

- ✅ المتطوعون كانوا يرون زر التعديل → **تم الإصلاح**
- ✅ لم يكن هناك فحص أمني للتحديث في الـ Backend → **تم الإصلاح**
- ✅ لم يكن هناك فحص أمني للحذف في الـ Backend → **تم الإصلاح**
- ✅ نمط الصلاحيات غير متسق → **تم توحيده**

## 📁 الملفات المعدلة

### Frontend
1. ✅ `Frontend/src/views/Event/EventDetails.vue`
   - تحسين `canEdit` computed property
   - إضافة console logging للتشخيص

### Backend
2. ✅ `Backend/app/Http/Controllers/Api/EventController.php`
   - إضافة `organization.user:id` في `show()`
   - إضافة فحص الصلاحيات في `update()`
   - إضافة فحص الصلاحيات في `destroy()`

## 🔄 الـ Commits

1. **Commit 1**: `2b556e7`
   ```
   🔒 Fix Edit button visibility - only show for organization owners
   ```

2. **Commit 2**: `b97b6ec`
   ```
   🔐 Add authorization checks for event update and delete
   ```

3. **Commit 3**: `729cfd9`
   ```
   📚 Add documentation for event authorization fix
   ```

## 🧪 كيفية الاختبار

### 1. اختبار Frontend
1. سجل دخول كمتطوع
2. اذهب لأي فعالية (ليست من منظمتك)
3. تحقق من أن زر "Edit" **مخفي**
4. سجل دخول كصاحب منظمة
5. اذهب لفعالية من منظمتك
6. تحقق من أن زر "Edit" **ظاهر**

### 2. اختبار Backend
افتح Chrome DevTools → Console وجرب تحديث فعالية لا تملكها:
- يجب أن ترجع خطأ 403: `"Unauthorized to edit this event"`

## 🚀 الخطوات التالية

1. ❌ **إزالة console.log** من الكود بعد التأكد من عمل كل شيء
2. ⚠️ تطبيق نفس النمط على موارد أخرى إذا لزم الأمر
3. 💡 النظر في إنشاء middleware للصلاحيات

## 📝 ملاحظات مهمة

- ✅ الإصلاح يطبق **الحماية المزدوجة**: Frontend يخفي الزر + Backend يمنع الوصول
- ✅ نمط الصلاحيات أصبح متسقاً في جميع عمليات الفعاليات
- ✅ ملكية المنظمة تُحدد بـ: `event.organization.user_id === current_user.id`
- ✅ جميع التغييرات متوافقة مع الكود السابق

---

**الحالة**: ✅ مكتمل  
**التاريخ**: 1 أكتوبر 2025  
**الاختبار**: بانتظار التحقق من المستخدم  
**مستوى الأمان**: أولوية عالية ⚠️
