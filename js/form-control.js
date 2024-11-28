import { PRODUCTS } from "./products-data.js";
import { EstimateCalculator } from "./estimate-calc.js";

class FormController {
  constructor() {
    this.calculator = new EstimateCalculator();

    // Step2,3を初期非表示に
    const step2 = document.querySelector(".simu-step02");
    const step3 = document.querySelector(".simu-step03");
    if (step2) step2.style.display = "none";
    if (step3) step3.style.display = "none";

    this.init();
  }

  init() {
    this.bindProductSelection();
    this.bindColorAddition();
    this.bindProductAddition();
    this.bindCalculateButton();

    // 初期状態でStep1の最初の商品を選択状態にする
    const firstProduct = document.querySelector(".step01-item");
    if (firstProduct) {
      firstProduct.click();
    }
  }

  // Step1: 商品選択の制御
  bindProductSelection() {
    const productButtons = document.querySelectorAll(".step01-item");
    productButtons.forEach((button) => {
      button.addEventListener("click", (e) => {
        // 選択状態の更新
        productButtons.forEach((btn) => btn.classList.remove("active"));
        button.classList.add("active");

        // 選択された商品に基づいてStep2を更新
        this.updateStep2Options(this.getProductIdFromButton(button));
      });
    });
  }

  // 商品IDの取得（ボタンのdata属性から取得することを想定）
  getProductIdFromButton(button) {
    // data-product-id属性から取得
    return button.dataset.productId;
  }

  // Step2: カラー・サイズオプションの更新
  updateStep2Options(productId) {
    const product = PRODUCTS[productId];
    if (!product) return;

    // Step2,3を表示
    document.querySelector(".simu-step02").style.display = "block";
    document.querySelector(".simu-step03").style.display = "block";

    // 既存のカラーブロックをクリア
    const step2 = document.querySelector(".simu-step02");
    const blocks = step2.querySelectorAll(".block");
    blocks.forEach((block) => block.remove());

    // カラー総数の更新
    const colorCountText = document.querySelector(".color-wrap p");
    if (colorCountText) {
      colorCountText.innerHTML = `<span>商品カラー</span>　${product.colors.length}colors`;
    }

    // 新しいカラーブロックを追加
    this.addNewColorBlock(step2, product);
  }

  // カラーブロックのHTML生成
  createColorBlock(product) {
    const block = document.createElement("div");
    block.className = "block";

    block.innerHTML = `
      <div class="color-wrap">
        <p><span>商品カラー</span>　${product.colors.length}colors</p>
        <div class="flex">
          <div class="select-wrap">
            <select>
              <option disabled selected value>選択してください</option>
              ${product.colors
                .map((color) => `<option value="${color}">${color}</option>`)
                .join("")}
            </select>
          </div>
          <button class="delete-block">削除</button>
          <a class="btn-chart" href="#">カラーチャートを確認</a>
        </div>
      </div>
      <div class="size-wrap">
        <p><span>サイズ/枚数</span></p>
        <div class="flex">
          ${product.sizes
            .map(
              (size) => `
            <div class="box">
              <p>${size}</p>
              <div>
                <input type="number" min="0" value="0" data-size="${size}">枚
              </div>
            </div>
          `
            )
            .join("")}
        </div>
        <a class="btn-chart" href="#">サイズチャートを確認</a>
      </div>
    `;

    // 削除ボタンの制御
    const deleteBtn = block.querySelector(".delete-block");
    if (deleteBtn) {
      deleteBtn.addEventListener("click", () => {
        if (document.querySelectorAll(".block").length > 1) {
          block.remove();
        }
      });
    }

    return block;
  }

  // カラーブロックの追加
  addNewColorBlock(container, product) {
    const block = this.createColorBlock(product);
    const addButton = container.querySelector(".add-wrap");
    if (addButton) {
      container.insertBefore(block, addButton);
    } else {
      container.appendChild(block);
    }
  }

  // カラーの追加ボタンの制御
  bindColorAddition() {
    const addColorBtn = document.querySelector(".simu-step02 .btn-add");
    if (addColorBtn) {
      addColorBtn.addEventListener("click", () => {
        const activeProduct = document.querySelector(".step01-item.active");
        if (activeProduct) {
          const productId = this.getProductIdFromButton(activeProduct);
          const product = PRODUCTS[productId];
          if (product) {
            const step2 = document.querySelector(".simu-step02");
            this.addNewColorBlock(step2, product);
          }
        }
      });
    }
  }

  // 商品の追加ボタンの制御
  bindProductAddition() {
    const addProductBtn = document.querySelector(
      ".simulation_wrap > .add-wrap .btn-add"
    );
    if (addProductBtn) {
      addProductBtn.addEventListener("click", () => {
        const newContent = this.createProductContent();
        const simulationWrap = document.querySelector(".simulation_wrap");
        simulationWrap.insertBefore(newContent, addProductBtn.parentElement);
      });
    }
  }

  // 商品コンテンツのHTML生成
  createProductContent() {
    const content = document.createElement("div");
    content.className = "simu-content";

    const template = document.querySelector(".simu-content").cloneNode(true);

    // 選択状態のリセット
    template.querySelectorAll(".step01-item").forEach((item) => {
      item.classList.remove("active");
    });

    // 入力値のリセット
    template.querySelectorAll("input").forEach((input) => {
      input.value = "";
    });
    template.querySelectorAll("select").forEach((select) => {
      select.selectedIndex = 0;
    });

    // 削除ボタンの追加
    const deleteBtn = document.createElement("button");
    deleteBtn.className = "delete-product";
    deleteBtn.textContent = "この商品を削除";
    deleteBtn.addEventListener("click", () => {
      if (document.querySelectorAll(".simu-content").length > 1) {
        content.remove();
      }
    });
    template.appendChild(deleteBtn);

    // イベントリスナーの再バインド
    this.bindProductSelection(template);
    this.bindColorAddition(template);

    content.appendChild(template);
    return content;
  }

  // 見積り計算ボタンの制御
  bindCalculateButton() {
    const calculateBtn = document.querySelector(".btn-result");
    if (calculateBtn) {
      calculateBtn.addEventListener("click", () => {
        const formData = this.collectFormData();
        if (this.validateFormData(formData)) {
          this.calculator = new EstimateCalculator();
          this.applyFormData(formData);
          const result = this.calculator.calculateTotal();
          this.displayResult(result);
        }
      });
    }
  }

  // フォームデータの収集
  collectFormData() {
    const products = [];

    document.querySelectorAll(".simu-content").forEach((content) => {
      const activeProduct = content.querySelector(".step01-item.active");
      if (!activeProduct) return;

      const productId = this.getProductIdFromButton(activeProduct);
      const colorBlocks = content.querySelectorAll(".block");
      const colors = [];

      colorBlocks.forEach((block) => {
        const colorSelect = block.querySelector(".color-wrap select");
        const colorData = {
          color: colorSelect.value,
          sizes: [],
        };

        block
          .querySelectorAll('.size-wrap input[type="number"]')
          .forEach((input) => {
            const quantity = parseInt(input.value) || 0;
            if (quantity > 0) {
              colorData.sizes.push({
                size: input.dataset.size,
                quantity: quantity,
              });
            }
          });

        if (colorData.sizes.length > 0) {
          colors.push(colorData);
        }
      });

      if (colors.length > 0) {
        products.push({ productId, colors });
      }
    });

    // プリント位置の収集
    const printPositions = [];
    const printSelect = document.querySelector(".print-position select");
    if (printSelect && printSelect.value) {
      printPositions.push(printSelect.value);
    }

    // 袋詰めの設定を収集
    const hasBagging = document.querySelector("#ari").checked;

    return { products, printPositions, hasBagging };
  }

  // フォームデータのバリデーション
  validateFormData(formData) {
    if (formData.products.length === 0) {
      alert("商品を選択してください。");
      return false;
    }

    for (const product of formData.products) {
      if (product.colors.length === 0) {
        alert("カラーを選択してください。");
        return false;
      }

      for (const colorData of product.colors) {
        if (colorData.sizes.length === 0) {
          alert("サイズと枚数を入力してください。");
          return false;
        }
      }
    }

    if (formData.printPositions.length === 0) {
      alert("プリント箇所を選択してください。");
      return false;
    }

    return true;
  }

  // フォームデータをCalculatorに適用
  applyFormData(formData) {
    formData.products.forEach((product) => {
      product.colors.forEach((colorData) => {
        this.calculator.addItem(
          product.productId,
          colorData.color,
          colorData.sizes
        );
      });
    });

    this.calculator.setPrintPositions(formData.printPositions);
    this.calculator.setBagging(formData.hasBagging);
  }

  // 結果の表示
  displayResult(result) {
    const resultContent = document.querySelector(".result-content .block_wrap");
    if (!resultContent) return;

    let html = "";

    // 商品情報の表示
    result.breakdown.products.forEach((product) => {
      html += `
        <div class="block item-info">
          <p class="name">${product.productName}</p>
          <div class="group flex">
            <p class="color">カラー：<span>${product.color}</span></p>
            <div class="child">
              ${product.sizes
                .map(
                  (size) => `
                <div>
                  <p class="size">サイズ：<span>${size.size}</span></p>
                  <p class="quanntity">数量：<span>${size.quantity}枚</span></p>
                  <p class="price"><span>${size.price.toLocaleString()}円</span></p>
                </div>
              `
                )
                .join("")}
            </div>
          </div>
          <div class="item-total flex">
            <p>${product.quantity}枚</p>
            <p><span>${product.subtotal.toLocaleString()}円</span></p>
          </div>
        </div>
      `;
    });

    // プリント位置の表示
    if (result.breakdown.printing.length > 0) {
      html += `
        <div class="block print-position">
          <p class="name">プリント箇所</p>
          <div class="group flex">
            <p>${result.breakdown.printing.map((p) => p.position).join("/")}</p>
            <p><span>${result.breakdown.printing
              .reduce((sum, p) => sum + p.price, 0)
              .toLocaleString()}円</span></p>
          </div>
        </div>
      `;
    }

    // 袋詰めオプションの表示
    if (result.breakdown.bagging > 0) {
      html += `
        <div class="block option">
          <p class="name">オプション</p>
          <div class="group flex">
            <p>袋詰め：あり（45円×${result.totalQuantity}枚）</p>
            <p><span>${result.breakdown.bagging.toLocaleString()}円</span></p>
          </div>
        </div>
      `;
    }

    // 合計金額の表示
    html += `
      <div class="total-price flex">
        <p>合計金額<span>(税込)</span></p>
        <p class="price">${result.total.toLocaleString()}円</p>
      </div>
    `;

    resultContent.innerHTML = html;

    // 結果エリアを表示
    document.querySelector(".result-wrap").style.display = "block";
  }
}

// DOMContentLoadedで初期化
document.addEventListener("DOMContentLoaded", () => {
  new FormController();
});
