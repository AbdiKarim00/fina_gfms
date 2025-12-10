# Alternative Setup for macOS 12

## Issue
Your macOS 12 system has limited Homebrew support, causing installation conflicts. Here are alternative approaches.

---

## Option 1: Use Pre-installed Tools + Manual Downloads

### Check What's Already Available
```bash
# Check for existing tools
which php
which node
which npm
which sqlite3
which python3

# Check versions
node --version
npm --version
```

### Use SQLite Instead of PostgreSQL
Since PostgreSQL installation is problematic, let's use SQLite which is built into macOS:

```bash
# Test SQLite
sqlite3 --version
```

---

## Option 2: Manual PHP Installation

### Download PHP Binary
```bash
# Create PHP directory
mkdir -p ~/php

# Download PHP 8.2 binary for macOS
curl -L "https://github.com/phpbrew/phpbrew/releases/download/1.27.0/phpbrew.phar" -o ~/php/phpbrew.phar
chmod +x ~/php/phpbrew.phar

# Or use the system PHP if available
/usr/bin/php --version
```

### Alternative: Use XAMPP
1. Download XAMPP from https://www.apachefriends.org/download.html
2. Install it - includes PHP, MySQL, Apache
3. Use XAMPP's PHP: `/Applications/XAMPP/bin/php`

---

## Option 3: Simplified SQLite Setup

Let me create a SQLite-based setup that avoids PostgreSQL entirely:

### Backend Configuration for SQLite
```bash
cd gfms/apps/backend

# Copy and modify .env for SQLite
cp .env.example .env
```

**Edit `.env` for SQLite:**
```env
APP_NAME="Kenya GFMS"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# SQLite Database (no server needed)
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/gfms/apps/backend/database/database.sqlite
DB_FOREIGN_KEYS=true

# File-based cache (no Redis needed)
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

# File-based mail
MAIL_MAILER=log
```

### Create SQLite Database
```bash
cd gfms/apps/backend

# Create database file
touch database/database.sqlite

# Make sure it's writable
chmod 664 database/database.sqlite
```

---

## Option 4: Use Docker Desktop Alternative

### OrbStack (Lighter than Docker Desktop)
1. Download OrbStack from https://orbstack.dev
2. Install it (much lighter than Docker Desktop)
3. Use your existing docker-compose.yml

### Podman Desktop
1. Download from https://podman-desktop.io
2. Lighter alternative to Docker
3. Compatible with docker-compose

---

## Option 5: Cloud Development Environment

### GitHub Codespaces
1. Push your code to GitHub
2. Open in Codespaces
3. Everything runs in the cloud
4. No local resource usage

### Gitpod
1. Connect your GitHub repo
2. Runs in browser
3. Pre-configured environments

---

## Recommended: SQLite + System Tools Setup

Let's go with the simplest approach that will work on your system:

### Step 1: Check System PHP
```bash
# Check if system PHP exists
/usr/bin/php --version

# If it exists and is 7.4+, we can use it
```

### Step 2: Install Composer Manually
```bash
# Download Composer
curl -sS https://getcomposer.org/installer | /usr/bin/php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
```

### Step 3: Use Node.js (Already Working)
```bash
# You already have Node.js working
node --version
npm --version
```

### Step 4: Configure for SQLite
I'll create a simplified setup script that uses SQLite and avoids problematic installations.

---

## Let's Try the SQLite Approach

Would you like me to:
1. **Create a SQLite-based setup** (recommended - no server installations needed)
2. **Try XAMPP installation** (includes everything in one package)
3. **Set up a cloud development environment** (no local resources used)

Which option sounds best for your situation?