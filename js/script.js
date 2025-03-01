const userBox = document.querySelector(".header .header-2 .user-box");
document.querySelector("#user-btn").onclick = () => {
  userBox.classList.toggle("active");
  navbar.classList.remove("active");
};

const navbar = document.querySelector(".header .header-2 .navbar");
document.querySelector("#menu-btn").onclick = () => {
  navbar.classList.toggle("active");
  userBox.classList.remove("active");
};

window.onscroll = () => {
  navbar.classList.remove("active");
  userBox.classList.remove("active");

  if (window.scrollY > 60) {
    document.querySelector(".header .header-2").classList.add("active");
  } else {
    document.querySelector(".header .header-2").classList.remove("active");
  }
};
window.onload = () => {
  const activeLink = document.querySelectorAll(".navbar a");
  [...activeLink].forEach((link) => {
    const linkHref = link.getAttribute("href");
    const url = window.location.pathname;
    var filename = url.substring(url.lastIndexOf("/") + 1);
    if (linkHref == filename) {
      link.classList.add("active");
    } else {
      link.classList.remove("active");
    }
  });
};
