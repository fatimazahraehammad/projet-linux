import { Injectable, signal } from '@angular/core';

@Injectable({ providedIn: 'root' })
export class PanierService {
  items = signal<any[]>(this.loadFromStorage());

  private loadFromStorage(): any[] {
    try {
      const data = localStorage.getItem('panier');
      return data ? JSON.parse(data) : [];
    } catch {
      return [];
    }
  }

  private saveToStorage() {
    localStorage.setItem('panier', JSON.stringify(this.items()));
  }

  ajouter(produit: any, qty: number) {
    const existant = this.items().find(i => i.id === produit.id);
    if (existant) {
      this.items.update(items => items.map(i =>
        i.id === produit.id ? { ...i, qty: i.qty + qty } : i
      ));
    } else {
      this.items.update(items => [...items, { ...produit, qty }]);
    }
    this.saveToStorage();
  }

  supprimer(id: number) {
    this.items.update(items => items.filter(i => i.id !== id));
    this.saveToStorage();
  }

  total() {
    return this.items().reduce((acc, i) => acc + i.price * i.qty, 0);
  }

  count() {
    return this.items().reduce((acc, i) => acc + i.qty, 0);
  }
}
