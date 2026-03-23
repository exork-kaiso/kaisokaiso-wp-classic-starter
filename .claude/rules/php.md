---
paths:
  - "**/*.php"
---

# PHP (WordPress) コーディングルール

- WordPressのコーディング規約に従う
- 実装・改善提案は [WordPress Developer Reference](https://developer.wordpress.org/reference/) の推奨に従う。関数・フックの仕様確認は公式リファレンスを情報源とする
- `dist/` のアセットは `functions.php` で `wp_enqueue_*` を使って読み込む
- インデントは半角スペース2つを使用する（タブ文字は使用しない）
