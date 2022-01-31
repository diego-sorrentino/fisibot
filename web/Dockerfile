FROM nginx:1.21
COPY nginx.conf /etc/nginx/nginx.conf
COPY fastcgi.conf /etc/nginx/fastcgi.conf
COPY virtualhost.template /etc/nginx/conf.d/virtualhost.template
COPY fisibot.key /etc/ssl/certs/fisibot.key
COPY fisibot.pem /etc/ssl/certs/fisibot.pem
RUN ln -sf /usr/share/zoneinfo/Europe/Rome /etc/localtime

