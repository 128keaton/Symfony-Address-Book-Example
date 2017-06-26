{% macro defaultConfig(doc_root, server_name) %}
    ServerAdmin webmaster@localhost
    DocumentRoot {{ doc_root }}
    {% set server_names = server_name.split() %}
    {% for server_name in server_names %}
    {% if loop.first %}
        ServerName {{ server_name }}
    {% else %}
        ServerAlias {{ server_name }}
    {% endif %}
    {% endfor %}

    <Directory {{ doc_root }}>
        AllowOverride All
        Require all granted
    </Directory>
{% endmacro %}

{% macro phpmyadmin() %}
    Alias /phpmyadmin/ "/usr/share/phpmyadmin/"

    <Directory "/usr/share/phpmyadmin/">
        Order allow,deny
        Allow from all
        Require all granted
    </Directory>    
{% endmacro %}

{% macro ssl(server_name) %}
    SSLEngine on
    SSLCertificateFile /etc/apache2/ssl/{{server_name}}.crt
    SSLCertificateKeyFile /etc/apache2/ssl/{{server_name}}.key
    
    <FilesMatch "\.(cgi|shtml|phtml|php)$">
        SSLOptions +StdEnvVars
    </FilesMatch>
    
    <Directory /usr/lib/cgi-bin>
        SSLOptions +StdEnvVars
    </Directory>
    
    BrowserMatch "MSIE [2-6]" \
                    nokeepalive ssl-unclean-shutdown \
                    downgrade-1.0 force-response-1.0
    BrowserMatch "MSIE [17-9]" ssl-unclean-shutdown
{% endmacro %}