import { Component } from '@angular/core';
import { RouterLink, RouterLinkActive } from '@angular/router';
import { CommonModule } from '@angular/common';
import { PanierService } from '../panier.service';

@Component({
  selector: 'app-panier',
  standalone: true,
  imports: [CommonModule, RouterLink, RouterLinkActive],
  templateUrl: './panier.html',
  styleUrl: './panier.css'
})
export class PanierComponent {
  constructor(public panier: PanierService) {}
}