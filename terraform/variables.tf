variable "aws_region" {
  description = "Region AWS"
  default     = "eu-north-1"
}

variable "public_key" {
  description = "Clé SSH publique pour accéder au serveur"
  type        = string
}
