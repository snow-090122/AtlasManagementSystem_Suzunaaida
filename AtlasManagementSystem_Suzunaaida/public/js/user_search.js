// 検索条件の開閉＋矢印切り替え
$('.search_conditions').click(function () {
  $('.search_conditions_inner').slideToggle();

  const arrow = $(this).find('.arrow');
  const current = arrow.text();
  arrow.text(current === '▼' ? '▲' : '▼');
});

// 選択科目の開閉＋矢印切り替え
$('.subject_edit_btn').click(function () {
  const subjectBox = $(this).closest('.subject_edit_box');
  subjectBox.find('.subject_inner').slideToggle();

  const arrow = $(this).find('.arrow');
  const current = arrow.text();
  arrow.text(current === '▼' ? '▲' : '▼');
});
