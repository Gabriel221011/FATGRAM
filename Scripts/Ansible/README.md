# Ansible
Este directorio contiene los scripts de Ansible para la automatización de preparación, instalación, actualización y monitorización de las instancias de AWS con el uso de playbooks.

## Requisitos para el correcto funcionamiento de los scripts
- Disponer de la clave vockey de las instancias para que Ansible se pueda conectar
- Copiar la clave a la instancia **monitorización** en la ruta /home/ubuntu/.ssh/fatgram.pem. **Para este script, es importante que el nombre de la clave sea fatgram.pem**
- Modificar el inventario de hosts (hosts.ini) en función de las direcciones IP de las instancias
- Modificar el archivo **ansible.cfg** para establecer la ruta de la clave y el inventario que se utilizará para configurar las instancias
- Ejecutar el script inicial **(playbook-monitorizacion.yml)** desde una máquina Linux local (o máquina virtual en VMware o Virtualbox) para el correcto funcionamiento de Ansible

**Es importante tener en cuenta que estos scripts se han de ejecutar en una máquina local en Linux para su correcto funcionamiento**

