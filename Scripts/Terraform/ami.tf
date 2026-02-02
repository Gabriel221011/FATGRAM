# 1. Buscamos la AMI en el catálogo de AWS
data "aws_ami" "mi_ami_laboratorio" {
  most_recent = true      # Trae la última versión si hay varias con el mismo nombre
  owners      = ["self"]  # BUSCA SOLO EN TU CUENTA (Importante para AMIs de laboratorio)

  filter {
    name   = "name"
    # Cambia esto por el nombre que le diste a tu AMI. 
    # El asterisco (*) funciona como comodín.
    values = ["mi-servidor-configurado-*"] 
  }

  filter {
    name   = "state"
    values = ["available"]
  }
}

# 2. Usamos el ID obtenido para lanzar una instancia
resource "aws_instance" "servidor_prueba" {
  # Aquí llamamos al ID que encontró el filtro de arriba
  ami           = data.aws_ami.mi_ami_laboratorio.id
  instance_type = "t3.small"

  tags = {
    Name = "Instancia-desde-AMI-Custom"
  }
}