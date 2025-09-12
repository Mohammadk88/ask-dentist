#!/usr/bin/env bash
# Simple TCP connection waiter
# Usage: ./scripts/wait-for.sh HOST PORT [TIMEOUT]

HOST=$1
PORT=$2
TIMEOUT=${3:-60}

if [ -z "$HOST" ] || [ -z "$PORT" ]; then
    echo "Usage: $0 HOST PORT [TIMEOUT]"
    echo "Example: $0 localhost 5432 60"
    exit 1
fi

echo "Waiting for $HOST:$PORT to be ready (timeout: ${TIMEOUT}s)..."

for i in $(seq $TIMEOUT); do
    if (echo > /dev/tcp/$HOST/$PORT) >/dev/null 2>&1; then
        echo "✅ $HOST:$PORT is ready!"
        exit 0
    fi
    printf "."
    sleep 1
done

echo ""
echo "❌ Timeout waiting for $HOST:$PORT after ${TIMEOUT} seconds"
exit 1