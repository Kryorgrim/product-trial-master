import { Component, inject, OnInit } from "@angular/core";
import { ShoppingCartProductComponent } from "../shopping-cart-product/shopping-cart-product.component";
import { ShoppingCartService } from "app/shopping-cart/data-access/shopping-cart.service";
import { Product } from "app/products/data-access/product.model";
import { DataViewModule } from "primeng/dataview";
import { ShoppingCartProduct } from "app/shopping-cart/data-access/shopping-cart-product.model";

@Component({
  selector: "app-shopping-cart",
  templateUrl: "./shopping-cart.component.html",
  standalone: true,
  imports: [ShoppingCartProductComponent, DataViewModule],
})
export class ShoppingCartComponent implements OnInit {
  private readonly shoppingCartService = inject(ShoppingCartService);

  public readonly shoppingCartProducts = this.shoppingCartService.cartProducts

  public ngOnInit(): void {
    this.shoppingCartService.get().subscribe();
  }

  public onUpdate(cartProduct: ShoppingCartProduct) {
    this.shoppingCartService.updateProduct(cartProduct).subscribe();
  }

  public onRemove(cartProduct: ShoppingCartProduct) {
    this.shoppingCartService.removeProduct(cartProduct).subscribe();
  }

  public onDelete(cartProduct: ShoppingCartProduct) {
    this.shoppingCartService.deleteProduct(cartProduct).subscribe();
  }
}
