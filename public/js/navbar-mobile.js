let hamburger = document.getElementById('icon');



function switchMenu() {
        let x = document.getElementById('links');
        let navbar = document.getElementById('navbar');
            if (x.style.display === "flex") {
                x.style.display = "none";
                navbar.style.flexDirection = "row";


            } else {
                x.style.display = "flex";
                x.style.flexDirection = "column"
                navbar.style.flexDirection = "column";
            }
}

