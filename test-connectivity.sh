#!/bin/bash

echo "🔍 فحص اتصال Backend و Flutter App"
echo "================================="

# Test Backend Health
echo "1️⃣ اختبار صحة Backend API:"
if curl -f -s http://127.0.0.1:8000/api/v1/health > /dev/null 2>&1; then
    echo "✅ Backend API متصل بنجاح"
    curl -s http://127.0.0.1:8000/api/v1/health | python3 -m json.tool 2>/dev/null || curl -s http://127.0.0.1:8000/api/v1/health
else
    echo "❌ Backend API غير متصل"
    echo "تأكد من تشغيل Laravel server على المنفذ 8000"
fi

echo ""
echo "2️⃣ اختبار home endpoint:"
if curl -f -s http://127.0.0.1:8000/api/v1/home > /dev/null 2>&1; then
    echo "✅ Home endpoint متاح"
    curl -s http://127.0.0.1:8000/api/v1/home | head -c 200
    echo "..."
else
    echo "❌ Home endpoint غير متاح"
fi

echo ""
echo "3️⃣ فحص إعدادات Flutter App:"
if [ -f "/Users/mohammadkfelati/MyProjects/ask_dentist/ask-dentist-mvp/apps/patient/assets/.env.json" ]; then
    echo "✅ ملف .env.json موجود:"
    cat /Users/mohammadkfelati/MyProjects/ask_dentist/ask-dentist-mvp/apps/patient/assets/.env.json
else
    echo "❌ ملف .env.json غير موجود"
fi

echo ""
echo "4️⃣ معلومات الشبكة:"
echo "Backend Server: http://127.0.0.1:8000"
echo "API Base URL: http://127.0.0.1:8000/api/v1"

# Get LAN IP for mobile devices
LAN_IP=$(ifconfig en0 2>/dev/null | grep 'inet ' | awk '{print $2}' | head -n 1)
if [ ! -z "$LAN_IP" ]; then
    echo "LAN IP للأجهزة المحمولة: http://$LAN_IP:8000"
fi

echo ""
echo "📱 عناوين للمنصات المختلفة:"
echo "• macOS/Desktop: http://localhost:8000"
echo "• iOS Simulator: http://localhost:8000"
echo "• Android Emulator: http://10.0.2.2:8000"
if [ ! -z "$LAN_IP" ]; then
    echo "• Physical Devices: http://$LAN_IP:8000"
fi

echo ""
echo "🚀 لتشغيل تطبيق المرضى:"
echo "cd /Users/mohammadkfelati/MyProjects/ask_dentist/ask-dentist-mvp/apps/patient"
echo "flutter run -d macos"
