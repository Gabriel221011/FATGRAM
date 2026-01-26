output "ip_proxy_nat" {
  description = "IP Publica del Proxy NAT"
  value       = "La dirección IP pública de la instancia Proxy es ${aws_instance.proxy.public_ip}"
}
output "ip_servidor_web" {
  description = "IP Privada del Servidor Web"
  value       = "La dirección IP privada de la instancia del Servidor Web es ${aws_instance.ServerWeb.private_ip}"
}