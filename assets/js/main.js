document.addEventListener("DOMContentLoaded", function(){
    const slider=document.getElementById("tagsSlider");
    const leftBtn=document.querySelector(".scroll-btn.left");
    const rightBtn=document.querySelector(".scroll-btn.right");

    if(slider && leftBtn && rightBtn){
        leftBtn.addEventListener("click", function (){
            slider.scrollBy({
                left: -250,
                behavior: "smooth"
            });
        });

        rightBtn.addEventListener("click", function(){
            slider.scrollBy({
                left: 250,
                behavior: "smooth"
            });
        });
    }
});