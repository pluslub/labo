/* ============================================================
   MAIN.JS — Plusらぼ サイトリニューアル
   ============================================================ */

/* ----------------------------------------------------------
   Lenis — 慣性スクロール
   ---------------------------------------------------------- */
const lenis = new Lenis({
    duration: 1.15,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
});

function raf(time) {
    lenis.raf(time);
    requestAnimationFrame(raf);
}
requestAnimationFrame(raf);

/* アンカーリンクは Lenis.scrollTo で制御 */
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", (e) => {
        const href = anchor.getAttribute("href");
        if (!href || href === "#") return;
        const target = document.querySelector(href);
        if (!target) return;
        e.preventDefault();
        lenis.scrollTo(target, { offset: -80 });
    });
});

/* ----------------------------------------------------------
    ハンバーガーメニュー
   ---------------------------------------------------------- */

const menuToggle = document.getElementById('menuToggle');
const toggleIcon = document.getElementById('toggleIcon');
const toggleText = document.getElementById('toggleText');
const menuOverlay = document.getElementById('menuOverlay');
const header = document.getElementById('header');

menuToggle.addEventListener('click', () => {
    const isOpen = toggleIcon.classList.contains('is-open');
    
    if (!isOpen) {
        // Open Menu
        toggleIcon.classList.remove('is-closing');
        toggleIcon.classList.add('is-open');
        menuOverlay.classList.add('is-active');
        header.classList.add('is-open');
        toggleText.textContent = 'close';
        document.body.style.overflow = 'hidden';
    } else {
        // Close Menu
        toggleIcon.classList.remove('is-open');
        toggleIcon.classList.add('is-closing');
        menuOverlay.classList.remove('is-active');
        header.classList.remove('is-open');
        toggleText.textContent = 'menu';
        document.body.style.overflow = '';
        
        // Remove closing class after animation ends
        setTimeout(() => {
            toggleIcon.classList.remove('is-closing');
        }, 600);
    }
});


/* ----------------------------------------------------------
   Swiper — サービスセクション
   ---------------------------------------------------------- */
document.addEventListener("DOMContentLoaded", () => {
let swiperWork, swiperBiz;

// Swiperの初期化（Workセクション）
if (document.querySelector("#swiper-work")) {
    swiperWork = new Swiper("#swiper-work", {
    loop: true,
    speed: 600,
    grabCursor: true,
    slidesPerView: 1.08,
    spaceBetween: 16,
    breakpoints: {
        769: {
        slidesPerView: 1.82,
        spaceBetween: 24,
        },
    },
    });
}

// Swiperの初期化（Bizセクション）
if (document.querySelector("#swiper-biz")) {
    // PC(769px以上)のみRTL（blob右・swiper左レイアウトに対応）
    if (window.innerWidth >= 769) {
        document.querySelector('#swiper-biz').setAttribute('dir', 'rtl');
    }
    swiperBiz = new Swiper("#swiper-biz", {
    loop: true,
    speed: 600,
    grabCursor: true,
    slidesPerView: 1.08,
    spaceBetween: 16,
    breakpoints: {
        769: {
        slidesPerView: 1.82,
        spaceBetween: 24,
        },
    },
    });
}

// ナビゲーションボタンのイベント設定（反応しない問題を解決）
const navButtons = document.querySelectorAll(".js-swiper-prev, .js-swiper-next");

navButtons.forEach((btn) => {
    btn.addEventListener("click", (e) => {
    e.preventDefault();
    const target = btn.dataset.for; // data-for="work" または "biz"
    const swiper = target === "work" ? swiperWork : swiperBiz;
    
    if (swiper) {
        if (btn.classList.contains("js-swiper-prev")) {
        swiper.slidePrev();
        } else {
        swiper.slideNext();
        }
    }
    });
});

// レイアウト確定後に再計算してズレを防止
requestAnimationFrame(() => {
        swiperWork?.update();
        swiperBiz?.update();
    });
});

/* ----------------------------------------------------------
   Swiper — コラムセクション
   ---------------------------------------------------------- */
let swiperColumn;

if (document.querySelector("#swiper-column")) {
    swiperColumn = new Swiper("#swiper-column", {
        loop: true,
        speed: 600,
        grabCursor: true,
        slidesPerView: 1.2,
        spaceBetween: 16,
        breakpoints: {
        769: {
            slidesPerView: 3.2,
            spaceBetween: 24,
        },
        },
    });

    requestAnimationFrame(() => {
        swiperColumn.update();
    });
}

document.querySelector(".js-column-prev")?.addEventListener("click", () => swiperColumn?.slidePrev());
document.querySelector(".js-column-next")?.addEventListener("click", () => swiperColumn?.slideNext());

/* ----------------------------------------------------------
   GSAP ScrollTrigger — スクロール連動アニメーション
   ---------------------------------------------------------- */
gsap.registerPlugin(ScrollTrigger);

/* Lenis と ScrollTrigger を同期 */
lenis.on("scroll", ScrollTrigger.update);
gsap.ticker.add((time) => {
  lenis.raf(time * 1000);
});
gsap.ticker.lagSmoothing(0);

/* ヒーロー — ページロード時のフェードイン */
gsap.fromTo(
    ".p-hero__catch",
    { opacity: 0, y: 28 },
    { opacity: 1, y: 0, duration: 0.85, ease: "power2.out", delay: 0.15 }
);

gsap.fromTo(
    ".p-hero__sub",
    { opacity: 0, y: 20 },
    { opacity: 1, y: 0, duration: 0.7, ease: "power2.out", delay: 0.4 }
);

gsap.fromTo(
    ".p-hero__circle",
    { opacity: 0, scale: 0.88 },
    { opacity: 1, scale: 1, duration: 1.0, ease: "power2.out", delay: 0.25 }
);

/* スクロール連動フェードアップ（共通） */
const fadeTargets = [
    ".p-info__inner",
    ".p-about__content",
    ".p-about__blob",
    ".p-service__text",
    ".p-service__slider-wrap",
    ".p-column__head",
    ".p-column__card",
    ".p-cta__body",
    ".p-cta__visual",
];

fadeTargets.forEach((selector) => {
    document.querySelectorAll(selector).forEach((el) => {
        gsap.fromTo(
        el,
        { opacity: 0, y: 36 },
        {
            opacity: 1,
            y: 0,
            duration: 0.8,
            ease: "power2.out",
            scrollTrigger: {
            trigger: el,
            start: "top 82%",
            once: true,
            },
        }
        );
    });
});

/* コラムSwiper — セクション全体でフェードイン */
gsap.fromTo(
    ".p-column__swiper",
    { opacity: 0, y: 36 },
    {
        opacity: 1,
        y: 0,
        duration: 0.8,
        ease: "power2.out",
        scrollTrigger: {
        trigger: ".p-column__swiper",
        start: "top 82%",
        once: true,
        },
    }
);



/* ----------------------------------------------------------
   アコーディオン
   ---------------------------------------------------------- */

document.querySelectorAll('.p-faq__item').forEach(item => {
    const summary = item.querySelector('.p-faq__q');
    const wrapper = item.querySelector('.p-faq__a-wrapper');

    summary.addEventListener('click', (e) => {
        e.preventDefault();

        if (item.hasAttribute('open')) {
            wrapper.style.gridTemplateRows = '0fr';
            setTimeout(() => {
                item.removeAttribute('open');
            }, 400); 
        } else {
            item.setAttribute('open', 'true');
            requestAnimationFrame(() => {
                wrapper.style.gridTemplateRows = '1fr';
            });
        }
    });
});
