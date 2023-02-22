/**
 * フォームの二重投稿を防止する。
 */
$(function () {
    $("form").on("submit", function () {
        $(this).find(":submit").prop("disabled", "true");
    });
});
