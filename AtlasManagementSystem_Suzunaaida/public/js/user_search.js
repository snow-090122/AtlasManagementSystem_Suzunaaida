$('.search_conditions').click(function () {
  $('.search_conditions_inner').slideToggle();

  const arrow = $(this).find('.arrow');
  const current = arrow.text();
  arrow.text(current === '▼' ? '▲' : '▼');

  $('.subject_edit_btn').click(function () {
    const subjectBox = $(this).closest('.subject_edit_box');
    subjectBox.find('.subject_inner').slideToggle();

    // 矢印切り替え
    const arrow = $(this).find('.arrow');
    const current = arrow.text();
    arrow.text(current === '▼' ? '▲' : '▼');
  });
});
