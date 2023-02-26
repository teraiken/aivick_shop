const image = document.getElementById("image");
const productImage = document.getElementById("productImage");

image.addEventListener("change", showProductImage);

/**
 * 選択された商品画像を表示する。
 * @param {*} e
 */
function showProductImage(e) {
    const reader = new FileReader();

    reader.onload = function (e) {
        productImage.setAttribute("src", e.target.result);
    };

    reader.readAsDataURL(e.target.files[0]);
}
