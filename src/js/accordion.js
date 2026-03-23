"use strict";

/**
 * アコーディオン
 * ヘッダー・フッターのクリックでコンテンツの表示/非表示を切り替える
 * - 募集要項（.js-career-accordion）
 * - 業務の流れ（.js-workflow-accordion）
 */

const LABEL_OPEN = "詳細を閉じる";
const LABEL_CLOSED = "詳細を表示する";

/**
 * アコーディオンを初期化する
 * @param {Element} wrapper - .js-career-accordion または .js-workflow-accordion
 */
function initAccordion(wrapper) {
  const content = wrapper.querySelector(".js-accordion-content");
  const triggers = wrapper.querySelectorAll(".js-accordion-trigger");
  const labelEl = wrapper.querySelector(".js-accordion-label");
  const iconHeader = wrapper.querySelector(".js-accordion-icon-header");
  const iconFooter = wrapper.querySelector(".js-accordion-icon-footer");

  if (!content || !triggers.length) return;

  /** アコーディオンが閉じているかどうか */
  function isCollapsed() {
    return content.classList.contains("is-collapsed");
  }

  /** アコーディオンの開閉を切り替える */
  function toggle() {
    const collapsed = isCollapsed();
    content.classList.toggle("is-collapsed", !collapsed);

    triggers.forEach((trigger) => {
      trigger.setAttribute("aria-expanded", collapsed ? "true" : "false");
    });

    if (labelEl) {
      labelEl.textContent = collapsed ? LABEL_OPEN : LABEL_CLOSED;
    }

    if (iconHeader) {
      iconHeader.classList.toggle("rotate-90", collapsed);
      iconHeader.classList.toggle("-rotate-90", !collapsed);
    }

    if (iconFooter) {
      iconFooter.classList.toggle("-rotate-90", collapsed);
      iconFooter.classList.toggle("rotate-90", !collapsed);
    }
  }

  triggers.forEach((trigger) => {
    trigger.addEventListener("click", toggle);
  });
}

// 募集要項アコーディオン
const careerAccordion = document.querySelector(".js-career-accordion");
if (careerAccordion) {
  initAccordion(careerAccordion);
}

// 業務の流れアコーディオン
const workflowAccordion = document.querySelector(".js-workflow-accordion");
if (workflowAccordion) {
  initAccordion(workflowAccordion);
}
