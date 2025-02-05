#!/bin/bash

# Change ownership recursively to www-data:www-data
sudo chown -R www-data:www-data /var/www/html/orbus_courier_server

# Add gcs-user to the www-data group
sudo usermod -a -G www-data gcs-user

# Set permissions for files
sudo find /var/www/html/orbus_courier_server -type f -exec chmod 644 {} \;

# Set permissions for directories
sudo find /var/www/html/orbus_courier_server -type d -exec chmod 755 {} \;

# Change ownership recursively to gcs-user:www-data
sudo chown -R gcs-user:www-data /var/www/html/orbus_courier_server

# Set permissions for files recursively
sudo find /var/www/html/orbus_courier_server -type f -exec chmod 664 {} \;

# Set permissions for directories recursively
sudo find /var/www/html/orbus_courier_server -type d -exec chmod 775 {} \;

# Change group ownership of storage and bootstrap/cache directories
sudo chgrp -R www-data /var/www/html/orbus_courier_server/storage /var/www/html/orbus_courier_server/bootstrap/cache
