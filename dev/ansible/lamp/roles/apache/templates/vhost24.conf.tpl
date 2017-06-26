{% import "base.tpl" as tools with context %}

<VirtualHost *:80>
    {{ tools.defaultConfig(doc_root, server_name) }}
    {% if enviroment == "dev" or enviroment == "test" %}
    {{ tools.phpmyadmin() }}
    {% endif %}
</VirtualHost>
        
<VirtualHost *:443>
    {{ tools.defaultConfig(doc_root, server_name) }}
    {% if enviroment == "dev" or enviroment == "test" %}
    {{ tools.phpmyadmin() }}
    {% endif %}
    {{ tools.ssl(server_name) }}
</VirtualHost>
        
        

