import { Routes } from '@angular/router';
import { HomeComponent } from './pages/home/home';
import { CollectionsComponent } from './pages/collections/collections';
import { ProduitComponent } from './pages/produit/produit';
import { PanierComponent } from './pages/panier/panier';
import { CommandeComponent } from './pages/commande/commande';
import { AProposComponent } from './pages/a-propos/a-propos';
import { ContactComponent } from './pages/contact/contact';

export const routes: Routes = [
  { path: '', component: HomeComponent },
  { path: 'collections', component: CollectionsComponent },
  { path: 'produit/:id', component: ProduitComponent },
  { path: 'panier', component: PanierComponent },
  { path: 'commande', component: CommandeComponent },
  { path: 'a-propos', component: AProposComponent },
  { path: 'contact', component: ContactComponent },
  { path: '**', redirectTo: '' }
];