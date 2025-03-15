$(function () {
  $('.main_categories').click(function () {
    var category_id = $(this).attr('category_id');
    $('.category_num' + category_id).slideToggle();
  });

  $(document).on('click', '.like_btn', function (e) {
    e.preventDefault();
    $(this).addClass('un_like_btn');
    $(this).removeClass('like_btn');
    var post_id = $(this).attr('post_id');
    var count = $('.like_counts' + post_id).text();
    var countInt = Number(count);
    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      method: "post",
      url: "/like/post/" + post_id,
      data: {
        post_id: $(this).attr('post_id'),
      },
    }).done(function (res) {
      console.log(res);
      $('.like_counts' + post_id).text(countInt + 1);
    }).fail(function (res) {
      console.log('fail');
    });
  });

  $(document).on('click', '.un_like_btn', function (e) {
    e.preventDefault();
    $(this).removeClass('un_like_btn');
    $(this).addClass('like_btn');
    var post_id = $(this).attr('post_id');
    var count = $('.like_counts' + post_id).text();
    var countInt = Number(count);

    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      method: "post",
      url: "/unlike/post/" + post_id,
      data: {
        post_id: $(this).attr('post_id'),
      },
    }).done(function (res) {
      $('.like_counts' + post_id).text(countInt - 1);
    }).fail(function () {

    });
  });

  $(document).ready(function () {

    $('.edit-modal-open').on('click', function () {
      $('.js-modal').fadeIn();

      var post_title = $(this).attr('post_title');
      var post_body = $(this).attr('post_body');
      var post_id = $(this).attr('post_id');

      $('.modal-inner-title input').val(post_title);
      $('.modal-inner-body textarea').val(post_body);
      $('.edit-modal-hidden').val(post_id);

      return false;
    });

    $('.js-modal-close').on('click', function () {
      $('.js-modal').fadeOut();
      $('.error-message').remove();
      return false;
    });

    $('.edit-modal-btn input[type="submit"]').on('click', function (e) {
      e.preventDefault();
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
            $('.error-message').remove();
          }
        },
        error: function (xhr) {
          let errors = xhr.responseJSON.errors;
          $(".error-message").remove();
          if (errors) {
            $.each(errors, function (key, value) {
              $(`[name="${key}"]`).after(`<div class="text-danger error-message">${value}</div>`);
            });
          }
        }
      });
    });
  });


  $('.delete-modal-open').on('click', function () {
    $('.js-delete-modal').fadeIn();
    var post_id = $(this).attr('post_id');
    $('.delete-modal-hidden').val(post_id);
    $('.delete-form').attr('action', '/bulletin_board/delete/' + post_id);
    return false;
  });

  $('.js-modal-close').on('click', function () {
    $('.js-delete-modal').fadeOut();
    return false;
  });



});
