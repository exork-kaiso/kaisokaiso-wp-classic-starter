# CLAUDE.md — 汎用的な雛形として使うWordPressのクラシックテーマのスターターテーマ

## プロジェクト概要

WordPressカスタムテーマ。Vite + Tailwind CSS v4 でフロントエンドをビルドする構成。

## 技術スタック

- WordPress (カスタムテーマ)
- Vite (ビルドツール)
- Tailwind CSS v4 (`@tailwindcss/vite` プラグイン経由)
- Browser-sync (開発時ライブリロード)

## ディレクトリ構成

```
src/
├── css/
│   ├── main.css       # CSSエントリーポイント
│   ├── theme.css      # Tailwindテーマ変数 (@theme)
│   └── entry-form.css # CF7 用スタイル（条件付き読み込み）
└── js/
    ├── main.js     # JSエントリーポイント
    ├── hamburger.js
    └── accordion.js

dist/               # ビルド出力（Git管理対象）
├── assets/
│   ├── main.css
│   ├── entry-form.css
│   └── script.js
```

## 開発コマンド

```bash
npm run dev          # ビルド監視 + Browser-sync を同時起動
npm run build        # 本番ビルド（dist/ へ出力）
npm run build:watch  # ビルド監視のみ
npm run sync         # Browser-sync のみ
```

## コーディング規約

ファイル種別ごとの詳細なルールは `.claude/rules/` を参照。

## コミュニケーション

- **Claudeとのやり取りは常に日本語で行う**

## 実装時のルール

- **コミットはユーザーが明示的に依頼した場合のみ行う**
- 不要なファイルを新規作成しない。既存ファイルへの編集を優先する
- `dist/` はビルド成果物だが Git 管理対象のため、変更後は `npm run build` を実行する
- `node_modules/` は参照のみ。依存関係の変更はユーザーに確認する
- 過剰なリファクタリングや未依頼の機能追加は行わない
