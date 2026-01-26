variable "aws_profile" {
  description = "El perfil de AWS CLI a utilizar"
  type        = string
}
variable "aws_region" {
  description = "La región de AWS donde se desplegarán los recursos"
  type        = string
}
variable "aws_availability_zone" {
  description = "La zona de disponibilidad de AWS donde se desplegarán los recursos"
  type        = string
}
variable "aws_availability_zone_rds" {
  description = "La segunda zona de disponibilidad de AWS que utiliza el grupo de subredes de la base de datos RDS"
  type        = string
}
variable "ubuntu_ami" {
  description = "ID de la AMI de Ubuntu"
  type        = string
}
variable "instance_type" {
  description = "Tipo de instancia EC2"
  type        = string
}
variable "key_name" {
  description = "Nombre del par de claves para las instancias EC2"
  type        = string
}