'use srtict'

let swiper = new Swiper('.swiper-container', {
  slidesPerView: 2,
  spaceBetween: 24,
  mousewheel: true,
  scrollbar: {
    el: '.swiper-scrollbar',
    draggable: true,
    dragSize: "auto"
  },
  breakpoints: {
  1: {
    slidesPerView: 1.18,
    spaceBetween: 16,
  },
  767: {
    slidesPerView: 3,
    spaceBetween: 24
  }
  },
  on: {
  init: function(){

  }
  }
});

let tabAction = function(){
 const tab = document.querySelectorAll(".tab-content__tab")
 const contents = document.querySelectorAll(".content-body")
 for (let i = 0; i < tab.length && contents.length; i++) {
   contents[i].setAttribute("data-box",`tab_0${i+1}`)
   tab[i].addEventListener("click",function(e){
    tabOpen(this, tab, contents)
   })
 }

 function tabOpen (e,tab,contents) {
   const tabId = e.dataset.tab
   const targetContent = contents[0].parentElement.querySelector(`.${tabId}`)

   for (var i = 0; i < contents.length; i++) {
    tab[i].classList.remove('active')
    contents[i].classList.remove('on')
   }

   e.classList.add('active')
   targetContent.classList.add('on')
 }
}

const gnavActions = function(){
  const bodyElement = document.querySelector('body')
  const gnavBtnElement = document.querySelector('.header__sp-btn')
  const targetElement = document.querySelector('.header__nav')
  const removeElement = document.querySelector('.header__remove-overlay')

  const gnavAction = function (){
   if( !targetElement.classList.contains('active')){
    targetElement.classList.add('active')
    bodyElement.classList.add('on')
   }else{
    targetElement.classList.remove('active')
    bodyElement.classList.remove('on')
   }
  }

  gnavBtnElement.addEventListener('click',gnavAction)
  removeElement.addEventListener('click',gnavAction)
}

const scrollAddClass = function(__setClass){

 const windowHeight = window.innerHeight;
 let addClassElement = document.querySelectorAll(`.${__setClass}`)

 window.addEventListener('scroll', function(){

  for (let index = 0; index < addClassElement.length; index++) {
   // let pageWindowHeight = document.body.innerHeight
   let element = addClassElement[index]
   let pageWindowHeight = element.getBoundingClientRect().top;
   let elementHeight = element.pageYOffset;
   if( pageWindowHeight < 500 ){
     element.classList.add('_fade_animation_')
   }
  }

 })

}

window.addEventListener("load", function(){
  gnavActions()
  tabAction()
  scrollAddClass('_set_animation_')
	 scrollAddClass('_set_font_animation_')
})
