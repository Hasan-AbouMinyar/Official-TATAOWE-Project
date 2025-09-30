# 🔍 نظام البحث - منصة تطوع

## نظرة عامة

نظام بحث شامل يدعم:
- ✅ **البحث الشامل** - البحث في جميع الكيانات (مستخدمين، منظمات، فعاليات)
- ✅ **البحث المتخصص** - البحث في نوع واحد فقط
- ✅ **البحث المتقدم** - مع فلاتر إضافية
- ✅ **الاقتراحات التلقائية** (Autocomplete) - أثناء الكتابة
- ✅ **Pagination** - لنتائج كبيرة
- ✅ **Real-time** - نتائج فورية

---

## 📋 API Endpoints

### 1. البحث الشامل
```http
GET /api/search?q={query}&limit={limit}

Parameters:
- q: نص البحث (required, min: 2 characters)
- limit: عدد النتائج لكل نوع (optional, default: 10)

Response:
{
  "users": [
    {
      "id": 24,
      "name": "amg",
      "username": "abouminyar",
      "email": "abouminyar@gmail.com",
      "photo": "/storage/..."
    }
  ],
  "organizations": [...],
  "events": [...],
  "total": 15
}
```

### 2. البحث عن مستخدمين
```http
GET /api/search/users?q={query}&page={page}&per_page={perPage}

Parameters:
- q: نص البحث (required, min: 2 characters)
- page: رقم الصفحة (optional, default: 1)
- per_page: عدد النتائج في الصفحة (optional, default: 15)

Response:
{
  "data": [...],
  "current_page": 1,
  "last_page": 3,
  "total": 42,
  "per_page": 15
}
```

### 3. البحث عن منظمات
```http
GET /api/search/organizations?q={query}&page={page}&per_page={perPage}

Parameters:
- نفس البحث عن مستخدمين

Response:
{
  "data": [
    {
      "id": 5,
      "name": "منظمة تطوع",
      "description": "...",
      "user": {
        "id": 24,
        "name": "amg",
        "photo": "..."
      },
      "events_count": 5
    }
  ],
  "current_page": 1,
  "last_page": 2,
  "total": 18
}
```

### 4. البحث عن فعاليات
```http
GET /api/search/events?q={query}&page={page}&per_page={perPage}

Parameters:
- نفس البحث عن مستخدمين

Response:
{
  "data": [
    {
      "id": 28,
      "name": "Netflix.v2",
      "description": "...",
      "location": "Tripoli",
      "start_time": "2025-10-15 10:00:00",
      "end_time": "2025-10-15 16:00:00",
      "organization": {
        "id": 5,
        "name": "منظمة تطوع"
      },
      "applications_count": 12
    }
  ],
  "current_page": 1,
  "last_page": 2,
  "total": 25
}
```

### 5. البحث المتقدم
```http
GET /api/search/advanced?q={query}&type={type}&filters={filters}

Parameters:
- q: نص البحث (required)
- type: نوع البحث (all, users, organizations, events)
- filters: فلاتر إضافية (JSON object)

Filters Examples:

For Users:
{
  "has_organizations": true,
  "has_applications": true
}

For Organizations:
{
  "has_events": true
}

For Events:
{
  "upcoming": true,      // فقط الفعاليات القادمة
  "past": false,        // فقط الفعاليات السابقة
  "location": "Tripoli",
  "organization_id": 5
}
```

### 6. الاقتراحات التلقائية (Autocomplete)
```http
GET /api/search/suggestions?q={query}&limit={limit}

Parameters:
- q: نص البحث (required, min: 2 characters)
- limit: عدد الاقتراحات (optional, default: 5)

Response:
[
  {
    "type": "user",
    "id": 24,
    "text": "amg",
    "subtext": "@abouminyar",
    "photo": "/storage/...",
    "url": "/users/24"
  },
  {
    "type": "organization",
    "id": 5,
    "text": "منظمة تطوع",
    "subtext": "منظمة",
    "photo": "/storage/...",
    "url": "/organizations/5"
  },
  {
    "type": "event",
    "id": 28,
    "text": "Netflix.v2",
    "subtext": "2025-10-15",
    "photo": "/storage/...",
    "url": "/events/28"
  }
]
```

---

## 🎨 Frontend Components

### 1. SearchBar Component
**الموقع:** `src/components/search/SearchBar.vue`

**المميزات:**
- 🔍 بحث مباشر أثناء الكتابة
- 📝 اقتراحات تلقائية (Autocomplete)
- ⌨️ تنقل بالكيبورد (Arrow keys, Enter, Esc)
- 🎯 تصنيف النتائج حسب النوع (مستخدم، منظمة، فعالية)
- 🖼️ عرض صور أو أيقونات افتراضية
- ⚡ Debouncing (300ms) - لتقليل الطلبات

**الاستخدام:**
```vue
<template>
  <SearchBar />
</template>

<script setup>
import SearchBar from '@/components/search/SearchBar.vue'
</script>
```

### 2. SearchResults Page
**الموقع:** `src/views/SearchResults.vue`

**المميزات:**
- 📑 تبويبات (All, Users, Organizations, Events)
- 🔢 عدد النتائج لكل تبويب
- 📄 Pagination للنتائج الكبيرة
- 🎨 عرض مخصص لكل نوع
- ⚡ Loading state
- 🚫 Empty state عند عدم وجود نتائج

**الروابط:**
```javascript
// router/index.js
{
  path: '/search',
  name: 'SearchResults',
  component: () => import('@/views/SearchResults.vue'),
  meta: { requiresAuth: true }
}
```

### 3. Display Components

#### UserCard
**الموقع:** `src/components/users/UserCard.vue`

**العرض:**
- صورة المستخدم أو حرف أول
- اسم المستخدم
- Username (@...)
- البريد الإلكتروني

#### OrganizationCard
**الموقع:** `src/components/organizations/OrganizationCard.vue`

**العرض:**
- شعار المنظمة
- اسم المنظمة
- المالك (بواسطة ...)
- الوصف (2 سطور)
- عدد الفعاليات

#### EventCard
**الموقع:** `src/components/events/EventCard.vue`

**العرض:**
- صورة الفعالية
- اسم الفعالية
- اسم المنظمة
- التاريخ والموقع
- عدد المتطوعين

---

## 🗄️ Pinia Store

**الموقع:** `src/stores/search.js`

### State
```javascript
{
  query: '',              // نص البحث الحالي
  results: {             // النتائج
    users: [],
    organizations: [],
    events: [],
    total: 0
  },
  suggestions: [],       // الاقتراحات
  loading: false,        // حالة التحميل
  currentTab: 'all',     // التبويب الحالي
  filters: {},           // الفلاتر المطبقة
  currentPage: 1,        // الصفحة الحالية
  lastPage: 1,           // آخر صفحة
  total: 0,              // إجمالي النتائج
  perPage: 15            // عدد النتائج في الصفحة
}
```

### Getters
```javascript
hasResults      // هل توجد نتائج؟
isEmpty         // هل البحث فارغ؟
```

### Actions
```javascript
searchAll()                          // بحث شامل
searchByType(type, page)             // بحث متخصص
advancedSearch(filters)              // بحث متقدم
fetchSuggestions(query)              // جلب الاقتراحات
clearResults()                       // مسح النتائج
clearSearch()                        // مسح البحث بالكامل
setQuery(query)                      // تعيين نص البحث
setTab(tab)                          // تغيير التبويب
nextPage()                           // الصفحة التالية
prevPage()                           // الصفحة السابقة
goToPage(page)                       // الذهاب لصفحة محددة
```

### الاستخدام
```javascript
import { useSearchStore } from '@/stores/search'

const searchStore = useSearchStore()

// بحث شامل
searchStore.setQuery('Netflix')
await searchStore.searchAll()

// بحث عن مستخدمين فقط
await searchStore.searchByType('users', 1)

// جلب الاقتراحات
await searchStore.fetchSuggestions('am')

// تصفح الصفحات
searchStore.nextPage()
searchStore.prevPage()
searchStore.goToPage(3)
```

---

## 💡 أمثلة الاستخدام

### 1. البحث من شريط التنقل
```vue
<template>
  <Navigation>
    <SearchBar />
  </Navigation>
</template>
```

### 2. البحث البرمجي
```javascript
import { useSearchStore } from '@/stores/search'

const searchStore = useSearchStore()

// بحث وعرض النتائج
async function performSearch(query) {
  searchStore.setQuery(query)
  await searchStore.searchAll()
  
  if (searchStore.hasResults) {
    console.log('Found:', searchStore.results.total, 'results')
  }
}
```

### 3. بحث متقدم بفلاتر
```javascript
// بحث عن فعاليات قادمة في طرابلس
await searchStore.advancedSearch({
  upcoming: true,
  location: 'Tripoli'
})
```

---

## 🔧 كيفية البحث

### في Backend (Laravel)
```php
// SearchController@searchAll
$users = User::where('name', 'LIKE', "%{$query}%")
    ->orWhere('username', 'LIKE', "%{$query}%")
    ->orWhere('email', 'LIKE', "%{$query}%")
    ->limit($limit)
    ->get();
```

### في Frontend (Vue.js)
```javascript
// Debounced search with vueuse
import { useDebounceFn } from '@vueuse/core'

const debouncedSearch = useDebounceFn(() => {
  if (query.value.length >= 2) {
    searchStore.fetchSuggestions(query.value)
  }
}, 300)
```

---

## ✅ الميزات المتقدمة

### 1. Keyboard Navigation
- ⬇️ **Arrow Down** - الاقتراح التالي
- ⬆️ **Arrow Up** - الاقتراح السابق
- ↵ **Enter** - فتح الاقتراح أو عرض كل النتائج
- **Esc** - إغلاق الاقتراحات

### 2. Click Outside
- النقر خارج منطقة البحث يغلق الاقتراحات

### 3. Real-time Updates
- نتائج فورية أثناء الكتابة (مع Debouncing)

### 4. Empty State
- رسالة واضحة عند عدم وجود نتائج

### 5. Loading State
- مؤشر تحميل أثناء البحث

---

## 🎯 حالات الاستخدام

### 1. بحث سريع
المستخدم يكتب في شريط البحث → يظهر اقتراحات → ينقر على نتيجة

### 2. بحث كامل
المستخدم يكتب → يضغط Enter → ينتقل لصفحة النتائج → يتصفح النتائج

### 3. بحث متقدم
المستخدم يفتح صفحة البحث → يختار نوع محدد → يطبق فلاتر → يعرض النتائج

### 4. تصفح الصفحات
المستخدم يبحث → يعرض النتائج → ينتقل بين الصفحات

---

## 📊 الأداء

### Optimizations
- ✅ **Debouncing** (300ms) - تقليل الطلبات
- ✅ **Pagination** - تحميل تدريجي
- ✅ **Limit Results** - حد أقصى للنتائج
- ✅ **Select Only Needed Fields** - تقليل حجم البيانات
- ✅ **Lazy Loading** - تحميل Components عند الحاجة

### Performance Tips
- استخدام `LIKE` بحذر (يمكن استخدام Full-Text Search للمشاريع الكبيرة)
- إضافة indexes على الحقول المستخدمة في البحث
- استخدام Cache للنتائج الشائعة

---

## 🎉 الخلاصة

**نظام بحث متكامل ومرن يدعم:**
- ✅ البحث في جميع أنواع الكيانات
- ✅ اقتراحات تلقائية
- ✅ تنقل بالكيبورد
- ✅ Pagination
- ✅ واجهة مستخدم جذابة
- ✅ أداء محسّن

**جاهز للاستخدام الآن! 🚀**
