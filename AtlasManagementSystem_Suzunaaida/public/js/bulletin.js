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
  $(document).ready(function () {
    // 編集ボタンをクリックしてモーダルを開く
    $(document).on('click', '.edit-modal-open', function (event) {
      event.preventDefault(); // デフォルトの動作を防ぐ
      event.stopPropagation(); // イベントの伝播を防ぐ

      $('.js-modal').fadeIn();

      let post_id = $(this).attr('post_id');
      let post_title = $(this).attr('post_title');
      let post_body = $(this).attr('post_body');

      $('#edit-post-id').val(post_id);
      $('#edit-post-title').val(post_title);
      $('#edit-post-body').val(post_body);
    });

    // モーダルを閉じる処理
    $(document).on('click', '.js-modal-close', function () {
      $('.js-modal').fadeOut();
    });

    // 編集フォームの送信（Ajax）
    $(document).on('submit', '#edit-post-form', function (event) {
      event.preventDefault(); // デフォルトのフォーム送信を防ぐ
      event.stopPropagation();

      let post_id = $('#edit-post-id').val();
      let url = `/bulletin_board/update/${post_id}`;

      let formData = new FormData();
      formData.append('_token', $('meta[name="csrf-token"]').attr('content')); // CSRFトークン
      formData.append('_method', 'PUT'); // LaravelのPUTメソッド識別
      formData.append('post_title', $('#edit-post-title').val());
      formData.append('post_body', $('#edit-post-body').val());

      $.ajax({
        url: url,
        type: 'POST',  // LaravelはフォームでPUTメソッドを受け付けないため、POSTで送る
        data: formData,
        processData: false,
        contentType: false,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRFトークンをヘッダーに追加
        },
        success: function (response) {
          if (response.success) {
            // 投稿の内容を更新
            $(`#post-title-${post_id}`).text(response.updated_title);
            $(`#post-body-${post_id}`).text(response.updated_body);

            // モーダルを閉じる
            $('.js-modal').fadeOut();
          }
        },
        error: function (xhr) {
          console.log(xhr.responseJSON); // デバッグ用

          let errors = xhr.responseJSON.errors;

          $('#error-post-title').html('');
          $('#error-post-body').html('');

          if (errors && errors.post_title) {
            errors.post_title.forEach(function (error) {
              $('#error-post-title').append(`<li>${error}</li>`);
            });
          }

          if (errors && errors.post_body) {
            errors.post_body.forEach(function (error) {
              $('#error-post-body').append(`<li>${error}</li>`);
            });
          }
        }
      });
    });
  });

});
