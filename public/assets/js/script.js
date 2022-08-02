const burgerMenu = document.querySelector(".nav-toggler")
const navigate = document.querySelector("nav")

burgerMenu.addEventListener("click", toggleNav)

function toggleNav(){
    burgerMenu.classList.toggle("active")
    navigate.classList.toggle("active")
}