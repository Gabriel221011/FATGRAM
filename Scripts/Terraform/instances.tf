resource "aws_instance" "proxy" {
  ami           = "ami-0ecb62995f68bb549"
  key_name      = "vockey"
  instance_type = "t3.small"
  vpc_security_group_ids = [aws_security_group.gs_proxy_nat.id]
  subnet_id = aws_subnet.subnet_publica.id
  
  associate_public_ip_address = true

  tags = {
    Name = "Proxy-NAT"
  }
}

resource "aws_instance" "ServerWeb" {
  ami           = "ami-0ecb62995f68bb549"
  key_name      = "vockey"
  instance_type = "t3.small"
  vpc_security_group_ids = [aws_security_group.gs_private.id]
  subnet_id = aws_subnet.subnet_privada.id
  

  tags = {
    Name = "Servidor Web"
  }
}