import { Component, OnInit, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RouterLink, RouterLinkActive } from '@angular/router';
import { OrderService } from '../../services/order.service';
import { PanierService } from '../panier.service';

@Component({
  selector: 'app-commande',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterLink, RouterLinkActive],
  templateUrl: './commande.html',
  styleUrl: './commande.css'
})
export class CommandeComponent implements OnInit {
  commandePassee = false;
  loading = false;
  erreur = false;
  commande = {
    prenom: '', nom: '', email: '', telephone: '',
    adresse: '', ville: '', codePostal: '',
    notes: '', paiement: 'livraison'
  };

  constructor(
    private orderService: OrderService,
    public panier: PanierService,
    private cdr: ChangeDetectorRef
  ) {}

  ngOnInit() {}

  passerCommande() {
    if (this.commande.prenom && this.commande.nom && this.commande.email &&
        this.commande.telephone && this.commande.adresse) {
      this.loading = true;
      const orderData = {
        customer_name: this.commande.prenom + ' ' + this.commande.nom,
        email: this.commande.email,
        phone: this.commande.telephone,
        address: this.commande.adresse,
        city: this.commande.ville,
        notes: this.commande.notes,
        payment_method: this.commande.paiement,
        items: this.panier.items().map(item => ({
          product_id: item.id,
          quantity: item.qty,
          price: item.price
        }))
      };

      this.orderService.createOrder(orderData).subscribe({
        next: () => {
          this.commandePassee = true;
          this.loading = false;
          this.panier.items.set([]);
          this.cdr.detectChanges();
        },
        error: (err) => {
          console.error('Erreur:', err);
          this.erreur = true;
          this.loading = false;
          this.cdr.detectChanges();
        }
      });
    }
  }
}
