 const productSwiper = new Swiper(".cafeSwiper", {
      direction: "vertical",
      grabCursor: true,
      effect: "creative",
      loop:true,
      slidesPerView: 1,
      creativeEffect: {
        prev: {
          shadow: true,
          translate: [0, "-70%", -500],
        },
        next: {
          shadow: true,
          translate: [0, "70%", -500],
        },
      },
      mousewheel: true,
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
      },
      scrollbar: {
    el: '.swiper-scrollbar',
  },
  speed:600,
  navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });

    console.log("Swiper loaded")