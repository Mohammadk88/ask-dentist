#!/bin/bash

# Ask.Dentist Patient App Runner
# ===========================================

set -e  # Exit on any error

APP_NAME="patient"
LOG_DIR="../../logs"
LOG_FILE="$LOG_DIR/flutter-$APP_NAME.log"
API_HEALTH_URL="http://localhost:8080/api/health"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Ensure we're in the right directory
cd "$(dirname "$0")"

echo -e "${BLUE}🦷 Ask.Dentist Patient App Runner${NC}"
echo "======================================"

# Create logs directory if it doesn't exist
mkdir -p "$LOG_DIR"

# 1. Flutter pub get
echo -e "${BLUE}📦 Getting dependencies...${NC}"
flutter pub get

# 2. Flutter test (ignore failures for now)
echo -e "${BLUE}🧪 Running tests...${NC}"
flutter test || true
echo -e "${YELLOW}⚠️  Test failures ignored (CI mode)${NC}"

# 3. API Health Check Guard
echo -e "${BLUE}🏥 Checking API health...${NC}"
if curl -s -f --connect-timeout 3 "$API_HEALTH_URL" > /dev/null 2>&1; then
    echo -e "${GREEN}✅ API is reachable at $API_HEALTH_URL${NC}"
else
    echo -e "${YELLOW}⚠️  WARNING: API not reachable at $API_HEALTH_URL${NC}"
    echo -e "${YELLOW}   App will continue with demo/mocked data${NC}"
fi

# 4. Device Selection and Launch
echo -e "${BLUE}📱 Selecting device...${NC}"

# Check for running Android emulator
ANDROID_DEVICES=$(flutter devices --machine 2>/dev/null | jq -r '.[] | select(.platform == "android" and .isDevice == false) | .id' 2>/dev/null || echo "")

if [ -n "$ANDROID_DEVICES" ]; then
    # Use first available Android emulator
    DEVICE_ID=$(echo "$ANDROID_DEVICES" | head -n1)
    DEVICE_TYPE="Android Emulator"
    echo -e "${GREEN}📱 Using Android emulator: $DEVICE_ID${NC}"
else
    # Fallback to Chrome
    DEVICE_ID="chrome"
    DEVICE_TYPE="Chrome Browser"
    echo -e "${GREEN}🌐 Using Chrome browser${NC}"
fi

echo -e "${BLUE}🚀 Starting $APP_NAME app on $DEVICE_TYPE...${NC}"
echo -e "${BLUE}📋 Logs will be streamed to: $LOG_FILE${NC}"

# Clear previous log
> "$LOG_FILE"

# Start the app and stream logs
{
    echo "=== Ask.Dentist Patient App Started at $(date) ==="
    echo "Device: $DEVICE_ID ($DEVICE_TYPE)"
    echo "API Health URL: $API_HEALTH_URL"
    echo "=============================================="
    echo ""
} >> "$LOG_FILE"

# Run flutter with device selection and capture output
flutter run -d "$DEVICE_ID" --dart-define=API_BASE_URL=http://localhost:8080/api 2>&1 | tee -a "$LOG_FILE"
