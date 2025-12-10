import { Component, computed, inject } from "@angular/core";
import { RouterModule } from "@angular/router";
import { SplitterModule } from "primeng/splitter";
import { ToolbarModule } from "primeng/toolbar";
import { PanelMenuComponent } from "./shared/ui/panel-menu/panel-menu.component";
import { ShoppingCartComponent } from "./shopping-cart/ui/shopping-cart/shopping-cart.component";
import { DialogModule } from "primeng/dialog";
import { ButtonModule } from "primeng/button";
import { BadgeModule } from "primeng/badge";
import { ShoppingCartService } from "./shopping-cart/data-access/shopping-cart.service";

@Component({
  selector: "app-root",
  templateUrl: "./app.component.html",
  styleUrls: ["./app.component.scss"],
  standalone: true,
  imports: [
    RouterModule,
    SplitterModule,
    ToolbarModule,
    PanelMenuComponent,
    ShoppingCartComponent,
    DialogModule,
    ButtonModule,
    BadgeModule,
  ],
})
export class AppComponent {
  title = "ALTEN SHOP";

  shoppingCartProductsCount = computed(() => {
    return this.shoppingCartService.cartProducts().reduce<number>(
    (sum, current) => sum + current.quantity,
    0
  )});

  public readonly shoppingCartService = inject(ShoppingCartService);

  public isDialogVisible = false;

  public onShoppingCartClick() {
    this.isDialogVisible = true;
  }
}
