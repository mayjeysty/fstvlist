FROM nginx:stable-alpine

ENV TZ="Asia/Jakarta"
ENV PS1="\u@\h:\w\\$ "

RUN apk add --no-cache bash tzdata openssl

RUN mkdir -p /etc/nginx/ssl/live

RUN openssl dhparam -out /etc/nginx/ssl/dhparam.pem 2048

COPY ./default.conf /etc/nginx/conf.d/default.conf
