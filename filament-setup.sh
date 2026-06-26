#!/bin/bash
# Filament Admin Setup Script
# Run after composer install completes

echo "🚀 Purprime Fox - Filament Admin Setup Script"
echo "=============================================="
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Step 1: Publish Filament Assets
echo -e "${BLUE}[1/5]${NC} Publishing Filament assets..."
php artisan vendor:publish --tag=filament-assets

# Step 2: Create Admin Panel
echo ""
echo -e "${BLUE}[2/5]${NC} Creating Filament admin panel..."
php artisan filament:install --panels

# Step 3: Run custom setup command
echo ""
echo -e "${BLUE}[3/5]${NC} Registering resources..."
php artisan filament:setup-admin

# Step 4: Create admin user (optional)
echo ""
echo -e "${BLUE}[4/5]${NC} Creating admin user..."
read -p "Create admin user now? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan tinker << 'EOF'
\App\Models\User::create([
    'email' => 'admin@moontrade.local',
    'password' => bcrypt('admin123456'),
    'first_name' => 'Admin',
    'last_name' => 'User',
    'is_active' => true,
    'email_verified_at' => now(),
]);
EOF
    echo -e "${GREEN}✓ Admin user created${NC}"
    echo "  Email: admin@moontrade.local"
    echo "  Password: admin123456"
fi

# Step 5: Cache configuration
echo ""
echo -e "${BLUE}[5/5]${NC} Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo -e "${GREEN}✅ Setup Complete!${NC}"
echo ""
echo -e "${YELLOW}Access Points:${NC}"
echo "  📊 Admin Panel: http://localhost:8000/admin"
echo "  🔌 API: http://localhost:8000/api"
echo ""
echo -e "${YELLOW}API Trade Endpoints:${NC}"
echo "  POST   /api/trade/operations/open"
echo "  POST   /api/trade/operations/{id}/close"
echo "  POST   /api/trade/operations/close-all"
echo "  GET    /api/trade/operations/pnl"
echo "  PATCH  /api/trade/operations/{id}/update-levels"
echo "  GET    /api/trade/operations/orders"
echo "  POST   /api/trade/operations/orders"
echo "  DELETE /api/trade/operations/orders/{id}"
echo ""
echo -e "${YELLOW}Documentation:${NC}"
echo "  See FILAMENT_SETUP.md for full configuration details"
echo ""
