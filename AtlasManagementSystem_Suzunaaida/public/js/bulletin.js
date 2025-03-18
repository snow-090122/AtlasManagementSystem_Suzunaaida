$(function () {
  $('.main_categories').click(function () {
    var category_id = $(this).attr('category_id');
    $('.category_num' + category_id).slideToggle();
  });

  $(document).on('click', '.like_btn', function (e) {
    e.preventDefault();
    let $this = $(this);
    let post_id = $this.data('post_id');

    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      method: "POST",
      url: "/like/post/" + post_id,
      data: { post_id: post_id },
    }).done(function (res) {
      console.log("レスポンス:", res);
      if (res.success) {
        $this.removeClass('like_btn').addClass('un_like_btn');
        $(`.like_counts${post_id}`).text(res.like_count); // ✅ 修正
      }
    }).fail(function () {
      console.log("いいね失敗");
    });
  });

  $(document).on('click', '.un_like_btn', function (e) {
    e.preventDefault();
    let $this = $(this);
    let post_id = $this.data('post_id');

    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      method: "DELETE",
      url: "/unlike/post/" + post_id,
      data: { post_id: post_id },
    }).done(function (res) {
      console.log("レスポンス:", res);
      if (res.success) {
        $this.removeClass('un_like_btn').addClass('like_btn');
        $(`.like_counts${post_id}`).text(res.like_count); // ✅ 修正
      }
    }).fail(function () {
      console.log("いいね解除失敗");
    });
  });


  $(document).ready(function () {
    $(".edit-modal-btn input[type='submit']").on('click', function (e) {
      e.preventDefault(); // 通常の送信を防ぐ

      let form = $(this).closest("form");
      let formData = new FormData(form[0]);
      let actionUrl = form.attr("action");

      $.ajax({
        url: actionUrl,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          if (response.success) {
            $(".detsail_post_title").text(response.updated_title);
            $(".detsail_post").text(response.updated_body);
            $(".js-modal").fadeOut();
            $('.error-message').remove(); // エラー表示をクリア
          }
        },
        error: function (xhr) {
          let errors = xhr.responseJSON.errors;
          $(".error-message").remove(); // 既存のエラーメッセージ削除
          if (errors) {
            $.each(errors, function (key, messages) {
              let errorHtml = '<ul class="text-danger error-message">';
              $.each(messages, function (index, message) {
                errorHtml += `<li>${message}</li>`;
              });
              errorHtml += '</ul>';
              $(`[name="${key}"]`).after(errorHtml);
            });
          }
        }
      });
    });
  });
});
