$(document).on("click", ".report-link", function () {
    let id = $(this).attr("data-id");
    let idPost = $(this).attr("data-post");

    $(".modal-report").attr("data-id", + id);
    $(".modal-report").attr("data-post", + idPost);
});

$(document).on("click", ".modal-report", function () {
    let id = $(this).attr("data-id");
    let idPost = $(this).attr("data-post");

    let path = window.location.pathname;
    let slug = path.split("/").splice(3).join();

    $(".modal-report").attr("href","/post/" + idPost + "/" + slug + "/reported/" + id);
});