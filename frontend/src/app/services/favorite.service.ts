import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class FavoriteService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  getFavorites() {
    return this.http.get(`${this.apiUrl}/favorites`);
  }

  toggleFavorite(productId: number) {
    return this.http.post(`${this.apiUrl}/favorites/toggle`, { product_id: productId });
  }
}