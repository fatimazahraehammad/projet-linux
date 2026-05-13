import { Injectable, signal } from '@angular/core';

@Injectable({ providedIn: 'root' })
export class PanierService {
  items = signal<any[]>([]);

  ajouter(produit: any, qty: number) {
    const existant = this.items().find(i => i.id === produit.id);
    if (existant) {
      this.items.update(items => items.map(i =>
        i.id === produit.id ? { ...i, qty: i.qty + qty } : i
      ));
    } else {
      this.items.update(items => [...items, { ...produit, qty }]);
    }
  }

  supprimer(id: number) {
    this.items.update(items => items.filter(i => i.id !== id));
  }

  total() {
    return this.items().reduce((acc, i) => acc + i.prix * i.qty, 0);
  }

  count() {
    return this.items().reduce((acc, i) => acc + i.qty, 0);
  }
}
