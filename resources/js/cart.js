const addCartForm = $(".addCartForm");

$(function () {
    addCartForm.on("submit", function (e) {
        e.preventDefault();

        const url = $(this).attr("action");
        const id = $(this).find("input[name='id']").val();
        const quantity = $(this).find("select").val();
        const select = $(this).find("select");
        const paragraph = '<p class="leading-relaxed">SOLD OUT</p>';

        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            data: {
                id: id,
                quantity: quantity,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        })
            .done(function (json) {
                const stock = Number(json["stock"]);

                if (stock === 0) {
                    removeAddCartForm(select, paragraph);
                } else {
                    rebuildOptions(select, stock);
                }

                $(".cartCount").text(json["count"]);
                alert(json["message"]);
            })
            .fail(function (json) {
                showErrorMessage();
            });
    });
});

const removeAddCartForm = function (select, paragraph) {
    // $(this).after(paragraph); では何故か動作しない。。
    select.parent(addCartForm).after(paragraph);
    select.parent(addCartForm).remove();
};

const rebuildOptions = function (select, stock) {
    select.children().remove();
    for (let i = 1; i <= stock; i++) {
        select.append($("<option>").text(i)).val(i);
    }
    select.val(1);
};

const showErrorMessage = function () {
    $("html, body").animate({ scrollTop: 0 }, "fast");
    $(".container").prepend(
        "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-8'>通信に失敗しました。ブラウザをリロードして再度実行してください。</div>"
    );
};
