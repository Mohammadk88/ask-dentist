# Ask.Dentist MVP Scripts

Quick health checks and development utilities.

## Smoke Tests (`smoke.sh`)

Quick health checks for all critical endpoints and services.

### Usage

```bash
# Run via orchestrator (recommended)
./bin/dev smoke

# Run directly
scripts/smoke.sh
```

### Tests Performed

1. **API Health Check** - `GET /api/health`
   - Verifies backend API is responding
   - Checks database connectivity

2. **Guest Home API** - `GET /api/home`
   - Tests guest browsing functionality
   - Validates `is_guest=true` in response

3. **Admin Panel Access** - `HEAD /admin`
   - Verifies admin panel is accessible
   - Expects 200 (if logged in) or 302 (redirect to login)

4. **Doctor Portal Access** - `HEAD /doctor`
   - Verifies doctor portal is accessible
   - Expects 200 (if logged in) or 302 (redirect to login)

### Exit Codes

- `0` - All tests passed (SMOKE: OK)
- `1` - One or more tests failed

## Enhanced Logging

The `./bin/dev logs` command provides intelligent error detection and suggestions:

### Error Pattern Detection

- **APP_KEY issues** → `Hint: docker compose exec php php artisan key:generate`
- **Database connection** → `Hint: check DB env, wait-for Postgres, rerun migrate`
- **Encryption key** → `Hint: php artisan key:generate`
- **Class not found** → `Hint: composer dump-autoload; php artisan optimize:clear`
- **CSRF errors** → `Hint: clear cookies, check APP_URL`
- **Redis issues** → `Hint: ensure predis installed and redis container up`

### Colorized Output

- 🔴 **Red**: Errors, Critical, Fatal, Exceptions
- 🟡 **Yellow**: Warnings
- 🔵 **Blue**: Info, Notice
- 🟢 **Green**: Hints and suggestions

### Commands

```bash
./bin/dev logs      # Smart logs with error detection
./bin/dev logs-all  # All logs (unfiltered)
```

## Requirements

- `curl` - For HTTP requests
- `jq` - For JSON parsing
- `docker compose` - For container management
- Services running on `localhost:8080`

## Example Output

```bash
🔍 Running Ask.Dentist smoke tests...
1. Testing API health endpoint...
✅ API health check passed
2. Testing guest home API...
✅ Guest home API passed
3. Testing admin panel access...
✅ Admin panel access passed
4. Testing doctor portal access...
✅ Doctor portal access passed

🎉 SMOKE: OK
All smoke tests passed successfully!
```
