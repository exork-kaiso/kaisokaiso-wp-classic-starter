# 開発環境セットアップ手順書

本ドキュメントでは、本テーマ（kaisokaiso-wp-classic-starter）の開発環境の構築手順を説明します。

## 概要

- **WordPress テーマ**: kaisokaiso-wp-classic-starter
- **ビルドツール**: Vite
- **CSS フレームワーク**: Tailwind CSS v4
- **JavaScript**: ES モジュール形式（Vite でバンドル）
- **開発サーバー**: BrowserSync（Local by Flywheel の WordPress サイトをプロキシ）

---

## 前提条件

- Node.js（v18 以上推奨）
- npm
- Local by Flywheel で構築した WordPress サイト（例: `your-site.local`）

---

## セットアップ手順

### 1. テーマディレクトリへ移動

```bash
cd wp-content/themes/kaisokaiso-wp-classic-starter
```

### 2. 依存パッケージのインストール

#### package.json を共有する場合

```bash
npm install
```

#### package.json を共有しない場合

プロジェクトを初期化し、必要なパッケージを個別にインストールします。

```bash
# プロジェクトの初期化
npm init -y

# 本番依存パッケージ（Tailwind CSS）
npm install tailwindcss@^4.2.0 @tailwindcss/vite@^4.2.0

# 開発依存パッケージ
npm install -D vite browser-sync@^3.0.4 concurrently@^9.2.1
```

インストール後、`package.json` の `scripts` に以下を追加してください。

```json
{
  "scripts": {
    "dev": "concurrently \"npm run build:watch\" \"npm run sync\"",
    "build": "vite build",
    "build:watch": "vite build --watch",
    "sync": "browser-sync start --config bs-config.js",
    "preview": "vite preview"
  }
}
```

インストールされる主なパッケージ：

| パッケージ | 用途 |
|-----------|------|
| tailwindcss | CSS フレームワーク |
| @tailwindcss/vite | Tailwind v4 用 Vite プラグイン |
| vite | ビルドツール（CSS・JS） |
| browser-sync | ファイル変更時の自動リロード |
| concurrently | 複数コマンドの同時実行 |

### 3. ビルドの実行

```bash
npm run build
```

**出力ファイル**：

| ファイル | 説明 |
|---------|------|
| `dist/assets/main.css` | Tailwind でコンパイルされた CSS |
| `dist/assets/script.js` | バンドルされた JavaScript（ES モジュール形式） |

---

## 開発時の使い方

### 開発モードの起動

```bash
npm run dev
```

このコマンドで以下が同時に起動します：

1. **Vite ビルド（ウォッチモード）**: `src/css/main.css` と `src/js/main.js` の変更を監視し、自動でコンパイル
2. **BrowserSync**: `dist/` と PHP ファイルの変更を検知し、ブラウザを自動リロード

### ブラウザでの確認

- BrowserSync のプロキシ URL がターミナルに表示されます（例: `http://localhost:3000`）
- または Local のサイト URL（例: `http://your-site.local`）で直接アクセス

### BrowserSync のプロキシ先の変更

Local のサイト URL が異なる場合は、`bs-config.js` の `proxy` を変更してください。

```javascript
// bs-config.js
module.exports = {
  proxy: 'http://あなたのサイト.local',
  // ...
};
```

---

## ファイル構成

```
kaisokaiso-wp-classic-starter/
├── src/
│   ├── css/
│   │   └── main.css          # Tailwind のエントリーポイント
│   └── js/
│       ├── main.js            # JS のエントリーポイント
│       └── hamburger.js        # ハンバーガーメニュー・スムーススクロール
├── dist/
│   └── assets/
│       ├── main.css           # ビルド出力（CSS）
│       └── script.js          # ビルド出力（JS）
├── CLAUDE.md                  # Cursor AI 向けプロジェクトガイド（規約・ルール）
├── front-page.php             # フロントページテンプレート
├── functions.php              # スタイル・スクリプト読み込み
├── package.json
├── vite.config.ts             # Vite 設定
├── bs-config.js               # BrowserSync 設定
└── docs/
    └── SETUP.md               # 本ドキュメント
```

---

## ドキュメント

| ファイル | 説明 |
|---------|------|
| `CLAUDE.md` | Cursor の AI アシスタント向けプロジェクトガイド。技術スタック・コーディング規約・実装ルールを定義 |
| `docs/SETUP.md` | 本ドキュメント（開発環境セットアップ手順） |

---

## 主要な設定ファイル

### vite.config.ts

- **CSS エントリーポイント**: `src/css/main.css` → `dist/assets/main.css`
- **JS エントリーポイント**: `src/js/main.js` → `dist/assets/script.js`
- Tailwind CSS v4 プラグインを使用

### src/css/main.css

```css
@import "tailwindcss";
```

Tailwind v4 はプロジェクト内の PHP ファイルを自動スキャンし、使用されているクラスのみを CSS に含めます。

### src/js/main.js

```javascript
console.log('loaded');
import './hamburger.js';
```

`hamburger.js` をインポートし、Vite が 1 つの `script.js` にバンドルします。

### functions.php

- **スタイル**: `wp_enqueue_style()` で `dist/assets/main.css` を読み込み
- **スクリプト**: `wp_enqueue_script_module()`（WordPress 6.5+）で `dist/assets/script.js` を `type="module"` で読み込み
- WordPress 6.4 以前は `wp_enqueue_script()` + `script_loader_tag` フィルターでフォールバック

テンプレートでは `wp_head()` と `wp_footer()` の呼び出しが必須です。

---

## トラブルシューティング

### スタイルが適用されない

1. **ビルドの実行**: `npm run build` を実行し、`dist/assets/main.css` が生成されているか確認
2. **パスの確認**: `functions.php` の `get_stylesheet_directory_uri()` が正しくテーマの URL を返しているか確認
3. **wp_head() の呼び出し**: テンプレートの `<head>` 内に `<?php wp_head(); ?>` が含まれているか確認

### スクリプトが動作しない・404 エラー

1. **ビルドの実行**: `npm run build` を実行し、`dist/assets/script.js` が生成されているか確認
2. **wp_footer() の呼び出し**: テンプレートの `</body>` 直前に `<?php wp_footer(); ?>` が含まれているか確認
3. **ビルドエラー**: `src/js/` 内の構文エラーがないか確認（`return` は関数内でのみ使用可能）

### Tailwind のクラスが生成されない

- 使用するクラスはテンプレート（PHP ファイル）に直接記述してください
- 動的にクラス名を組み立てる場合、完全なクラス名がソース内に存在する必要があります

### BrowserSync が接続できない

- Local のサイトが起動しているか確認
- `bs-config.js` の `proxy` が正しい URL か確認

### コンソールに「SES Removing unpermitted intrinsics」と表示される

- ブラウザ拡張機能（広告ブロッカー等）が原因の可能性があります
- テーマのコードが原因ではないため、無視して問題ありません

---

## npm スクリプト一覧

| コマンド | 説明 |
|---------|------|
| `npm run build` | 本番用ビルド（1回実行） |
| `npm run build:watch` | ウォッチモードでビルド |
| `npm run dev` | ビルド + BrowserSync を同時起動 |
| `npm run sync` | BrowserSync のみ起動 |
| `npm run preview` | Vite のプレビューサーバー起動 |

---

## 参考リンク

- [Tailwind CSS v4 ドキュメント](https://tailwindcss.com/docs)
- [Vite ドキュメント](https://vitejs.dev/)
- [BrowserSync ドキュメント](https://browsersync.io/docs)
- [WordPress Script Modules API（6.5+）](https://developer.wordpress.org/reference/functions/wp_enqueue_script_module/)
