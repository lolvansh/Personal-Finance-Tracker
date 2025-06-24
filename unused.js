const expenseform = document.querySelector(".form");
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

    if(category && amount){
        const expense = {
            category: category,
            amount: amount,
            date: new Date().toISOString().replace("T", " ").slice(0, 19)
        };
        let expenses = JSON.parse(localStorage.getItem("expenses")) || [];
        expenses.push(expense);
        localStorage.setItem("expenses", JSON.stringify(expenses));

        document.getElementById("description").value = "";
        document.getElementById("Amount").value = "";

        alert("Expense added successfully!");
        window.location.href = "index.html"; 
    }

})
