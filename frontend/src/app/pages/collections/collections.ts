import { Component, OnInit, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RouterLink, RouterLinkActive, ActivatedRoute } from '@angular/router';
import { ProductService } from '../../services/product.service';

@Component({
  selector: 'app-collections',
  standalone: true,
  imports: [CommonModule, RouterLink, RouterLinkActive, FormsModule],
  templateUrl: './collections.html',
  styleUrl: './collections.css'
})
export class CollectionsComponent implements OnInit {
  filtreActif = 'TOUS';
  triActif = 'nouveautes';
  produits: any[] = [];
  wished: boolean[] = [];

  filtres = ['TOUS', 'COLLIERS', 'BAGUES', 'BRACELETS', 'BOUCLES'];

  constructor(
    private route: ActivatedRoute,
    private productService: ProductService,
    private cdr: ChangeDetectorRef
  ) {}

  ngOnInit() {
    this.route.queryParams.subscribe(params => {
      if (params['categorie']) {
        this.filtreActif = params['categorie'];
      }
    });

    this.productService.getProducts().subscribe({
      next: (response: any) => {
        this.produits = response.data.data;
        this.wished = new Array(this.produits.length).fill(false);
        this.cdr.detectChanges();
      },
      error: (err) => console.error('Erreur:', err)
    });
  }

  get produitsFiltres() {
    if (this.filtreActif === 'TOUS') return this.produits;
    return this.produits.filter(p =>
      p.category?.name?.toUpperCase().includes(this.filtreActif) ||
      p.category?.slug?.toUpperCase().includes(this.filtreActif.toLowerCase())
    );
  }

  toggleWish(index: number) {
    this.wished[index] = !this.wished[index];
  }
}
