# 🔔 نظام الإشعارات - منصة تطوع

## نظرة عامة

نظام إشعارات شامل يدعم:
- ✅ **إشعارات قاعدة البيانات** - تظهر في الواجهة الأمامية
- ✅ **إشعارات البريد الإلكتروني** - تُرسل إلى البريد الإلكتروني
- ✅ **تحديثات آنية** - Polling كل 30 ثانية
- ✅ **تصنيف بالألوان والأيقونات**

---

## 📋 جدول الإشعارات

| # | الحدث | الإشعار | المستلمون | القناة | الأيقونة | اللون |
|---|-------|---------|-----------|--------|---------|-------|
| 1 | **تقديم طلب تطوع** | ApplicationSubmitted | المتطوع | DB + Email | ✉️ mail | أزرق |
| 2 | **استلام طلب جديد** | NewApplicationReceived | صاحب المنظمة | DB + Email | ✉️ mail | أزرق |
| 3 | **قبول طلب** | ApplicationStatusChanged | المتطوع + صاحب المنظمة | DB + Email | ✅ check-circle | أخضر |
| 4 | **رفض طلب** | ApplicationStatusChanged | المتطوع + صاحب المنظمة | DB + Email | ❌ x-circle | أحمر |
| 5 | **طلب معلق** | ApplicationStatusChanged | المتطوع + صاحب المنظمة | DB + Email | ⏱ clock | أصفر |
| 6 | **تقييم جديد** | NewReviewReceived | صاحب المنظمة | DB + Email | ⭐ star | ذهبي |
| 7 | **إنشاء فعالية** | EventCreated | صاحب المنظمة | DB + Email | ✅ check-circle | أخضر |
| 8 | **تحديث فعالية** | EventUpdated | صاحب المنظمة + جميع المتقدمين | DB + Email | 📅 calendar | أزرق |

---

## 🎯 تفاصيل كل إشعار

### 1️⃣ تقديم طلب تطوع (ApplicationSubmitted)

**الحدث:** عندما يتقدم متطوع لفعالية

**المستلم:** المتطوع نفسه (تأكيد)

**الرسالة:**
- 🇦🇷 عربي: "تم تقديم طلبك للفعالية: [اسم الفعالية]"
- 📧 البريد: "تم تقديم طلبك بنجاح - [اسم الفعالية]"

**الكود:**
```php
// في: routes/api.php - POST /events/{event}/apply
$user->notify(new ApplicationSubmitted($application));
```

---

### 2️⃣ استلام طلب جديد (NewApplicationReceived)

**الحدث:** عندما تستلم المنظمة طلب تطوع جديد

**المستلم:** صاحب المنظمة

**الرسالة:**
- 🇦🇷 عربي: "لديك طلب جديد للفعالية: [اسم الفعالية]، المتقدم: [اسم المتطوع]"
- 📧 البريد: "طلب جديد على فعاليتك - [اسم الفعالية]"

**الكود:**
```php
// في: routes/api.php - POST /events/{event}/apply
$event->organization->user->notify(new NewApplicationReceived($application));

// في: ApplicationController@store
$application->event->organization->user->notify(new NewApplicationReceived($application));
```

---

### 3️⃣ تحديث حالة الطلب (ApplicationStatusChanged)

**الحدث:** عندما تقبل/ترفض المنظمة طلب تطوع

**المستلمون:** 
- المتطوع (صاحب الطلب)
- صاحب المنظمة (تأكيد)

**الرسائل:**

**للمتطوع:**
- ✅ قبول: "مبروك! تم قبول طلبك"
- ❌ رفض: "للأسف، لم يتم قبول طلبك هذه المرة"
- ⏱ معلق: "طلبك قيد المراجعة"

**للمنظمة:**
- ✅ قبول: "لقد قبلت طلب [اسم المتطوع] للفعالية [اسم الفعالية]"
- ❌ رفض: "لقد رفضت طلب [اسم المتطوع] للفعالية [اسم الفعالية]"

**الكود:**
```php
// في: routes/api.php - PATCH /applications/{application}/status
$application->user->notify(new ApplicationStatusChanged($application, $status));
$orgOwner->notify(new ApplicationStatusChanged($application, $status, true));

// في: ApplicationController@update
$application->user->notify(new ApplicationStatusChanged($application, $status));
$application->event->organization->user->notify(
    new ApplicationStatusChanged($application, $status, true)
);
```

---

### 4️⃣ تقييم جديد (NewReviewReceived)

**الحدث:** عندما يضع متطوع تقييم على فعالية

**المستلم:** صاحب المنظمة

**الرسالة:**
- 🇦🇷 عربي: "[اسم المتطوع] قام بتقييم فعاليتك ⭐⭐⭐⭐⭐"
- 📧 البريد: "تقييم جديد على فعاليتك - [اسم الفعالية]"

**الكود:**
```php
// في: routes/api.php - POST /events/{event}/reviews
$event->organization->user->notify(new NewReviewReceived($review));
```

---

### 5️⃣ إنشاء فعالية (EventCreated)

**الحدث:** عندما تنشئ منظمة فعالية جديدة

**المستلم:** صاحب المنظمة (تأكيد)

**الرسالة:**
- 🇦🇷 عربي: "تم إنشاء فعاليتك بنجاح: [اسم الفعالية]"
- 📧 البريد: "تم إنشاء فعالية جديدة - [اسم الفعالية]"

**الكود:**
```php
// في: EventController@store
$user->notify(new EventCreated($event));
```

---

### 6️⃣ تحديث فعالية (EventUpdated)

**الحدث:** عندما تُحدث منظمة فعالية

**المستلمون:**
- صاحب المنظمة
- **جميع المتطوعين المتقدمين** للفعالية

**الرسالة:**
- 🇦🇷 عربي: "تم تحديث الفعالية: [اسم الفعالية]"
- 📧 البريد: "تحديث في الفعالية - [اسم الفعالية]"

**الكود:**
```php
// في: EventController@update
// إرسال للمالك
$user->notify(new EventUpdated($event));

// إرسال لجميع المتقدمين
$applicants = $event->applications()->with('user')->get()->pluck('user')->unique('id');
foreach ($applicants as $applicant) {
    $applicant->notify(new EventUpdated($event));
}
```

---

## 🛠️ API Endpoints

### الحصول على الإشعارات
```http
GET /api/notifications
Authorization: Bearer {token}

Response:
{
  "notifications": [...],
  "unread_count": 5,
  "current_page": 1,
  "last_page": 2,
  "total": 22
}
```

### عدد الإشعارات غير المقروءة
```http
GET /api/notifications/unread-count
Authorization: Bearer {token}

Response:
{
  "count": 5
}
```

### تحديد إشعار كمقروء
```http
POST /api/notifications/{id}/read
Authorization: Bearer {token}

Response:
{
  "message": "Notification marked as read"
}
```

### تحديد جميع الإشعارات كمقروءة
```http
POST /api/notifications/mark-all-read
Authorization: Bearer {token}

Response:
{
  "message": "All notifications marked as read"
}
```

### حذف إشعار
```http
DELETE /api/notifications/{id}
Authorization: Bearer {token}

Response: 204 No Content
```

### حذف جميع الإشعارات المقروءة
```http
DELETE /api/notifications/clear-read
Authorization: Bearer {token}

Response:
{
  "message": "Read notifications cleared"
}
```

---

## 🎨 الواجهة الأمامية

### Notification Store (Pinia)

```javascript
import { useNotificationStore } from '@/stores/notification'

const notificationStore = useNotificationStore()

// الحصول على الإشعارات
await notificationStore.fetchNotifications()

// عدد غير المقروءة
console.log(notificationStore.unreadCount)

// تحديد كمقروء
await notificationStore.markAsRead(notificationId)

// بدء Polling
notificationStore.startPolling()

// إيقاف Polling
notificationStore.stopPolling()
```

### مكونات الواجهة

1. **NotificationDropdown.vue** - القائمة المنسدلة في شريط التنقل
2. **NotificationItem.vue** - عنصر إشعار واحد
3. **Notifications.vue** - صفحة الإشعارات الكاملة

---

## 📧 إعداد البريد الإلكتروني

### للتطوير (Mailtrap)

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS="noreply@tatawoe.com"
MAIL_FROM_NAME="منصة تطوع"
```

### للإنتاج (Gmail)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tatawoe.com"
MAIL_FROM_NAME="منصة تطوع"
```

### عرض في Log فقط

```env
MAIL_MAILER=log
```

---

## ✅ الاختبارات

تم إنشاء اختبارات شاملة في:
```
tests/Feature/NotificationTest.php
```

**تشغيل الاختبارات:**
```bash
php artisan test --filter NotificationTest
```

**الاختبارات المتوفرة:**
- ✅ إشعار عند التقديم على فعالية
- ✅ إشعار عند تغيير حالة الطلب
- ✅ إشعار عند إضافة تقييم
- ✅ إشعار عند إنشاء فعالية
- ✅ إشعار عند تحديث فعالية

---

## 🔍 التحقق من الإشعارات

### في قاعدة البيانات
```sql
-- جميع الإشعارات
SELECT * FROM notifications;

-- حسب المستخدم
SELECT * FROM notifications WHERE notifiable_id = 24;

-- غير المقروءة فقط
SELECT * FROM notifications WHERE read_at IS NULL;

-- حسب النوع
SELECT * FROM notifications 
WHERE JSON_EXTRACT(data, '$.type') = 'application_status';
```

### في ملف Log
```bash
tail -f storage/logs/laravel.log | grep "Subject:"
```

---

## 📊 إحصائيات

**عدد أنواع الإشعارات:** 7 أنواع

**عدد الملفات المنشأة:**
- Backend: 13 ملف
- Frontend: 5 ملفات
- Tests: 1 ملف

**التغطية الكاملة:**
- ✅ تطبيق للفعالية
- ✅ قبول/رفض الطلبات
- ✅ التقييمات
- ✅ إدارة الفعاليات
- ✅ تحديثات آنية

---

## 🎯 الخلاصة

نظام إشعارات متكامل يغطي **جميع العمليات الرئيسية** في المنصة:
- ✅ تقديم + قبول/رفض الطلبات
- ✅ التقييمات
- ✅ إدارة الفعاليات
- ✅ تحديثات آنية كل 30 ثانية
- ✅ دعم قاعدة البيانات والبريد الإلكتروني
- ✅ واجهة مستخدم جذابة وسهلة الاستخدام

**كل عملية في المنصة الآن ترسل إشعارات! 🎉**
