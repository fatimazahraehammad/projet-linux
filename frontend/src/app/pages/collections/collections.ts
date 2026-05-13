import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RouterLink, RouterLinkActive, ActivatedRoute } from '@angular/router';

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

  constructor(private route: ActivatedRoute) {}

  ngOnInit() {
    this.route.queryParams.subscribe(params => {
      if (params['categorie']) {
        this.filtreActif = params['categorie'];
      }
    });
  }
  filtres = ['TOUS', 'COLLIERS', 'BAGUES', 'BRACELETS', 'BOUCLES'];

 produits = [
  { id: 1, nom: 'Collier Saphir Doré',  mat: 'OR 18K · VERMEIL',  prix: 890,  badge: 'NOUVEAU', couleur: 'img-beige', categorie: 'COLLIERS',  image: 'images/collier-saphir.jpeg'    },
  { id: 2, nom: 'Bague Éternité',        mat: 'ARGENT · ZIRCON',   prix: 650,  badge: 'NOUVEAU', couleur: 'img-rose',  categorie: 'BAGUES',    image: 'images/bague-eternite.jpeg'    },
  { id: 3, nom: 'Bracelet Torsadé',      mat: 'OR ROSE · 14K',     prix: 520,  badge: '-15%',    couleur: 'img-vert',  categorie: 'BRACELETS', image: 'images/bracelet-torsade.jpeg'  },
  { id: 4, nom: 'Boucles Soleil',        mat: 'OR 18K · VERMEIL',  prix: 730,  badge: '',        couleur: 'img-creme', categorie: 'BOUCLES',   image: 'images/boucles-soleil.jpeg'    },
  { id: 5, nom: 'Collier Lune',          mat: 'ARGENT · NACRE',    prix: 480,  badge: '',        couleur: 'img-beige', categorie: 'COLLIERS',  image: 'images/collier-lune.jpeg'      },
  { id: 6, nom: 'Bague Solitaire',       mat: 'OR 18K · DIAMANT',  prix: 1200, badge: '',        couleur: 'img-rose',  categorie: 'BAGUES',    image: 'images/bague-solitaire.jpeg'   },
  { id: 7, nom: 'Bracelet Jonc Doré',    mat: 'OR 18K · VERMEIL',  prix: 610,  badge: 'NOUVEAU', couleur: 'img-creme', categorie: 'BRACELETS', image: 'images/bracelet-jonc.jpeg'     },
  { id: 8, nom: 'Boucles Perle',         mat: 'ARGENT · PERLE',    prix: 390,  badge: '-10%',    couleur: 'img-vert',  categorie: 'BOUCLES',   image: 'images/boucles-perle.jpeg'     },
  { id: 9, nom: 'Collier Étoile',        mat: 'OR ROSE · 14K',     prix: 750,  badge: '',        couleur: 'img-rose',  categorie: 'COLLIERS',  image: 'images/collier-etoile.jpeg'    },
];

  wished: boolean[] = new Array(this.produits.length).fill(false);

  get produitsFiltres() {
    if (this.filtreActif === 'TOUS') return this.produits;
    return this.produits.filter(p => p.categorie === this.filtreActif);
  }

  toggleWish(index: number) {
    this.wished[index] = !this.wished[index];
  }
}