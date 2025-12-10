import { Component, computed, input, output } from "@angular/core";
import { Product } from "app/products/data-access/product.model";
import { ShoppingCartProduct } from "app/shopping-cart/data-access/shopping-cart-product.model";
import { ButtonModule } from "primeng/button";
import { CardModule } from "primeng/card";

@Component({
  selector: "app-shopping-cart-product",
  templateUrl: "./shopping-cart-product.component.html",
  standalone: true,
  imports: [ButtonModule, CardModule],
})
export class ShoppingCartProductComponent {
  cartProduct = input.required<ShoppingCartProduct>();

  onDelete = output<ShoppingCartProduct>();

  onUpdate = output<ShoppingCartProduct>();

  onRemove = output<ShoppingCartProduct>();

  price = computed(() => this.cartProduct().quantity * this.cartProduct().product.price)

  onDeleteClick() {
    this.onDelete.emit(this.cartProduct());
  }

  onUpdateClick() {
    this.onUpdate.emit(this.cartProduct());
  }

  onRemoveClick() {
    this.onRemove.emit(this.cartProduct());
  }
}
