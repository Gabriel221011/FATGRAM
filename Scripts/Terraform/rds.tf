resource "aws_db_subnet_group" "rds_subnet_group" {
  name       = "rds-subnet-group"
  subnet_ids = [aws_subnet.subnet_rds.id, aws_subnet.subnet_privada.id]

  tags = {
    Name = "proyecto-intermodular-rds-subnet-group"
  }
}
# 1. Grupo de Seguridad para la EC2 
resource "aws_security_group" "ec2_rds" {
  name        = "ec2-rds"
  description = "Security group attached to instances to securely connect to fatgram-db. Modification could lead to connection loss."
  vpc_id      = aws_vpc.vpc_proyecto.id

  # Regla de salida (Egress) para permitir tráfico al RDS
  egress {
    from_port       = 3306
    to_port         = 3306
    protocol        = "tcp"
    security_groups = [aws_security_group.rds_ec2.id]
    description     = "Rule to allow outbound connection to fatgram-db"
  }

  tags = {
    Name = "ec2-rds-1"
  }
}

# 2. Grupo de Seguridad para el RDS 
resource "aws_security_group" "rds_ec2" {
  name        = "rds-ec2"
  description = "Security group attached to fatgram-db to allow EC2 instances with specific security groups attached to connect to the database. Modification could lead to connection loss."
  vpc_id      = aws_vpc.vpc_proyecto.id 
  tags = {
    Name = "rds-ec2-1"
  }
}

# 3. Regla de ENTRADA en el RDS
resource "aws_security_group_rule" "rds_ingress" {
  type                     = "ingress"
  from_port                = 3306
  to_port                  = 3306
  protocol                 = "tcp"
  security_group_id        = aws_security_group.rds_ec2.id
  
  # El origen es el SG de la EC2 
  source_security_group_id = aws_security_group.ec2_rds.id
  description              = "Rule to allow connection from EC2 instances"
}
resource "aws_db_instance" "rds_db" {
  identifier              = "fatgram"
  allocated_storage       = 20
  engine                  = "mariadb"
  engine_version          = "11.4.8"
  instance_class          = "db.t4g.micro"
  username                = "root"
  password                = "usuario2"
  skip_final_snapshot     = true
  publicly_accessible     = false
  vpc_security_group_ids  = [aws_security_group.rds_ec2.id, aws_security_group.ec2_rds.id]
  db_subnet_group_name    = aws_db_subnet_group.rds_subnet_group.name

  tags = {
    Name = "proyecto-intermodular-rds"
  }
}