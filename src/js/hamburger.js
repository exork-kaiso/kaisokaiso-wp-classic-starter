"use strict";

{
  // ハンバーガーメニューの処理に必要な要素の取得
  // body要素、ダイアログ要素、ダイアログの開閉ボタン、ダイアログの閉じるボタン
  const body = document.body;
  const dialogElement = document.querySelector(".js-dialog");
  const dialogOpenButton = document.querySelector(".js-dialog-open");
  const dialogCloseButton = document.querySelector(".js-dialog-close");

  // ハンバーガーメニュー（ダイアログの開閉）が存在する場合の処理
  if (dialogElement && dialogOpenButton && dialogCloseButton) {

    // ハンバーガーメニュー（ダイアログの開閉）ボタンをクリックしたときの処理
    dialogOpenButton.addEventListener("click", () => {
      if (typeof dialogElement.showModal === "function") {
        body.classList.add("js-scroll-lock");
        dialogElement.showModal();
      } else {
        alert("dialog要素をサポートしていないブラウザです。");
      }
      dialogOpenButton.classList.toggle("open");
    });

    // ハンバーガーメニュー（ダイアログの閉じる）ボタンをクリックしたときの処理
    dialogCloseButton.addEventListener("click", () => {
      body.classList.remove("js-scroll-lock");
      dialogOpenButton.classList.toggle("open");
      dialogElement.close();
    });

    // ハンバーガーメニュー（ダイアログのリンク）をクリックしたときの処理
    dialogElement.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => {
        body.classList.remove("js-scroll-lock");
        dialogOpenButton.classList.toggle("open");
        dialogElement.close();
      });
    });
  }

  // スムーススクロール（ページ内アンカーリンク、ダイアログの有無に依存しない）
  document.addEventListener("DOMContentLoaded", function () {

    // ヘッダーの高さ
    const headerHeight = 132;

    // ページ内アンカーリンクをクリックしたときの処理
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
      anchor.addEventListener("click", function (event) {
        const href = this.getAttribute("href");
        const target = document.querySelector(
          href === "#" || href === "" ? "html" : href
        );

        if (!target) return;

        event.preventDefault();

        const position = target.offsetTop - headerHeight;

        window.scrollTo({
          top: position,
          behavior: "smooth",
        });
      });
    });
  });
}
