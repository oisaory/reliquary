FROM nginx:alpine

# Install OpenSSL for certificate generation
RUN apk add --no-cache openssl

# Create directory for SSL certificates
RUN mkdir -p /etc/nginx/ssl

# Generate self-signed SSL certificate
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/nginx/ssl/nginx.key -out /etc/nginx/ssl/nginx.crt \
    -subj "/C=US/ST=State/L=City/O=Organization/CN=localhost"

# Copy configuration
COPY default.conf /etc/nginx/conf.d/

# Set working directory
WORKDIR /var/www

# Add security headers and optimize for production
RUN echo 'server_tokens off;' > /etc/nginx/conf.d/security.conf \
    && echo 'add_header X-Content-Type-Options nosniff;' >> /etc/nginx/conf.d/security.conf \
    && echo 'add_header X-Frame-Options SAMEORIGIN;' >> /etc/nginx/conf.d/security.conf \
    && echo 'add_header X-XSS-Protection "1; mode=block";' >> /etc/nginx/conf.d/security.conf

# Expose port 443 for HTTPS
EXPOSE 443
