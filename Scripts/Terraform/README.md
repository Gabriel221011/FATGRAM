# Terraform
En este directorio, se almacenará el script de **Terraform** para construir la infraestructura de **AWS**.
La estructura de los scripts se forma de la siguiente manera:
- **main.tf**: Contiene la creación de la estructura de la red. Se crea la VPC y sus subredes y la Internet Gateway, la tabla de rutas, grupos de seguridad para las instancias con los puertos que se asignan para que sean accesibles desde otras instancias u otros equipos y el grupo de seguridad para la base de datos RDS.
- **instances.tf**: Contiene la creación de las instancias EC2 con la configuración de sus AMI,  la asignación del tipo de instancia y su clave **vockey**.
- **variables.tf** Contiene variables para evitar repetir los mismos valores.
- **build.tfvars**: Contiene los datos de las variables para asignar sus datos al laboratorio final.
- **testing.tfvars**: Archivo de valores de variables para probar en un laboratorio aparte.
- **outputs.tfvars**: Contiene los outputs que se van a mostrar por pantalla al ejecutar el script. Se encuentran outputs para mostrar las direcciones IP de las instancias.
- **rds.tf**: En este archivo, se crean los recursos necesarios para crear la base de datos RDS.
- **providers.tf**: Se encuentra la versión de Terraform que se debe ejecutar y el perfil y región que le indicarán en qué laboratorio deben crearse los recursos.