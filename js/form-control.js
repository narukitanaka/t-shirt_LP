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
  }

  // Step1: 商品選択の制御
  bindProductSelection(container = document) {
    const productButtons = container.querySelectorAll(".step01-item");
    productButtons.forEach((button) => {
      button.addEventListener("click", (e) => {
        // 選択状態の更新（現在の商品グループ内のみ）
        const currentSimuContent = button.closest(".simu-content");
        currentSimuContent.querySelectorAll(".step01-item").forEach((btn) => {
          btn.classList.remove("active");
        });
        button.classList.add("active");

        // Step2を表示（現在の商品グループのみ）
        const step2 = currentSimuContent.querySelector(".simu-step02");
        if (step2) step2.style.display = "block";

        step2.scrollIntoView({ behavior: "smooth" });

        // 商品追加ボタンを表示
        const addProductButton = document.querySelector(
          ".simulation_wrap > .add-wrap"
        );
        if (addProductButton) {
          addProductButton.style.display = "block";
        }

        // Step3を含むsimu-content要素とstep3自体を表示
        const step3Content = document.querySelector(
          ".simu-content:last-of-type"
        );
        if (step3Content) {
          step3Content.style.display = "block";
          const step3 = step3Content.querySelector(".simu-step03");
          if (step3) {
            step3.style.display = "block";
          }
        }

        // 選択された商品に基づいてStep2を更新（currentSimuContentを渡す）
        const productId = this.getProductIdFromButton(button);
        this.updateStep2Options(productId, currentSimuContent);
      });
    });
  }

  // 商品IDの取得（ボタンのdata属性から取得することを想定）
  getProductIdFromButton(button) {
    // data-product-id属性から取得
    return button.dataset.productId;
  }

  // Step2: カラー・サイズオプションの更新
  updateStep2Options(productId, currentSimuContent) {
    // currentSimuContentパラメータを追加
    const product = PRODUCTS[productId];
    if (!product) return;

    // 現在の商品のstep2を取得
    const step2 = currentSimuContent.querySelector(".simu-step02");
    if (!step2) return;

    const blocks = step2.querySelectorAll(".block");
    blocks.forEach((block) => block.remove());

    // カラー総数の更新
    const colorCountText = step2.querySelector(".color-wrap p");
    if (colorCountText) {
      colorCountText.innerHTML = `<span>商品カラー</span>　${product.colors.length}colors`;
    }

    // 新しいカラーブロックを追加
    this.addNewColorBlock(step2, product);
  }

  // カラーブロックのHTML生成
  createColorBlock(product, isAdditional = false) {
    const block = document.createElement("div");
    block.className = "block";

    block.innerHTML = `
    ${isAdditional ? '<button class="delete-block"></button>' : ""}
    <div class="color-wrap">
      <p>商品カラー　${product.colors.length}colors</p>
      <div class="flex">
        <div class="select-wrap">
          <select>
            <option disabled selected value>選択してください</option>
            ${product.colors
              .map((color) => `<option value="${color}">${color}</option>`)
              .join("")}
          </select>
        </div>
        <a class="btn-chart" href="${
          product.charts.color
        }" target="_blank">カラーチャートを確認</a>
      </div>
    </div>
    <div class="size-wrap">
      <p>サイズ/枚数</p>
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
      <a class="btn-chart" href="${
        product.charts.size
      }" target="_blank">サイズチャートを確認</a>
    </div>
  `;

    // 追加ブロックの場合のみ削除ボタンの制御を追加
    if (isAdditional) {
      const deleteBtn = block.querySelector(".delete-block");
      if (deleteBtn) {
        deleteBtn.addEventListener("click", () => {
          block.remove();
        });
      }
    }

    return block;
  }

  // カラーブロックの追加
  addNewColorBlock(container, product) {
    // 最初のブロック追加時はisAdditional = false
    const isFirstBlock = container.querySelectorAll(".block").length === 0;
    const block = this.createColorBlock(product, !isFirstBlock);

    const addButton = container.querySelector(".add-wrap");
    if (addButton) {
      container.insertBefore(block, addButton);
    } else {
      container.appendChild(block);
    }
  }

  // カラーの追加ボタンの制御
  bindColorAddition(container = document) {
    const addColorBtns = container.querySelectorAll(".simu-step02 .btn-add");
    addColorBtns.forEach((addColorBtn) => {
      addColorBtn.addEventListener("click", () => {
        const simuContent = addColorBtn.closest(".simu-content");
        const activeProduct = simuContent.querySelector(".step01-item.active");
        if (activeProduct) {
          const productId = this.getProductIdFromButton(activeProduct);
          const product = PRODUCTS[productId];
          if (product) {
            const step2 = simuContent.querySelector(".simu-step02");
            this.addNewColorBlock(step2, product);
          }
        }
      });
    });
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
    // step1とstep2だけをクローン
    const content = document.querySelector(".simu-content").cloneNode(true);

    // step3は含めない
    const step3 = content.querySelector(".simu-step03");
    if (step3) {
      step3.remove();
    }

    // 選択状態のリセット
    content.querySelectorAll(".step01-item").forEach((item) => {
      item.classList.remove("active");
    });

    // 入力値のリセット
    content.querySelectorAll("input").forEach((input) => {
      if (input.type === "number") {
        input.value = "";
      }
    });
    content.querySelectorAll("select").forEach((select) => {
      select.selectedIndex = 0;
    });

    // Step2を非表示に
    const step2 = content.querySelector(".simu-step02");
    if (step2) step2.style.display = "none";

    // 削除ボタンの追加
    const deleteBtn = document.createElement("button");
    deleteBtn.className = "delete-product";
    deleteBtn.textContent = "";
    deleteBtn.addEventListener("click", () => {
      if (document.querySelectorAll(".simu-content").length > 1) {
        content.remove();
      }
    });
    content.appendChild(deleteBtn);

    // イベントリスナーの再バインド
    this.bindProductSelection(content);
    this.bindColorAddition(content);

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

    // プリント位置と袋詰めの収集は共通のstep3から
    const printPositions = [];
    document
      .querySelectorAll('.print-position input[type="checkbox"]:checked')
      .forEach((checkbox) => {
        printPositions.push(checkbox.value);
      });

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
      alert("プリント箇所を1つ以上選択してください。");
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

    // 商品情報の表示（simu-contentごとにまとめる）
    const products = result.breakdown.products;
    let currentProductIndex = 0;
    const simuContents = document.querySelectorAll(".simu-content");

    simuContents.forEach((content) => {
      // この simu-content に関連する商品をまとめる
      const productCount = content.querySelectorAll(".block").length;
      const contentProducts = products.slice(
        currentProductIndex,
        currentProductIndex + productCount
      );
      currentProductIndex += productCount;

      if (contentProducts.length > 0) {
        html += `
        <div class="block item-info">
          <p class="name">${contentProducts[0].productName}</p>
          ${contentProducts
            .map(
              (product) => `
            <div class="group flex">
              <p class="color">カラー：<span>${product.color}</span></p>
              <div class="child">
                ${product.sizes
                  .map(
                    (size) => `
                  <div>
                    <p class="size">サイズ：<span>${size.size}</span></p>
                    <p class="quanntity">数量：<span>${
                      size.quantity
                    }枚</span></p>
                    <p class="price"><span>${size.price.toLocaleString()}円</span></p>
                  </div>
                `
                  )
                  .join("")}
              </div>
            </div>
          `
            )
            .join("")}
          <div class="item-total flex">
            <p>${contentProducts.reduce((sum, p) => sum + p.quantity, 0)}枚</p>
            <p><span>${contentProducts
              .reduce((sum, p) => sum + p.subtotal, 0)
              .toLocaleString()}円</span></p>
          </div>
        </div>
      `;
      }
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

    // 結果をHTMLに反映
    resultContent.innerHTML = html;
    document.querySelector(".result-wrap").style.display = "block";

    // お問い合わせフォームを表示
    document.querySelector(".contact-wrap").style.display = "block";

    // 見積り結果を隠しフィールドに格納
    const estimateResultText = this.formatEstimateResult(result);
    const hiddenField = document.querySelector("#estimateResult");
    if (hiddenField) {
      hiddenField.value = estimateResultText;
    }
  }

  formatEstimateResult(result) {
    let text = "\n\n";

    // 商品情報
    result.breakdown.products.forEach((product) => {
      text += `【商品】${product.productName}\n`;
      text += `・カラー：${product.color}\n`;

      // サイズと数量
      product.sizes.forEach((size) => {
        text += `・${size.size}サイズ：${
          size.quantity
        }枚（${size.price.toLocaleString()}円）\n`;
      });

      text += `・小計：${product.subtotal.toLocaleString()}円\n`;
    });

    // プリント箇所
    if (result.breakdown.printing.length > 0) {
      text += "【プリント箇所】\n";
      result.breakdown.printing.forEach((p) => {
        text += `・${p.position}：${p.price.toLocaleString()}円\n`;
      });
      text += `・小計：${result.breakdown.printing
        .reduce((sum, p) => sum + p.price, 0)
        .toLocaleString()}円\n\n`;
    }

    // 袋詰めオプション
    text += "【オプション】\n";
    if (result.breakdown.bagging > 0) {
      text += `・袋詰め：あり（${
        result.totalQuantity
      }枚 × 45円 = ${result.breakdown.bagging.toLocaleString()}円）\n`;
    } else {
      text += `・袋詰め：なし（ひとまとめにして梱包）\n\n`;
    }

    // 合計金額
    text += `【合計金額】${result.total.toLocaleString()}円（税込）\n\n`;

    return text;
  }
}

// DOMContentLoadedで初期化
document.addEventListener("DOMContentLoaded", () => {
  new FormController();
});
