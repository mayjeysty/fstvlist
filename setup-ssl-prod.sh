#!/bin/bash
# Let's Encrypt SSL Setup for FSTVLIST Production
# Usage: bash setup-ssl-prod.sh fstvlist.my.id

set -e

DOMAIN="${1:-fstvlist.my.id}"
EMAIL="${2:-admin@${DOMAIN}}"
NGINX_SSL_DIR="./nginx/ssl"

echo "============================================"
echo " FSTVLIST — Let's Encrypt SSL Setup"
echo " Domain: $DOMAIN"
echo " Email:  $EMAIL"
echo "============================================"

if [ "$EUID" -ne 0 ] && ! command -v docker &>/dev/null; then
    echo "[ERROR] Run as root or in a Docker-aware environment."
    exit 1
fi

if command -v certbot &>/dev/null; then
    echo "[1/3] certbot found — using standalone method..."
    systemctl stop nginx 2>/dev/null || true
    docker compose stop nginx 2>/dev/null || true

    certbot certonly --standalone \
        -d "$DOMAIN" \
        --email "$EMAIL" \
        --agree-tos \
        --non-interactive

    echo "[2/3] Copying certificates..."
    cp /etc/letsencrypt/live/$DOMAIN/fullchain.pem "$NGINX_SSL_DIR/fullchain.pem"
    cp /etc/letsencrypt/live/$DOMAIN/privkey.pem   "$NGINX_SSL_DIR/privkey.pem"
    chmod 600 "$NGINX_SSL_DIR/privkey.pem"

elif command -v docker &>/dev/null; then
    echo "[1/3] Using certbot via Docker..."

    docker compose stop nginx 2>/dev/null || true

    docker run --rm \
        -v "$(pwd)/$NGINX_SSL_DIR:/etc/letsencrypt/live/$DOMAIN" \
        -v "$(pwd)/certbot-www:/var/www/certbot" \
        certbot/certbot certonly --standalone \
        -d "$DOMAIN" \
        --email "$EMAIL" \
        --agree-tos \
        --non-interactive \
        --force-renewal 2>/dev/null || true

    echo "[2/3] Copying certificates..."
else
    echo "[ERROR] Neither certbot nor Docker found. Install certbot first:"
    echo "  apt install -y certbot  (Ubuntu/Debian)"
    echo "  yum install -y certbot  (CentOS/RHEL)"
    exit 1
fi

echo "[3/3] Restarting Nginx..."
docker compose up -d nginx 2>/dev/null || systemctl start nginx 2>/dev/null || true

echo ""
echo "============================================"
echo " SSL setup complete!"
echo " Certificate valid for 90 days."
echo " Auto-renewal: certbot renew --quiet"
echo " Add to crontab:"
echo "   0 3 * * * certbot renew --quiet && docker compose restart nginx"
echo "============================================"
