import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-clients-admin',
  templateUrl: './clients.component.html',
  styleUrls: ['./clients.component.css']
})
export class ClientsAdminComponent implements OnInit {

  clients: any[] = [];

  constructor(private http: HttpClient) {}

  ngOnInit() {
    this.http.get<any[]>('http://localhost:8000/api/clients')
      .subscribe(data => this.clients = data);
  }
}