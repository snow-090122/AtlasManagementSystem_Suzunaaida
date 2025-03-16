$(function () {
  $(document).ready(function () {
    let mainCategorySelect = $("#main_category");
    let subCategorySelect = $("#sub_category");

    let subCategories = @json($main_categories -> mapWithKeys(function ($mainCategory) {
      return [$mainCategory -> id=> $mainCategory -> subCategories -> pluck('id', 'sub_category')];
  }));

mainCategorySelect.on("change", function () {
  let mainCategoryId = $(this).val();
  subCategorySelect.empty().append('<option value="">選択してください</option>');

  if (mainCategoryId && subCategories[mainCategoryId]) {
    $.each(subCategories[mainCategoryId], function (id, name) {
      subCategorySelect.append(`<option value="${id}">${name}</option>`);
    });
  }
});
});


$(document).on("click", ".like_btn, .un_like_btn", function (e) {
  e.preventDefault();
  let button = $(this);
  let post_id = button.attr("post_id");
  let countElement = $(".like_counts" + post_id);
  let count = parseInt(countElement.text(), 10);

  let isLike = button.hasClass("like_btn");
  let url = isLike ? "/like/post/" + post_id : "/unlike/post/" + post_id;
  let method = "post";

  $.ajax({
    headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
    method: method,
    url: url,
    data: { post_id: post_id },
  })
    .done(function (res) {
      if (res.success) {
        countElement.text(isLike ? count + 1 : Math.max(0, count - 1));
        button.toggleClass("like_btn un_like_btn");
      }
    })
    .fail(function () {
      console.log("エラーが発生しました");
    });
});


$(document).on("click", ".edit-modal-open", function () {
  let post_title = $(this).attr("post_title");
  let post_body = $(this).attr("post_body");
  let post_id = $(this).attr("post_id");

  $(".modal-inner-title input").val(post_title);
  $(".modal-inner-body textarea").val(post_body);
  $(".edit-modal-hidden").val(post_id);

  $(".js-modal").fadeIn();
  return false;
});

$(document).on("click", ".js-modal-close", function () {
  $(".js-modal").fadeOut();
  $(".error-message").remove();
  return false;
});

$(document).on("submit", ".edit-modal-form", function (e) {
  e.preventDefault();

  let form = $(this);
  let formData = new FormData(this);
  let actionUrl = form.attr("action");

  $.ajax({
    url: actionUrl,
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
  })
    .done(function (response) {
      if (response.success) {
        $(".detsail_post_title").text(response.updated_title);
        $(".detsail_post").text(response.updated_body);
        $(".js-modal").fadeOut();
        $(".error-message").remove();
      }
    })
    .fail(function (xhr) {
      let errors = xhr.responseJSON.errors;
      $(".error-message").remove();
      if (errors) {
        $.each(errors, function (key, value) {
          $(`[name="${key}"]`).after(`<div class="text-danger error-message">${value}</div>`);
        });
      }
    });
});


$(document).on("click", ".delete-modal-open", function () {
  let post_id = $(this).attr("post_id");
  $(".delete-modal-hidden").val(post_id);
  $(".delete-form").attr("action", "/bulletin_board/delete/" + post_id);
  $(".js-delete-modal").fadeIn();
  return false;
});

$(document).on("click", ".js-modal-close", function () {
  $(".js-delete-modal").fadeOut();
  return false;
});
});
