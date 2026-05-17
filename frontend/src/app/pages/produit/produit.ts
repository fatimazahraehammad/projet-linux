import { Component, OnInit, ChangeDetectorRef } from '@angular/core';
import { ActivatedRoute, RouterLink } from '@angular/router';
import { CommonModule } from '@angular/common';
import { PanierService } from '../panier.service';
import { ProductService } from '../../services/product.service';

@Component({
  selector: 'app-produit',
  standalone: true,
  imports: [CommonModule, RouterLink],
  templateUrl: './produit.html',
  styleUrl: './produit.css'
})
export class ProduitComponent implements OnInit {
  longueur = '45';
  qty = 1;
  wished = false;
  openTab = 0;
  ajouteAuPanier = false;
  produit: any = null;

  constructor(
    private route: ActivatedRoute,
    public panier: PanierService,
    private productService: ProductService,
    private cdr: ChangeDetectorRef
  ) {}

  ngOnInit() {
    this.route.params.subscribe(params => {
      const slug = params['id'];
      this.productService.getProduct(slug).subscribe({
        next: (response: any) => {
          this.produit = response.data;
          this.cdr.detectChanges();
        },
        error: (err) => console.error('Erreur:', err)
      });
    });
  }

  addToCart() {
    this.panier.ajouter(this.produit, this.qty);
    this.ajouteAuPanier = true;
    setTimeout(() => this.ajouteAuPanier = false, 2500);
  }
}
