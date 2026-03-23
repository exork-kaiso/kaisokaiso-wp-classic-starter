/**
 * BrowserSync 設定
 * Local by Flywheel の WordPress サイトをプロキシし、ファイル変更時に自動リロード
 */
module.exports = {
  // Local のサイト URL（例: http://your-site.local）
  proxy: 'http://XXXXXXXX.local',
  files: [
    'dist/**/*',
    '**/*.php',
  ],
  open: false,
  notify: false,
  ui: false,
  // Local が HTTPS の場合、以下を有効にしてください
  // https: {
  //   key: '/path/to/key.pem',
  //   cert: '/path/to/cert.pem',
  // },
};
