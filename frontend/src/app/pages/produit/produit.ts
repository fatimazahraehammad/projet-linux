import { Component } from '@angular/core';
import { RouterLink, RouterLinkActive } from '@angular/router';

@Component({
  selector: 'app-produit',
  imports: [RouterLink, RouterLinkActive],
  templateUrl: './produit.html',
  styleUrl: './produit.css',
})
export class ProduitComponent {}