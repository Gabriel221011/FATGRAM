output "ip_proxy_nat" {
  description = "IP Publica del Proxy NAT"
  value       = "La dirección IP pública de la instancia Proxy es ${aws_instance.proxy.public_ip}"
}
output "ip_servidor_web_1" {
  description = "IP Privada del Servidor Web"
  value       = "La dirección IP privada de la instancia del Servidor Web es ${aws_instance.ServerWeb.private_ip}"
}
output "ip_servidor_web2" {
  description = "IP Privada del Servidor Web 2"
  value       = "La dirección IP privada de la instancia del Servidor Web 2 es ${aws_instance.ServerWeb2.private_ip}"
}
output "ip_monitorizacion" {
  description = "IP Publica del Servidor de Monitorización"
  value       = "La dirección IP pública de la instancia del Servidor de Monitorización es ${aws_instance.monitorizacion.public_ip}"
}
output "endpoint_rds" {
  description = "Endpoint de la base de datos RDS"
  value       = "El endpoint de la base de datos RDS es ${aws_db_instance.rds_instance.endpoint}"
}