# Ansible
Este directorio contiene los scripts de Ansible para la automatización de preparación, instalación, actualización y monitorización de las instancias de AWS con el uso de playbooks.

## Requisitos para el correcto funcionamiento de los scripts
- Disponer de la clave vockey de las instancias para que Ansible se pueda conectar
- Copiar la clave a la instancia **Proxy-NAT** en la ruta /home/ubuntu/.ssh/fatgram.pem. **Para este script, es importante que el nombre de la clave sea fatgram.pem**
- Modificar el inventario de hosts (hosts.ini) en función de las direcciones IP de las instancias
- Modificar el archivo **ansible.cfg** para establecer la ruta de la clave y el inventario que se utilizará para configurar las instancias
- Los scripts de configuración del proxy y monitorización pueden ser ejecutados desde la máquina local Linux.

**Es importante tener en cuenta que estos scripts se han de ejecutar en una máquina local en Linux para su correcto funcionamiento**

## Procedimiento de instalación
#### Preparación para las instalaciones
1. Descargar la clave vockey del laboratorio de AWS en **AWS Details**, cambiarle el nombre a fatgram.pem y enviarla a la máquina Linux usando el comando `scp fatgram.pem usuario_linux@ip_linux:/home/usuario_linux/.ssh`
2. Cambiar los permisos de la clave a 400 usando `chmod 400 fatgram.pem` dentro del directorio **/home/usuario_linux/.ssh**
3. Copiar la clave privada *.pem* a la instancia Proxy usando el comando `scp -i /home/tu_usuario/.ssh/fatgram.pem /home/tu_usuario/.ssh/fatgram.pem ubuntu@ip_de_la_instancia`
4. Cambiar los permisos de la clave a 400 usando `chmod 400 /home/ubuntu/fatgram.pem` 
#### Instalación de la instancia Proxy y Monitorizacion
1. Clonar el repositorio en la máquina Linux si no está clonado.
2. Entrar en el directorio **/FATGRAM/Scripts/Ansible**
3. Modificar ansible.cfg y hosts.ini. **Las instrucciones vienen dadas dentro de los archivos**
4. Ejecutar `ansible-playbook 1-playbook-proxy` para la configuración imprescindible de red para las instancias privadas.
5. Ejecutar `ansible-playbook 2-playbook-monitorizacion` para la configuración de las instancias de monitorización.
**El playbook de monitorizacion puede ser ejecutado antes o después de ejecutar el playbook de los servidores, pero es más óptimo ejecutarlo antes.**
#### Instalación de los servidores web
1. Conectarse por ssh al Proxy
2. Entrar al directorio **/home/ubuntu/server_config** que ha dejado el playbook de la instalación del proxy.
3. Modificar ansible.cfg y hosts.ini. **Las instrucciones vienen dadas dentro de los archivos**
4. Modificar dentro del playbook **3-playbook-servidores** el endpoint de la base de datos del RDS para la creación de usuarios y bases de datos. Quedan comentadas las indicaciones dentro del playbook.
5. Ejecutar `3-playbook-servidores` para la configuración básica de los servidores web.