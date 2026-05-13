import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, RouterLink } from '@angular/router';
import { CommonModule } from '@angular/common';
import { PanierService } from '../panier.service';

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

  produits = [
    { id: 1, nom: 'Collier Saphir Doré',  categorie: 'COLLIERS',  mat: 'OR 18K · VERMEIL · FABRIQUÉ À LA MAIN',  prix: 890,  prixOld: 1050, badge: 'NOUVEAU', image: 'images/collier-saphir.jpeg',   desc: 'Un collier d\'exception serti de pierres saphir naturelles, monté sur une chaîne en vermeil 18 carats.' },
    { id: 2, nom: 'Bague Éternité',        categorie: 'BAGUES',    mat: 'ARGENT · ZIRCON · FABRIQUÉ À LA MAIN',   prix: 650,  prixOld: null, badge: 'NOUVEAU', image: 'images/bague-eternite.jpeg',   desc: 'Une bague éternité élégante ornée de zircons scintillants, symbole d\'amour infini.' },
    { id: 3, nom: 'Bracelet Torsadé',      categorie: 'BRACELETS', mat: 'OR ROSE · 14K · FABRIQUÉ À LA MAIN',     prix: 520,  prixOld: 612,  badge: '-15%',    image: 'images/bracelet-torsade.jpeg', desc: 'Un bracelet torsadé en or rose 14 carats, alliant modernité et élégance intemporelle.' },
    { id: 4, nom: 'Boucles Soleil',        categorie: 'BOUCLES',   mat: 'OR 18K · VERMEIL · FABRIQUÉ À LA MAIN',  prix: 730,  prixOld: null, badge: '',         image: 'images/boucles-soleil.jpeg',   desc: 'Des boucles d\'oreilles en forme de soleil, rayonnantes et légères.' },
    { id: 5, nom: 'Collier Lune',          categorie: 'COLLIERS',  mat: 'ARGENT · NACRE · FABRIQUÉ À LA MAIN',    prix: 480,  prixOld: null, badge: '',         image: 'images/collier-lune.jpeg',     desc: 'Un collier délicat orné d\'une lune en nacre naturelle, symbole de féminité.' },
    { id: 6, nom: 'Bague Solitaire',       categorie: 'BAGUES',    mat: 'OR 18K · DIAMANT · FABRIQUÉ À LA MAIN',  prix: 1200, prixOld: null, badge: '',         image: 'images/bague-solitaire.jpeg',  desc: 'Une bague solitaire intemporelle sertie d\'un diamant naturel en or 18 carats.' },
    { id: 7, nom: 'Bracelet Jonc Doré',    categorie: 'BRACELETS', mat: 'OR 18K · VERMEIL · FABRIQUÉ À LA MAIN',  prix: 610,  prixOld: null, badge: 'NOUVEAU',  image: 'images/bracelet-jonc.jpeg',    desc: 'Un bracelet jonc doré au design minimaliste, parfait pour toutes les occasions.' },
    { id: 8, nom: 'Boucles Perle',         categorie: 'BOUCLES',   mat: 'ARGENT · PERLE · FABRIQUÉ À LA MAIN',    prix: 390,  prixOld: 433,  badge: '-10%',     image: 'images/boucles-perle.jpeg',    desc: 'Des boucles d\'oreilles ornées de perles naturelles, d\'une élégance classique.' },
    { id: 9, nom: 'Collier Étoile',        categorie: 'COLLIERS',  mat: 'OR ROSE · 14K · FABRIQUÉ À LA MAIN',     prix: 750,  prixOld: null, badge: '',         image: 'images/collier-etoile.jpeg',   desc: 'Un collier étoile en or rose 14 carats, délicat et lumineux.' },
  ];

  constructor(private route: ActivatedRoute, public panier: PanierService) {}

  ngOnInit() {
    this.route.params.subscribe(params => {
      const id = +params['id'];
      this.produit = this.produits.find(p => p.id === id) || this.produits[0];
    });
  }

  addToCart() {
    this.panier.ajouter(this.produit, this.qty);
    this.ajouteAuPanier = true;
    setTimeout(() => this.ajouteAuPanier = false, 2500);
  }
}