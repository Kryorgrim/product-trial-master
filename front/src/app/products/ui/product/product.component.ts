import { Component, computed, EventEmitter, input, output, Output } from "@angular/core";
import { FormsModule } from "@angular/forms";
import { Product } from "app/products/data-access/product.model";
import { ButtonModule } from "primeng/button";
import { CardModule } from "primeng/card";
import { RatingModule } from 'primeng/rating';

@Component({
  selector: "app-product",
  templateUrl: "./product.component.html",
  standalone: true,
  imports: [ButtonModule, CardModule, RatingModule, FormsModule]
})
export class ProductComponent {
  product = input.required<Product>();

  onDelete = output<Product>();

  onUpdate = output<Product>();

  onAdd = output<Product>();

  public readonly editedProduct = computed(() => ({ ...this.product() }));

  getStockLabel() {
    switch (this.editedProduct().inventoryStatus) {
      case 'INSTOCK':
          return "En stock";
      case "LOWSTOCK":
          return "Faible stock";
      default:
          return "Rupture de stock";
    }
  }

  onDeleteClick() {
    this.onDelete.emit(this.editedProduct());
  }

  onUpdateClick() {
    this.onUpdate.emit(this.editedProduct());
  }

  onAddClick() {
    this.onAdd.emit(this.editedProduct());
  }
}
