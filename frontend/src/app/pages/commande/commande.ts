import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RouterLink, RouterLinkActive } from '@angular/router';

@Component({
  selector: 'app-commande',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterLink, RouterLinkActive],
  templateUrl: './commande.html',
  styleUrl: './commande.css'
})
export class CommandeComponent {
  commandePassee = false;
  commande = {
    prenom: '', nom: '', email: '', telephone: '',
    adresse: '', ville: '', codePostal: '',
    produit: '', notes: '', paiement: 'livraison'
  };

  passerCommande() {
    if (this.commande.prenom && this.commande.nom && this.commande.email &&
        this.commande.telephone && this.commande.adresse && this.commande.produit) {
      this.commandePassee = true;
    }
  }
}