import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent {

  email = '';
  password = '';

  constructor(private http: HttpClient, private router: Router) {}

  login() {
    this.http.post<any>('http://localhost:8000/api/admin/login', {
      email: this.email,
      password: this.password
    }).subscribe(res => {
      localStorage.setItem('token', res.token);
      this.router.navigate(['/admin/dashboard']);
    });
  }
}