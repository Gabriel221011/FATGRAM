# Este script se está probando y todavía hay que revisarlo antes de modificar main.tf para poder ordenar toda la infraestructura.
# 1. INSTANCIA NAT
# Configuración basada en las capturas de la página 3 [cite: 7, 14, 18]
resource "aws_instance" "proxy_nat" {
  ami           = "ami-0c55b159cbfafe1f0" # Sustituir por la AMI de Ubuntu mencionada [cite: 151]
  instance_type = "t2.micro"
  
  # Desactivar la comprobación de origen/destino [cite: 8, 31, 41]
  # Esto permite que la instancia reenvíe tráfico que no es para sí misma [cite: 40]
  source_dest_check = false 

  vpc_security_group_ids = [aws_security_group.nat_sg.id]
  subnet_id              = var.public_subnet_id

  tags = {
    Name = "Proxy-NAT" [cite: 14, 18]
  }

  # Comandos de configuración interna (Páginas 5 y 6)
  user_data = <<-EOF
              #!/bin/bash
              # Actualizar repositorios [cite: 150, 151, 152]
              sudo apt update && sudo apt upgrade -y

              # Habilitar reenvío de paquetes IPv4 [cite: 155, 156, 160, 188]
              echo "net.ipv4.ip_forward=1" | sudo tee -a /etc/sysctl.conf
              sudo sysctl -w net.ipv4.ip_forward=1 [cite: 156]

              # Configurar NAT con IPTABLES [cite: 167, 185]
              # Se usa ens5 como ID de tarjeta detectado en el documento 
              sudo iptables -t nat -A POSTROUTING -o ens5 -j MASQUERADE 

              # Instalar persistencia para las reglas [cite: 161, 163]
              echo iptables-persistent iptables-persistent/autosave_v4 boolean true | sudo debconf-set-selections
              sudo apt install -y iptables-persistent
              sudo netfilter-persistent save [cite: 165]
              EOF
}

# 2. GRUPO DE SEGURIDAD DE LA INSTANCIA NAT (Proxy-Nat-GS)
# Configuración basada en las capturas de la página 4 [cite: 45, 48]
resource "aws_security_group" "nat_sg" {
  name   = "Proxy-Nat-GS" [cite: 48, 79]
  vpc_id = var.vpc_id

  # Reglas de Entrada: Permitir todo el tráfico desde la subred privada [cite: 46, 62]
  ingress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["10.0.1.0/24"] # Rango de la Subred Privada [cite: 62, 63]
  }

  # Reglas de Salida: Todo el tráfico permitido [cite: 65, 75, 77]
  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

# 3. GRUPO DE SEGURIDAD PRIVADO (Servidores Web)
# Configuración basada en la página 4 y 5 [cite: 98, 99]
resource "aws_security_group" "private_sg" {
  name   = "Privado" [cite: 100, 113, 125]
  vpc_id = var.vpc_id

  # Entrada: Permitir tráfico desde la IP de la Instancia NAT [cite: 109, 111]
  ingress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["10.0.0.138/32"] # IP específica de la instancia NAT [cite: 171, 180]
  }

  # Salida: Permitir todo el tráfico [cite: 112, 119, 120]
  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

# 4. ACL DE RED
# Configuración basada en la página 5 [cite: 134]
resource "aws_network_acl_rule" "inbound_110" {
  network_acl_id = var.nacl_id
  rule_number    = 110 
  egress         = false
  protocol       = "-1" # Todo el tráfico [cite: 141]
  rule_action    = "allow"
  cidr_block     = "10.0.1.0/24" # Subred Privada [cite: 147, 148]
  from_port      = 0
  to_port        = 0
}

# 5. TABLA DE RUTAS (Lógica necesaria para completar el flujo)
resource "aws_route_table" "private_rt" {
  vpc_id = var.vpc_id

  route {
    cidr_block  = "0.0.0.0/0"
    instance_id = aws_instance.proxy_nat.id # El tráfico sale por la instancia NAT
  }

  tags = {
    Name = "Ruta-Subred-Privada"
  }
}