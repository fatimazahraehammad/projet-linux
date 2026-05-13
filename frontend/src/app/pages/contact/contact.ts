import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RouterLink, RouterLinkActive } from '@angular/router';

@Component({
  selector: 'app-contact',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterLink, RouterLinkActive],
  templateUrl: './contact.html',
  styleUrl: './contact.css'
})
export class ContactComponent {
  envoye = false;
  form = { nom: '', email: '', sujet: '', message: '' };

  envoyerMessage() {
    if (this.form.nom && this.form.email && this.form.message) {
      this.envoye = true;
      setTimeout(() => {
        this.envoye = false;
        this.form = { nom: '', email: '', sujet: '', message: '' };
      }, 4000);
    }
  }
}