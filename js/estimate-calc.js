import { PRODUCTS, PRINT_POSITIONS, BAG_PRICE } from "./products-data.js";

export class EstimateCalculator {
  constructor() {
    this.items = [];
    this.printPositions = [];
    this.hasBagging = false;
  }

  // 商品を追加
  addItem(productId, color, sizes) {
    const product = PRODUCTS[productId];
    if (!product) return false;

    this.items.push({
      productId,
      color,
      sizes, // { size: string, quantity: number }[]
    });
    return true;
  }

  // プリント位置を設定
  setPrintPositions(positions) {
    this.printPositions = positions;
  }

  // 袋詰め設定
  setBagging(enabled) {
    this.hasBagging = enabled;
  }

  // 合計金額を計算
  calculateTotal() {
    let total = 0;
    let totalQuantity = 0;

    // 商品代金の計算
    for (const item of this.items) {
      const product = PRODUCTS[item.productId];
      for (const sizeData of item.sizes) {
        const price = product.getPrice(item.color, sizeData.size);
        total += price * sizeData.quantity;
        totalQuantity += sizeData.quantity;
      }
    }

    // プリント代金の計算
    for (const position of this.printPositions) {
      total += PRINT_POSITIONS[position] * totalQuantity;
    }

    // 袋詰め代金の計算
    if (this.hasBagging) {
      total += BAG_PRICE * totalQuantity;
    }

    return {
      total,
      totalQuantity,
      breakdown: {
        products: this.calculateProductBreakdown(),
        printing: this.calculatePrintingBreakdown(totalQuantity),
        bagging: this.hasBagging ? BAG_PRICE * totalQuantity : 0,
      },
    };
  }

  // 商品別の内訳を計算
  calculateProductBreakdown() {
    const breakdown = [];
    for (const item of this.items) {
      const product = PRODUCTS[item.productId];
      let subtotal = 0;
      let quantity = 0;

      const sizeBreakdown = item.sizes.map((sizeData) => {
        const price = product.getPrice(item.color, sizeData.size);
        subtotal += price * sizeData.quantity;
        quantity += sizeData.quantity;

        return {
          size: sizeData.size,
          quantity: sizeData.quantity,
          price: price * sizeData.quantity,
        };
      });

      breakdown.push({
        productName: product.name,
        color: item.color,
        sizes: sizeBreakdown,
        subtotal,
        quantity,
      });
    }
    return breakdown;
  }

  // プリント代金の内訳を計算
  calculatePrintingBreakdown(totalQuantity) {
    return this.printPositions.map((position) => ({
      position,
      price: PRINT_POSITIONS[position] * totalQuantity,
    }));
  }
}
