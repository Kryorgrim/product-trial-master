import { Component, inject } from "@angular/core";
import {
  FormControl,
  FormGroup,
  ReactiveFormsModule,
  Validators,
} from "@angular/forms";
import { MessageService } from "primeng/api";
import { ButtonModule } from "primeng/button";
import { InputTextModule } from "primeng/inputtext";
import { InputTextareaModule } from "primeng/inputtextarea";
import { ToastModule } from "primeng/toast";

@Component({
  selector: "app-contact",
  templateUrl: "./contact.component.html",
  standalone: true,
  imports: [
    ReactiveFormsModule,
    InputTextModule,
    InputTextareaModule,
    ButtonModule,
    ToastModule,
  ],
})
export class ContactComponent {
  private readonly messageService = inject(MessageService);

  public submitted = false;

  contactForm = new FormGroup({
    email: new FormControl("", [Validators.required, Validators.email]),
    message: new FormControl("", [
      Validators.required,
      Validators.maxLength(300),
    ]),
  });

  isInvalid(controlName: string) {
    const control = this.contactForm.get(controlName);
    return control?.invalid && (control.touched || this.submitted);
  }

  onSubmit() {
    this.submitted = true;
    if (!this.contactForm.valid) {
      this.messageService.add({
        severity: "error",
        summary: "Erreur",
        detail: "Formulaire de contact invalide",
      });
      return;
    }

    this.messageService.add({
      severity: "success",
      summary: "Succés",
      detail: "Demande de contact envoyée avec succès",
    });
    this.contactForm.reset();
    this.submitted = false;
  }
}
