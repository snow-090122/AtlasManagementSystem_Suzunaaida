$(function () {
  $('.main_categories').click(function () {
    var category_id = $(this).attr('category_id');
    $('.category_num' + category_id).slideToggle();
  });
  //いいねのコード
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
  //いいね解除のコード
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

  //削除モーダル
  $(function () {
    $(".js-delete-btn").on("click", function () {
      let postId = $(this).data("post-id");
      let deleteUrl = "/bulletin_board/delete/" + postId;

      $(".delete-form").attr("action", deleteUrl);
      $(".delete-modal-hidden").val(postId);

      console.log("削除リクエストURL:", deleteUrl);
      $(".js-delete-modal").fadeIn();
    });

    $(".delete-form").on("submit", function (e) {
      e.preventDefault();

      let form = $(this);
      let url = form.attr("action");
      let postId = $(".delete-modal-hidden").val();
      $.ajax({
        type: "DELETE",
        url: url,
        data: form.serialize(),
        success: function (response) {
          console.log("削除成功:", response);

          $("#post-" + postId).fadeOut(300, function () {
            $(this).remove();
          });

          setTimeout(function () {
            window.location.replace(response.redirect + "?t=" + new Date().getTime());
          }, 500);
        },
        error: function (xhr) {
          console.error("削除エラー:", xhr.responseText);
        }
      });
    });

    $(".js-modal-close").on("click", function () {
      $(".js-delete-modal").fadeOut();
    });
  });

  //編集モーダル
  $('.edit-modal-open').on('click', function () {
    $('.js-modal').fadeIn();
    var post_title = $(this).attr('post_title');
    var post_body = $(this).attr('post_body');
    var post_id = $(this).attr('post_id');
    $('.modal-inner-title input').val(post_title);
    $('.modal-inner-body textarea').text(post_body);
    $('.edit-modal-hidden').val(post_id);
    return false;
  });
  $('.js-modal-close').on('click', function () {
    $('.js-modal').fadeOut();
    return false;
  });

});
