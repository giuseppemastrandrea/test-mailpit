FROM php:8.2-apache

# Installiamo msmtp (un client SMTP leggerissimo)
RUN apt-get update && apt-get install -y msmtp && rm -rf /var/lib/apt/lists/*

# Installiamo l'estensione mysqli per PHP
RUN docker-php-ext-install mysqli

# Creiamo il file di configurazione per msmtp
# Gli diciamo di mandare tutto al servizio 'mailpit' sulla porta 1025
RUN echo "account default" > /etc/msmtprc && \
    echo "host mailpit" >> /etc/msmtprc && \
    echo "port 1025" >> /etc/msmtprc && \
    echo "auto_from on" >> /etc/msmtprc && \
    echo "maildomain test.local" >> /etc/msmtprc && \
    echo "from noreply@test.local" >> /etc/msmtprc && \
    echo "allow_from_override on" >> /etc/msmtprc && \
    echo "syslog off" >> /etc/msmtprc

# Diamo i permessi corretti al file di configurazione
RUN chmod 644 /etc/msmtprc