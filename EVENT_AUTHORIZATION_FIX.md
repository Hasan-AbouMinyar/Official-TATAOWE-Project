# Event Authorization Fix - October 1, 2025

## 🐛 Problem
The Edit button was visible to all users (including volunteers) when viewing event details, even though they shouldn't have permission to edit events they don't own. This was a **security and UX issue**.

## 📸 Screenshot
User reported seeing Edit button when viewing an event after clicking notification, even though they were not the organization owner.

## ✅ Solution

### Frontend Changes (`EventDetails.vue`)

#### 1. Load Organization User Relationship
**File**: `Backend/app/Http/Controllers/Api/EventController.php`

Changed from:
```php
$event->load(['organization', 'reviews.user:id,name,photo']);
```

To:
```php
$event->load(['organization.user:id', 'reviews.user:id,name,photo']);
```

This ensures the `user_id` of the organization is included in the API response.

#### 2. Enhanced canEdit Logic
**File**: `Frontend/src/views/Event/EventDetails.vue`

```javascript
const canEdit = computed(() => {
  if (!event.value || !authStore.user) return false
  // Check if current user owns the organization that created this event
  const organizationUserId = event.value.organization?.user_id
  const currentUserId = authStore.user.id
  console.log('canEdit check:', { organizationUserId, currentUserId, match: organizationUserId === currentUserId })
  return organizationUserId === currentUserId
})
```

**Logic**:
- ✅ If not logged in → Hide Edit button
- ✅ If event has no organization → Hide Edit button
- ✅ If current user ID ≠ organization user ID → Hide Edit button
- ✅ Only show Edit button if: `event.organization.user_id === authStore.user.id`

### Backend Security Changes

#### 3. Authorization in update() Method
**File**: `Backend/app/Http/Controllers/Api/EventController.php`

Added ownership check at the start of `update()`:
```php
public function update(Request $request, Event $event)
{
    // Check if the authenticated user owns the organization that created this event
    $user = auth('sanctum')->user();
    if (!$user || !$event->organization || $event->organization->user_id !== $user->id) {
        return response()->json(['message' => 'Unauthorized to edit this event'], 403);
    }
    // ... rest of update logic
}
```

#### 4. Authorization in destroy() Method
Added ownership check at the start of `destroy()`:
```php
public function destroy(Event $event)
{
    // Check if the authenticated user owns the organization that created this event
    $user = auth('sanctum')->user();
    if (!$user || !$event->organization || $event->organization->user_id !== $user->id) {
        return response()->json(['message' => 'Unauthorized to delete this event'], 403);
    }
    // ... rest of delete logic
}
```

## 🔒 Security Improvements

### Frontend (UX Layer)
- ✅ Edit button hidden from non-owners
- ✅ Clear visual feedback (button doesn't appear)
- ✅ Debug logging added for troubleshooting

### Backend (Security Layer)
- ✅ Authorization check in `update()` - Returns 403 Forbidden
- ✅ Authorization check in `destroy()` - Returns 403 Forbidden
- ✅ Authorization already existed in `applications()` - Consistent pattern
- ✅ Prevents API manipulation by unauthorized users

## 📋 Authorization Matrix

| User Type | View Event | Apply to Event | Edit Event | Delete Event | View Applications |
|-----------|------------|----------------|------------|--------------|-------------------|
| **Guest** | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Volunteer** | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Organization Owner** (Different Org) | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Organization Owner** (Same Org) | ✅ | ❌ | ✅ | ✅ | ✅ |

## 🧪 Testing Checklist

### Frontend Tests
- [ ] Login as volunteer
- [ ] View any event (not owned by volunteer)
- [ ] Verify Edit button is **hidden**
- [ ] Login as organization owner
- [ ] View event created by their organization
- [ ] Verify Edit button is **visible**
- [ ] Check browser console for `canEdit check` logs

### Backend Tests
```bash
# Test unauthorized update (should return 403)
curl -X PUT http://localhost:8000/api/events/1 \
  -H "Authorization: Bearer {volunteer_token}" \
  -H "Content-Type: application/json" \
  -d '{"name": "Hacked Event"}'

# Expected: 403 Forbidden
# {"message":"Unauthorized to edit this event"}

# Test authorized update (should return 200)
curl -X PUT http://localhost:8000/api/events/1 \
  -H "Authorization: Bearer {owner_token}" \
  -H "Content-Type: application/json" \
  -d '{"name": "Updated Event"}'

# Expected: 200 OK with updated event data
```

## 📁 Files Changed

### Frontend
1. ✅ `Frontend/src/views/Event/EventDetails.vue`
   - Enhanced `canEdit` computed property
   - Added console logging

### Backend
2. ✅ `Backend/app/Http/Controllers/Api/EventController.php`
   - Added `organization.user:id` to `show()` eager loading
   - Added authorization in `update()` method
   - Added authorization in `destroy()` method

## 🔄 Commits

1. **Commit 1**: `2b556e7`
   ```
   🔒 Fix Edit button visibility - only show for organization owners
   - Load organization.user relationship in EventController
   - Add console.log for debugging canEdit check
   - Ensures Edit button only shows for event creator's organization owner
   - Prevents unauthorized users from seeing edit option
   ```

2. **Commit 2**: `b97b6ec`
   ```
   🔐 Add authorization checks for event update and delete
   - Added ownership check in update() method
   - Added ownership check in destroy() method
   - Ensures only organization owners can edit/delete their events
   - Returns 403 Forbidden if unauthorized
   - Prevents API manipulation by non-owners
   ```

## 🎯 Related Issues Fixed

- ✅ Volunteers see Edit button when they shouldn't
- ✅ No backend authorization for event updates
- ✅ No backend authorization for event deletes
- ✅ Inconsistent authorization pattern (applications had it, but update/delete didn't)

## 🚀 Next Steps

1. **Remove console.log** from production after testing:
   ```javascript
   // Remove this line after debugging:
   console.log('canEdit check:', { organizationUserId, currentUserId, match: organizationUserId === currentUserId })
   ```

2. **Add similar authorization checks** for other resources if needed:
   - Organization updates
   - Application status changes
   - Review submissions

3. **Consider creating a middleware** for organization ownership checks:
   ```php
   // Future enhancement
   class EnsureOrganizationOwnership
   {
       public function handle($request, Closure $next)
       {
           // Check ownership logic
       }
   }
   ```

## 📝 Notes

- This fix implements **defense in depth**: Frontend hides UI + Backend blocks API
- Authorization pattern now consistent across all event operations
- Organization ownership determined by: `event.organization.user_id === current_user.id`
- All changes are backward compatible

---

**Status**: ✅ Completed  
**Date**: October 1, 2025  
**Tested**: Pending user verification  
**Security Level**: High Priority ⚠️
