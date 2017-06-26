{% import "base.tpl" as tools with context %}

<VirtualHost *:80>
    {{ tools.defaultConfig(doc_root, server_name) }}
    {% if enviroment == "dev" or enviroment == "test" %}
    {{ tools.phpmyadmin() }}
    {% endif %}
    
    <FilesMatch \.php$>
        SetHandler "proxy:unix:/run/php/php{{ php_version }}-fpm.sock|fcgi://localhost/"
    </FilesMatch>
</VirtualHost>
        
<VirtualHost *:443>
    {{ tools.defaultConfig(doc_root, server_name) }}
    {% if enviroment == "dev" or enviroment == "test" %}
    {{ tools.phpmyadmin() }}
    {{ tools.ssl(server_name) }}
    {% endif %}
    <FilesMatch \.php$>
        SetHandler "proxy:unix:/run/php/php{{ php_version }}-fpm.sock|fcgi://localhost/"
    </FilesMatch>
</VirtualHost>
    