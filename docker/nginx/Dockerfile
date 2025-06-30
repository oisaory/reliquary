FROM nginx:alpine

# Copy configuration
COPY default.conf /etc/nginx/conf.d/

# Set working directory
WORKDIR /var/www

# Add security headers and optimize for production
RUN echo 'server_tokens off;' > /etc/nginx/conf.d/security.conf \
    && echo 'add_header X-Content-Type-Options nosniff;' >> /etc/nginx/conf.d/security.conf \
    && echo 'add_header X-Frame-Options SAMEORIGIN;' >> /etc/nginx/conf.d/security.conf \
    && echo 'add_header X-XSS-Protection "1; mode=block";' >> /etc/nginx/conf.d/security.conf

# Expose port 80
EXPOSE 80
