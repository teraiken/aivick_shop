window.onload = switchAddress;

const addressRadios = document.getElementsByName("selectedAddress");
for (let i = 0; i < addressRadios.length; i++) {
    addressRadios[i].addEventListener("click", switchAddress);
}

const addressId = document.getElementById("addressId");
const addressSelectBox = document.getElementById("addressSelectBox");

const addressForm = document.getElementById("addressForm");
const addressInputItems = addressForm.getElementsByTagName("input");
const pref_id = document.getElementById("pref_id");

/**
 * ラジオボタンで配送先入力画面の表示内容を切り替える。
 */
function switchAddress() {
    if (addressRadios[0].checked) {
        addressId.required = true;
        addressSelectBox.style.display = "";

        for (let i = 0; i < addressInputItems.length; i++) {
            addressInputItems[i].required = false;
        }
        pref_id.required = false;
        addressForm.style.display = "none";
    }

    if (addressRadios[1].checked) {
        addressId.required = false;
        addressSelectBox.style.display = "none";

        for (let i = 0; i < addressInputItems.length; i++) {
            addressInputItems[i].required = true;
        }
        pref_id.required = true;
        addressForm.style.display = "";
    }
}
