/* ============================================================
   SERVICE-WORK.JS — 就労支援サービス ページ専用スクリプト
   ============================================================ */

(function () {

/* ----------------------------------------------------------
   Swiper — 作業サンプルスライダー
   ---------------------------------------------------------- */
var swiper = new Swiper('.p-work__swiper', {
  loop: true,
  speed: 600,
  grabCursor: true,
  slidesPerView: 1.4,
  spaceBetween: 20,
  on: {
    touchStart: function () { lenis.stop(); },
    touchEnd:   function () { lenis.start(); },
  },
  breakpoints: {
    769: {
      slidesPerView: 2.2,
      spaceBetween: 32,
    },
  },
});

/* ----------------------------------------------------------
   GSAP ScrollTrigger — フェードアップアニメーション
   ---------------------------------------------------------- */
gsap.registerPlugin(ScrollTrigger);

lenis.on("scroll", ScrollTrigger.update);

var fadeTargets = [
  ".p-work__text",
  ".p-work__samples",
  ".p-timeline__text",
  ".p-timeline__schedule",
  ".p-training__title",
  ".p-training__desc",
  ".p-training__card",
  ".p-stats__card",
  ".p-jobs__inner",
  ".p-faq__item",
  ".p-flow__step",
  ".p-cta__body",
  ".p-cta__visual",
];

fadeTargets.forEach(function (selector) {
  document.querySelectorAll(selector).forEach(function (el, i) {
    gsap.fromTo(
      el,
      { opacity: 0, y: 32 },
      {
        opacity: 1,
        y: 0,
        duration: 0.7,
        ease: "power2.out",
        delay: i * 0.06,
        scrollTrigger: {
          trigger: el,
          start: "top 84%",
          once: true,
        },
      }
    );
  });
});

}());
