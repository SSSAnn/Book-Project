const menuBtn = document.querySelector("#menu-btn");
const navbar = document.querySelector(".header .navbar");
const accountBox = document.querySelector(".header .account-box");
const userBtn = document.querySelector("#user-btn");
const closeUpdate = document.querySelector('#close_update');

menuBtn.onclick = ()=>{
    navbar.classList.toggle('active');
    accountBox.classList.remove('active');
}

userBtn.onclick = ()=>{
    accountBox.classList.toggle('active');
    navbar.classList.remove('active');
}

window.onscroll = ()=>{
    navbar.classList.remove('active');
    accountBox.classList.remove('active');
}

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

if(closeUpdate){
    closeUpdate.onclick = ()=>{
        document.querySelector(".edit-product-form").style.display ="none";
        window.location.href = 'admin_products.php';
    }
}



