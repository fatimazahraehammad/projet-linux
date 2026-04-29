import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-produits-admin',
  templateUrl: './produits.component.html',
  styleUrls: ['./produits.component.css']
})
export class ProduitsAdminComponent implements OnInit {

  produits: any[] = [];

  constructor(private http: HttpClient) {}

  ngOnInit() {
    this.loadProduits();
  }

  loadProduits() {
    this.http.get<any[]>('http://localhost:8000/api/produits')
      .subscribe(data => this.produits = data);
  }

  deleteProduit(id: number) {
    this.http.delete(`http://localhost:8000/api/produits/${id}`)
      .subscribe(() => this.loadProduits());
  }
}