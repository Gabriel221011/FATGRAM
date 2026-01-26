resource "aws_db_subnet_group" "rds_subnet_group" {
  name       = "rds-subnet-group"
  subnet_ids = [aws_subnet.subnet_rds.id, aws_subnet.subnet_privada.id]

  tags = {
    Name = "proyecto-intermodular-rds-subnet-group"
  }
}
resource "aws_db_instance" "rds_instance" {
  identifier              = "fatgram"
  allocated_storage       = 20
  engine                  = "mariadb"
  engine_version          = "11.4.8"
  instance_class          = "db.t3.micro"
  username                = "root"
  password                = "usuario2"
  skip_final_snapshot     = true
  publicly_accessible     = false
  vpc_security_group_ids  = [aws_security_group.gs_rds.id]
  db_subnet_group_name    = aws_db_subnet_group.rds_subnet_group.name

  tags = {
    Name = "proyecto-intermodular-rds"
  }
}