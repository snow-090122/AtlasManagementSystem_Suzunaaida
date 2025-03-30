$(function () {
  $('.open-cancel-modal').on('click', function () {
    const date = $(this).data('date');
    const time = $(this).data('time');

    $('#modal-date').text(date);
    $('#modal-time').text(time);

    $('#input-date').val(date);

    const partMatch = time.match(/(\d+)部/);
    $('#input-part').val(partMatch ? partMatch[1] : '');

    $('#cancelModal').show();
  });

  $('.close-modal').on('click', function () {
    $('#cancelModal').hide();
  });
});
