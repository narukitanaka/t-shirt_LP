export const PRODUCTS = {
  "united-athle-dry": {
    id: "united-athle-dry",
    name: "【United Athle】4.1オンス ドライアスレチック Tシャツ",
    colors: [
      "ホワイト",
      "ブラック",
      "グレー",
      "イエロー",
      "ゴールド",
      "ミントグリーン",
      "ブライトグリーン",
      "グリーン",
      "オリーブ",
      "ライムグリーン",
      "パープル",
      "オレンジ",
      "ピンク",
      "レッド",
      "バーガンディ",
      "ラベンダー",
      "アクアブルー",
      "コバルトブルー",
      "ネイビー",
      "インディゴ",
      "マリンブルー",
      "OD",
      "蛍光イエロー",
      "蛍光オレンジ",
      "蛍光ピンク",
      "ローズレッド",
      "ガンメタル",
      "カナリアイエロー",
      "ディープパープル",
      "コヨーテ",
      "ライトブルー",
      "アイビーグリーン",
      "トロピカルピンク",
      "アイスグレー",
      "ターコイズブルー",
      "バイオレットパープル",
      "ベビーピンク",
      "ヘザーチャコール",
      "ヘザーピンク",
      "ヘザーブルー",
    ],
    sizes: ["S", "M", "L", "XL", "XXL", "XXXL", "XXXXL", "5L", "6L"],
    getPrice: function (color, size) {
      if (["S", "M", "L", "XL"].includes(size)) return 556;
      if (["XXL", "XXXL", "XXXXL", "5L"].includes(size)) return 598;
      if (size === "6L") return 741;
      return 0;
    },
    charts: {
      color: "./images/item/UnitedAthle_4.1/color-chart_UnitedAthle-4.1.pdf",
      size: "./images/item/UnitedAthle_4.1/size_chart_UnitedAthle-4.1.pdf",
    },
  },
  "united-athle-quality": {
    id: "united-athle-quality",
    name: "【United Athle】5.6オンス ハイクオリティー Tシャツ",
    colors: [
      "ホワイト",
      "ブラック",
      "アッシュ",
      "ミックスグレー",
      "チャコール",
      "オートミール",
      "ライトグレー",
      "ナチュラル",
      "イエロー",
      "ゴールド",
      "ミントグリーン",
      "グリーン",
      "シティグリーン",
      "メロン",
      "ダークブラウン",
      "ライトベージュ",
      "パープル",
      "オレンジ",
      "ピンク",
      "レッド",
      "バーガンディ",
      "ラベンダー",
      "サックス",
      "アクアブルー",
      "ロイヤルブルー",
      "ネイビー",
      "インディゴ",
      "ストレート",
      "スミ",
      "カナリアイエロー",
      "バニラホワイト",
      "ハイレッド",
      "アシッドブルー",
      "バナナ",
      "ライトイエロー",
      "ライトブルー",
      "ライトパープル",
      "ライトピンク",
      "アイビーグリーン",
      "トロピカルピンク",
      "サンドカーキ",
      "ターコイズブルー",
      "バイオレットパープル",
      "サンドベージュ",
      "アプリコット",
      "アップルグリーン",
      "ベビーピンク",
      "ダークヘイザーネイビー",
      "ダークネイビー",
      "ヘザーブラック",
      "ライトオリーブ",
      "ヘイジーブラック",
      "ヘイジーネイビー",
      "ヘイジーグリーン",
      "ヘイジーレッド",
      "ヘイジーイエロー",
    ],
    sizes: ["S", "M", "L", "XL", "XXL", "XXXL"],
    getPrice: function (color, size) {
      const isWhite = color === "ホワイト";
      if (["S", "M", "L", "XL"].includes(size)) {
        return isWhite ? 702 : 741;
      }
      if (size === "XXL") {
        return isWhite ? 825 : 906;
      }
      if (size === "XXXL") {
        return isWhite ? 918 : 995;
      }
      return 0;
    },
    charts: {
      color: "./images/item/UnitedAthle_5.6/color-chart_UnitedAthle-5.6.pdf",
      size: "./images/item/UnitedAthle_5.6/size_chart_UnitedAthle-5.6.pdf",
    },
  },
  "printstar-light": {
    id: "printstar-light",
    name: "【Printstar】4.0オンス ライトウェイト Tシャツ",
    colors: [
      "ホワイト",
      "イエロー",
      "ライトピンク",
      "ピーチ",
      "ブライトグリーン",
      "ライトブルー",
      "歪グレー",
      "デイジー",
      "ピンク",
      "グリーン",
      "ターコイズ",
      "オリーブ",
      "オレンジ",
      "ピンク",
      "ライトパープル",
      "ロイヤルブルー",
      "ブラック",
      "レッド",
      "バーガンディ",
      "パープル",
      "ネイビー",
    ],
    sizes: ["150", "160", "S", "M", "L", "XL", "XXL"],
    getPrice: function (color, size) {
      const isWhite = color === "ホワイト";
      if (["150", "160"].includes(size)) {
        return isWhite ? 546 : 612;
      }
      if (["S", "M", "L", "XL"].includes(size)) {
        return isWhite ? 568 : 660;
      }
      if (size === "XXL") {
        return isWhite ? 682 : 753;
      }
      return 0;
    },
    charts: {
      color: "./images/item/printster_4.0/color-chart_Printstar-4.0.pdf",
      size: "./images/item/printster_4.0/size_chart_Printstar-4.0.pdf",
    },
  },
  "printstar-heavy": {
    id: "printstar-heavy",
    name: "【Printstar】5.6オンス ヘビーウェイト Tシャツ",
    colors: [
      "ホワイト",
      "アイボリー",
      "ナチュラル",
      "ライトイエロー",
      "ライトピンク",
      "ライトパープル",
      "ライトブルー",
      "アイスグリーン",
      "ライトグリーン",
      "アッシュ",
      "オートミール",
      "ゴールドイエロー",
      "イエロー",
      "ピーチ",
      "ラベンダー",
      "サックス",
      "ミントグリーン",
      "ライム",
      "杢グレー",
      "シルバーグレー",
      "コーラオレンジ",
      "デイジー",
      "ピンク",
      "ミディアムブルー",
      "シーブルー",
      "アクア",
      "ブライトグリーン",
      "チャコール",
      "デニム",
      "オレンジ",
      "サンセットオレンジ",
      "ホットピンク",
      "ロイヤルブルー",
      "ターコイズ",
      "ミント",
      "グリーン",
      "スモークブラック",
      "アーミーグリーン",
      "レッド",
      "イタリアンレッド",
      "ガーネットレッド",
      "ジャパンブルー",
      "インディゴ",
      "アイビーグリーン",
      "オリーブ",
      "ブラック",
      "フォレスト",
      "バーガンディ",
      "パープル",
      "ネイビー",
      "メトロブルー",
      "ホワイト×ブルー",
      "ホワイト×レッド",
      "ホワイト×ブラック",
    ],
    sizes: [
      "100",
      "110",
      "120",
      "130",
      "140",
      "150",
      "160",
      "XS",
      "S",
      "M",
      "L",
      "XL",
      "XXL",
      "XXXL",
      "4XL",
      "5XL",
      "WM",
      "WL",
    ],
    getPrice: function (color, size) {
      const isWhite = color === "ホワイト";
      if (["100", "110", "120", "130", "140", "150", "160"].includes(size)) {
        return isWhite ? 652 : 700;
      }
      if (["XS", "S", "M", "L", "XL", "WM", "WL"].includes(size)) {
        return isWhite ? 700 : 744;
      }
      if (["XXL", "XXXL", "4XL", "5XL"].includes(size)) {
        return isWhite ? 836 : 880;
      }
      return 0;
    },
    charts: {
      color: "./images/item/Printstar_5.6/color-chart_Printstar_5.6.pdf",
      size: "./images/item/Printstar_5.6/size_chart_Printstar-5.6.pdf",
    },
  },
  "printstar-super": {
    id: "printstar-super",
    name: "【Printstar】7.4オンス スーパーヘビー Tシャツ",
    colors: [
      "ホワイト",
      "ライトベージュ",
      "ピンク",
      "ライトブルー",
      "ライトグリーン",
      "アイボリー",
      "イエロー",
      "ダスティピンク",
      "ダスティブルー",
      "グリーン",
      "杢グレー",
      "デイジー",
      "ホットピンク",
      "ターコイズ",
      "アーミーグリーン",
      "スモークブラック",
      "オレンジ",
      "ライトパープル",
      "ロイヤルブルー",
      "バーガンディ",
      "ブラック",
      "レッド",
      "パープル",
      "ネイビー",
    ],
    sizes: ["XS", "S", "M", "L", "XL", "2XL", "3XL"],
    getPrice: function (color, size) {
      const isWhite = color === "ホワイト";
      if (["XS", "S", "M", "L", "XL"].includes(size)) {
        return isWhite ? 880 : 946;
      }
      if (["2XL", "3XL"].includes(size)) {
        return isWhite ? 1039 : 1092;
      }
      return 0;
    },
    charts: {
      color: "./images/item/Printstar_7.4/color-chart_Printstar_7.4.pdf",
      size: "./images/item/Printstar_7.4/size_chart_Printstar-7.4.pdf",
    },
  },
  "glimmer-dry": {
    id: "glimmer-dry",
    name: "【glimmer】4.4オンス ドライ Tシャツ",
    colors: [
      "ホワイト",
      "ライトイエロー",
      "ライトベージュ",
      "ライトピンク",
      "ライトパープル",
      "ライトブルー",
      "メロン",
      "ライトグリーン",
      "シルバーグレー",
      "イエロー",
      "コヨーテ",
      "ピンク",
      "サックス",
      "ターコイズ",
      "ミントグリーン",
      "ライム",
      "グレー",
      "デイジー",
      "レッド",
      "ホットピンク",
      "ロイヤルブルー",
      "ミディアムブルー",
      "ミントブルー",
      "ブライトグリーン",
      "ダークグレー",
      "オレンジ",
      "ガーネットレッド",
      "ラベンダー",
      "インディゴ",
      "ジャパンブルー",
      "アイビーグリーン",
      "グリーン",
      "ブラック",
      "サンセットオレンジ",
      "バーガンディ",
      "パープル",
      "ネイビー",
      "メトロブルー",
      "オリーブ",
      "アーミーグリーン",
      "蛍光イエロー",
      "蛍光ピンク",
      "蛍光オレンジ",
      "ホワイト×ロイヤルブルー",
      "イエロー×グリーン",
      "ロイヤルブルー×ブラック",
      "ガーネットレッド×ブラック",
      "ブラック×ターコイズ",
      "ミックスグレー",
      "ミックスピンク",
      "ミックスレッド",
      "ミックスブルー",
      "ミックススパープル",
    ],
    sizes: [
      "100",
      "110",
      "120",
      "130",
      "140",
      "150",
      "SS",
      "S",
      "M",
      "L",
      "LL",
      "3L",
      "4L",
      "5L",
      "6L",
      "7L",
      "WM",
      "WL",
    ],
    getPrice: function (color, size) {
      if (["100", "110", "120", "130", "140", "150"].includes(size)) {
        return 568;
      }
      if (["SS", "S", "M", "L", "LL", "WM", "WL"].includes(size)) {
        return 590;
      }
      if (["3L", "4L", "5L"].includes(size)) {
        return 647;
      }
      if (["6L", "7L"].includes(size)) {
        return 810;
      }
      return 0;
    },
    charts: {
      color: "./images/item/glimmer_4.4/color-chart_glimmer_4.4.pdf",
      size: "./images/item/glimmer_4.4/glimmer_Printstar-4.4.pdf",
    },
  },
};

export const PRINT_POSITIONS = {
  前面: 1650,
  背面: 1650,
  右胸: 990,
  左胸: 990,
  右肩: 990,
  左肩: 990,
};

export const BAG_PRICE = 45;
