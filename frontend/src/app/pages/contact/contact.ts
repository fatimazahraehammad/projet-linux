import { Component, afterNextRender, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RouterLink, RouterLinkActive } from '@angular/router';
import { ContactService } from '../../services/contact.service';

@Component({
  selector: 'app-contact',
  standalone: true,
  imports: [CommonModule, FormsModule, RouterLink, RouterLinkActive],
  templateUrl: './contact.html',
  styleUrl: './contact.css'
})
export class ContactComponent {
  envoye = false;
  erreur = false;
  loading = false;
  form = { nom: '', email: '', sujet: '', message: '' };

  constructor(private contactService: ContactService, private cdr: ChangeDetectorRef) {}

  envoyerMessage() {
    if (this.form.nom && this.form.email && this.form.message) {
      this.loading = true;
      this.contactService.sendMessage({
        name: this.form.nom,
        email: this.form.email,
        subject: this.form.sujet,
        message: this.form.message
      }).subscribe({
        next: () => {
          this.envoye = true;
          this.loading = false;
          this.form = { nom: '', email: '', sujet: '', message: '' };
          this.cdr.detectChanges();
          setTimeout(() => {
            this.envoye = false;
            this.cdr.detectChanges();
          }, 4000);
        },
        error: (err) => {
          console.error('Erreur:', err);
          this.erreur = true;
          this.loading = false;
          this.cdr.detectChanges();
        }
      });
    }
  }
}
