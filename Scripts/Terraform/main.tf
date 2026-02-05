# Este es el archivo main.tf provisional que integra los recursos necesarios para la infraestructura del proyecto.
# Creación de la VPC
resource "aws_vpc" "vpc_proyecto" {
  cidr_block = "10.0.0.0/16"
  enable_dns_support = true
  enable_dns_hostnames = true
  tags = {
    Name = "proyecto-intermodular-vpc"
  }
}
# Creación del Internet Gateway
resource "aws_internet_gateway" "igw" {
  vpc_id = aws_vpc.vpc_proyecto.id

  tags = {
    Name = "proyecto-intermodular-igw"
  }
}
# Creación de la subred pública
resource "aws_subnet" "subnet_publica" {
  vpc_id            = aws_vpc.vpc_proyecto.id
  cidr_block        = "10.0.0.0/24"
  availability_zone = var.aws_availability_zone

  tags = {
    Name = "proyecto-intermodular-subnet-public"
  }
}
# Creación de la subred privada
resource "aws_subnet" "subnet_privada" {
  vpc_id            = aws_vpc.vpc_proyecto.id
  cidr_block        = "10.0.128.0/24"
  availability_zone = var.aws_availability_zone

  tags = {
    Name = "proyecto-intermodular-subnet-private"
  }
}
# Creación de la subred para la base de datos RDS
resource "aws_subnet" "subnet_rds" {
  vpc_id            = aws_vpc.vpc_proyecto.id
  cidr_block        = "10.0.30.0/24"
  availability_zone = var.aws_availability_zone_rds

  tags = {
    Name = "proyecto-intermodular-subnet-rds"
  }
}
# Tabla de ruta de la subred pública
resource "aws_route_table" "rt_public" {
  vpc_id = aws_vpc.vpc_proyecto.id
  
  tags = {
    Name = "proyecto-intermodular-rt-public"
  }
}
# Ruta para que la subred pública tenga acceso a Internet
resource "aws_route" "route_public_internet" {
  route_table_id         = aws_route_table.rt_public.id
  destination_cidr_block = "0.0.0.0/0"
  gateway_id             = aws_internet_gateway.igw.id
}

resource "aws_route_table_association" "rt_assoc_public" {
  subnet_id      = aws_subnet.subnet_publica.id
  route_table_id = aws_route_table.rt_public.id
}
# Tabla de ruta de la subred privada
resource "aws_route_table" "rt_private" {
  vpc_id = aws_vpc.vpc_proyecto.id

  tags = {
    Name = "proyecto-intermodular-rt-private"
  }
}

resource "aws_route_table_association" "rt_assoc_private" {
  subnet_id      = aws_subnet.subnet_privada.id
  route_table_id = aws_route_table.rt_private.id
}

resource "aws_route" "private_route" {
  route_table_id         = aws_route_table.rt_private.id
  destination_cidr_block = "0.0.0.0/0"
  network_interface_id   = aws_instance.proxy.primary_network_interface_id
}
# Grupo de seguridad para el proxy
resource "aws_security_group" "gs_proxy_nat" {
  name        = "Proxy-NAT-GS"
  description = "Grupo de seguridad para el proxy"
  vpc_id      = aws_vpc.vpc_proyecto.id

  ingress {
    description = "SSH"
    from_port   = 22
    to_port     = 22
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    description = "HTTP"
    from_port   = 80
    to_port     = 80
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    description = "HTTPS"
    from_port   = 443
    to_port     = 443
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }
  ingress {
    description = "MySQL/MariaDB"
    from_port   = 3306
    to_port     = 3306
    protocol    = "tcp"
    cidr_blocks = ["10.0.128.0/24"]
   }
  ingress {
      description = "Permitir todo desde el grupo de seguridad privado"
      from_port = 0
      to_port = 0
      protocol = "-1"
      cidr_blocks = ["10.0.128.0/24"]
    }
  egress {
    description = "All outbound"
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
    tags = {
    Name = "Proxy-NAT-GS"
  }
}
# Grupo de seguridad para la subred privada
resource "aws_security_group" "gs_private" {
  name        = "gs-private"
  description = "Grupo de seguridad para instancias en la subnet privada"
  vpc_id      = aws_vpc.vpc_proyecto.id

  ingress {
    description = "Proxy"
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["10.0.0.138/32"]
  }

  ingress {
    description = "MySQL/MariaDB"
    from_port   = 3306
    to_port     = 3306
    protocol    = "tcp"
    cidr_blocks = ["10.0.128.0/24"]
  }
  egress {
    description = "All outbound"
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
  tags = {
    Name = "Grupo-Privado"
  }
}
resource "aws_security_group" "gs_rds" {
  name        = "gs-rds"
  description = "Grupo de seguridad para la base de datos RDS"
  vpc_id      = aws_vpc.vpc_proyecto.id

  ingress {
    description = "MariaDB"
    from_port   = 3306
    to_port     = 3306
    protocol    = "tcp"
    cidr_blocks = ["10.0.128.0/24"]
  }
  egress {
    description = "All outbound"
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
  tags = {
    Name = "Grupo-RDS"
  }
}
# Creación de IP elástica
resource "aws_eip" "proxy_eip" {
  instance = aws_instance.proxy.id
  tags = {
    Name = "proxy-eip"
  }
}
# Creación de la ACL de red
resource "aws_network_acl" "acl_nat" {
  vpc_id = aws_vpc.main.id

  tags = {
    Name = "ACL-Instancia-NAT"
  }
}
# ACL para la instancia NAT
resource "aws_network_acl_rule" "inbound_110" {
  network_acl_id = aws_network_acl.acl_nat.id
  rule_number    = 110 
  egress         = false
  protocol       = "-1"
  rule_action    = "allow"
  cidr_block     = "10.0.128.0/24" 
  from_port      = 0
  to_port        = 0
}