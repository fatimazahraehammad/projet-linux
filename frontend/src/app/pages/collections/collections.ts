import { Component } from '@angular/core';
import { RouterLink, RouterLinkActive } from '@angular/router';

@Component({
  selector: 'app-collections',
  imports: [RouterLink, RouterLinkActive],
  templateUrl: './collections.html',
  styleUrl: './collections.css',
})
export class CollectionsComponent {}