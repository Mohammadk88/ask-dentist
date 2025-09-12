#!/bin/bash

# Ask.Dentist MVP Smoke Tests
# ============================
# Quick health checks for all critical endpoints

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}🔍 Running Ask.Dentist smoke tests...${NC}"

# Test 1: API Health Check
echo -e "${BLUE}1. Testing API health endpoint...${NC}"
curl -sf http://localhost:8080/api/health || {
    echo -e "${RED}❌ API health check failed${NC}"
    exit 1
}
echo -e "${GREEN}✅ API health check passed${NC}"

# Test 2: Guest Home API
echo -e "${BLUE}2. Testing guest home API...${NC}"
curl -sf http://localhost:8080/api/home | jq -e '.is_guest==true' || {
    echo -e "${RED}❌ Guest home API failed${NC}"
    exit 1
}
echo -e "${GREEN}✅ Guest home API passed${NC}"

# Test 3: Admin Panel Access
echo -e "${BLUE}3. Testing admin panel access...${NC}"
curl -sI http://localhost:8080/admin | grep -E "302|200" || {
    echo -e "${RED}❌ Admin panel access failed${NC}"
    exit 1
}
echo -e "${GREEN}✅ Admin panel access passed${NC}"

# Test 4: Doctor Portal Access
echo -e "${BLUE}4. Testing doctor portal access...${NC}"
curl -sI http://localhost:8080/doctor | grep -E "302|200" || {
    echo -e "${RED}❌ Doctor portal access failed${NC}"
    exit 1
}
echo -e "${GREEN}✅ Doctor portal access passed${NC}"

echo ""
echo -e "${GREEN}🎉 SMOKE: OK${NC}"
echo -e "${GREEN}All smoke tests passed successfully!${NC}"
