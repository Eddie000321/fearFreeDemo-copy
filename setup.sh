#!/bin/bash

# Fear Free Learning Portal - Quick Setup Script
# Mac/Linux compatible

set -e

echo "🚀 Fear Free Learning Portal - Setup"
echo "======================================"
echo ""

# Check Docker
if ! command -v docker &> /dev/null; then
    echo "❌ Docker not found. Please install Docker Desktop first."
    echo "   Download: https://www.docker.com/products/docker-desktop"
    exit 1
fi

# Check Node.js
if ! command -v node &> /dev/null; then
    echo "⚠️  Node.js not found. React widget won't be built."
    echo "   Install with: brew install node"
    SKIP_REACT=true
else
    echo "✅ Node.js $(node --version) detected"
    SKIP_REACT=false
fi

# Start Docker containers
echo ""
echo "📦 Starting WordPress + MySQL containers..."
docker compose up -d

echo ""
echo "⏳ Waiting for WordPress to be ready (30 seconds)..."
sleep 30

# Build React widget if Node.js available
if [ "$SKIP_REACT" = false ]; then
    echo ""
    echo "⚛️  Building React progress widget..."
    cd wp-content/plugins/wp-learning-portal/frontend
    npm install --silent
    npm run build
    cd ../../../..
    echo "✅ React widget built successfully"
fi

echo ""
echo "✅ Setup complete!"
echo ""
echo "Next steps:"
echo "1. Open http://localhost:8080"
echo "2. Complete WordPress installation (create admin account)"
echo "3. Activate plugin: Plugins → WP Learning Portal"
echo "4. Activate theme (optional): Appearance → Themes → Twenty Twenty-Five Child"
echo "5. Create sample courses: Courses → Add New"
echo "6. Test shortcodes on a new page:"
echo "   [course_list limit=\"5\"]"
echo "   [pet_reminder msg=\"Don't forget hydration!\"]"
echo ""
echo "📝 See README.md for detailed documentation"
echo ""
