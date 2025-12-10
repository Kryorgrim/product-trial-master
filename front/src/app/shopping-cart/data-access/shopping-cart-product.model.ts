import { Product } from "app/products/data-access/product.model";

export interface ShoppingCartProduct {
  id: number;
  product: Product;
  quantity: number;
}
