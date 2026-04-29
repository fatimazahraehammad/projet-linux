import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css']
})
export class DashboardComponent implements OnInit {

  stats: any = {};

  constructor(private http: HttpClient) {}

  ngOnInit() {
    this.http.get('http://localhost:8000/api/admin/stats')
      .subscribe(data => this.stats = data);
  }
}