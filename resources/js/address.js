window.onload = display;

const radios = document.getElementsByName("address");
for (let i = 0; i < radios.length; i++) {
    radios[i].addEventListener("click", display);
}

const address_id = document.getElementById("address_id");
const selectBox = document.getElementById("selectBox");

const form = document.getElementById("form");
const inputItems = form.getElementsByTagName("input");
const pref_id = document.getElementById("pref_id");

function display() {
    if (radios[0].checked) {
        address_id.required = true;
        selectBox.style.display = "";

        for (let i = 0; i < inputItems.length; i++) {
            inputItems[i].required = false;
        }
        pref_id.required = false;
        form.style.display = "none";
    }

    if (radios[1].checked) {
        address_id.required = false;
        selectBox.style.display = "none";

        for (let i = 0; i < inputItems.length; i++) {
            inputItems[i].required = true;
        }
        pref_id.required = true;
        form.style.display = "";
    }
}
