import { inject, Injectable, signal } from "@angular/core";
import { Product } from "app/products/data-access/product.model";
import { ShoppingCartProduct } from "./shopping-cart-product.model";
import { HttpClient } from "@angular/common/http";
import { catchError, Observable, of, tap } from "rxjs";

@Injectable({
  providedIn: "root",
})
export class ShoppingCartService {
  private readonly http = inject(HttpClient);
  private readonly path = "/api/shopping-cart";

  private readonly _cartProducts = signal<ShoppingCartProduct[]>([]);

  public readonly cartProducts = this._cartProducts.asReadonly();

  public get(): Observable<ShoppingCartProduct[]> {
    return this.http.get<ShoppingCartProduct[]>(this.path).pipe(
      catchError((error) => {
        return [];
      }),
      tap((products) => this._cartProducts.set(products))
    );
  }

  private patchShoppingCartProduct(
    cartProduct: ShoppingCartProduct,
    add: boolean
  ): Observable<ShoppingCartProduct[]> {
    const newQuantity = add
      ? cartProduct.quantity + 1
      : cartProduct.quantity - 1;
    return this.http
      .patch<ShoppingCartProduct[]>(`${this.path}/products/${cartProduct.id}`, {
        quantity: newQuantity,
      })
      .pipe(
        catchError(() => {
          const cartProducts = this._cartProducts();
          const index = cartProducts.findIndex((cp) => cp.id == cartProduct.id);
          cartProducts[index].quantity = newQuantity;
          return of(cartProducts);
        }),
        tap((cartProducts) =>
          this._cartProducts.update(() => [...cartProducts])
        )
      );
  }

  public addProduct(product: Product): Observable<ShoppingCartProduct[]> {
    return this.http
      .post<ShoppingCartProduct[]>(`${this.path}/products`, {
        productId: product.id,
        quantity: 1,
      })
      .pipe(
        catchError(() => {
          const cartProducts = [...this._cartProducts()];
          let maxId = 1;

          if (cartProducts.length > 0) {
            maxId = Math.max(...cartProducts.map((cp) => cp.id));
          }

          cartProducts.push({
            id: maxId,
            product: product,
            quantity: 1,
          });

          return of(cartProducts);
        }),
        tap((cartProducts) => this._cartProducts.set(cartProducts))
      );
  }

  public updateProduct(cartProduct: ShoppingCartProduct): Observable<ShoppingCartProduct[]>
  {
    return this.patchShoppingCartProduct(cartProduct, true);
  }

  public removeProduct(cartProduct: ShoppingCartProduct): Observable<ShoppingCartProduct[]> {
    if (cartProduct.quantity == 1) {
      return this.deleteProduct(cartProduct);
    }
    return this.patchShoppingCartProduct(cartProduct, false);
  }

  public deleteProduct(cartProduct: ShoppingCartProduct): Observable<ShoppingCartProduct[]> {
    return this.http
      .delete<ShoppingCartProduct[]>(`${this.path}/products/`)
      .pipe(
        catchError(() => {
          const cartProducts = this._cartProducts();
          const index = cartProducts.findIndex((cp) => cp.id == cartProduct.id);
          cartProducts.splice(index, 1);
          return of(cartProducts);
        }),
        tap((cartProducts) =>
          this._cartProducts.update(() => [...cartProducts])
        )
      );
  }
}
