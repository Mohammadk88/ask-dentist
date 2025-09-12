# HomeController@index Update Summary

## Overview
Updated the `HomeController@index` method to implement guest detection, section assembly, action flags, and intelligent caching as specified.

## ✅ Implementation Details

### 🔍 **Guest Detection**
```php
// Detect guest status via request attributes or auth check
$isGuest = $request->attributes->get('is_guest', !auth()->check());
$user = auth()->user();
$isPatient = $user && $user->role === 'patient';
```

### 📋 **Section Assembly**
The method now assembles sections conditionally:

**Always Included:**
- `stories` - Active stories (20 items)
- `top_clinics` - Top clinics (10 items)  
- `top_doctors` - Top doctors (15 items)
- `before_after` - Featured before/after cases (12 items)

**Conditionally Included:**
- `favorites_doctors` - Only included if user is an authenticated patient
- If user is not a patient, this field is omitted entirely (not empty array)

### 🚀 **Action Flags**
Added `action_flags` object indicating user capabilities:
```php
'action_flags' => [
    'can_favorite' => auth()->check(),
    'can_book' => auth()->check(), 
    'can_message' => auth()->check()
]
```

### 💾 **Intelligent Caching**
Implemented separate cache keys with 60-second TTL:

**Guest Cache:**
```php
$cacheKey = "cache:home:guest:{$locale}";
```

**User Cache:**
```php  
$cacheKey = "cache:home:user:{$user->id}:{$locale}";
```

**Cache Strategy:**
- TTL: 60 seconds
- Locale-based separation
- User-specific caching for personalized content
- Guest cache shared across all guest users

### 🔄 **Cache Invalidation**
Added `invalidateHomeCache()` method for content updates:
```php
public function invalidateHomeCache($locale = null)
{
    // Clears guest and user caches when content is updated
    // Supports specific locale or all locales
}
```

## 📤 **Response Format**

### Guest User Response:
```json
{
  "stories": [...],
  "top_clinics": [...],
  "top_doctors": [...], 
  "before_after": [...],
  "action_flags": {
    "can_favorite": false,
    "can_book": false,
    "can_message": false
  },
  "locale": "en",
  "timestamp": "2025-09-08T...",
  "is_guest": true,
  "user_authenticated": false
}
```

### Authenticated Patient Response:
```json
{
  "stories": [...],
  "top_clinics": [...],
  "top_doctors": [...],
  "before_after": [...],
  "favorites_doctors": [...],
  "action_flags": {
    "can_favorite": true,
    "can_book": true,
    "can_message": true
  },
  "locale": "en", 
  "timestamp": "2025-09-08T...",
  "is_guest": false,
  "user_authenticated": true
}
```

### Authenticated Doctor Response:
```json
{
  "stories": [...],
  "top_clinics": [...],
  "top_doctors": [...],
  "before_after": [...],
  // Note: No favorites_doctors field for non-patients
  "action_flags": {
    "can_favorite": true,
    "can_book": true,
    "can_message": true
  },
  "locale": "en",
  "timestamp": "2025-09-08T...",
  "is_guest": false,
  "user_authenticated": true
}
```

## 🔧 **Technical Implementation**

### Dependencies Added:
- `use Illuminate\Support\Facades\Cache;`

### Key Features:
1. **Conditional Content**: Favorites only for patients
2. **Smart Caching**: User-specific vs guest-shared cache
3. **Action Flags**: Frontend can determine UI state
4. **Guest Detection**: Multiple fallback methods
5. **Locale Support**: Cache separation by language
6. **Cache Management**: Invalidation method for content updates

### Performance Benefits:
- 60-second cache reduces database queries
- Separate guest/user caches optimize for different use cases
- Locale-based caching supports internationalization
- Resource collections provide consistent API responses

### Security Considerations:
- Guest users cannot access favorites data
- Action flags clearly indicate permissions
- User-specific caching prevents data leakage
- Authentication checks before accessing user data

## 🎯 **Frontend Integration Points**

### 1. Guest Mode Detection:
```javascript
if (response.is_guest) {
  // Show guest UI elements
  // Hide favorites section
  // Show login prompts for actions
}
```

### 2. Action Flags Usage:
```javascript
if (response.action_flags.can_favorite) {
  // Show favorite buttons
} else {
  // Show "Login to favorite" prompts
}
```

### 3. Conditional Rendering:
```javascript
// Only render favorites if present
if (response.favorites_doctors) {
  // Render favorites section
}
```

### 4. Cache Awareness:
- Frontend can implement optimistic updates
- 60-second cache means fresh content every minute
- Locale changes trigger different cache keys

The HomeController now provides a robust foundation for guest mode browsing while maintaining personalized experiences for authenticated users and optimal performance through intelligent caching.