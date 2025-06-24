const incomeform = document.querySelector(".form");
const submit = document.getElementById("submit");

submit.addEventListener("click", event => {
    const category = document.getElementById("description").value;
    const amount = document.getElementById("Amount").value;
    document.querySelector(".empty-1").style.display = "none";
    document.querySelector(".empty-2").style.display = "none";

    
    if(!category){
        const ReqCategory = document.querySelector(".empty-1");
        ReqCategory.style.display = "flex";  
    }

    if(!amount){
        const reqamount = document.querySelector(".empty-2");
        reqamount.style.display = "flex"; 
    }

    if(!category && !amount){
        event.preventDefault();
    }


})
