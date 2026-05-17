import { Component, afterNextRender, ChangeDetectorRef } from '@angular/core';
import { RouterLink, RouterLinkActive } from '@angular/router';
import { CommonModule } from '@angular/common';
import { ProductService } from '../../services/product.service';

@Component({
  selector: 'app-home',
  imports: [RouterLink, RouterLinkActive, CommonModule],
  templateUrl: './home.html',
  styleUrl: './home.css',
})
export class HomeComponent {
  products: any[] = [];
  loading = true;

  constructor(private productService: ProductService, private cdr: ChangeDetectorRef) {
    afterNextRender(() => {
      this.productService.getProducts().subscribe({
        next: (response: any) => {
          this.products = response.data.data.slice(0, 3);
          this.loading = false;
          this.cdr.detectChanges();
        },
        error: (err) => {
          console.error('Erreur:', err);
          this.loading = false;
          this.cdr.detectChanges();
        }
      });
    });
  }
}
