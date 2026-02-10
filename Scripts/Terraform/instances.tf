resource "aws_instance" "proxy" {
  ami           = var.ubuntu_ami
  key_name      = var.key_type
  instance_type = var.instance_type
  vpc_security_group_ids = [aws_security_group.gs_proxy_nat.id]
  subnet_id = aws_subnet.subnet_publica.id
  source_dest_check = false
  associate_public_ip_address = true

  tags = {
    Name = "Proxy-NAT"
  }
}

resource "aws_instance" "ServerWeb" {
  ami           = var.ubuntu_ami
  key_name      = var.key_type
  instance_type = var.instance_type
  root_block_device {
    volume_size = 10              
    volume_type = "gp2"           
    delete_on_termination = true  
  }

  vpc_security_group_ids = [aws_security_group.gs_private.id]
  subnet_id = aws_subnet.subnet_privada.id
  

  tags = {
    Name = "Servidor Web"
  }
}

resource "aws_instance" "ServerWeb2" {
  ami           = var.ubuntu_ami
  key_name      = var.key_type
  instance_type = var.instance_type
  root_block_device {
    volume_size = 10              
    volume_type = "gp2"           
    delete_on_termination = true  
  }
  vpc_security_group_ids = [aws_security_group.gs_private.id]
  subnet_id = aws_subnet.subnet_privada.id
  

  tags = {
    Name = "Servidor Web 2"
  }
}

resource "aws_instance" "monitorizacion" {
  ami           = var.ubuntu_ami
  key_name      = var.key_type
  instance_type = var.instance_type
  vpc_security_group_ids = [aws_security_group.gs_proxy_nat.id]
  subnet_id = aws_subnet.subnet_publica.id
  
  associate_public_ip_address = true

  tags = {
    Name = "Monitorizacion"
  }
}