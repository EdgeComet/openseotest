# Deployment Guide for openseotest.org

This guide covers deploying openseotest.org to a production server using Ansible.

## Prerequisites

### Server Requirements

- **OS**: Ubuntu 22.04 LTS or Debian 12 (recommended)
- **PHP**: 8.3 with FPM
- **Nginx**: 1.18+
- **Composer**: 2.x
- **SSL**: Let's Encrypt certificates (or equivalent)

### Required PHP Extensions

- php8.3-fpm
- php8.3-cli
- php8.3-common
- php8.3-mbstring
- php8.3-xml
- php8.3-curl

### Local Requirements

- Ansible 2.10+
- SSH access to the production server
- Sudo privileges on the server

## Configuration

### 1. Update Inventory

Edit `ansible/inventory.yml` and update:

```yaml
production:
  ansible_host: your-server-ip-or-hostname
  ansible_user: your-ssh-user
```

### 2. SSL Certificates

Ensure SSL certificates are configured on the server. Using Let's Encrypt:

```bash
sudo certbot certonly --nginx -d openseotest.org -d www.openseotest.org
```

### 3. Create Log Directories

On the server:

```bash
sudo mkdir -p /var/log/php-fpm
sudo chown www-data:www-data /var/log/php-fpm
```

## Deployment

### Dry Run (Check Mode)

Test the deployment without making changes:

```bash
cd deploy/ansible
ansible-playbook -i inventory.yml playbook.yml --check
```

### Full Deployment

Run the deployment:

```bash
cd deploy/ansible
ansible-playbook -i inventory.yml playbook.yml
```

### Verbose Output

For detailed output during deployment:

```bash
ansible-playbook -i inventory.yml playbook.yml -v
```

## Verification

After deployment, verify:

### 1. Site Responds

```bash
curl -I https://openseotest.org/
```

Expected: HTTP 200 with `X-Debug-Hash` header

### 2. Test Pages Work

```bash
curl -I https://openseotest.org/lab/js-injection/domcontentinit
```

Expected: HTTP 200

### 3. Sitemap Accessible

```bash
curl https://openseotest.org/sitemap.xml | head -10
```

Expected: Valid XML sitemap

### 4. Check Nginx Logs

```bash
sudo tail -f /var/log/nginx/openseotest.access.log
```

Look for the `hash=` field in log entries.

## Troubleshooting

### PHP-FPM Issues

Check PHP-FPM status:

```bash
sudo systemctl status php8.3-fpm
sudo journalctl -u php8.3-fpm -f
```

Check pool-specific logs:

```bash
sudo tail -f /var/log/php-fpm/openseotest-error.log
```

### Nginx Issues

Test configuration:

```bash
sudo nginx -t
```

Check error log:

```bash
sudo tail -f /var/log/nginx/openseotest.error.log
```

### Permission Issues

Reset permissions:

```bash
sudo chown -R www-data:www-data /var/www/openseotest
sudo find /var/www/openseotest -type d -exec chmod 755 {} \;
sudo find /var/www/openseotest -type f -exec chmod 644 {} \;
```

## Manual Deployment

If you prefer not to use Ansible:

1. Copy files to server:
   ```bash
   rsync -avz --exclude='.git' --exclude='tests' --exclude='*.md' \
     ./ user@server:/var/www/openseotest/
   ```

2. Install dependencies:
   ```bash
   cd /var/www/openseotest
   composer install --no-dev --optimize-autoloader
   ```

3. Copy nginx config:
   ```bash
   sudo cp deploy/nginx/openseotest.conf /etc/nginx/sites-available/
   sudo cp deploy/nginx/cloudflare-ips.conf /etc/nginx/snippets/
   sudo ln -sf /etc/nginx/sites-available/openseotest.conf /etc/nginx/sites-enabled/
   sudo nginx -t && sudo systemctl reload nginx
   ```

4. Copy PHP-FPM config:
   ```bash
   sudo cp deploy/php-fpm/openseotest.conf /etc/php/8.3/fpm/pool.d/
   sudo systemctl reload php8.3-fpm
   ```

5. Set permissions:
   ```bash
   sudo chown -R www-data:www-data /var/www/openseotest
   ```
