output "server_ip" {
  description = "Adresse IP publique du serveur"
  value       = aws_instance.projet_server.public_ip
}

output "server_dns" {
  description = "DNS public du serveur"
  value       = aws_instance.projet_server.public_dns
}
